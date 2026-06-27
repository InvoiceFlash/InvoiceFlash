<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'sticky-note'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_user; ?></label>
        <div class="col-sm-4">
          <p class="form-control-static"><?php echo $user_name ?></p>
          <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_date_note; ?></label>
        <div class="control-field col-sm-4"><input type="date" class="form-control" name="date_added" value="<?php echo $date_added ?>"></div>
      </div>
      <div class="form-group">
        <label for="" class="control-label col-sm-2"><?php echo $entry_comment; ?></label>
        <div class="control-field col-sm-4"><textarea name="comment" id="" cols="30" rows="10" class="form-control"><?php echo $comment ?></textarea></div>
      </div>
    </form>
  </div>
</div>