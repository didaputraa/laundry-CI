<div>
	<?php 
		$error = $this->session->flashdata('error');
		if (strlen($error) > 0) {
	?>
		<div class="alert alert-danger">
			<?php echo $error;?>
		</div>
	<?php
		}
	?>
	<button class="btn btn-primary btn-add-role" type="button"><?php echo azlang('Add');?></button>
</div>
<div class="role-content">
	<?php 
		echo $data;
	?>
</div>