var validator_action;
$(function() {
	initOnEvents();
	initValidationComplaintAction();
	
	$('#complaint-action-status-submit').on('click', function(e){
		e.preventDefault();
		validateComplaintAction();
	});
	$('#reporting-complaint-action-status-submit').on('click', function(e){
		e.preventDefault();
		saveReportingCamplaintAction();
	});
});
function initOnEvents(){
    $('#modal_complaint_action_form').on('show.bs.modal', function() {
    	if($('#modal_complaint_action_form input[name=status]').val() == 'CLO'){
				initComplaintUsers($('#modal_complaint_action_form input[name=id]').val());
				initRole();
				$('.displayCloseOptions').show();
		}
    });
    $('#modal_complaint_action_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	validator_action.resetForm();
    	removeErrorPlacement();
		$('.displayCloseOptions').hide();
    });
	$('#modal_reporting_complaint_action_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	// validator_action.resetForm();
    	removeErrorPlacement();
    });
}
function initValidationComplaintAction(){

	validator_action = $("#complaint-action-status-form").validate({
//		focusCleanup: true,
		rules: { 
			
			"reason": { 
				required: true               
			},
//			'complaint_user_id_pk': {
//				required: true
//			},
//			'system_role': {
//				required:true
//			}
			
		},
		messages: {
			
			"reason": { 
				required: "The Remark is Required."               
			},
//			'complaint_user_id_pk': {
//				required: "Primary user is Required."
//			},
//			'system_role': {
//				required: "Primary role is Required."
//			} 
			
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
function validateComplaintAction(){
	if($('#modal_complaint_action_form input[name=status]').val() == 'CLO'){
		saveCamplaintAction();
	}else{
		if($("#complaint-action-status-form").valid()){
			saveCamplaintAction();
		}else{
			return false;
		}
	}
}
function saveCamplaintAction() {
	var formData = $("#complaint-action-status-form").serialize();
	$.ajax({
		url: custom_url + '/'+$('#modal_complaint_action_form input[name=flow]').val()+'/status',
		type: 'PUT',
		data: formData,
		dataType: 'JSON',
		beforeSend:function(){
			$('#complaint-action-status-submit').prop('disabled',true);
		},
		success: function (data) {
			$('#complaint-action-status-submit').prop('disabled',false);
			if($('#modal_complaint_action_form #refresh').val()){
				if($('#modal_complaint_action_form #refresh_type').val() == 'page'){
					alertService({
						title: 'Success !',
						text: "Reference updated successfully.",
						type: 'success',
						redirectUrl: data.redirect_url,
					});
					$('#modal_complaint_action_form').modal('hide');
				}else{
					$('#modal_complaint_action_form').modal('hide');
					alertService({
						title: 'Success !',
						text: "Reference updated successfully.",
						type: 'success'
					});
				}
				refreshTable($('#modal_complaint_action_form #refresh_table').val());
			}
		},
		error: function (data) {
			$('#complaint-action-status-submit').prop('disabled',false);
			var res = data.responseJSON;
			serverErrorHandler(res);
		},
		complete:function(){
		}
	});
}
function saveReportingCamplaintAction() {
	var formData = $("#reporting-complaint-action-status-form").serialize();
	$.ajax({
		url: custom_url + '/'+$('#modal_reporting_complaint_action_form input[name=flow]').val()+'/status',
		type: 'PUT',
		data: formData,
		dataType: 'JSON',
		beforeSend:function(){
			$('#reporting-complaint-action-status-submit').prop('disabled',true);
		},
		success: function (data) {
			$('#reporting-complaint-action-status-submit').prop('disabled',false);
			if($('#modal_reporting_complaint_action_form #refresh').val()){
				if($('#modal_reporting_complaint_action_form #refresh_type').val() == 'page'){
					alertService({
						title: 'Success !',
						text: "Reference updated successfully.",
						type: 'success',
						redirectUrl: data.redirect_url,
					});
					$('#modal_reporting_complaint_action_form').modal('hide');
				}else{
					$('#modal_reporting_complaint_action_form').modal('hide');
					alertService({
						title: 'Success !',
						text: "Reference updated successfully.",
						type: 'success'
					});
				}
				refreshTable($('#modal_reporting_complaint_action_form #refresh_table').val());
			}
		},
		error: function (data) {
			$('#reporting-complaint-action-status-submit').prop('disabled',false);
			var res = data.responseJSON;
			serverErrorHandler(res);
		},
		complete:function(){
		}
	});
}
//function initComplaintUsers(id){
//	var init_selectors = [	
//		{
//			class: '.select-complaint-user-search',
//			url: '/dashboard/complaints/get/users/all',
//			noResults_label: '',
//			display_text_box: '',
//			id: '#complaint_user_id_pk',
//			modal_name: "",
//			noResults_btn: false,
//			autofocus: false,
//			resource_id: id,
//			disabled: false
//		}
//	];
//	
//	$.each(init_selectors, function(index, selector){
//		initSetupSelectorsWithAjax(selector);
//	});	
//}
