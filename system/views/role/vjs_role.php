<script>
jQuery('.btn-add-role').click(function() {
	reset();
	show_modal('role');
});

jQuery('.btn-action-save').click(function() {
	jQuery('#form').submit();
});

jQuery('.btn-delete-rolexxx').click(function() {
	var id = jQuery(this).attr('data-id');
	bootbox.confirm({
        title: "<?php echo azlang('Delete data');?>",
        message: "<?php echo azlang('Are you sure for delete?');?>",
        callback : function(result) {
            if (result == true) {
                $.ajax({
                    url: app_url+'role/delete',
                    type: "post",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        if (response.err_code > 0) {
                            bootbox.alert({
                                title: "Error",
                                message: response.err_message
                            });
                        }
                        else {
                            location.href = app_url+'role';
                        }
                    },
                    error: function (er) {
                        bootbox.alert({
                            title: "Error",
                            message: "<?php echo azlang('Delete data failed');?> "+er
                        });
                    }
                });
            }
        }
    });
});

jQuery('.btn-delete-role').click(function() {
	remove(app_url+'role/delete', jQuery(this).attr('data-id'), 'tabel', callback_delete);
});

jQuery('.btn-edit-role').click(function() {
	edit(app_url+'role/edit', jQuery(this).attr('data-id'), 'form', 'role', callback);
});

function reset() {
	jQuery('form input').not('.x-hidden').val('');
	jQuery('#parent').val('0');
}

function callback(response) {}

function callback_delete(response) {
	location.href = app_url+'role';
}