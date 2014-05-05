define(
[
	'emberjs'
],
function(Ember) {
	Ember.TextSupport.reopen({
		attributeBindings: ['name', 'required', 'pattern', 'step', 'min', 'max']
	});

	return Ember.TextArea.extend({
		classNames: ['expand'],
		validators: {}
	});
});
