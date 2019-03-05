<?php echo $header ?>
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <div class="pull-left h2"><i class="hidden-xs"></i><?php echo $heading_title; ?></div>
    <div class="pull-right">
     <button type="submit" form="contact-form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
      <a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctye="multipart/form-data" id="contact-form">
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