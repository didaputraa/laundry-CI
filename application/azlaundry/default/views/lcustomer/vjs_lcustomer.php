<script>
	jQuery('body').on('click', '.btn-search-lcustomer', function() {
		var dtable = jQuery('#customer').dataTable({bRetrieve: true});
        dtable.fnDraw();
		show_modal('lcustomer');
	});

	jQuery('body').on('click', '.btn-choose-lcustomer', function() {
		var id = jQuery(this).attr('data-id');
		var name = jQuery(this).attr('data-name');
		customer_selected(id, name);
	});

	jQuery('body').on('click', '.btn-remove-selected-lcustomer', function() {
		var the = jQuery(this).parent('.container-lcustomer');
		jQuery('#idcustomer').val('');
		jQuery('#customer_text').val('');
		jQuery(this).addClass('hide');
		jQuery('#customer_text').attr('readonly', false);
	});

	jQuery('body').on('keydown', '#customer_text', function(evt) {
		if (evt.keyCode == 13) {
			var customer_text = jQuery('#customer_text').val();
			var str = customer_text.replace(/\s/g, '');
			jQuery('#customer_text').val(str);
			scanning();
		}
	});

	function customer_selected(id, name) {
		jQuery('#idcustomer').val(id).change();
		jQuery('#customer_text').val(name);
		jQuery('.btn-remove-selected-lcustomer').removeClass('hide');
		jQuery('#customer_text').attr('readonly', true);
		hide_modal('lcustomer');

		var dtable = jQuery('#sales_transaction').dataTable({bRetrieve: true});
        dtable.fnDraw();
	}

	function scanning() {
		var code = jQuery('#customer_text').val();
		if (code.length > 0) {
			var type = jQuery('.barcode-radio:checked').val();
			jQuery.ajax({
				url: app_url + 'data_customer/scanning',
				type: 'POST',
				dataType: 'JSON',
				data: {
					type: type,
					code: code
				},
				success: function(response) {
					if (response.err_code > 0) {
						bootbox.alert({
							title: "Kesalahan",
							message: response.err_message,
							callback: function() {
								jQuery('#customer_text').focus();
							}
						});
					}
					else {
						customer_selected(response.id, response.name);
					}
				},
				error: function(response) {}
			});			
		}

	}