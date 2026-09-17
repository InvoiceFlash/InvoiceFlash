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
table.lines { width: 100%; border-collapse: collapse; }
table.lines th { text-align: left; border-bottom: 1px solid #000000; padding: 3px 4px; font-size: 8px; white-space: nowrap; }
table.lines td { padding: 2px 4px; font-size: 8px; }
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
			<th><?php echo $column_entry; ?></th>
			<th><?php echo $column_account; ?></th>
			<th><?php echo $column_description; ?></th>
			<th><?php echo $column_concept; ?></th>
			<th class="text-right"><?php echo $column_debit; ?></th>
			<th class="text-right"><?php echo $column_credit; ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $row) { ?>
		<tr>
			<td><?php echo $row['entry_id'] . ' ' . $row['line_date']; ?></td>
			<td><?php echo $row['account']; ?></td>
			<td><?php echo $row['description']; ?></td>
			<td><?php echo $row['concept']; ?></td>
			<td class="text-right"><?php echo $row['debit']; ?></td>
			<td class="text-right"><?php echo $row['credit']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</body>
</html>
