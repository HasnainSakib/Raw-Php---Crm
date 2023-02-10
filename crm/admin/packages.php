<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
	$edit = isset($_GET['edit']) ? $_GET['edit'] : 0;
?> 
<?php
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		$package_id = (isset($_POST['package_id'])) ? $conn->real_escape_string($_POST['package_id']) : null;
		$pi = '<li><div class="tooltip-wide">';
		$pi.= str_replace("\r\n", '</div></li><li><div class="tooltip-wide">', $_POST['info']);
		$pi.= '</div></li>';
		$pack_info = str_replace('<li><div class="tooltip-wide"></div></li>', '', $pi);
		
		$fields['pack_name'] = $conn->real_escape_string($_POST['title']);
		$fields['pack_name'] = $conn->real_escape_string($_POST['title']);
		$fields['price'] = $conn->real_escape_string($_POST['price']);
		$fields['amount'] = $conn->real_escape_string($_POST['amount']);
		$fields['pack_info'] = $conn->real_escape_string($pack_info); 
		isset($_POST['service_id'])  ? $fields['service_id'] = $conn->real_escape_string($_POST['service_id']) : null; 
		
		$sql = (isset($_POST['package_edit']))
		? UpdateTable("packages", $fields, "id='{$package_id}'") : InsertInTable("packages", $fields);
		if($conn->query($sql)) header('Location: '.$base.'admin/packages/?smsg='.urlencode('Successfully updated !'));
		else header('Location: '.$base.'admin/packages/?emsg='.urlencode($conn->error));
	} else if(isset($_GET['delete'])) {
		if($conn->query(DeleteTable("packages", "id='{$_GET['delete']}'")))
			header('Location: '.$base.'admin/packages/?smsg='.urlencode('Successfully deleted packages !'));
	}
?>
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12"><h1 class="page-header"><i class="fa fa-gift"></i> PACKAGES</h1></div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		<?php if(!isset($_GET['edit']) && !isset($_GET['add_new'])){ ?>
		<?php
			// $result_services = get_all("packages", "GROUP BY service_id");
			$result_services = "SELECT * FROM `packages` ORDER BY `packages`.`service_id` DESC";
			$run = $conn->query($result_services);
			while($row_ser = $run->fetch_array()) {
		?>
			<div class="panel panel-default">
				<div class="panel-heading"><?= switch_service($row_ser['service_id']) ?></div>
				<div class="panel-body">
					<div class="flex packages-flex">
					<?php 
						$result_packages = get_packages($row_ser['service_id']);
						if($result_packages->num_rows > 0) {
							while($row_pk = $result_packages->fetch_array()) {
					?>
						<div class="flex-items">
							<div class="titlef"><?= $row_pk['pack_name'] ?></div>
							<div class="price">Tk <?= $row_pk['price'] ?></div>
							<ul class="list-info">
								<?= $row_pk['pack_info'] ?>
							</ul>
							<a href="admin/packages/?edit=<?= $row_pk['id'] ?>" class="wpc-btn btn-confirm">
								<i class="fa fa-pencil"></i> Edit
							</a>
						</div>
					<?php
							}
						}
						mysqli_free_result($result_packages);
					?>
					</div>
				</div>
			</div>
		<?php
			}
			mysqli_free_result($result_services)
		?>
			<p class="text-center">
				<a href="admin/packages/?add_new=1">
					<button class="btn btn-success"><i class="fa fa-plus"></i> Add New</button>
				</a>
			</p>
		<?php } else if(isset($_GET['edit']) && !isset($_GET['add_new'])) { ?>
		<?php
			$row_pk = get_single_data("packages", "id='{$_GET['edit']}'");
			$pack_info = str_replace(array('<li><div class="tooltip-wide">','</div></li>'), array("", "\n"), $row_pk['pack_info']);
		?>
			<div class="col-lg-6 col-md-6 col-md-offset-3">
				<form class="well" action="" method="post">
					<input type="hidden" name="package_edit" />
					<input type="hidden" name="package_id" value="<?= $row_pk['id'] ?>" />
					
					<div class="form-group g-form">
						<label>Pakage Title</label>
						<input type="text" name="title" class="form-control" value="<?= $row_pk['pack_name'] ?>" />
					</div>
					<div class="form-group g-form">
						<label>Pakage Price</label>
						<div class="input-group">
							<span class="input-group-addon">Tk</span>
							<input type="text" name="price" class="form-control" value="<?= $row_pk['price'] ?>"  />
						</div>
					</div>
					<div class="form-group g-form">
						<label>Amount</label>
						<input type="text" name="amount" class="form-control" value="<?= $row_pk['amount'] ?>" />
					</div>
					<div class="form-group g-form">
						<label>Pakage Info</label>
						<textarea type="text" name="info" class="form-control" rows="5" /><?= $pack_info ?></textarea>
					</div>
					<div class="form-group">
						<input type="submit" value="Edit" class="btn btn-info"/>
						<a href="admin/packages/?delete=<?= $row_pk['id'] ?>" class="btn btn-danger"/>Delete</a>
						<a href="admin/packages/" class="btn btn-warning"/>Cancel</a>
					</div>
				</form>
			</div>
		<?php } else { ?>
			<div class="col-lg-6 col-md-6 col-md-offset-3">
				<form class="well" action="" method="post">
					<input type="hidden" name="package_add" />
					
					<div class="form-group">
						<label>Select Service</label>
						<select name="service_id" class="form-control">
							<option value="1">Facebook Advertisement</option>
							<option value="2">Web Hosting</option>
							<option value="3">Web Design</option>
							<option value="4">eCommerce Development</option>
							<option value="5">Software Development</option>
						</select>
					</div>
					<div class="form-group g-form">
						<label>Pakage Title</label>
						<input type="text" name="title" class="form-control" />
					</div>
					<div class="form-group g-form">
						<label>Pakage Price</label>
						<input type="text" name="price" class="form-control" />
					</div>
					<div class="form-group g-form">
						<label>Amount</label>
						<input type="text" name="amount" class="form-control" />
					</div>
					<div class="form-group g-form">
						<label>Pakage Info</label>
						<textarea type="text" name="info" class="form-control" rows="5"/></textarea>
					</div>
					<div class="form-group">
						<input type="submit" value="Add New" class="btn btn-warning"/>
						<a href="admin/packages/" class="btn btn-danger"/>Cancel</a>
					</div>
				</form>
			</div>
		<?php } ?>
		</div>
		
		<!--form class="well" action="https://46r68.api.infobip.com/tts/3/single" method="post">
			
			<div class="form-group g-form">
				<label>from</label>
				<input type="text" name="from" class="form-control" />
			</div>
			<div class="form-group g-form">
				<label>to</label>
				<input type="text" name="to" class="form-control" />
			</div>
			<div class="form-group g-form">
				<label>text</label>
				<input type="text" name="text" class="form-control" />
			</div>
			<div class="form-group g-form">
				<label>language</label>
				<textarea type="text" name="language" class="form-control height-2x" /></textarea>
			</div>
			<div class="form-group g-form">
				<input type="text" name="audioFileUrl"  class="form-control" />
			</div>
			<div class="form-group">
				<input type="submit" value="Add New" class="btn btn-warning"/>
				<a href="admin/packages/" class="btn btn-danger"/>Cancel</a>
			</div>
		</form-->
	</div>
</div>
<?php include "includes/footer.php"; ?>