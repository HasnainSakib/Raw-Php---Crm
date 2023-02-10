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
<div id="page-wrapper">
		<div class="row">
				<div class="col-lg-12">
						<h1 class="page-header"><i class="fa fa-rocket"></i> Invoice list</h1>
						
						

							<?php 
							
							if (isset($_GET["id"])) {
								$inid = $_GET["id"];
							$product_query = "SELECT * FROM invoice where id= $inid";
							$run_query = mysqli_query($conn,$product_query);
							if(mysqli_num_rows($run_query) > 0){
		$sl = 0;
										while($row = mysqli_fetch_array($run_query)){
											$sl++;
											$orderid   = $row['id'];
											$id   = $row['date'];
											$fname   = $row['service'];
											$lname = $row['package'];
											$service = $row['payment-type'];
											$date = $row['amount'];
											$mobile = $row['bill_to'];
											?>
							
							
							
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
								<h2 class="company-name">Elance Network</h2>
								<div class="separator"></div>
								<p class="company-address">Suite # 618, 87 BNS Center, Sector # 7, Uttara, Dhaka-1230</p>
								<p class="company-contact">+88 02 48954862 | info@dhakasolution.com</p>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 qr">
								<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo urlencode('http://dhakasolution.com/'); ?>&choe=UTF-8" />
							</div>
						</div>
						<div class="row invoice-middle">
							<div class="col-md-12 invoice-id">
								<h1>INVOICE</h1>
								<div class="separator"></div>
								<h3>#<?php echo $row['id']; ?></h3>
							</div>
							<div class="clearfix"></div>

							<div class="col-md-6 col-sm-6 col-xs-6 invoice-info">
								<table border="0">
									<tr><td>Issue Date</td><td>:</td><td><?php echo $row['date']; ?> </td></tr>
									<tr><td>Net</td><td>:</td><td> 0 </td></tr>
									<tr><td>Currency</td><td>:</td><td> BDT </td></tr>
									<tr><td>Payment Type</td><td>:</td><td><?php echo $row['payment-type']; ?> </td></tr>
								</table>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6 invoice-bill-to">
								<p><u>Bill To: </u></p>
								<p><strong><?php echo $row['bill_to']; ?></strong></p>
								<p>Elance IT</p>
								<p>+8801734821060</p>
								<p>15203073@iubat.edu</p>
							
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
												<p class="ipnaid ipname">Service Name: <?php echo $row['service']; ?></p>
												<p class="ipnaid">Package: <?php echo $row['package']; ?></p>
											
												<p class="ipnaid text-info">Budget Amount: Tk.<?php echo $row['amount']; ?> </p>
											
											</td>
											<td>Tk.<?php echo $row['amount']; ?></td>
										</tr>
									</tbody>
								</table>
								<table class="itemTotal" border="0">
									<tr><td>Total</td><td>Tk. <?php echo $row['amount']; ?></td></tr>
									<tr><td>Service Charge</td><td>0</td></tr>
									<tr class="subtotal"><td>Subtotal</td><td>Tk.<?php echo $row['amount']; ?></td></tr>
								</table>
								<div class="clearfix"></div>
								
								<div class="separator"></div>
								<div class="payment-info">
									Payment Details: 
									
									, Number: 
									, Trxn ID: 
									
									, Bank Name: 
									
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>
		</div>
				
							
							
										 
							
							
							<?php
							
							
							
							
							
							
		}
							}
							
							
							}
							
							?>
				
					
		
				</div>
		</div>
		<div class="row">
				
		</div>
</div>
<?php 
	include "includes/footer.php";
?>