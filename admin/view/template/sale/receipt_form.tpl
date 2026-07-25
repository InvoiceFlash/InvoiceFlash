<?php echo $header ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa='shopping-cart';include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group row">
				<label class="col-form-label col-sm-2"><?php echo $entry_status ?></label>
				<div class="col-sm-4">
					<select name="status_id" class="form-control">
						<option value="*">&ndash;</option>
						<?php foreach ($statuses as $status): ?>
							<option value="<?php echo $status['status_id'] ?>" <?php echo ($status['status_id'] == $status_id) ? 'selected' : '' ?>><?php echo $status['name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"><?php echo $text_bank_cc; ?></label>
				<div class="col-sm-4">
					<select name="bank_cc" id="bank_cc" class="form-control">
						<option value="">&ndash;</option>
						<?php $bank_cc_matched = false; ?>
						<?php foreach ($bank_options as $bank_option) { ?>
							<?php if ($bank_option['value'] == $bank_cc) { $bank_cc_matched = true; } ?>
							<option value="<?php echo htmlspecialchars($bank_option['value'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($bank_option['value'] == $bank_cc) ? 'selected' : ''; ?>><?php echo htmlspecialchars($bank_option['label'], ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
						<?php if (!$bank_cc_matched && $bank_cc) { ?>
							<option value="<?php echo htmlspecialchars($bank_cc, ENT_QUOTES, 'UTF-8'); ?>" selected><?php echo htmlspecialchars($bank_cc, ENT_QUOTES, 'UTF-8'); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer ?> 