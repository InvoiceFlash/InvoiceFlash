<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'user-friends'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="card-body">
    <form action="<?php echo $action ?>" class="form-classic" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label for="name" class="col-form-label text-sm-end pb-0 col-sm-2"><i class="text-danger">*</i> <?php echo $entry_name ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?>">
          <?php if ($error_name) { ?>
          <span class="text-danger"><?php echo $error_name; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label for="email" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_email ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="email" id="email" class="form-control" value="<?php echo $email ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="telephone" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_telephone ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="telef1" id="telef1" class="form-control" value="<?php echo $telef1 ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="telephone2" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_telephone2 ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="telef2" id="telef2" class="form-control" value="<?php echo $telef2 ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="puesto" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_puesto ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="puesto" id="puesto" class="form-control" value="<?php echo $puesto ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="notas" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_notas ?></label>
        <div class="control-field col-sm-4">
          <textarea name="notas" id="notas" cols="30" rows="10" class="form-control"><?php echo $notas; ?></textarea>
        </div>
      </div>
    </form>
  </div>
</div>