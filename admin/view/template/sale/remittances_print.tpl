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
<?php foreach ($remittances as $remittance) { ?>
	<div class="store_logo">
		<div class="logo">
			<img src="<?php echo '../image/' . $logo; ?>" title="<?php echo $remittance['store_name']; ?>" />
			<span class="title"><?php echo $text_remittance; ?></span>
		</div>
	</div>
	<table class="table table-bordered">
		<tr>
			<td width="50%"><?php echo $remittance['store_name']; ?><br>
				<?php echo $remittance['store_address']; ?><br>
				<?php echo $text_telephone; ?> <?php echo $remittance['store_telephone']; ?><br>
				<?php echo $remittance['store_email']; ?><br>
				<?php echo $remittance['store_url']; ?></td>
			<td>
				<b><?php echo $text_date; ?></b> <?php echo $remittance['date']; ?><br>
				<b><?php echo $text_remittance_id; ?></b> <?php echo $remittance['remittance_id']; ?><br>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<th><?php echo $column_customer_id; ?></th>
			<th><?php echo $column_customer; ?></th>
			<th><?php echo $column_bank_cc; ?></th>
			<th class="text-right"><?php echo $column_amount; ?></th>
		</tr>
		<?php foreach ($remittance['remittance_lines'] as $line) { ?>
		<tr>
			<td><?php echo $line['customer_id']; ?></td>
			<td><?php echo $line['customer']; ?></td>
			<td><?php echo $line['bank_cc']; ?></td>
			<td class="text-right"><?php echo $line['amount']; ?></td>
		</tr>
		<?php } ?>
	</table>
<?php } ?>
</div>
</body>
</html>