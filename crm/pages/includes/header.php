<?php
	if(isset($_COOKIE['user'])) {
		$userinfo = get_user_by_token($_COOKIE['user'], 'users');
		if($userinfo === 0){
			setcookie("user", null, time() - (86400 * 30),"/");
			header('Location: '.$base.'login/?emsg='.urlencode('Invalid Token or User Deleted !'));
		} 
	} else {
		header('Location: '.$base.'login/');
	}
?>
<?php 
	$new_support_ticket = get_notifications($userinfo['username'], 'new_support_ticket'); $nst = ($new_support_ticket > 0) ? 'list-group-item-danger' : null;
	$total_due = get_notifications($userinfo['username'], 'total_due'); $tdc = ($total_due > 0) ? 'list-group-item-danger' : null;
	$total_notification = $new_support_ticket+$total_due;
?>
<?php ((file_exists('../images/users/'.$userinfo['id'].'-ppic.png'))) ? $userimg = 'images/users/'.$userinfo['id'].'-ppic.png' : $userimg= 'images/male-icon.png'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?= $base ?>" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Support Ticket Admin Panel For User. Developed By Elance Network">
  <meta name="author" content="Sangita">
  <title>Flashnet-Broadband & Internet Provider</title>
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet" />
  <link href="dist/css/sb-admin-2.css" rel="stylesheet" />
  <link href="dist/css/limon-mod.css" rel="stylesheet" />
  <link href="vendor/morrisjs/morris.css" rel="stylesheet" />
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <!-- <style type="text/css">
  	.navbar-default {
	    background-color: #002b73 !important;
	    border-color: #e7e7e7;
	}
	
	.page-header {
	    padding-bottom: 9px;
	    margin: 40px 0 20px;
	    border-bottom: 1px solid #eee;
	    background-color: #dbdbdb !important;
	    padding: 12px;
	}
	.sidebar ul li a.active {
	    background-color: #b16f00;
	}
	a {
	    color: #ffffff;
	    text-decoration: none;
	}
  </style> -->
  <style type="text/css">
  	
	/* .navbar-default {
	    background-color: #346059 !important;
	    border-color: #0dbf07;
	} */
	.navbar-default {
    background-color: grey !important;
    border-color: grey;
}
	.page-header {
	    padding-bottom: 9px;
	    margin: 40px 0 20px;
	    border-bottom: 1px solid #eee;
	    background-color: #dbdbdb !important;
	    padding: 12px;
	}
	.sidebar ul li a.active {
    	background-color: #19b57e;
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
				<a class="navbar-brand" href=""><img src="images/logo.png" alt="logo" style="color:white !important;" /> Flashnet-Broadband & Internet Provider</a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<ul class="nav navbar-top-links navbar-right notification-area">
				<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-bell fa-fw"></i><?php if($total_notification>0) { ?> <span class="badge"><?= $total_notification ?></span><?php } ?><i class="fa fa-caret-down"></i>
						</a>
						<ul class="dropdown-menu dropdown-alerts">
						<?php if($total_due > 0){ ?>
							<li>
								<a href="my-account/">
									<div>
										<i class="fa fa-money fa-fw"></i> <strong><?= user_at_a_glance($userinfo['username'], 'total_due') ?></strong> Total Due
										<span class="pull-right text-muted small">View Orders</span>
									</div>
								</a>
							</li>
						<?php } ?>
						</ul>
				</li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<img src="<?= $userimg ?>" class="user-image-top" /> <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
						<li><a href="my-account/"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
						<li class="divider"></li>
						<li><a href="logout/"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
			<div class="clearfix"></div>
		</nav>
		<div class="navbar-default sidebar" id="hf4" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav" id="side-menu">
					<li class="sidebar-search">
					</li>
					<li><a href="orders/create-new/?service=1"><i class="fa fa-rocket fa-fw"></i> Create New Order / Tickets</a></li>
					<li><a href="my-orders/"><i class="fa fa-list-alt  fa-fw"></i> My Orders</a></li>
					<li><a href="messages/"><i class="fa fa-envelope fa-fw"></i> Feedback/Quotes</a></li>
					<li><a href="my-invoice/"><i class="fa fa-list fa-fw"></i> Invoice</a></li>
					<li>
						<a href="#"><i class="fa fa-user fa-fw"></i> Account<span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="my-account/">My Account</a></li>
							<li><a href="my-account/change-password/">Change Password</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<?php if(isset($_GET['emsg']) || isset($_GET['smsg'])) { ?>
		<?php 
			if(isset($_GET['emsg'])) {adminMessage('#ef9898', $_GET['emsg']);}
			else {adminMessage('#98ef98', $_GET['smsg']);}
		?>
		<?php } ?>