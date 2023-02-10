<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php"; 
?>
<?php 
	$page = isset($_GET['page']) ? $_GET['page'] : 1; $offset = ($page*25)-25;
	$view_user_type = isset($_GET['user_type']) ? $_GET['user_type'] : 'all';
	$selected_customers = isset($_GET['s_c']) ? $_GET['s_c'] : '';
?>
<?php 
	if(isset($_POST['update_order_info'])) {
		$id = $conn->real_escape_string($_POST['id']);
		$ufileds['rm'] = $conn->real_escape_string($_POST['rm']);
		$ufileds['status'] = $conn->real_escape_string($_POST['status']);
		
		$usql = UpdateTable('customer_orders', $ufileds, "id={$id}");
		if($conn->query($usql)) {
			header('Location: '.$base.'admin/orders/?view='.$id.'&smsg='.urlencode('Successfully Updated Data !'));
		} else {
			header('Location: '.$base.'admin/orders/?view='.$id.'&emsg='.urlencode($conn->error));
		}
	}
	if(isset($_GET['delete'])) {
		$id_numbers = $conn->real_escape_string($_GET['delete']);
		$id_number_array = explode(',', $id_numbers); $total_deleted = 0;
		foreach($id_number_array as $id){
			$sql = DeleteTable('users', "id={$id}"); $conn->query($sql); $total_deleted +=1;
		}
		header("Location: ".$base."admin/customer-list/?smsg=".urlencode("Total {$total_deleted} User Deleted !"));
	}
?>
<?php include "includes/header.php"; ?>
<?php if($selected_customers != ''){ ?>
<script type="text/javascript">
	selected_customers = "<?= addslashes($selected_customers) ?>";
	$(document).ready(function(){
		setTimeout(function(){
			$('#select-cus').trigger('click');
		<?php
			$sc_array = explode(',', $selected_customers);
			foreach($sc_array as $customer_id){
		?>
			$('[data-userid="<?= $customer_id ?>"]').find('input[type="checkbox"]').prop('checked', true);
		<?php } ?>
		},100);
	});
</script>
<?php } ?>
<div id="page-wrapper">
		<div class="row">
				<div class="col-lg-12">
						<h1 class="page-header"><i class="fa fa-sitemap"></i> CUSTOMER LIST</h1>
				</div>
		</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body">
				<?php if(!isset($_GET['view'])) { ?>
				<?php
					$total_data = get_total_rows('users'); $total_page = $total_data/25;
					$total_page = (is_float($total_page)) ? ($total_page+1) : $total_page;
					$result_customers	= get_all("users", "where status = 0 ORDER BY id DESC LIMIT 25 OFFSET {$offset}");
					if($result_customers->num_rows > 0) {
				?>
					<div class="table-responsive">
						<table class="table table-hover order-table">
							<thead>
								<tr>
									<th style="width: 100px;">#</th>
									<th>Customer Information</th>
									<th>Company</th>
									<th>Joined</th>
								</tr>
							</thead>
							<tbody>
							<?php
								while($row_cus = $result_customers->fetch_array()) {
									if($view_user_type == 'active') if(get_total_rows("customer_orders", "username='{$row_cus['username']}'") == 0) continue;
									if($view_user_type == 'inactive') if(get_total_rows("customer_orders", "username='{$row_cus['username']}'") > 0) continue;
									((file_exists('../images/users/'.$row_cus['id'].'-ppic.png'))) ? $userimg= 'images/users/'.$row_cus['id'].'-ppic.png': $userimg= 'images/male-icon.png';
							?>
								<tr class="clickable-row" data-href="admin/customer-list/?view=<?= urlencode($row_cus['username']) ?>" data-userid="<?= $row_cus['id'] ?>">
									<td><img src="<?= $userimg ?>" alt="User Image" class="img-responsive user-img"/></td>
									<td>
										<h4><?= $row_cus['first_name'] ?></h4>
										<p><i class="fa fa-envelope"></i> <?= $row_cus['email'] ?></p>
										<p><i class="fa fa-phone"></i> <?= $row_cus['mobile_number'] ?></p>
									</td>
									<td><?= $row_cus['address'] ?></td>
									<td><?= date('F j, Y', strtotime($row_cus['joined'])) ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						<div class="paging">
							<ul class="pagination">
							<?php for($page_i=1; $page_i<=$total_page; $page_i++){ ?>
								<li <?php if($page_i == $page) echo "class='active'"; ?>>
									<a href="javascript:void(0)" data-pn="<?= $page_i ?>" data-ut="<?= $view_user_type ?>" data-type="paging" data-ref="admin/orders/" class="send-remove"><?= $page_i ?></a>
								</li>
							<?php } ?>
							</ul>
						</div>
						<div class="form-group">
							<button class="btn btn-success" id="select-cus"><i class="fa fa-plus"></i> Select Customers</button>
							<button class="btn btn-info hidden-btns hidden-md hidden-lg send-remove" data-type="send"><i class="fa fa-envelope"></i> Send Message</button>
							<button class="btn btn-danger hidden-btns hidden-md hidden-lg send-remove" data-type="remove" data-ref="admin/customer-list/"><i class="fa fa-trash"></i> Remove Users</button>
							<a class="btn btn-warning hidden-btns hidden-md hidden-lg" href="admin/customer-list/"><i class="fa fa-times"></i> Cancel</a>
							<form class="form-inline visible-lg-inline-block visible-md-inline-block" action="" method="get">
								<div class="form-group">
									<select name="user_type" class="form-control">
										<option value="new">New</option>
										<option value="active">Active</option>
										<option value="inactive">Inactive</option>
									</select>
								</div>
								<div class="form-group">
									<input type="submit" value="Sort" class="btn btn-info" />
								</div>
							</form>
						</div> 
					</div>
				<?php } else { ?>
				<div class="alert alert-danger">
					<h3 class="text-center">No Customer !</h3>
				</div>
				<?php 
					}
					mysqli_free_result($result_customers);
				?>
				<?php } else { ?>
				<?php
					$row_userinfo	= get_user_info($_GET['view']);
					((file_exists('../images/users/'.$row_userinfo['id'].'-ppic.png'))) 
						? $userimg= 'images/users/'.$row_userinfo['id'].'-ppic.png'
						: $userimg= 'images/male-icon.png';
				?>
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="well rhead-section">
								<h4>CUSTOMER ID #<?= sprintf('%06d', $row_userinfo['id']); ?></h4>
								<p> &nbsp; </p>
								
								<div class="row">
									<div class="col-lg-5 col-md-5">
										<p><strong>Customer Name: </strong><?= $row_userinfo['first_name'].' '.$row_userinfo['last_name'] ?></p>
										<p><strong>Customer Email: </strong><?= $row_userinfo['email'] ?></p>
										<p><strong>Customer Moible Number: </strong><?= $row_userinfo['mobile_number'] ?></p>
										<p><strong>Company Name: </strong><?= $row_userinfo['address'] ?></p>
										<p><strong>Joined In Elance Network: </strong><?= date('F j,Y', strtotime($row_userinfo['joined'])) ?></p>
									</div>
									<div class="col-lg-4 col-md-4">
										<ul class="list-group">
											<li class="list-group-item list-group-item-success">Total Order: <span class="badge"><?= user_at_a_glance($row_userinfo['username'], 'total_order') ?></span></li>
											<li class="list-group-item list-group-item-warning">Total Paid: <span class="badge"><?= user_at_a_glance($row_userinfo['username'], 'total_paid') ?></span></li>
											<li class="list-group-item list-group-item-success">Total Due: <span class="badge"><?= user_at_a_glance($row_userinfo['username'], 'total_due') ?></span></li>
										</ul>
									</div>
									<div class="col-lg-3 col-md-3">
										<img src="<?= $userimg ?>" class="img-responsive user-img-view pull-right" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12">
									<?php
										$result_orders = get_order_history($row_userinfo['username']);
										if($result_orders->num_rows > 0) {
									?>
										<div class="order-history-title"><span>This Customer Previous Orders</span></div>
										<div class="table-responsive">
											<table class="table table-hover table-bordered">
												<tr><th>Date</th><th>Service</th><th>Pack</th><th>Amount</th></tr>
											<?php	while($row_orders = $result_orders->fetch_array()) { ?>
												<tr>
													<td><?= time_elapsed_string($row_orders['date']) ?></td>
													<td><a href="admin/orders/?view=<?= $row_orders['id'] ?>" target="_blank">#<?= $row_orders['id'] ?> - <?= switch_service($row_orders['service_type']) ?></a></td>
													<td><?= switch_package($row_orders['package']) ?></td>
													<td>Tk. <?= $row_orders['amount'] ?></td>
												</tr>
											<?php } ?>
											</table>
										</div>
									<?php } else { ?>
										<div class="order-history-title"><span>This Customer Has No Previous Orders</span></div>
									<?php
										}
										mysqli_free_result($result_orders);
									?>
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
</div>
<?php 
	include "includes/footer.php";
?>