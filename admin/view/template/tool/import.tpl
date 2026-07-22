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
			<div class="form-group row align-items-center">
				<label class="col-sm-2 col-form-label"><?php echo $entry_type; ?></label>
				<div class="col-sm-10">
					<select name="type" id="import-type" class="form-control">
						<option value="product"><?php echo $text_type_product; ?></option>
						<option value="customer"><?php echo $text_type_customer; ?></option>
						<option value="supplier"><?php echo $text_type_supplier; ?></option>
						<option value="saconta"><?php echo $text_type_saconta; ?></option>
					</select>
				</div>
			</div>
			<div class="form-group row align-items-center" id="import-row-file">
				<label class="col-sm-2 col-form-label"><?php echo $entry_file; ?></label>
				<div class="col-sm-10">
					<div class="custom-file">
						<input type="file" class="custom-file-input" name="file" accept=".xlsx">
						<label class="custom-file-label">Browse....</label>
					</div>
				</div>
			</div>
			<div class="form-group row align-items-center" id="import-row-path" style="display:none;">
				<label class="col-sm-2 col-form-label"><?php echo $entry_path; ?></label>
				<div class="col-sm-10">
					<input type="text" name="path" class="form-control" placeholder="F:\proyectos\SaConta\SaConta.1.5.9.6\Servidor\DATO\027">
					<div class="help-block"><?php echo $text_saconta_help; ?></div>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-10 offset-sm-2">
					<button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> <?php echo $button_import; ?></button>
				</div>
			</div>
		</form>
		<hr id="import-examples-divider">
		<div id="import-examples-heading">
			<h4><?php echo $text_example; ?></h4>
			<p class="text-muted"><?php echo $text_example_help; ?></p>
		</div>
		<div id="import-example-product" class="table-responsive">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td><?php echo $column_code; ?></td>
						<td><?php echo $column_description; ?></td>
						<td><?php echo $column_price; ?></td>
						<td><?php echo $column_quantity; ?></td>
						<td><?php echo $column_status; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>F001A</td>
						<td>Descripción del producto de ejemplo</td>
						<td>19.99</td>
						<td>100</td>
						<td>1</td>
					</tr>
					<tr>
						<td>F002XY</td>
						<td>Otra descripción de ejemplo</td>
						<td>9.50</td>
						<td>25</td>
						<td>1</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="import-example-customer" class="table-responsive" style="display:none;">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td><?php echo $column_company; ?></td>
						<td><?php echo $column_nif; ?></td>
						<td><?php echo $column_email; ?></td>
						<td><?php echo $column_telephone; ?></td>
						<td><?php echo $column_address; ?></td>
						<td><?php echo $column_city; ?></td>
						<td><?php echo $column_postcode; ?></td>
						<td><?php echo $column_country; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Empresa de Ejemplo SL</td>
						<td>B12345678</td>
						<td>contacto@ejemplo.com</td>
						<td>6754544321</td>
						<td>Calle Mayor 1</td>
						<td>Madrid</td>
						<td>28001</td>
						<td>España</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="import-example-supplier" class="table-responsive" style="display:none;">
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<td><?php echo $column_company; ?></td>
						<td><?php echo $column_nif; ?></td>
						<td><?php echo $column_email; ?></td>
						<td><?php echo $column_telephone; ?></td>
						<td><?php echo $column_address; ?></td>
						<td><?php echo $column_city; ?></td>
						<td><?php echo $column_postcode; ?></td>
						<td><?php echo $column_country; ?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Proveedor de Ejemplo SL</td>
						<td>B87654321</td>
						<td>contacto@proveedor.com</td>
						<td>6754544321</td>
						<td>Avenida Industrial 5</td>
						<td>Barcelona</td>
						<td>08001</td>
						<td>España</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$('#import-type').on('change', function() {
	var type = $(this).val();
	var isSaconta = (type == 'saconta');

	$('#import-row-file').toggle(!isSaconta);
	$('#import-row-path').toggle(isSaconta);

	$('#import-examples-divider, #import-examples-heading').toggle(!isSaconta);
	$('#import-example-product').toggle(!isSaconta && type == 'product');
	$('#import-example-customer').toggle(!isSaconta && type == 'customer');
	$('#import-example-supplier').toggle(!isSaconta && type == 'supplier');
});
</script>
<?php echo $footer; ?>
