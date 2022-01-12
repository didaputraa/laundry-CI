<?php
    $ci =& get_instance();
    $ci->load->helper("az_core");
    $ci->load->helper('az_menu_top');
    $ci->load->helper('az_config');
    $ci->load->helper('array');

    $active_lang = $ci->session->userdata('azlang');
    if (strlen($active_lang) == 0) {
        $active_lang = 'indonesian';
        $ci->session->set_userdata('azlang', 'indonesian');
    }
    $arr_language = array();
    $arr_language[] = array(
        'code' => 'id',
        'name' => 'Indonesian',
        'active' => '',
    );
    $arr_language[] = array(
        'code' => 'en',
        'name' => 'English',
        'active' => '',
    );

    if ($active_lang == 'indonesian') {
        $arr_language[0]['active'] = 'active';    
    }
    else {
        $arr_language[1]['active'] = 'active';
    }
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url().AZAPP.'assets/images/logo.png';?>" />
        <title><?php echo az_get_config('app_name');?></title>

        <?php
        	echo az_css();
        ?>
        <script type="text/javascript">
            var base_url = "<?php echo base_url();?>"; 
            var app_url = "<?php echo app_url();?>";
        </script>  
	</head>
	<body>
        <div class="az-loading">
            <div>
                <img src="<?php echo base_url().AZAPP;?>assets/images/loading.gif">
            </div>
        </div>

        <div class="header-content">
            <div class="image-logo">
                <img src="<?php echo base_url().AZAPP.'assets/images/logo.png';?>"/>
            </div>
            <div class="app-info">
                <?php echo az_get_config('app_name');?>
                <div class="app-info-description">
                    <?php echo az_get_config('app_description');?>
                </div>
            </div>
            <div class="container-language">
                <?php 
                    foreach ($arr_language as $key => $value) {
                        $url = app_url().'core/change_language/'.$value['code'];
                        if ($value['active'] == 'active') {
                            $url = 'javascript:void(0)';
                        }
                ?>
                <div class="az-language-list"><a href="<?php echo $url;?>"><button class="btn btn-primary <?php echo $value['active'];?>" title="<?php echo azlang($value['name']);?>"><?php echo strtoupper($value['code']);?></button></a></div>
                <?php
                    }
                ?>
            </div>
            <div class="account-box">
                <div class="account">
                    <div class="account-container">
                        <div class="icon-user">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <?php echo $ci->session->userdata("name");?>
                        </div>
                    </div>
                    <div class="account-detail">
                        <div class="account-detail-content">
                            <a href="<?php echo app_url().'account/change_password';?>"><button type="button" class="btn btn-primary"><i class="fa fa-key"></i> <?php echo azlang('Change Password');?></button></a>
                            <a href="<?php echo app_url().'login/logout';?>"><button type="button" class="btn btn-default"><i class="fa fa-sign-out"></i> <?php echo azlang('Logout');?></button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-btn-xs-menu">
            <button class="btn btn-primary az-btn-primary az-btn-xs-menu visible-xs">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="az-ttheme-left">
            <div class="az-menu">
                <?php 
                    $breadcrumb = azarr($data_header, 'breadcrumb', array());
                    echo az_generate_menu($breadcrumb);
                ?>
            </div>
        </div>
        <div class="az-breadcrumb">
            <?php echo az_generate_breadcrumb($breadcrumb);?>
        </div>

        <div class="container-content">
            <h3 class="header-title"><?php echo azarr($data_header, 'title');?></h3>
            <div class="az-content">