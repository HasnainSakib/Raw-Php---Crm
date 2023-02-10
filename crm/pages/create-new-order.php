<?php 
	include "includes/config.php";
	require_once "includes/functions.php";
?>
<?php
	if(isset($_POST['payment'])) {
		$fields['date'] = $payment_fields['date'] = date('Y-m-d H:i:s');
		$fields['username'] = $payment_fields['username'] =$conn->real_escape_string($_POST['username']);
		$fields['ad_manager_id'] = $conn->real_escape_string($_POST['admanagerid']);
		$fields['service_type'] = $conn->real_escape_string($_POST['service_type']);
		$fields['package'] = $conn->real_escape_string($_POST['package']);
		$fields['amount'] = $payment_fields['amount'] = ($fields['package'] == 1) 
			? $conn->real_escape_string($_POST['amount']) 
			: get_single_index_data('packages', "service_id='".$fields['service_type']."' AND id='".$fields['package']."'", 'price');
		$fields['rm'] = 0; $fields['status'] = 1;
		
		$payment_fields['type'] = 'order_payment';
		$payment_fields['method'] = isset($_POST['payment_type']) ? $conn->real_escape_string($_POST['payment_type']) : null;
		$payment_fields['payee_number'] = isset($_POST['pn']) ? $conn->real_escape_string($_POST['pn']) : null;
		$payment_fields['trxn_id'] = isset($_POST['trnid']) ? $conn->real_escape_string($_POST['trnid']) : null;
		$payment_fields['reference'] = isset($_POST['ref']) ? $conn->real_escape_string($_POST['ref']) : null;
		$payment_fields['bank_name'] = isset($_POST['bank_name']) ? $conn->real_escape_string($_POST['bank_name']) : null;
		if(!empty($_FILES['attatchment']['name']))
			$attatchment = upload_image_noArray('attatchment', 'useruploads/bank-statements');
		$payment_fields['bank_attachment'] = isset($attatchment) ? $conn->real_escape_string($attatchment) : null;
		$payment_fields['remark'] = 0;
		
		$paymentsql = InsertInTable('payments', $payment_fields);
		if($conn->query($paymentsql)) {
			$fields['payment_id'] = $conn->insert_id;
			$sql = InsertInTable('customer_orders', $fields);
			if($conn->query($sql)) {
				$sms_text = "New order from ".$fields['ad_manager_id'].". ";
				$sms_text.= "Budget amount : BDT".$fields['amount'].".\n";
				$sms_text.= "Plese check your orders https://crm.dhakasolution.com/admin/orders/";
				
				$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
				$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=8801914500116";
				$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
				$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
				exit(header('Location: '.$base.'dashboard?smsg='.urlencode('Successfully placed your order !')));
			} else {
				exit(header('Location: '.$base.'orders/create-new/?emsg='.urlencode($conn->error)));
			}
		} else {
			exit(header('Location: '.$base.'orders/create-new/?emsg='.urlencode($conn->error)));
		}
	}
	
	
	
	if(isset($_POST['payment1'])) {
		$fields['date'] = $payment_fields['date'] = date('Y-m-d H:i:s');
		$fields['username'] = $payment_fields['username'] =$conn->real_escape_string($_POST['username']);
		$fields['ad_manager_id'] = $conn->real_escape_string($_POST['admanagerid']);
		$fields['service_type'] = $conn->real_escape_string($_POST['service_type']);
		$fields['package'] = $conn->real_escape_string($_POST['package']);
		$fields['amount'] = $payment_fields['amount'] = ($fields['package'] == 1) 
			? $conn->real_escape_string($_POST['amount']) 
			: get_single_index_data('packages', "service_id='".$fields['service_type']."' AND id='".$fields['package']."'", 'price');
		$fields['rm'] = 0; $fields['status'] = 1;
		
		$payment_fields['type'] = 'due_payment';
		$payment_fields['method'] = isset($_POST['payment_type']) ? $conn->real_escape_string($_POST['payment_type']) : null;
		$payment_fields['payee_number'] = isset($_POST['pn']) ? $conn->real_escape_string($_POST['pn']) : null;
		$payment_fields['trxn_id'] = isset($_POST['trnid']) ? $conn->real_escape_string($_POST['trnid']) : null;
		$payment_fields['reference'] = isset($_POST['ref']) ? $conn->real_escape_string($_POST['ref']) : null;
		$payment_fields['bank_name'] = isset($_POST['bank_name']) ? $conn->real_escape_string($_POST['bank_name']) : null;
		if(!empty($_FILES['attatchment']['name']))
			$attatchment = upload_image_noArray('attatchment', 'useruploads/bank-statements');
		$payment_fields['bank_attachment'] = isset($attatchment) ? $conn->real_escape_string($attatchment) : null;
		$payment_fields['remark'] = 0;
		
		$paymentsql = InsertInTable('payments', $payment_fields);
		if($conn->query($paymentsql)) {
			$fields['payment_id'] = $conn->insert_id;
			$sql = InsertInTable('customer_orders', $fields);
			if($conn->query($sql)) {
				$sms_text = "New order from ".$fields['ad_manager_id'].". ";
				$sms_text.= "Budget amount : BDT".$fields['amount'].".\n";
				$sms_text.= "Plese check your orders https://crm.dhakasolution.com/admin/orders/";
				
				$msgsenturl ="http://bsms.dhakasolution.com/smsapi";
				$msgsenturl.="?api_key=C20022345bd5a54eb52ce4.62365419&type=text&contacts=8801914500116";
				$msgsenturl.="&senderid=8804445629106&msg=".urlencode($sms_text);
				$req = curl_init(); curl_setopt($req, CURLOPT_URL, $msgsenturl); curl_exec($req);
				exit(header('Location: '.$base.'dashboard?smsg='.urlencode('Successfully placed your order !')));
			} else {
				exit(header('Location: '.$base.'orders/create-new/?emsg='.urlencode($conn->error)));
			}
		} else {
			exit(header('Location: '.$base.'orders/create-new/?emsg='.urlencode($conn->error)));
		}
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
		<div class="row" style="background-color:grey;">
			<div class="col-lg-12">
				<h2 class="page-header"><i class="fa fa-rocket"></i> CREATE NEW ORDER</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
				<?php if(isset($_GET['service']) && !isset($_GET['package'])) { ?>
					<div class="panel-heading"><?= switch_service($_GET['service']) ?></div>
					<div class="panel-body">
						<div class="flex packages-flex">
						<?php 
							$result_services = get_packages($_GET['service']);
							if($result_services->num_rows > 0) {
								while($row_services = $result_services->fetch_array()) {
						?>
							<div class="flex-items">
								<div class="titlef"><?= $row_services['pack_name'] ?></div>
								<div class="price">Tk <?= $row_services['price'] ?></div>
								<ul class="list-info">
									<?= $row_services['pack_info'] ?>
								</ul>
								<a href="orders/create-new/?service=<?= $_GET['service'] ?>&package=<?= $row_services['id'] ?>" class="wpc-btn btn-confirm">order now</a>
							</div>
						<?php
								}
							}
							mysqli_free_result($result_services);
						?>
						</div>
					</div>
				<?php } else if(isset($_GET['package'])) { ?>
				<?php 
					$pack_price = ($_GET['package'] == 1) 
						? $conn->real_escape_string($_GET['amount']) 
						: get_single_index_data('packages', "service_id='".$_GET['service']."' AND id='".$_GET['package']."'", 'price');
				?>
					<div class="panel-heading">
						<?= get_single_index_data('packages', "service_id='".$_GET['service']."' AND id='".$_GET['package']."'", 'pack_name') ?>
					</div>
					<div class="panel-body">
						<div class="row" id="select-payment">
							<h4 class="text-center">Select Payment Method</h4>
							<div class="col-md-4">
								<div class="well payment-methods bkash" data-payment="bkash">
									<span class="payment-icon bkash"></span>
									<span class="payment-type">bKash</span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="well payment-methods bank" data-payment="bank">
									<span class="payment-icon bank"></span>
									<span class="payment-type">Bank Payment</span>
								</div>
							</div>
							<div class="col-md-4">
								<div class="well payment-methods due" data-payment="due">
									<span class="payment-icon due"></span>
									<span class="payment-type">Pay Later</span>
								</div>
							</div>
						</div>
						<?php include "includes/payment-info.php" ?>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
		<div class="modal fade" tabindex="-1" role="dialog"  aria-hidden="true" id="mi-modal">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-sm vertical-align-center">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Are You Sure ?</h4>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" id="modal-btn-no">No</button>
							<button type="button" class="btn btn-primary" id="modal-btn-si">Yes</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
					$('[data-toggle="tooltip"]').tooltip(); 
					$('#attachment-input').change(function(){
						var numFiles = this.files.length; var label = $(this).val(); 
						
						var input = $(this).parents('.input-group').find(':text'),
						log = numFiles > 1 ? numFiles + ' files selected' : label;
						
						if (input.length) { input.val(log); }
						else { if (log) alert(log); } 
					});
					$('html body').on('change', 'select[name="bank_name"]', function(){
						$this_val = $('select[name="bank_name"]').val();
						
						if($this_val == 'thecitybank') { var textforinfo  = '<strong>City Bank</strong><br/>A/C Name: SAIFUL ISLAM<br/>A/C Number: 2401835373001'; }
						else if($this_val == 'bracbank') { var textforinfo  = '<strong>Brac Bank</strong><br/>A/C Name: ELANCE IT<br/>A/C Number: 1510203305196001'; }
						$('div.bank-info').html(textforinfo);
					});
					$('.payment-methods.bkash').click(function(){
						$(this).closest('#select-payment').hide(); $('#bkash').fadeIn();
					});
					$('.payment-methods.bank').click(function(){
						$(this).closest('#select-payment').hide(); $('#bank').fadeIn();
					});
					$('.payment-methods.due').click(function(){
						$(this).closest('#select-payment').hide();
						$('#due').fadeIn();
						//window.location.href = "<?= $base.'dashboard?smsg='.urlencode('Successfully placed your order !') ?>"
					});
					$('.cancel-payment').click(function(e){
						e.preventDefault();
						$(this).closest('.row.payment-info').hide();
						$('#select-payment').show();
					});
			});
			
			var modalConfirm = function(callback){
				$(".btn-confirm").on("click", function(e){
					e.preventDefault();
					$this_href = $(this).attr('href');
					$("#mi-modal").modal('show');
				});
				$("#modal-btn-si").on("click", function(){
					callback(true); $("#mi-modal").modal('hide');
				});
				$("#modal-btn-no").on("click", function(){
					callback(false); $("#mi-modal").modal('hide');
				});
			};
			modalConfirm(function(confirm){
				if(confirm) window.location = $this_href;
			});
		</script>
</div>
<?php 
	include "includes/footer.php";
?>