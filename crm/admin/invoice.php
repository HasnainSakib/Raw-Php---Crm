<?php 
	include "includes/config.php";
	require_once "includes/functions.php";
?>
<?php include "includes/header.php" ?>
<div id="page-wrapper">
	<div class="row"> 
		<div class="col-lg-12 col-md-12">
			<h2 class="page-header"><i class="fa fa-list-alt"></i> Quotes / Invoice HISTORY</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
		<?php 
			$result_orders = get_order_history1();
			if($result_orders->num_rows > 0) {
		?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-hover">
									<thead>
										<tr>
											<th>Invoice Id</th>
											<th>Ordering Date</th>
											<th>Service</th>
											<th>Package</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php while($row_orders = $result_orders->fetch_array()) { ?>
										<tr>
											<td>#<?= sprintf("%06d", $row_orders['id']) ?></td>
											<td><?= date('F j,Y', strtotime($row_orders['date'])) ?></td>
											<td><?= switch_service($row_orders['service_type']) ?></td>
											<td>
												<?= switch_package($row_orders['package']) ?> 
												<?php if($row_orders['package']==1){ ?><br><small class="text-info">(Amount: BDT<?= $row_orders['amount'] ?>)</small><?php } ?>
											</td>
											<td><a href="invoice/?order_id=<?= $row_orders['id'] ?>" target="_blank"><i class="fa fa-print"></i> Print Invoice</a></td>
										</tr>
									<?php } ?>	
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-danger text-center">
						<strong>No Invoice Found! </strong>
						To create a order <a href="orders/create-new/" class="alert-link">click here</a>.
					</div>
				</div>
			</div>
		<?php	}mysqli_free_result($result_orders); ?>
		</div>
	</div>
</div>
<?php 
	include "includes/footer.php";
?>