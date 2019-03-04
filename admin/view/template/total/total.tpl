<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'percent'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_status; ?></label>
				<div class="col-sm-6">
					<select name="total_status" class="form-control">
						<?php if ($total_status) { ?>
						<option value="1" selected=""><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
						<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected=""><?php echo $text_disabled; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_sort_order; ?></label>
				<div class="col-sm-6">
					<input type="text" name="total_sort_order" value="<?php echo $total_sort_order; ?>" class="form-control">
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>