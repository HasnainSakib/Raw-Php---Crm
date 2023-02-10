<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
?> 
<?php 
	if(isset($_POST['reply'])) {
		$id = $conn->real_escape_string($_POST['id']);
		$mobile_number = $conn->real_escape_string($_POST['number']);
		$sms_text = $conn->real_escape_string(urlencode($_POST['reply']));
		$fields['admin_read'] = 1;
		$fields['reply'] = $sms_text;
		
		$sql = UpdateTable('contact', $fields, "Id={$id}");
		if($conn->query($sql)) {
			$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
			$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($mobile_number));
			$msgsenturl.="&senderid=8804445629106&msg=".$sms_text;
			$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
			header("Location: ".$base."admin/messages/?view={$id}&smsg=".urlencode("Successfully Replied. Message Sent !"));
		} else {
			header("Location: ".$base."admin/messages/?view={$id}&emsg=".urlencode($conn->error));
		}
	}
	if(isset($_POST['new_message'])) {
		$mobile_numbers = $conn->real_escape_string($_POST['number']);
		$sms_text = $conn->real_escape_string(urlencode($_POST['message']));
		$mobile_number_array = explode(',', $mobile_numbers); $total_sent = 0;
		foreach($mobile_number_array as $mobile_number){
			$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
			$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($mobile_number));
			$msgsenturl.="&senderid=8804445629106&msg=".$sms_text;
			$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req); 
			$total_sent += 1;
		}
		header("Location: ".$base."admin/messages/?smsg=".urlencode("Total {$total_sent} Message Sent !"));
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
		<div class="row">
				<div class="col-lg-12">
						<h1 class="page-header"><i class="fa fa-envelope"></i> MESSAGES</h1>
				</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
					<?php if(!isset($_GET['view']) && !isset($_GET['new_message'])) { ?>
						<div class="table-responsive">
							<div class="form-group">
								<a href="admin/messages/?new_message=1">
									<button class="btn btn-success"><i class="fa fa-plus"></i> New Message</button>
								</a>
							</div> 
							<table class="table table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>From</th>
											<th>Time</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											$result_messages	= get_message(1000); $sli = 1;
											while($row_msg = $result_messages->fetch_array()) {
										?>
										<?php 
											switch($row_msg['admin_read']) {
												case 0: $s_ost = 'New'; $l_type='success'; break;
												case 1: $s_ost = 'Replied'; $l_type='primary'; break;
											}
										?>
											<tr class="clickable-row" data-href="admin/messages/?view=<?= $row_msg['Id'] ?>">
													<td><?= $sli ?></td>
													<td>
														<h4><?= $row_msg['name'] ?></h4>
														<p>Mobile: <?= $row_msg['number'] ?></p>
													</td>
													<td><?= time_elapsed_string($row_msg['date']) ?></td>
													<td><span class="label label-<?= $l_type ?>"><?= $s_ost ?></span></td>
											</tr>
										<?php 
												$sli++;
											}
											mysqli_free_result($result_messages);
										?>
									</tbody>
							</table>
						</div>
					<?php } else if(isset($_GET['view'])) { ?>
					<?php 
						$row_msg = get_single_data("contact", "id='".$_GET['view']."'");
						$userinfo = get_user_info($row_msg['email']);
						$ppic = (file_exists("../images/users/{$userinfo['id']}-ppic.png"))
							? "images/users/{$userinfo['id']}-ppic.png" : "images/male-icon.png";
					?>
						<div class="col-md-8 col-md-offset-2 well">
							<div class="col-lg-9 col-md-9">
								<h4><?= htmlspecialchars($row_msg['name']); ?></h4>
								<p><?= htmlspecialchars($row_msg['email']); ?></p>
								<p><?= htmlspecialchars($row_msg['number']); ?></p>
								<p><strong>Subject:</strong> <u><?= htmlspecialchars($row_msg['subject']); ?></u></p><p>&nbsp;</p>
								<?= $row_msg['message']; ?>	<br/>
								Admin Reply: <?= $row_msg['reply']; ?>	
							</div>
							<div class="col-lg-3 col-md-3"><img src="<?= $ppic ?>" alt="NO USER IMAGE" class="img-responsive" /></div>
							<div class="clearfix"></div>
							
							<p> &nbsp; </p>
							<div class="col-lg-12 col-md-12">
								<form action="" method="POST">
									<input type="hidden" name="id" value="<?= $row_msg['Id'] ?>" />
									<input type="hidden" name="number" value="<?= $row_msg['number'] ?>" />
									<div class="form-group">
										<textarea class="form-control" name="reply" placeholder="Write a reply.." required></textarea>
									</div>
									<div class="form-group">
										<input type="submit" value="Reply" class="btn btn-success" />
									</div>
								</form>
							</div>
						</div>
					<?php } else if(isset($_GET['new_message'])){ ?>
					<?php 
						if($_GET['new_message'] == 'all') {
							$msgto = 'Dhaka Solution Customers';
							$customers = get_imploded_column(",", "users", "username", $extra_sql=true);
						} else if($_GET['new_message'] != 1) {
							$customerIds = explode(",", $_GET['new_message']); $customerNames = array(); $customerNumbers = array();
							foreach($customerIds as $customerId) {
								$customerNames[] = get_single_index_data("users", "id = '{$customerId}'", "first_name");
								$customerNumbers[] = get_single_index_data("users", "id = '{$customerId}'", "mobile_number");
							}
							$msgto = implode(', ',$customerNames); $customers = implode(',',$customerNumbers);
						}
					?>
						<div class="col-md-6 col-md-offset-3">
							<div class="well">
								<h4>New Message</h4><hr/>
								<form action="" method="POST">
									<input type="hidden" name="new_message" value="1" />
								<?php if(isset($customers) && !empty($customers)) { ?>
									<div class="form-group">
										<label>To</label>
										<input type="text" class="form-control" value="<?= $msgto ?>" readonly />
									</div>
									<input type="hidden" name="number" value="<?= $customers ?>" />
								<?php } else { ?>
								<?php $result_customers = get_all('users', null); ?>
									<div class="form-group g-form">
										<label>Customer Number</label>
										<input type="text" name="number" class="form-control" list="customerList" required />
										<datalist id="customerList">
										<?php while($row_cus = $result_customers->fetch_array()) { ?>
											<option value="<?= $row_cus['mobile_number'] ?>"><?= $row_cus['first_name'] ?></option>
										<?php } mysqli_free_result($result_customers); ?>
										</datalist>
										<p style="font-size: 11.5px; font-style: italic;">
											<a href="admin/messages/?new_message=all">To all customer</a>
										</p>
									</div>
								<?php } ?>
									<div class="form-group g-form">
										<label>Message Body</label>
										<textarea name="message" class="form-control" required /></textarea>
									</div>
									<div class="form-group">
										<input type="submit" value="Send" class="btn btn-success" />
									</div>
								</form>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
</div>
<?php 
	include "includes/footer.php";
?>