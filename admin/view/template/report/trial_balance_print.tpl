<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $heading_title; ?></title>
<style>
* { padding: 0; margin: 0; font-family: helvetica; }
body { font-size: 10px; }
.report-title { text-align: center; font-size: 16px; font-weight: bold; padding-bottom: 10px; text-transform: uppercase; }
table.meta { margin-bottom: 6px; }
table.meta td { padding: 1px 4px; font-size: 10px; }
table.meta .label { font-weight: bold; width: 80px; }
hr { border: none; border-top: 1px solid #000000; margin: 4px 0 8px 0; }
table.lines { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
table.lines th { text-align: left; border-bottom: 1px solid #000000; padding: 3px 4px; font-size: 8px; white-space: nowrap; }
table.lines td { padding: 2px 4px; font-size: 8px; }
table.lines tfoot td { border-top: 1px solid #000000; font-weight: bold; }
.text-right { text-align: right; }
</style>
</head>
<body>
<div class="report-title"><?php echo $heading_title; ?></div>
<table class="meta">
	<tr><td class="label"><?php echo $text_company; ?>:</td><td><?php echo $company_name; ?></td></tr>
	<tr><td class="label"><?php echo $text_period; ?>:</td><td><?php echo $period; ?></td></tr>
	<tr><td class="label"><?php echo $text_print_date; ?>:</td><td><?php echo $print_date; ?></td></tr>
</table>
<hr>
<table class="lines">
	<thead>
		<tr>
			<th><?php echo $column_account; ?></th>
			<th><?php echo $column_title; ?></th>
			<th class="text-right"><?php echo $column_debit; ?></th>
			<th class="text-right"><?php echo $column_credit; ?></th>
			<?php if ($balance_columns == 'one') { ?>
			<th class="text-right"><?php echo $column_balance; ?></th>
			<?php } else { ?>
			<th class="text-right"><?php echo $column_debit_balance; ?></th>
			<th class="text-right"><?php echo $column_credit_balance; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($result['rows'] as $row) { ?>
		<tr>
			<td><?php echo $row['code']; ?></td>
			<td><?php echo $row['title']; ?></td>
			<td class="text-right"><?php echo $row['debit']; ?></td>
			<td class="text-right"><?php echo $row['credit']; ?></td>
			<?php if ($balance_columns == 'one') { ?>
			<td class="text-right"><?php echo $row['balance']; ?></td>
			<?php } else { ?>
			<td class="text-right"><?php echo $row['debit_balance']; ?></td>
			<td class="text-right"><?php echo $row['credit_balance']; ?></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2"><?php echo $text_total; ?></td>
			<td class="text-right"><?php echo $result['total_debit']; ?></td>
			<td class="text-right"><?php echo $result['total_credit']; ?></td>
			<?php if ($balance_columns == 'one') { ?>
			<td class="text-right"><?php echo $result['total_balance']; ?></td>
			<?php } else { ?>
			<td class="text-right"><?php echo $result['total_debit_balance']; ?></td>
			<td class="text-right"><?php echo $result['total_credit_balance']; ?></td>
			<?php } ?>
		</tr>
	</tfoot>
</table>
</body>
</html>
