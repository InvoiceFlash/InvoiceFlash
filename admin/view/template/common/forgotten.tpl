<?php echo $header; ?>
<div class="container">
	<div class="container-fluid">
		<div class="row">
			<div class="offset-sm-4 col-sm-4">
				<br><div class="mx-auto text-center">
					<a href="<?php echo $home; ?>" ><img src="../image/Invoice_Flash.png"  alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="logo"></a>
				</div><br>
				<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
					<div class="well well-lg">
						<p class="lead"><?php echo $heading_title; ?></p>
						<?php if ($error_warning) { ?>
						<div class="alert alert-danger"><?php echo $error_warning; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
						<?php } ?>
						<p><?php echo $text_email; ?></p>
						<h5><?php echo $entry_email; ?></h5>
						<input type="text" name="email" value="<?php echo $email; ?>" class="form-control" autofocus="">
						<hr><button class="btn btn-primary"><?php echo $button_reset; ?></button>
						<a class="btn btn-warning" href="<?php echo $cancel; ?>"><i class="fa fa-ban"></i><span class="hidden-xs"> <?php echo $button_cancel; ?></span></a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>