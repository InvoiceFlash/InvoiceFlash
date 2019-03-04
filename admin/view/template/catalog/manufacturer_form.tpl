<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'qrcode'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<ul class="nav nav-tabs"><li class="nav-item"><a class="nav-link active" href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li></ul>
		<div class="tab-content mt-2">
			<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2" for="name"><b class="required">*</b> <?php echo $entry_name; ?></label>
					<div class="col-sm-6">
						<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" id="name" class="form-control">
						<?php if ($error_name) { ?>
						<div class="help-block error"><?php echo $error_name; ?></div>
						<?php } ?>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_store; ?></label>
					<div class="col-sm-6">
						<div class="panel panel-default panel-scrollable">
							<div class="list-group">
								<label class="list-group-item">
									<?php if (in_array(0, $manufacturer_store)) { ?>
									<input type="checkbox" name="manufacturer_store[]" value="0" checked=""><?php echo $text_default; ?>
									<?php } else { ?>
									<input type="checkbox" name="manufacturer_store[]" value="0"><?php echo $text_default; ?>
									<?php } ?>
								</label>
								<?php foreach ($stores as $store) { ?>
								<label class="list-group-item">
									<?php if (in_array($store['store_id'], $manufacturer_store)) { ?>
									<input type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>" checked=""><?php echo $store['name']; ?>
									<?php } else { ?>
									<input type="checkbox" name="manufacturer_store[]" value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?>
									<?php } ?>
								</label>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2"><?php echo $entry_image; ?></label>
					<div class="col-sm-6">
						<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">
						<img src="<?php echo $thumb; ?>" data-placeholder="<?php echo $no_image; ?>"/></a>
						<input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2" for="keyword"><?php echo $entry_keyword; ?></label>
					<div class="col-sm-6">
						<input type="text" name="keyword" value="<?php echo $keyword; ?>" class="form-control" id="keyword" class="form-control">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-10 col-md-2" for="sort_order"><?php echo $entry_sort_order; ?></label>
					<div class="col-sm-6">
						<input type="text" name="sort_order" value="<?php echo $sort_order; ?>" id="sort_order" class="form-control">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php include(DIR_TEMPLATE . 'common/template-modal.tpl'); ?>
<?php echo $footer; ?>