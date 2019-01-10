<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-tablet"></i><?php echo $heading_title; ?></div>
		<div class="pull-right">
			<button class="btn btn-success btn-spacer" onClick="validate();"><i class="fa fa-files-o"></i><span class="hidden-xs"> <?php echo $button_copy; ?></span></button>
			<a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
			<button type="submit" form="form" formaction="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
		</div>
	</div>
	<div class="panel-body">
		<form action="<?php echo $copy; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
						<th class="text-center"><?php echo $column_image; ?></th>
						<th><a href="<?php echo $sort_name; ?>"><?php echo $column_name; echo ($sort == 'pd.name') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_model; ?>"><?php echo $column_model; echo ($sort == 'p.model') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_price; ?>"><?php echo $column_price; echo ($sort == 'p.price') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="text-right hidden-xs"><a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; echo ($sort == 'p.quantity') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th class="hidden-xs"><a href="<?php echo $sort_status; ?>"><?php echo $column_status; echo ($sort == 'p.status') ? '<i class="caret caret-' . strtolower($order) . '"></i>' : ''; ?></a></th>
						<th><span class="hidden-xs"><?php echo $column_action; ?></span></th>
					</tr>
				</thead>
				<tbody data-link="row" class="rowlink">
					<tr id="filter" class="info">
						<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=catalog/product&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
						<td></td>
						<td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" data-target="name" data-url="catalog/product" class="form-control"></td>
						<td class="hidden-xs"><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" class="form-control" data-target="model" data-url="catalog/product" class="form-control"></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" class="form-control"></td>
						<td class="text-right hidden-xs"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" class="form-control text-right"></td>
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
						<td><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
					</tr>
					<?php if ($products) { ?>
					<?php foreach ($products as $product) { ?>
					<tr>
						<td class="rowlink-skip text-center"><?php if ($product['selected']) { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="">
							<?php } else { ?>
							<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>">
							<?php } ?></td>
						<td class="text-center"><img class="img-thumbnail" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
						<td><?php echo $product['name']; ?></td>
						<td class="hidden-xs"><?php echo $product['model']; ?></td>
						<td class="text-right hidden-xs"><?php if ($product['special']) { ?>
								<s class="text-danger"><?php echo $product['price']; ?></s> <?php echo $product['special']; ?>
							<?php } else { ?>
								<?php echo $product['price']; ?>
							<?php } ?></td>
						<td class="text-right hidden-xs"><?php if ($product['quantity'] <= 0) { ?>
							<b class="text-danger"><?php echo $product['quantity']; ?></b>
							<?php } elseif ($product['quantity'] <= 5) { ?>
							<b class="text-warning"><?php echo $product['quantity']; ?></b>
							<?php } else { ?>
							<span class="text-success"><?php echo $product['quantity']; ?></span>
							<?php } ?></td>
						<td class="hidden-xs text-<?php echo strtolower($product['status']); ?>"><?php echo $product['status']; ?></td>
						<td><?php foreach ($product['action'] as $action) { ?>
							<a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i><span class="hidden-xs"> <?php echo $action['text']; ?></span></a>
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
		</form>
		<div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
	</div>
</div>
<script>
	function validate() {
		if (!$('input[type="checkbox"]').is(':checked')) {
			alert('Select a product.');
		} else {
			$('#form').submit();
		}
	}
</script>
<?php echo $footer; ?>
