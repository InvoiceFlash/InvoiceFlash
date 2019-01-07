<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'user'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_username; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="username" value="<?php echo $username; ?>" class="form-control" autofocus="">
					<?php if ($error_username) { ?>
						<div class="help-block error"><?php echo $error_username; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_firstname; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="firstname" value="<?php echo $firstname; ?>" class="form-control">
					<?php if ($error_firstname) { ?>
						<div class="help-block error"><?php echo $error_firstname; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_lastname; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="lastname" value="<?php echo $lastname; ?>" class="form-control">
					<?php if ($error_lastname) { ?>
						<div class="help-block error"><?php echo $error_lastname; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_email; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_user_group; ?></label>
				<div class="control-field col-sm-4">
					<select name="user_group_id" class="form-control">
						<?php foreach ($user_groups as $user_group) { ?>
						<?php if ($user_group['user_group_id'] == $user_group_id) { ?>
						<option value="<?php echo $user_group['user_group_id']; ?>" selected=""><?php echo $user_group['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_password; ?></label>
				<div class="control-field col-sm-4">
					<input type="password" name="password" value="<?php echo $password; ?>" class="form-control">
					<?php if ($error_password) { ?>
						<div class="help-block error"><?php echo $error_password; ?></div>
						<?php	} ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_confirm; ?></label>
				<div class="control-field col-sm-4">
					<input type="password" name="confirm" value="<?php echo $confirm; ?>" class="form-control">
					<?php if ($error_confirm) { ?>
						<div class="help-block error"><?php echo $error_confirm; ?></div>
						<?php	} ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
					<select name="status" class="form-control">
						<?php if ($status) { ?>
						<option value="0"><?php echo $text_disabled; ?></option>
						<option value="1" selected=""><?php echo $text_enabled; ?></option>
						<?php } else { ?>
						<option value="0" selected=""><?php echo $text_disabled; ?></option>
						<option value="1"><?php echo $text_enabled; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?> 