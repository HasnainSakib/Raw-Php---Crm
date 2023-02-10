<?php
	include "../pages/includes/config.php";
	if (!isset($_COOKIE['aDmTkn'])) {
		header('Location: '.$base.'admin/login/');	
	} else { 
		setcookie('aDmTkn', null,time()-24*60*60,"/");
		header('Location: '.$base.'admin/login/');
	}
?>