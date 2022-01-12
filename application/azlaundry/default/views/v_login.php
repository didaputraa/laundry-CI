<?php 
    $this->load->helper('az_config');
?>
<!DOCTYPE html>
<html>
    <head>
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url().AZAPP.'assets/images/logo.png';?>" />
        <title><?php echo az_get_config('app_name');?> - LOGIN</title>

        <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/az-core/az-core.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/az-core/az-core-left-theme.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url().AZAPP;?>assets/plugins/az_theme/az_theme.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/fa/font-awesome.css" type="text/css" />
        <script src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
        <style type="text/css">
            .txt-info-login {
                font-size: 12px;
            }
        </style>
	</head>
	<body style="width:100%;overflow-x:hidden;" class="container-body-login">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="login-container">
                    <div class="box-login-wrapper">
                        <div class="box-login">
                            <img width="100px" src="<?php echo base_url().AZAPP;?>assets/images/logo.png">
                            <h3><?php echo az_get_config('app_name');?></h3>
                            <form method="POST" action="login/process">
                                <?php 
                                    $err_login = $this->session->flashdata("error_login");
                                    if (strlen($err_login) > 0) {
                                        echo "<div class='login-error-message'>".$err_login."</div>";
                                    }
                                ?>
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                <div class="field-login">
                                    <i class="fa fa-user"></i>
                                    <input type="text" name="username" class="form-control" placeholder="Username">
                                </div>
                                <div class="field-login">
                                    <i class="fa fa-key"></i>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="txt-right">
                                    <button type="submit" class="btn btn-info btn-login">Login</button>
                                </div>
                                <div style="margin-top:15px;display: none;">
                                    <h4>Administrator</h4>
                                    <div class="txt-info-login">
                                        user: administrator | pass: password
                                    </div>
                                </div>
                                <div style="margin-top:15px;display: none;">
                                    <h4>Cashier</h4>
                                    <div class="txt-info-login">
                                        user: cashier | pass: password
                                        <br>
                                        user: cashierwangi | pass: password
                                    </div>
                                </div>
                                <div style="margin-top:15px;display: none;">
                                    Untuk informasi detail aplikasi, silakan klik link berikut <a href="https://www.azostech.com/produk/aplikasi-laundry-azlaundry" target="_blank">Detail AZLaundry</a>
                                </div>
                                <div class="login-copyright">
                                    Copyright &copy; <?php echo Date('Y');?> <a target="_blank" href="http://www.axoku.com">AXOKU</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    setTimeout(function(){
        jQuery(".login-error-message").hide("slow")
    }, 5000);
</script>