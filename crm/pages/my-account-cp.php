<?php
	include "includes/config.php";
	require_once "includes/functions.php"; 
?>
<?php include "includes/config.php"; require_once "includes/functions.php"; ?>
<?php 
	if(isset($_POST['change_password'])) {
		$id = $conn->real_escape_string($_POST['userid']);
		$password = $conn->real_escape_string($_POST['password']);
		$confirm = $conn->real_escape_string($_POST['confirm']);
		if($password == $confirm) {
			$fields['password'] = $password;
			if($conn->query(UpdateTable("users", $fields, "id={$id}"))) {
				setcookie("user", null, time() - (86400 * 30),"/");
				exit(header('Location: '.$base.'login/?emsg='.urlencode('Password Successfully Changed ! Please Login Again')));
			} else {
				exit(header('Location: '.$base.'my-account/change-password/?emsg='.urlencode($conn->error)));
			}
		} else {
			exit(header('Location: '.$base.'my-account/change-password/?emsg='.urlencode('Two password doesn\'t match !')));
		}
		
		$sql = InsertInTable('contact', $fields);
		if($conn->query($sql) == TRUE) {
			exit(header('Location: '.$base.'dashboard/?smsg='.urlencode('Successfully Posted Data !')));
		} else {
			exit(header('Location: '.$base.'messages/?emsg='.urlencode($conn->error)));
		}
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper" style="min-height: 331px;">

<div class="row" style="background-color:#e7ebec;">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<h2 class="page-header"><i class="fa fa-key"></i> CHANGE PASSWORD</h2>
			
			<div class="col-md-8 col-md-offset-2">
				<div class="well">
					<h4>Chage your accout password</h4><hr/>
					<form action="" method="POST">
						<input type="hidden" name="change_password" />
						<input type="hidden" name="userid" value="<?= $userinfo['id'] ?>" />
						
						<div class="form-group">
							<label>New Pasword</label>
							<input type="password" name="password" class="form-control" />
						</div>
						<div class="form-group">
							<label>Confirm New Pasword</label>
							<input type="password" name="confirm" class="form-control" />
						</div>
						
						<div class="form-group">
							<input type="submit" value="Change" class="btn btn-success" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="dist/css/jquery-te-1.4.0.css">
<script src="js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script>$('.message-box').jqte({placeholder: "Please, write your inquiry"});</script>
</div>
<?php 
	include "includes/footer.php";
?>
<?php 
	
?>