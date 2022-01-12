<?php
    $ci =& get_instance();
    $ci->load->library("encrypt");
?>
<form class="form-horizontal az-form" name="form" method="post" id="form_sales_transaction" action="<?php echo app_url();?>sales_transaction/print_report" target="_blank">
	<div class="form-group element-top-filter" data-id="<?= $ci->encrypt->encode("date");?>">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Date');?></label>
        <div class="col-sm-6">
            <div class="row">
                <div class="col-sm-5">
                    <?php echo $datetime1;?>
                </div>
                <div class="col-sm-2 div-between-col">
                	s/d
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
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Outlet');?></label>
        <div class="col-sm-6">
            <?php echo $outlet;?>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Customer');?></label>
        <div class="col-sm-6">
            <?php echo az_select_customer('customer', 'transaction_group');?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Invoice Code');?></label>
        <div class="col-sm-6">
            <input type="text" id="filter_code" class="form-control element-top-filter" data-id="<?php echo $ci->encrypt->encode('transaction_group.code');?>">
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Pay');?></label>
        <div class="col-sm-6">
            <select class="form-control element-top-filter" name="pay" data-w="true" data-id="<?php echo $ci->encrypt->encode('pay');?>">
                <option value=""><?php echo azlang('ALL');?></option>
                <option value="PAID"><?php echo azlang('PAID');?></option>
                <option value="NOT PAID YET"><?php echo azlang('NOT PAID YET');?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Status');?></label>
        <div class="col-sm-6">
            <select class="form-control element-top-filter" name="status" data-w="true" data-id="<?php echo $ci->encrypt->encode('transaction_group_status');?>">
                <option value=""><?php echo azlang('ALL');?></option>
                <option value="NEW"><?php echo azlang('NEW');?></option>
                <option value="PROGRESS"><?php echo azlang('PROGRESS');?></option>
                <option value="FINISH"><?php echo azlang('FINISH');?></option>
                <option value="ACCEPTED"><?php echo azlang('ACCEPTED');?></option>
            </select>
        </div>
    </div>
</form>