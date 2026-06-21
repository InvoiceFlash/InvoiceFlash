<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'undo'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="nav-item"><a class="nav-link" href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
		</ul>
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="tab-conten mt-2">
				<div class="tab-pane" id="tab-general">
					<legend><?php echo $text_order_info; ?></legend>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_order_id; ?></label>
						<div class="col-sm-6">
							<input type="text" name="order_id" value="<?php echo $order_id; ?>" class="form-control">
							<?php if ($error_order_id) { ?>
								<span class="text-danger"><?php echo $error_order_id; ?></span>
							<?php } ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_date_ordered; ?></label>
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="form-control date"/>
								<div class="input-group-append">
									<div class="input-group-text"><i class="fas fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_customer; ?></label>
						<div class="col-sm-6">
							<input type="text" name="customer" value="<?php echo $customer; ?>" id="order-customer" autocomplete="off" class="form-control">
							<input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_email; ?></label>
						<div class="col-sm-6">
							<input type="text" name="email" value="<?php echo $email; ?>" class="form-control">
							<?php if ($error_email) { ?>
								<div class="help-block text-danger"><?php echo $error_email; ?></div>
							<?php	} ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_telephone; ?></label>
						<div class="col-sm-6">
							<input type="text" name="telephone" value="<?php echo $telephone; ?>" class="form-control">
							<?php if ($error_telephone) { ?>
								<div class="help-block text-danger"><?php echo $error_telephone; ?></div>
							<?php	} ?>
						</div>
					</div>
					<legend><?php echo $text_product_info; ?></legend>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><b class="required">*</b> <?php echo $entry_product; ?></label>
						<div class="col-sm-6">
							<input type="text" name="product" value="<?php echo $product; ?>" id="return-product" autocomplete="off" class="form-control">
							<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
							<?php if ($error_product) { ?>
								<div class="help-block text-danger"><?php echo $error_product; ?></div>
							<?php	} ?>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_model; ?></label>
						<div class="col-sm-6">
							<input type="text" name="model" value="<?php echo $model; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_quantity; ?></label>
						<div class="col-sm-6">
							<input type="text" name="quantity" value="<?php echo $quantity; ?>" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_reason; ?></label>
						<div class="col-sm-6">
							<select name="return_reason_id" class="form-control">
								<?php foreach ($return_reasons as $return_reason) { ?>
									<?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>
									<option value="<?php echo $return_reason['return_reason_id']; ?>" selected=""><?php echo $return_reason['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $return_reason['return_reason_id']; ?>"><?php echo $return_reason['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_opened; ?></label>
						<div class="col-sm-6">
							<select name="opened" class="form-control">
								<?php if ($opened) { ?>
									<option value="1" selected=""><?php echo $text_opened; ?></option>
									<option value="0"><?php echo $text_unopened; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_opened; ?></option>
									<option value="0" selected=""><?php echo $text_unopened; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_comment; ?></label>
						<div class="col-sm-6">
							<textarea name="comment" class="form-control" rows="3"><?php echo $comment; ?></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_action; ?></label>
						<div class="col-sm-6">
							<select name="return_action_id" class="form-control">
								<option value="0">&ndash;</option>
								<?php foreach ($return_actions as $return_action) { ?>
								<?php if ($return_action['return_action_id'] == $return_action_id) { ?>
									<option value="<?php echo $return_action['return_action_id']; ?>" selected=""> <?php echo $return_action['name']; ?></option>
								<?php } else { ?>
									<option value="<?php echo $return_action['return_action_id']; ?>"><?php echo $return_action['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_return_status; ?></label>
						<div class="col-sm-6">
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
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $footer; ?>