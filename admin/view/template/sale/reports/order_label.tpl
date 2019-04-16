<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>"
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
</head>
<body>
<div class="container">
  <?php foreach ($orders as $order): ?>
    <img src="<?php echo '../image/' . $logo ?>" style="width : 200px;">
    <table class="table table-bordered">
      <tr>
        <td width="50%"><?php echo $order['store_name']; ?><br>
          <?php echo $order['store_address']; ?><br>
          <?php echo $text_telephone; ?> <?php echo $order['store_telephone']; ?><br>
          <?php if ($order['store_fax']) { ?>
          <?php echo $text_fax; ?> <?php echo $order['store_fax']; ?><br>
          <?php } ?>
          <?php echo $order['store_email']; ?><br>
          <?php echo $order['store_url']; ?></td>
        <td>
          <b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br>
          <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br>
          <?php } ?>
          <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br>
          <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?><br>
          <?php if ($order['shipping_method']) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br>
          <?php } ?>
        </td>
      </tr>
    </table>
  <?php endforeach ?>
</div>
</body>
</html>
<script type="text/javascript"><!--
window.print()
//--></script>