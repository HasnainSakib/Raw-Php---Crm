

<!DOCTYPE html>
<html lang="en"> 
<head>
	<base href="<?= $base ?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Support Ticket Admin Panel For User. Developed By Dhaka Solution">
  <meta name="author" content="Limon">
  <title>Flashnet - Broadband & Internet Provider</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet" />
  <link href="dist/css/sb-admin-2.css" rel="stylesheet" />
  <link href="dist/css/limon-mod.css" rel="stylesheet" />
  <link href="vendor/morrisjs/morris.css" rel="stylesheet" />
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<div id="wrapper">
		<div class="site-message"></div>
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="admin/">Flashnet - Broadband & Internet Provider(Admin)</a>
			</div>
			<ul class="nav navbar-top-links navbar-right notification-area">
				
				
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="admin/logout/"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
					</ul>
				</li>
			</ul>

			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
						<li class="sidebar-search">
							<form class="input-group custom-search-form" id="search-form" action="admin/search/" method="get">
								<input type="search" name="q" class="form-control" placeholder="Search..." />
								<span class="input-group-btn search">
									<button class="btn btn-default" type="submit" form="search-form">
											<i class="fa fa-search"></i>
									</button>
								</span>
							</form>
						</li>
						<li><a href="admin/home.php"><i class="fa fa-home fa-fw"></i> Homepage</a></li>
						<li><a href="admin/customer-list.php"><i class="fa fa-users fa-fw"></i> Customer List</a></li>
						<li><a href="admin/orders.php"><i class="fa fa-rocket fa-fw"></i> Orders</a></li>
						<li><a href="admin/messages"><i class="fa fa-envelope fa-fw"></i> Feedback</a></li>
						<li><a href="#"><i class="fa fa-list fa-fw"></i> Invoice</a></li>
						<li><a href="admin/due-payments.php"><i class="fa fa-money fa-fw"></i> Due Payments</a></li>
						<li><a href="admin/packages.php"><i class="fa fa-cubes fa-fw"></i> Services</a></li>
						<li><a href="#"><i class="fa fa-bar-chart"></i> Report</a></li>
					</ul>
				</div>
			</div>
		</nav>
