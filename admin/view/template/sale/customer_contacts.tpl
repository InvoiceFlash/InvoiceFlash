<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'user-friends'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label for="name" class="control-label col-sm-2"><?php echo $entry_name ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="name" id="name" class="form-control" value="<?php echo $name ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="email" class="control-label col-sm-2"><?php echo $entry_email ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="email" id="email" class="form-control" value="<?php echo $email ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="telephone" class="control-label col-sm-2"><?php echo $entry_telephone ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="telef1" id="telef1" class="form-control" value="<?php echo $telef1 ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="telephone2" class="control-label col-sm-2"><?php echo $entry_telephone2 ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="telef2" id="telef2" class="form-control" value="<?php echo $telef2 ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="puesto" class="control-label col-sm-2"><?php echo $entry_puesto ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="puesto" id="puesto" class="form-control" value="<?php echo $puesto ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="notas" class="control-label col-sm-2"><?php echo $entry_notas ?></label>
        <div class="control-field col-sm-4">
          <input type="text" name="notas" id="notas" class="form-control" value="<?php echo $notas ?>">
        </div>
      </div>
    </form>
  </div>
</div>