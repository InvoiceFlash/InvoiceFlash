<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fas fa-hdd"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"><?php echo $entry_restore; ?></label>
				<div class="custom-file col-sm-10">
					<input type="file" class="custom-file-input from-control" name="import">
					<label class="custom-file-label">Browse....</label>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10 offset-sm-2">
					<button type="submit" class="btn btn-primary pull-right"><i class="fas fa-hdd"></i> <?php echo $button_restore; ?></button>
				</div>
			</div>
		</form>
		<hr>
		<form class="form-horizontal" action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" role="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-2"><?php echo $entry_backup; ?>
					<div class="btn-group-vertical">
						<a class="btn btn-default btn-sm rounded-0" onclick="$('input[name*=\'backup\']').prop('checked',true);"><?php echo $text_select_all; ?></a>
						<a class="btn btn-default btn-sm rounded-0 mt-2" onclick="$('input[name*=\'backup\']').prop('checked',false);"><?php echo $text_unselect_all; ?></a>
					</div>
				</label>
				<div class="col-sm-10">
					<div class="panel panel-default panel-scrollable">
						<div class="list-group list-group-hover">
							<?php foreach ($tables as $table) { ?>
							<label class="list-group-item">
							<input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked>
							<?php echo $table; ?></label>
							<?php } ?>
						</div>
					</div>
					<button type="submit" class="btn btn-primary mt-2"><i class="fa fa-download"></i> <?php echo $button_backup; ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>