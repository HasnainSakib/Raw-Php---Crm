<?php
	function get_notifications($username, $type) {
		switch($type) {
			case 'new_message' : 
				$messages = get_some_data("contact", "admin_read = '0'");
				return $messages->num_rows; break;
			case 'new_order' : 
				$orders = get_some_data("customer_orders", "status = '1'");
				return $orders->num_rows; break;
			case 'new_support_ticket' : 
				$extra_sql = ($username != 'admin') ?  "email = '".$username."'" : 1;
				$new_tickets = get_some_data("support_ticket", "{$extra_sql} AND status != '1'");
				return $new_tickets->num_rows; break;
			case 'new_user_registered' : 
				$new_tickets = get_some_data("users", "joined BETWEEN '".date("Y-m-d H:s:i",strtotime('-2 day'))."' AND '".date("Y-m-d H:s:i",strtotime('+1 day'))."'");
				return $new_tickets->num_rows; break;
			case 'new_payment' : 
				$payments = get_some_data("payments", "amount != 0 ");
				return $payments->num_rows; break;
			case 'total_due' : 
				$total_due = user_at_a_glance($username, 'total_due');
				return ($total_due > 0) ? 1 : 0; break;
			default: return 0;
		}
	}
	function get_support_tickets($extra_sql=true){
		global $conn;
		$get = "SELECT * FROM support_ticket ";
		$get.= "WHERE ".$extra_sql." ";
		$get.= "ORDER BY id DESC";
		$result = $conn->query($get);
		return $result;
	}
	function get_total_support_tickets(){
		global $conn;
		$get = "SELECT * FROM support_ticket ";
		$result = $conn->query($get); $num = $result->num_rows;
		return $num;
	}
	function get_orders($extra_sql=true){
		global $conn;
		$get = "SELECT * FROM customer_orders ";
		$get.= "WHERE ".$extra_sql." ";
		$get.= "ORDER BY id DESC";
		$result = $conn->query($get);
		return $result;
	}
	function get_order_info($id){
		global $conn;
		$get = "SELECT * FROM customer_orders ";
		$get.= "WHERE id='".$id."'";
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row;
	}
	function get_payment_info($id){
		global $conn;
		$get = "SELECT * FROM payments ";
		$get.= "WHERE id='".$id."'";
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row;
	}
	function get_info_by_ticket($id){
		global $conn;
		$get = "SELECT * FROM support_ticket ";
		$get.= "WHERE id = '".$id."'";
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row;
	}
	function get_replies($id) {
		global $conn;
		$get = "SELECT * FROM replies ";
		$get.= "WHERE ticket_id='".$id."' ";
		$get.= "ORDER BY date DESC";
		$result = $conn->query($get);
		return $result;
	}
	function get_packages($service_id) {
		global $conn;
		$get = "SELECT * FROM packages ";
		$get.= "WHERE service_id='".$service_id."'";
		$result = $conn->query($get);
		return $result;
	}
	function get_order_history($username, $extra_sql=true) {
		global $conn;
		$get = "SELECT * FROM customer_orders ";
		$get.= "WHERE username='".$username."' ";
		$get.= "AND ".$extra_sql." ";
		$get.= "ORDER BY id DESC";
		$result = $conn->query($get);
		return $result;
	}
	
	function get_order_history1($extra_sql=true) {
		global $conn;
		$get = "SELECT * FROM customer_orders ";
		$get.= "WHERE ";
		$get.= "".$extra_sql." ";
		$get.= "ORDER BY id DESC";
		$result = $conn->query($get);
		return $result;
	}
	function get_contact_information($index, $extra_sql=true) {
		global $conn;
		$get = "SELECT * FROM contact_information ";
		$get .= "WHERE ".$extra_sql;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		$output = $row[$index];
		return $output;
	}
	function get_rm_value($package, $prev_amount, $extra_sql=true){
		if($package == 1) {return $prev_amount;}
		else {
			global $conn;
			$get = "SELECT * FROM packages WHERE id='".$package."' ";
			$get .= "AND ".$extra_sql;
			$result = $conn->query($get);
			$row = $result->fetch_array();
			return  $row['price'];
		}
	}
	function switch_service($service_type) {
		switch($service_type) {
			case 1: return 'Internet Service'; break;
			default: return null;
		}
	}
	function switch_package($package){
		switch($package) {
			case 2: return '3 Mbps'; break;
			case 3: return '5 Mbps'; break;
			case 4: return '8 Mbps'; break;
			case 7: return '10 Mbps'; break;
			case 8: return 'Digital marketing'; break;
			default: return null;
		}
	}
	function switch_status($status, $index){
		switch($status) {
			case 1: $oarray= array('rm'=>'Unseen', 'payment'=>'Pending');return $oarray[$index]; break;
			case 2: $oarray= array('rm'=>'Added', 'payment'=>'Not-Paid');return $oarray[$index]; break;
			case 3: $oarray= array('rm'=>'Added', 'payment'=>'Paid');return $oarray[$index]; break;
			default: return null;
		}
	}
	function get_user_info($username) {
		global $conn;
		$sql = "SELECT * FROM users ";
		$sql.= "WHERE username='".addslashes($username)."' ";
		$result	= $conn->query($sql);
		$row	= $result->fetch_array();
		return $row;
	}
	function user_at_a_glance($username, $index) {
		$user_info = get_user_info($username);
		$user_orders = get_order_history($username);
		$user_orders_paid = get_order_history($username, "status = '3'");
		$user_orders_rm_placed = get_order_history($username, "status != '1'");
		
		$total_rm = 0; $total_paid = 0; $total_payable = 0; $total_rm_placed_amount = 0;
		foreach($user_orders as $value) $total_payable = $total_payable + $value['amount'];
		foreach($user_orders_paid as $value) $total_paid = $total_paid + $value['amount'];
		foreach($user_orders_rm_placed as $value){
			$total_rm = $total_rm + $value['rm'];
			$total_rm_placed_amount = $total_rm_placed_amount + $value['amount'];
		}
		
		$total_due = $total_payable-$total_paid;
		$average_rm = ($total_rm != 0) ? $total_rm_placed_amount/$total_rm : 0;
		switch($index) {
			case 'total_order': return $user_orders->num_rows; break;
			case 'total_selled_rm': return $total_rm; break;
			case 'total_paid':  return $total_paid; break;
			case 'total_due': return $total_payable-$total_paid; break;
			case 'average_rm': return round($average_rm, 2); break;
		}
	}
	function get_user_by_token($token, $type) {
		global $conn;
		$sql = "SELECT * FROM {$type} ";
		$sql.= " WHERE token='".addslashes($token)."'";
		$result	= $conn->query($sql);
		if($result->num_rows == 1) {
			$row	= $result->fetch_array(); return $row;
		} else {return 0;}
	}
	function get_unread_message() {
		global $conn;
		$sql = "SELECT admin_read FROM contact ";
		$sql.= "WHERE admin_read = '0'";
		$result	= $conn->query($sql);
		return $result;
	}
	function get_message($limit, $extra_sql=true) {
		global $conn;
		$sql = "SELECT * FROM contact ";
		$sql.= "WHERE ".$extra_sql." ";
		$sql.= "ORDER BY Id DESC LIMIT {$limit}";
		$result	= $conn->query($sql);
		return $result;
	}
	function get_admins(){
		global $conn;
		$get		= "SELECT * FROM admins";
		$result		= $conn->query($get);
		return  $result;
	}
	
	/*======= Compulsory Function ====*/
	function get_max($table, $index, $min) {
		global $conn;
		$get = "SELECT MAX({$index}) as {$index} FROM {$table}";
		$result = $conn->query($get);
		$row = $result->fetch_array();
		if(empty($row[$index])) { return $min;}
		else { return $row[$index]; }
	}
	function get_sum_of_index($table, $index, $extra_sql=true){
		global $conn;
		$get = "SELECT SUM({$index}) AS {$index} FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		if(empty($row[$index])) { return 0;}
		else { return $row[$index]; }
	}
	function get_total_rows($table, $extra_sql=true){
		global $conn;
		$get = "SELECT * FROM {$table} ";
		$get.= "WHERE ".$extra_sql;
		$result = $conn->query($get); $num = $result->num_rows;
		return $num;
	}
	function get_all($tablename, $extra_sql=true) {
		global $conn;
		$get = "SELECT * FROM {$tablename} ";
		$get.= $extra_sql; 
		$result = $conn->query($get) or trigger_error($get);
		return $result;
	}
	
	function get_some_data($tablename, $condition) {
		global $conn;
		$get = "SELECT * FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get);
		return $result;
	}
	function get_single_data($tablename, $condition) {
		global $conn;
		$get = "SELECT * FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row;
	}
	function get_single_index_data($tablename, $condition, $index) {
		global $conn;
		$get = "SELECT * FROM ".$tablename." ";
		$get.= "WHERE ".$condition;
		$result = $conn->query($get);
		$row = $result->fetch_array();
		return $row[$index];
	}
	function get_imploded_column($char, $tablename, $column, $extra_sql=true){
		global $conn;
		$implode_array = array(); $result = get_some_data($tablename, $extra_sql);
		foreach($result as $value) $implode_array[] = $value[$column];
		return implode($char, $implode_array);
	}
	function upload_image($imageName, $imageArray, $outputFolder, $outputFile){
		$target_path 	= "../"; 
		$target_path 	= $target_path  . basename( $_FILES[$imageName]['name'][$imageArray]); 
		if(move_uploaded_file($_FILES[$imageName]['tmp_name'][$imageArray], $target_path)) {
			if (!file_exists($outputFolder)) mkdir($outputFolder, 0777, true);
			
			$file 	= basename( $_FILES[$imageName]['name'][$imageArray]);
			if(file_exists($target_path)) rename("../".$file , $outputFile);
			return $outputFile;
		}
	}
	function upload_image_noArray($imageName, $outputFolder){
		$target_path 	= "../"; 
		$target_path 	= $target_path  . basename( $_FILES[$imageName]['name']); 
		if(move_uploaded_file($_FILES[$imageName]['tmp_name'], $target_path)) {
			if(!file_exists($outputFolder)) mkdir($outputFolder, 0777, true);
			
			$file = basename($_FILES[$imageName]['name']);
			if(file_exists($target_path)) rename("../".$file , $outputFolder.'/'.$file);
			return $outputFolder.'/'.$file;
		}
	}
	function InsertInTable($table,$fields){
		$sql  = "INSERT INTO {$table} (".implode(" , ",array_keys($fields)).") ";
		$sql .= "VALUES('";      
		foreach($fields as $key => $value) { 
			$fields[$key] = $value;
		}
		$sql .= implode("' , '",array_values($fields))."');";       
		return $sql;
	}
	function UpdateTable($table,$fields,$condition) {
		$sql = "UPDATE {$table} SET ";
		foreach($fields as $key => $value) { 
			$fields[$key] = " {$key} = '{$value}' ";
		}
		$sql .= implode(" , ",array_values($fields))." WHERE ".$condition.";";
		return $sql;
	}
	function DeleteTable($tablename, $condition) {
		$sql = "DELETE FROM {$tablename} ";
		$sql .= "WHERE {$condition}" ;
		return $sql;
	}
	function adminMessage($background, $html) {
		echo "\n";
		echo "<script>";
		echo "	$(document).ready(function(){ \n";
		echo "		$('.site-message').slideDown('slow'); \n";
		echo "		$('.site-message').css({'background': '{$background}'}); \n";
		echo "		$('.site-message').html('".addslashes($html)."'); \n";
		echo " \n";
		echo "		setTimeout(function(){ \n";
		echo "			$('.site-message').fadeOut(); \n";
		echo "		},7000); \n";
		echo "	}); \n";
		echo "</script>";
		echo "\n";
	}
	function restyle_url($url) {
		$from = array("-", "~", "!", "#", "^", "*", "(", ")", "'", "\"", ",", "%", "&", "$", "@", "/", "\\", ";", " ");
		$to1 = array("-dash-", "-tide-", "-int-", "-hash-", "-caret-", "-star-", "-open-", "-close-", "-squote-");
		$to2 = array("-dquote-", "-comma-", "-percent-", "-and-", "-dollar-", "-at-", "-slash-", "-backslash-", "-semicolon-", "-");
		
		$restyle	= trim($url);
		$restyle	= str_replace($from, array_merge($to1, $to2), $restyle);
		$restyle	= str_replace("--", "~", $restyle);
		return $restyle;
	}
	function random_token() {
		$alpha = "abcdefghijklmnopqrstuvwxyz"; $alpha_upper = strtoupper($alpha);
		$numeric = "0123456789"; $special = ".-+=_,!@$#*%<>[]{}";
		$chars = ""; $chars = $alpha . $alpha_upper . $numeric; $length = 16;
		$len = strlen($chars); $pw = '';
		for ($i=0;$i<$length;$i++)
			$pw .= substr($chars, rand(0, $len-1), 1);
		$pw = str_shuffle($pw);
		return $pw;
	}
	function resize($newWidth, $newHeight, $targetFile, $originalFile) {
		$info = getimagesize($originalFile); $mime = $info['mime'];
		switch ($mime) {
			case 'image/jpeg':
				$image_create_func = 'imagecreatefromjpeg';
				$image_save_func = 'imagejpeg';
				$new_image_ext = 'jpg';
				break;
			case 'image/png':
				$image_create_func = 'imagecreatefrompng';
				$image_save_func = 'imagepng';
				$new_image_ext = 'png';
				break;
			case 'image/gif':
				$image_create_func = 'imagecreatefromgif';
				$image_save_func = 'imagegif';
				$new_image_ext = 'gif';
				break;
			default: 
				throw new Exception('Unknown image type.');
		}
		$img = $image_create_func($originalFile); list($width, $height) = getimagesize($originalFile);
		$tmp = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		if(file_exists($originalFile)) { unlink($originalFile); }
		$image_save_func($tmp, "{$targetFile}.{$new_image_ext}");
	}
	function get_image_information($originalFile) {
		if(file_exists($originalFile) && $originalFile != "../"){
			if($info = getimagesize($originalFile)) {
				$mime = $info['mime'];
				switch ($mime) {
					case 'image/jpeg': $image_extension = 'jpg'; break;
					case 'image/png': $image_extension = 'png'; break;
					case 'image/gif': $image_extension = 'gif'; break;
					default: 
						throw new Exception('Unknown image type.');
				}
				list($width, $height) = getimagesize($originalFile); $imageInfo	= array($width, $height, $image_extension);
				return $imageInfo;
			}else {
				$imageInfo = array(0, 0, 'Unknown');
				return $imageInfo;
			}
		} else {
			$imageInfo = array(0, 0, 'Unknown');
			return $imageInfo;
		}
	}
	function restyle_text($input){
		$input = number_format($input); $input_count = substr_count($input, ',');
		if($input_count != '0'){
			if($input_count == '1'){ return substr($input, 0, -4).'K';}
			else if($input_count == '2'){ return substr($input, 0, -8).'M';}
			else if($input_count == '3'){ return substr($input, 0,  -12).'B';}
			else { return false;}
		} else { return $input; }
	}
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime; $ago = new DateTime($datetime); $diff = $now->diff($ago);
		$diff->w = floor($diff->d / 7); $diff->d -= $diff->w * 7;
		$string = array(
			'y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 
			'h' => 'hour', 'i' => 'minute', 's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if($diff->$k){$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');}
			else{unset($string[$k]);}
		}
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	function send_mail($from, $to, $subject, $messageBody) {
		$email_to = $to; $email_from = $from; $email_subject= $subject;
		if(!isset($messageBody) || strlen($messageBody) <= 5) {
			exit('Message content must be greater than 5 letter...');		
		} else {
			$bad 	= array("content-type","bcc:","to:","cc:"); $Xman = array("\r\n","\n");
			$email_message	= str_replace($bad,"",$messageBody);
			$email_message	= str_replace($Xman,"<br>",$email_message);
		}
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$email_from."\r\n";
		return mail($email_to, $email_subject, $email_message, $headers);
	}
	function deleteDir($dir) { 
		if(!file_exists($dir)) { return false; }
		else {
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach ($files as $file) { 
				(is_dir($dir."/".$file)) ? deleteDir($dir."/".$file) : unlink($dir."/".$file); 
			}
			return rmdir($dir); 
		}
	}