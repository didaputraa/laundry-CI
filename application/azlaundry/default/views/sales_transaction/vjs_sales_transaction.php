<script>
	jQuery('.btn-add-product-transaction').on('click', function() {
		new_transaction();
	});

	jQuery('.btn-add-detail').on('click', function() {
		add_detail();
	});

	function add_detail() {
		var new_numb = jQuery('.table-detail .detail-numb').length;
		new_numb++;
		var table = '<tr>';
		table +=		'<td><input type="hidden" name="idtransaction_detail[]" class="detail-idtransaction-detail"><button class="btn btn-danger btn-remove-row" data-id="transaction-detail" type="button"><i class="fa fa-remove"></i></button></td>';
		table += 		'<td><input readonly type="text" class="form-control detail-numb numb" value="'+new_numb+'"></td>';
		table += 		'<td><input type="text" name="detail_description[]" class="form-control detail-description"></td>';
		table += 		'<td><input type="number" class="form-control detail-qty txt-center" name="detail_qty[]"></td>';
		table +=	'</tr>';

		jQuery(".table-detail tbody").append(table);
		calc_total_qty();		
	}

	calc_total_qty();
	jQuery('body').on('keyup keydown change mouseup', '.detail-qty', function() {
		calc_total_qty();
	});
	function calc_total_qty() {
		var total_qty = 0;
		jQuery('.detail-qty').each(function(adata, bdata) {
			var detail_qty = jQuery(bdata).val() || 0;
			total_qty += parseInt(detail_qty);
		});

		jQuery('.txt-total-qty').text(total_qty);
	}

	function new_transaction() {
		var new_numb = jQuery('.table-transaction .numb').length;
		new_numb++;
		var table = '';
		table += '<tr>';
		table += '	<td><button class="btn btn-danger btn-remove-row" data-id="transaction" type="button"><i class="fa fa-remove"></i></button></td>';
		table += '	<td><input readonly type="text" class="form-control numb" value="'+new_numb+'"></td>';
		table += '	<td>';
		table += '		<input type="hidden" name="idtransaction[]" class="product-idtransaction">';
		table += '		<input type="hidden" name="idproduct[]" class="product-idproduct">';
		table += '		<div class="container-product-search">';
		table += '			<input readonly type="text" class="form-control product-name" placeholder="<?php echo azlang('Search Product');?>">';
		table += '			<button class="btn btn-default btn-search-product" type="button"><i class="fa fa-search"></i></button>';
		table += '		</div>';
		table += '	</td>';
		table += '	<td><input type="text" readonly class="form-control product-type"></td>';
		table += '	<td><input type="text" value="1,00" class="form-control product-qty format-number-decimal" name="qty[]"></td>';
		table += '	<td><input type="text" value="0,00" class="form-control product-price format-number-decimal" name="price[]"></td>';
		table += '	<td><input type="text" value="0,00" class="form-control product-discount format-number-decimal" name="discount[]"></td>';
		table += '	<td><input type="text" value="0,00" class="form-control product-add-cost format-number-decimal" name="add_cost[]"></td>';
		table += '	<td><input type="text" value="0,00" class="form-control product-tax format-number-decimal" name="tax[]"></td>';
		table += '	<td><input type="text" value="0,00" class="form-control product-total format-number-decimal" readonly name="total[]"></td>';
		table += '</tr>';

		jQuery(".table-transaction tbody").append(table);
	}

	jQuery('body').on('click', '.container-product-search', function() {
		var index = jQuery('.container-product-search').index(jQuery(this));
				
		if (jQuery('#idoutlet').val() == null) {
			bootbox.alert('<?php echo azlang("Please select outlet first");?>');
		}
		else {
			jQuery('#product_selected').val(index);
			show_modal('product');
		}

	});

	jQuery('body').on('click', '.btn-choose-product', function() {
		var idproduct = jQuery(this).attr('data-id');
		var type = jQuery(this).attr('data-type');
		var price = jQuery(this).attr('data-price');
		var name = jQuery(this).attr('data-name');
		var index = jQuery("#product_selected").val();
		price = thousand_separator_decimal(price);
		jQuery('.product-idproduct:eq('+index+')').val(idproduct);
		jQuery('.product-type:eq('+index+')').val(type);
		jQuery('.product-price:eq('+index+')').val(price);
		jQuery('.product-name:eq('+index+')').val(name);
		jQuery('.az-modal').modal('hide');
		jQuery('.product-qty:eq('+index+')').focus();
		calculate_total();
	});

	jQuery('body').on('change keyup keydown', '.product-qty, .product-price, .product-tax, .product-add-cost, .product-discount, #info_discount, #info_add_cost, #info_tax, #info_pay, #info_discount_percent', function() {
		calculate_total();
	});

	calculate_total();
	function calculate_total() {
		var info_total = 0;
		jQuery('.product-qty').each(function(adata, bdata) {
			var qty = jQuery(bdata).val() || 0;
			var price = jQuery('.product-price:eq('+adata+')').val() || 0;
			var discount = jQuery('.product-discount:eq('+adata+')').val() || 0;
			var add_cost = jQuery('.product-add-cost:eq('+adata+')').val() || 0;
			var tax = jQuery('.product-tax:eq('+adata+')').val() || 0;
			qty = remove_separator(qty);
			price = remove_separator(price);
			discount = remove_separator(discount);
			add_cost = remove_separator(add_cost);
			tax = remove_separator(tax);
			var total = (parseFloat(qty) * parseFloat(price)) - parseFloat(discount) + parseFloat(add_cost) + parseFloat(tax);
			info_total += total;
			total = thousand_separator_decimal(total);
			jQuery('.product-total:eq('+adata+')').val(total);
		});

		var info_discount = jQuery('#info_discount').val() || 0;
		var info_add_cost = jQuery('#info_add_cost').val() || 0;
		var info_tax = jQuery('#info_tax').val() || 0;
		info_discount = remove_separator(info_discount);
		info_add_cost = remove_separator(info_add_cost);
		info_tax = remove_separator(info_tax);

		var info_total_final = parseFloat(info_total) - parseFloat(info_discount) + parseFloat(info_add_cost) + parseFloat(info_tax);


		jQuery('#info_total').val(thousand_separator_decimal(info_total));
		jQuery('#info_total_final').val(thousand_separator_decimal(info_total_final));

		var pay = jQuery('#info_pay').val() || 0;
		pay = remove_separator(pay);
		var not_yet = parseFloat(pay) - info_total_final;
		if (not_yet > 0) {
			not_yet = 0;
		}
		else {
			not_yet = Math.abs(not_yet);
		}
		jQuery('#info_not_yet_pay').val(thousand_separator_decimal(not_yet));
	}

	function calculate_percent(type) {
        var discount = remove_separator(jQuery('#info_'+ type).val()) || 0;
        var total_transaction = remove_separator(jQuery('#info_total').val());
        var discount_percent = parseInt(discount)/parseInt(total_transaction) * 100;
        discount_percent = discount_percent || 0;
        jQuery('#info_'+ type +'_percent').val(thousand_separator_decimal(discount_percent));
    }

    function calculate_discount(type) {
        var discount_percent = remove_separator(jQuery('#info_'+ type +'_percent').val()) || 0;
        var total_transaction = remove_separator(jQuery('#info_total').val());
        var discount_total = parseInt(discount_percent) / 100 * total_transaction;
        discount_percent = discount_total;
        jQuery('#info_'+ type).val(thousand_separator_decimal(discount_percent));
        calculate_total();
    }

    jQuery('#info_discount_percent').on('keyup keydown', function() {
    	calculate_discount('discount');
    });

    jQuery('#info_discount').on('keyup keydown', function() {
    	calculate_percent('discount');
    });

    jQuery('#info_tax_percent').on('keyup keydown', function() {
    	calculate_discount('tax');
    });

    jQuery('#info_tax').on('keyup keydown', function() {
    	calculate_percent('tax');
    });


    jQuery('body').on('change', '#idoutlet', function() {
    	var dtable = jQuery('#product').dataTable({bRetrieve: true});
        dtable.fnDraw();

        jQuery('.table-transaction tbody').html('');
        new_transaction();
        calculate_total();
    });

    jQuery('body').on('click', '.btn-remove-row', function() {
    	var the_table = jQuery(this).parents('table');
    	var attr = jQuery(this).attr('data-id');
    	if (attr == 'transaction') {
    		id = 'product-idtransaction';
    		field = 'x_transaction';
    	}
    	else {
    		id = 'detail-idtransaction-detail';
    		field = 'x_transaction_detail';
    	}

    	var old_val = jQuery('#' + field).val();
		var new_val = jQuery(this).parents('tr').find('.' + id).val();

		if (new_val.length > 0) {
			var val_remove = old_val + ',' + new_val;
			jQuery("#" + field).val(val_remove);
		}

    	jQuery(this).parents('tr').remove();
    	
    	var numb = the_table.find('.numb');
    	jQuery(numb).each(function(adata, bdata) {
    		jQuery(bdata).val((adata + 1));
    	});
    	calculate_total();
    	calc_total_qty();
    });

    jQuery('#btn_save_transaction').on('click', function() {
    	save_transaction();
    });
    jQuery('#btn_save_print_transaction').on('click', function() {
    	save_transaction('print');
    });

    function save_transaction(type) {
    	show_loading();
    	jQuery.ajax({
    		url: app_url + 'sales_transaction/save',
    		type: 'POST',
    		dataType: 'JSON',
    		data: jQuery('#form_transaction').serialize(),
    		success: function(response) {
    			hide_loading();
    			if (response.err_code == 0) {
    				bootbox.alert({
    					title: "<?php echo azlang('Success');?>",
    					message: "<?php echo azlang('Transaction Success Added');?>",
    					callback: function() {
    						if (type == 'print') {
    							window.open(app_url + 'sales_transaction/invoice/?t=small&c=' + response.invoice, '_blank');
    						}
    						location.href = app_url + 'sales_transaction';
    					}
    				});
    			}
    			else {
    				bootbox.alert({
    					title: "Error", 
    					message: response.err_message
    				});
    			}
    		},
    		error: function(response) {
    			hide_loading();
    		}
    	});    	
    }

    get_edit();
    function get_edit() {
    	var is_edit = "<?php echo $this->uri->segment(2);?>";
    	var id = "<?php echo $this->uri->segment(3);?>";
    	if (is_edit == 'edit' && id.length > 0) {
    		jQuery.ajax({
    			url: app_url + 'sales_transaction/get_edit',
    			type: 'POST',
    			dataType: 'JSON',
    			data: {
    				id: id
    			},
    			success: function(response) {
    				if (response.err_code > 0) {
    					bootbox.alert({
    						title: "<?php echo azlang('Error');?>",
    						message: response.err_message,
    						callback: function() {
    							location.href = app_url + 'sales_transaction'
    						}
    					});
    				}
    				else {
	    				jQuery.each(response.data, function(adata, bdata){
							jQuery('#'+adata).val(bdata);
							if (jQuery('#'+adata).hasClass('format-number-decimal')) {
								jQuery('#'+adata).val(thousand_separator_decimal(bdata));
							}

							var arr_ajax = [];
							var ajax_ = adata;

					        if (ajax_.indexOf("ajax_") >= 0) {
					            arr_ajax.push(ajax_);
					        }

							if (arr_ajax.length > 0) {
					            jQuery.each(arr_ajax, function(index_arr, value_arr) {
					                var idajax = value_arr.replace("ajax_", "");
					                if (response.data[value_arr] != null) {
					                    jQuery("#"+idajax+".select2-ajax").append(new Option(response.data[value_arr], response.data[idajax], true, true)).trigger('change');
					                }
					            });
					        }
						});

	    				jQuery(".table-transaction tbody").html('');
	    				jQuery.each(response.data_transaction, function(adata, bdata) {
	    					new_transaction();
	    					jQuery('.product-idtransaction').last().val(bdata.idtransaction);
	    					jQuery('.product-idproduct').last().val(bdata.idproduct);
	    					jQuery('.product-name').last().val(bdata.product_name);
	    					jQuery('.product-type').last().val(bdata.product_type);
	    					jQuery('.product-qty').last().val(thousand_separator_decimal(bdata.qty));
	    					jQuery('.product-price').last().val(thousand_separator_decimal(bdata.price));
	    					jQuery('.product-discount').last().val(thousand_separator_decimal(bdata.discount));
	    					jQuery('.product-add-cost').last().val(thousand_separator_decimal(bdata.add_cost));
	    					jQuery('.product-tax').last().val(thousand_separator_decimal(bdata.tax));
	    					jQuery('.product-total').last().val(thousand_separator_decimal(bdata.total));
	    					calculate_total();
	    				});

	    				jQuery(".table-detail tbody").html('');
						jQuery.each(response.data_detail, function(adata, bdata) {
							add_detail();
							jQuery('.detail-idtransaction-detail').last().val(bdata.idtransaction_detail);
							jQuery('.detail-description').last().val(bdata.detail_description);
							jQuery('.detail-qty').last().val(bdata.detail_qty);
							calc_total_qty();
						});
						check_pay();
    				}
    			},
    			error: function(response) {}
    		});
    	}
    }

    jQuery('#btn_add_customer').on('click', function() {
    	jQuery('.form-customer input').val('');
    	jQuery('.form-customer textarea').val('');
    	jQuery('.form-customer select').val('');
    	show_modal('customer');
    });

    jQuery('body').on('click', '.btn-action-save_customer', function() {
    	var ca = function(response){
    		jQuery("#idcustomer.select2-ajax").append(new Option(response.customer_name, response.idcustomer, true, true)).trigger('change');
    	};
    	save(app_url + 'sales_transaction/save_customer', 'form', 'thetable', ca, '');
    });

    jQuery('body').on('change', '#pay', function() {
    	check_pay();
    });

    check_pay();
    function check_pay() {
    	var pay = jQuery('#pay').val();
    	if (pay == 'PAID') {
    		jQuery('.container-pay-date').removeClass('hide');
    	}
    	else {
    		jQuery('.container-pay-date').addClass('hide');
    	}
    }