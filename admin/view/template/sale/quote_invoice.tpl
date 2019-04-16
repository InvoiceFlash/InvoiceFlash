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
<?php foreach ($quotes as $quote) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $quote['store_name']; ?>" />
			<span class="title"><?php echo $text_quote; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><?php echo $quote['store_name']; ?><br>
				<?php echo $quote['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $quote['store_telephone']; ?><br>
				<?php if ($quote['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $quote['store_fax']; ?><br>
				<?php } ?>
				<?php echo $quote['store_email']; ?><br>
				<?php echo $quote['store_url']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $quote['date_added']; ?><br>
				<?php if ($quote['invoice_no']) { ?>
					<b><?php echo $text_invoice_no; ?></b> <?php echo $quote['invoice_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_quote_id; ?></b> <?php echo $quote['quote_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b> <?php echo $quote['payment_method']; ?><br>
				<?php if ($quote['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $quote['shipping_method']; ?><br>
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
			<td><?php echo $quote['payment_address']; ?><br/>
				<?php echo $quote['email']; ?><br/>
				<?php echo $quote['telephone']; ?>
				<?php if ($quote['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $quote['payment_company_id']; ?>
				<?php } ?>
				<?php if ($quote['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $quote['payment_tax_id']; ?>
				<?php } ?></td>
			<td><?php echo $quote['shipping_address']; ?></td>
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
		<?php foreach ($quote['product'] as $product) { ?>
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
		<?php foreach ($quote['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php if ($quote['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $quote['comment']; ?></td>
		</tr>
	</table>
	<?php } ?>
<?php } ?>
</div>
</body>
</html>