<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-upload"></i> <?php echo $heading_title; ?></h2>
    </div>
    <div class="card-body">
        <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <?php if ($error) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>

        <div class="form-group row">
            <label class="col-form-label col-sm-10 col-md-3"><?php echo $text_current_commit; ?></label>
            <div class="col-sm-6"><code id="current-commit"><?php echo $current_commit; ?></code></div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-10 col-md-3"><?php echo $text_latest_commit; ?></label>
            <div class="col-sm-6"><code id="latest-commit"><?php echo $latest_commit; ?></code></div>
        </div>

        <div id="upgrade-status" class="form-group row">
            <div class="col-sm-10 offset-md-3">
                <?php if ($has_update) { ?>
                <p id="status-text" class="text-warning"><?php echo $text_update_available; ?></p>
                <?php } else { ?>
                <p id="status-text" class="text-success"><?php echo $text_up_to_date; ?></p>
                <?php } ?>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="button" id="check" class="btn btn-default"><?php echo $button_check; ?></button>
                <a href="<?php echo $compare_url; ?>" target="_blank" class="btn btn-link"><?php echo $text_view_changes; ?></a>
                <?php if ($can_upgrade) { ?>
                <button type="button" id="upgrade" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#UpgradeConfirmModal"><?php echo $button_upgrade; ?></button>
                <?php } ?>
            </div>
        </div>
        <div id="response"></div>
    </div>
</div>
<?php if ($can_upgrade) { ?>
<div id="UpgradeConfirmModal" class="modal fade" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $text_modal_title; ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-form-label col-sm-5"><?php echo $text_current_commit; ?></label>
                    <div class="col-sm-6"><code id="modal-current-commit"><?php echo $current_commit; ?></code></div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-sm-5"><?php echo $text_latest_commit; ?></label>
                    <div class="col-sm-6"><code id="modal-latest-commit"><?php echo $latest_commit; ?></code></div>
                </div>
                <p><?php echo $text_confirm; ?></p>
            </div>
            <div class="modal-footer">
                <a href="<?php echo $upgrade_url; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $button_cancel; ?></button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>
$('#check').on('click', function () {
    $('#response').html('<p><?php echo $text_loading; ?></p>');

    $.ajax({
        url: '<?php echo $check_url; ?>',
        type: 'post',
        dataType: 'json',
        success: function (json) {
            $('#response').html('');

            $('#current-commit').text(json['current_commit']);
            $('#latest-commit').text(json['latest_commit']);
            $('#modal-current-commit').text(json['current_commit']);
            $('#modal-latest-commit').text(json['latest_commit']);

            if (json['has_update']) {
                $('#status-text').attr('class', 'text-warning').text('<?php echo $text_update_available; ?>');
            } else {
                $('#status-text').attr('class', 'text-success').text('<?php echo $text_up_to_date; ?>');
            }
        }
    });
});
</script>
<?php echo $footer; ?>
