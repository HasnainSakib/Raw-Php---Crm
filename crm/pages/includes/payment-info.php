<div class="row payment-info" id="bkash">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<h4 class="text-center">Enter Payment Info</h4>
			<form id="" action="" method="POST"> 
				<input type="hidden" name="payment" />
				<input type="hidden" name="username" value="<?= $userinfo['username'] ?>" />
				<input type="hidden" name="service_type" value="<?= $_GET['service'] ?>" />
				<input type="hidden" name="package" value="<?= $_GET['package'] ?>" />
				<input type="hidden" name="amount" value="<?= isset($_GET['amount']) ? $_GET['amount'] : 0; ?>" />
				<input type="hidden" name="payment_type" value="bkash" />
				
				<div class="form-group g-form">
					<label>bKash Number</label>
					<input type="text" name="pn" class="form-control" required />
				</div>
				<div class="form-group g-form">
					<label>Transaction Id</label>
					<input type="text" name="trnid" class="form-control"/>
				</div>
				<input type="hidden" name="ref" class="form-control"/>
				<div class="form-group g-form">
					<input type="submit" class="btn btn-success" value="Submit"/>
					<button class="btn btn-warning cancel-payment">Cancel</button>
				</div>
			</form>
			<div class="alert alert-info">
				<p>01. Go to your bKash Mobile Menu by dialing <strong>*247#</strong></p>
				<p>02. Choose <strong>"Cash Out"</strong></p>
				<p>02. Choose <strong>"From Agent"</strong></p>
				<p>03. Enter the bKash Agent Number <strong>0197 883 8878</strong></p>
				<p>04. Enter the amount <strong class="bkash-amount"><?= $pack_price ?></strong></p>
				<p>05. Now enter your bKash Mobile Menu PIN to confirm the transaction</p>
				<p>Done! You'll receive a transaction id. Fill it below</p>
				<img src="images/bkash-payment-system.png" class="img-responsive" />
			</div>
		</div>
	</div>
</div>
<div class="row payment-info" id="bank">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<h4 class="text-center">Enter Payment Info</h4>
			<form enctype="multipart/form-data" action="" method="POST">
				<input type="hidden" name="payment" />
				<input type="hidden" name="username" value="<?= $userinfo['username'] ?>" />
				<input type="hidden" name="service_type" value="<?= $_GET['service'] ?>" />
				<input type="hidden" name="package" value="<?= $_GET['package'] ?>" />
				<input type="hidden" name="amount" value="<?= isset($_GET['amount']) ? $_GET['amount'] : 0; ?>" />
				<input type="hidden" name="payment_type" value="bank" />
				
				<div class="form-group">
					<div class="form-group">
						<label>Select Bank Name</label>
						<select name="bank_name" class="form-control">
							<option value="thecitybank" selected>City Bank</value>
							<option value="bracbank">Brac Bank</value>
						</select>
					</div>
					
					<div class="alert alert-info bank-info">
						<p><u>Pay Tk <?= $pack_price ?></u></p>
						<strong>City Bank</strong><br/>A/C Name: SAIFUL ISLAM<br/>A/C Number: 2401835373001
					</div>
				</div>
				<div class="form-group">
					<label>
						Upload Attachment 
						<a href="javascript:void(0)" 
							data-toggle="tooltip"
							title="After submitting the money to the bank, Bank will give you Slip/Token.
							Capture a photo of that Slip/Token with your mobile phone camera. And Upload it here..">
							<i class="fa fa-question-circle"></i>
						</a>
					</label>
					<div class="input-group input-group-sm">
						<span class="input-group-btn">
							<span class="btn btn-default btn-file">
								Select File <span class="glyphicon glyphicon-folder-open"></span>
								<input name="attatchment" id="attachment-input" type="file" required>
							</span>
						</span>
						<input class="form-control" readonly="" type="text">
					</div>
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Submit"/>
					<button class="btn btn-warning cancel-payment">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="row payment-info" id="due">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<h4 class="text-center">Are you confirm to due order ?</h4>
			<form enctype="multipart/form-data" action="" method="POST">
				<input type="hidden" name="payment1" />
				<input type="hidden" name="username" value="<?= $userinfo['username'] ?>" />
				<input type="hidden" name="service_type" value="<?= $_GET['service'] ?>" />
				<input type="hidden" name="package" value="<?= $_GET['package'] ?>" />
				<input type="hidden" name="amount" value="<?= isset($_GET['amount']) ? $_GET['amount'] : 0; ?>" />
				<input type="hidden" name="payment_type" value="due" />
				
				
			
				<div class="form-group">
					<input type="submit" class="btn btn-success" value="Submit"/>
					<button class="btn btn-warning cancel-payment">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>