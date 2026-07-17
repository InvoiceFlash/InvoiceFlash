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
					<select name="type" id="import-type" class="form-control">
						<option value="product"><?php echo $text_type_product; ?></option>
						<option value="customer"><?php echo $text_type_customer; ?></option>
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
					<a href="<?php echo $template_product; ?>" id="import-template-product" class="btn btn-default"><i class="fas fa-download"></i> <?php echo $button_template; ?></a>
					<a href="<?php echo $template_customer; ?>" id="import-template-customer" class="btn btn-default" style="display:none;"><i class="fas fa-download"></i> <?php echo $button_template; ?></a>
				</div>
			</div>
		</form>
		<hr>
		<h4><?php echo $text_example; ?></h4>
		<p class="text-muted"><?php echo $text_example_help; ?></p>
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
						<td>REF-001</td>
						<td>Descripción del producto de ejemplo</td>
						<td>19.99</td>
						<td>100</td>
						<td>1</td>
					</tr>
					<tr>
						<td>REF-002</td>
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
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Empresa de Ejemplo SL</td>
						<td>B12345678</td>
						<td>contacto@ejemplo.com</td>
						<td>600000000</td>
						<td>Calle Mayor 1</td>
						<td>Madrid</td>
						<td>28001</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$('#import-type').on('change', function() {
	var type = $(this).val();
	$('#import-example-product, #import-template-product').toggle(type == 'product');
	$('#import-example-customer, #import-template-customer').toggle(type == 'customer');
});
</script>
<?php echo $footer; ?>
