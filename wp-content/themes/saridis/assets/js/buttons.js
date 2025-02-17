(function() {
	tinymce.PluginManager.add('text_color', function( editor, url ) {
		editor.addButton( 'text_color', { 
			//text: '—',
			icon: 'forecolor', 
			title: 'Выделение цветом',
			onclick: function() {
				editor.selection.setContent('<span class="text_color">' + editor.selection.getContent() + '</span>');
			}
		});
	});
})();