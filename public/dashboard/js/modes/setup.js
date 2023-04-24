var validator;
$(function() {
	initModeStatus();
	initcolorPicker();
	initValidationMode();
	eventHandler();
	initOnEvents();
	$('#mode-setup-submit').on('click', function(){
		saveMode();
	});
});

function initOnEvents(){
    $('#modal_mode_form').on('show.bs.modal', function() {
    	checkModeEdit();
    });
    $('#modal_mode_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	validator.resetForm();
    	removeErrorPlacement();
    	
    	var selects = ['#mode_status'];
		$.each(selects, function(index, element){
			$(element).trigger({
				type: 'select2:unselect'
			});
			destroySelect2(element);
		});
		initModeStatus();
    });
    
    var selects = ['#mode_status'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
		});
	});
    
}

function initcolorPicker(){
//	const pickr1 = new Pickr({
//		  el: '#color-picker-1',
//		  default: "303030",
//		  components: {
//		    preview: true,
//		    opacity: true,
//		    hue: true,
//
//		    interaction: {
//		      hex: true,
//		      rgba: true,
//		      hsla: true,
//		      hsva: true,
//		      cmyk: true,
//		      input: true,
//		      clear: true,
//		      save: true
//		    }
//		  }
//		});
	$('#mode-color-picker').colorpicker();
}

function checkModeEdit(){
	if($('#modal_mode_form #mode').val() == 'edit'){
		var params = {'id' : $('#modal_mode_form #complaint_mode_id_pk').val() };
		$.ajax({
			url: custom_url + '/dashboard/modes/getById/'+ encodeURIComponent($.param(params)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setModeEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}
}

function setModeEditData(data){

	$('#mode_name').val(data.name);
	$('#mode_code').val(data.code);
	$('#mode_status').val(data.status).trigger('change');
	$('#mode-color-picker').val(data.color);
	$('#mode_status').trigger({
	    type: 'select2:select'
	   
	});
}

function initValidationMode(){

	validator = $("#mode-setup-form").validate({
//		focusCleanup: true,
		rules: { 
			"name": { 
				required: true               
			},
			"code": {
				required: true
			},
			"status": { 
				required: true               
			},
			"color": { 
				required: true               
			}
		},
		messages: {
			"name": { 
				required: "Name is Required"               
			},
			"code": {
				required: "Code is Required"
			},
			"status": { 
				required: "Status is Required"               
			},
			"color": { 
				required: "Color is Required"               
			}
			
		},
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
			if($(label).is('select')){
				$(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-success').addClass('has-select-error');
			}
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			if($(label).is('select')){
				$(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-select-error');
			}
			label.remove();
		},
		errorPlacement: function( error, element ) {
			if(element.hasClass('select2-hidden-accessible')){
				error.appendTo( element.parent() );
			}
			else {
				var placement = element.closest('.input-group');
				if (!placement.get(0)) {
					placement = element;
				}
				if (error.text() !== '') {
					placement.after(error);
				}
			}
		},
		submitHandler: function(form) {
		}
	});
}
function saveMode() {
	if($("#mode-setup-form").valid()){
		
		var formData = $("#mode-setup-form").serialize();
		$.ajax({
			url: custom_url + '/dashboard/modes/save',
			type: 'POST',
			data: formData,
			dataType: 'JSON',
			beforeSend:function(){
				$('#mode-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#mode-setup-submit').prop('disabled',false);
				if($('#modal_mode_form #refresh').val()){
					refreshTable($('#modal_mode_form #refresh_table').val());
				}
				$('#modal_mode_form').modal('hide');
				alertService({
					title: 'Success !',
					text: "Mode saved successfully.",
					type: 'success',
				});
			},
			error: function (data) {
				$('#mode-setup-submit').prop('disabled',false);
				var res = data.responseJSON;
				serverErrorHandler(res);
			},
			complete:function(){
			}
		});
	}else{
		return false;
	}
}
function eventHandler() {
	var selects = ['#mode_status'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			
		  });
	});
	
	$('#active-mode').on('click', function(){
		$('#active-mode').hide();
		$('#inactive-mode').show();
	});
	
	$('#inactive-mode').on('click', function(){
		$('#inactive-mode').hide();
		$('#active-mode').show();
	});
	
	
}



