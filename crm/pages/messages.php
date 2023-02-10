<?php include "includes/config.php"; require_once "includes/functions.php"; ?>
<?php 
	if(isset($_POST['send_message'])) {
		$fields['date'] = date('Y-m-d H:i:s');
		$fields['name'] = $conn->real_escape_string($_POST['name']);
		$fields['email'] = $conn->real_escape_string($_POST['email']);
		$fields['number'] = $conn->real_escape_string($_POST['number']);
		$fields['subject'] = $conn->real_escape_string($_POST['subject']);
		$fields['message'] = $conn->real_escape_string($_POST['message']); 
		$fields['admin_read'] = 0;
		
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
			<h2 class="page-header" style="background-color:#f3ae4c;"><i class="fa fa-envelope"></i> MESSAGE</h2>
			
			<div class="col-md-8 col-md-offset-2">
				<div class="well">
					<h4>Send a Message to Admin</h4><hr/>
					<form action="" method="POST">
						<input type="hidden" name="send_message" />
						<input type="hidden" name="number" value="<?= $userinfo['mobile_number'] ?>" />
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Your Name *</label>
									<input type="text" name="name" class="form-control" value="<?= $userinfo['first_name'] ?>" readonly />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Your Email *</label>
									<input type="email" name="email" class="form-control" value="<?= $userinfo['username'] ?>" readonly />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Subject</label>
							<input type="text" name="subject" class="form-control" placeholder="Enter Subject: " />
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="message">Message *</label>
									<textarea name="message" class="message-box" required></textarea>
								</div>
							</div>
						</div>
						<div class="form-group">
							<input type="submit" value="Send" class="btn btn-success" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
					<?php if(!isset($_GET['view']) && !isset($_GET['new_message'])) { ?>
						<div class="table-responsive">
							<div class="form-group">
								
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
											<tr class="clickable-row" data-href="messages/?view=<?= $row_msg['Id'] ?>">
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
							
						</div>
					<?php } ?>
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