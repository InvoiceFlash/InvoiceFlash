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
<?php foreach ($quotes as $quotes) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $quotes['store_name']; ?>" /><br>
			<span class="title"><?php echo $text_quote; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><strong><?php echo $quotes['store_name']; ?></strong><br>
				<?php echo $quotes['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $quotes['store_telephone']; ?><br>
				<?php if ($quotes['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $quotes['store_fax']; ?><br>
				<?php } ?>
				<?php echo $quotes['store_email']; ?><br>
				<?php echo $quotes['store_url']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $quotes['date_added']; ?><br>
				<?php if ($quotes['invoice_no']) { ?>
					<b><?php echo $text_order_no; ?></b> <?php echo $quotes['invoice_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_quote_id; ?></b> <?php echo $quotes['quote_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b> <?php echo $quotes['payment_method']; ?><br>
				<?php if ($quotes['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $quotes['shipping_method']; ?><br>
				<?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th width="50%"><?php echo $text_to; ?></th>
			<th><?php echo $text_ship_to; ?></th>
		</tr>
		<tr>
			<td><?php echo $quotes['payment_address']; ?><br/>
				<?php echo $quotes['email']; ?><br/>
				<?php echo $quotes['telephone']; ?>
				<?php if ($quotes['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $quotes['payment_company_id']; ?>
				<?php } ?>
				<?php if ($quotes['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $quotes['payment_tax_id']; ?>
				<?php } ?></td>
			<td><?php echo $quotes['shipping_address']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th><?php echo $column_model; ?></th>
			<th class="center"><?php echo $column_quantity; ?></th>
			<th class="right"><?php echo $column_price; ?></th>
			<th class="right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($quotes['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
			<td><?php echo $product['model']; ?></td>
			<td class="center"><?php echo $product['quantity']; ?></td>
			<td class="right"><?php echo $product['price']; ?></td>
			<td class="right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($quotes['total'] as $total) { ?>
		<tr>
			<td class="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php if ($quotes['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $quotes['comment']; ?></td>
		</tr>
	</table>
	<?php } ?>
<?php } ?>
</div>
</body>
</html>