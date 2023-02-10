<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
	if(isset($_COOKIE['user'])) header('Location: '.$base.'dashboard/');
?>
<?php 
	$emsg = isset($_GET['emsg']) ? $_GET['emsg'] : '';
	
	 
	
	
if(isset($_POST['login'])) {		

	$username = $_POST['username'];
	$password = $_POST['password'];

	if(empty($username) || empty($password)) {
		if($username == "") {
			$emsg = "Username is required";
		} 

		if($password == "") {
			$emsg = "Password is required";
		}
	} else {
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$result = $conn->query($sql);

		if($result->num_rows == 1) {
			$password = $password;
			// exists
			$mainSql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
			$mainResult = $conn->query($mainSql);

			if($mainResult->num_rows == 1) {
				//$value = $mainResult->fetch_assoc();
				//$user_id = $value['type'];
				//$name = $value['first_name'];
                  
				  
				  $row	= $mainResult->fetch_array();
		setcookie("user", $row['token'], time() + (86400 * 30), "/");
			header('Location: '.$base.'dashboard/');
				// set session
				//$_SESSION['userId'] = $user_id;
				//$_SESSION['name'] = $name;
				
				

				
			} else{
				
				$emsg = "Incorrect username/password combination";
			} // /else
		} else {		
			$emsg = "Username doesnot exists";		
		} // /else
	} // /else not empty username // password
	
}
	
	
	
	
	
	
	
	if(isset($_POST['register'])) {
		if($_POST['password'] == $_POST['confirm']) {
			if(get_total_rows('users', "username = '".$_POST['email']."'") == 0){
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
				$fields['joined'] = date("Y-m-d H:i:s");
				$fields['mobile_number'] = "+88".$conn->real_escape_string($_POST['mobile']);
				$fields['facebook_page'] = $conn->real_escape_string($_POST['fbpage']);
				
				$sql = InsertInTable('users', $fields);
				if($conn->query($sql)) {
					$sms_text = "Thanks for joining Elance Network and Welcome !\n";
					$sms_text.= "To contact our Support Center, Call: 01709309110";
					$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
					$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($fields['mobile_number']));
					$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
					$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
					
					$sms_text = "New user register!\n ";
					$sms_text.= "Mobile number: ".$fields['mobile_number'];
					$sms_text.= "Plese check your customers https://crm.dhakasolution.com/admin/customer-list/";
					
					$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
					$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=8801914500116";
					$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
					$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
					setcookie("user", $fields['token'], time() + (86400 * 30), "/");
					header('Location: '.$base.'admin/?new&update_profic=1&smsg='.urlencode('Registration Successfull. One new lead is added !'));
				} else {$emsg = $conn->error;}
			} else {$emsg="Username Already Registered !";}
		} else {$emsg="Two password doesn't match !";}
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="<?= $base ?>dist/css/bootstrap-4.1.1.min.css" rel="stylesheet" id="bootstrap-css">
		<title>Register || Friends Network</title>
		<style>
			body {
				overflow-x: hidden;
			}
			.register{
					 background: -webkit-linear-gradient(left, #28a745, #6c757d);
					 margin-top: 3%;
					 padding: 3%;
			}
			 .register-left{
					 text-align: center;
					 color: #ddd;
					 margin-top: 4%;
			}
			 .register-left input{
					 border: none;
					 padding: 2%;
					 width: 60%;
					 background: #fff;
					 font-weight: bold;
					 margin-top: 30%;
					 margin-bottom: 3%;
					 cursor: pointer;
			}
			 .register-right{
					 background: #ddd;
			}
			 .register-left img{
					 margin-top: 15%;
					 margin-bottom: 5%;
					 width: 25%;
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
					 margin-top: 2%;
					 border: none;
					 border-radius: 1.5rem;
					 padding: 2%;
					 background: #28a745;
					 color: #fff;
					 font-weight: 600;
					 width: 50%;
					 cursor: pointer;
			}
			 .btnRegister[disabled] {
					background: #ccc;
					cursor: not-allowed;
			}
			 .register .nav-tabs{
					 margin-top: 3%;
					 border: none;
					 background: #28a745;
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
			#login form .form-group:nth-child(2) {
				margin-bottom: 0;
			}
			#login .f_pass {
				margin: 4px;
				font-size: 12px;
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
						<h3>Add Lead Form</h3>
						<p></p>
						<a href="../admin/"><input type="submit" name="" value="Home"/><br/></a>
				</div>
				<div class="col-md-9 register-right">
					<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="profile" aria-selected="true">Lead</a>
						</li>
						<!-- <li class="nav-item">
							<a class="nav-link" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="home" aria-selected="false">Login</a>
						</li> -->
					</ul>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
							<h3 class="register-heading">Registration</h3>
							<form method="POST" action="">
								<div class="row register-form">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="name" placeholder="Name *" required />
										</div>
										<div class="form-group">
											<input type="password" class="form-control" minlength="5" name="password" placeholder="Password *" required />
										</div>
										<div class="form-group">
											<input type="password" class="form-control" name="confirm" placeholder="Confirm Password *" required />
										</div>
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">facebook.com/</span>
												</div>
												<input type="text" class="form-control" name="fbpage"  placeholder="Your Facebook Page" />
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="email" class="form-control" name="email" placeholder="Your Email *" required />
										</div>
										<div class="form-group">
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text"><img src="../images/bd.svg" style="width: 16px;" />&nbsp;+88</span>
												</div>
												<input type="text" minlength="10" name="mobile" class="form-control" placeholder="Eg: 01914500116 *" required />
											</div>
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="company_name" placeholder="Your Company Name" />
										</div>
										<div class="form-group ">
											<input type="checkbox" id="term-and-condition" class="list-inline" checked="true" />
											<label for="term-and-condition" class="list-inline"> I accept <a href="">Terms And Coditions</a></label>
										</div>
										<input type="submit" class="btnRegister" name="register"  value="Add Lead"/>
									</div>
								</div>
							</form>
						</div>
						<div class="tab-pane fade show" id="login" role="tabpanel" aria-labelledby="login-tab">
							<h3 class="register-heading">Login</h3>
							<div class="row justify-content-md-center register-form">
								<div class="col-md-6">
									<form method="POST" action="">
										<div class="form-group">
											<input type="text" name="username" class="form-control" placeholder="Email *" required />
										</div>
										<div class="form-group">
											<input type="password" name="password" class="form-control" placeholder="Password *" required />
										</div>
										<p class="f_pass"><a href="#forgotModal" data-toggle="modal">Forgot password ?</a></p>
										<input type="submit" class="btnRegister" name="login" value="Login"/>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="forgotModal" role="dialogue">
			<div class="modal-dialog vertical-align-center">
				<div class="modal-content">
					<div class="modal-header">
						Forgot Your Pasword ?
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					</div>
					<div class="modal-body">
						<form id="forgotPasswordform">
							<input type="hidden" name="forgotPassword" value="1" />
							
							<div class="form-group g-form">
								<label>Your Email Or Mobile Number</label>
								<input type="text" name="username" class="form-control" />
							</div>
							<div class="form-group">
								<input type="submit" value="Find" class="btn btn-success" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<script src="<?= $base ?>dist/js/jquery-3.1.min.js"></script>
		<script src="<?= $base ?>dist/js/bootstrap-4.1.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$('#term-and-condition').change(function() {
						if($(this).is(":checked")) {
							$('input[name="register"]').prop('disabled', false);
						} else {
							$('input[name="register"]').prop('disabled', true);
						}
				});
				$('#forgotPasswordform').submit(function(e){
					var loading = '<img src="<?= $base ?>images/loding.gif"';
					loading+= 'style="display: block; width: 100px; margin: 0 auto;" />';
					$(this).parent().html(loading);
					
					$.ajax({
						type: 'POST',
						url: '<?= $base ?>ajax/',
						data: $(this).serialize(),
						success: function(data) {
							$('.modal-body').html('<p class="text-info">'+data+'</p>');
							setTimeout(function(){window.location = '<?= $base ?>login/'}, 4000);
						}
					});
					e.preventDefault();
				});
			});
		</script>
	</body>
</html>