<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_code; ?>"><?php echo $column_code; echo ($sort == 'code') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><a href="<?php echo $sort_title; ?>"><?php echo $column_title; echo ($sort == 'title') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_debit; ?>"><?php echo $column_debit; echo ($sort == 'debit') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_credit; ?>"><?php echo $column_credit; echo ($sort == 'credit') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_balance; ?>"><?php echo $column_balance; echo ($sort == 'balance') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=accounting/subaccount&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td><input type="text" name="filter_code" value="<?php echo $filter_code; ?>" class="form-control"></td>
						<td><input type="text" name="filter_title" value="<?php echo $filter_title; ?>" class="form-control"></td>
						<td class="hidden-xs"></td>
						<td class="hidden-xs"></td>
						<td class="hidden-xs"></td>
						<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($subaccounts) { ?>
					<?php foreach ($subaccounts as $subaccount) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($subaccount['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $subaccount['ctab61_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $subaccount['ctab61_id']; ?>">
							<?php } ?></td>
						<td><?php echo $subaccount['code']; ?></td>
						<td><?php echo $subaccount['title']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['debit']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['credit']; ?></td>
						<td class="text-right hidden-xs"><?php echo $subaccount['balance']; ?></td>
						<td class="text-right"><?php foreach ($subaccount['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>
