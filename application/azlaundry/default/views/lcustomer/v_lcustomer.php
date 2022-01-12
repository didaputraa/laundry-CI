<div style='display:table;width:100%;' class='container-lcustomer'>
	<div style='display:table-cell;vertical-align:top;position:relative;'>
		<input type='text' name='customer_text' id='customer_text' class='form-control' placeholder='Scan Barcode'/>
		<input type='hidden' name='idcustomer' id='idcustomer' data-w='true' data-id='<?php echo $data_id;?>' class='<?php echo $data_class;?>'/>
		<button style='position:absolute;right:5px;top:6px;' class='btn btn-danger btn-xs hide btn-remove-selected-lcustomer' type='button'><i class='fa fa-remove'></i></button>
	</div>
	<div style='display:table-cell;vertical-align:top;width:80px'>
		<button class='btn btn-info btn-search-lcustomer' type='button'><i class='fa fa-search'></i></button>
		<button class='btn btn-default btn-qrcode' type='button'><i class='fa fa-qrcode'></i></button>
	</div>
</div>