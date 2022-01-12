<div class="list-media selected">
	<img src="<?php echo base_url().AZAPP_FRONT.'assets/media_manager/'.$media_selected;?>" alt="<?php echo $media_selected;?>"/>
	<div class="list-media-name">
		<?php 
			echo $media_selected;
		?>
	</div>
	<div class="container-choose-media-selected">
		<button class="btn btn-primary btn-choose-media" type="button"><?php echo azlang('Choose');?></button>
	</div>
</div>