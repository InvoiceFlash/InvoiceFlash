<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-building"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<p><?php echo $text_instruction; ?></p>

		<?php if (!$has_api_key) { ?>
		<div class="alert alert-warning"><?php echo $error_no_api_key; ?></div>
		<?php } ?>

		<div id="filter" class="well">
			<div class="d-flex flex-wrap align-items-end mb-4" style="gap:.75rem;">
				<div style="width:150px;">
					<label class="control-label d-block"><?php echo $entry_date; ?><br>&nbsp;</label>
					<div class="input-group" style="position:relative;margin-bottom:0;">
						<input type="text" id="borme-date" class="form-control date" style="height:calc(2.0625rem + 2px);" value="<?php echo $default_date; ?>">
						<div class="input-group-append"><div class="input-group-text" style="height:calc(2.0625rem + 2px);"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div style="width:200px;">
					<label class="control-label d-block"><?php echo $entry_province; ?><br>&nbsp;</label>
					<input type="text" id="borme-province" class="form-control" style="height:calc(2.0625rem + 2px);" value="<?php echo $default_province; ?>">
				</div>
				<div style="width:120px;">
					<label class="control-label d-block"><?php echo $entry_max_emails; ?></label>
					<input type="number" id="borme-max-emails" class="form-control" style="height:calc(2.0625rem + 2px);" min="1" value="<?php echo $default_max_emails; ?>">
				</div>
				<div>
					<label class="control-label d-block">&nbsp;<br>&nbsp;</label>
					<button type="button" id="borme-run" class="btn btn-info" style="height:calc(2.0625rem + 2px);" onclick="bormeRun();" <?php echo (!$has_api_key) ? 'disabled' : ''; ?>>
						<i class="fa fa-play"></i> <?php echo $button_run; ?>
					</button>
				</div>
				<div>
					<label class="control-label d-block">&nbsp;<br>&nbsp;</label>
					<div id="borme-status" class="text-muted"></div>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td class="left"><?php echo $column_date; ?></td>
						<td class="left"><?php echo $column_province; ?></td>
						<td class="left"><?php echo $column_company; ?></td>
						<td class="left"><?php echo $column_city; ?></td>
						<td class="left"><?php echo $column_website; ?></td>
						<td class="left"><?php echo $column_email; ?></td>
						<td class="left"><?php echo $column_status; ?></td>
						<td class="left"><?php echo $column_action; ?></td>
					</tr>
				</thead>
				<tbody id="borme-results">
					<?php if ($bormes) { ?>
					<?php foreach ($bormes as $borme) { ?>
					<tr id="borme-row-<?php echo $borme['borme_id']; ?>">
						<td class="text-left"><?php echo $borme['borme_date']; ?></td>
						<td class="text-left"><?php echo $borme['province']; ?></td>
						<td class="text-left" data-role="company"><?php echo $borme['company_name']; ?></td>
						<td class="text-left"><?php echo $borme['city']; ?></td>
						<td class="text-left" data-role="website"><?php if ($borme['website']) { ?><a href="<?php echo $borme['website']; ?>" target="_blank" rel="noopener"><?php echo $borme['website']; ?></a><?php } ?></td>
						<td class="text-left" data-role="email"><?php echo $borme['email']; ?></td>
						<td class="text-left" data-role="status">
							<button type="button" class="badge <?php echo ($borme['status'] == 'found') ? 'bg-success' : 'bg-secondary'; ?> border-0" onclick="bormeEditEmail(<?php echo $borme['borme_id']; ?>, '<?php echo htmlspecialchars($borme['company_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars((string)$borme['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars((string)$borme['website'], ENT_QUOTES); ?>');"><?php echo ($borme['status'] == 'found') ? $text_status_found : $text_status_not_found; ?></button>
						</td>
						<td class="text-left" data-role="action">
							<button type="button" class="btn btn-default btn-sm" onclick="bormeOpenSendEmail(<?php echo $borme['borme_id']; ?>, '<?php echo htmlspecialchars((string)$borme['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($borme['company_name'], ENT_QUOTES); ?>');"><i class="fa fa-envelope"></i> <?php echo $button_email; ?></button>
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

		<?php echo $pagination; ?>
	</div>
</div>

<!-- Modal Email BORME -->
<div class="modal fade" tabindex="-1" role="dialog" id="BormeEmailModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="borme-email-modal-title"><?php echo $text_edit_email; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="borme-email-modal-id" value="0">
				<div class="form-group">
					<label class="control-label"><?php echo $entry_email; ?></label>
					<input type="email" id="borme-email-modal-input" class="form-control">
				</div>
				<div class="form-group">
					<label class="control-label"><?php echo $entry_website; ?></label>
					<input type="text" id="borme-email-modal-website" class="form-control" placeholder="https://...">
					<div id="borme-email-modal-error" class="text-danger" style="display:none;"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
				<button type="button" class="btn btn-success" onclick="bormeSaveEmail();"><?php echo $button_save; ?></button>
			</div>
		</div>
	</div>
</div>
<!-- Fin Modal Email BORME -->

<!-- Modal Send Email BORME -->
<div class="modal fade" tabindex="-1" role="dialog" id="BormeSendEmailModal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="borme-send-email-modal-title"><?php echo $button_email; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="borme-send-email-id" value="0">
				<div class="form-group row">
					<label class="control-label col-sm-2" for="borme-send-to"><?php echo $text_to; ?></label>
					<div class="col-sm-10">
						<input type="email" id="borme-send-to" class="form-control">
						<span class="text-danger" id="borme-send-error-to"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-sm-2" for="borme-send-subject"><?php echo $text_subject; ?></label>
					<div class="col-sm-10">
						<input type="text" id="borme-send-subject" class="form-control">
						<span class="text-danger" id="borme-send-error-subject"></span>
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-sm-2" for="borme-send-message"><?php echo $text_message; ?></label>
					<div class="col-sm-10">
						<textarea id="borme-send-message" class="ckeditor form-control" spellcheck="false"></textarea>
						<span class="text-danger" id="borme-send-error-message"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="borme-send-button" class="btn btn-success"><?php echo $button_send; ?></button>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>
<!-- Fin Modal Send Email BORME -->

<script type="text/javascript">
var bormePollTimer = null;
var bormeWatching = false; // solo recargamos la pagina si nosotros mismos vimos el proceso en marcha

function bormeDateToApi(value) {
	// convierte DD-MM-YYYY (formato del datepicker del proyecto) a YYYYMMDD (formato que espera el script)
	var parts = (value || '').split('-');
	if (parts.length !== 3) {
		return '';
	}
	return parts[2] + parts[1] + parts[0];
}

function bormeRun() {
	bormeWatching = true;
	$('#borme-run').prop('disabled', true);
	$('#borme-status').html('<i class="fa fa-spinner fa-spin"></i> ...');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $run_url); ?>',
		type: 'post',
		data: {
			date: bormeDateToApi($('#borme-date').val()),
			province: $('#borme-province').val(),
			max_emails: $('#borme-max-emails').val()
		},
		dataType: 'json',
		success: function(json) {
			if (json.error) {
				$('#borme-status').text(json.error);
				$('#borme-run').prop('disabled', false);
				return;
			}

			bormePoll();
		},
		error: function() {
			$('#borme-status').text('Error de conexión.');
			$('#borme-run').prop('disabled', false);
		}
	});
}

function bormePoll() {
	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $status_url); ?>',
		type: 'get',
		dataType: 'json',
		success: function(json) {
			if (json.running) {
				bormeWatching = true; // habia un proceso activo al abrir/recargar la pantalla: cuando termine, refrescamos una vez
				var msg = json.message ? json.message : '...';
				$('#borme-status').html('<i class="fa fa-spinner fa-spin"></i> ' + msg + ' (' + (json.found_emails || 0) + '/' + (json.target_emails || 0) + ')');
				bormePollTimer = setTimeout(bormePoll, 3000);
			} else {
				clearTimeout(bormePollTimer);
				$('#borme-run').prop('disabled', false);

				if (json.message) {
					$('#borme-status').text(json.message);
				}

				if (bormeWatching && json.finished_at) {
					bormeWatching = false;
					location.reload();
				}
			}
		}
	});
}

function bormeEditEmail(bormeId, companyName, email, website) {
	$('#borme-email-modal-id').val(bormeId);
	$('#borme-email-modal-input').val(email || '');
	$('#borme-email-modal-website').val(website || '');
	$('#borme-email-modal-error').hide().text('');
	$('#borme-email-modal-title').text('<?php echo $text_edit_email; ?>: ' + companyName);
	bootstrap.Modal.getOrCreateInstance(document.getElementById('BormeEmailModal')).show();
}

$('#BormeEmailModal').on('shown.bs.modal', function() {
	$('#borme-email-modal-input').focus();
});

function bormeSaveEmail() {
	var bormeId = $('#borme-email-modal-id').val();
	var email = $('#borme-email-modal-input').val();
	var website = $('#borme-email-modal-website').val();

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $save_email_url); ?>',
		type: 'post',
		data: { borme_id: bormeId, email: email, website: website },
		dataType: 'json',
		success: function(json) {
			if (json.error) {
				$('#borme-email-modal-error').text(json.error).show();
				return;
			}

			var $row = $('#borme-row-' + bormeId);
			$row.find('[data-role="email"]').text(json.email);
			$row.find('[data-role="website"]').html(json.website ? ('<a href="' + json.website + '" target="_blank" rel="noopener">' + json.website + '</a>') : '');
			$row.find('[data-role="status"]').html('<span class="badge bg-success"><?php echo $text_status_found; ?></span>');

			bootstrap.Modal.getInstance(document.getElementById('BormeEmailModal')).hide();
		},
		error: function() {
			$('#borme-email-modal-error').text('Error de conexión.').show();
		}
	});
}

function bormeOpenSendEmail(bormeId, email, companyName) {
	$('#borme-send-email-id').val(bormeId);
	$('#borme-send-to').val(email || '');
	$('#borme-send-subject').val('');

	if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['borme-send-message']) {
		CKEDITOR.instances['borme-send-message'].setData('');
	}

	$('#borme-send-error-to,#borme-send-error-subject,#borme-send-error-message').text('');
	$('#borme-send-email-modal-title').text('<?php echo $button_email; ?>: ' + companyName);
	bootstrap.Modal.getOrCreateInstance(document.getElementById('BormeSendEmailModal')).show();
}

$('#BormeSendEmailModal').on('shown.bs.modal', function() {
	$('#borme-send-to').trigger('focus');
});

$('#borme-send-button').on('click', function() {
	var to = $('#borme-send-to').val();
	var subject = $('#borme-send-subject').val();
	var message = (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['borme-send-message']) ? CKEDITOR.instances['borme-send-message'].getData() : '';

	$('#borme-send-error-to,#borme-send-error-subject,#borme-send-error-message').text('');

	$.ajax({
		url: '<?php echo str_replace('&amp;', '&', $send_email_url); ?>',
		type: 'post',
		dataType: 'json',
		data: { to: to, subject: subject, message: message },
		beforeSend: function() {
			$('#borme-send-button').prop('disabled', true);
		},
		success: function(json) {
			$('#borme-send-button').prop('disabled', false);

			if (json.error) {
				if (json.error.to) { $('#borme-send-error-to').text(json.error.to); }
				if (json.error.subject) { $('#borme-send-error-subject').text(json.error.subject); }
				if (json.error.message) { $('#borme-send-error-message').text(json.error.message); }

				if (json.error.permission) {
					bootstrap.Modal.getInstance(document.getElementById('BormeSendEmailModal')).hide();
					alert(json.error.permission);
				}

				return;
			}

			if (json.success) {
				bootstrap.Modal.getInstance(document.getElementById('BormeSendEmailModal')).hide();
			}
		},
		error: function() {
			$('#borme-send-button').prop('disabled', false);
			$('#borme-send-error-message').text('Error de conexión.');
		}
	});
});

$(document).ready(function() {
	bormePoll();
});
</script>

<?php echo $footer; ?>
