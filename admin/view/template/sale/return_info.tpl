<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-undo"></i><?php echo $heading_title; ?></div>
		<div class="pull-right"><a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a></div>
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs"><li class="nav-item"><a class="nav-link active"href="#tab-return" data-toggle="tab"><?php echo $tab_return; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-product" data-toggle="tab"><?php echo $tab_product; ?></a></li><li class="nav-item"><a class="nav-link" href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li></ul>
		<div class="form-horizontal tab-content">
			<div class="tab-pane" id="tab-return">
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<td class="col-sm-3"><?php echo $text_return_id; ?></td>
						<td><?php echo $return_id; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_order_id; ?></td>
						<?php if ($order) { ?>
							<td><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></td>
						<?php } else { ?>
							<td><?php echo $order_id; ?></td>
						<?php } ?>
					</tr>
					<tr>
						<td><?php echo $text_date_ordered; ?></td>
						<td><?php echo $date_ordered; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_customer; ?></td>
						<?php if ($customer) { ?>
							<td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
						<?php } else { ?>
							<td><?php echo $firstname; ?> <?php echo $lastname; ?></td>
						<?php } ?>
					</tr>
					<tr>
						<td><?php echo $text_email; ?></td>
						<td><?php echo $email; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_telephone; ?></td>
						<td><?php echo $telephone; ?></td>
					</tr>
					<?php if ($return_status) { ?>
						<tr>
							<td><?php echo $text_return_status; ?></td>
							<td id="return-status"><?php echo $return_status; ?></td>
						</tr>
					<?php } ?>
					<tr>
						<td><?php echo $text_date_added; ?></td>
						<td><?php echo $date_added; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_date_modified; ?></td>
						<td><?php echo $date_modified; ?></td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="tab-product">
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<td class="col-sm-3"><?php echo $text_product; ?></td>
						<td><?php echo $product; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_model; ?></td>
						<td><?php echo $model; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_quantity; ?></td>
						<td><?php echo $quantity; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_return_reason; ?></td>
						<td><?php echo $return_reason; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_opened; ?></td>
						<td><?php echo $opened; ?></td>
					</tr>
					<tr>
						<td><?php echo $text_return_action; ?></td>
						<td><select name="return_action_id" class="form-control">
							<option value="0">&ndash;</option>
							<?php foreach ($return_actions as $return_action) { ?>
							<?php if ($return_action['return_action_id'] == $return_action_id) { ?>
							<option value="<?php echo $return_action['return_action_id']; ?>" selected=""><?php echo $return_action['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<?php if ($comment) { ?>
					<tr>
						<td><?php echo $text_comment; ?></td>
						<td><?php echo $comment; ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="tab-pane" id="tab-history">
				<div id="history" data-href="index.php?route=sale/return/history&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>"></div>
				<div class="form-group">
					<label class="control-label col-sm-2"><?php echo $entry_return_status; ?></label>
					<div class="control-field col-sm-4">
						<select name="return_status_id" class="form-control">
							<?php foreach ($return_statuses as $return_status) { ?>
							<?php if ($return_status['return_status_id'] == $return_status_id) { ?>
								<option value="<?php echo $return_status['return_status_id']; ?>" selected=""><?php echo $return_status['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"><?php echo $entry_notify; ?></label>
					<div class="control-field col-sm-4">
						<label class="checkbox-inline"><input type="checkbox" name="notify" value="1" id="notify"></label>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"><?php echo $entry_comment; ?></label>
					<div class="control-field col-sm-4">
						<textarea name="comment" class="form-control" rows="3" id="comment"></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="control-field col-sm-4 col-sm-offset-2">
						<button type="button" id="button-history" data-action="return" data-id="<?php echo $return_id; ?>" class="btn btn-info"><i class="fa fa-plus-circle"></i> <?php echo $button_add_history; ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('select[name="return_action_id"]').change(function(){
	var a=$(this);
	$.ajax({
		url:'index.php?route=sale/return/action&token=<?php echo $token; ?>&return_id=<?php echo $return_id; ?>',
		type:'post',
		dataType:'json',
		data:'return_action_id='+a.val(),
		beforeSend:function(){
			a.blur().button('loading').append($('<i>',{class:'icon-loading'}));
		},
		complete:function(){
			a.button('reset');
		},
		success:function(json){
			if(json['error']){
				alertMessage('danger',json['error']);
			}
			if(json['success']){
				alertMessage('success',json['success']);
			}
		}
	});
});
</script>
<?php echo $footer; ?>