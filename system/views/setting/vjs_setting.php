<script>
    jQuery(document).ready(function() {
        jQuery("#btn_save_setting").on("click", function() {
            var formdata = new FormData();
            var file = jQuery('#image_logo')[0].files[0];
            formdata.append('logo', file);  

            var txt_ckeditor = jQuery('#form .ckeditor');
            jQuery(txt_ckeditor).each(function(){
                var id_ckeditor = jQuery(this).attr("id");
                CKEDITOR.instances[id_ckeditor].updateElement();            
            });
            
            jQuery.each($('#form').serializeArray(), function (a, b) {
                formdata.append(b.name, b.value);
            });

            show_loading();
            jQuery.ajax({
                url: app_url+"setting/save",
                type: "POST",
                data: formdata,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(response){
                    hide_loading();
                    if (response.sMessage == "") {
                        bootbox.alert({
                            title: "<?php echo azlang('Success');?>",
                            message: "<?php echo azlang('Save data success');?>",
                            callback: function() {
                                location.href = app_url+'setting';
                            }
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "Error",
                            message: response.sMessage
                        });
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });


        jQuery(document).on("hidden.bs.modal", ".bootbox.modal", function (e) {
            location.href = app_url+'setting';
        });
    });