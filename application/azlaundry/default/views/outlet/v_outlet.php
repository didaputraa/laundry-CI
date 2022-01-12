<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idoutlet" id="idoutlet">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Outlet Code');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="outlet_code" name="outlet_code"/>
		</div>
	</div>	
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Outlet Name');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="outlet_name" name="outlet_name"/>
		</div>
	</div>	
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Address');?></label>
		<div class="col-md-5">
			<textarea class="form-control" name="address" id="address"></textarea>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Phone');?></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="phone" name="phone" data-role="tagsinput"/>
		</div>
	</div>	
</form>