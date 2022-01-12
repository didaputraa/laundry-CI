<form class="form-horizontal az-form" id="form" name="form" method="POST" action="<?php echo app_url();?>role/save">
	<input type="hidden" name="idrole" id="idrole">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Parent');?> *</label>
		<div class="col-md-5">
			<select class="form-control select" id="parent" name="parent">
				<option value='0'>-</option>
				<?php echo $role_option;?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Name');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="name" name="name"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Title');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="title" name="title"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Description');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="description" name="description"/>
		</div>
	</div>
</form>