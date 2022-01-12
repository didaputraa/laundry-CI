<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idproduct" id="idproduct">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Outlet');?> <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control select" name="idoutlet" id="idoutlet">
				<?php 
					foreach ($outlet->result() as $key => $value) {
						echo "<option value='".$value->idoutlet."'>".$value->outlet_name."</option>";
					}
				?>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Product Type');?> <red>*</red></label>
		<div class="col-md-5">
			<select class="form-control select" name="product_type" id="product_type">
				<option value="KILOGRAM"><?php echo azlang('KILOGRAM');?></option>
				<option value="UNIT"><?php echo azlang('UNIT');?></option>
                                <option value="METER"><?php echo azlang('METER');?></option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Code');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="product_code" name="product_code"/>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Product Name');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="product_name" name="product_name"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Description');?></label>
		<div class="col-md-5">
			<textarea class="form-control" name="description" id="description" rows="3"></textarea>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Sell Price');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control txt-right format-number-decimal" id="sell_price" name="sell_price"/>
		</div>
	</div>
</form>