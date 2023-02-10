<?php
	date_default_timezone_set('Asia/Dhaka');
	$base = "http://localhost/projects/crm/";
	
	$servername = ""; 
	$username 	= "root";
	$password 	= '';
	$dbname 	= "crms";
	
	$conn = new mysqli($servername, $username, $password, $dbname);
	if($conn->connect_error) {
		die("connection failed !" .  $conn->connect_error);
	}