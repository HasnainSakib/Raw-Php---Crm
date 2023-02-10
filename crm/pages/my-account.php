<?php
	include "includes/config.php";
	require_once "includes/functions.php";
?>
<?php 
	if(isset($_POST['update_profic'])) {
		$id = $conn->real_escape_string($_POST['userid']);
		$file = upload_image_noArray('image', '../images/users');
		if(rename($file, "../images/users/{$id}-ppic.png")) {
			header('Location: '.$base.'my-account/?smsg='.urlencode('Updated !'));
		}
	}
	
	if(isset($_POST['due_payment'])) {
		$fields['username'] = $conn->real_escape_string($_POST['username']);
		$fields['type'] = 'due_payment';
		$fields['amount'] = $conn->real_escape_string($_POST['amount']);
		$fields['method'] = $conn->real_escape_string($_POST['payment_type']);
		$fields['payee_number'] = $conn->real_escape_string($_POST['payee_number']);
		$fields['trxn_id'] = $conn->real_escape_string($_POST['trxn_id']);
		$fields['reference'] = $conn->real_escape_string($_POST['reference']);
		$fields['bank_name'] = $conn->real_escape_string($_POST['bank_name']); 
		
		$attachment_fi = array();
		if(!empty($_FILES['attatchments']['names'])) {
			foreach($_FILES['attatchments']['names'] as $key=>$value) 
				$attachment_fi[] = upload_image("attatchments", $key, "useruploads", "useruploads/".$value);
		}
		$fields['bank_attachment'] = implode(',', $attachment_fi);
		$fields['date'] = date("Y-m-d H:i:s"); $fields['remark'] = 0;
		
		$sql = InsertInTable("payments", $fields);
		if($conn->query($sql)) {
			$userinfo	= get_user_info($fields['username']);
			$sms_text = "New due payment received from {$userinfo['first_name']}. ";
			$sms_text.= "Payment amount: BDT{$fields['amount']}, Method: {$fields['method']}.";
			$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
			$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=8801914500116";
			$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
			$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
			header('Location: '.$base.'my-account/?smsg='.urlencode("Successfully submitted ! Payment Under Review "));
		} else {
			header('Location: '.$base.'my-account/?emsg='.urlencode($conn->error));
		}
	}
?>
<?php include "includes/header.php"; ?>


<div id="page-wrapper">
<div class="row" style="background-color:#e7ebec;">
	<div class="row" style="background-color:grey;">
		<div class="col-lg-12">
			<h2 class="page-header"><i class="fa fa-user"></i> MY ACCOUNT</h2>
		</div>
	</div>
<?php if(!isset($_GET['update_profic'])){ ?>
	<div class="row">
		<div class="col-lg-8 col-md-4" style="background-color:#d3f3f5;">
			<table class="table table-bordered">
				<tr>
					<th rowspan="3" class="propic-th">
						<img src="<?= $userimg ?>?1222259157.415" class="img-responsive" />
						<a href="my-account/?update_profic=1" class="update-profic"><i class="fa fa-camera"></i> UPADATE</a>
					</th>
					<th><h3><?= $userinfo['first_name'] ?> <?= $userinfo['last_name'] ?></h3></th>
				</tr>
				<tr><td><strong><i class="fa fa-envelope"></i></strong> <?= $userinfo['email'] ?></td></tr>
				<tr><td><strong><i class="fa fa-phone"></i></strong> <?= $userinfo['mobile_number'] ?></td></tr>
				<tr><td colspan="2"><strong>Location: </strong>  <?= $userinfo['city'] ?>, <?= $userinfo['district'] ?></td></tr>
				<tr><td colspan="2"><strong>Company Name: </strong><?= $userinfo['address'] ?></td></tr>
				<tr><td colspan="2"><strong>Facebook Page Name: </strong><?= $userinfo['facebook_page'] ?> - <a href="http://facebook.com/<?= $userinfo['facebook_page'] ?>">View On Facebook</a></td></tr>
			</table>
		</div>
		<div class="col-lg-4 col-md-4" >
			<ul class="list-group">
				<li class="list-group-item list-group-item-success">Total Order: <span class="badge"><?= user_at_a_glance($userinfo['username'], 'total_order') ?></span></li>
				<li class="list-group-item list-group-item-warning">Total Paid: <span class="badge"><?= user_at_a_glance($userinfo['username'], 'total_paid') ?></span></li>
				<li class="list-group-item list-group-item-success">Total Due: <span class="badge"><?= user_at_a_glance($userinfo['username'], 'total_due') ?></span></li>
			</ul>
			<h4><a href="#payment-modal" data-toggle="modal" class="text-danger"><i class="fa fa-money"></i> Request Due Payment</a></h4>
		</div>
	</div>
	<div class="modal fade" id="payment-modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
				<style scoped="scoped">
					.tab-content > .active {
						padding: 20px 10px;
						border: 1px solid #ccc;
						border-top: none;
					}
				</style>
				
				<form action="" method="POST">
					<input type="hidden" name="due_payment" />
					<input type="hidden" name="payment_type" value="bkash" />
					<input type="hidden" name="username" value="<?= $userinfo['username'] ?>"/>
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Due Payment</h4>
					</div>
					<div class="modal-body">
						<div class="well">
							<div class="form-group">
								<label>Payment Amount <abbr>*</abbr></label>
								<div class="input-group">
									<div class="input-group-addon">BDT</div>
									<input type="number" name="amount" class="form-control" value="<?= user_at_a_glance($userinfo['username'], 'total_due') ?>" />
								</div>
							</div>
							
							<ul class="nav nav-tabs" id="tabContent">
								<li class="active"><a data-toggle="tab" href="#bkash">bKash</a></li>
								<li><a data-toggle="tab" href="#bank">Bank</a></li>
							</ul>
							<div class="tab-content">
								<div id="bkash" class="tab-pane active">
									<div class="form-group">
										<label>Bkash Number <abbr>*</abbr></label>
										<input type="text" name="payee_number" class="form-control required" placeholder="Eg: 01956758055" required />
									</div>
									<div class="form-group">
										<label>Transaction ID</label>
										<input type="text" name="trxn_id" class="form-control" placeholder="Eg: ASdFg12345" />
									</div>
									<div class="form-group">
										<label>Reference</label>
										<input type="text" name="reference" class="form-control" value="<?= $userinfo['facebook_page'] ?>" placeholder="Eg: your facebook page" />
									</div>
								</div>
								<div id="bank" class="tab-pane">
									<div class="form-group">
										<label>Bank Name</label>
										<select name="bank_name" class="form-control required">
											<option value="" selected>Select Bank Name</option>
											<option value="thecitybank">The City Bank</option>
											<option value="bracbank">Brac Bank</option>
										</select>
									</div>
									<div class="form-group">
										<label>Attatchment <abbr>*</abbr></label>
										<div class="input-group">
											<span class="input-group-btn">
												<span class="btn btn-default btn-file">
													Select File 
													<span class="glyphicon glyphicon-folder-open"></span>
													<input type="file" name="attatchments" class="required" />
												</span>
											</span>
											<input type="text" class="form-control" readonly />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="submit" value="Make Payment" class="btn btn-success"/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</form>
      </div>
    </div>
  </div>
	<?php 
		$result_orders = get_order_history($userinfo['username']);
		if($result_orders->num_rows > 0) {
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="order-history-title"><span>MY ORDER HISTORY</span></div>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Order Id</th>
							<th>Date</th>
							<th>Service</th>
							<th>Package</th>
							<th>Amount</th>
							<th>Payment status</th>
						</tr>
					</thead>
					<tbody>
					<?php while($row_orders = $result_orders->fetch_array()) { ?>
						<tr>
							<td>#<?= $row_orders['id'] ?></td>
							<td><?= date('F j,Y', strtotime($row_orders['date'])) ?></td>
							<td><?= switch_service($row_orders['service_type']) ?></td>
							<td>
								<?= switch_package($row_orders['package']) ?> 
								<?php if($row_orders['package']==1){ ?><br><small class="text-info">(Amount: BDT<?= $row_orders['amount'] ?>)</small><?php } ?>
							</td>
							<td><?= $row_orders['amount'] ?></td>
							<td><span class="label label-default"> <?= switch_status($row_orders['status'], 'payment') ?></span></td>
						</tr>
					<?php } ?>	
					</tbody>
				</table>			
			</div>
		</div>
	</div>
	<?php 
		}
		mysqli_free_result($result_orders);
	?>
	<script>
		$(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
			$('a[href="#bkash"]').click(function(){
				$('input[name="payment_type"]').val('bkash');
				$('#bkash').find('input.required').prop('required', true);
				$('#bank').find('input.required').prop('required', false);
			});
			$('a[href="#bank"]').click(function(){
				$('input[name="payment_type"]').val('bank');
				$('#bkash').find('input.required').prop('required', false);
				$('#bank').find('input.required').prop('required', true);
			});
		});
	</script>
<?php } else { ?>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="well">
				<form action="" enctype="multipart/form-data" method="POST">
					<input type="hidden" name="update_profic" value="1" />
					<input type="hidden" name="userid" value="<?= $userinfo['id'] ?>" />
					
					<img id="up-profic-box" src="<?= $userimg ?>" class="img-responsive" />
					<div class="form-group">
						<label>Select image</label>
						<div class="input-group">
							<div class="input-group-btn">
								<span class="btn btn-default btn-file">
									Select Photo <span class="glyphicon glyphicon-picture"></span>
									<input type="file" name="image" id="attachment-input" accept=".jpg,.png" />
								</span>
							</div>
							<input type="text" class="form-control" id="image-name" readonly />
						</div>
					</div>
					<div class="form-group">
						<input type="submit" value="Upload" class="btn btn-success" />
						<?php if(isset($_GET['new'])){ ?><a href="dashboard/" class="btn btn-warning"> Skip</a><?php } ?>
					</div>
				</form>	
			</div>
		</div>
	</div>
<?php } ?>
<script>
	$(document).ready(function(){
		$('#attachment-input').change(function(){
			var numFiles = this.files.length; var label = $(this).val(); 
			var input = $(this).parents('.input-group').find(':text'),
			log = numFiles > 1 ? numFiles + ' files selected' : label;
			if (input.length) { input.val(log); }
			else { if (log) alert(log); }
			
			var inputs = this;
			var url = $(this).val();
			var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if (inputs.files && inputs.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
					var reader = new FileReader();
					reader.onload = function (e) {
						 $('#up-profic-box').attr('src', e.target.result);
					}
				 reader.readAsDataURL(inputs.files[0]);
			} else {
				$('#up-profic-box').attr('src', 'images/male-icon.png');
			}
		});
	});
</script>

</div>
<?php 
	include "includes/footer.php";
?>