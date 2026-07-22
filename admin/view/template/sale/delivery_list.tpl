<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-clipboard"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button onclick="validate();" class="btn btn-default btn-spacer"><i class="fa fa-print"></i><span class="hidden-xs"> <?php echo $button_delivery; ?></span></button>
			<button type="submit" form="form" formaction="<?php echo $copy; ?>" onclick="return confirm(text_confirm);" id="btn-copy" class="btn btn-spacer" style="background-color:#d3f1f7; border-color:#a8d8e8; color:#004085;"><i class="fa fa-copy"></i><span class="hidden-xs"> <?php echo $button_copy; ?></span></button>
			<button type="button" onclick="convertToDraft();" id="btn-convert" class="btn btn-success btn-spacer"><i class="fa fa-exchange-alt"></i><span class="hidden-xs"> <?php echo $button_convert_draft; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary btn-spacer"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="foe" action="<?php echo $invoice; ?>" method="post" enctype="multipart/form-data" id="form" name="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-right"><a href="<?php echo $sort_delivery; ?>"><?php echo $column_delivery_id; echo ($sort == 'o.delivery_id') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_company; ?>"><?php echo $column_customer; echo ($sort == 'company') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_total; ?>"><?php echo $column_total; echo ($sort == 'o.total') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'o.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs hidden-sm"><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; echo ($sort == 'o.date_modified') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/delivery&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td class="text-right"><input type="text" name="filter_delivery_id" value="<?php echo $filter_delivery_id; ?>" class="form-control text-right"></td>
						<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" class="form-control" data-target="company" data-url="sale/customer" class="form-control"></td>
						<td class="hidden-xs"><select name="filter_invoice_status_id" class="form-control">
							<option value="*">&ndash;</option>
							<?php foreach ($invoice_statuses as $invoice_status) { ?>
							<?php if ($invoice_status['delivery_status_id'] == $filter_invoice_status_id) { ?>
							<option value="<?php echo $invoice_status['delivery_status_id']; ?>" selected=""><?php echo $invoice_status['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $invoice_status['delivery_status_id']; ?>"><?php echo $invoice_status['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control text-right"></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_modified" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($deliveries) { ?>
					<?php foreach ($deliveries as $delivery) { ?>
					<tr id="delivery-row-<?php echo $delivery['delivery_id']; ?>"<?php echo $delivery['status_color'] ? ' style="background-color:' . $delivery['status_color'] . ';"' : ''; ?>>
						<td class="rowlink-skip text-center"><?php if ($delivery['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $delivery['delivery_id']; ?>" data-status-id="<?php echo $delivery['status_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $delivery['delivery_id']; ?>" data-status-id="<?php echo $delivery['status_id']; ?>">
							<?php } ?></td>
						<td class="text-right"><?php echo $delivery['delivery_id']; ?></td>
						<td><?php echo $delivery['company']; ?></td>
						<td id="delivery-status-<?php echo $delivery['delivery_id']; ?>" class="hidden-xs text-<?php echo strtolower($delivery['status']); ?>"><?php echo $delivery['status']; ?></td>
						<td class="text-right hidden-xs"><?php echo $delivery['total']; ?></td>
						<td class="hidden-xs"><?php echo $delivery['date_added']; ?></td>
						<td class="hidden-xs hidden-sm"><?php echo $delivery['date_modified']; ?></td>
						<td class="text-right"><?php foreach ($delivery['action'] as $action) { ?>
							<a href="<?php echo $action['href']; ?>" class="btn btn-<?php echo $action['color']; ?>"><i class="<?php echo $action['icon']; ?>"></i></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<div id="ConvertGroupModal" class="modal fade" role="dialog" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_group_question; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<button type="button" class="btn btn-default btn-block" id="btn-convert-single" style="margin-bottom:10px;"><?php echo $text_group_single; ?></button>
				<button type="button" class="btn btn-primary btn-block" id="btn-convert-merge"><?php echo $text_group_merge; ?></button>
			</div>
		</div>
	</div>
</div>
<script>
function validate() {
	if (!$('input[type="checkbox"]').is(':checked')) {
		alert('Seleccione un albarán para imprimir');
	} else {
		var form = document.getElementById('form');
		form.setAttribute('target', '_blank');
		document.form.submit();
	}
}

function convertToDraft() {
	var $checked = $('input[name="selected[]"]:checked');

	if (!$checked.length) {
		alert('Seleccione un albarán para convertir');
		return;
	}

	var blocked = false;

	$checked.each(function() {
		var statusId = parseInt($(this).data('status-id'), 10);

		if (statusId === 2 || statusId === 3) {
			blocked = true;
		}
	});

	if (blocked) {
		alertMessage('danger', '<?php echo $error_already_converted; ?>');
		return;
	}

	if ($checked.length > 1) {
		bootstrap.Modal.getOrCreateInstance(document.getElementById('ConvertGroupModal')).show();
		return;
	}

	if (!confirm(text_confirm)) {
		return;
	}

	doConvert(0);
}

function doConvert(group) {
	var $checked = $('input[name="selected[]"]:checked');
	var $btn = $('#btn-convert');
	$btn.prop('disabled', true);

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $convert); ?>',
		type: 'post',
		dataType: 'json',
		data: $checked.serialize() + '&group=' + group,
		success: function(json) {
			if (json['error']) {
				alertMessage('danger', json['error']);
				return;
			}

			if (json['converted']) {
				json['converted'].forEach(function(item) {
					$('#delivery-row-' + item['delivery_id']).attr('style', item['status_color'] ? 'background-color:' + item['status_color'] + ';' : '');
					$('#delivery-status-' + item['delivery_id']).text(item['status']);
				});
			}

			if (json['success']) {
				alertMessage('success', json['success']);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alertMessage('danger', thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
		complete: function() {
			$btn.prop('disabled', false);
		}
	});
}

$('#btn-convert-single').on('click', function() {
	bootstrap.Modal.getInstance(document.getElementById('ConvertGroupModal')).hide();
	doConvert(0);
});

$('#btn-convert-merge').on('click', function() {
	bootstrap.Modal.getInstance(document.getElementById('ConvertGroupModal')).hide();
	doConvert(1);
});
</script>
<?php echo $footer; ?>