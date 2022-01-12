<script>
    var type = "<?php echo $stock_type;?>";
    var option = '';
    var option_in = ['Stok Awal', 'Penambahan Stok', 'Lain'];
    var option_out = ['Hilang', 'Rusak', 'Kadaluarsa', 'Lain'];

    if (type == 'out') {
        jQuery.each(option_out, function(key, value){
            option += "<option value='"+value+"'>"+value+"</option>";
        });
        jQuery("#stock_name").html(option);

        jQuery("#div_supplier").hide();
    }
    else {
        jQuery.each(option_in, function(key, value){
            option += "<option value='"+value+"'>"+value+"</option>";
        });
        jQuery("#stock_name").html(option);
    }