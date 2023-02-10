<?php
	include "includes/config.php";
	require_once "includes/functions.php";
	 
	if(isset($_GET['order_id'])) {
		$today = date("d-m-Y"); $orderno = $_GET['order_id'];
		$row_oi = get_order_info($orderno);
		$row_pi = get_payment_info($row_oi['payment_id']);
		$row_userinfo	= get_user_info($row_oi['username']);
		
		$name = $row_userinfo['first_name']; $mobile = $row_userinfo['mobile_number'];
		$email = $row_userinfo['email']; $company_name = $row_userinfo['address'];
		$city = $row_userinfo['city']; $district = $row_userinfo['district'];
		
		$payment = $row_pi['method'];
		$payment_number = $row_pi['payee_number']; $payment_trid = $row_pi['trxn_id'];
		$bank_name = $row_pi['bank_name'];
?>
<!doctype thml>
<html>
	<head>
		<title>Invoice Id : <?php echo sprintf('%06d', $orderno); ?></title>
		<link rel="stylesheet" href="<?= $base ?>vendor/bootstrap/css/bootstrap.min.css" />
		<style>
			/*----- Thank You Page ----*/
			#thank-you {
				background-color: #f9f8f8;
			}
			#thank-you h2.successfull {
				text-align: center;
				margin: 2em 0;
				font-size: 26px;
				color: #fdb90b;
				text-shadow: 1px 1px 1px #666;
			}
			#thank-you span.p-title {
				text-align: center;
				margin: 1.5em 0;
				background-color: #fdb90b;
				color: #f9f8f8;
				font-size: 20px;
				padding: .3em .6em;
				display: inline-block;
			}
			#thank-you .separator {
				height: 10px;
			}
			#thank-you .background-white {
				background: #fff;
				border: 1px solid #fdb90b;
				padding: .5em;
			}
			#thank-you .your-data, #thank-you .your-bill {
				text-align: center;
			}
			#thank-you .your-data table{
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				border: 0;
			}
			#thank-you .your-data table tr td{
				padding: 2px;
				font-size: 12px;
				text-align: left;
				color: #666
			}
			#thank-you .your-bill p {
				text-align: right;
			}
			#thank-you .your-bill .invoice-print {
				color: #333;
				text-decoration: underline;
				font-size: 18px;
				font-weight: 600;
				font-family: 'Times New Roman';
				cursor: pointer;
			}
			#thank-you .invoice {
				position: relative;
				width: 800px;
				margin: 50px auto;
				text-align: left;
				padding: 50px 65px;
				box-shadow: 0px 0px 20px #ccc;
			}
			#thank-you .invoice-top img {
				max-width: 100%;
				height: 100px;
			}
			#thank-you .invoice-top .tagline h2.company-name{
				font-weight: bold;
				font-size: 28px;
				line-height: 1em;
				text-transform: none;
				margin: 0;
			}
			#thank-you .invoice-top .tagline p{
				text-align: left;
				color: #888;
				font-size: 14px;
				margin-bottom: 0;
				line-height: 19.6px;
			}
			#thank-you .invoice-top .qr {
				text-align: right;
			}
			#thank-you .invoice-middle .invoice-id {
				margin-top: 60px;
				margin-bottom: 40px;
			}
			#thank-you .invoice-middle h1{
				font-size: 50px;
				font-family: 'impact';
				color: #396E00;
				line-height: 50px;
			}
			#thank-you .invoice-middle .invoice-info table{
				width: auto;
				border-collapse: collapse;
				border-spacing: 0;
			}
			#thank-you .invoice-middle .invoice-info table tr td{
				padding: 1px 3px;
			}
			#thank-you .invoice-middle .invoice-bill-to p {
				text-align:left;
				margin-bottom: 2px;
				font-size: 14px;
				color: #000;
			}
			#thank-you .invoice-table .itemLists {
				width: 100%;
				border-collapse: collapse;
				border-spacing: 0;
				margin-top: 40px;
				font-size: 14px;
			}
			#thank-you .invoice-table .itemLists td,#thank-you .invoice-table .itemLists th{
				padding: 10px;
			}
			#thank-you .invoice-table .itemLists td p.ipnaid {
				font-size: 13px;
				color: #333;
				text-align: left;
				margin-bottom: 0px;
			}
			#thank-you .invoice-table .itemLists td p.ipnaid.ipname {
				font-weight: 600;
			}
			#thank-you .invoice-table .itemLists thead tr{
				border-bottom: 2px solid #aaa;
				color: #333;
				font-weight: 600;
			}
			#thank-you .invoice-table .itemLists tbody tr{
				border-bottom: 1px solid #ccc;
				color: #333;
				font-weight: 500;
			}
			#thank-you .invoice-table .itemTotal {
				width: 35%;
				border-collapse: collapse;
				border-spacing: 0;
				margin-top: 10px;
				font-size: 14px;
				float: right;
				color: #333;
			}
			#thank-you .invoice-table .itemTotal tr.subtotal {
				color: #396E00;
				border-top: 2px dotted #aaa;
				font-size: 16px;
			}
			#thank-you .invoice-table .itemTotal tr td{
				padding: 5px;
			}
			#thank-you .invoice-table .payment-info {
				color: #888;
				font-size: 12px;
				margin-top: 20px;
				width: 100%;
				font-weight: normal;
			}
			#thank-you .paid-stamp {
				max-width: 25%;
				display: block;
				margin: 0 auto;
				position: absolute;
				top: 215px;
				right: 71px;
				opacity: .5;
			}
			@media print {
				body * {
					visibility: hidden;
				}
				.invoice, .invoice * {
					visibility: visible;
				}
				.invoice {
					width: 100%;
					position: absolute;
					left: 0;
					top: 0;
				}
			}
		</style>
		<script>window.onload = window.print();</script>
	</head>
	<body>
		<div id="thank-you">
			<div class="your-bill">				
				<div class="background-white">
					<p><span class="invoice-print" onclick="window.print()"><i class="fa fa-print"></i> Print</span></p> 
					<div class="invoice">
						<div class="row invoice-top">
							<div class="col-md-3 col-sm-3 col-xs-3 logo">
								<img src="<?= $base ?>images/logo.png">
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 tagline">
								<h2 class="company-name">Friends Network</h2>
								<div class="separator"></div>
								<p class="company-address">Suite # 618, 87 BNS Center, Sector # 7, Uttara, Dhaka-1230</p>
								<p class="company-contact">+88 02 48954862 | info@friendsnetwork.com</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 qr">
								
							</div>
						</div>
						<div class="row invoice-middle">
							<div class="col-md-12 invoice-id">
								<h1>INVOICE</h1>
								<div class="separator"></div>
								<h3>#<?php echo sprintf('%06d', $orderno); ?></h3>
							</div>
							<div class="clearfix"></div>

							<div class="col-md-6 col-sm-6 col-xs-6 invoice-info">
								<table border="0">
									<tr><td>Issue Date</td><td>:</td><td><?php echo date("d-m-Y (h:i A)") ?> </td></tr>
									<tr><td>Net</td><td>:</td><td> <?php echo $row_oi['rm']; ?> </td></tr>
									<tr><td>Currency</td><td>:</td><td> BDT </td></tr>
									<tr><td>Payment Type</td><td>:</td><td><?php if($payment=='bkash')echo 'bKash';else if($payment=='bank')echo 'Bank Payment'; else echo 'Due'; ?> </td></tr>
								</table>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 invoice-bill-to">
								<p><u>Bill To: </u></p>
								<p><strong><?php echo $name; ?></strong></p>
								<p><?php echo $company_name; ?></p>
								<p><?php echo $city.' '.$district; ?></p>
								<p><?php echo $mobile; ?></p>
								<p><?php echo $email; ?></p>
							</div>
						</div>
						<div class="row invoice-table">
							<div class="col-md-12">
								<table border="0" class="itemLists">
									<thead>
										<tr><th>Sl No</th><th>Description</th><th>Amount</th></tr>
									</thead>
									<tbody>
										<tr>
											<td>1</td>
											<td>
												<p class="ipnaid ipname">Service Name: <?= switch_service($row_oi['service_type']) ?></p>
												<p class="ipnaid">Package: <?= switch_service($row_oi['package']) ?></p>
											<?php if($row_oi['package'] == 1) { ?>
												<p class="ipnaid text-info">Budget Amount: Tk.<?= $row_oi['amount'] ?></p>
											<?php } ?>
											</td>
											<td>Tk.<?= $row_oi['amount'] ?></td>
										</tr>
									</tbody>
								</table>
								<table class="itemTotal" border="0">
									<tr><td>Total</td><td>Tk. <?= $row_oi['amount'] ?></td></tr>
									<tr><td>Service Charge</td><td>0</td></tr>
									<tr class="subtotal"><td>Subtotal</td><td>Tk.<?= $row_oi['amount'] ?></td></tr>
								</table>
								<div class="clearfix"></div>
								
								<div class="separator"></div>
								<div class="payment-info">
									Payment Details: <?php if($payment == 'bkash') {echo 'bKash';} else if($payment == 'bank'){echo 'Bank Payment';} else {echo 'Due';} ?>
									<?php if($payment == 'bkash') { ?>
									, Number: <?php echo $payment_number; ?> 
									, Trxn ID: <?php echo $payment_trid; ?>
									<?php } else if($payment == 'bank'){ ?>
									, Bank Name: <?= $bank_name ?>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php if($row_pi['remark'] == 1){ ?>
						<img src="<?= $base ?>images/stamp.png" class="paid-stamp" />
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	} else {
		exit(header('Location: orders.php'));
	}
?>