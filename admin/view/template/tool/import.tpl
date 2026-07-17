<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<?php if (!empty($import_errors)) { ?>
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>
	<ul class="mb-0">
		<?php foreach ($import_errors as $import_error) { ?>
		<li><?php echo $import_error; ?></li>
		<?php } ?>
	</ul>
</div>
<?php } ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fas fa-file-import"></i> <?php echo $heading_title; ?></div>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-import">
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"><?php echo $entry_type; ?></label>
				<div class="col-sm-10">
					<select name="type" class="form-control">
						<option value="product"><?php echo $text_type_product; ?></option>
						<option value="customer" disabled><?php echo $text_type_customer; ?></option>
						<option value="supplier" disabled><?php echo $text_type_supplier; ?></option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"><?php echo $entry_file; ?></label>
				<div class="custom-file col-sm-10">
					<input type="file" class="custom-file-input from-control" name="file" accept=".xlsx">
					<label class="custom-file-label">Browse....</label>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> <?php echo $button_import; ?></button>
					<a href="<?php echo $template; ?>" class="btn btn-default"><i class="fas fa-download"></i> <?php echo $button_template; ?></a>
				</div>
			</div>
		</form>
		<hr>
		<h4><?php echo $text_example; ?></h4>
		<p class="text-muted"><?php echo $text_example_help; ?></p>
		<div class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td><?php echo $column_model; ?></td>
						<td><?php echo $column_name; ?></td>
						<td><?php echo $column_description; ?></td>
						<td><?php echo $column_price; ?></td>
						<td><?php echo $column_quantity; ?></td>
						<td><?php echo $column_status; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>REF-001</td>
						<td>Producto de ejemplo</td>
						<td>Descripción del producto</td>
						<td>19.99</td>
						<td>100</td>
						<td>1</td>
					</tr>
					<tr>
						<td>REF-002</td>
						<td>Otro producto</td>
						<td>Otra descripción</td>
						<td>9.50</td>
						<td>25</td>
						<td>1</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $footer; ?>
