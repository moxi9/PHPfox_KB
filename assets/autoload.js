
;var simplemde;
function markdownStart(form) {
	form.find('textarea').val(simplemde.value());
}

$Ready(function() {
	if (!$('#markdown-editor').length) {
		return;
	}
	var runInt = function() {
		simplemde = new SimpleMDE( {
			element: $("#markdown-editor textarea")[0]
		} );
		simplemde.render();
	};
	if (typeof(SimpleMDE) != 'object') {
		$.getScript('//cdn.jsdelivr.net/simplemde/latest/simplemde.min.js', runInt);
	}
});