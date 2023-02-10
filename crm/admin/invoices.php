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
					<span style="float:right;"> 	 <a class="btn btn-primary">Addb new invoice <a/></span>
						<?php
	
		$sql = "SELECT * FROM invoice";

		$result = $conn->query($sql);
		echo"<table class='table' align=center><tr><th>id</th> <th>Date</th><th>Service</th><th>package</th><th>payment/th><th>amount</th><th>Action</th><th>Action</th><th>Action</th></tr>";

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				// HERE EVERY TABLE DATA WILL CONTAIN DIFFRENT ID TO PRINT
				echo "<tr><td id='a".$row['id']."'>".$row['id']."</td><td id='b".$row['id']."'>".$row['date']."</td>";
				echo "<td id='c".$row['id']."'>".$row['service']."</td><td id='d".$row['id']."'>".$row['package']."</td>";
				echo "<td id='e".$row['id']."'>".$row['payment-type']."</td><td id='f".$row['id']."'>".$row['amount']."</td>";
				

				// EDIT BUTTON CREATION
				
				// DELETE BUTTON CREATION
				echo "<td><form action='' method='GET'><input type='submit' name='delete".$row['id']."' value='Delete'></form></td>";
				// PDF BUTTON CREATION
			
				?>
				<td> <a class="btn btn-success"href="admin/editinvoice.php?id=<?php echo $row['id'];?>"> view  </a> </td>
				<td> <a class="btn btn-success"href="admin/editinvoice.php?id=<?php echo $row['id'];?>"> view  </a> </td> </tr> </table>
				<?php

				// UPDATE CODE STARTS FROM HERE
				if(isset($_GET[$row['id']])){
					echo"<form action='' method='POST'><div class='p' id='close'>";// CLASS P IS USED TO DECORATION AND ID CLOSE IS USED TO CLOSE THE POPUP PAGE
					echo"Update Information</br></br>";
					echo "order Id: <input type='text' name='id' value=".$row['id'].">";
					echo "</br></br>";
					echo "date: <input type='text' name='name' value=".$row['date'].">";
					echo "</br></br>";
					echo "service: <input type='text' name='gender' value=".$row['service'].">";
					echo "</br></br>";
					echo "payment : <input type='text' name='department' value=".$row['payment-type'].">";
					echo "</br></br>";
					echo "amount: <input type='text' name='programs' value=".$row['amount'].">";
					echo "</br></br>";
					

					echo"<input type='submit' name = 'submit' value='Update'>";
					echo"<input type='submit' name = 'cancle' value='Cancle'>";
					echo "</div></form>";

					
					if(isset($_POST['submit'])){
						$id = $_POST["id"];
						$name = $_POST["name"];
						$gender = $_POST["gender"];
						$departments = $_POST["department"];
						$programs = $_POST["programs"];
						

						$ssql = "UPDATE product SET id='$id', date='$name', service='$gender', payment-type='$departments', amount='$programs'
						WHERE id=".$row['id']."";
						
						if ($conn->query($ssql) === TRUE) {
						echo "<script type='text/javascript'>alert('Submitted successfully!')</script>";
						} else {
						echo "Upadate Unsucessful". $conn->error;
						}

					}
					if(isset($_POST['cancle'])){
						echo "<script>document.getElementById('close').style.display='none'</script>";
					}
					
				}

				// DELETE CODE STARTS FORM HERE
				if(isset($_GET['delete'.$row['id']])){
					$delete = "DELETE FROM invoice WHERE id=".$row['id']."";
					if ($conn->query($delete) === TRUE) {
					echo "<script type='text/javascript'>alert('Deleted successfully!')</script>";
					echo "<meta http-equiv='refresh' content='0'>"; // THIS IS FOR AUTO REFRESH CURRENT PAGE
					} else {
					echo "Delete Unsucessful". $conn->error;
					}
				}
				
				
				// PDF STARTS FROM HERE
				



			}echo"</table>";
		}else{
				echo "No search found!!!";
		}
	$conn->close();
	 ?>

				</div>
		</div>
		<div class="row">
				
		</div>
</div>
<?php 
	include "includes/footer.php";
?>