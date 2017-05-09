<?php
define("TITLE", "O.A.S.I.S. Client");

// Image path
define("IMG_PATH", "img/filter.php?filename=");
define("IMG_PAD", 2);

define("SCAN_DIR", "/home/pi/scantest");
define("SCAN_SCRIPT", SCAN_DIR . "/OasisScan.py");
define("SCAN_QUALITY", "very fine"); // standard, fine, very fine

// Image parameters for demo mode
define("IMG_VSTART", 1);
define("IMG_HSTART", 1);
define("IMG_VMAX", 2);
define("IMG_HMAX", 8);

define("API_PATH", "api.php");

// Toggle demo flags
define("LOAD", false);
define("DEMO", false);
?>
