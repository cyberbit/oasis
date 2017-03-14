<?php
require "config.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= TITLE ?></title>
	
		<!-- Bootstrap -->
		<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<style>
			h1 {
				text-align: center;
			}
			
			table.img-grid {
				margin: auto;
				border-spacing: 2em;
				border-collapse: separate;
			}
			
			table.img-grid td {
				width: 200px;
				height: 200px;
				background-position: center;
				background-size: cover;
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><?= TITLE ?></a>
				</div>
				<ul class="nav navbar-nav">
					<li><a href="/oasis/">Home</a></li>
					<li class="active"><a href="/oasis/multifilter.php">Multi-filter Test</a></li>
				</ul>
			</div>
		</nav>
		<div class="container-fluid">
			<h1 id="hover">Hover an image to inspect the filters.</h1>
			<table class="img-grid">
				<tr><td data-filter="brightness:-51,contrast:-20"></td><td data-filter="brightness:0,contrast:-20"></td><td data-filter="brightness:51,contrast:-20"></td></tr>
				<tr><td data-filter="brightness:-51,contrast:0"  ></td><td data-filter="brightness:0,contrast:0"></td><td data-filter="brightness:51,contrast:0"  ></td></tr>
				<tr><td data-filter="brightness:-51,contrast:20" ></td><td data-filter="brightness:0,contrast:20" ></td><td data-filter="brightness:51,contrast:20" ></td></tr>
			</table>
		</div>
		
		<script>
            // Load PHP config
            var config = <?= json_encode(get_defined_constants(true)['user']) ?>;
		</script>
	
		<!-- jQuery -->
		<script src="vendor/components/jquery/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script>
			$(function() {
				// Iterate image grid
				$("table.img-grid td").each(function() {
					var $this = $(this);
					var data = $this.data("filter");
					
					// Parse filter string
					var filters = (data ? data.split(",") : []);
					var imageString = config.IMG_PATH + "01_01.jpg";
					
					// Create parameter array
					var params = {
						filter: [],
						args: []
					};
					$.each(filters, function(i, v) {
						var split = v.split(":");
						
						params.filter.push(split[0]);
						params.args.push(split[1]);
					});
					
					// Apply filters to background image
					$this.css("background-image", "url(" + imageString + "&" + $.param(params) + ")");
				});
				
				// Image hover handler
				$("table.img-grid td").hover(function() {
					$("#hover").text($(this).data("filter"));
				});
			});
		</script>
	</body>
</html>