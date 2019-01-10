<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-sitemap"></i><?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a href="<?php echo $repair; ?>" class="btn btn-success btn-spacer"><i class="fa fa-wrench"></i><span class="hidden-xs">  <?php echo $button_repair; ?></span></a>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form class="form-inline" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><?php echo $column_name; ?></th>
						<th class="text-right hidden-xs"><?php echo $column_sort_order; ?></th>
						<th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($categories) { ?>
					<?php foreach ($categories as $category) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($category['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>">
						<?php } ?></td>
						<td><?php echo $category['name']; ?></td>
						<td class="text-right hidden-xs"><?php echo $category['sort_order']; ?></td>
						<td class="text-right"><?php foreach ($category['action'] as $action) { ?>
							<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
						<?php } ?></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr>
						<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>