<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'percent'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_status; ?></label>
				<div class="control-field col-sm-4">
					<select name="sub_total_status" class="form-control">
						<?php if ($sub_total_status) { ?>
						<option value="1" selected=""><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected=""><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><?php echo $entry_sort_order; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="sub_total_sort_order" value="<?php echo $sub_total_sort_order; ?>" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>