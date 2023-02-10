<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
?>  
<?php 
	if(isset($_POST['update_order_info'])) {
		$id = $conn->real_escape_string($_POST['id']);
		$mobile_number = $conn->real_escape_string($_POST['mobile_number']);
		$ufileds['rm'] = $conn->real_escape_string($_POST['rm']);
		$ufileds['status'] = $conn->real_escape_string($_POST['status']);
		switch($ufileds['status']) {
			case 1: $upfields['remark'] = 0; break;
			case 2: $upfields['remark'] = 1; break;
			case 3: $upfields['remark'] = 2; break;
		}
		$payment_id = get_single_index_data("customer_orders", "id='{$id}'", "payment_id");
		
		$usql = UpdateTable('customer_orders', $ufileds, "id={$id}");
		$upsql = UpdateTable('payments', $upfields, "id={$payment_id}");
		if($conn->query($usql) && $conn->query($upsql)) {
			if($ufileds['status'] != 1) {
				$sms_text = "Your balance has been added! ";
				$sms_text.= "You got RM".$ufileds['rm'].". Thank You. \n";
				$sms_text.= "- Dhaka Solution";
				$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
				$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($mobile_number));
				$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
				$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
			}
			header('Location: '.$base.'admin/orders/?view='.$id.'&smsg='.urlencode('Successfully Updated Data !'));
		} else {
			header('Location: '.$base.'admin/orders/?view='.$id.'&emsg='.urlencode($conn->error));
		}
	}
	if(isset($_GET['delete'])) {
		$ids = $conn->real_escape_string($_GET['delete']);
		foreach(explode(',', $ids) as $id) {
			$sql = DeleteTable("customer_orders", "id='{$id}'");
			if($conn->query($sql))
				header('Location: '.$base.'admin/orders/?smsg='.urlencode(mysqli_affected_rows($conn).' data deleted !'));
		}
		
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
		<div class="row">
				<div class="col-lg-12">
						<h1 class="page-header"><i class="fa fa-rocket"></i> ORDERS</h1>
				</div>
		</div>
		<div class="row">
				<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-body">
							<?php if(!isset($_GET['view'])) { ?>
								<div class="table-responsive">
									<table class="table table-hover order-table">
										<thead>
											<tr>
												<th>#</th>
												<th>Order From</th>
												<th>Service Name</th>
												<th>Package</th>
												<th>Date</th>
												<th>Total</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												$result_orders	= get_orders(); $sli = 1;
												while($row_od = $result_orders->fetch_array()) {
													$row_userinfo	= get_user_info($row_od['username']);
											?>
											<tr class="clickable-row" data-href="admin/orders/?view=<?= $row_od['id'] ?>" data-userid="<?= $row_od['id'] ?>">
												<td><?= $sli ?></td>
												<td>
													<h4><?= $row_userinfo['first_name'] ?></h4>
													<p><i class="fa fa-envelope"></i> <?= $row_userinfo['email'] ?></p>
													<p><i class="fa fa-phone"></i> <?= $row_userinfo['mobile_number'] ?></p>
													<p><i class="fa fa-payment"></i>Payment ID:  <?= $row_od['payment_id'] ?></p>
												</td>
												<td><?= switch_service($row_od['service_type']) ?></td>
												<td><?= switch_package($row_od['package']) ?></td>
												<td><?= time_elapsed_string($row_od['date']) ?></td>
												<td><?= $row_od['amount'] ?></td>
												<td>
													<p><span class="label label-default <?= switch_status($row_od['status'], 'payment') ?>"><?= switch_status($row_od['status'], 'payment') ?></span></p>
												</td>
											</tr>
											<?php 
													$sli++;
												}
												mysqli_free_result($result_orders);
											?>
										</tbody>
									</table>
									<div class="form-group">
										<button class="btn btn-success" id="select-cus"><i class="fa fa-plus"></i> Select Orders</button>
										<button class="btn btn-danger hidden-btns hidden-md hidden-lg send-remove" data-type="remove" data-ref="admin/orders/"><i class="fa fa-trash"></i> Delete</button>
										<a class="btn btn-warning hidden-btns hidden-md hidden-lg" href="admin/orders/"><i class="fa fa-times"></i> Cancel</a>
									</div> 
								</div>
								<script>
									$(document).ready(function(){
										$('.clickable-row').click(function(){
											var href= $(this).attr('data-href');
											window.location.href = href;
										});
									});
								</script>
							<?php } else { ?>
							<?php 
								$row_oi = get_order_info($_GET['view']);
								$row_pi = get_payment_info($row_oi['payment_id']);
								$row_userinfo	= get_user_info($row_oi['username']);
							?>
								<div class="row">
									<div class="col-lg-12 col-md-12">
										<div class="well rhead-section">
											<h4>ORDER ID #<?= sprintf('%06d', $row_oi['id']); ?></h4>
											<p> &nbsp; </p>
											<div class="row">
												<div class="col-lg-6 col-md-6">
													<p><strong>Customer Name: </strong><?= $row_userinfo['first_name'].' '.$row_userinfo['last_name'] ?></p>
													<p><strong>Customer Email: </strong><?= $row_userinfo['email'] ?></p>
													<p><strong>Customer Mobile Number: </strong><?= $row_userinfo['mobile_number'] ?></p>
													<p><strong>Ordering Date: </strong><?= date('F j, Y', strtotime($row_oi['date'])) ?></p>
													<p><strong>Service Type: </strong><?= switch_service($row_oi['service_type']) ?></p>
													<p><strong>Package: </strong><?= switch_package($row_oi['package']) ?></p>
												<?php if($row_oi['package'] == 1){ ?>
													<p class="text-danger"><strong>Budget Amount: <?= $row_oi['amount'] ?> BDT</strong></p>
												<?php } ?>
												</div>
												<div class="col-lg-6 col-md-6">
													<p><strong>Payment: </strong><?= ucfirst($row_pi['method']) ?></p>
												<?php if($row_pi['method'] == 'bkash'){ ?>
													<p><strong>Bkash Number: </strong><?= $row_pi['payee_number'] ?></p>
													<p><strong>Bkash Trxn Id: </strong><?= $row_pi['trxn_id'] ?></p>
													<p><strong>Bkash Referrence: </strong><?= $row_pi['reference'] ?></p>
												<?php } if($row_pi['method'] == 'bank'){ ?>
													<p><strong>Bank Name: </strong><?= $row_pi['bank_name'] ?></p>
													<p>
														<strong>Attatchments: </strong>
													<?php 
														$attatchemnts_fi = explode(',', $row_pi['bank_attachment']);
														foreach($attatchemnts_fi as $key=>$value){
													?>
														<a href="pages/<?= $value ?>" download=""><i class="fa fa-file"></i> Attatchment<?= $key+1 ?> </a>
													<?php } ?>
													</p>
												<?php } ?>
													<p><strong>Payment Status: </strong><span class="text-warning"><?= switch_status($row_oi['status'], 'payment') ?></span></p>
												</div>	
											</div>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12">
										<div class="well">
										<?php 
											$result_orders = get_order_history($row_userinfo['username']);
											if($result_orders->num_rows > 0) {
										?>
											<div class="order-history-title"><span>This Customer Previous Orders</span></div>
											<div class="table-responsive">
												<table class="table table-hover table-bordered">
													<tr><th>Date</th><th>Service</th><th>Pack</th><th>Amount</th><th>Payment Status</th></tr>
												<?php while($row_orders = $result_orders->fetch_array()) { ?>
													<tr>
														<td><?= time_elapsed_string($row_orders['date']) ?></td>
														<td>#<?= $row_orders['id'] ?> - <?= switch_service($row_orders['service_type']) ?></td>
														<td><?= switch_package($row_orders['package']) ?></td>
														
														<td><?= $row_orders['amount'] ?></td>
														<td><span class="label label-default"> <?= switch_status($row_orders['status'], 'payment') ?></span></td>
													</tr>
												<?php } ?>
												</table>
											</div>
										<?php
											} else {
										?>
											<div class="order-history-title"><span>This Customer Has No Previous Orders</span></div>
										<?php
											}
											mysqli_free_result($result_orders);
										?>	
										</div>
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