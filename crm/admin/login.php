<?php
	include "../pages/includes/config.php";
	if(isset($_COOKIE['aDmTkn'])) header('Location: '.$base.'admin/dashboard/');
?>
<?php 
$emsg = isset($_GET['emsg']) ? $_GET['emsg'] : '';








if($_SERVER['REQUEST_METHOD'] == 'POST') {		

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
		$sql = "SELECT * FROM admins WHERE username = '$username'";
		$result = $conn->query($sql);

		if($result->num_rows == 1) {
			$password = $password;
			// exists
			$mainSql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
			$mainResult = $conn->query($mainSql);

			if($mainResult->num_rows == 1) {
				//$value = $mainResult->fetch_assoc();
				//$user_id = $value['type'];
				//$name = $value['first_name'];
                  
				  
				  $row	= $mainResult->fetch_array();
		setcookie("aDmTkn", $row['token'], time() + (86400 * 30), "/");
		header('Location:  '.$base.'admin/dashboard/');
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?= $base ?>admin/login" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="MD Jahid Khan Limon">

	<title>Admin Panel || Dhaka Solution</title>
	<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
	<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
	<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Admin Login</h3>
                    </div>
                    <div class="panel-body">
											<?php if(isset($emsg) && !empty($emsg)) { ?>
												<div class="alert alert-danger">
													<?= htmlspecialchars($emsg) ?>
												</div>
											<?php } ?>
                        <form role="form" method='POST' action="">
													<fieldset>
														<div class="form-group">
															<input class="form-control" placeholder="Username" name="username" type="text" autofocus>
														</div>
														<div class="form-group">
															<input class="form-control" placeholder="Password" name="password" type="password" value="">
														</div>
														<div class="checkbox">
															<label>
																	<input name="remember" type="checkbox" value="Remember Me" checked disabled>Remember Me
															</label>
														</div>
														<input type="submit" class="btn btn-lg btn-success btn-block" value="Login" />
													</fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>