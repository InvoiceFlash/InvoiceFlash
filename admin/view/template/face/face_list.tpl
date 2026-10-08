<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<?php foreach ($warnings as $face_warning) { ?>
<div class="alert alert-warning"><?php echo $face_warning; ?></div>
<?php } ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="float-start h2"><i class="fa fa-landmark"></i> <?php echo $heading_title; ?></div>
		<div class="float-end">
			<?php if ($can_modify) { ?>
			<button type="button" id="face-refresh-all" class="btn btn-default"><i class="fa fa-sync"></i> <span class="d-none d-lg-inline"><?php echo $button_refresh_all; ?></span></button>
			<?php } ?>
			<a href="<?php echo $setting; ?>" class="btn btn-primary"><i class="fa fa-cog"></i> <span class="d-none d-lg-inline"><?php echo $button_setting; ?></span></a>
		</div>
	</div>
	<div class="card-body">
		<div id="face-alert"></div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th><?php echo $column_invoice; ?></th>
						<th class="d-none d-md-table-cell"><?php echo $column_customer; ?></th>
						<th class="d-none d-lg-table-cell"><?php echo $column_date; ?></th>
						<th class="text-end d-none d-sm-table-cell"><?php echo $column_total; ?></th>
						<th><?php echo $column_registry; ?></th>
						<th class="d-none d-lg-table-cell"><?php echo $column_environment; ?></th>
						<th><?php echo $column_status; ?></th>
						<th class="text-end"><?php echo $column_action; ?></th>
					</tr>
					<tr id="filter">
						<td colspan="6"></td>
						<td>
							<select name="filter_status" class="form-select">
								<option value=""><?php echo $text_all; ?></option>
								<?php foreach ($statuses as $status_code => $status_name) { ?>
								<option value="<?php echo $status_code; ?>"<?php echo ((string)$status_code === $filter_status) ? ' selected="selected"' : ''; ?>><?php echo $status_name; ?></option>
								<?php } ?>
							</select>
						</td>
						<td class="text-end"><button type="button" id="button-face-filter" class="btn btn-default"><i class="fa fa-filter"></i> <span class="d-none d-lg-inline"><?php echo $button_filter; ?></span></button></td>
					</tr>
				</thead>
				<tbody>
					<?php if ($records) { ?>
					<?php foreach ($records as $record) { ?>
					<tr>
						<td><a href="<?php echo $record['invoice']; ?>"><?php echo $record['number']; ?></a></td>
						<td class="d-none d-md-table-cell"><?php echo $record['customer']; ?></td>
						<td class="d-none d-lg-table-cell"><?php echo $record['date']; ?></td>
						<td class="text-end d-none d-sm-table-cell"><?php echo $record['total']; ?></td>
						<td>
							<?php echo $record['registry']; ?>
							<?php if ($record['dir3']) { ?>
							<div><small class="text-muted" title="DIR3"><?php echo $record['dir3']; ?></small></div>
							<?php } ?>
						</td>
						<td class="d-none d-lg-table-cell"><?php echo $record['environment']; ?></td>
						<td>
							<span class="badge <?php echo $record['badge']; ?>"><?php echo $record['status_text']; ?></span>
							<?php if ($record['cancel_text']) { ?>
							<div><small><?php echo htmlspecialchars($record['cancel_text'], ENT_QUOTES, 'UTF-8'); ?></small></div>
							<?php } ?>
							<?php if ($record['reason']) { ?>
							<div><small><?php echo $record['reason']; ?></small></div>
							<?php } ?>
						</td>
						<td class="text-end" style="white-space:nowrap;">
							<?php if ($can_modify) { ?>
							<?php if ($record['can_resend']) { ?>
							<button type="button" class="btn btn-primary face-resend" data-invoice-id="<?php echo $record['invoice_id']; ?>" title="<?php echo $button_resend; ?>"><i class="fa fa-paper-plane"></i></button>
							<?php } ?>
							<?php if ($record['registered']) { ?>
							<button type="button" class="btn btn-default face-refresh" data-invoice-id="<?php echo $record['invoice_id']; ?>" title="<?php echo $button_refresh; ?>"><i class="fa fa-sync"></i></button>
							<?php } ?>
							<?php if ($record['can_cancel']) { ?>
							<button type="button" class="btn btn-warning face-cancel" data-invoice-id="<?php echo $record['invoice_id']; ?>" title="<?php echo $button_cancel_invoice; ?>"><i class="fa fa-ban"></i></button>
							<?php } ?>
							<?php } ?>
							<a href="<?php echo $record['xml']; ?>" class="btn btn-default" title="<?php echo $button_xml; ?>"><i class="fa fa-download"></i></a>
						</td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="pagination"><?php echo $pagination; ?></div>
	</div>
</div>

<script type="text/javascript"><!--
function faceCall(url, data, $button) {
	$.ajax({
		url: url,
		type: 'post',
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$button.prop('disabled', true);
		},
		complete: function() {
			$button.prop('disabled', false);
		},
		success: function(json) {
			var $alert = $('<div class="alert"></div>').addClass(json['success'] ? 'alert-success' : 'alert-danger').html(json['success'] ? json['success'] : json['error']);

			$('#face-alert').empty().append($alert);

			if (json['success']) {
				setTimeout(function() { location.reload(); }, 1200);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			$('#face-alert').html('<div class="alert alert-danger"></div>').find('.alert').text(thrownError);
		}
	});
}

$('#button-face-filter').on('click', function() {
	var url = '<?php echo $filter_action; ?>';
	var status = $('select[name=\'filter_status\']').val();

	if (status) {
		url += '&filter_status=' + encodeURIComponent(status);
	}

	location = url;
});

$('#face-refresh-all').on('click', function() {
	faceCall('<?php echo $refresh_url; ?>', {}, $(this));
});

$('.face-refresh').on('click', function() {
	faceCall('<?php echo $refresh_url; ?>', {invoice_id: $(this).data('invoice-id')}, $(this));
});

$('.face-resend').on('click', function() {
	if (confirm('<?php echo addslashes(html_entity_decode($text_confirm_resend, ENT_QUOTES, 'UTF-8')); ?>')) {
		faceCall('<?php echo $send_url; ?>', {invoice_id: $(this).data('invoice-id')}, $(this));
	}
});

$('.face-cancel').on('click', function() {
	if (!confirm('<?php echo addslashes(html_entity_decode($text_confirm_cancel, ENT_QUOTES, 'UTF-8')); ?>')) {
		return;
	}

	var reason = prompt('<?php echo addslashes(html_entity_decode($text_prompt_reason, ENT_QUOTES, 'UTF-8')); ?>');

	if (reason && $.trim(reason) !== '') {
		faceCall('<?php echo $cancel_url; ?>', {invoice_id: $(this).data('invoice-id'), reason: reason}, $(this));
	}
});
//--></script>

<?php echo $footer; ?>
