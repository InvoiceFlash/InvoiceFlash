<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<base href="<?php echo $base; ?>">
<style>
* {
	padding: 0;
	margin: 0;
}
.center {
	text-align: center;
}
.right {
	text-align: right;
}
.title {
	font-size: 32px;
	text-transform: uppercase;
	padding-left: 20px;
	text-align: right;
}
.table-bordered {
	border: 1px solid grey;
}
.table {
	width: 100%;
	margin-bottom: 1rem;
	background-color: white;
	margin: 5px;
	padding: 5px;
}
th {
    font-weight: bold;
	background-color: #dee2e6;
}
</style>
</head>
<body style="padding-top:0;">
<div class="container">
<?php foreach ($receptions as $reception) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $reception['supplier_company']; ?>" width="242" /><br>
			<span class="title"><?php echo $text_reception; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><strong><?php echo $reception['supplier_company']; ?></strong><br>
				<?php echo $reception['supplier_address']; ?><?php echo $reception['supplier_address'] ? '<br>' : ''; ?>
				<?php if ($reception['supplier_tax_id']) { ?>
				<?php echo $text_tax_id; ?> <?php echo $reception['supplier_tax_id']; ?><br>
				<?php } ?>
				<?php if ($reception['supplier_telephone']) { ?>
				<?php echo $text_telephone; ?> <?php echo $reception['supplier_telephone']; ?><br>
				<?php } ?>
				<?php echo $reception['supplier_email']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b>&nbsp;&nbsp;<?php echo $reception['date_added']; ?><br>
				<b><?php echo $text_reception_id; ?></b>&nbsp;&nbsp;#<?php echo $reception['reception_id']; ?><br>
				<?php if ($reception['supplier_delivery_no']) { ?>
				<b>Alb. Proveedor:</b>&nbsp;&nbsp;<?php echo $reception['supplier_delivery_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_payment_method; ?></b>&nbsp;&nbsp;<?php echo $reception['payment_method']; ?><br>
				<?php if ($reception['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b>&nbsp;&nbsp;<?php echo $reception['shipping_method']; ?><br>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $text_to; ?></th>
		</tr>
		<tr>
			<td><strong><?php echo $reception['store_name']; ?></strong><br>
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
			<th class="right"><?php echo $column_quantity; ?></th>
			<th class="right"><?php echo $column_price; ?></th>
			<th class="right"><?php echo $column_discount; ?></th>
			<th class="right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($reception['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?></td>
			<td class="right"><?php echo $product['quantity']; ?></td>
			<td class="right"><?php echo $product['price']; ?></td>
			<td class="right"><?php echo $product['discount']; ?></td>
			<td class="right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($reception['total'] as $total) { ?>
		<tr>
			<td class="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php } ?>
</div>
</body>
</html>
