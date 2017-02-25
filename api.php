<?php
// Display errors
error_reporting(E_ALL);
ini_set("display_errors", '1');

// IDs for demo scans
$scans = ["255,0,0", "0,255,0"];

// Grab URL parameters
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$id     = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";

// Default data
$dataDefault = [
    "context" => "danger",
    "msg" => "Oops!",
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
        $newId = implode(",", [rand(0, 255), rand(0, 255), rand(0, 255)]);
        $data = [
            "context" => "success",
            "msg" => "Scan complete!",
            "data" => [
                "id" => $newId,
                "path" => "img/",
                "vMin" => 1,
                "hMin" => 1,
                "vMax" => 8,
                "hMax" => 2
            ]
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
            "msg" => "Available scans",
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
            "msg" => "Self-test complete!"
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