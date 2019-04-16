<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'money-bill-alt'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="col-sm-6">
					<?php foreach ($languages as $language) { ?>
						<div class="input-group">
							<input type="text" name="pay_name[<?php echo $language['language_id'] ?>][name]" value="<?php echo isset($pay_name[$language['language_id']]['name']) ? $pay_name[$language['language_id']]['name'] : '' ?>" class="form-control">
							<div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
							<?php if (isset($error_name[$language['language_id']])) { ?>
							<div class="help-block text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group number row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_nexpirations; ?></label>
				<div class="col-sm-1">
					<input type="number" class="form-control" name="nexpirations" value="<?php echo $nexpirations; ?>" class="form-control text-right" min="1" max="100">
				</div>
			</div>
			<div class="form-group number row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_displacement; ?></label>
				<div class="col-sm-1">
					<input type="number" class="form-control" name="displacement" value="<?php echo $displacement; ?>" class="form-control text-right" min="1" max="100">
				</div>
			</div>
			<div class="form-group number row">
				<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_days_between; ?></label>
				<div class="col-sm-1">
					<input type="number" class="form-control" name="days_between" value="<?php echo $days_between; ?>" class="form-control text-right" min="1" max="100">
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$('.number input[type="number"]').after('<div class="number-nav"><div class="num-button button-up"></div><div class="num-button button-down"></div></div>');
$('.button-up').click(function(){
	var div = $(this).parent().parent();

	var input = $(div).children('input[type="number"]');
	var num = input.val();

	if (num < parseInt($(input).attr('max'))) {
		num++;

		$(input).val(num);
	}
});
$('.button-down').click(function(){
	var div = $(this).parent().parent();

	var input = $(div).children('input[type="number"]');
	var num = input.val();

	if (num > parseInt($(input).attr('min'))) {
		num--;

		$(input).val(num);
	}
});
</script>
<?php echo $footer; ?>