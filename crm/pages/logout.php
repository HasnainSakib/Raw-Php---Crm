<?php
	include 'includes/config.php';
	if (!isset($_COOKIE['user'])) {
		header('Location: '.$base.'login/');	
	} else {
		setcookie('user','user',time()-24*60*60,"/");
		header('Location: '.$base.'login/');
	} 
?>