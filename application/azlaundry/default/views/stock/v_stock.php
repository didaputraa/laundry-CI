<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="idstock" name="idstock"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Date');?> <red>*</red></label>
        <div class="col-sm-6">
            <?php echo $datetime;?>
        </div>
    </div>
    
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Outlet Name');?><red>*</red></label>
        <div class="col-sm-6">
            <?php echo az_select_outlet();?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Product Name');?><red>*</red></label>
        <div class="col-sm-6">
            <?php echo az_select_product();?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Stock Name');?> <red>*</red></label>
        <div class="col-sm-6">
            <select class="form-control" id="stock_name" name="stock_name">
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Description');?></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="description" name="description" maxlength="100" />
        </div>
    </div>
    <div class="form-group" id="div_supplier">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Supplier');?></label>
        <div class="col-sm-6">
            <?php echo az_select_supplier();?>
        </div> 
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Total');?> <red>*</red></label>
        <div class="col-sm-6">
            <input type="text" class="form-control txt-right format-number" id="total" name="total" placeholder="0" maxlength="10"/>
        </div>
    </div>
</form>