<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="card">
	<div class="card-header">
		<div class="float-left h2"><i class="hidden-xs fa fa-truck"></i><span> <?php echo $heading_title; ?></span></div>
		<div class="float-right">
			<button class="btn btn-default" data-bs-toggle="modal" data-bs-target="#EmailModal" data-keyboard="true"><i class="fa fa-envelope"></i><span class="hidden-xs"> <?php echo $button_new_email; ?></span></button>
			<button type="submit" form="form" class="btn btn-primary"><i class="fa fa-save"></i><span class="hidden-xs"> <?php echo $button_save; ?></span></button>
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="card-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab-general" data-bs-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-contacts" data-bs-toggle="tab"><?php echo $tab_contacts; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-contracts" data-bs-toggle="tab"<?php if ($has_documents) { ?> style="background-color:#c3e6cb;color:#fff;"<?php } ?>><?php echo $tab_contracts; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-email" data-bs-toggle="tab"><?php echo $tab_email; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-orders" data-bs-toggle="tab"><?php echo $tab_orders; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-recepciones" data-bs-toggle="tab"><?php echo $tab_recepciones; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-products" data-bs-toggle="tab"><?php echo $tab_products; ?></a></li>
			<li class="nav-item"><a class="nav-link" href="#tab-invoices" data-bs-toggle="tab"><?php echo $tab_invoices; ?></a></li>
		</ul>
		<form class="form-horizontal mt-2" action="<?php echo $action; ?>" method="post" id="form">
			<div class="tab-content">
				<div class="tab-pane" id="tab-general">
					<div class="row">
						<div class="col-xs-4 col-sm-3">
							<div class="nav flex-column" id="vtabs-supplier">
								<a href="#tab-supplier-general" class="nav-link active" role="tab" aria-selected="true" data-bs-toggle="pill"><?php echo $tab_general; ?></a>
								<a href="#tab-supplier-notes" class="nav-link" role="tab" aria-selected="false" data-bs-toggle="pill"<?php if ($notes) { ?> style="background-color:#fdf6d3;"<?php } ?>><?php echo $tab_notes; ?></a>
							</div>
						</div>
						<div class="col-xs-8 col-sm-9">
							<div class="tab-content" id="supplier-content">
								<div class="tab-pane fade show active" role="tab-panel" id="tab-supplier-general">
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_company; ?></label>
										<div class="col-sm-6">
											<input type="text" name="company" value="<?php echo $company; ?>" id="input-company" class="form-control">
											<?php if ($error_company) { ?>
											<div class="help-block text-danger"><?php echo $error_company; ?></div>
											<?php } ?>
										</div>
									</div>
									<input type="hidden" name="firstname" value="<?php echo $firstname; ?>">
									<input type="hidden" name="lastname" value="<?php echo $lastname; ?>">
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_email; ?></label>
										<div class="col-sm-6">
											<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
											<?php if ($error_email) { ?>
											<div class="help-block text-danger"><?php echo $error_email; ?></div>
											<?php } ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_tax_id; ?></label>
										<div class="col-sm-6">
											<input type="text" name="tax_id" value="<?php echo $tax_id; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_telephone; ?></label>
										<div class="col-sm-6">
											<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_web; ?></label>
										<div class="col-sm-6 input-group">
											<input type="text" name="web" value="<?php echo $web; ?>" id="web" class="form-control">
											<div class="input-group-append">
												<button type="button" id="button-web" class="btn btn-info" title="<?php echo $button_web?>"><i class="fas fa-globe"></i></button>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_address_1; ?></label>
										<div class="col-sm-6">
											<input type="text" name="address_1" value="<?php echo $address_1; ?>" class="form-control">
										</div>
									</div>
									<input type="hidden" name="address_2" value="<?php echo $address_2; ?>">
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_city; ?></label>
										<div class="col-sm-6">
											<input type="text" name="city" value="<?php echo $city; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><span id="supplier-postcode-required"></span> <?php echo $entry_postcode; ?></label>
										<div class="col-sm-6">
											<input type="text" name="postcode" value="<?php echo $postcode; ?>" class="form-control">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_country; ?></label>
										<div class="col-sm-6">
											<select name="country_id" id="supplier-country" class="form-control">
												<option value=""><?php echo $text_select; ?></option>
												<?php foreach ($countries as $country) { ?>
												<?php if ($country['country_id'] == $country_id) { ?>
												<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>
												<?php } else { ?>
												<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
												<?php } ?>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_zone; ?></label>
										<div class="col-sm-6">
											<select name="zone_id" class="form-control"></select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_status; ?></label>
										<div class="col-sm-6">
											<select name="status" class="form-control">
												<?php if ($status) { ?>
												<option value="1" selected=""><?php echo $text_enabled; ?></option>
												<option value="0"><?php echo $text_disabled; ?></option>
												<?php } else { ?>
												<option value="1"><?php echo $text_enabled; ?></option>
												<option value="0" selected=""><?php echo $text_disabled; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" role="tab-panel" id="tab-supplier-notes">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="40" class="text-center"></th>
												<th><?php echo $column_comment; ?></th>
												<th><?php echo $column_user; ?></th>
												<th class="d-none d-sm-table-cell"><?php echo $column_date; ?></th>
											</tr>
										</thead>
										<tbody>
											<?php if ($notes) { ?>
											<?php foreach ($notes as $note) { ?>
											<tr>
												<td class="text-center"><input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
													<a href="<?php echo $note['delete']; ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
												</td>
												<td><?php echo $note['comment']; ?></td>
												<td><?php echo $note['user']; ?></td>
												<td class="d-none d-sm-table-cell"><?php echo $note['date']; ?></td>
											</tr>
											<?php } ?>
											<?php } else { ?>
											<tr>
												<td colspan="4" class="text-center"><?php echo $text_no_results; ?></td>
											</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-right" colspan="4"><a href="<?php echo $add_note; ?>" class="btn btn-info"><?php echo $button_add_note; ?></a></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="tab-contacts">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_contact_name; ?></th>
								<th class="d-none d-sm-table-cell"><?php echo $column_contact_email; ?></th>
								<th><?php echo $column_telephone; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($contacts) { ?>
							<?php foreach ($contacts as $contact) { ?>
							<tr>
								<td><?php echo $contact['name']; ?></td>
								<td class="d-none d-sm-table-cell"><?php echo $contact['email']; ?></td>
								<td><?php echo $contact['telephone']; ?></td>
								<td class="text-right"><?php foreach ($contact['action'] as $action) { ?>
									<?php echo $action['link']; ?>
								<?php } ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr><td colspan="4" class="text-center"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="4"><a href="<?php echo $add_contact; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_contact; ?></a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="tab-pane" id="tab-contracts">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_filename; ?></th>
								<th><?php echo $column_date_added; ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php if ($contracts) { ?>
							<?php foreach ($contracts as $contract) { ?>
							<tr>
								<td><?php echo $contract['filename']; ?></td>
								<td><?php echo $contract['date_added']; ?></td>
								<td class="text-right"><?php foreach ($contract['action'] as $action) { ?>
									<?php echo $action['link']; ?>
								<?php } ?></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr><td colspan="3" class="text-center"><?php echo $text_no_results; ?></td></tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="text-right" colspan="3"><a href="<?php echo $add_contract; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_contract; ?></a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="tab-pane" id="tab-email">
					<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class="hidden-xs"><?php echo $column_date; ?></th>
							<th class="text-left"><?php echo $column_email_subject; ?></th>
							<th class="text-left"><?php echo $column_email_sender; ?></th>
							<th class="text-right"><?php echo $column_action; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if ($emails) { ?>
					 <?php foreach ($emails as $email) { ?>
						<tr>
							<td class="text-left"><?php echo $email['date_added']; ?></td>
							<td class="text-left"><?php echo $email['subject']; ?></td>
							<td class="text-left"><?php echo $email['sender']; ?></td>
							<td class="d-none" id="mail-<?php echo $email['mail_id']; ?>"><?php echo $email['text']; ?></td>
							<td class="text-right">
								<button type="button" class="btn btn-info" onclick="viewMessage(<?php echo $email['mail_id']; ?>);">
									<i class="fa fa-eye"></i> <?php echo $text_view; ?>
								</button>
							</td>
						</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</div>
				<div class="tab-pane" id="tab-orders">
					<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th><?php echo $column_order; ?></th>
							<th><?php echo $column_status; ?></th>
							<th class="hidden-xs"><?php echo $column_date_added; ?></th>
							<th class="text-right"><?php echo $column_total; ?></th>
							<th class="text-right"><?php echo $column_action; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if ($orders) { ?>
					<?php foreach ($orders as $order) { ?>
						<tr>
							<td class="left"><?php echo $order['order_id']; ?></td>
							<td class="left"><?php echo $order['status']; ?></td>
							<td class="left"><?php echo $order['date']; ?></td>
							<td class="text-right hidden-xs"><?php echo $order['total']; ?></td>
							<td class="text-right"><?php foreach ($order['action'] as $action) { ?>
							  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
							<?php } ?></td>
						</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</div>
				<div class="tab-pane" id="tab-recepciones">
					<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th><?php echo $column_order; ?></th>
							<th><?php echo $column_status; ?></th>
							<th class="hidden-xs"><?php echo $column_date_added; ?></th>
							<th class="text-right"><?php echo $column_total; ?></th>
							<th class="text-right"><?php echo $column_action; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php if ($receptions) { ?>
					<?php foreach ($receptions as $order) { ?>
						<tr>
							<td class="left"><?php echo $order['order_id']; ?></td>
							<td class="left"><?php echo $order['status']; ?></td>
							<td class="left"><?php echo $order['date']; ?></td>
							<td class="text-right hidden-xs"><?php echo $order['total']; ?></td>
							<td class="text-right"><?php foreach ($order['action'] as $action) { ?>
							  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
							<?php } ?></td>
						</tr>
						<?php } ?>
					<?php } else { ?>
						<tr>
							<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				</div>
				<div class="tab-pane" id="tab-products">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_product_id; ?></th>
								<th><?php echo $column_product_name; ?></th>
								<th class="text-left"><?php echo $column_invoice; ?></th>
								<th class="text-left"><?php echo $column_invoice_date; ?></th>
								<th class="text-left"><?php echo $column_quantity; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($products) { ?>
							<?php foreach ($products as $product) { ?>
							<tr>
								<td><?php echo $product['product_id']; ?></td>
								<td><?php echo $product['name']; ?></td>
								<td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['invoice_id']; ?></a></td>
								<td class="text-left"><?php echo $product['date']; ?></td>
								<td class="text-left"><?php echo $product['quantity']; ?></td>
								<td class="text-right"><?php echo $product['total']; ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr><td colspan="6" class="text-center"><?php echo $text_no_results; ?></td></tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab-invoices">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th><?php echo $column_invoice; ?></th>
								<th class="hidden-xs"><?php echo $column_date_added; ?></th>
								<th class="text-right"><?php echo $column_total; ?></th>
								<th class="text-right"><?php echo $column_action; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ($invoices) { ?>
						<?php foreach ($invoices as $invoice) { ?>
							<tr>
								<td class="left"><?php echo $invoice['invoice_id']; ?></td>
								<td class="left"><?php echo $invoice['date']; ?></td>
								<td class="text-right hidden-xs"><?php echo $invoice['total']; ?></td>
								<td class="text-right"><?php foreach ($invoice['action'] as $action) { ?>
									  <a class="btn btn-default" href="<?php echo $action['href']; ?>"><i class="fa fa-edit"></i> <?php echo $action['text']; ?></a>
								<?php } ?></td>
							</tr>
							<?php } ?>
						<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- MessagePopUp -->
<div id="MessagePopUp" class="modal fade" tabindex="-1" role="dialog">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body"><textarea readonly class="form-control-plaintext" id="message" rows="30"></textarea></div>
	</div>
</div>
</div>
<!-- Modal -->
<div id="EmailModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	<?php if (isset($error_server)) { ?>
		<center><span class="label label-danger"><?php echo $error_server; ?></span>
	<?php } ?>
        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="formEmail">
            <div class="form-group row">
              <label for="to" class="control-label col-sm-3"><?php echo $text_to ?></label>
              <div class="col-sm-9">
                <input type="email" name="to" id="to" class="form-control" value="<?php echo $to; ?>">
								<span class="text-danger" id="error-to"></span>
              </div>
            </div>
        <div class="form-group row">
          <label class="control-label col-sm-3" for="subject"><?php echo $text_subject ?></label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="subject" name="subject">
						<span class="text-danger" id="error-subject"></span>
          </div>
          </div>
          <div class="form-group row">
            <label for="message" class="control-label col-sm-3"><?php echo $text_message ?></label>
            <div class="col-sm-9"><textarea name="message" class="ckeditor form-control" spellcheck="false" id="message"></textarea>
						<span class="text-danger" id="error-message"></span></div>
          </div>
          <div class="form-group row">
          	<label class="control-label col-sm-3">Attachment:</label>
          	<div class="control-field col-sm-9">
          		<div class="input-group">
          			<span class="input-group-btn">
          				<button type="button" id="button-upload" class="btn btn-info">
          					<i class="fa fa-upload"></i> Upload
          				</button>
          			</span>
          			<input type="hidden" name="filename" id="input-filename" class="form-control">
          			<input type="text" name="mask" id="mask"class="form-control">
          		</div>
          	</div>
          </div>
         </form>
      </div>
      <div class="modal-footer">
			<button type="button" id="send" class="btn btn-default"> <?php echo $button_send; ?></button>
         	<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$('#supplier-country').on('change', function() {
	var $this = $(this);

	$.ajax({
		url: 'index.php?route=localisation/country/autocomplete&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$this.after($('<i>', {class: 'fas fa-spinner'}));
		},
		complete: function() {
			$('.fas.fa-spinner').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#supplier-postcode-required').show();
			} else {
				$('#supplier-postcode-required').hide();
			}

			var html = '<option value=""><?php echo $text_select; ?></option>';

			if (typeof(json['zone']) != 'undefined' && json['zone'] != '') {
				for (var i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == <?php echo (int)$zone_id; ?>) {
						html += ' selected=""';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name="zone_id"]').html(html);
		}
	});
});
$('#supplier-country').change();
</script>
<script>
$('#button-web').click(function(){
	if ($('#web').val().length > 0) {
		window.open('https:/'+$('#web').val(), '_blank');
	} else {
		alert('<?php echo $error_web; ?>');
	}
});
</script>
<script>
$(document).ready(function() {
	$('#input-company').trigger('focus');
});

document.getElementById('EmailModal').addEventListener('shown.bs.modal', function () {
	$('#subject').trigger('focus');
});
</script>
<script>
$('#send').on('click',function(e){
	var to = $('#to').val();
	var subject = $('#subject').val();

	var editor = CKEDITOR.instances.message;
	var message = editor.getData();

	var filename = $('#input-filename').val();

	$.ajax({
		url:'index.php?route=purchase/supplier/new_email&token=<?php echo $token; ?>&supplier_id=<?php echo $supplier_id; ?>',
		type:'post',
		dataType:'json',
		data:'to='+encodeURIComponent(to)+'&subject='+encodeURIComponent(subject)+'&message='+encodeURIComponent(message)+'&filename='+encodeURIComponent(filename),
		beforeSend:function(){
			$('#send').button('loading');
			$('#send').append($('<i>', {class:'icon-loading'}));
		},
		success:function(json){
			$('#send').button('reset');
			if(json['error']){
				if(json['error']['to']){ $('#error-to').html(json['error']['to']); }
				if(json['error']['subject']){ $('#error-subject').html(json['error']['subject']); }
				if(json['error']['message']){ $('#error-message').html(json['error']['message']); }
				if(json['error']['permission']) {
					var emailModalInstanceErr = bootstrap.Modal.getInstance(document.getElementById('EmailModal'));
					if (emailModalInstanceErr) {
						emailModalInstanceErr.hide();
					}
					alertMessage('danger', json['error']['permission']);
				}
			}
			if(json['success']){
				var emailModalInstance = bootstrap.Modal.getInstance(document.getElementById('EmailModal'));
				if (emailModalInstance) {
					emailModalInstance.hide();
				}
				alertMessage('success',json['success']);
			}
		}
	});
});
</script>
<script>
function viewMessage(mail_id) {
	$('#message').html($('#mail-'+mail_id).text());
	bootstrap.Modal.getOrCreateInstance(document.getElementById('MessagePopUp')).show();
}
</script>
<?php echo $footer; ?>
