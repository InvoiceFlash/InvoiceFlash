<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'clock'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div class="form-group row">
                <label class="col-form-label col-sm-10 col-md-2"><span class="required">*</span> <?php echo $entry_code; ?></label>
                <div class="col-sm-6">
                    <input type="text" name="cron_code" class="form-control" value="<?php echo $cron_code; ?>">
                    <?php if ($error_code) { ?>
                        <div class="help-block text-danger"><?php echo $error_code; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-10 col-md-2"><span class="required">*</span> <?php echo $entry_action; ?></label>
                <div class="col-sm-6">
                    <input type="text" name="cron_action" class="form-control" value="<?php echo $cron_action; ?>">
                    <?php if ($error_action) { ?>
                        <div class="help-block text-danger"><?php echo $error_action; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_status?></label>
                <div class="col-sm-6">
                    <select name="cron_status" class="form-control">
                        <option value="1" <?php echo $cron_status ? 'selected' : ''; ?>><?php echo $text_enabled; ?></option>
                        <option value="0" <?php echo $cron_status ? '' : 'selected'; ?>><?php echo $text_disabled; ?></option>
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>