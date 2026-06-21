<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="pull-left h2"><i class="hidden-xs fa fa-envelope"></i> <?php echo $heading_title; ?></div>
		<div class="pull-right">
			<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="panel-body">
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php echo $entry_from ?></label>
				<div class="col-sm-4">
					<input type="text" readonly class="form-control-plaintext" value="<?php echo htmlentities($from); ?>">
				</div>
				<div class="col-sm-2">
					<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#EmailModal">Reply</button>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php echo $entry_subject; ?></label>
				<div class="col-sm-6">
					<input type="text" readonly class="form-control-plaintext" value="<?php echo $subject; ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label"><?php echo $entry_message; ?></label>
				<div class="col-sm-6">
					<p><?php echo $message; ?></p>
				</div>
			</div>
		</div>
</div>

<!-- Modal -->
<div id="EmailModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
				<h4 class="modal-title">Reply Mail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	    <?php
		if (!extension_loaded('imap')) {?>
			<center><span class="label label-danger">Imap library is not installed!!</span></center>
		<?php }		?>
        <form action="<?php echo $reply; ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="formEmail">
					<div class="form-group">
						<label for="to" class="control-label col-2">To:</label>
						<div class="col-sm-10"><input type="text" name="to" id="to" class="form-control" value="<?php echo htmlentities($from); ?>"></div>
					</div>
					<div class="form-group">
						<label for="subject" class="control-label col-2">Subject:</label>
						<div class="col-sm-10"><input type="text" name="subject" id="subject" class="form-control" value="Re: <?php echo $subject; ?>"></div>
					</div>
					<div class="form-group">
						<label for="message" class="control-label col-2">Message:</label>
						<div class="col-sm-10"><textarea name="message" id="message" cols="30" rows="10" class="from-control ckeditor"><?php echo $message; ?></textarea></div>
					</div>
         </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="submit" form="formEmail" id="send"> Send</button>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>