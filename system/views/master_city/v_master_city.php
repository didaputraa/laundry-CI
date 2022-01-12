<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idcity" id="idcity">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Province Name');?> <red>*</red></label>
		<div class="col-md-5">
			<?php echo $province;?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('City Name');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="city_name" name="city_name"/>
		</div>
	</div>
</form>