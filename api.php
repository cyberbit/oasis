<?php
require "config.php";

// Display errors
error_reporting(E_ALL);
ini_set("display_errors", '1');

// Helper function for forming demo scan structure
$formDemoScan = function($id) {
    $imgPath = ($id == "Oasis" ? IMG_PATH."OASIS/oasis_test" : IMG_PATH);
    
    return [
        "id" => $id,
        "filters" => $_SESSION['filters'][$id],
        "path" => $imgPath,
        "pad" => IMG_PAD,
        "vMin" => IMG_VSTART,
        "hMin" => IMG_HSTART,
        "vMax" => IMG_VMAX,
        "hMax" => IMG_HMAX
    ];
};

// Helper function for forming actual scan structure
$formScan = function($path) {
    // Check path. If invalid, return error
    if (!is_dir($path)) return false;
    
    // Get scan ID
    $scanId = array_reverse(explode(DIRECTORY_SEPARATOR, $path))[0];
    
    // Read list of images from path
    $dir = array_values(array_diff(scandir($path), [".", ".."]));
    
    /**
     * Parse metadata from image list:
     *      id      Parsed from path.
     *      path    As passed to function.
     *      pad     Kevin is hard-coding to 2.
     *      
     *      vMin    All of these will be calculated
     *      hMin    based on the minimum and maximum
     *      vMax    values for each position in the
     *      hMax    image filename.
     */
    
    // Scan is empty
    if (empty($dir)) return false;
    
    // Parse image list into number list
    $parse = array_map(function($v) {
        $numbers = explode("_", explode(".", str_replace("Oasis", "", $v))[0]);
        
        foreach ($numbers as &$num) $num = intval($num);
        
        return $numbers;
    }, $dir);
    
    // Determine minimum and maximum for each column
    $vArray = array_column($parse, 0);
    $hArray = array_column($parse, 1);
    
    $vMin = min($vArray);
    $vMax = max($vArray);
    $hMin = min($hArray);
    $hMax = max($hArray);
    
    // Return data structure
    return [
        "id" => $scanId,
        "path" => dirname($path),
        "pad" => 2,
        "vMin" => $vMin,
        "vMax" => $vMax,
        "hMin" => $hMin,
        "hMax" => $hMax
    ];
};

// Simulate load
if (LOAD) for($i=0;$i<20000000;$i++);

// Start session
session_start();

// Set up demo scans and filters
if (DEMO and !isset($_SESSION['scans'])) $_SESSION['scans'] = ["demo1", "demo2"];
if (DEMO and !isset($_SESSION['filters'])) $_SESSION['filters'] = [
    "demo1" => [["colorize", [255, 0, 0, 64]]],
    "demo2" => [["colorize", [0, 255, 0, 64]]]
];

// Grab URL parameters
$action = isset($_REQUEST['action'])  ? $_REQUEST['action']  : "";
$id     = isset($_REQUEST['id'])      ? $_REQUEST['id']      : "";
$cmd    = isset($_REQUEST['command']) ? $_REQUEST['command'] : "";

// Default data
$dataDefault = [
    "context" => "danger"
];
$data = [];
$success = false;

switch ($action) {
    /**
     * Request a new or existing scan.
     *
     * Requires:
     *      id      Identifier for the scan. If falsy,
     *              request a new scan via Python, and
     *              return the metadata when complete.
     *              If truthy, check if scan exists. If
     *              it exists, return the scan info. If
     *              it doesn't exist, return an error.
     *
     * Returns (on success):
     *      id      Unique identifier for the scan.
     *      path    Path to image folder.
     *      pad     Zero-padding for numbers in path.
     *      vMin    Minimum vertical index.
     *      hMin    Minimum horizontal index.
     *      vMax    Maximum vertical index. 
     *      hMax    Maximum horizontal index.
     */
    case "scan":
        if (DEMO) {
            // Generate random filter parameters and ID
            $color = [rand(-255, 255), rand(-255, 255), rand(-255, 255), 64];
            $newId = substr(sha1(implode(",", $color)), 0, 7);
            
            // Add scan to session
            $_SESSION['scans'][] = $newId;
            
            // Add demo filters to session
            $_SESSION['filters'][$newId] = [["colorize", $color]];
            
            // Form output
            $data = [
                "context" => "success",
                "msg" => "Scanned!",
                "data" => $formDemoScan($newId)
            ];
        }
        
        else {
            // Command to run
            $exec = "python ".SCAN_SCRIPT." -i ".SCAN_DIR." -t \"".SCAN_QUALITY."\" 2>&1";
            
            // Request scan via Python
            exec($exec, $output, $return);
            
            // Some error occurred
            if ($return) {
                $data = [
                    "msg" => "Oops!",
                    "error" => [
                        "exec" => $exec,
                        "output" => $output,
                        "return" => $return
                    ]
                ];
            }
            
            // No errors
            else {
                // Form scan based on path returned by Python
                $scan = $formScan($output[0]);
                
                // Form output
                $data = [
                    "context" => "success",
                    "msg" => "Scanned!",
                    "data" => $scan
                ];
                
                $success = true;
            }
        }
        
        break;
    
    /**
     * Request list of scans.
     *
     * Requires:
     *      No parameters.
     *
     * Returns (on success):
     *      id      Array of available scans, fully formed.
     */
    case "scans":
        if (DEMO) {
            // Demo scans
            $scans = array_map($formDemoScan, $_SESSION['scans']);
            $data = [
                "context" => "success",
                "data" => $scans
            ];
        }
        
        else {
            // Read list of directories from master scan directory
            $scanIDs = array_values(array_filter(array_diff(scandir(SCAN_DIR), [".", ".."]), function($v) {
                return strstr($v, "scan_");
            }));
            
            // Prepend scan directories with full scan path
            $scanDirs = array_map(function($id) {
                return SCAN_DIR."/".$id;
            }, $scanIDs);
            
            // Iterate scan list and form scan for each item
            $scans = array_values(array_filter(array_map($formScan, $scanDirs)));
            
            // Return scan list
            $data = [
                "context" => "success",
                "data" => $scans,
                "debug" => [
                    "scanIDs" => $scanIDs,
                    "scanDirs" => $scanDirs
                ]
            ];
        }
        
        $success = true;
        
        break;
    
    /**
     * Request a hardware self-test.
     *
     * Requires:
     *      No parameters.
     *
     * Returns (on success):
     *      Standard success message.
     */
    case "selftest":
        if (DEMO) {
            $data = [
                "context" => "success",
                "msg" => "Done!"
            ];
        }
        
        else {
            // Request self-test via Python
            
            // Return any output from script
            $data = [
                "context" => "success",
                "msg" => "Done!",
                "data" => ["change" => 2, "script" => "output"]
            ];
        }
        
        $success = true;
        
        break;
    
    /**
     * Request to delete a scan.
     *
     * Requires:
     *      id      Identifier for the scan.
     *
     * Returns (on success):
     *      id      Identifier of the deleted scan.
     *      images  Number of images deleted.
     */
    case "delete":
        if (DEMO) {
            // Delete scan
            $_SESSION['scans'] = array_diff($_SESSION['scans'], [$id]);
            
            $data = [
                "context" => "success",
                "msg" => "Deleted scan '$id'",
                "data" => [
                    "id" => $id,
                    "images" => 16
                ]
            ];
        }
        
        else {
            // Find directory of images
            
            // Count images in directory
            
            // Delete all images
            
            // Delete directory
            
            // Return appropriate information
            $data = [
                "context" => "success",
                "msg" => "Deleted scan '$id'",
                "data" => ["id" => "and numDeleted"]
            ];
        }
        
        
        $success = true;
        
        break;
    
    /**
     * Run command with Python. Note that this is for
     * testing purposes only.
     *
     * Requires:
     *      command Command to run. Must not include
     *              double quotes.
     *
     * Returns (on success):
     *      output  Command output. Each line is an
     *              entry in the array.
     *      return  Return of the command (exit code).
     */ 
    case "python":
        exec("python -c \"$cmd\"", $output, $return);
        
        $data = [
            "context" => "success",
            "msg" => "Python command \"$cmd\" run.",
            "data" => [
                "output" => $output,
                "return" => $return
            ]
        ];
        
        $success = true;
        
        break;
    
    /**
     * Test parsing directory of pictures for metadata.
     */
    case "parseTest":
        // Grab
        $dir = array_values(array_diff(scandir(dirname(IMG_PATH)."/oasis_test"), [".", ".."]));
        
        // Parse image list into number list
        $parse = array_map(function($v) {
            $numbers = explode("_", explode(".", $v)[0]);
            
            foreach ($numbers as &$num) $num = intval($num);
            
            return $numbers;
        }, $dir);
        
        // Determine minimum and maximum for each column
        
        $vArray = array_column($parse, 0);
        $hArray = array_column($parse, 1);
        
        $vMin = min($vArray);
        $vMax = max($vArray);
        $hMin = min($hArray);
        $hMax = max($hArray);
        
        $data = [
            "context" => "success",
            "msg" => "Parsed directory!",
            "data" => [
                "vMin" => $vMin,
                "vMax" => $vMax,
                "hMin" => $hMin,
                "hMax" => $hMax
            ]
        ];
        
        $success = true;
        
        break;
    
    /**
     * Show phpinfo() page.
     *
     * Requires:
     *      No parameters.
     *
     * Returns:
     *      phpinfo() page.
     */
    case "phpinfo":
        phpinfo();
        die;
        
        break;
    
    // Unknown action
    default:
        $data = [
            "error" => "Unknown action: '$action'"
        ];
        
        break;
}

// Add $success to output
$data['success'] = $success;

// Output result
header("Content-Type: application/json");
echo json_encode(array_merge($dataDefault, $data));
die;

?>
