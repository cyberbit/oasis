<?php
define("TITLE", "O.A.S.I.S. Client TEST");

// Image path
define("IMG_PATH", "img/filter.php?filename=");
define("IMG_PAD", 2);

// Image set
define("IMG_VSTART", 1);
define("IMG_HSTART", 1);
define("IMG_VMAX", 2);
define("IMG_HMAX", 8);

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
		<link href="css/app.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><?= TITLE ?></a>
				</div>
			</div>
		</nav>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 col-sm-4">
					<div id="scans" class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Available Scans</h3>
						</div>
						<div class="panel-body">
							<div class="thumbnail" data-scan-id="255,0,0">
								<a href="#" class="preview">
									<img alt="scan">
								</a>
								<div class="caption">
									<button type="button" class="btn btn-danger">Delete</button>
								</div>
							</div>
							<div class="thumbnail" data-scan-id="0,255,0">
								<a href="#" class="preview">
									<img alt="scan">
								</a>
								<div class="caption">
									<button type="button" class="btn btn-danger">Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9 col-sm-8">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-12">
							<img id="view" alt="scan" class="img-responsive center-block">
							<p></p>
							<div id="controls" class="text-center">
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
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script>
            // Load PHP config
            var config = <?= json_encode(get_defined_constants(true)['user']) ?>;
		</script>
	
		<!-- jQuery -->
		<script src="vendor/components/jquery/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="js/app.js"></script>
	</body>
</html>