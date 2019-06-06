<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <div class="pull-left h2"><i class="hidden-xs fa fa-user"> <?php echo $heading_title ?></i></div>
    <div class="pull-right">
      <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-danger"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="panel-body">
    <form action="<?php echo $action ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
     <div class="form-group row">
        <label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
        <div class="col-sm-6">
          <?php foreach ($languages as $language) { ?>
            <div class="input-group"><input type="text" name="potentials_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($potentials_group_description[$language['language_id']]) ? $potentials_group_description[$language['language_id']]['name'] :''; ?>" class="form-control">
            <div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div>
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <div class="help-block text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
            <?php } ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_description; ?></label>
        <div class="col-sm-6">
        <?php foreach ($languages as $language) { ?>
          <div class="input-group"><textarea name="potentials_group_description[<?php echo $language['language_id']; ?>][description]" class="form-control" rows="3"><?php echo isset($potentials_group_description[$language['language_id']]) ? $potentials_group_description[$language['language_id']]['description'] :''; ?></textarea>
          <div class="input-group-append"><span class="input-group-text"><i class="lang-<?php echo str_replace('.png','', $language['image']); ?>" title="<?php echo $language['name']; ?>"></i></span></div></div>
        <?php } ?>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_approval; ?></label>
        <div class="col-sm-6">
          <?php if ($approval) { ?>
          <label class="radio-inline"><input type="radio" name="approval" value="1" checked=""><?php echo $text_yes; ?></label>
          <label class="radio-inline"><input type="radio" name="approval" value="0"><?php echo $text_no; ?></label>
          <?php } else { ?>
          <label class="radio-inline"><input type="radio" name="approval" value="1"><?php echo $text_yes; ?></label>
          <label class="radio-inline"><input type="radio" name="approval" value="0" checked=""><?php echo $text_no; ?></label>
          <?php } ?>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-6">
          <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" class="form-control">
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>