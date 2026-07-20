<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-history hidden-xs"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash"></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="form-inline" method="post" enctype="multipart/form-data" id="form">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th width="30" class="text-center"><input type="checkbox" data-toggle="selected"></th>
					<th><?php echo $column_date_from; ?></th>
					<th><?php echo $column_date_to; ?></th>
					<th><?php echo $column_username; ?></th>
					<th><?php echo $column_action; ?></th>
					<th><?php echo $column_document; ?></th>
					<th><?php echo $column_reference; ?></th>
					<th class="text-center"><?php echo $column_changes; ?></th>
					<th class="text-right"><?php echo $column_ip; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr id="filter" class="info">
					<td class="text-center">
						<a class="btn btn-default btn-block" href="index.php?route=tool/user_logs&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a>
					</td>
					<td>
						<div class="input-group">
							<input type="text" name="filter_date_from" value="<?php echo $filter_date_from; ?>" class="form-control date" placeholder="DD-MM-YYYY">
							<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
						</div>
					</td>
					<td>
						<div class="input-group">
							<input type="text" name="filter_date_to" value="<?php echo $filter_date_to; ?>" class="form-control date" placeholder="DD-MM-YYYY">
							<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
						</div>
					</td>
					<td>
						<select name="filter_username" class="form-control">
							<option value="">&ndash;</option>
							<?php foreach ($users as $u) { ?>
							<option value="<?php echo $u['username']; ?>"<?php echo ($filter_username == $u['username']) ? ' selected' : ''; ?>><?php echo $u['username']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select name="filter_action" class="form-control">
							<option value="">&ndash;</option>
							<option value="login"  <?php echo ($filter_action == 'login')  ? 'selected' : ''; ?>><?php echo $text_login; ?></option>
							<option value="create" <?php echo ($filter_action == 'create') ? 'selected' : ''; ?>><?php echo $text_create; ?></option>
							<option value="edit"   <?php echo ($filter_action == 'edit')   ? 'selected' : ''; ?>><?php echo $text_edit; ?></option>
							<option value="delete" <?php echo ($filter_action == 'delete') ? 'selected' : ''; ?>><?php echo $text_delete; ?></option>
						</select>
					</td>
					<td></td>
					<td>
						<input type="text" name="filter_reference" value="<?php echo $filter_reference; ?>" class="form-control" placeholder="<?php echo $filter_reference_placeholder; ?>">
					</td>
					<td class="text-center"></td>
					<td class="text-right">
						<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button>
					</td>
				</tr>
				<?php if ($logs) { ?>
				<?php foreach ($logs as $log) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $log['log_id']; ?>"></td>
					<td class="text-nowrap"><?php echo $log['date_added']; ?></td>
					<td></td>
					<td><?php echo $log['username']; ?></td>
					<td>
						<?php if ($log['action_raw'] == 'login') { ?>
						<span class="label label-info"><?php echo $log['action']; ?></span>
						<?php } elseif ($log['action_raw'] == 'create') { ?>
						<span class="label label-success"><?php echo $log['action']; ?></span>
						<?php } elseif ($log['action_raw'] == 'delete') { ?>
						<span class="label label-danger"><?php echo $log['action']; ?></span>
						<?php } else { ?>
						<span class="label label-warning"><?php echo $log['action']; ?></span>
						<?php } ?>
					</td>
					<td><?php echo $log['document']; ?></td>
					<td>
						<?php if ($log['href'] && $log['reference']) { ?>
						<a href="<?php echo $log['href']; ?>"><?php echo $log['reference']; ?></a>
						<?php } else { ?>
						<?php echo $log['reference']; ?>
						<?php } ?>
					</td>
					<td class="text-center">
						<?php if ($log['changes']) { ?>
						<button type="button" class="btn btn-default btn-sm" data-changes="<?php echo htmlspecialchars(json_encode($log['changes']), ENT_QUOTES, 'UTF-8'); ?>" onclick="userLogShowChanges(this);"><i class="fa fa-eye"></i> <?php echo $button_view_changes; ?></button>
						<?php } else { ?>
						<?php echo $text_no_changes; ?>
						<?php } ?>
					</td>
					<td class="text-right"><?php echo $log['ip']; ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</form>
		<div class="pagination"><?php echo str_replace('....', '', $pagination); ?></div>
	</div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="UserLogChangesModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo $text_changes_title; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th><?php echo $text_field; ?></th>
							<th><?php echo $text_original; ?></th>
							<th><?php echo $text_changed; ?></th>
						</tr>
					</thead>
					<tbody id="user-log-changes-body"></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
function userLogShowChanges(button) {
	var changes = JSON.parse(button.getAttribute('data-changes'));
	var tbody = document.getElementById('user-log-changes-body');
	tbody.innerHTML = '';

	for (var i = 0; i < changes.length; i++) {
		var tr = document.createElement('tr');
		['field', 'original', 'changed'].forEach(function(key) {
			var td = document.createElement('td');
			td.textContent = changes[i][key];
			tr.appendChild(td);
		});
		tbody.appendChild(tr);
	}

	bootstrap.Modal.getOrCreateInstance(document.getElementById('UserLogChangesModal')).show();
}
</script>
<?php echo $footer; ?>
