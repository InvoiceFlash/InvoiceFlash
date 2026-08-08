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
<style>
@media print {
	@page { size: auto; margin: 0; }
	body, .container { min-width: 0 !important; width: auto !important; }
	body { margin: 15mm 10mm; padding-top: 0 !important; }
}
</style>
</head>
<body style="padding-top:80px;">
<div class="container">
<?php foreach ($purchase_orders as $purchase_order) { ?>
	<table style="width:100%; margin:0; border-collapse:collapse;">
		<tr>
			<td style="width:50%; vertical-align:top; padding:0;">
				<table style="width:100%; border-collapse:collapse;">
					<tr>
						<td style="width:26%; vertical-align:top; padding:0;">
							<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $purchase_order['store_name']; ?>" style="width:24mm;" />
						</td>
						<td style="width:21%; padding:0;"></td>
						<td style="width:53%; vertical-align:top; padding:0;">
							<strong style="font-size:15px;"><?php echo $purchase_order['store_name']; ?></strong><br>
							<?php echo $purchase_order['store_address']; ?><br>
							<?php echo $text_telephone; ?> <?php echo $purchase_order['store_telephone']; ?><br>
							<?php if ($purchase_order['store_fax']) { ?>
							<?php echo $text_fax; ?> <?php echo $purchase_order['store_fax']; ?><br>
							<?php } ?>
							<?php echo $text_tax_id; ?> <?php echo $purchase_order['store_nif']; ?><br>
							<?php echo $purchase_order['store_email']; ?>
						</td>
					</tr>
				</table>
			</td>
			<td style="text-align:right; vertical-align:top; padding:0;">
				<b><?php echo mb_strtoupper($text_purchase_order, 'UTF-8'); ?>:</b> <?php echo $purchase_order['po_number']; ?> &nbsp;&nbsp; <b>DATE:</b> <?php echo $purchase_order['date_added']; ?>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top; padding:0;"></td>
			<td style="text-align:left; vertical-align:top; padding:0;">
				<b><?php echo $text_to; ?>:</b><br>
				<?php if ($purchase_order['supplier_company']) { ?><strong><?php echo $purchase_order['supplier_company']; ?></strong><br/><?php } ?>
				<?php if ($purchase_order['supplier_tax_id']) { ?><?php echo $text_tax_id; ?> <?php echo $purchase_order['supplier_tax_id']; ?><br/><?php } ?>
				<?php echo $purchase_order['supplier_address']; ?><?php echo $purchase_order['supplier_address'] ? '<br/>' : ''; ?>
				<?php echo $purchase_order['supplier_email']; ?>
				<?php if ($purchase_order['supplier_telephone']) { ?><br/><?php echo $purchase_order['supplier_telephone']; ?><?php } ?>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_product; ?></th>
			<th class="text-right"><?php echo $column_quantity; ?></th>
			<th class="text-right"><?php echo $column_price; ?></th>
			<th class="text-right"><?php echo $column_discount; ?></th>
			<th class="text-right"><?php echo $column_total; ?></th>
		</tr>
		<?php foreach ($purchase_order['product'] as $product) { ?>
		<tr>
			<td><?php echo $product['name']; ?></td>
			<td class="text-right"><?php echo $product['quantity']; ?></td>
			<td class="text-right"><?php echo $product['price']; ?></td>
			<td class="text-right"><?php echo $product['discount']; ?></td>
			<td class="text-right"><?php echo $product['total']; ?></td>
		</tr>
		<?php } ?>
		<?php foreach ($purchase_order['total'] as $total) { ?>
		<tr>
			<td class="text-right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
			<td class="text-right"><?php echo $total['text']; ?></td>
		</tr>
		<?php } ?>
	</table>
	<div style="text-align:left;"><b><?php echo $text_payment_method; ?></b> &nbsp; <?php echo $purchase_order['payment_method']; ?></div>
	<?php if ($purchase_order['shipping_method']) { ?>
	<div style="text-align:left;"><b><?php echo $text_shipping_method; ?></b> &nbsp; <?php echo $purchase_order['shipping_method']; ?></div>
	<?php } ?>
<?php } ?>
</div>
</body>
</html>
<?php if (!isset($auto_print) || $auto_print) { ?>
<script type="text/javascript"><!--
window.print()
//--></script>
<?php } ?>
