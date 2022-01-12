<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idsupplier" id="idsupplier">
	<div style="background-color:#D1CDE6;padding:10px 5px 0px 5px;border-radius:10px;">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Supplier Name');?> <red>*</red></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="supplier_name" name="supplier_name"/>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Supplier Code');?> <red>*</red></label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="supplier_code" name="supplier_code"/>
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
			<div style="background-color:#F3B3B3;padding:10px 5px 2px 5px;border-radius:10px;">
				<div class="form-group">
					<label class="control-label col-md-4"><?php echo azlang('Address');?> <red>*</red></label>
					<div class="col-md-8">
						<textarea class="form-control" id="address" name="address" rows="11"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div style="background-color:#D5EAD9;padding:10px 5px 5px 5px;border-radius:10px;">
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