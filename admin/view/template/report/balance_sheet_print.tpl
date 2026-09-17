<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $heading_title; ?></title>
<style>
* { padding: 0; margin: 0; font-family: helvetica; }
body { font-size: 10px; }
.meta { margin-bottom: 10px; }
.meta td { padding: 1px 4px; font-size: 10px; }
.meta .label { font-weight: bold; width: 80px; }
.statement-title { font-size: 16px; font-weight: bold; text-transform: uppercase; padding: 10px 0 6px 0; border-bottom: 1px solid #000000; margin-bottom: 6px; }
.row { display: flex; justify-content: space-between; padding: 2px 0; font-size: 9px; }
.row .label { text-align: left; }
.row .amount { text-align: right; white-space: nowrap; }
.section .label, .section .amount { font-weight: bold; }
.group { padding-left: 14px; }
.group .label, .group .amount { font-weight: bold; }
.account { padding-left: 28px; }
.total { border-top: 1px solid #000000; margin-top: 4px; padding-top: 4px; font-weight: bold; }
</style>
</head>
<body>
<table class="meta">
	<tr><td class="label"><?php echo $text_company; ?>:</td><td><?php echo $company_name; ?></td></tr>
	<tr><td class="label"><?php echo $text_as_of; ?>:</td><td><?php echo $as_of; ?></td></tr>
	<tr><td class="label"><?php echo $text_print_date; ?>:</td><td><?php echo $print_date; ?></td></tr>
</table>
<?php foreach ($statements as $statement) { ?>
<div class="statement-title"><?php echo $statement['title']; ?></div>
<?php foreach ($statement['sections'] as $section) { ?>
<div class="row section">
	<div class="label"><?php echo $section['label']; ?></div>
	<div class="amount"><?php echo $section['total']; ?></div>
</div>
<?php foreach ($section['groups'] as $group) { ?>
<div class="row group">
	<div class="label"><?php echo $group['label']; ?></div>
	<div class="amount"><?php echo $group['total']; ?></div>
</div>
<?php foreach ($group['rows'] as $row) { ?>
<div class="row account">
	<div class="label"><?php echo $row['code'] . ' ' . $row['title']; ?></div>
	<div class="amount"><?php echo $row['amount']; ?></div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>
<div class="row total">
	<div class="label"><?php echo $text_total; ?> <?php echo $statement['title']; ?></div>
	<div class="amount"><?php echo $statement['total']; ?></div>
</div>
<?php } ?>
</body>
</html>
