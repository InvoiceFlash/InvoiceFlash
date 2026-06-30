<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="fa fa-history hidden-xs"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th width="30"></th>
					<th><?php echo $column_date_from; ?></th>
					<th><?php echo $column_date_to; ?></th>
					<th><?php echo $column_username; ?></th>
					<th><?php echo $column_action; ?></th>
					<th><?php echo $column_document; ?></th>
					<th><?php echo $column_reference; ?></th>
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
						</select>
					</td>
					<td></td>
					<td></td>
					<td class="text-right">
						<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button>
					</td>
				</tr>
				<?php if ($logs) { ?>
				<?php foreach ($logs as $log) { ?>
				<tr>
					<td></td>
					<td class="text-nowrap" colspan="2"><?php echo $log['date_added']; ?></td>
					<td><?php echo $log['username']; ?></td>
					<td>
						<?php if ($log['action_raw'] == 'login') { ?>
						<span class="label label-info"><?php echo $log['action']; ?></span>
						<?php } elseif ($log['action_raw'] == 'create') { ?>
						<span class="label label-success"><?php echo $log['action']; ?></span>
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
					<td class="text-right"><?php echo $log['ip']; ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo str_replace('....', '', $pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>
