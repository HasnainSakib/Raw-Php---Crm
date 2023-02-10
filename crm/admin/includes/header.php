<?php
	if(isset($_COOKIE['aDmTkn'])) {
		$userinfo = get_user_by_token($_COOKIE['aDmTkn'], 'admins');
		if($userinfo === 0){
			setcookie("aDmTkn", null, time() - (86400 * 30),"/");
			//header('Location: '.$base.'admin/login/emsg='.urlencode('Invalid Token or Admin Deleted !'));
		}
	} else { 
		header('Location: '.$base.'admin/login/');
	}
?>
<?php 
	$new_message = get_notifications('admin', 'new_message'); $nmc = ($new_message > 0) ? 'list-group-item-danger' : null;
	$new_support_ticket = get_notifications('admin', 'new_support_ticket'); $nst = ($new_support_ticket > 0) ? 'list-group-item-danger' : null;
	$new_user_registered = get_notifications('admin', 'new_user_registered'); $nur = ($new_user_registered > 0) ? 'list-group-item-danger' : null;
	$new_order = get_notifications('admin', 'new_order'); $nor = ($new_order > 0) ? 'list-group-item-danger' : null;
	$new_payment = get_notifications('admin', 'new_payment'); $npr = ($new_payment > 0) ? 'list-group-item-danger' : null;
	$total_notification = $new_message+$new_support_ticket+$new_user_registered+$new_order; $total_unread_message = get_message(9999, "admin_read = 0")->num_rows ;
?>
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
  <style type="text/css">
  	.sidebar ul li a.active {
	    background-color: #b16f00;
	}
	a {
	    color: #ffffff;
	    text-decoration: none;
	}

	

  </style>



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
						<i class="fa fa-envelope fa-fw"></i><?php if($total_unread_message > 0){ ?><span class="badge"><?= $total_unread_message ?></span><?php } ?><i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-messages">
					<?php
						$result_messages	= get_message(3, "admin_read = '0'");
						while($row_message = $result_messages->fetch_array()) {
					?>
						<li>
							<a href="admin/messages.php">
								<div>
									<strong><?php echo $row_message['name']; ?></strong>
									<span class="pull-right text-muted">
											<em><?php echo time_elapsed_string($row_message['date']); ?></em>
									</span>
								</div>
								<div>Email: <?php echo $row_message['email']; ?></div>
							</a>
						</li>
						<li class="divider"></li>
					<?php
						}
						mysqli_free_result($result_messages);
					?>
						<li>
							<a class="text-center" href="admin/messages.php">
								<strong>See All Messages</strong>
								<i class="fa fa-angle-right"></i>
							</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="javascript.void(0)">
							<i class="fa fa-bell fa-fw"></i><?php if($total_notification > 0){ ?><span class="badge"><?= $total_notification ?></span><?php } ?><i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-alerts">
						<?php if($new_user_registered > 0){ ?>
							<li>
								<a href="admin/customer-list.php">
									<div>
										<i class="fa fa-users fa-fw"></i> <strong><?= $new_user_registered ?></strong> New User
										<span class="pull-right text-muted small">View All</span>
									</div>
								</a>
							</li>
							<li class="divider"></li>
						<?php } ?>
						<?php if($new_order > 0){ ?>
							<li>
									<a href="admin/orders.php">
											<div>
													<i class="fa fa-shopping-cart fa-fw"></i> <strong><?= $new_order ?></strong> New Order
													<span class="pull-right text-muted small">View All</span>
											</div>
									</a>
							</li>
							<li class="divider"></li>
						<?php } ?>
						<?php if($new_payment > 0){ ?>
							<li>
									<a href="admin/due-payments.php">
											<div>
													<i class="fa fa-money fa-fw"></i> <strong><?= $new_payment ?></strong> New Payment Received
													<span class="pull-right text-muted small">View All</span>
											</div>
									</a>
							</li>
						<?php } ?>
						</ul>
				</li>
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
						<li><a href="admin/messages.php"><i class="fa fa-envelope fa-fw"></i> Feedback/Quotes</a></li>
						<li><a href="admin/my-invoice"><i class="fa fa-list fa-fw"></i> Invoice/Reports</a></li>
						<li><a href="admin/due-payments.php"><i class="fa fa-money fa-fw"></i> Due Payments</a></li>
						<li><a href="admin/packages.php"><i class="fa fa-cubes fa-fw"></i> Services/Tickets</a></li>
						<li><a href="admin/my-account"><i class="fa fa-bar-chart"></i> Accounts</a></li>
					</ul>
				</div>
			</div>
		</nav>
	<?php 
		if(isset($_GET['emsg']) || isset($_GET['smsg'])) {
			if(isset($_GET['emsg'])) {adminMessage('#ef9898', $_GET['emsg']);}
			else {adminMessage('#98ef98', $_GET['smsg']);}
		}
	?>