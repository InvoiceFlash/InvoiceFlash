<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'university'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label for="bank_name" class="control-label col-sm-2"><i class="text-danger">*</i> <?php echo $entry_bank_name ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?php echo $bank_name ?>">
          <?php if ($error_bank_name) { ?>
          <span class="text-danger"><?php echo $error_bank_name; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label for="iban" class="control-label col-sm-2"><i class="text-danger">*</i> <?php echo $entry_iban ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="iban" id="iban" class="form-control" value="<?php echo $iban ?>">
          <?php if ($error_iban) { ?>
          <span class="text-danger"><?php echo $error_iban; ?></span>
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
</div>
