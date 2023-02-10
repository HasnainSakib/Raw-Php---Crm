<?php
	include "../pages/includes/config.php";
	require_once "../pages/includes/functions.php";
?> 
<?php include "includes/header.php"; ?>
<div id="page-wrapper">
	<style>
		.viewport {position: relative; height: calc(100vh - 153px)}
		.viewport .welcome-msg {
			position: absolute;
			text-align: center;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%)
		}
	</style>
		<div class="row">
				<div class="col-lg-12"><h1 class="page-header">Admin Panel</h1></div>
		</div>
		<div class="row">
			<div class="viewport">
				<div class="welcome-msg">
					<h1>Welcome !</h1>
					<p>Elance Network Admin Panel</p>
				</div>
			</div>
		</div>
</div>
<?php include "includes/footer.php"; ?>