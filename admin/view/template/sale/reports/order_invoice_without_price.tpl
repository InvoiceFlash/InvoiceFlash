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
<body style="padding-top: 0;">
  <div class="container">
    <?php foreach ($orders as $order): ?>
      <img src="<?php echo '../image/' . $logo ?>" style="width: 200px;"><h1><?php echo $text_order; ?></h1>
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
      <table class="table table-bordered">
        <tr>
          <th width="50%"><?php echo $text_to; ?></th>
          <th><?php echo $text_ship_to; ?></th>
        </tr>
        <tr>
          <td><?php echo $order['payment_address']; ?><br/>
            <?php echo $order['email']; ?><br/>
            <?php echo $order['telephone']; ?>
            <?php if ($order['payment_company_id']) { ?>
            <br/>
            <br/>
            <?php echo $text_company_id; ?> <?php echo $order['payment_company_id']; ?>
            <?php } ?>
            <?php if ($order['payment_tax_id']) { ?>
            <br/>
            <?php echo $text_tax_id; ?> <?php echo $order['payment_tax_id']; ?>
            <?php } ?></td>
          <td><?php echo $order['shipping_address']; ?></td>
        </tr>
      </table>
      <table class="table table-bordered">
        <tr>
          <th><?php echo $column_product; ?></th>
          <th><?php echo $column_model; ?></th>
          <th class="text-right"><?php echo $column_quantity; ?></th>
        </tr>
        <?php foreach ($order['product'] as $product): ?>
          <tr>
            <td><?php echo $product['name'] ?>
              <?php foreach ($product['option'] as $option): ?>
                <br>
                $nbsp;<small> - <?php echo $option['name'] ?>: <?php echo $option['value'] ?></small>
              <?php endforeach ?>
            </td>
            <td><?php echo $product['model'] ?></td>
            <td class="text-right"><?php echo $product['quantity'] ?></td>
          </tr>
        <?php endforeach ?>
        <?php if ($order['comment']): ?>
          <tr><th><?php echo $column_comment ?></th></tr>
          <tr><td><?php echo $order['comment'] ?></td></tr>
        <?php endif ?>
      </table>
    <?php endforeach ?>
  </div>
</body>
</html>
<script type="text/javascript">
  window.print()
//</script>