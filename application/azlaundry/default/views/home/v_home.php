<div class="row">
	<div class="col-sm-3">
		<div class="box-info new">
			<h3><?php echo az_thousand_separator($NEW);?></h3>
			<h5><?php echo azlang('NEW');?></h5>
			<i class="fa fa-file-o"></i>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="box-info prog">
			<h3><?php echo az_thousand_separator($PROGRESS);?></h3>
			<h5><?php echo azlang('PROGRESS');?></h5>
			<i class="fa fa-refresh"></i>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="box-info finish">
			<h3><?php echo az_thousand_separator($FINISH);?></h3>
			<h5><?php echo azlang('FINISH');?></h5>
			<i class="fa fa-check"></i>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="box-info approved">
			<h3><?php echo az_thousand_separator($ACCEPTED);?></h3>
			<h5><?php echo azlang('ACCEPTED');?></h5>
			<i class="fa fa-check-square"></i>
		</div>
	</div>
</div>
<br><br>
<div class="row">
	<div class="col-sm-12">
		<div class="box-home">
			<div class="box-home-content">
				<?php 
					echo az_get_config('app_preface');
				?>
			</div>
		</div>
	</div>
</div>