<?php
require "config.php";

// Display errors
error_reporting(E_ALL);
ini_set("display_errors", '1');

// Helper function for forming demo scan structure
$formDemoScan = function($id) {
    return [
        "id" => $id,
        "path" => IMG_PATH,
        "pad" => IMG_PAD,
        "vMin" => IMG_VSTART,
        "hMin" => IMG_HSTART,
        "vMax" => IMG_VMAX,
        "hMax" => IMG_HMAX
    ];
};

// Simulate load
for($i=0;$i<20000000;$i++);

// Start session
session_start();

// Set up demo scans
if (!isset($_SESSION['scans'])) $_SESSION['scans'] = ["255,0,0", "0,255,0"];

// Demo scans
$scans = array_map($formDemoScan, $_SESSION['scans']);

// Grab URL parameters
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$id     = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
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
        // Generate random ID (color)
        $newId = implode(",", [rand(-255, 255), rand(-255, 255), rand(-255, 255)]);
        
        // Add scan to session
        $_SESSION['scans'][] = $newId;
        
        // Form output
        $data = [
            "context" => "success",
            "msg" => "Scanned!",
            "data" => $formDemoScan($newId)
        ];
        
        $success = true;
        
        break;
    
    /**
     * Request list of scans.
     *
     * Requires:
     *      No parameters.
     *
     * Returns (on success):
     *      id      Array of available scan IDs.
     */
    case "scans":
        $data = [
            "context" => "success",
            "data" => $scans
        ];
        
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
        $data = [
            "context" => "success",
            "msg" => "Done!"
        ];
        
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