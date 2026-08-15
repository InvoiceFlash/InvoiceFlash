<?php echo $header; ?>

<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>

<div class="panel panel-default">

	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-vector-square"></i> <?php echo $heading_title; ?></div>
	</div>

	<div class="panel-body">
		<p><?php echo $text_docemb_instruction; ?></p>
		<div class="form-group row">
			<label class="col-form-label col-sm-2" for="docemb-type"><?php echo $text_docemb_type; ?></label>
			<div class="col-sm-4">
				<select id="docemb-type" class="form-control">
					<?php foreach ($docemb_types as $option) { ?>
					<option value="<?php echo htmlspecialchars($option['value'], ENT_QUOTES); ?>" <?php echo ($option['value'] == $active_type) ? 'selected' : ''; ?>><?php echo $option['text']; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-sm-6">
				<button type="button" id="button-docemb-filter" class="btn btn-default"><i class="fa fa-filter"></i> <?php echo $button_docemb_filter; ?></button>
				<button type="button" id="button-docemb-run" class="btn btn-info"><i class="fa fa-play"></i> <?php echo $button_docemb_run; ?></button>
			</div>
		</div>
		<div id="docemb-progress" class="alert alert-info" style="display:none;"></div>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td style="width:1px;" class="text-center"><input type="checkbox" id="docemb-select-all"></td>
						<td><?php echo $column_docemb_title; ?></td>
						<td><?php echo $column_docemb_related; ?></td>
						<td><?php echo $column_docemb_date; ?></td>
					</tr>
				</thead>
				<tbody id="docemb-tbody">
					<?php if ($document_files) { ?>
					<?php foreach ($document_files as $document) { ?>
					<tr>
						<td class="text-center"><input type="checkbox" class="docemb-select" value="<?php echo (int)$document['id']; ?>"></td>
						<td><?php echo htmlspecialchars($document['title'], ENT_QUOTES); ?></td>
						<td><?php echo $document['related'] ? htmlspecialchars($document['related'], ENT_QUOTES) : ''; ?></td>
						<td><?php echo htmlspecialchars($document['date'], ENT_QUOTES); ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td colspan="4" class="text-center"><?php echo $text_docemb_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	var docembRunUrl = <?php echo json_encode(html_entity_decode($docemb_run_url, ENT_QUOTES, 'UTF-8')); ?>;
	var docembStatusUrl = <?php echo json_encode(html_entity_decode($docemb_status_url, ENT_QUOTES, 'UTF-8')); ?>;
	var docembIndexUrl = <?php echo json_encode(html_entity_decode($docemb_index_url, ENT_QUOTES, 'UTF-8')); ?>;
	var docembWatching = false;
	var docembNoResultsText = <?php echo json_encode($text_docemb_no_results); ?>;

	function docembCurrentType() {
		return $('#docemb-type').val();
	}

	function docembEsc(s) {
		return $('<div>').text(s === null || s === undefined ? '' : s).html();
	}

	function docembRenderRows(documents) {
		if (!documents || !documents.length) {
			$('#docemb-tbody').html('<tr><td colspan="4" class="text-center">' + docembEsc(docembNoResultsText) + '</td></tr>');
			return;
		}

		var html = '';

		documents.forEach(function(doc) {
			html += '<tr>' +
				'<td class="text-center"><input type="checkbox" class="docemb-select" value="' + parseInt(doc.id, 10) + '"></td>' +
				'<td>' + docembEsc(doc.title) + '</td>' +
				'<td>' + docembEsc(doc.related || '') + '</td>' +
				'<td>' + docembEsc(doc.date) + '</td>' +
				'</tr>';
		});

		$('#docemb-tbody').html(html);
	}

	function docembPoll() {
		$.ajax({
			url: docembStatusUrl + '&type=' + encodeURIComponent(docembCurrentType()),
			type: 'get',
			dataType: 'json',
			cache: false,
			success: function(json) {
				// docembRenderRows() se llama SIEMPRE con el resultado fresco de
				// getUnindexedItems(), este corriendo o no el proceso - asi las filas
				// ya indexadas desaparecen solas en el siguiente sondeo, sin recargar
				// la pagina.
				docembRenderRows(json.documents);

				if (json.running) {
					var label = json.current_file ? json.current_file : '';
					var fileInfo = ' [' + json.processed_files + '/' + json.total_files + ']';

					$('#docemb-progress').removeClass('alert-danger').addClass('alert-info').text(label + fileInfo).show();

					setTimeout(docembPoll, 1500);
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

	$('#button-docemb-filter').on('click', function() {
		window.location = docembIndexUrl + '&type=' + encodeURIComponent(docembCurrentType());
	});

	$('#button-docemb-run').on('click', function() {
		var ids = [];

		$('.docemb-select:checked').each(function() {
			ids.push($(this).val());
		});

		if (!ids.length) {
			alert(text_select_warning);
			return;
		}

		if (!confirm(text_confirm)) {
			return;
		}

		$.ajax({
			url: docembRunUrl,
			type: 'post',
			data: {selected: ids, type: docembCurrentType()},
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
			url: docembStatusUrl + '&type=' + encodeURIComponent(docembCurrentType()),
			type: 'get',
			dataType: 'json',
			cache: false,
			success: function(json) {
				if (json.running) {
					docembWatching = true;
					docembRenderRows(json.documents);

					var label = json.current_file ? json.current_file : '';
					$('#docemb-progress').removeClass('alert-danger').addClass('alert-info').text(label).show();

					setTimeout(docembPoll, 1500);
				}
			}
		});
	});
</script>
<?php echo $footer; ?>
