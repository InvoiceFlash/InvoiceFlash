<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-hdd-o"></i><?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_restore; ?></label>
				<div class="custom-file col-sm-4">
					<input type="file" class="custom-file-input" name="import">
					<label class="custom-file-label">Browse....</label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-2">
					<button type="submit" class="btn btn-primary"><i class="fa fa-hdd-o"></i> <?php echo $button_restore; ?></button>
				</div>
			</div>
		</form>
		<hr>
		<form class="form-horizontal" action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" role="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><p><?php echo $entry_backup; ?></p>
					<div class="btn-group-vertical">
						<a class="btn btn-default btn-sm" onclick="$('input[name*=\'backup\']').prop('checked',true);"><?php echo $text_select_all; ?></a>
						<a class="btn btn-default btn-sm" onclick="$('input[name*=\'backup\']').prop('checked',false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</label>
				<div class="col-sm-4">
					<div class="panel panel-default panel-scrollable">
						<div class="list-group list-group-hover">
							<?php foreach ($tables as $table) { ?>
							<label class="list-group-item">
							<input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked>
							<?php echo $table; ?></label>
							<?php } ?>
						</div>
					</div>
					<button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> <?php echo $button_backup; ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>