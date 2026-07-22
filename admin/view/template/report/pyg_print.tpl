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
table.lines tr.bold td { font-weight: bold; }
.text-right { text-align: right; }
</style>
</head>
<body>
<div class="report-title"><?php echo $heading_title; ?></div>
<table class="meta">
	<tr><td class="label"><?php echo $text_company; ?>:</td><td><?php echo $company_name; ?></td></tr>
	<tr><td class="label"><?php echo $text_period; ?>:</td><td><?php echo $period; ?></td></tr>
	<?php if ($compare && isset($period_prev)) { ?>
	<tr><td class="label">&nbsp;</td><td><?php echo $period_prev; ?></td></tr>
	<?php } ?>
	<tr><td class="label"><?php echo $text_print_date; ?>:</td><td><?php echo $print_date; ?></td></tr>
</table>
<hr>
<table class="lines">
	<thead>
		<tr>
			<th><?php echo $column_concept; ?></th>
			<th class="text-right"><?php echo $column_amount; ?></th>
			<?php if ($compare) { ?>
			<th class="text-right"><?php echo $column_amount_prev; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($rows as $row) { ?>
		<tr<?php echo $row['bold'] ? ' class="bold"' : ''; ?>>
			<td style="padding-left:<?php echo (4 + $row['level'] * 10); ?>px;"><?php echo $row['name']; ?></td>
			<td class="text-right"><?php echo $row['amount']; ?></td>
			<?php if ($compare) { ?>
			<td class="text-right"><?php echo $row['amount_prev']; ?></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>
</body>
</html>
