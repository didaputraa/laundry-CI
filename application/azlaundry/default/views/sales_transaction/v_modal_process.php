<form class="form-horizontal" id="form_process">
	<input type="hidden" name="idtransaction_group" id="idtransaction_group">
	<div class="form-group">
		<label class="control-label col-sm-4"><?php echo azlang('Pay');?></label>
		<div class="col-sm-4">
			<select class="form-control" id="pay" name="pay">
				<option value="PAID"><?php echo azlang('PAID');?></option>
				<option value="NOT PAID YET"><?php echo azlang('NOT PAID YET');?></option>
			</select>
		</div>
	</div>
	<div class="form-group container-pay-date">
		<label class="control-label col-sm-4"><?php echo azlang('Pay Date');?></label>
		<div class="col-sm-4">
			<?php echo $pay_date;?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-4"><?php echo azlang('Status');?></label>
		<div class="col-sm-4">
			<select class="form-control" id="transaction_group_status" name="transaction_group_status">
				<option value="NEW"><?php echo azlang('NEW');?></option>
				<option value="PROGRESS"><?php echo azlang('PROGRESS');?></option>
				<option value="FINISH"><?php echo azlang('FINISH');?></option>
				<option value="ACCEPTED"><?php echo azlang('ACCEPTED');?></option>
			</select>
		</div>
	</div>
</form>