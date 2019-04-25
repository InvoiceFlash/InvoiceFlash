<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<base href="<?php echo $base; ?>">
<link href="view/stylesheet/main.css" rel="stylesheet">
<script src="view\javascript\jquery\jquery-3.3.1.min.js"></script>
<script src="view\javascript\bootstrap\js\bootstrap.js"></script>
</head>
<body style="padding-top:0;">
<div class="container">
<?php foreach ($deliveries as $delivery) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $delivery['store_name']; ?>" />
			<span class="title"><?php echo $text_delivery; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><?php echo $delivery['store_name']; ?><br>
				<?php echo $delivery['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $delivery['store_telephone']; ?><br>
				<?php if ($delivery['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $delivery['store_fax']; ?><br>
				<?php } ?>
				<?php echo $delivery['store_email']; ?><br>
				<?php echo $delivery['store_url']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $delivery['date_added']; ?><br>
				<?php if ($delivery['invoice_no']) { ?>
					<b><?php echo $text_invoice_no; ?></b> <?php echo $delivery['invoice_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_delivery_id; ?></b> <?php echo $delivery['delivery_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b> <?php echo $delivery['payment_method']; ?><br>
				<?php if ($delivery['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $delivery['shipping_method']; ?><br>
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
			<td><?php echo $delivery['payment_address']; ?><br/>
				<?php echo $delivery['email']; ?><br/>
				<?php echo $delivery['telephone']; ?>
				<?php if ($delivery['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $delivery['payment_company_id']; ?>
				<?php } ?>
				<?php if ($delivery['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $delivery['payment_tax_id']; ?>
				<?php } ?></td>
			<td><?php echo $delivery['shipping_address']; ?></td>
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
		<?php foreach ($delivery['product'] as $product) { ?>
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
		<?php foreach ($delivery['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php if ($delivery['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $delivery['comment']; ?></td>
		</tr>
	</table>
	<?php } ?>
<?php } ?>
</div>
</body>
</html>