# O.A.S.I.S.
Client software for OASIS Corp's primary product, the Object Arc-Scanning Instructional System.

## Requirements
This software requires the following:
 - **LAMP stack**. PHP version should be 5.6.x and have the GD2 PHP extension installed. I use AMPPS (http://ampps.com/download). It's cross-platform, easy to set up, and works.
 - **Composer** (https://getcomposer.org/).

## Installation
1. Clone this repo in your web root. On AMPPS for Windows, this should be 
   
   ```bash
   $ git clone https://github.com/cyberbit/oasis.git
   ```
2. Install dependencies using Composer.
   
   ```bash
   $ composer install
   ```
   
3. Start AMPPS if it isn't already started. Wait for the AMPPS window to appear.
   
4. Navigate to the installed repository via a web browser. The location will most likely be http://localhost/oasis or something similar.

5. If the images do not load, double check that the php_gd extension is enabled in AMPPS. Click the gear icon next to **Php-5.x**, then click **PHP Extension**. Find **php_gd** and **php_gd2** in the list and select them (or one of them if only one is available). Click **Apply**.

6. Check that the images are loading correctly. If they are, success! If they are not, open an issue and I'll do my best to debug.
