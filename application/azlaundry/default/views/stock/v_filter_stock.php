<form class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-sm-1"><?php echo azlang('Outlet');?></label>
		<div class="col-sm-3">
			<?php echo az_select_outlet('foutlet', 'outlet');?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-1"><?php echo azlang('Product');?></label>
		<div class="col-sm-3">
			<?php echo az_select_product('fproduct', 'product', 'idfoutlet');?>
		</div>
	</div>
</form>