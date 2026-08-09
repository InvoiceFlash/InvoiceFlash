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
<?php foreach ($purchase_orders as $purchase_order) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $purchase_order['supplier_company']; ?>" width="242" /><br>
			<span class="title"><?php echo $text_purchase_order; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><strong><?php echo $purchase_order['supplier_company']; ?></strong><br>
				<?php echo $purchase_order['supplier_address']; ?><?php echo $purchase_order['supplier_address'] ? '<br>' : ''; ?>
				<?php if ($purchase_order['supplier_tax_id']) { ?>
				<?php echo $text_tax_id; ?> <?php echo $purchase_order['supplier_tax_id']; ?><br>
				<?php } ?>
				<?php if ($purchase_order['supplier_telephone']) { ?>
				<?php echo $text_telephone; ?> <?php echo $purchase_order['supplier_telephone']; ?><br>
				<?php } ?>
				<?php echo $purchase_order['supplier_email']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $purchase_order['date_added']; ?><br>
				<b><?php echo $text_purchase_order_id; ?></b> <?php echo $purchase_order['po_number']; ?><br>
				<b><?php echo $text_payment_method; ?></b>&nbsp;&nbsp;<?php echo $purchase_order['payment_method']; ?><br>
				<?php if ($purchase_order['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b>&nbsp;&nbsp;<?php echo $purchase_order['shipping_method']; ?><br>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $text_to; ?></th>
		</tr>
		<tr>
			<td><strong><?php echo $purchase_order['store_name']; ?></strong><br>
				<?php echo $purchase_order['store_address']; ?><br>
				<?php echo $text_tax_id; ?> <?php echo $purchase_order['store_nif']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $purchase_order['store_telephone']; ?><br>
				<?php if ($purchase_order['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $purchase_order['store_fax']; ?><br>
				<?php } ?>
				<?php echo $purchase_order['store_email']; ?></td>
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
		<?php foreach ($purchase_order['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?></td>
			<td class="right"><?php echo $product['quantity']; ?></td>
			<td class="right"><?php echo $product['price']; ?></td>
			<td class="right"><?php echo $product['discount']; ?></td>
			<td class="right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($purchase_order['total'] as $total) { ?>
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
