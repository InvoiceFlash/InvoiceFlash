<?php echo $header; ?>
	
<div class="container">
	
    <div class="container-fluid">
	<div class="row">

		<div class="offset-sm-4 col-sm-4">
			<br>
				<div class="mx-auto text-center">
					<a href="<?php echo $home; ?>" ><img src="../image/Invoice_Flash.png"  alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="logo"></a>
				</div>	
				<br>
			<noscript>
				<div class="alert alert-danger">
					Javascript is disabled in your web browser. Please, to view this site correctly,<br />
					<b><i>enable javascript</i></b>.<br />
				</div>
			</noscript>				
			
			<?php if ($success) { ?>
			<div class="alert alert-success"><?php echo $success; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
			<?php } ?>
			<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><?php echo $error_warning; ?><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>
			<?php } ?>
			<form action="<?php echo $action; ?>" class="form-signin" method="post" enctype="multipart/form-data" autocomplete="off" id="form">
			<div class="well well-lg">
				<p class="lead"><?php echo $text_login; ?></p>
				<div class="form-group">
					<div class="input-icon">
						<i class="fa fa-envelope"></i>
						<input type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" class="form-control" autofocus="">
					</div>
				</div>
				<div class="form-group">
					<div class="input-icon">
						<i class="fa fa-unlock-alt"></i>
						<input type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" class="form-control" autofocus="">
					</div>
				</div>
				
				<div class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
			  
				<button type="submit" class="btn btn-primary btn-block"><?php echo $button_login; ?></button>
				<?php if ($redirect) { ?>
				<input type="hidden" name="redirect" value="<?php echo $redirect; ?>">
				<?php } ?>

			</div>
			</form>
		</div>
	</div>
	</div>
</div>
<?php echo $footer; ?>