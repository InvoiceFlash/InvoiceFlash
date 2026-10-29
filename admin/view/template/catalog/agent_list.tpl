<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="float-start h2"><i class="fa fa-robot"></i> <?php echo $heading_title; ?></div>
		<div class="float-end">
			<a href="<?php echo $insert; ?>" class="btn btn-primary"<?php if (!$has_api_key) { ?> onclick="alertMessage('danger', <?php echo htmlspecialchars(json_encode(html_entity_decode($error_no_api_key, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'); ?>); return false;"<?php } ?>><i class="fa fa-plus-circle"></i><span class="d-none d-lg-inline"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash"></i><span class="d-none d-lg-inline"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="card-body">
		<p class="text-muted"><?php echo $text_cron_note; ?></p>
		<p><a href="<?php echo $link_all; ?>"><?php echo $text_filter; ?></a></p>
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center" style="width:40px;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"/></th>
						<th class="text-start"><?php echo $column_name; ?></th>
						<th class="text-start"><?php echo $column_description; ?></th>
						<th class="text-start"><?php echo $column_schedule; ?></th>
						<th class="text-start"><?php echo $column_model; ?></th>
						<th class="text-start"><?php echo $column_access; ?></th>
						<th class="text-start"><?php echo $column_status; ?></th>
						<th class="text-start"><?php echo $column_last_run; ?></th>
						<th class="text-start"><?php echo $column_last_result; ?></th>
						<th class="text-end"><?php echo $column_action; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($agents) { ?>
						<?php foreach ($agents as $agent) { ?>
							<tr>
								<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $agent['agent_id']; ?>"></td>
								<td class="text-start"><?php echo $agent['name']; ?></td>
								<td class="text-start"><?php echo $agent['description']; ?></td>
								<td class="text-start"><?php echo $agent['schedule']; ?></td>
								<td class="text-start"><?php echo htmlspecialchars($agent['model'], ENT_QUOTES, 'UTF-8'); ?></td>
								<td class="text-start"><?php if ($agent['access'] == 'write') { ?><span class="badge bg-warning text-dark"><?php echo $text_access_write; ?></span><?php } else { ?><span class="badge bg-secondary"><?php echo $text_access_read; ?></span><?php } ?></td>
								<td class="text-start"><?php echo $agent['status'] ? $text_enabled : $text_disabled; ?></td>
								<td class="text-start"><?php echo $agent['last_run']; ?></td>
								<td class="text-start">
									<?php if ($agent['last_status'] == 'error') { ?><span class="badge bg-danger">ERROR</span><?php } elseif ($agent['last_status'] == 'ok') { ?><span class="badge bg-success">OK</span><?php } elseif ($agent['last_status'] == 'stopped' || $agent['last_status'] == 'stopping') { ?><span class="badge bg-secondary">STOP</span><?php } elseif ($agent['last_status'] == 'running') { ?><span class="badge bg-warning">...</span><?php } ?>
									<?php echo $agent['last_result']; ?>
								</td>
								<td class="text-end" style="white-space:nowrap;">
									<a href="<?php echo $agent['edit']; ?>" class="btn btn-default" title="<?php echo $button_edit; ?>"><i class="fas fa-edit"></i></a>
									<a href="#" class="btn btn-warning btn-agent-run" data-id="<?php echo $agent['agent_id']; ?>" title="<?php echo $button_run; ?>"><i class="fa fa-play"></i></a>
									<a href="#" class="btn btn-danger btn-agent-stop" data-id="<?php echo $agent['agent_id']; ?>" title="<?php echo $button_stop; ?>"><i class="fa fa-stop-circle"></i></a>
									<a href="<?php echo $agent['history']; ?>" class="btn btn-info" title="<?php echo $button_history; ?>"><i class="fa fa-list-alt"></i><span class="d-none d-lg-inline"> <?php echo $button_history; ?></span></a>
								</td>
							</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td colspan="10" class="text-center"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
</div>
<script>
// Antipanico: sin confirmacion (tiene que ser inmediato). Para la ejecucion en curso y desactiva el agente.
$('.btn-agent-stop').click(function(e) {
	e.preventDefault();

	var $btn = $(this);

	$btn.addClass('disabled');

	$.ajax({
		url: <?php echo json_encode($stop_url); ?> + '&agent_id=' + $btn.attr('data-id'),
		type: 'get',
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				alertMessage('danger', json['error']);
			} else {
				alertMessage('success', json['success']);
			}
		},
		error: function() {
			alertMessage('danger', <?php echo json_encode(html_entity_decode($text_run_error, ENT_QUOTES, 'UTF-8')); ?>);
		},
		complete: function() {
			$btn.removeClass('disabled');
		}
	});
});

$('.btn-agent-run').click(function(e) {
	e.preventDefault();

	var $btn = $(this);

	if ($btn.hasClass('disabled')) {
		return;
	}

	<?php if (!$has_api_key) { ?>
	alertMessage('danger', <?php echo json_encode(html_entity_decode($error_no_api_key, ENT_QUOTES, 'UTF-8')); ?>);
	return;
	<?php } ?>

	if (!confirm(<?php echo json_encode(html_entity_decode($text_run_confirm, ENT_QUOTES, 'UTF-8')); ?>)) {
		return;
	}

	$btn.addClass('disabled').find('i').removeClass('fa-play').addClass('fa-spinner fa-spin');

	$.ajax({
		url: <?php echo json_encode($run_url); ?> + '&agent_id=' + $btn.attr('data-id'),
		type: 'get',
		dataType: 'json',
		timeout: 0,
		success: function(json) {
			if (json['error']) {
				alertMessage('danger', json['error']);
			} else {
				alertMessage('success', json['success']);
			}
		},
		error: function() {
			alertMessage('danger', <?php echo json_encode(html_entity_decode($text_run_error, ENT_QUOTES, 'UTF-8')); ?>);
		},
		complete: function() {
			$btn.removeClass('disabled').find('i').removeClass('fa-spinner fa-spin').addClass('fa-play');
		}
	});
});
</script>
<?php echo $footer; ?>
