
$Ready(function() {
	if (!$('#markdown-editor').length) {
		return;
	}

	var simplemde = new SimpleMDE( {element: $("#markdown-editor textarea")[0]} );
	simplemde.render();
});