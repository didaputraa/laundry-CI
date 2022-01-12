<form class="form-horizontal az-form" id="form" name="form" method="POST">
	<input type="hidden" name="iduser" id="iduser">
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Role');?> *</label>
		<div class="col-md-5">
			<select class="form-control" name="idrole" id="idrole">
				<?php echo az_generate_role_option(az_get_role());?>
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
		<label class="control-label col-md-4"><?php echo azlang('Email');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="email" name="email"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Username');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="username" name="username"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Password');?> *</label>
		<div class="col-md-5">
			<input type="password" class="form-control" id="password" name="password"/>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-4"><?php echo azlang('Address');?> *</label>
		<div class="col-md-5">
			<input type="text" class="form-control" id="address" name="address"/>
		</div>
	</div>
</form>