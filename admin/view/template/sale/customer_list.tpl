<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="clearfix">
			<div class="pull-left h2"><i class="hidden-xs fa fa-user"></i> <?php echo $heading_title; ?></div>
			<div class="pull-right">
				<button type="submit" form="form" class="btn btn-success btn-spacer"><i class="fa fa-check"></i><span class="hidden-xs"> <?php echo $button_approve; ?></span></button>
				<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
				<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $approve; ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_email; ?>"><?php echo $column_email; echo ($sort == 'c.email') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; echo ($sort == 'customer_group') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'c.status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_approved; ?>"><?php echo $column_approved; echo ($sort == 'c.approved') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<!-- <th width="40" class="visible-lg"><a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; echo ($sort == 'c.ip') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th> -->
						<th class="hidden-xs"><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; echo ($sort == 'c.date_added') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="visible-lg"><?php echo $column_login; ?></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=sale/customer&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control" data-target="name" data-url="sale/customer"></td>
						<td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" class="form-control"></td>
						<td class="hidden-xs"><select name="filter_customer_group_id" class="form-control">
							<option value="*">&ndash;</option>
							<?php foreach ($customer_groups as $customer_group) { ?>
							<?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>" selected=""><?php echo $customer_group['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td class="hidden-xs"><select name="filter_status" class="form-control">
							<option value="*">&ndash;</option>
							<?php if ($filter_status) { ?>
							<option value="1" selected=""><?php echo $text_enabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<?php } ?>
							<?php if (!is_null($filter_status) && !$filter_status) { ?>
							<option value="0" selected=""><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select></td>
						<td class="hidden-xs"><select name="filter_approved" class="form-control">
							<option value="*">&ndash;</option>
							<?php if ($filter_approved) { ?>
							<option value="1" selected=""><?php echo $text_yes; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_yes; ?></option>
							<?php } ?>
							<?php if (!is_null($filter_approved) && !$filter_approved) { ?>
							<option value="0" selected=""><?php echo $text_no; ?></option>
							<?php } else { ?>
							<option value="0"><?php echo $text_no; ?></option>
							<?php } ?>
						</select></td>
						<td class="hidden-xs"><div class="input-group">
							<input type="text" name="filter_date_added" class="form-control date"/>
							<div class="input-group-append">
							<div class="input-group-text"><i class="fas fa-calendar"></i></div>
							</div>
						</div></td>
						<td class="visible-lg"></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($customers) { ?>
					<?php foreach ($customers as $customer) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($customer['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>">
							<?php } ?></td>
						<td><?php echo $customer['name']; ?></td>
						<td><?php echo $customer['email']; ?></td>
						<td class="hidden-xs"><?php echo $customer['customer_group']; ?></td>
						<td class="hidden-xs text-<?php echo strtolower($customer['status']); ?>"><?php echo $customer['status']; ?></td>
						<td class="hidden-xs"><?php echo $customer['approved']; ?></td>
						<!-- <td class="visible-lg"><?php echo $customer['ip']; ?></td> -->
						<td class="hidden-xs"><?php echo $customer['date_added']; ?></td>
						<td class="rowlink-skip text-center visible-lg"><select class="form-control" onchange="((this.value!=='') ? window.open('index.php?route=sale/customer/login&token=<?php echo $token; ?>&customer_id=<?php echo $customer['customer_id']; ?>&store_id='+this.value) : null); this.value = '';">
							<option value=""><?php echo $text_select; ?></option>
							<option value="0"><?php echo $text_default; ?></option>
							<?php foreach ($stores as $store) { ?>
							<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
							<?php } ?>
						</select></td>
						<td class="text-right"><?php foreach ($customer['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?> 