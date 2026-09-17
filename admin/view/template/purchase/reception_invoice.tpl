<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<base href="<?php echo $base; ?>">
<link href="view/stylesheet/main.css" rel="stylesheet">
<script src="view\javascript\jquery\jquery-3.7.1.min.js"></script>
<script src="view\javascript\bootstrap\js\bootstrap.js"></script>
<style>
@media print {
	@page { size: auto; margin: 0; }
	body, .container { min-width: 0 !important; width: auto !important; }
	body { margin: 15mm 10mm; padding-top: 0 !important; }
}
</style>
</head>
<body style="padding-top:0;">
<div class="container">
<?php foreach ($receptions as $reception) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $reception['supplier_company']; ?>" />
			<span class="title"><?php echo $text_reception; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><b><?php echo $reception['supplier_company']; ?></b><br>
				<?php echo $reception['supplier_address']; ?><?php echo $reception['supplier_address'] ? '<br>' : ''; ?>
				<?php if ($reception['supplier_tax_id']) { ?>
				<?php echo $text_tax_id; ?> <?php echo $reception['supplier_tax_id']; ?><br>
				<?php } ?>
				<?php if ($reception['supplier_telephone']) { ?>
				<?php echo $text_telephone; ?> <?php echo $reception['supplier_telephone']; ?><br>
				<?php } ?>
				<?php echo $reception['supplier_email']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $reception['date_added']; ?><br>
				<b><?php echo $text_reception_id; ?></b> #<?php echo $reception['reception_id']; ?><br>
				<?php if ($reception['supplier_delivery_no']) { ?>
				<b>Albarán Proveedor:</b> <?php echo $reception['supplier_delivery_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_payment_method; ?></b> <?php echo $reception['payment_method']; ?><br>
				<?php if ($reception['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $reception['shipping_method']; ?><br>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $text_to; ?></th>
		</tr>
		<tr>
			<td><b><?php echo $reception['store_name']; ?></b><br>
				<?php echo $reception['store_address']; ?><br>
				<?php echo $text_tax_id; ?> <?php echo $reception['store_nif']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $reception['store_telephone']; ?><br>
				<?php if ($reception['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $reception['store_fax']; ?><br>
				<?php } ?>
				<?php echo $reception['store_email']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th class="text-right"><?php echo $column_quantity; ?></th>
			<th class="text-right"><?php echo $column_price; ?></th>
			<th class="text-right"><?php echo $column_discount; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($reception['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?></td>
			<td class="text-right"><?php echo $product['quantity']; ?></td>
			<td class="text-right"><?php echo $product['price']; ?></td>
			<td class="text-right"><?php echo $product['discount']; ?></td>
			<td class="text-right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($reception['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php } ?>
</div>
</body>
</html>
<?php if (!isset($auto_print) || $auto_print) { ?>
<script type="text/javascript"><!--
window.print()
//--></script>
<?php } ?>
