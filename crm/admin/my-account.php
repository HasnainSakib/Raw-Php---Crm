<?php
	include "includes/config.php";
	require_once "../pages/includes/functions.php";
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
		
		<div class="col-lg-6 col-md-6" >
			<ul class="list-group">
				<li class="list-group-item list-group-item-success">Total Order: <span class="badge"><?= user_at_a_glance1( 'total_order') ?></span></li>
				<li class="list-group-item list-group-item-warning">Total Paid: <span class="badge"><?= user_at_a_glance1('total_paid') ?></span></li>
				<li class="list-group-item list-group-item-success">Total Due: <span class="badge"><?= user_at_a_glance1( 'total_due') ?></span></li>
			</ul>
			
		</div>
	</div>

	<?php 
		$result_orders = get_order_history1();
		if($result_orders->num_rows > 0) {
	?>
	<div class="row">
		<div class="col-lg-12">
			<div class="order-history-title"><span>All ORDER HISTORY</span></div>
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