<?php echo $header ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa='shopping-cart';include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_status ?></label>
				<div class="control-field col-sm-4">
					<select name="status_id" class="form-control">
						<option value="*">&ndash;</option>
						<?php foreach ($statuses as $status): ?>
							<option value="<?php echo $status['status_id'] ?>" <?php echo ($status['status_id'] == $status_id) ? 'selected' : '' ?>><?php echo $status['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer ?>