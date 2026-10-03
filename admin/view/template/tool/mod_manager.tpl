<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<div class="card-header clearfix">
		<div class="h2"><i class="fa fa-puzzle-piece"></i><?php echo $heading_title; ?></div>
	</div>
	<div class="card-body">
		<table class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th><?php echo $column_name; ?></th>
					<th class="text-end"><span class="d-none d-lg-inline"><?php echo $column_action; ?></span></th>
				</tr>
			</thead>
			<tbody data-link="row" class="rowlink">
				<?php if ($extensions) { ?>
				<?php foreach ($extensions as $extension) { ?>
				<tr>
					<td><?php echo $extension['name']; ?></td>
					<td class="text-end"><?php foreach ($extension['action'] as $action) { ?>
						<span class="bracket"><a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a></span>
					<?php } ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $footer; ?>