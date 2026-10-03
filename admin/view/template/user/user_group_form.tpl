<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'user'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="card-body">
		<form class="form-classic" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" autofocus="">
					<?php if ($error_name) { ?>
						<div class="form-text error"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><p><?php echo $entry_access; ?></p>
					<div class="btn-group-vertical">
						<a class="btn btn-default btn-sm" onclick="$('[name*=\'permission[access]\']').prop('checked',true);"><?php echo $text_select_all; ?></a>
						<a class="btn btn-default btn-sm" onclick="$('[name*=\'permission[access]\']').prop('checked',false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</label>
				<div class="control-field col-sm-4">
					<div class="card page-card page-card-scroll">
						<div class="list-group list-group-hover">
						<?php foreach ($permissions as $permission) { ?>
							<?php $is_dashboard = (strpos($permission, 'common/home_') === 0); ?>
							<label class="list-group-item<?php echo $is_dashboard ? ' list-group-item-warning' : ''; ?>">
								<?php if (in_array($permission, $access)) { ?>
								<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>" checked=""><?php echo $permission; ?>
								<?php } else { ?>
								<input type="checkbox" name="permission[access][]" value="<?php echo $permission; ?>"><?php echo $permission; ?>
								<?php } ?>
							</label>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label text-sm-end pb-0 col-sm-2"><p><?php echo $entry_modify; ?></p>
					<div class="btn-group-vertical">
						<a class="btn btn-default btn-sm" onclick="$('[name*=\'permission[modify]\']').prop('checked',true);"><?php echo $text_select_all; ?></a>
						<a class="btn btn-default btn-sm" onclick="$('[name*=\'permission[modify]\']').prop('checked',false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</label>
				<div class="control-field col-sm-4">
					<div class="card page-card page-card-scroll">
						<div class="list-group list-group-hover">
							<?php foreach ($permissions as $permission) { ?>
							<?php $is_dashboard = (strpos($permission, 'common/home_') === 0); ?>
							<label class="list-group-item<?php echo $is_dashboard ? ' list-group-item-warning' : ''; ?>">
								<?php if (in_array($permission, $modify)) { ?>
								<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>" checked=""><?php echo $permission; ?>
								<?php } else { ?>
								<input type="checkbox" name="permission[modify][]" value="<?php echo $permission; ?>"><?php echo $permission; ?>
								<?php } ?>
							</label>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?> 