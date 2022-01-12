<!DOCTYPE html>
<html>
<head>
	<title><?php echo az_get_config('app_name');?></title>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/bootstrap/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/az_media_manager/az_media_manager.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/az-core/az-core.css" type="text/css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/az-core/az-core-left-theme.css" type="text/css">
</head>
<?php 
	$select = $this->input->get('select');
	$noselect = '';
	if ($select == 'noselect') {
		$noselect = 'media-noselect';
	}
?>
<body class="<?php echo $noselect;?>">
	<div class="az-loading">
        <div>
            <img src="<?php echo base_url().AZAPP;?>assets/images/loading.gif">
        </div>
    </div>

	<div class="container-media-manager">
		<div class="row">
			<div class="col-md-12">
				<form id="form_upload" enctype="multipart/form-data">
					<?php 
						$type = '*';
						if ($aztype == 'image') {
							$type = 'image/*';
						}
					?>
					<input type="file" name="file_image" id="file_image" accept="<?php echo $type;?>"/>
					<button class="btn btn-primary" id="btn_upload" type="button"><?php echo azlang('Upload');?></button>
				</form>
			</div>
		</div>
	</div>
	<div class="container-media-manager content">
		<div class="row">
<?php
	foreach ((array)$directory as $key => $value) {
		$sort_value = substr($value, 0, 65).'...';
		$filetype = base_url().AZAPP_FRONT.'assets/media_manager/'.$value;
		$ext = pathinfo($filetype, PATHINFO_EXTENSION);
		if ($aztype == 'image') {
			if (!in_array($ext, array('jpg', 'jpeg', 'png'))) {
				continue;
			}
		}
?>
			<div class="col-md-2 col-sm-4 col-xs-6">
				<div class="list-media">
					<div class="container-remove">
						<input type="hidden" class="hd-delete" value="<?php echo $value;?>"/>
						<button class="btn btn-danger btn-sm btn-remove-media" type="button">X</button>
					</div>
					<?php 
						if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
					?>
					<img src="<?php echo base_url().AZAPP_FRONT.'assets/media_manager/'.$value;?>" data-id="<?php echo base_url().AZAPP_FRONT.'assets/media_manager/'.$value;?>" alt="<?php echo $value;?>"/>
					<?php
						}
						else {
					?>
					<img src="<?php echo base_url().'assets/images/no-image.jpg';?>" data-id="<?php echo base_url().AZAPP_FRONT.'assets/media_manager/'.$value;?>" alt="<?php echo $value;?>"/>
					<?php
						}
					?>
					<div class="list-media-name">
						<?php echo $sort_value;?>
						<div class="choose-media-wrap">
							<button class="btn btn-default btn-choose-media" type="button"><?php echo azlang('Choose');?></button>
						</div>
					</div>
				</div>
			</div>
<?php
	}
?>
		</div>
	</div>

	<?php echo $modal;?>

	<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootbox/bootbox.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/bootstrap/bootstrap.min.js"></script>
</body>
</html>
<script type="text/javascript">
	<?php echo $js_core;?>
	<?php echo $js;?>
</script>