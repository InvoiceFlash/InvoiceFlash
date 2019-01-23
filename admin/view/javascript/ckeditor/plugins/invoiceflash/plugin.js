CKEDITOR.plugins.add('invoiceflash', {
	init: function(editor) {
		editor.addCommand('invoiceflash', {
			exec: function(editor) {
				$.ajax({
					url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&ckeditor=' + editor.name,
					dataType: 'html',
					success: function(html) {
						$('body').append(html);

						$('#modal-image').modal('show');
					}
				});
			}
		});

		editor.ui.addButton('invoiceflash', {
			label: 'invoiceflash',
			command: 'invoiceflash',
			icon: this.path + 'images/icon.png'
		});
	}
});