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

//filter=negate
//$filter = "negate"
//
//filter[]=negate
//$_REQUEST['filter'] = ["negate", "colorize"]
//
//$_REQUEST = [
//	"personal" => [
//		"name" => "some value",
//		"email" => "some value"
//	],
//	"beer" => ["warthog", "guinness"]
//];

switch ($_REQUEST['filter']) {
    case "colorize":
        $filtertype = IMG_FILTER_COLORIZE;
        
        // Parse arguments
        if (count($args) != 4) break;
        
        $arg1 = $args[0];
        $arg2 = $args[1];
        $arg3 = $args[2];
        $arg4 = $args[3];
        break;
    case "brightness":
        $filtertype = IMG_FILTER_BRIGHTNESS;

        if(count($args) != 1) break;
		
        $arg1 = $args[0];
        break;
    case "contrast":
        $filtertype = IMG_FILTER_CONTRAST;

        if(count($args) != 1) break;

        $arg1 = $args[0];
        break;
    case "negate":
        $filtertype = IMG_FILTER_NEGATE;

		if(count($args) != 0) break;
		
    default:
        break;
}

// Apply filter

if ($filtertype !== false) {
    // Run appropriate function, depending on number of arguments
    if (count($args) === 4) imagefilter($image, $filtertype, $arg1, $arg2, $arg3, $arg4);
    elseif (count($args) === 3) imagefilter($image, $filtertype, $arg1, $arg2, $arg3);
    elseif (count($args) === 2) imagefilter($image, $filtertype, $arg1, $arg2);
    elseif (count($args) === 1) imagefilter($image, $filtertype, $arg1);
    elseif (count($args) === 0) imagefilter($image, $filtertype);
}

// Output image
header("Content-Type: image/jpeg");
header("Expires: ".gmdate("D, d M Y H:i:s", time()+1800)." GMT");
header("Cache-Control: max-age=1800");
imagejpeg($image);
imagedestroy($image);
?>
