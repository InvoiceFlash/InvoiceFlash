<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-file-pdf"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<p><?php echo $text_docemb_instruction; ?></p>
		<div id="docemb-progress" class="alert alert-info" style="display:none;"></div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td style="width:1px;" class="text-center"><input type="checkbox" id="docemb-select-all"></td>
						<td><?php echo $column_docemb_filename; ?></td>
						<td><?php echo $column_docemb_product; ?></td>
						<td><?php echo $column_docemb_status; ?></td>
						<td class="text-center"><?php echo $column_docemb_pages; ?></td>
						<td class="text-center"><?php echo $column_docemb_chunks; ?></td>
						<td><?php echo $column_docemb_date; ?></td>
					</tr>
				</thead>
				<tbody id="docemb-tbody">
					<?php if ($document_files) { ?>
					<?php foreach ($document_files as $document) { ?>
					<tr>
						<td class="text-center"><input type="checkbox" class="docemb-select" value="<?php echo htmlspecialchars($document['filename'], ENT_QUOTES); ?>"></td>
						<td><?php echo htmlspecialchars($document['filename'], ENT_QUOTES); ?></td>
						<td><?php echo $document['product_name'] ? htmlspecialchars($document['product_name'], ENT_QUOTES) : '<span class="text-danger">' . $text_docemb_no_product . '</span>'; ?></td>
						<td>
							<?php if ($document['status'] == 'done') { ?>
							<span class="badge bg-success"><?php echo $text_docemb_status_done; ?></span>
							<?php } elseif ($document['status'] == 'error') { ?>
							<span class="badge bg-danger" title="<?php echo htmlspecialchars($document['error_message'], ENT_QUOTES); ?>"><?php echo $text_docemb_status_error; ?></span>
							<?php } elseif ($document['status'] == 'processing') { ?>
							<span class="badge bg-warning"><?php echo $text_docemb_status_processing; ?></span>
							<?php } else { ?>
							<span class="badge bg-secondary"><?php echo $text_docemb_status_pending; ?></span>
							<?php } ?>
						</td>
						<td class="text-center"><?php echo $document['pages'] ? $document['pages'] : ''; ?></td>
						<td class="text-center"><?php echo $document['chunks'] ? $document['chunks'] : ''; ?></td>
						<td><?php echo $document['date_finished']; ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td colspan="7" class="text-center"><?php echo $text_docemb_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<button type="button" id="button-docemb-run" class="btn btn-info"><i class="fa fa-play"></i> <?php echo $button_docemb_run; ?></button>
	</div>
</div>

<script>
	var docembRunUrl = <?php echo json_encode(html_entity_decode($docemb_run_url, ENT_QUOTES, 'UTF-8')); ?>;
	var docembStatusUrl = <?php echo json_encode(html_entity_decode($docemb_status_url, ENT_QUOTES, 'UTF-8')); ?>;
	var docembWatching = false;

	var docembStatusLabels = {
		pending: {cls: 'bg-secondary', text: <?php echo json_encode($text_docemb_status_pending); ?>},
		processing: {cls: 'bg-warning', text: <?php echo json_encode($text_docemb_status_processing); ?>},
		done: {cls: 'bg-success', text: <?php echo json_encode($text_docemb_status_done); ?>},
		error: {cls: 'bg-danger', text: <?php echo json_encode($text_docemb_status_error); ?>}
	};

	function docembEsc(s) {
		return $('<div>').text(s === null || s === undefined ? '' : s).html();
	}

	function docembEscAttr(s) {
		return docembEsc(s).replace(/"/g, '&quot;');
	}

	function docembRenderRows(documents) {
		if (!documents || !documents.length) {
			$('#docemb-tbody').html('<tr><td colspan="7" class="text-center">' + docembEsc(<?php echo json_encode($text_docemb_no_results); ?>) + '</td></tr>');
			return;
		}

		var html = '';

		documents.forEach(function(doc) {
			var st = docembStatusLabels[doc.status] || docembStatusLabels.pending;
			var productHtml = doc.product_name ? docembEsc(doc.product_name) : '<span class="text-danger">' + docembEsc(<?php echo json_encode($text_docemb_no_product); ?>) + '</span>';
			var errTitle = doc.error_message ? ' title="' + docembEscAttr(doc.error_message) + '"' : '';

			html += '<tr>' +
				'<td class="text-center"><input type="checkbox" class="docemb-select" value="' + docembEscAttr(doc.filename) + '"></td>' +
				'<td>' + docembEsc(doc.filename) + '</td>' +
				'<td>' + productHtml + '</td>' +
				'<td><span class="badge ' + st.cls + '"' + errTitle + '>' + docembEsc(st.text) + '</span></td>' +
				'<td class="text-center">' + (doc.pages || '') + '</td>' +
				'<td class="text-center">' + (doc.chunks || '') + '</td>' +
				'<td>' + (doc.date_finished || '') + '</td>' +
				'</tr>';
		});

		$('#docemb-tbody').html(html);
	}

	function docembPoll() {
		$.ajax({
			url: docembStatusUrl,
			type: 'get',
			dataType: 'json',
			success: function(json) {
				docembRenderRows(json.documents);

				if (json.running) {
					var label = json.current_file ? json.current_file : '';
					var pageInfo = (json.total_pages > 0) ? ' (' + json.current_page + '/' + json.total_pages + ')' : '';
					var fileInfo = ' [' + json.processed_files + '/' + json.total_files + ']';

					$('#docemb-progress').removeClass('alert-danger').addClass('alert-info').text(label + pageInfo + fileInfo).show();

					setTimeout(docembPoll, 3000);
				} else if (docembWatching) {
					docembWatching = false;

					if (json.errors && json.errors.length) {
						var msg = json.errors.map(function(e) { return e.file + ': ' + e.error; }).join(' | ');
						$('#docemb-progress').removeClass('alert-info').addClass('alert-danger').text(msg).show();
					} else {
						$('#docemb-progress').hide();
					}
				}
			}
		});
	}

	$('#docemb-select-all').on('change', function() {
		$('.docemb-select').prop('checked', this.checked);
	});

	$('#button-docemb-run').on('click', function() {
		var files = [];

		$('.docemb-select:checked').each(function() {
			files.push($(this).val());
		});

		if (!files.length) {
			alert(text_select_warning);
			return;
		}

		if (!confirm(text_confirm)) {
			return;
		}

		$.ajax({
			url: docembRunUrl,
			type: 'post',
			data: {selected: files},
			dataType: 'json',
			success: function(json) {
				if (json.error) {
					$('#docemb-progress').removeClass('alert-info').addClass('alert-danger').text(json.error).show();
					return;
				}

				docembWatching = true;
				$('#docemb-progress').removeClass('alert-danger').addClass('alert-info').text(json.success).show();
				docembPoll();
			}
		});
	});

	$(document).ready(function() {
		$.ajax({
			url: docembStatusUrl,
			type: 'get',
			dataType: 'json',
			success: function(json) {
				if (json.running) {
					docembWatching = true;
					docembRenderRows(json.documents);

					var label = json.current_file ? json.current_file : '';
					$('#docemb-progress').removeClass('alert-danger').addClass('alert-info').text(label).show();

					setTimeout(docembPoll, 3000);
				}
			}
		});
	});
</script>
<?php echo $footer; ?>
