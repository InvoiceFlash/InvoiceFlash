<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-bar-chart-o"></i><?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<div id="filter" class="well">
			<div class="row">
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start ?>" placeholder="<?php echo $entry_date_start?>">
						<div class="input-group-append"><span class="input-group-text"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end ?>" placeholder="<?php echo $entry_date_end?>">
						<div class="input-group-append"><span class="input-group-text"><button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button></span>
					</div>
				</div>
				<div class="col-sm-2">
					<select name="filter_group" title="<?php echo $entry_group; ?>" class="form-control">
						<?php foreach ($groups as $groups) { ?>
						<?php if ($groups['value'] == $filter_group) { ?>
						<option value="<?php echo $groups['value']; ?>" selected=""><?php echo $groups['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2">
					<select name="filter_order_status_id" title="<?php echo $entry_status; ?>" class="form-control">
						<option value="0"><?php echo $text_all_status; ?></option>
						<?php foreach ($order_statuses as $order_status) { ?>
						<?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>" selected=""><?php echo $order_status['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
				<div class="col-sm-2 text-right">
					<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th><?php echo $column_date_start; ?></th>
					<th><?php echo $column_date_end; ?></th>
					<th><?php echo $column_title; ?></th>
					<th class="hidden-xs right"><?php echo $column_orders; ?></th>
					<th class="text-right"><?php echo $column_total; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($orders) { ?>
				<?php foreach ($orders as $order) { ?>
				<tr>
					<td><?php echo $order['date_start']; ?></td>
					<td><?php echo $order['date_end']; ?></td>
					<td><?php echo $order['title']; ?></td>
					<td class="hidden-xs right"><?php echo $order['orders']; ?></td>
					<td class="text-right"><?php echo $order['total']; ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</div>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>