<form class="form-horizontal az-form form-customer" id="form" name="form" method="POST">
	<input type="hidden" name="idcustomer" id="new_idcustomer">
	<?php 
        if (strlen($this->session->userdata('idoutlet')) == 0) {
    ?>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Outlet');?> <red>*</red></label>
		<div class="col-md-5">
			<?php echo az_select_outlet('cidoutlet', '', 'idoutlet');?>
		</div>
	</div>	
	<?php 
		}
	?>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Customer Code');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="customer_code" name="customer_code"/>
		</div>
	</div>	
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Customer Name');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="customer_name" name="customer_name"/>
		</div>
	</div>	
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Email');?></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="email" name="email"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Phone');?></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="phone" name="phone" data-role="tagsinput"/>
		</div>
	</div>	
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Address');?></label>
		<div class="col-md-5">
			<textarea class="form-control" name="address" id="address"></textarea>
		</div>
	</div>	
</form>