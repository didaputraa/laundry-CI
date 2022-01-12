<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idemployee" id="idemployee">
	<div class="form-group">
		<label class="control-label col-md-1"><?php echo azlang('Outlet');?></label>
		<div class="col-md-4">
			<?php echo az_select_outlet();?>
		</div>
	</div>
	<div style="background-color:#A0D9F6;padding:10px 5px 0px 5px;border-radius:10px;">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Employee Name');?> <red>*</red></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="employee_name" name="employee_name"/>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Employee Code');?> <red>*</red></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="employee_code" name="employee_code"/>
					</div>
				</div>
			</div>
		</div>
	</div>

	<h4>
		Detail Kontak
	</h4>
	<div class="row">
		<div class="col-sm-6">
			<div style="background-color:#D1CDE6;padding:10px 5px 2px 5px;border-radius:10px;">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Address');?> <red>*</red></label>
					<div class="col-md-8">
						<textarea class="form-control" id="address" name="address" rows="11"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div style="background-color:#EF8762;padding:10px 5px 5px 5px;border-radius:10px;">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Email');?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="email" name="email"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Phone');?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="phone" name="phone" data-role="tagsinput"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Postal Code');?></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="postal_code" name="postal_code"/>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Province');?></label>
					<div class="col-md-8">
						<?php 
							echo az_select_province();
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('City');?></label>
					<div class="col-md-8">
						<?php 
							echo az_select_city();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</form>