<?php 
	include "includes/config.php";
	require_once "includes/functions.php";
?>
<?php include "includes/header.php" ?>
<div id="page-wrapper">
<div class="row" style="background-color:#e7ebec;">
	<div class="row" style="background-color:grey;">
		<div class="col-lg-12 col-md-12">
			<h2 class="page-header"><i class="fa fa-list-alt"></i> MY ORDER HISTORY</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
		<?php 
			$result_orders = get_order_history($userinfo['username']);
			if($result_orders->num_rows > 0) {
		?>
			<div class="row">
				<div class="col-lg-12"> 
					<div class="panel panel-default">
						<div class="panel-body">
						<?php if(!isset($_GET['view'])) { ?>
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Order Id</th>
											<th>Date</th>
											<th>Service</th>
											<th>Package</th>
											<th>Payment</th>
											
										</tr>
									</thead>
									<tbody>
									<?php while($row_orders = $result_orders->fetch_array()) { ?>
										<tr class="clickable-row" data-href="my-orders/?view=<?= $row_orders['id'] ?>">
											<td>#<?= $row_orders['id'] ?></td>
											<td><?= date('F j,Y', strtotime($row_orders['date'])) ?></td>
											<td><?= switch_service($row_orders['service_type']) ?></td>
											<td>
												<?= switch_package($row_orders['package']) ?> 
												<?php if($row_orders['package']==1){ ?><br><small class="text-info">(Amount: BDT<?= $row_orders['amount'] ?>)</small><?php } ?>
											</td>
											<td><span class="label label-default"> <?= switch_status($row_orders['status'], 'payment') ?></span></td>
											
										</tr>
									<?php } ?>	
									</tbody>
								</table>
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
												<p><strong>Ordering Date: </strong><?= date('F j, Y', strtotime($row_oi['date'])) ?></p>
												<p><strong>Service Type: </strong><?= switch_service($row_oi['service_type']) ?></p>
												<p><strong>Package: </strong><?= switch_package($row_oi['package']) ?></p>
												<p><strong>Payment Method: </strong><?= ucfirst($row_pi['method']) ?></p>
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
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="col-md-6 pd-5">
													<div class="well text-right">
														<div class="huge"><?= $row_oi['amount'] ?></div>
														<p>Package</p>
													</div>
												</div>
												<div class="col-md-6 pd-5">
													<div class="well text-right">
														<div class="huge text-info"><?= switch_status($row_oi['status'], 'payment') ?></div>
														<p>Payment Status</p>
													</div>
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-danger text-center">
						<strong>No Order Found! </strong>
						To create a order <a href="orders/create-new/" class="alert-link">click here</a>.
					</div>
				</div>
			</div>
		<?php	}mysqli_free_result($result_orders); ?>
		</div>
	</div>
</div>
</div>
<?php 
	include "includes/footer.php";
?>