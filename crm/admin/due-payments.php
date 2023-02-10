<style>
.table a {
    color: #000000;
    text-decoration: none;
}
</style>
<?php 
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
?>
<?php 
	if(isset($_POST['action'])) {
		$action = $conn->real_escape_string($_POST['action']); 
		if($action == 'confirm') {
			$pmnt_id = $conn->real_escape_string($_POST['pmnt_id']);
			$payment_info = get_single_data("payments", "id='{$pmnt_id}'");
			$userinfo = get_user_info($payment_info['username']);
			
			$updatable_orders = $_POST['orders']; $total_selected_amount = 0;
			foreach($updatable_orders as $order_id) {
				$total_selected_amount += get_single_index_data("customer_orders", "id='{$order_id}'", "amount");
				
				$update_orders_fields['status'] = 3;
				$update_orders_sql = UpdateTable("customer_orders", $update_orders_fields, "id='{$order_id}'");
				$conn->query($update_orders_sql);
			}
			
			$total_paid_amount = $payment_info['amount'];			
			
			$update_payments_fields['amount'] = $total_paid_amount-$total_selected_amount;
			if($update_payments_fields['amount'] >= 0) $update_payments_sql = UpdateTable("payments", $update_payments_fields, "id='{$pmnt_id}'");
			else $update_payments_sql = DeleteTable("payments", "id='{$pmnt_id}'");
			
			if($conn->query($update_payments_sql)) {
				$sms_text = "We have received your due payment. ";
				$sms_text.= "Thank you for staying with us. \n";
				$sms_text.= "- Dhaka Solution";
				$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
				$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($userinfo['mobile_number']));
				$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
				$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
				header('Location: '.$base.'admin/due-payments/?smsg='.urlencode('Successfully Updated Data !'));
			} else {
				header('Location: '.$base.'admin/due-payments/?emsg='.urlencode($conn->error));
			}
		}
	} else if(isset($_GET['action'])) {
	    if($_GET['action'] == 'delete') {
			$pmnt_id = $conn->real_escape_string($_GET['pmnt_id']);
    		$payment_info = get_single_data("payments", "id='{$pmnt_id}'");
    		$userinfo = get_user_info($payment_info['username']);
    		
    		$update_payments_sql = DeleteTable("payments", "id='{$pmnt_id}'");
    		if($conn->query($update_payments_sql)) {
    			$sms_text = "Sorry! Your payment information is invalid. ";
    			$sms_text.= "Any inquiry, plz contact +8801709309110 . \n";
    			$sms_text.= "- Dhaka Solution";
    			$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
    			$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=".urlencode(trim($userinfo['mobile_number']));
    			$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
    			$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
    			header('Location: '.$base.'admin/due-payments/?smsg='.urlencode('Successfully Deleted Data !'));
    		} else {
    			header('Location: '.$base.'admin/due-payments/?emsg='.urlencode($conn->error));
    		}       
	    }
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
	<div class="row">
			<div class="col-lg-12">
					<h1 class="page-header"><i class="fa fa-money"></i> DUE PAYMENTS</h1>
			</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
				<?php 
					$result_payments	= get_some_data("payments", " amount !=0 AND remark='0' ORDER BY id DESC");
					if($result_payments->num_rows > 0) {
				?>
					<div class="table-responsive">
						<table class="table table-hover order-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Username</th>
									<th>Payment Info</th>
									<th>Time</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
								while($row_pmnt = $result_payments->fetch_array()) {
									$row_userinfo	= get_user_info($row_pmnt['username']);
							?>
								<tr>
									<td>1</td>
									<td>
										<h4><?= $row_userinfo['first_name'] ?></h4>
										<p><i class="fa fa-envelope"></i> <?= $row_userinfo['username'] ?></p>
										<p><i class="fa fa-phone"></i> <?= $row_userinfo['mobile_number'] ?></p>
										<p><i class="fa fa-payment"></i> paymeny ID: <?= $row_pmnt['id'] ?></p>
									</td>
									<td>
										<p class="text-danger"><strong>Payment Method: </strong><?= ucfirst($row_pmnt['method']) ?></p>
										<p><strong>Amount: </strong><?= $row_pmnt['amount'] ?></p>
									<?php if($row_pmnt['method'] == 'bkash'){ ?>
										<p><strong>Bkash Number: </strong><?= ucfirst($row_pmnt['payee_number']) ?></p>
										<p><strong>Transaction ID: </strong><?= $row_pmnt['trxn_id'] ?></p>
										<p><strong>Reference: </strong><?= $row_pmnt['reference'] ?></p>
									<?php } else { ?>
										<p><strong>Bank Name: </strong><?= $row_pmnt['bank_name'] ?></p>
										<p><strong>Attatchments: </strong><?= $row_pmnt['bank_attachment'] ?></p>
									<?php } ?>
									</td>
									<td><?= time_elapsed_string($row_pmnt['date']) ?></td>
									<td>
										<p><a data-toggle="modal" href="#select-confirm-type" data-pmnt-id="<?= $row_pmnt['id'] ?>" data-pmnt-amount="<?= $row_pmnt['amount'] ?>"><span class="badge action-badge check"><i class="fa fa-check"></i> Confirm</span></a></p>
										<p><a href="admin/due-payments/?action=delete&pmnt_id=<?= $row_pmnt['id'] ?>"><span class="badge action-badge times"><i class="fa fa-times"></i> Delete</span></a></p>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
					
					<div class="modal fade" id="select-confirm-type" role="dialog">
						<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-sm vertical-align-center">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Unpaid Order Selection</h4>
									</div>
									<div class="modal-body">
										<button type="button" id="target-modal-btn" class="btn btn-success" data-target="#select-orders" data-toggle="modal" data-dismiss="modal">Manual</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Automatic</button>
									</div>
								</div>
							</div>	
						</div>
					</div>
					
				<?php 
					$result_payments	= get_some_data("payments", " remark='0' ORDER BY id DESC");
					while($row_pmnt = $result_payments->fetch_array()) { 
						$row_userinfo	= get_user_info($row_pmnt['username']);
				?>
					<div class="modal fade" id="select-orders-<?= $row_pmnt['id'] ?>" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<form action="" method="POST">
									<input type="hidden" name="action" value="confirm" />
									<input type="hidden" name="pmnt_id" value="<?= $row_pmnt['id'] ?>" />
									
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Select Unpaid Orders</h4>
									</div>
									<div class="modal-body">
										<div class="well">
											<h4>Total: <?= $row_pmnt['amount'] ?>, Selected: <span class="samount">0</span></h4>
											<table class="table table-striped">
											<?php
												$user_unpaid_orders = get_orders("payment_id = '".$row_pmnt['id']."' AND status = '1'");
												while($row_uuo = $user_unpaid_orders->fetch_array()) {
											?>
												<tr class="clickable-row" data-href="<?= $self_url."#" ?>">
													<td><input type="checkbox" name="orders[]" id="<?= $row_uuo['id'] ?>" value="<?= $row_uuo['id'] ?>" data-amount="<?= $row_uuo['amount'] ?>" onclick="return false;" /></td>
													<td><label for="<?= $row_uuo['id'] ?>"><a href="javascript:void(0)"><?= switch_service($row_uuo['service_type']) ?></a></label></td>
													<td><label for="<?= $row_uuo['id'] ?>"><a href="javascript:void(0)"><?= time_elapsed_string($row_uuo['date']) ?></a></label></td>
												</tr>
											<?php } ?>
											<?php mysqli_free_result($user_unpaid_orders); ?>
											</table>
										</div>
									</div>
									<div class="modal-footer">
										<input type="submit" value="Confirm" class="btn btn-success"/>
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php } ?>
					<script>
						$(document).ready(function(){
							total = 0; selected = 0;
							$('.clickable-row input[type="checkbox"]').each(function(){$(this).prop('checked',false)})
							
							$('a[href="#select-confirm-type"]').click(function(){
								var pmnt_id = $(this).attr('data-pmnt-id');
								$('#target-modal-btn').attr('data-target', "#select-orders-"+pmnt_id);
								total = parseInt($(this).attr('data-pmnt-amount')); selected = 0; $('.samount').text(0);
								$('.clickable-row input[type="checkbox"]').each(function(){$(this).prop('checked',false)})
							});
							
							$('.clickable-row label').click(function(){
								var this_label = $(this).attr('for');
								var this_amount =	parseInt($('#'+this_label).attr('data-amount'));
								
								if($('#'+this_label).is(":checked")) selected -= this_amount;
								else selected += this_amount;
								
								if(selected>total || selected<0) {selected -= this_amount; alert('Selected limit exceed than total !'); return false;}
								else {
									$('.samount').text(selected);
									$('#'+this_label).prop('checked', !$('#'+this_label).prop('checked'));
								}
							});
						});
					</script>
				<?php } else { ?>
				<div class="alert alert-danger">
					<h3 class="text-center">No Due Payments !</h3>
				</div>
				<?php 
					}
					mysqli_free_result($result_payments);
				?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include "includes/footer.php"; ?>