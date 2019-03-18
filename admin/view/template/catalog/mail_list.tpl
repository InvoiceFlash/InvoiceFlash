<?php echo $header ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<?php if ($error_config) { ?>
<div class="alert alert-danger"><?php echo $error_config; ?></div>
<?php } ?>
<div id="content" class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-envelope"> <?php echo $heading_title ?></i></div>
		<div class="pull-right">
			<a href="<?php echo $getmail ?>" class="btn btn-primary"><i class="fa fa-sync-alt"></i><span class="hidden-xs"> <?php echo $button_reset ?></span></a>
			<button type="submit" class="btn btn-danger" formaction="<?php echo $delete ?>" id="btn-delete" form="form"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
			<a href="<?php echo $cancel ?>" class="btn btn-warning"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a data-toggle="tab" href="#tab-inbox" class="nav-link"><?php echo $tab_inbox; ?></a></li>
			<li class="nav-item"><a data-toggle="tab" href="#tab-out" class="nav-link"><?php echo $tab_out; ?></a></li>
			<li class="nav-item"><a data-toggle="tab" href="#tab-mail" class="nav-link"><?php echo $tab_mail; ?></a></li>
		</ul>
		<form method="post" enctype="multipart/form-data" id="form">
			<div class="tab-content mt-3">
				<div class="tab-pane" id="tab-inbox">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th><?php echo $column_from; ?></th>
								<th class="d-none d-md-table-cell"><?php echo $column_customer; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_subject; ?></th>
								<th class="d-none d-md-table-cell"><?php echo $column_received; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody data-link="row" class="rowlink">
							<tr id="filter" class="info">
								<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=catalog/mail&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
								<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" data-target="company" data-url="sale/customer" class="form-control"></td>
								<td class="d-none d-md-table-cell"></td>
								<td class="d-none d-sm-table-cell"></td>
								<td class="d-none d-md-table-cell"></td>
								<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
							</tr>
							<?php if ($mails_ins) { ?>
								<?php foreach ($mails_ins as $mail_in) { ?>
									<tr>
									<td class="rowlink-skip text-center">
										<input type="checkbox" name="sel_mail_in[]" value="<?php echo $mail_in['mail_id']; ?>" <?php echo ($mail_in['sel_mail_in'] ? 'checked' : '')?>>
									</td>
									<td><?php echo $mail_in['company']; ?></td>
									<td class="d-none d-md-table-cell"><?php echo $mail_in['mailfrom']; ?></td>
									<td class="d-none d-sm-table-cell"><?php echo $mail_in['title']; ?></td>
									<td class="d-none d-md-table-cell"><?php echo $mail_in['created']; ?></td>
									<td class="text-right"><?php foreach ($mail_in['action'] as $action) { ?>
									<a class="btn btn-info" href="<?php echo $action['href']; ?>"><i class="fas fa-eye"></i><span class="d-none d-md-inline"> <?php echo $action['text']; ?></span></a>
									<?php } ?></td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
								<td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="pagination"><?php echo str_replace('....', '', $pag_mail_in); ?></div>
				</div>
				<div class="tab-pane" id="tab-out">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th><?php echo $column_to; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_subject; ?></th>
								<th class="d-none d-md-table-cell"><?php echo $column_date; ?></th>
								<th><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody data-link="row" class="rowlink">
							<?php if ($mails_outs) { ?>
								<?php foreach ($mails_outs as $mails_out) { ?>
									<tr>
										<td class="rowlink-skip text-center">
											<input type="checkbox" name="sel_mail_out[]" value="<?php echo $mails_out['mail_id']; ?>" <?php echo ($mails_out['sel_mail_out'] ? 'checked' : ''); ?>>
										</td>
										<td><?php echo $mails_out['company']; ?></td>
										<td class="d-none d-sm-table-cell"><?php echo $mails_out['subject']; ?></td>
										<td class="d-none d-md-table-cell"><?php echo $mails_out['date_added']; ?></td>
										<td class="text-right"><?php foreach ($mails_out['action'] as $action) { ?>
											<a class="btn btn-info" href="<?php echo $action['href']; ?>"><i class="fas fa-eye"></i><span class="d-none d-md-inline"> <?php echo $action['text']; ?></span></a>
										<?php } ?></td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
								<td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="pagination"><?php echo str_replace('....','',$pag_mail_out); ?></div>
				</div>
				<div class="tab-pane" id="tab-mail">
					<div class="form-horizontal" id="form-email">
						<div class="form-group row">
							<label for="email" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_to; ?></label>
							<div class="input-group col-sm-6">
								<input type="text" name="email" id="email" class="form-control">
								<div class="input-group-append">
									<button type="button" class="btn btn-primary" id="btn-send"><?php echo $button_send; ?></button>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="subject" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_subject; ?></label>
							<div class="col-sm-6">
								<input type="text" name="subject" id="subject" class="form-control">
							</div>
						</div>
						<div class="form-group row">
							<label for="message" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_message; ?></label>
							<div class="col-sm-6">
								<textarea name="message" id="message" cols="30" rows="10" class="form-control ckeditor"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="file" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_file; ?></label>
							<div class="input-group col-sm-6">
								<input type="text" name="mask" id="input-file" class="form-control">
								<input type="hidden" name="filename">
								<span class="input-group-btn">
									<button type="button" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$('#btn-send').on('click',function(e){
	var to = $('#email').val();
	var subject = $('#subject').val();

	var editor = CKEDITOR.instances.message;
	var message = editor.getData();
	
	var filename = $('#input-file').val();

	$.ajax({
		url:'index.php?route=catalog/mail/send&token=<?php echo $token; ?>',
		type:'post',
		dataType:'json',
		data:'to='+encodeURIComponent(to)+'&subject='+encodeURIComponent(subject)+'&message='+encodeURIComponent(message)+'&filename='+encodeURIComponent(filename),
		beforeSend:function(){
			$('#btn-send').button('loading');
			$('#btn-send').append($('<i>', {class:'icon-loading'}));
		},
		success:function(json){
			$('#btn-send').button('reset');
			if(json['error']){
				if(json['error']['to']){ alertMessage('danger', json['error']['to']); }
				if(json['error']['subject']){ alertMessage('danger', json['error']['subject']); }
				if(json['error']['message']){ alertMessage('danger', json['error']['message']); }
			}
			if(json['success']){
				alertMessage('success',json['success']);
			}
		}
	});
});
</script>
<script>
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
</script>
<?php echo $footer ?>