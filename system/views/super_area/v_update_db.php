<form action="<?php echo app_url();?>super_area/update_db/passwordxxx" method="POST">
	<?php 
		if ($err_code == 0) {
		}
		else if ($err_code == 99) {
	?>
	<div class="alert alert-success">Sukses</div>
	<?php
		}
		else {
	?>
	<div class="alert alert-danger"><?php echo $err_message;?></div>
	<?php
		}
	?>
	<textarea class="form-control" name="query" id="query" rows="10"></textarea>
	<br>
	<button class="btn btn-danger" type="submit">Proses</button>
</form>