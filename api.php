<?php
$action = $_REQUEST['action'];
$data = array();

// Default data
$dataDefault = array();

switch ($action) {
    case "scan":
        $data = array(
            "result" => "success"
        );
        break;
}

header("Content-Type: application/json");
echo json_encode($data);
die;

?>