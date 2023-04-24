window.errorPlacement = function(ele_array, error, element_class) {
	var element = $(element_class);
	$(element_class).closest('.form-group').removeClass('has-success').addClass('has-error');
	if ($.inArray(element_class, ele_array) !== -1) {
		$('<label class="error">' + error[0] + '</label>').appendTo(element.parent());
		$(element_class).parent().find('.select2-container .selection .select2-selection').removeClass('has-success').addClass('has-select-error');
	}
	else if(element_class == '.file-error'){
		console.log(element_class,element);
		$(element).parent().find('.filename').addClass('has-file-error');
		$('<label class="error">' + error[0] + '</label>').appendTo(element.parent().parent());
	}
	else {
		var placement = element.closest('.input-group');
		if (!placement.get(0)) {
			placement = element;
		}
		if (error[0] !== '') {
			placement.after('<label class="error">' + error[0] + '</label>');
		}
	}
}


window.removeErrorPlacement = function(element) {
	if (element) {
		$(element).closest('.form-group').removeClass('has-error');
		$(element).parent().find('.select2-container .selection .select2-selection').removeClass('has-select-error');
		$(element).parent().find('.error').remove();
		$(element).parent().find('.filename').removeClass('has-file-error');
		$(element).parent().parent().find('.error').remove();
		return true;
	}
	$('.error-placement').closest('.form-group').removeClass('has-error');
	$('.error-placement').parent().find('.select2-container .selection .select2-selection').removeClass('has-select-error');
	$('.error-placement').parent().find('.filename').removeClass('has-file-error');
	$('.error-placement').parent().find('.error').remove();
	$('.error-placement').parent().parent().find('.error').remove();
}
