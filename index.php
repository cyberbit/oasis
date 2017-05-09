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
		<link href="css/bootstrap-slider.min.css" rel="stylesheet">
		<link href="css/app.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><?= TITLE ?></a>
				</div>
				<ul class="nav navbar-nav">
					<li class="active"><a href="/oasis/">Home</a></li>
					<li><a href="/oasis/multifilter.php">Multi-filter Test</a></li>
				</ul>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 col-sm-4">
					<div id="scans" class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Available Scans <span class="badge"></span></h3>
						</div>
						<div class="panel-body">
							<h3 class="empty panel-msg text-center">No scans available. Press <strong>Scan</strong> to request a new scan.</h3>
						</div>
					</div>
				</div>
				<div class="col-md-9 col-sm-8">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-12">
							<img id="view" alt="scan" class="img-responsive center-block">
							<p></p>
							<div id="controls" class="text-center">
								<h3 class="scan-id"></h3>
								<div class="btn-group btn-group-lg" role="group">
									<button id="scan" type="button" class="btn btn-primary">Scan</button>
									<button id="test" type="button" class="btn btn-default">Self-test</button>
								</div>
								<div class="rotate btn-group btn-group-lg" role="group">
									<button type="button" class="btn btn-default" data-vdelta="+1"><i class="glyphicon glyphicon-triangle-top"></i></button>
									<button type="button" class="btn btn-default" data-vdelta="-1"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
									<button type="button" class="btn btn-default" data-hdelta="-1"><i class="glyphicon glyphicon-triangle-left"></i></button>
									<button type="button" class="btn btn-default" data-hdelta="+1"><i class="glyphicon glyphicon-triangle-right"></i></button>
								</div>
							</div>
							<p></p>
							<div>
								<div id="controls" class="text-center">
									<div class="panel-heading">
										<h3 class="panel-title">Brightness</h3>
									<input id="brightness" data-slider-id='ex1Slider' type="text" data-slider-min="-255" data-slider-max="255" data-slider-step="1" data-slider-value="0"/>
									</div>
								</div>
							</div>
							<div>
								<div id="controls" class="text-center">
									<div class="panel-heading">
										<h3 class="panel-title">Contrast</h3>
									<input id="contrast" data-slider-id='ex2Slider' type="text" data-slider-min="-100" data-slider-max="100" data-slider-step="1" data-slider-value="0"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="factory">
			<div class="scan-thumbnail thumbnail" data-scan-id="">
				<a href="#" class="preview">
					<img alt="scan">
				</a>
				<div class="caption">
					<h4 class="scan-id">Scan ID</h4>
					<button type="button" class="delete btn btn-danger">Delete</button>
				</div>
			</div>
			<h3 class="scan-loading panel-msg text-center"><i class="glyphicon glyphicon-hourglass"></i> Loading scans...</h3>
		</div>
		
		<script>
            // Load PHP config
            var config = <?= json_encode(get_defined_constants(true)['user']) ?>;
		</script>
	
		<!-- jQuery -->
		<script src="vendor/components/jquery/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="js/bootstrap-slider.min.js"></script>
		<script src="js/app.js"></script>
	</body>
</html>