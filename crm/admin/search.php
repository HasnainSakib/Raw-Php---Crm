<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
?>
<?php  
	$search = (isset($_GET['q']) && !empty($_GET['q'])) ? $conn->real_escape_string($_GET['q']) : "asdfg;lkjh"; 
	$ssql = "LIKE '%".$search."%'";
	$customers = get_some_data("users", "first_name {$ssql} OR username {$ssql} OR mobile_number {$ssql}");
	$orders = get_some_data("customer_orders", "id {$ssql} OR username {$ssql} OR ad_manager_id {$ssql}");
	$messages = get_some_data("contact", "Id {$ssql} OR name {$ssql} OR subject {$ssql} OR message {$ssql}");
	$support_tickets = get_some_data("replies", "ticket_id {$ssql} OR username {$ssql} OR message {$ssql}");
	if(($customers->num_rows+$orders->num_rows+$messages->num_rows+$support_tickets->num_rows) == 0) $not_found = true;	
	function h_w($content, $word){$content = preg_replace("/{$word}/i", "<em>\$0</em>", $content); return $content;}
	function m_wc($content, $word){str_ireplace($word, "**", $content, $counter); return (($counter > 0) ? '<em>'.$counter.'</em>' : $counter).' matched found';}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
		<div class="row">
				<div class="col-lg-12">
						<h1 class="page-header"><i class="fa fa-search "></i> Search</h1>
				</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading"><h4 class="panel-title">Keyword: <?= $conn->real_escape_string($_GET['q']); ?></h4></div>
					<div class="panel-body">
					<?php if(isset($not_found)){ ?>
						<div class="search-group"><h4 class="group-striped">No match found !</h4></div>
					<?php } ?>
					<?php if($customers->num_rows > 0){ ?>
						<div class="search-group">
							<h4 class="group-striped">Customers</h4>
							<div class="group-items">
							<?php while($row_cus = $customers->fetch_array()) { ?>
								<a href="admin/customer-list/?view=<?= urlencode($row_cus['username']) ?>" class="g-items">
									<h5 class="gitem-title"><?= h_w(htmlspecialchars($row_cus['first_name']), $search) ?></h5>
									<p><span>Email: <?= h_w(htmlspecialchars($row_cus['email']), $search) ?></span><span>Mobile: <?= h_w(htmlspecialchars($row_cus['mobile_number']), $search) ?></span></p>
								</a>
							<?php } mysqli_free_result($customers); ?>
							</div>
						</div>
					<?php } if($orders->num_rows > 0) { ?>
						<div class="search-group">
							<h4 class="group-striped">Orders</h4>
							<div class="group-items">
							<?php while($row_od = $orders->fetch_array()) { ?>
								<a href="admin/orders/?view=<?= urlencode($row_od['id']) ?>" class="g-items">
									<h5 class="gitem-title">Order ID: #<?= h_w(htmlspecialchars(sprintf('%06d', $row_od['id'])), $search) ?></h5>
									<p><span>User: <?= h_w(htmlspecialchars($row_od['username']), $search) ?></span><span>Ad Manager ID: <?= h_w(htmlspecialchars($row_od['ad_manager_id']), $search) ?></span></p>
								</a>
							<?php } mysqli_free_result($orders); ?>
							</div>
						</div>
					<?php } if($messages->num_rows > 0) { ?>
						<div class="search-group">
							<h4 class="group-striped">Messages</h4>
							<div class="group-items">
							<?php while($row_msg = $messages->fetch_array()) { ?>
								<a href="admin/messages/?view=<?= urlencode($row_msg['Id']) ?>" class="g-items">
									<h5 class="gitem-title">From: <?= h_w(htmlspecialchars($row_msg['name']), $search) ?></h5>
									<p><span>Subject: <?= h_w(htmlspecialchars($row_msg['subject']), $search) ?></span><span>Message: <?= m_wc(htmlspecialchars($row_msg['message']), $search) ?></span></p>
								</a>
							<?php } mysqli_free_result($messages); ?>	
							</div>
						</div>
					<?php } if($support_tickets->num_rows > 0) { ?>	
						<div class="search-group">
							<h4 class="group-striped">Support Tickets</h4>
							<div class="group-items">
							<?php while($row_st = $support_tickets->fetch_array()) { ?>
								<a href="admin/support/tickets/view/<?= urlencode($row_st['ticket_id']) ?>" class="g-items">
									<h5 class="gitem-title">Ticket ID: <?= h_w(htmlspecialchars($row_st['ticket_id']), $search) ?></h5>
									<p><span>Username: <?= h_w(htmlspecialchars($row_st['username']), $search) ?></span><span>Content: <?= m_wc(htmlspecialchars($row_st['message']), $search) ?></span></p>
								</a>
							<?php } mysqli_free_result($support_tickets); ?>	
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