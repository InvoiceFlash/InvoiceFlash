<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'landmark'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_title; ?>"><?php echo $column_title; echo ($sort == 'title') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($tax_classes) { ?>
					<?php foreach ($tax_classes as $tax_class) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($tax_class['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $tax_class['tax_class_id']; ?>">
							<?php } ?></td>
						<td><?php echo $tax_class['title']; ?></td>
						<td class="text-right"><?php foreach ($tax_class['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><?php echo $action['icon']; ?> <?php echo $action['text']; ?></a>
							<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>