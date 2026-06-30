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
<?php foreach ($invoices as $invoices) { ?>
	<?php if (!empty($header_html)) { ?>
	<div class="invoice-custom-header"><?php echo $header_html; ?></div>
	<?php } ?>
	<table style="width:100%; margin:0; border-collapse:collapse;">
		<tr>
			<td style="width:50%; vertical-align:top; padding:0;">
				<table style="width:100%; border-collapse:collapse;">
					<tr>
						<td style="width:26%; vertical-align:top; padding:0;">
							<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $invoices['store_name']; ?>" style="width:24mm;" />
						</td>
						<td style="width:21%; padding:0;"></td>
						<td style="width:53%; vertical-align:top; padding:0;">
							<strong style="font-size:15px;"><?php echo $invoices['store_name']; ?></strong><br>
							<?php echo $invoices['store_address']; ?><br>
							<?php echo $text_telephone; ?> <?php echo $invoices['store_telephone']; ?><br>
							<?php if ($invoices['store_fax']) { ?>
							<?php echo $text_fax; ?> <?php echo $invoices['store_fax']; ?><br>
							<?php } ?>
							<?php echo $invoices['store_email']; ?><br>
							<?php echo $text_tax_id; ?> <?php echo $invoices['store_nif']; ?>
						</td>
					</tr>
				</table>
			</td>
			<td style="vertical-align:top; padding:0;"></td>
		</tr>
		<tr>
			<td style="vertical-align:top; padding:0;">
				<b>DATE:</b> <?php echo $invoices['date_added']; ?>
			</td>
			<td style="text-align:right; vertical-align:top; padding:0;">
				<span class="title" style="font-size:22px;"><?php echo $text_invoice; ?></span> <?php echo $invoices['invoice_prefix'] . $invoices['invoice_id']; ?>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top; padding:0;"></td>
			<td style="text-align:left; vertical-align:top; padding:0;"><?php if ($invoices['payment_company']) { ?><strong><?php echo $invoices['payment_company']; ?></strong><br/><?php } ?><?php echo $invoices['payment_address']; ?><br/><?php echo $invoices['email']; ?><?php if ($invoices['telephone']) { ?><br/><?php echo $invoices['telephone']; ?><?php } ?><?php if ($invoices['payment_company_id']) { ?><br/><br/><?php echo $text_company_id; ?> <?php echo $invoices['payment_company_id']; ?><?php } ?><?php if ($invoices['payment_tax_id']) { ?><br/><?php echo $text_tax_id; ?> <?php echo $invoices['payment_tax_id']; ?><?php } ?></td>
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
		<?php foreach ($invoices['product'] as $product) { ?>
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
		<?php foreach ($invoices['total'] as $total) { ?>
		<tr>
			<td class="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<div style="text-align:left;"><b><?php echo $text_payment_method; ?></b> &nbsp; <?php echo $invoices['payment_method']; ?></div>
	<?php if ($invoices['comment']) { ?>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_comment; ?></th>
		</tr>
		<tr>
			<td><?php echo $invoices['comment']; ?></td>
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
