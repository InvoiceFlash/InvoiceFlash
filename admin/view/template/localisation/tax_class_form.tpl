<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'landmark'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_title; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="title" value="<?php echo $title; ?>" class="form-control" autofocus="">
					<?php if ($error_title) { ?>
						<div class="help-block error"><?php echo $error_title; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_description; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="description" value="<?php echo $description; ?>" class="form-control">
					<?php if ($error_description) { ?>
						<div class="help-block error"><?php echo $error_description; ?></div>
					<?php } ?>
				</div>
			</div>
			<table id="tax-rule" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th><?php echo $entry_rate; ?></th>
						<th><?php echo $entry_based; ?></th>
						<th><?php echo $entry_priority; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php $tax_rule_row = 0; ?>
				<?php foreach ($tax_rules as $tax_rule) { ?>
					<tr id="tax-rule-row<?php echo $tax_rule_row; ?>">
						<td><select name="tax_rule[<?php echo $tax_rule_row; ?>][tax_rate_id]" class="form-control">
							<?php foreach ($tax_rates as $tax_rate) { ?>
							<?php	if ($tax_rate['tax_rate_id'] == $tax_rule['tax_rate_id']) { ?>
							<option value="<?php echo $tax_rate['tax_rate_id']; ?>" selected=""><?php echo $tax_rate['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo $tax_rate['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td><select name="tax_rule[<?php echo $tax_rule_row; ?>][based]" class="form-control">
							<?php	if ($tax_rule['based'] == 'shipping') { ?>
							<option value="shipping" selected=""><?php echo $text_shipping; ?></option>
							<?php } else { ?>
							<option value="shipping"><?php echo $text_shipping; ?></option>
							<?php } ?>
							<?php	if ($tax_rule['based'] == 'payment') { ?>
							<option value="payment" selected=""><?php echo $text_payment; ?></option>
							<?php } else { ?>
							<option value="payment"><?php echo $text_payment; ?></option>
							<?php } ?>	
							<?php	if ($tax_rule['based'] == 'store'){ ?>
							<option value="store" selected=""><?php echo $text_store; ?></option>
							<?php } else { ?>
							<option value="store"><?php echo $text_store; ?></option>
							<?php } ?>												
						</select></td>
						<td><input type="text" name="tax_rule[<?php echo $tax_rule_row; ?>][priority]" value="<?php echo $tax_rule['priority']; ?>" class="form-control"></td>
						<td><a onclick="$('#tax-rule-row<?php echo $tax_rule_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
					</tr>
				<?php $tax_rule_row++; ?>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3"></td>
						<td><a onclick="addRule();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs">	<?php echo $button_add_rule; ?></span></a></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
<script>
var tax_rule_row=<?php echo $tax_rule_row; ?>;

function addRule(){
	html ='<tr id="tax-rule-row'+tax_rule_row+'">';
	html+='<td><select name="tax_rule['+tax_rule_row+'][tax_rate_id]" class="form-control">';
	<?php foreach ($tax_rates as $tax_rate) { ?>
	html+='<option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo addslashes($tax_rate['name']); ?></option>';
	<?php } ?>
	html+='</select></td>';
	html+='<td><select name="tax_rule['+tax_rule_row+'][based]" class="form-control">';
	html+='<option value="shipping"><?php echo $text_shipping; ?></option>';
	html+='<option value="payment"><?php echo $text_payment; ?></option>';
	html+='<option value="store"><?php echo $text_store; ?></option>';
	html+='</select></td>';
	html+='<td><input type="text" name="tax_rule['+tax_rule_row+'][priority]" value="" class="form-control"></td>';
	html+='<td><a onclick="$(\'#tax-rule-row'+tax_rule_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#tax-rule > tbody').append(html);
	
	tax_rule_row++;
}
</script>
<?php echo $footer; ?>