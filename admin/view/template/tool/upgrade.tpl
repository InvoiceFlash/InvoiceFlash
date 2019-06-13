<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-upload"></i><?php echo $heading_title; ?></h2>
    </div>
    <div class="card-body">
        <p><strong><?php echo $text_version; ?></strong><p>
		<div class="form-group row">
            <div class="col-sm-10">
			<button type="button" id="check" class="btn btn-default"><?php echo $button_check; ?></button>
            </div>
        </div>
		<div class="form-group row">
            <div class="col-sm-10">
			<button type="button" id="backup" class="btn btn-default"><?php echo $button_backup; ?></button>
            </div>
        </div>
		<div class="form-group row">
            <div class="col-sm-10">
			<button type="button" id="download" class="btn btn-default"><?php echo $button_download; ?></button>
            </div>
        </div>
		<div class="form-group row">
            <div class="col-sm-10">
			<button type="button" id="upgrade" class="btn btn-default"><?php echo $button_upgrade; ?></button>
            </div>
        </div>
        <div id="response"></div>
    </div>
</div>
<script>
$('#check').on('click', function(){
    $.ajax({
        url:'index.php?route=tool/upgrade/check&token=<?php echo $token; ?>',
        type:'post',
        dataType:'json',
        success:function(json){
			if (json['update'] == true) {
				$('#response').append('<p class="text-success">'+json['result']+'</p>');
			} else {
				$('#response').append('<p class="text-danger">'+json['result']+'</p>');
			}
        }
    });
});
$('#backup').on('click', function(){
    $.ajax({
        url:'index.php?route=tool/upgrade/backup&token=<?php echo $token; ?>',
        type:'post',
        dataType:'json',
        success:function(json){
			if (json['update'] == true) {
				$('#response').append('<p class="text-success">'+json['result']+'</p>');
			} else {
				$('#response').append('<p class="text-danger">'+json['result']+'</p>');
			}
        }
    });
});
$('#download').on('click', function(){
    $.ajax({
        url:'index.php?route=tool/upgrade/download&token=<?php echo $token; ?>',
        type:'post',
        dataType:'json',
        success:function(json){
			if (json['update'] == true) {
				$('#response').append('<p class="text-success">'+json['result']+'</p>');
			} else {
				$('#response').append('<p class="text-danger">'+json['result']+'</p>');
			}
        }
    });
});
$('#upgrade').on('click', function(){
    $.ajax({
        url:'index.php?route=tool/upgrade/upgrade&token=<?php echo $token; ?>',
        type:'post',
        dataType:'json',
        success:function(json){
			if (json['update'] == true) {
				$('#response').append('<p class="text-success">'+json['result']+'</p>');
			} else {
				$('#response').append('<p class="text-danger">'+json['result']+'</p>');
			}
        }
    });
});
</script>
<?php echo $footer; ?>