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
<!-- <?php foreach ($invoices as $invoice) { ?> -->
	<h1><?php echo $text_invoice; ?></h1>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><?php echo $invoice['store_name']; ?><br>
				<?php echo $invoice['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $invoice['store_telephone']; ?><br>
				<?php if ($invoice['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $invoice['store_fax']; ?><br>
				<?php } ?>
				<?php echo $invoice['store_email']; ?><br>
				<?php echo $invoice['store_url']; ?></td>
			<td>
				<b><?php echo $text_date_added; ?></b> <?php echo $invoice['date_added']; ?><br>
				<?php if ($invoice['invoice_no']) { ?>
					<b><?php echo $text_invoice_no; ?></b> <?php echo $invoice['invoice_no']; ?><br>
				<?php } ?>
				<b><?php echo $text_invoice_id; ?></b> <?php echo $invoice['invoice_id']; ?><br>
				<b><?php echo $text_payment_method; ?></b> <?php echo $invoice['payment_method']; ?><br>
				<?php if ($invoice['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b> <?php echo $invoice['shipping_method']; ?><br>
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
			<td><?php echo $invoice['payment_address']; ?><br/>
				<?php echo $invoice['email']; ?><br/>
				<?php echo $invoice['telephone']; ?>
				<?php if ($invoice['payment_company_id']) { ?>
				<br/>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $invoice['payment_company_id']; ?>
				<?php } ?>
				<?php if ($invoice['payment_tax_id']) { ?>
				<br/>
				<?php echo $text_tax_id; ?> <?php echo $invoice['payment_tax_id']; ?>
				<?php } ?></td>
			<td><?php echo $invoice['shipping_address']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_image; ?></th>
			<th><?php echo $column_product; ?></th>
			<th><?php echo $column_model; ?></th>
			<th class="text-right"><?php echo $column_quantity; ?></th>
			<th class="text-right"><?php echo $column_price; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($invoice['product'] as $product) { ?>
		<tr>
			<td><img src="<?php echo '../image/' . $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
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
		<?php foreach ($invoice['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="	5"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
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
<!-- <?php } ?> -->
</div>
</body>
</html>
<script type="text/javascript"><!--
window.print()
//--></script>