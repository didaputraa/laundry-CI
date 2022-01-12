<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="idoutlay" id="idoutlay">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Date');?> <red>*</red></label>
		<div class="col-md-5">
			<?php echo $date;?>
		</div>
	</div>
	<?php 
        if (strlen($this->session->userdata('idoutlet')) == 0) {
    ?>
	<div class="form-group">
        <label class="col-sm-4 control-label"><?php echo azlang('Outlet');?> <red>*</red></label>
        <div class="col-sm-5">
            <?php echo az_select_outlet();?>
        </div>
    </div>
    <?php 
    	}
    ?>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Outlay Type');?> <red>*</red></label>
		<div class="col-md-5">
			<?php echo $outlay_type;?>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Total');?> <red>*</red></label>
		<div class="col-md-5">
			<input type="text" class="form-control txt-right format-number-decimal" id="total" name="total"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Description');?> <red>*</red></label>
		<div class="col-md-5">
			<textarea class="form-control" name="description" id="description"></textarea>
		</div>
	</div>
</form>