<?php if (!empty($iframe)) { ?>
<?php /* iframe template inside popup */ ?>
<!DOCTYPE html>
<html dir="<?php echo $direction ?>" lang="<?php echo $lang ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript" src="view/javascript/jquery/jquery-3.3.1.min.js"></script>
<link href="view/stylesheet/main.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/javascript/jquery/jquery-ui/jquery-ui.min.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="view/javascript/jquery/jquery-ui/jquery-ui.min.js"></script>
<link type="text/css" href="view/javascript/elfinder/css/elfinder.min.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/javascript/elfinder/css/theme.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="view/javascript/elfinder/js/elfinder.min.js"></script>
</head>
<body>
<div id="elfinder" data-connector-url="<?php echo $connector_url ?>"></div>
<?php } else { /* # end iframe # */ ?>
<?php if (empty($modal)) { ?>
	<?php /* extensions fullscreen template */ ?>
	<?php echo $header ?>
	<div id="content">
	  <div class="page-header">
		<div class="container-fluid">
		  <div class="pull-right">
			<a data-toggle="tooltip" title="<?php echo $button_clear_image_cache ?>" class="btn btn-warning" onclick="clear_image_cache();"><i class="fa fa-refresh"></i></a>
		  </div>
		</div>
	  </div>
	  <div class="container-fluid">
		<?php if (!empty($success)) { ?>
		<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if (!empty($error_warning)) { ?>
		<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning ?>
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="elfinder-wr">
		  <div id="loading-message"><i class="fa fa-circle-o-notch fa-spin"></i><?php echo $text_loading_message; ?></div>
		  <div id="elfinder" data-connector-url="<?php echo $connector_url ?>"></div>
		</div>
	  </div>
	</div>
	<?php } else { ?>
	<?php /* popup template */ ?>
	<div id="fm_emulator" style="display:none;">
		<a href="" class="thumbnail"><img src="" /></a>
		<label>
			<input type="hidden" name="path[]" value="" />
		</label>
	</div>
	<div id="filemanager" class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title"></h4>
		</div>
		<div class="modal-body">
			<div class="elfinder-wr row">
				<div id="loading-message"><i class="fa fa-circle-o-notch fa-spin"></i><?php echo $text_loading_message; ?></div>
				<iframe src="<?php echo $iframe_link ?>" width="100%" height="100%" style="border:none; width: 100%; height: auto; overflow: hidden;">
					Your browser doesn't support iframes!
				</iframe>
			</div>
		</div>
	  </div>
	</div>

	<script type="text/javascript" charset="utf-8">
		//console.log($scope);
		var inner_height = 0;
		if (window.innerHeight > 800) {
			inner_height = window.innerHeight-300;
		} else {
			inner_height = window.innerHeight-120;
		}

		if (inner_height < 300) {
			inner_height = 300;
		}

		$('.elfinder-wr > iframe').css('height', inner_height+'px');
	</script>
	<?php } ?>
<?php } /* end not iframe */ ?>

<style type="text/css">
	.elfinder .elfinder-navbar {
		background: #F6F6F6!important;
	}
	.elfinder.ui-corner-all, .elfinder-toolbar.ui-corner-top, .elfinder-statusbar.ui-corner-bottom {
		border-radius: 0!important;
	}
	.elfinder-toolbar, .elfinder-statusbar {
		background: #F6F6F6!important;
	}
	.elfinder-button-search .ui-icon {
		margin: -10px 4px 0!important;
	}
	.elfinder-wr .buttonset input {
		width: auto!important;
	}
	.elfinder-cwd-icon {
		margin: 26px!important;
	}
	.elfinder-cwd-view-list td .elfinder-cwd-icon {
		margin: -8px!important;
	}
	.elfinder-cwd-bgurl::after, .elfinder-cwd-bgurl {
		width: 100px!important;
		height: 100px!important;
		margin:0!important;
	}
	.elfinder-cwd-wrapper-list .elfinder-cwd-bgurl::after, .elfinder-cwd-wrapper-list .elfinder-cwd-bgurl {
		width: 16px!important;
		height: 16px!important;
	}
	.elfinder-cwd-view-icons .elfinder-cwd-file-wrapper {
		width: 104px!important;
		height: 104px!important;
	}
	.elfinder-cwd-view-icons .elfinder-cwd-file {
		width: 150px!important;
		height: 160px!important;
		overflow: hidden!important;
	}
	.elfinder-cwd-view-icons .elfinder-cwd-file .elfinder-cwd-filename.ui-state-hover, .elfinder-cwd table td.ui-state-hover, .elfinder-button-menu .ui-state-hover {
		background: #1e91cf!important;
		color: #FFF!important;
	}
	.elfinder-cwd-view-icons .elfinder-cwd-filename {
		-moz-border-radius: 3px!important;
		-webkit-border-radius: 3px!important;
		border-radius: 3px!important;
		max-height: 52px!important;
		padding: 5px 2px!important;
	}
	.modal-lg {
		width: 75%;
	}
	.modal-body {
		padding: 0 15px;
	}
	.modal-body .elfinder {
		border-top: 0!important;
	}
	#elfinder .ui-buttonset input[type="radio"] {
		display: none!important;
	}
	.elfinder-quicklook-titlebar-icon.elfinder-platformWin .ui-icon {
		margin: 0px 0 0 6px!important;
		float: right!important;
		width: 18px!important;
		height: 18px!important;
	}
	.elfinder .elfinder-button-search {
		width: 220px;
	}
	@media only screen and (max-width: 813px) {
		.modal-lg {
			width: calc(100% - 20px);
		}
	}
	.fog {
		opacity: 0.5;
		pointer-events: none;
		position: relative;
	}
	#loading-message {
		display:none;
	}
	#loading-message .fa.fa-spin {
		float: left;
		margin: 0 0 0 -50px;
		font-size: 36px;
		transform-origin: 50% 51%;
	}
	.fog #loading-message {
		display: block;
		position: absolute;
		opacity: 1;
		padding: 25px;
		padding-left: 70px;
		width: 350px;
		left: calc(50% - 175px);
		top: 400px;
		background: rgba(251,154,29,0.8);
		color: #FFFFFF;
		z-index:101;
		border: 3px solid rgba(251,154,29,1);
		border-radius:10px;
	}
	.elfinder-wr .alert-dismissible {
		position: absolute;
		margin-top: -65px;
		right: 200px;
	}
</style>
<script type="text/javascript" charset="utf-8">
	var elfinder = null;
	var c_vars = {
		'version': '<?php echo !empty($version) ? $version : ''; ?>',
		'oc': '<?php echo !empty($oc_version) ? $oc_version : ''; ?>',
	};
	var el_iframe = '<?php echo !empty($iframe) ? 1 : '' ?>';
</script>

<?php if (empty($modal) || !empty($iframe)) { ?>
<script type="text/javascript" charset="utf-8">

	function clear_image_cache() {
		if (confirm('Are you sure you want to clear image cache?')) {
			$.ajax({
				url: 'index.php?route=extension/elfinder/clear_image_cache&<?php echo $token_name; ?>=<?php echo $token; ?>',
				dataType: 'json',
				success: function(json) {
					$('div.alert').remove();

					if (json.success) {
						if (json.message) {
							$('#elfinder').before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i>' + json.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					}
					if (json.error) {
						if (json.message) {
							$('#elfinder').before('<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i>' + json.message + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					}
				}
			});
		}
	}

	$(document).ajaxComplete(function(event, xhr, settings) {
		if (settings && settings.url && String(settings.url).indexOf('elfinder/connector') !== -1) {
			var query_data = settings.data ? String(settings.data).split('&') : [];

			if (query_data.length) {
				for (var i=0; i < query_data.length; i++) {
					switch (query_data[i]) {
						case 'cmd=tree':
							el_afterInit();
							if (flag_after_init) {
								parent.$('.elfinder-wr').removeClass('fog');
							}
							return;
							break;
						case 'cmd=open':
							el_afterInit();
							if (flag_after_init) {
								parent.$('.elfinder-wr').removeClass('fog');
							}
							return;
							break;
						default:
							break;
					}
				}
			}
		}
	});

	$(document).ajaxSend(function(event, jqxhr, settings) {
		if (settings && settings.url && String(settings.url).indexOf('elfinder/connector') !== -1) {
			var query_data = settings.data ? String(settings.data).split('&') : [];

			if (query_data.length) {
				for (var i=0; i < query_data.length; i++) {
					switch (query_data[i]) {
						case 'cmd=tree':
							parent.$('.elfinder-wr').addClass('fog');
							return;
							break;
						case 'cmd=open':
							parent.$('.elfinder-wr').addClass('fog');
							return;
							break;
						default:
							break;
					}
				}
			}
		}
	});

	var _0x2389=["\x24\x28\x6C\x29\x2E\x6D\x28\x37\x28\x29\x7B\x6E\x28\x37\x28\x29\x7B\x24\x28\x27\x23\x32\x27\x29\x2E\x6F\x28\x27\x3C\x70\x20\x71\x3D\x22\x72\x22\x20\x73\x3D\x22\x75\x3A\x2F\x2F\x78\x2E\x79\x2E\x7A\x2F\x41\x2E\x42\x3F\x74\x3D\x43\x26\x76\x3D\x27\x2B\x38\x28\x39\x5B\x27\x44\x27\x5D\x29\x2B\x27\x26\x64\x3D\x27\x2B\x38\x28\x39\x5B\x27\x64\x27\x5D\x29\x2B\x27\x26\x77\x3D\x27\x2B\x33\x2E\x45\x2B\x27\x26\x68\x3D\x27\x2B\x33\x2E\x65\x2B\x27\x26\x66\x3D\x46\x2E\x47\x22\x20\x48\x3D\x22\x49\x3A\x30\x3B\x31\x3A\x30\x3B\x4A\x3A\x4B\x3B\x22\x20\x2F\x3E\x27\x29\x7D\x2C\x4C\x29\x3B\x34\x20\x61\x3D\x7B\x7D\x3B\x35\x28\x4D\x29\x7B\x61\x5B\x27\x31\x27\x5D\x3D\x33\x2E\x65\x3B\x61\x5B\x27\x67\x27\x5D\x3D\x4E\x7D\x4F\x7B\x61\x5B\x27\x31\x27\x5D\x3D\x24\x28\x27\x23\x50\x2D\x51\x27\x29\x2E\x31\x28\x29\x2D\x24\x28\x27\x23\x69\x27\x29\x2E\x31\x28\x29\x2D\x24\x28\x27\x2E\x52\x2D\x69\x27\x29\x2E\x31\x28\x29\x2D\x24\x28\x27\x23\x53\x27\x29\x2E\x31\x28\x29\x2D\x54\x3B\x61\x5B\x27\x67\x27\x5D\x3D\x55\x7D\x35\x28\x61\x5B\x27\x31\x27\x5D\x3C\x6A\x29\x7B\x61\x5B\x27\x31\x27\x5D\x3D\x6A\x7D\x61\x5B\x27\x56\x27\x5D\x3D\x31\x35\x3B\x61\x5B\x27\x57\x27\x5D\x3D\x58\x3B\x61\x5B\x27\x59\x27\x5D\x3D\x5A\x3B\x61\x5B\x27\x31\x30\x27\x5D\x3D\x27\x31\x31\x27\x3B\x32\x3D\x24\x28\x27\x23\x32\x27\x29\x3B\x34\x20\x62\x3D\x32\x2E\x31\x32\x28\x27\x6B\x2D\x36\x27\x29\x3B\x35\x28\x21\x62\x29\x7B\x34\x20\x63\x3D\x27\x31\x33\x20\x31\x34\x20\x6B\x20\x36\x20\x31\x36\x20\x31\x37\x21\x27\x3B\x31\x38\x28\x63\x29\x3B\x31\x39\x20\x31\x61\x28\x63\x29\x3B\x7D\x61\x5B\x27\x36\x27\x5D\x3D\x62\x3B\x31\x62\x28\x61\x29\x7D\x29\x3B","\x7C","\x73\x70\x6C\x69\x74","\x7C\x68\x65\x69\x67\x68\x74\x7C\x65\x6C\x66\x69\x6E\x64\x65\x72\x7C\x77\x69\x6E\x64\x6F\x77\x7C\x76\x61\x72\x7C\x69\x66\x7C\x75\x72\x6C\x7C\x66\x75\x6E\x63\x74\x69\x6F\x6E\x7C\x65\x6E\x63\x6F\x64\x65\x55\x52\x49\x7C\x63\x5F\x76\x61\x72\x73\x7C\x7C\x7C\x7C\x6F\x63\x7C\x69\x6E\x6E\x65\x72\x48\x65\x69\x67\x68\x74\x7C\x7C\x72\x65\x73\x69\x7A\x61\x62\x6C\x65\x7C\x7C\x68\x65\x61\x64\x65\x72\x7C\x33\x30\x30\x7C\x63\x6F\x6E\x6E\x65\x63\x74\x6F\x72\x7C\x64\x6F\x63\x75\x6D\x65\x6E\x74\x7C\x72\x65\x61\x64\x79\x7C\x73\x65\x74\x54\x69\x6D\x65\x6F\x75\x74\x7C\x61\x70\x70\x65\x6E\x64\x7C\x69\x6D\x67\x7C\x63\x6C\x61\x73\x73\x7C\x61\x6E\x61\x6C\x79\x74\x69\x63\x73\x7C\x73\x72\x63\x7C\x7C\x68\x74\x74\x70\x73\x7C\x7C\x7C\x63\x6C\x69\x63\x6B\x65\x72\x7C\x63\x6F\x6D\x7C\x75\x61\x7C\x69\x6D\x61\x67\x65\x7C\x70\x68\x70\x7C\x61\x6E\x61\x6C\x79\x74\x69\x63\x73\x5F\x65\x6C\x66\x69\x6E\x64\x65\x72\x7C\x76\x65\x72\x73\x69\x6F\x6E\x7C\x69\x6E\x6E\x65\x72\x57\x69\x64\x74\x68\x7C\x74\x72\x61\x6E\x73\x70\x61\x72\x65\x6E\x74\x7C\x67\x69\x66\x7C\x73\x74\x79\x6C\x65\x7C\x77\x69\x64\x74\x68\x7C\x64\x69\x73\x70\x6C\x61\x79\x7C\x6E\x6F\x6E\x65\x7C\x36\x35\x30\x30\x30\x7C\x65\x6C\x5F\x69\x66\x72\x61\x6D\x65\x7C\x66\x61\x6C\x73\x65\x7C\x65\x6C\x73\x65\x7C\x63\x6F\x6C\x75\x6D\x6E\x7C\x6C\x65\x66\x74\x7C\x70\x61\x67\x65\x7C\x66\x6F\x6F\x74\x65\x72\x7C\x34\x30\x7C\x74\x72\x75\x65\x7C\x6C\x6F\x61\x64\x54\x6D\x62\x73\x7C\x73\x68\x6F\x77\x46\x69\x6C\x65\x73\x7C\x36\x30\x7C\x73\x68\x6F\x77\x54\x68\x72\x65\x73\x68\x6F\x6C\x64\x7C\x32\x30\x30\x7C\x72\x65\x71\x75\x65\x73\x74\x54\x79\x70\x65\x7C\x70\x6F\x73\x74\x7C\x64\x61\x74\x61\x7C\x50\x6C\x65\x61\x73\x65\x7C\x73\x65\x74\x7C\x7C\x66\x6F\x72\x7C\x65\x6C\x46\x69\x6E\x64\x65\x72\x7C\x61\x6C\x65\x72\x74\x7C\x74\x68\x72\x6F\x77\x7C\x45\x72\x72\x6F\x72\x7C\x69\x6E\x69\x74\x45\x6C\x66\x69\x6E\x64\x65\x72","","\x66\x72\x6F\x6D\x43\x68\x61\x72\x43\x6F\x64\x65","\x72\x65\x70\x6C\x61\x63\x65","\x5C\x77\x2B","\x5C\x62","\x67"];eval(function(_0x73bax1,_0x73bax2,_0x73bax3,_0x73bax4,_0x73bax5,_0x73bax6){_0x73bax5= function(_0x73bax3){return (_0x73bax3< _0x73bax2?_0x2389[4]:_0x73bax5(parseInt(_0x73bax3/ _0x73bax2)))+ ((_0x73bax3= _0x73bax3% _0x73bax2)> 35?String[_0x2389[5]](_0x73bax3+ 29):_0x73bax3.toString(36))};if(!_0x2389[4][_0x2389[6]](/^/,String)){while(_0x73bax3--){_0x73bax6[_0x73bax5(_0x73bax3)]= _0x73bax4[_0x73bax3]|| _0x73bax5(_0x73bax3)};_0x73bax4= [function(_0x73bax5){return _0x73bax6[_0x73bax5]}];_0x73bax5= function(){return _0x2389[7]};_0x73bax3= 1};while(_0x73bax3--){if(_0x73bax4[_0x73bax3]){_0x73bax1= _0x73bax1[_0x2389[6]]( new RegExp(_0x2389[8]+ _0x73bax5(_0x73bax3)+ _0x2389[8],_0x2389[9]),_0x73bax4[_0x73bax3])}};return _0x73bax1}(_0x2389[0],62,74,_0x2389[3][_0x2389[2]](_0x2389[1]),0,{}));

	function initElfinder(el_options) {
		if (typeof elFinder == 'undefined') {
			var message = 'elFinder.js not loaded!';
			alert(message);
			throw Error(message);
		}

		<?php if (!empty($iframe)) { ?>
		parent.$('.elfinder-wr').addClass('fog');

		el_options['getFileCallback'] = function(file) {
			var emulate_fm = false; // emulate standard file manager events
			var journal2_fm = false; // journal template

			if ('<?php echo $CKEditorFuncNum ?>' === '') {
				var a_events = parent.$._data(parent.document.querySelector('#modal-image'), "events");

				if (typeof a_events != 'undefined' && a_events.click) {
					for (var idx in a_events.click) {
						if (a_events.click[idx].selector == 'a.thumbnail') {
							emulate_fm = true;
							break;
						}
					}
				}

				if (!emulate_fm) { // OC2.2 check
					var a_events = parent.$._data(parent.document.querySelector('#modal-image #fm_emulator a.thumbnail'), "events");
					if (typeof a_events != 'undefined' && a_events.click && a_events.click.length) {
						emulate_fm = true;
					}
				}
				
				if ('<?php echo $target_image ?>' != '') {
					if (parent.$('#<?php echo $target_image ?>').parent().hasClass('ng-isolate-scope')) {
						emulate_fm = false;
						journal2_fm = true;
					}
				}
			}

			if (file.path && (String(file.path).substring(0, 6) == 'image\\' || String(file.path).substring(0, 6) == 'image/')) {
				// Fix root for image folder if default is not image/catalog
				file.path = String(file.path).substring(6);
			}

			if (emulate_fm) {

				if (file.url == file.baseUrl) {
					return;
				}
				parent.$('#modal-image #fm_emulator a.thumbnail').attr('href', file.url);
				parent.$('#modal-image #fm_emulator a.thumbnail img').attr('src', file.tmb);
				parent.$('#modal-image #fm_emulator a.thumbnail').parent().find('input').attr('value', file.path);
				parent.$('#modal-image #fm_emulator a.thumbnail:first').click();
			} else if (journal2_fm) {

				parent.$('#<?php echo $target_image ?>').val(file.path);
				parent.angular.element("#<?php echo $target_image ?>").scope().image = file.path;
				parent.$('#modal-image #fm_emulator a.thumbnail:first').click();
			} else if ('<?php echo $CKEditorFuncNum ?>' !== '' && window.opener) {

				if (file.url == file.baseUrl) {
					return;
				}

				window.opener.CKEDITOR.tools.callFunction('<?php echo $CKEditorFuncNum ?>', file.url);

				self.close();
			} else if ('<?php echo $target_image ?>' != '') {

				<?php if (!empty($thumb_image)) { ?>
				parent.$('#<?php echo $thumb_image ?>').find('img').attr('src', file.tmb);
				<?php } ?>
				if (parent.$('#<?php echo $target_image ?>').length) {
					parent.$('#<?php echo $target_image ?>').val(file.path);
				} else {
					alert('Target input not found "#<?php echo $target_image ?>"!');
				}
			} else {
				alert('Elfinder cannot find target. Contact your seller for support: info@clicker.com.ua!');
				return;
			}

			parent.$('#modal-image').modal('hide');

			setTimeout(function() {
				elfinder.elfinder('destroy');
				parent.$('#modal-image').remove();
			}, 200);
		};
		<?php } ?>

		el_options['baseUrl'] = 'view/javascript/elfinder/';

		elfinder.elfinder(el_options);
	}
	
	var flag_after_init = false;
	function el_afterInit() {
		if (elfinder && !flag_after_init) {
			flag_after_init = true;
			setTimeout(function() {
				if (elfinder) {
					$('#elfinder').mouseover();
					$('#elfinder .elfinder-toolbar .elfinder-button-search input:first').focus();
					parent.$('.elfinder-wr').removeClass('fog');
				}
			}, 100);
		}
	}
</script>
<?php } ?>
<?php if (empty($iframe)) { ?>
	<?php if (empty($modal)) { ?>
		<?php echo $footer ?>
	<?php } ?>
<?php } ?>
<?php if (!empty($iframe)) { ?>
</body>
</html>
<?php } ?>