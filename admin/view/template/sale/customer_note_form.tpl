<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card page-card">
	<?php $fa = 'sticky-note'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
  <div class="card-body">
    <form action="<?php echo $action ?>" class="form-classic" method="post" enctye="multipart/form-data" id="form">
      <div class="form-group">
        <label for="" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_user; ?></label>
        <div class="col-sm-4">
          <p class="form-control-static"><?php echo $user_name ?></p>
          <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_date_note; ?></label>
        <div class="control-field col-sm-4"><input type="date" class="form-control" name="date_added" value="<?php echo $date_added ?>"></div>
      </div>
      <div class="form-group">
        <label for="" class="col-form-label text-sm-end pb-0 col-sm-2"><?php echo $entry_comment; ?></label>
        <div class="control-field col-sm-4"><textarea name="comment" id="" cols="30" rows="10" class="form-control" autofocus><?php echo $comment ?></textarea></div>
      </div>
    </form>
  </div>
</div>