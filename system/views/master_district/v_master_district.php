<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="iddistrict" id="iddistrict">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('City Name');?> <red>*</red></label>
		<div class="col-md-5">
			<?php echo $city;?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('District Name');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="district_name" name="district_name"/>
		</div>
	</div>
</form>