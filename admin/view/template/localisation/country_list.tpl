<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'globe'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="card-body">
		<form class="form-bar" action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="d-none d-lg-table-cell"><a href="<?php echo $sort_iso_code_2; ?>"><?php echo $column_iso_code_2; echo ($sort == 'iso_code_2') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="d-none d-lg-table-cell"><a href="<?php echo $sort_iso_code_3; ?>"><?php echo $column_iso_code_3; echo ($sort == 'iso_code_3') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-end"><span class="d-none d-lg-inline"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<?php if ($countries) { ?>
					<?php foreach ($countries as $country) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($country['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $country['country_id']; ?>">
							<?php } ?></td>
						<td><?php echo $country['name']; ?></td>
						<td class="d-none d-lg-table-cell"><?php echo $country['iso_code_2']; ?></td>
						<td class="d-none d-lg-table-cell"><?php echo $country['iso_code_3']; ?></td>
						<td class="text-end"><?php foreach ($country['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><?php echo $action['icon']; ?><?php echo $action['text']; ?></a>
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
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<?php echo $footer; ?>