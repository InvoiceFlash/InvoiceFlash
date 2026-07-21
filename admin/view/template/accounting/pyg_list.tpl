<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'balance-scale'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><?php echo $error_warning; ?></div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><?php echo $success; ?></div>
		<?php } ?>
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><?php echo $column_code; ?></th>
						<th><?php echo $column_name; ?></th>
						<th class="text-right hidden-xs"><?php echo $column_order_code; ?></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($accounts) { ?>
					<?php foreach ($accounts as $account) { ?>
					<tr>
						<td class="text-center"><?php if ($account['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $account['ctab9_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $account['ctab9_id']; ?>">
							<?php } ?></td>
						<td><?php echo $account['code']; ?></td>
						<td><?php echo $account['name']; ?></td>
						<td class="text-right hidden-xs"><?php echo $account['order_code']; ?></td>
						<td class="text-right"><?php foreach ($account['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
</div>
<?php echo $footer; ?>
