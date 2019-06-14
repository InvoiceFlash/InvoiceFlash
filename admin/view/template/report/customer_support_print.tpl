<!doctype html>
<html class="no-js">
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
<body>
    <div class="container">
        <div class="row">
            <h2>
            <?php echo $heading_title_print; ?>
            <br><small class="text-muted"><?php echo $text_today; ?></small>
            </h2>
        </div>
        <div class="row">
            <div class="col-sm-4"><?php if (isset($filter_date_start)) { echo $text_date_start; }?></div>
            <div class="col-sm-4"><?php if (isset($filter_date_end)) { echo $text_date_end; }?></div>
            <div class="col-sm-4"><?php if (isset($filter_status_id)) { echo $text_status_id; }?></div>
        </div>
        <div class="row">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><?php echo $column_date; ?></th>
                        <th><?php echo $column_customer; ?></th>
                        <th><?php echo $column_product; ?></th>
                        <th><?php echo $column_email; ?></th>
                        <th><?php echo $column_telephone; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contracts as $contract) { ?>
                    <tr>
                        <td><?php echo $contract['date']; ?></td>
                        <td><?php echo $contract['customer']; ?></td>
                        <td><?php echo $contract['product']; ?></td>
                        <td><?php echo $contract['email']; ?></td>
                        <td><?php echo $contract['telephone']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>