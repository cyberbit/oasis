<?php
// Load image
$image = imagecreatefromjpeg($_REQUEST['filename']);
$args = explode(",", $_REQUEST['args']);

// Parse filter
$filtertype = false;
$arg1;
$arg2;
$arg3;
$arg4;
switch ($_REQUEST['filter']) {
    case "colorize":
        if (isset($_REQUEST))
        $filtertype = IMG_FILTER_COLORIZE;
        
        // Parse arguments
        if (count($args) != 4) break;
        
        $arg1 = $args[0];
        $arg2 = $args[1];
        $arg3 = $args[2];
        $arg4 = $args[3];
        break;
    default:
        break;
}

// Apply filter

if ($filtertype !== false) imagefilter($image, $filtertype, $arg1, $arg2, $arg3, $arg4);

// Output image
header("Content-Type: image/jpeg");
header("Expires: ".gmdate("D, d M Y H:i:s", time()+1800)." GMT");
header("Cache-Control: max-age=1800");
imagejpeg($image);
imagedestroy($image);
?>