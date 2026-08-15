<?php echo $header ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<?php if ($error_config) { ?>
<div class="alert alert-danger"><?php echo $error_config; ?></div>
<?php } ?>
<div id="content" class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-envelope"> <?php echo $heading_title ?></i></div>
		<div class="pull-right">
			<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#NewEmailModal"><i class="fa fa-envelope"></i><span class="hidden-xs"> <?php echo $button_new_email ?></span></button>
			<a href="<?php echo $getmail ?>" class="btn btn-primary"><i class="fa fa-sync-alt"></i><span class="hidden-xs"> <?php echo $button_reset ?></span></a>
			<button type="submit" class="btn btn-danger" formaction="<?php echo $delete ?>" id="btn-delete-mail" form="form"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
			<a href="<?php echo $cancel ?>" class="btn btn-warning"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel ?></span></a>
		</div>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a data-bs-toggle="tab" href="#tab-inbox" class="nav-link"><?php echo $tab_inbox; ?></a></li>
			<li class="nav-item"><a data-bs-toggle="tab" href="#tab-out" class="nav-link"><?php echo $tab_out; ?></a></li>
		</ul>
		<form method="post" enctype="multipart/form-data" id="form">
			<input type="hidden" name="type" id="input-mail-type" value="<?php echo $active_type; ?>">
			<div class="tab-content mt-3">
				<div class="tab-pane" id="tab-inbox">
					<?php if ($error_imap) { ?>
					<div class="alert alert-warning"><?php echo $error_imap; ?></div>
					<?php } ?>
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th><?php echo $column_from; ?></th>
								<th class="d-none d-md-table-cell"><?php echo $column_customer; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_subject; ?></th>
								<th class="d-none d-md-table-cell"><?php echo $column_received; ?></th>
								<th class="text-center"><?php echo $column_rag_indexed; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody data-link="row" class="rowlink">
							<tr id="filter" class="info">
								<td class="text-center"><a class="btn btn-default btn-block" href="index.php?route=catalog/mail&token=<?php echo $token; ?>" rel="tooltip" title="Reset"><i class="fa fa-power-off fa-fw"></i></a></td>
								<td><input type="text" name="filter_company" value="<?php echo $filter_company; ?>" data-target="company" data-url="sale/customer" class="form-control"></td>
								<td class="d-none d-md-table-cell"><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" class="form-control"></td>
								<td class="d-none d-sm-table-cell"></td>
								<td class="d-none d-md-table-cell"></td>
								<td class="text-center"></td>
								<td class="text-right"><button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i><span class="hidden-xs"> <?php echo $button_filter; ?></span></button></td>
							</tr>
							<?php if ($mails_ins) { ?>
								<?php foreach ($mails_ins as $mail_in) { ?>
									<tr<?php echo ($mail_in['unread']) ? ' style="background-color:#fff9c4;"' : ''; ?>>
									<td class="rowlink-skip text-center">
										<input type="checkbox" name="sel_mail_in[]" value="<?php echo $mail_in['mail_id']; ?>" <?php echo ($mail_in['sel_mail_in'] ? 'checked' : '')?>>
									</td>
									<td><?php echo $mail_in['company']; ?></td>
									<td class="d-none d-md-table-cell"><?php echo $mail_in['mailfrom']; ?></td>
									<td class="d-none d-sm-table-cell"><?php echo $mail_in['title']; ?></td>
									<td class="d-none d-md-table-cell"><?php echo $mail_in['created']; ?></td>
									<td class="rowlink-skip text-center"><input type="checkbox" disabled <?php echo ($mail_in['rag_indexed'] ? 'checked' : ''); ?>></td>
									<td class="text-right"><?php foreach ($mail_in['action'] as $action) { ?>
									<a class="btn btn-info" href="<?php echo $action['href']; ?>"><i class="fas fa-eye"></i><span class="d-none d-md-inline"> <?php echo $action['text']; ?></span></a>
									<?php } ?></td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr>
								<td colspan="7" class="text-center"><?php echo $text_no_results; ?></td>
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
								<th class="text-center"><?php echo $column_rag_indexed; ?></th>
								<th><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody data-link="row" class="rowlink" id="tbody-out">
							<?php if ($mails_outs) { ?>
								<?php foreach ($mails_outs as $mails_out) { ?>
									<tr>
										<td class="rowlink-skip text-center">
											<input type="checkbox" name="sel_mail_out[]" value="<?php echo $mails_out['mail_id']; ?>" <?php echo ($mails_out['sel_mail_out'] ? 'checked' : ''); ?>>
										</td>
										<td><?php echo $mails_out['company']; ?></td>
										<td class="d-none d-sm-table-cell"><?php echo $mails_out['subject']; ?></td>
										<td class="d-none d-md-table-cell"><?php echo $mails_out['date_added']; ?></td>
										<td class="rowlink-skip text-center"><input type="checkbox" disabled <?php echo ($mails_out['rag_indexed'] ? 'checked' : ''); ?>></td>
										<td class="text-right"><?php foreach ($mails_out['action'] as $action) { ?>
											<a class="btn btn-info" href="<?php echo $action['href']; ?>"><i class="fas fa-eye"></i><span class="d-none d-md-inline"> <?php echo $action['text']; ?></span></a>
										<?php } ?></td>
									</tr>
								<?php } ?>
							<?php } else { ?>
								<tr id="out-no-results">
								<td colspan="7" class="text-center"><?php echo $text_no_results; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="pagination"><?php echo str_replace('....','',$pag_mail_out); ?></div>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Modal -->
<div id="NewEmailModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo $button_new_email; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" id="form-email">
					<div class="form-group row">
						<label for="email" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_to; ?></label>
						<div class="col-sm-10">
							<input type="text" name="email" id="email" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="subject" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_subject; ?></label>
						<div class="col-sm-10">
							<input type="text" name="subject" id="subject" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label for="message" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_message; ?></label>
						<div class="col-sm-10">
							<textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label for="file" class="col-form-label col-sm-10 col-md-2"><?php echo $entry_file; ?></label>
						<div class="input-group col-sm-10">
							<input type="text" name="mask" id="input-file" class="form-control">
							<input type="hidden" name="filename">
							<span class="input-group-btn">
								<button type="button" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn-send"><?php echo $button_send; ?></button>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"><?php echo $button_cancel; ?></button>
			</div>
		</div>
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
				if(json['error']['permis']){ alertMessage('danger', json['error']['permis']); }
			}
			if(json['success']){
				alertMessage('success',json['success']);

				var modalInstance = bootstrap.Modal.getInstance(document.getElementById('NewEmailModal'));
				if (modalInstance) {
					modalInstance.hide();
				}

				$('#email').val('');
				$('#subject').val('');
				editor.setData('');
				$('#input-file').val('');
				$('input[name="filename"]').val('');

				var out = json['outbox'];

				if (out) {
					$('#out-no-results').remove();

					var row = $('<tr>');
					row.append($('<td class="rowlink-skip text-center">').append($('<input type="checkbox" name="sel_mail_out[]">').val(out.mail_id)));
					row.append($('<td>').text(out.company));
					row.append($('<td class="d-none d-sm-table-cell">').text(out.subject));
					row.append($('<td class="d-none d-md-table-cell">').text(out.date_added));
					row.append($('<td class="rowlink-skip text-center">').append($('<input type="checkbox" disabled>')));

					var action = $('<td class="text-right">');
					var link = $('<a class="btn btn-info">').attr('href', out.href);
					link.append($('<i class="fas fa-eye">'));
					link.append($('<span class="d-none d-md-inline">').text(' ' + out.text_view));
					action.append(link);
					row.append(action);

					$('#tbody-out').prepend(row);
				}
			}
		}
	});
});
</script>
<script>
document.getElementById('NewEmailModal').addEventListener('shown.bs.modal', function () {
	if (CKEDITOR.instances.message) {
		CKEDITOR.instances.message.focus();
		return;
	}

	CKEDITOR.replace('message', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	}).on('instanceReady', function () {
		this.focus();
	});
});
</script>
<script>
$('a[href="#tab-inbox"]').on('shown.bs.tab', function() {
	$('#input-mail-type').val('in');
});
$('a[href="#tab-out"]').on('shown.bs.tab', function() {
	$('#input-mail-type').val('out');
});

<?php if ($active_type == 'out') { ?>
$(document).ready(function() {
	bootstrap.Tab.getOrCreateInstance($('a[href="#tab-out"]')[0]).show();
});
<?php } ?>

$('#btn-delete-mail').on('click', function(e) {
	e.preventDefault();

	if (!$('input[name="sel_mail_in[]"]:checked').length && !$('input[name="sel_mail_out[]"]:checked').length) {
		return;
	}

	if (confirm(text_confirm)) {
		$('#form').attr('action', $(this).attr('formaction')).submit();
	}
});
</script>
<?php echo $footer ?>