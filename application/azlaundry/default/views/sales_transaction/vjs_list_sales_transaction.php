<script>
	jQuery('body').on('click', '.btn-add-sales_transaction', function() {
    	location.href = app_url + 'sales_transaction/add';
    });

    jQuery('body').on('click', '.btn-edit-sales_transaction', function() {
    	var id = jQuery(this).attr('data_id');
    	location.href = app_url + 'sales_transaction/edit/' + id;
    });

    jQuery('body').on('click', '#btn_print_report', function() {
    	jQuery('#form_sales_transaction').submit();
    });

    jQuery('body').on('click', '.btn-detail', function() {
        var code = jQuery(this).attr('data-code');

        show_loading();
        jQuery.ajax({
            url: app_url + 'sales_transaction/get_invoice',
            type: 'POST',
            dataType: 'JSON',
            data: {
                code: code
            },
            success: function(response) {
                if (response.err_code == 0) {
                    jQuery('.az-modal-detail .modal-body').html(response.data);
                    show_modal('detail');
                }
                else {
                    bootbox.alert({
                        title: "<?php echo azlang('Error');?>",
                        message: response.message
                    });
                }
                hide_loading();
            },
            error: function(response) {

            }
        });
    });

    jQuery('.btn-action-standart-invoice').on('click', function() {
        var code = jQuery('.transaction-group-code').text();
        window.open(app_url + 'sales_transaction/invoice/?c=' + code, '_blank');
    });

    jQuery('.btn-action-small-invoice').on('click', function() {
        var code = jQuery('.transaction-group-code').text();
        window.open(app_url + 'sales_transaction/invoice/?c=' + code +'&t=small', '_blank');
    });

    jQuery('body').on('change keyup keydown', '#filter_code', function(e) {
        if (e.keyCode == 13) {
            var dtable = jQuery('#sales_transaction').dataTable({bRetrieve: true});
            dtable.fnDraw();
        }
    });

    jQuery('body').on('click', '.btn-process-sales_transaction', function() {
        var id = jQuery(this).attr('data_id');
        jQuery('#idtransaction_group').val(id);

        jQuery.ajax({
            url: app_url + 'sales_transaction/get_detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(response) {
                jQuery('#pay').val(response.pay);
                jQuery('#pay_date').val(response.pay_date);
                jQuery('#transaction_group_status').val(response.transaction_group_status);
                check_pay();
            },
            error: function(response){}
        });

        show_modal('process');
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

    jQuery('body').on('click', '.btn-action-process', function() {
        jQuery.ajax({
            url: app_url + 'sales_transaction/process',
            type: 'POST',
            dataType: 'JSON',
            data: jQuery('#form_process').serialize(),
            success: function(response) {
                if (response.err_code == 0) {
                    hide_modal('process');
                    var dtable = jQuery('#sales_transaction').dataTable({bRetrieve: true});
                    dtable.fnDraw();
                }
                else {
                    bootbox.alert(response.err_message);
                }
            },
            error: function(response) {}
        });
    });