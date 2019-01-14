<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<base href="<?php echo $base; ?>">
<link href="view/stylesheet/stylesheet.css" rel="stylesheet">
<link href="view/stylesheet/main.css" rel="stylesheet">
<script src="view\javascript\jquery\jquery-3.3.1.min.js"></script>
<script src="view\javascript\bootstrap\js\bootstrap.js"></script>
</head>
<body style="padding-top:0;">
<div class="container">
<!-- <?php foreach ($orders as $orders) { ?> -->
	<h1><?php echo $text_invoice; ?></h1>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><?php echo $orders['store_name']; ?><br>
				<?php echo $orders['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $orders['store_telephone']; ?><br>
				<?php if ($orders['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $orders['store_fax']; ?><br>
				<?php } ?>
				<?php echo $orders['store_email']; ?><br>
				<?php echo $orders['store_url']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $orders['date_added']; ?><br>
				<?php if ($orders['invoice_no']) { ?>
					<b><?php echo $text_invoice_no; ?></b> <?php echo $orders['invoice_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_order_id; ?></b> <?php echo $orders['invoice_prefix'] . $orders['order_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b> <?php echo $orders['payment_method']; ?><br>
				<?php if ($orders['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $orders['shipping_method']; ?><br>
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
			<td><?php echo $orders['payment_address']; ?><br/>
				<?php echo $orders['email']; ?><br/>
				<?php echo $orders['telephone']; ?>
				<?php if ($orders['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $orders['payment_company_id']; ?>
				<?php } ?>
				<?php if ($orders['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $orders['payment_tax_id']; ?>
				<?php } ?></td>
			<td><?php echo $orders['shipping_address']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th><?php echo $column_model; ?></th>
			<th class="text-right"><?php echo $column_quantity; ?></th>
			<th class="text-right"><?php echo $column_price; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($orders['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
			<td><?php echo $product['model']; ?></td>
			<td class="text-right"><?php echo $product['quantity']; ?></td>
			<td class="text-right"><?php echo $product['price']; ?></td>
			<td class="text-right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($orders['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php if ($orders['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $orders['comment']; ?></td>
		</tr>
	</table>
	<?php } ?>
<!-- <?php } ?> -->
</div>
</body>
</html>
<script type="text/javascript"><!--
window.print()
//--></script>