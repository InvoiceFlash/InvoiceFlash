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
/* TCPDF ignores the linked main.css, so anything the PDF needs has to live here */
.table {
	width: 100%;
	margin-bottom: 1rem;
	background-color: white;
}
.table-bordered {
	border: 1px solid grey;
}
th {
	font-weight: bold;
	background-color: #dee2e6;
}
.text-right {
	text-align: right;
}
@media print {
	@page { size: auto; margin: 0; }
	body, .container { min-width: 0 !important; width: auto !important; }
	body { margin: 15mm 10mm; }
}
</style>
</head>
<body style="padding-top:0;">
<div class="container">
<!-- <?php foreach ($invoices as $invoice) { ?> -->
	<table class="table table-bordered">
		<tr>
			<td width="50%"><img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $invoice['store_name']; ?>" style="width:48mm;" /><br>
				<?php echo $invoice['store_name']; ?><br>
				<?php echo $invoice['store_address']; ?><br>
				<?php if ($invoice['store_locality']) { ?>
				<?php echo $invoice['store_locality']; ?><br>
				<?php } ?>
				<?php echo $text_telephone; ?> <?php echo $invoice['store_telephone']; ?><br>
				<?php echo $text_tax_id; ?> <?php echo $invoice['store_nif']; ?><br>
				<?php if ($invoice['store_fax']) { ?>
				<?php echo $text_fax; ?> <?php echo $invoice['store_fax']; ?><br>
				<?php } ?>
				<?php echo $invoice['store_email']; ?><br>
				<?php echo $invoice['store_url']; ?><br>
				<?php if ($invoice['invoice_no']) { ?>
				<b><?php echo $text_invoice_no; ?></b>&nbsp;&nbsp;<?php echo $invoice['invoice_no']; ?><br>
				<?php } ?>
				<br>
				<b><?php echo $text_payment_method; ?></b>&nbsp;&nbsp;<?php echo $invoice['payment_method']; ?><br>
				<?php if ($invoice['shipping_method']) { ?>
				<b><?php echo $text_shipping_method; ?></b>&nbsp;&nbsp;<?php echo $invoice['shipping_method']; ?><br>
				<?php } ?>
			</td>
			<td style="vertical-align:top; padding:0;">
				<?php if ($invoice['qr_code_pdf']) { ?>
				<table style="width:100%; border-collapse:collapse;">
					<tr>
						<td></td>
						<td style="width:30mm; text-align:center; padding:0;">
							<div><b>QR tributario:</b></div>
							<img src="<?php echo $invoice['qr_code_pdf']; ?>" style="width:30mm; height:30mm;" alt="QR tributario" /><br><b>VERI*FACTU</b>
						</td>
					</tr>
				</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td style="text-align:left;"><b>DATE:</b>&nbsp;&nbsp;<?php echo $invoice['date_added']; ?></td>
			<td style="text-align:right;"><b><?php echo mb_strtoupper($text_invoice, 'UTF-8'); ?>:</b>&nbsp;&nbsp;<?php echo $invoice['invoice_prefix'] . $invoice['invoice_id']; ?></td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th width="50%"><?php echo $text_to; ?></th>
			<th><?php echo $text_ship_to; ?></th>
		</tr>
		<tr>
			<td><?php if ($invoice['payment_company']) { ?><strong><?php echo $invoice['payment_company']; ?></strong><br/><?php } ?><?php echo $invoice['payment_address']; ?><?php if ($invoice['payment_tax_id']) { ?><br/>
				<?php echo $text_tax_id; ?> <?php echo $invoice['payment_tax_id']; ?><?php } ?><br/>
				<?php echo $invoice['email']; ?>
				<?php if ($invoice['telephone']) { ?>
				<br/>
				<?php echo $invoice['telephone']; ?>
				<?php } ?>
				<?php if ($invoice['payment_company_id']) { ?>
				<br/>
				<?php echo $text_company_id; ?> <?php echo $invoice['payment_company_id']; ?>
				<?php } ?></td>
			<td><?php echo $invoice['shipping_address']; ?></td>
		</tr>
	</table>
	<br>&nbsp;<br>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th class="text-right"><?php echo $column_quantity; ?></th>
			<th class="text-right"><?php echo $column_price; ?></th>
			<th class="text-right"><?php echo $column_discount; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($invoice['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?>
				<?php foreach ($product['option'] as $option) { ?>
				<br>
				&nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
				<?php } ?></td>
			<td class="text-right"><?php echo $product['quantity']; ?></td>
			<td class="text-right"><?php echo $product['price']; ?></td>
			<td class="text-right"><?php echo $product['discount']; ?></td>
			<td class="text-right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($invoice['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
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