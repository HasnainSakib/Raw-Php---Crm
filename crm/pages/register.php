<?php
	include "includes/config.php";
	require_once "includes/functions.php";
	if(isset($_COOKIE['user'])) header('Location: '.$base.'dashboard/');
?>
<?php 
	$emsg = isset($_GET['emsg']) ? $_GET['emsg'] : '';
	if(isset($_POST['login'])) {
		$username = $conn->real_escape_string(strip_tags($_POST['username']));
		$password = $conn->real_escape_string(strip_tags($_POST['password']));
			
		$get 		= "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}'";
		$result 	= $conn->query($get) or trigger_error($conn->error." [$get]");
		$user_num 	= $result->num_rows;
		
		if ($user_num == 1) {
			$row	= $result->fetch_array();
			setcookie("user", $row['token'], time() + (86400 * 30), "/");
			header('Location: '.$base.'dashboard/');
		} else {
			$emsg = 'Incorrect Username or Password !!'; 
		}		
	}
	if(isset($_POST['register'])) {
		if($_POST['password'] == $_POST['confirm']) {
			if(get_total_rows('users', $_POST['username']) == 0) {
				$user_ip = getenv('REMOTE_ADDR');
				$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip={$user_ip}"));
				
				$fields['username'] = $conn->real_escape_string($_POST['email']);
				$fields['password'] = $conn->real_escape_string($_POST['password']);
				$fields['token'] = random_token();
				$fields['first_name'] = $conn->real_escape_string($_POST['name']);
				$fields['last_name'] = '';
				$fields['email'] = $conn->real_escape_string($_POST['email']);
				$fields['address'] = $conn->real_escape_string($_POST['company_name']);
				$fields['city'] = $geo["geoplugin_city"];
				$fields['district'] = $geo["geoplugin_regionName"];
				$fields['postalcode'] = $geo["geoplugin_areaCode"];
				$fields['mobile_number'] = $conn->real_escape_string($_POST['mobile']);
				$fields['facebook_page'] = $conn->real_escape_string($_POST['fbpage']);
				
				$sql = InsertInTable('users', $fields);
				if($conn->query($sql)) {
					setcookie("user", $fields['token'], time() + (86400 * 30), "/");
					header('Location: '.$base.'my-account/?new&update_profic=1');
				} else {$emsg = $conn->error;}
			} else {$emsg = "Username already registered!";}
		} else {$emsg = "Two password doesn't match !";}
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<title>Register || Dhaka Solution</title>
		<style>
			body {
				overflow-x: hidden;
			}
			.register{
					 background: -webkit-linear-gradient(left, #3931af, #00c6ff);
					 margin-top: 3%;
					 padding: 3%;
			}
			 .register-left{
					 text-align: center;
					 color: #fff;
					 margin-top: 4%;
			}
			 .register-left input{
					 border: none;
					 border-radius: 1.5rem;
					 padding: 2%;
					 width: 60%;
					 background: #f8f9fa;
					 font-weight: bold;
					 color: #383d41;
					 margin-top: 30%;
					 margin-bottom: 3%;
					 cursor: pointer;
			}
			 .register-right{
					 background: #f8f9fa;
					 border-top-left-radius: 10% 50%;
					 border-bottom-left-radius: 10% 50%;
			}
			 .register-left img{
					 margin-top: 15%;
					 margin-bottom: 5%;
					 width: 25%;
					 -webkit-animation: mover 2s infinite alternate;
					 animation: mover 1s infinite alternate;
			}
			 @-webkit-keyframes mover {
					 0% {
							 transform: translateY(0);
					}
					 100% {
							 transform: translateY(-20px);
					}
			}
			 @keyframes mover {
					 0% {
							 transform: translateY(0);
					}
					 100% {
							 transform: translateY(-20px);
					}
			}
			 .register-left p{
					 font-weight: lighter;
					 padding: 12%;
					 margin-top: -9%;
			}
			 .register .register-form{
					 padding: 10%;
					 margin-top: 10%;
			}
			 .btnRegister{
					 float: right;
					 margin-top: 10%;
					 border: none;
					 border-radius: 1.5rem;
					 padding: 2%;
					 background: #0062cc;
					 color: #fff;
					 font-weight: 600;
					 width: 50%;
					 cursor: pointer;
			}
			 .register .nav-tabs{
					 margin-top: 3%;
					 border: none;
					 background: #0062cc;
					 border-radius: 1.5rem;
					 width: 28%;
					 float: right;
			}
			 .register .nav-tabs .nav-link{
					 padding: 2%;
					 height: 34px;
					 font-weight: 600;
					 color: #fff;
					 border-top-right-radius: 1.5rem;
					 border-bottom-right-radius: 1.5rem;
			}
			 .register .nav-tabs .nav-link:hover{
					 border: none;
			}
			 .register .nav-tabs .nav-link.active{
					 width: 110px;
					 color: #0062cc;
					 border: 2px solid #0062cc;
					 border-top-left-radius: 1.5rem;
					 border-bottom-left-radius: 1.5rem;
			}
			 .register-heading{
					 text-align: center;
					 margin-top: 8%;
					 margin-bottom: -15%;
					 color: #495057;
			}
			@media (max-width: 420px) {
				.register .nav-tabs {
					float: none;
					width: 66%;
					margin: 3% auto;
				}
				#myTab .nav-item a {
					font-size: 14px
				}
			}
		</style>
	</head>
	<body>
		<div class="container register">
			<?php if(isset($emsg) && !empty($emsg)) { ?>
			<div class="alert alert-danger"><?= $emsg ?></div>
			<?php } ?>
			<div class="row">
				<div class="col-md-3 register-left d-none d-sm-block">
					<img src="https://image.ibb.co/n7oTvU/logo_white.png" alt=""/>
					<h3>Welcome</h3>
					<p>Dhaka Solution</p>
					<a href="http://dhakasolution.com/"><input type="submit" name="" value="Home"/><br/></a>
				</div>
				
				<div class="col-md-9 register-right">
					<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
							<li class="nav-item">
									<a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="home" aria-selected="true">Login</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="profile" aria-selected="false">Registration</a>
							</li>
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
							<h3 class="register-heading">Login</h3>
							<div class="row justify-content-md-center register-form">
								<div class="col-md-6">
									<form method="POST" action="">
										<div class="form-group">
											<input type="email" name="username" class="form-control" placeholder="Email *" required />
										</div>
										<div class="form-group">
											<input type="password" name="password" class="form-control" placeholder="Password *" required />
										</div>
										<input type="submit" class="btnRegister" name="login" value="Login"/>
									</form>
								</div>
							</div>
						</div>
						<div class="tab-pane fade show" id="register" role="tabpanel" aria-labelledby="register-tab">
							<h3 class="register-heading">Registration</h3>
							<form method="POST" action="">
								<div class="row register-form">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="name" placeholder="Name *" required />
										</div>
										<div class="form-group">
											<input type="password" class="form-control" name="password" placeholder="Password *" required />
										</div>
										<div class="form-group">
											<input type="password" class="form-control" name="confirm" placeholder="Confirm Password *" required />
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="fbpage"  placeholder="Your Facebook Page" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="email" class="form-control" name="email" placeholder="Your Email *" required />
										</div>
										<div class="form-group">
											<input type="text" minlength="10" name="mobile" class="form-control" placeholder="Your Phone *" required />
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="company_name" placeholder="Your Company Name" />
										</div>
										<input type="submit" class="btnRegister" name="register"  value="Register"/>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	</body>
</html>