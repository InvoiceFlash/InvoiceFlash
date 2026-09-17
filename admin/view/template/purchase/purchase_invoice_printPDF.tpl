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
<?php foreach ($invoices as $invoice) { ?>
	<?php if (!empty($header_html)) { ?>
	<div class="invoice-custom-header"><?php echo $header_html; ?></div>
	<?php } ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $invoice['payment_company']; ?>" width="242" /><br>
			<span class="title"><?php echo $text_invoice; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><strong><?php echo $invoice['payment_company']; ?></strong><br>
				<?php echo $invoice['payment_address']; ?><br>
				<?php if ($invoice['payment_tax_id']) { ?>
				<?php echo $text_tax_id; ?> <?php echo $invoice['payment_tax_id']; ?><br>
				<?php } ?>
				<?php if ($invoice['telephone']) { ?>
				<?php echo $text_telephone; ?> <?php echo $invoice['telephone']; ?><br>
				<?php } ?>
				<?php echo $invoice['email']; ?>
				<?php if ($invoice['payment_company_id']) { ?>
				<br>
				<?php echo $text_company_id; ?> <?php echo $invoice['payment_company_id']; ?>
				<?php } ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $invoice['date_added']; ?><br>
				<b><?php echo $text_invoice_id; ?></b> <?php echo $invoice['invoice_prefix'] . $invoice['invoice_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b>&nbsp;&nbsp;<?php echo $invoice['payment_method']; ?><br>
				<?php if ($invoice['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b>&nbsp;&nbsp;<?php echo $invoice['shipping_method']; ?><br>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $text_to; ?></th>
		</tr>
		<tr>
			<td><strong><?php echo $invoice['store_name']; ?></strong><br>
				<?php echo $invoice['store_address']; ?><br>
				<?php if ($invoice['store_locality']) { ?>
				<?php echo $invoice['store_locality']; ?><br>
				<?php } ?>
				<?php echo $text_tax_id; ?> <?php echo $invoice['store_nif']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $invoice['store_telephone']; ?><br>
				<?php if ($invoice['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $invoice['store_fax']; ?><br>
				<?php } ?>
				<?php echo $invoice['store_email']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th><?php echo $column_model; ?></th>
			<th class="right"><?php echo $column_quantity; ?></th>
			<th class="right"><?php echo $column_price; ?></th>
			<th class="right"><?php echo $column_discount; ?></th>
			<th class="right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($invoice['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
			<td><?php echo $product['model']; ?></td>
			<td class="right"><?php echo $product['quantity']; ?></td>
			<td class="right"><?php echo $product['price']; ?></td>
			<td class="right"><?php echo $product['discount']; ?></td>
			<td class="right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($invoice['total'] as $total) { ?>
		<tr>
			<td class="right" colspan="5"><b><?php echo $total['title']; ?>:</b></td>
			<td class="right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php if ($invoice['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $invoice['comment']; ?></td>
		</tr>
	</table>
	<?php } ?>
	<?php if (!empty($footer_html)) { ?>
	<div class="invoice-custom-footer"><?php echo $footer_html; ?></div>
	<?php } ?>
<?php } ?>
</div>
</body>
</html>
