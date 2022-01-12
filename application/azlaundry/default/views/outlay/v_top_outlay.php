<form class="form-horizontal">
	<div class="form-group element-top-filter" data-id="<?= $this->encrypt->encode("datetime");?>">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Date');?></label>
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-5">
                    <?php echo $datetime1;?>
                </div>
                <div class="col-sm-2 div-between-col">
                	<?php echo azlang('to');?>
                </div>
                <div class="col-sm-5">
                	<?php echo $datetime2;?>
                </div>
            </div>
        </div>
    </div>
    <?php 
        if (strlen($this->session->userdata('idoutlet')) == 0) {
    ?>
    <div class="form-group">
        <label class="col-sm-1 control-label"><?php echo azlang('Outlet');?></label>
        <div class="col-sm-5">
            <?php echo az_select_outlet('foutlet', 'outlet');?>
        </div>
    </div>
    <?php
        }
    ?>
</form>