# O.A.S.I.S.
Client software for OASIS Corp's primary product, the Object Arc-Scanning Instructional System.

## Requirements
This software requires the following:
 - **LAMP stack**. PHP version should be 5.6.x and have the GD2 PHP extension installed. I use AMPPS (http://ampps.com/download). It's cross-platform, easy to set up, and works.
 - **Composer** (https://getcomposer.org/).

## Installation
1. Clone this repo in your web root.
   
   ```bash
   $ git clone https://github.com/cyberbit/oasis.git
   ```
2. Install dependencies using Composer.
   
   ```bash
   $ composer install
   ```

3. Navigate to the installed repository via a web browser. The location will most likely be http://localhost/oasis or something similar.
