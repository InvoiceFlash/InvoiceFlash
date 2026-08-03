<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-file-text"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<p><?php echo $text_select_document; ?></p>
	</div>
</div>

<div id="ReportTypeModal" class="modal fade" role="dialog" tabindex='-1'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_select_document; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form id="formReportType">
					<?php foreach ($types as $type) { ?>
					<div class="radio">
						<label><input type="radio" name="doc_type" value="<?php echo $type['type']; ?>"> <?php echo $type['name']; ?></label>
					</div>
					<?php } ?>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" type="button" id="btnReportTypeContinue"><?php echo $button_continue; ?></button>
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>

<div id="ReportFormatModal" class="modal fade" role="dialog" tabindex='-1'>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $text_existing_formats; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered">
					<tbody id="rf-formats"></tbody>
				</table>
				<div class="form-group">
					<label><?php echo $text_new_format_name; ?></label>
					<input type="text" id="rf-new-name" class="form-control" />
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" type="button" id="btnCreateFormat"><?php echo $button_create_edit; ?></button>
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?php echo $button_close; ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	var rfSelectedType = null;

	$('input[name="doc_type"]:first').prop('checked', true);

	bootstrap.Modal.getOrCreateInstance(document.getElementById('ReportTypeModal')).show();

	$('#btnReportTypeContinue').on('click', function() {
		rfSelectedType = $('input[name="doc_type"]:checked').val();

		bootstrap.Modal.getInstance(document.getElementById('ReportTypeModal')).hide();

		$.ajax({
			url: '<?php echo $get_formats; ?>&type=' + rfSelectedType,
			dataType: 'json',
			success: function(json) {
				var html = '';

				if (json.formats && json.formats.length) {
					json.formats.forEach(function(f, index) {
						html += '<tr>';
						html += '<td><input type="radio" name="rf-source" value="' + f.report_format_id + '"' + (index === 0 ? ' checked' : '') + '></td>';
						html += '<td>' + f.name + (f.is_default ? ' <span class="label label-default"><?php echo $text_default; ?></span>' : '') + '</td>';
						html += '<td>' + (f.is_active ? '<span class="label label-success"><?php echo $text_active; ?></span>' : '') + '</td>';
						html += '<td>';

						if (!f.is_default) {
							html += '<a href="' + json.edit_url + f.report_format_id + '" class="btn btn-primary btn-sm"><?php echo $button_edit; ?></a> ';
							html += '<button type="button" class="btn btn-danger btn-sm rf-delete" data-id="' + f.report_format_id + '"><?php echo $button_delete; ?></button>';
						}

						html += '</td>';
						html += '</tr>';
					});
				} else {
					html = '<tr><td colspan="4"><?php echo $text_no_formats; ?></td></tr>';
				}

				$('#rf-formats').html(html);
				$('#rf-new-name').val('');

				bootstrap.Modal.getOrCreateInstance(document.getElementById('ReportFormatModal')).show();
			}
		});
	});

	$('#btnCreateFormat').on('click', function() {
		var sourceId = $('input[name="rf-source"]:checked').val();
		var name = $('#rf-new-name').val();

		if (!sourceId || !name) {
			return;
		}

		$.ajax({
			url: '<?php echo $create_format; ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				type: rfSelectedType,
				source_format_id: sourceId,
				name: name
			},
			success: function(json) {
				if (json.report_format_id) {
					window.location = '<?php echo $edit_format; ?>&report_format_id=' + json.report_format_id;
				} else if (json.error) {
					alert(json.error);
				}
			}
		});
	});

	$(document).on('click', '.rf-delete', function() {
		var id = $(this).data('id');

		if (!confirm('<?php echo $text_confirm_delete; ?>')) {
			return;
		}

		var row = $(this).closest('tr');

		$.ajax({
			url: '<?php echo $delete_format; ?>',
			type: 'POST',
			dataType: 'json',
			data: { report_format_id: id },
			success: function(json) {
				if (json.success) {
					row.remove();
				} else if (json.error) {
					alert(json.error);
				}
			}
		});
	});
});
</script>
<?php echo $footer; ?>
