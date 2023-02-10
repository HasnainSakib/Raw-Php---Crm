<?php
	include "includes/config.php";
	include "includes/functions.php";
	
	if(isset($_POST['forgotPassword'])) {
		$check = "SELECT * FROM users ";
		$check.= "WHERE username='".addslashes($_POST['username'])."' ";
		$check.= "OR mobile_number LIKE '%".addslashes($_POST['username'])."'";
		$check_result	= $conn->query($check);
		
		if($check_result->num_rows != 0) {
			$check_row	= $check_result->fetch_array();
			
			$email_to 		= isset($_POST['username']) ? $_POST['username'] : exit("Invalid Email !") ;
			$email_from 	= "no-reply@dhakasolution.com";
			$email_subject 	= "Forgot Your Password!";
				
			$email_message = "<img src='http://dhakasolution.com/img/logo.png' style='width: 140px' /><br/>";
			$email_message .= "<h3>Dhaka Solution Password Reset</h3><br/>";
			 
			function clean_string($string) {
			  $bad = array("content-type","bcc:","to:","cc:");
			  return str_replace($bad,"",$string);
			}
			
			$email_message .= "<b>Your Email Address: </b>".clean_string($email_to)."<br/>";
			$email_message .= "<b>Your Password: </b><span style='color: #f00;'>".clean_string($check_row['password'])."</span><br/><br/>";
			$email_message .= "Now go back to <a href='".$base."login/'> login </a> page and login again ! ";
			
			$headers = "From: " . strip_tags($email_from) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($email_from) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			if(@mail($email_to, $email_subject, $email_message, $headers)) {
				exit("Password has been sent ! Please Check your Email");
			} else {
				exit('No mail integretted !');
			}
		} else {
			exit("No User Found !");
		}
	}
	if(isset($_POST['service-register'])) {
		$fields['service'] = $conn->real_escape_string($_POST['service']);
		$fields['name'] = $conn->real_escape_string($_POST['name']);
		$fields['email'] = $conn->real_escape_string($_POST['email']);
		$fields['mobile'] = $conn->real_escape_string($_POST['mobile']);
		
		$sql = InsertInTable('forms', $fields);
		if($conn->query($sql)) {
			$sms_text = "New service registered!\n ";
			$sms_text.= "Service Type: ".addslashes($fields['service']).", Mobile number: ".addslashes($fields['mobile']);
			
			$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
			$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=8801914500116";
			$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
			$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
?>
	<a href="https://dhakasolution.com/"><img src="images/logo_full.png" class="img-responsive" /></a>
	<p><i class="fa fa-check-circle-o fa-3x" style="margin: .2em; color: #00adee;"></i><br/>Thank you for submitting form. Just One step left. Input your password to join Dhaka Solution</p>
	<form action="login/" method="post" >
		<input type="hidden" name="register" />
		<input type="hidden" name="name" value="<?= $fields['name'] ?>" />
		<input type="hidden" name="email" value="<?= $fields['email'] ?>" />
		<input type="hidden" name="mobile" value="<?= $fields['mobile'] ?>" />
		<input type="hidden" name="company_name" value="" />
		<input type="hidden" name="fbpage" value="" />
		
		<div class="form-group">
			<input type="password" name="password" class="form-control g-text" required autofocus="true" />
			<label>Password</label>
		</div>
		<div class="form-group">
			<input type="password" name="confirm" class="form-control g-text" required />
			<label>Confirm Password</label>
		</div>
		<div class="form-group">
			<input type="submit" value="Register" class="g-btn"/>
		</div>
	</form>
<?php
		}
	}
