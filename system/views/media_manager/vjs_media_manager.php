<script>
var app_url = "<?php echo app_url();?>";
jQuery(document).ready(function() {
	var funcNum = "<?php echo $this->input->get('CKEditorFuncNum');?>";
	var select = "<?php echo $this->input->get('select');?>";
	jQuery('.btn-remove-media').click(function() {
		var filename = jQuery(this).parents('.container-remove').find('.hd-delete').val();
		bootbox.confirm({
	        title: "<?php echo azlang('Delete data');?>",
	        message: "<?php echo azlang('Are you sure for delete?');?>",
	        callback : function(result) {
	            if (result == true) {	    
					jQuery.ajax({
						url: app_url + 'media_manager/delete',
						type: 'POST',
						dataType: 'JSON',
						data: {
							filename: filename
						},
						success: function(response) {
							if (response.err_code > 0) {
								bootbox.alert({
	                                title: "<?php echo azlang('Error');?>",
	                                message: response.err_message
	                            });
							}
							else {
								bootbox.alert({
	                                title: "<?php echo azlang('Success');?>",
	                                message: "<?php echo azlang('Delete Success');?>",
	                                callback: function() {
	                                	location.href = app_url + 'media_manager/media_list/?select='+select+'&CKEditorFuncNum='+funcNum;
	                                }
	                            });
							}
						},
						error: function(response) {

						}
					});
				}
			}
		});
	});

	jQuery('#file_image').on('change', function() {
		var formdata = new FormData();
		var file = jQuery('#file_image')[0].files[0];
		formdata.append('file_image', file);
		show_loading();

		var type_media = "<?php echo $this->input->get('type');?>";

		jQuery.ajax({
			url: app_url + 'media_manager/save',
			type: 'POST',
			dataType: 'JSON', 
			data: formdata,
	        processData: false,
	        contentType: false,
			success: function(response){
				if (response.err_code == 0) {					
					location.href = app_url + 'media_manager/media_list/?select='+select+'&CKEditorFuncNum='+funcNum+'&media='+response.media+'&type=' + type_media;
				}
				else {
					bootbox.alert({
                        title: "<?php echo azlang('Error');?>",
                        message: response.err_message
                    });
				}
				hide_loading();
			},
			error: function(response) {

			}
		});
	});

	var data_modal = "<?php echo $this->input->get('media');?>";
	if (data_modal != '') {
		show_modal('upload');
	}

	jQuery(".btn-choose-media").click(function() {
		var url_select = jQuery(this).parents('.list-media').find('img').attr('data-id');
		var type_media = "<?php echo $this->input->get('type');?>";
		if (type_media == 'thumbnail') {
			window.opener.update_thumbnail(url_select);
		}
		else {
			window.opener.CKEDITOR.tools.callFunction( funcNum, url_select );
		}
		window.close();
	});

});