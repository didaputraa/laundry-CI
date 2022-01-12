<form class="form-horizontal">
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