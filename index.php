<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Bootstrap 101 Template</title>
	
		<!-- Bootstrap -->
		<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="css/app.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">O.A.S.I.S.</a>
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
							<div class="row">
								<div class="col-xs-12">
									<div class="thumbnail">
										<a href="#">
											<img src="img/01_01.jpg" alt="scan">
										</a>
										<div class="caption">
											<button type="button" class="btn btn-danger">Delete</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-9 col-sm-8">
					<div class="row">
						<div class="col-md-8 col-md-offset-2 col-sm-12">
							<img src="img/01_01.jpg" alt="scan" class="img-responsive">
							<p></p>
							<div class="text-center">
								<div class="btn-group btn-group-lg" role="group">
									<button type="button" class="btn btn-primary">Scan</button>
									<button type="button" class="btn btn-default">Self-test</button>
								</div>
								<div class="btn-group btn-group-lg" role="group">
									<button type="button" class="btn btn-default"><i class="glyphicon glyphicon-triangle-top"></i></button>
									<button type="button" class="btn btn-default"><i class="glyphicon glyphicon-triangle-bottom"></i></button>
									<button type="button" class="btn btn-default"><i class="glyphicon glyphicon-triangle-left"></i></button>
									<button type="button" class="btn btn-default"><i class="glyphicon glyphicon-triangle-right"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<!-- jQuery -->
		<script src="vendor/components/jquery/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
	</body>
</html>