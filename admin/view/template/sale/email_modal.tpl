<!-- Modal -->
<div id="EmailModal" class="modal fade" role="dialog" tabindex='-1'>
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Email</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
	    <?php
		if (!extension_loaded('imap')) {?>
			<center><span class="label label-danger">Imap library is not installed!!</span>
		<?php }		?>
        <form action="<?php echo $sendEmail; ?>" class="form-horizontal" method="post" enctype="multipart/form-data" id="formEmail">
         	  <div class="form-group">
         	  	<label for="to" class="control-label col-sm-2">To:</label>
         	  	<div class="col-sm-10">
         	  		<input type="email" name="to" id="to" class="form-control" value="<?php echo $email; ?>">
         	  	</div>
         	  </div>
			  <div class="form-group">
			    <label class="control-label col-sm-2" for="subject">Subject</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $heading_title ?> #<?php echo $id; ?>">
			    </div>
  			  </div>
  			  <div class="form-group">
  			  	<label for="message" class="control-label col-sm-2">Message:</label>
  			  	<div class="col-sm-10"><textarea name="message" id="message" cols="30" rows="5" class="ckeditor form-control" placeholder="Your message here"></textarea></div>
  			  </div>
         </form>
      </div>
      <div class="form-group">
        <label class="text-center col-sm-12">This message has attached the document in pdf format.</label>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="submit" form="formEmail" id="send"> Send</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#formEmail').on('submit', function(e) {
		var to = $('#to');

		// Check if there is an entered value
		if(!to.val()) {
			alert("Email to is necesary!!");
			
			// Stop submission of the form
			e.preventDefault();
		}

   });
</script>