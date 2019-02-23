<!doctype html>
<html class="no-js" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>
  * {
    padding: 0;
    margin: 0;
  }
 
  table {
    border: 1px solid lightgrey;
    margin: 5px;
    padding: 5px;
  }
  .center {
    text-align: center;
  }

  .right {
    text-align: right;
  }
</style>
</head>
<body>
<div>
<?php foreach ($invoices as $invoice) { ?>
  <div>
    <img src="<?php echo $logo ?>" alt="<?php echo $invoice['store_name'] ?>">
  </div>
  <h1><?php echo $text_invoice; ?></h1>
  <table>
    <tr>
      <td width="50%"><strong><?php echo $invoice['store_name']; ?></strong><br>
        <?php echo $invoice['store_address']; ?><br>
        <?php echo $text_telephone; ?> <?php echo $invoice['store_telephone']; ?><br>
        <?php if ($invoice['store_fax']) { ?>
        <?php echo $text_fax; ?> <?php echo $invoice['store_fax']; ?><br>
        <?php } ?>
        <?php echo $invoice['store_email']; ?><br>
        <?php echo $invoice['store_url']; ?></td>
      <td>
        <b><?php echo $text_date_added; ?></b> <?php echo $invoice['date_added']; ?><br>
        <?php if ($invoice['invoice_no']) { ?>
          <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice['invoice_no']; ?><br>
        <?php } ?>
        <b><?php echo $text_invoice_id; ?></b> <?php echo $invoice['invoice_id']; ?><br>
        <b><?php echo $text_payment_method; ?></b> <?php echo $invoice['payment_method']; ?><br>
        <?php if ($invoice['shipping_method']) { ?>
        <b><?php echo $text_shipping_method; ?></b> <?php echo $invoice['shipping_method']; ?><br>
        <?php } ?>
      </td>
    </tr>
  </table>
  <table>
    <tr style="background-color: lightgrey;">
      <th width="50%"><b><?php echo $text_to; ?></b></th>
      <th><b><?php echo $text_ship_to; ?></b></th>
    </tr>
    <tr>
      <td>
        <?php echo $invoice['payment_address']; ?><br/>
        <?php echo $invoice['email']; ?><br/>
        <?php echo $invoice['telephone']; ?>
        <?php if ($invoice['payment_company_id']) { ?>
        <br/>
        <br/>
        <?php echo $text_company_id; ?> <?php echo $invoice['payment_company_id']; ?>
        <?php } ?>
        <?php if ($invoice['payment_tax_id']) { ?>
        <br/>
        <?php echo $text_tax_id; ?> <?php echo $invoice['payment_tax_id']; ?>
        <?php } ?></td>
      <td><?php echo $invoice['shipping_address']; ?></td>
    </tr>
  </table>
  <table>
    <tr style="background-color: lightgrey;">
      <th><b><?php echo $column_product; ?></b></th>
      <th><b><?php echo $column_model; ?></b></th>
      <th class="center"><b><?php echo $column_quantity; ?></b></th>
      <th class="right"><b><?php echo $column_price; ?></b></th>
      <th class="right"><b><?php echo $column_total; ?></b></th>
    </tr>
    <?php foreach ($invoice['product'] as $product) { ?>
    <tr>
      <td><?php echo $product['name']; ?>
        <?php foreach ($product['option'] as $option) { ?>
        <br>
        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php } ?></td>
      <td><?php echo $product['model']; ?></td>
      <td class="center"><?php echo $product['quantity']; ?></td>
      <td class="right"><?php echo $product['price']; ?></td>
      <td class="right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($invoice['total'] as $total) { ?>
    <tr>
      <td class="right" colspan="4"><b><?php echo $total['title']; ?>:</b></td>
      <td class="right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </table>
  <?php if ($invoice['comment']) { ?>
  <table>
    <tr style="background-color: lightgrey;">
      <th><?php echo $column_comment; ?></th>
    </tr>
    <tr>
      <td><?php echo $invoice['comment']; ?></td>
    </tr>
  </table>
  <?php } ?>
<?php } ?>
</div>
</body>
</html>