// Custom M12.Foundation code for Neos backend

$(document).ready(function() {

	// By default, after click on LABEL, focus is moved to related INPUT field.
	// Prevent that default action while in edit mode (@see TS-113)
	$('label').children('.neos-inline-editable').click(function(e) {
		e.preventDefault();
	});
});
