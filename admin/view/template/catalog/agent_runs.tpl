<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="float-start h2"><i class="fa fa-history"></i> <?php echo $heading_title; ?></div>
		<div class="float-end">
			<?php if ($runs) { ?>
				<button type="button" class="btn btn-danger" onclick="if (confirm(<?php echo htmlspecialchars(json_encode(html_entity_decode($text_confirm_delete_runs, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'); ?>)) { $('#form-runs').attr('action', '<?php echo $delete_runs; ?>').submit(); }"><i class="fa fa-trash"></i><span class="d-none d-lg-inline"> <?php echo $button_delete; ?></span></button>
			<?php } ?>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-arrow-left"></i><span class="d-none d-lg-inline"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="card-body">
		<h5><?php echo $text_runs_title; ?></h5>
		<form action="<?php echo $delete_runs; ?>" method="post" enctype="multipart/form-data" id="form-runs">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th class="text-center" style="width:40px;"><input type="checkbox" onclick="$('input[name*=\'selected_runs\']').prop('checked', this.checked);"/></th>
					<th class="text-start"><?php echo $column_started; ?></th>
					<th class="text-start"><?php echo $column_finished; ?></th>
					<th class="text-start"><?php echo $column_run_status; ?></th>
					<th class="text-start"><?php echo $column_trigger; ?></th>
					<th class="text-start"><?php echo $column_summary; ?></th>
					<th class="text-start"><?php echo $column_changes; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($runs) { ?>
					<?php foreach ($runs as $run) { ?>
						<tr>
							<td class="text-center"><input type="checkbox" name="selected_runs[]" value="<?php echo (int)$run['run_id']; ?>"></td>
							<td class="text-start"><?php echo $run['date_started']; ?></td>
							<td class="text-start"><?php echo $run['date_finished']; ?></td>
							<td class="text-start">
								<?php if ($run['status'] == 'ok') { ?><span class="badge bg-success"><?php echo $text_run_status_ok; ?></span>
								<?php } elseif ($run['status'] == 'error') { ?><span class="badge bg-danger"><?php echo $text_run_status_error; ?></span>
								<?php } elseif ($run['status'] == 'stopped') { ?><span class="badge bg-secondary"><?php echo $text_run_status_stopped; ?></span>
								<?php } elseif ($run['status'] == 'stopping') { ?><span class="badge bg-warning"><?php echo $text_run_status_stopping; ?></span>
								<?php } else { ?><span class="badge bg-warning"><?php echo $text_run_status_running; ?></span><?php } ?>
							</td>
							<td class="text-start"><?php echo ($run['trigger'] == 'manual') ? $text_trigger_manual : $text_trigger_cron; ?></td>
							<td class="text-start" style="white-space:pre-wrap;"><?php echo $run['summary']; ?><?php if ($run['error']) { ?><div class="text-danger"><?php echo $run['error']; ?></div><?php } ?></td>
							<td class="text-start">
								<?php if ($run['actions']) { ?>
									<?php foreach ($run['actions'] as $action) { ?>
										<?php if (isset($action['sql'])) { ?>
											<div class="mb-1"><code><?php echo htmlspecialchars($action['sql'], ENT_QUOTES, 'UTF-8'); ?></code>
											<?php if (isset($action['error'])) { ?><span class="badge bg-danger"><?php echo htmlspecialchars($action['error'], ENT_QUOTES, 'UTF-8'); ?></span><?php } else { ?><span class="badge bg-secondary"><?php echo (int)$action['rows']; ?></span><?php } ?></div>
										<?php } elseif (isset($action['search'])) { ?>
											<div class="mb-1"><i class="fa fa-globe"></i> <?php echo htmlspecialchars($action['search'], ENT_QUOTES, 'UTF-8'); ?>
											<?php if (isset($action['error'])) { ?><span class="badge bg-danger"><?php echo htmlspecialchars($action['error'], ENT_QUOTES, 'UTF-8'); ?></span><?php } ?></div>
										<?php } elseif (isset($action['email'])) { ?>
											<div class="mb-1"><i class="fa fa-envelope"></i> <?php echo htmlspecialchars($action['email'], ENT_QUOTES, 'UTF-8'); ?>: <?php echo htmlspecialchars($action['subject'], ENT_QUOTES, 'UTF-8'); ?><?php if (!empty($action['html'])) { ?> <span class="badge bg-info">HTML</span><?php } ?>
											<?php if (isset($action['error'])) { ?><span class="badge bg-danger"><?php echo htmlspecialchars($action['error'], ENT_QUOTES, 'UTF-8'); ?></span><?php } else { ?><span class="badge bg-success"><?php echo $text_email_sent; ?></span><?php } ?></div>
										<?php } elseif (isset($action['note'])) { ?>
											<div class="mb-1 text-muted"><?php echo htmlspecialchars($action['note'], ENT_QUOTES, 'UTF-8'); ?></div>
										<?php } ?>
									<?php } ?>
								<?php } else { ?>
									<span class="text-muted"><?php echo $text_no_changes; ?></span>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				<?php } else { ?>
					<tr><td colspan="7" class="text-center"><?php echo $text_no_runs; ?></td></tr>
				<?php } ?>
			</tbody>
		</table>
		</form>
	</div>
</div>
<?php echo $footer; ?>
