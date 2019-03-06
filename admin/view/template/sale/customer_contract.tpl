<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'file-contract'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label class="control-label col-sm-2"><?php echo $entry_article ?></label>
        <div class="control-field col-sm-4">
          <select name="product_id" id="product" class="form-control">
            <option value="0"><?php echo $text_select ?></option>
            <?php foreach ($products as $product): ?>
              <option value="<?php echo $product['product_id'] ?>" <?php echo ($product_id==$product['product_id'] ? 'selected' : '') ?>><?php echo $product['name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2"><?php echo $entry_quantity ?></label>
        <div class="control-field col-sm-4">
          <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo $quantity ?>">
        </div>
      </div>
      <div class="form-group">
        <label class="control-label col-sm-2"><?php echo $entry_date ?></label>
        <div class="control-field col-sm-4">
          <input type="date" name="date_purchased" id="date_purchased" class="form-control" value="<?php echo $date_purchased ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_end_support ?></label>
        <div class="control-field col-sm-4">
          <input type="date" name="end_support" id="end_support" class="form-control" value="<?php echo $end_support ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_notes ?></label>
        <div class="control-field col-sm-4">
          <textarea name="notes" id="notes" cols="30" rows="10" class="form-control"><?php echo $notes ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_status ?></label>
        <div class="control-field col-sm-4">
          <select name="contract_status_id" id="contract_status" class="form-control">
            <option value="0"><?php echo $text_select ?></option>
            <?php foreach ($contract_statuses as $status): ?>
              <option value="<?php echo $status['contract_status_id'] ?>" <?php echo ($contract_status_id == $status['contract_status_id']) ? 'selected' : '' ?>><?php echo $status['name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
    </form>
  </div>
</div>