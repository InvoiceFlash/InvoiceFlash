<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-balance-scale"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="button" id="btn-statement" class="btn btn-default btn-spacer"><i class="fa fa-list-alt"></i><span class="hidden-xs"> <?php echo $button_statement; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_code; ?>"><?php echo $column_code; echo ($sort == 'code') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_title; ?>"><?php echo $column_title; echo ($sort == 'title') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_debit; ?>"><?php echo $column_debit; echo ($sort == 'debit') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_credit; ?>"><?php echo $column_credit; echo ($sort == 'credit') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_balance; ?>"><?php echo $column_balance; echo ($sort == 'balance') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=accounting/subaccount&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td><input type="text" name="filter_code" id="filter-code" value="<?php echo $filter_code; ?>" class="form-control"></td>
						<td><input type="text" name="filter_title" value="<?php echo $filter_title; ?>" class="form-control"></td>
						<td class="hidden-xs"></td>
						<td class="hidden-xs"></td>
						<td class="hidden-xs"></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($subaccounts) { ?>
					<?php foreach ($subaccounts as $subaccount) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($subaccount['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $subaccount['ctab61_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $subaccount['ctab61_id']; ?>">
							<?php } ?></td>
						<td><?php echo $subaccount['code']; ?></td>
						<td><?php echo $subaccount['title']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['debit']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['credit']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['balance']; ?></td>
						<td class="text-right"><?php foreach ($subaccount['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<div id="StatementModal" class="modal fade" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_statement; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" id="statement-modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>
<script>
var token = '<?php echo $token; ?>';

$('#btn-statement').on('click', function() {
	var selected = $('input[name="selected[]"]:checked');

	if (selected.length !== 1) {
		alert('<?php echo $error_select_one_account; ?>');
		return;
	}

	$('#statement-modal-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>');

	bootstrap.Modal.getOrCreateInstance(document.getElementById('StatementModal')).show();

	$.ajax({
		url: 'index.php?route=accounting/subaccount/statement&token=' + token + '&ctab61_id=' + selected.val(),
		dataType: 'html',
		success: function(html) {
			$('#statement-modal-body').html(html);
		},
		error: function() {
			$('#statement-modal-body').html('<div class="alert alert-danger"><?php echo $error_statement; ?></div>');
		}
	});
});

$('#filter-code').trigger('focus');

$('#filter-code').on('blur keydown', function(e) {
	if (e.type === 'keydown' && e.which !== 13) {
		return;
	}

	var val = $(this).val();
	var dot = val.indexOf('.');

	if (dot === -1) {
		return;
	}

	var prefix = val.substring(0, dot);
	var suffix = val.substring(dot + 1);
	var zeros = 10 - prefix.length - suffix.length;

	if (zeros < 0) {
		return;
	}

	$(this).val(prefix + '0'.repeat(zeros) + suffix);
});
</script>
<?php echo $footer; ?>
