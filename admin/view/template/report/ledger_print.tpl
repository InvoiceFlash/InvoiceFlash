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
.account-title { font-size: 11px; font-weight: bold; padding: 6px 0 3px 0; }
table.lines { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
table.lines th { text-align: left; border-bottom: 1px solid #000000; padding: 3px 4px; font-size: 8px; white-space: nowrap; }
table.lines td { padding: 2px 4px; font-size: 8px; }
table.lines tfoot td { border-top: 1px solid #000000; font-weight: bold; }
.text-right { text-align: right; }
.account-block-break { page-break-after: always; }
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
<?php foreach ($accounts as $index => $account) { ?>
<div class="<?php echo ($new_page_per_account && $index < count($accounts) - 1) ? 'account-block-break' : ''; ?>">
	<div class="account-title"><?php echo $account['code'] . ' - ' . $account['title']; ?></div>
	<table class="lines">
		<thead>
			<tr>
				<th><?php echo $column_entry; ?></th>
				<th><?php echo $column_concept; ?></th>
				<th class="text-right"><?php echo $column_debit; ?></th>
				<th class="text-right"><?php echo $column_credit; ?></th>
				<th class="text-right"><?php echo $column_balance; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($account['rows'] as $row) { ?>
			<tr>
				<td><?php echo trim($row['entry_id'] . ' ' . $row['line_date']); ?></td>
				<td><?php echo $row['concept']; ?></td>
				<td class="text-right"><?php echo $row['debit']; ?></td>
				<td class="text-right"><?php echo $row['credit']; ?></td>
				<td class="text-right"><?php echo $row['balance']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2"><?php echo $text_total; ?></td>
				<td class="text-right"><?php echo $account['total_debit']; ?></td>
				<td class="text-right"><?php echo $account['total_credit']; ?></td>
				<td class="text-right"><?php echo $account['balance']; ?></td>
			</tr>
		</tfoot>
	</table>
</div>
<?php } ?>
</body>
</html>
