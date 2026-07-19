<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-contract"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger alert-dismissable"><?php echo $error_warning; ?><button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button></div>
		<?php } ?>
		<form action="<?php echo $action; ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_document; ?></label>
				<div class="control-field col-sm-6">
					<input type="file" name="document" id="document" class="form-control" accept=".pdf,.xlsx">
				</div>
				<div class="control-field col-sm-2">
					<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> <span class="hidden-xs"><?php echo $button_upload; ?></span></button>
				</div>
			</div>
		</form>
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th><?php echo $column_filename; ?></th>
					<th><?php echo $column_date_added; ?></th>
					<th class="text-right"><?php echo $column_action; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($documents) { ?>
				<?php foreach ($documents as $document) { ?>
				<tr>
					<td><?php echo $document['filename']; ?></td>
					<td><?php echo $document['date_added']; ?></td>
					<td class="text-right">
						<a class="btn btn-default" href="<?php echo $document['view']; ?>" target="_blank"><i class="fa fa-eye"></i> <span class="hidden-xs"><?php echo $button_view; ?></span></a>
						<a class="btn btn-danger" href="<?php echo $document['delete']; ?>" onclick="return confirm(text_confirm);"><i class="fa fa-trash"></i></a>
					</td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="3"><?php echo $text_no_documents; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $footer; ?>
