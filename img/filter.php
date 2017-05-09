<?php
// Load image
$image = imagecreatefromjpeg($_REQUEST['filename']);

// Scale image
//$scaledImage = imagescale($image, 600, 600);
$scaledImage = $image;

$filterArray = is_array($_REQUEST['filter']) ? $_REQUEST['filter'] : [$_REQUEST['filter']];
$argsArray = is_array($_REQUEST['args']) ? $_REQUEST['args'] : [$_REQUEST['args']];

// Parse multi filters
$filters = count($filterArray) == count($argsArray) ? array_combine($filterArray, $argsArray) : [];

foreach ($filters as $filter=>$argString) {
	$args = explode(",", $argString);
	
	// Parse filter
	$filtertype = false;
	$arg1;
	$arg2;
	$arg3;
	$arg4;
	switch ($filter) {
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
		default:
			break;
	}
	
	// Apply filter
	if ($filtertype !== false) {
		// Run appropriate function, depending on number of arguments
		if (count($args) === 4) imagefilter($scaledImage, $filtertype, $arg1, $arg2, $arg3, $arg4);
		elseif (count($args) === 3) imagefilter($scaledImage, $filtertype, $arg1, $arg2, $arg3);
		elseif (count($args) === 2) imagefilter($scaledImage, $filtertype, $arg1, $arg2);
		elseif (count($args) === 1) imagefilter($scaledImage, $filtertype, $arg1);
		elseif (count($args) === 0) imagefilter($scaledImage, $filtertype);
	}
}

// Output image
header("Content-Type: image/jpeg");
header("Expires: ".gmdate("D, d M Y H:i:s", time()+1800)." GMT");
header("Cache-Control: max-age=1800");
imagejpeg($scaledImage);
imagedestroy($scaledImage);
imagedestroy($image);
?>
