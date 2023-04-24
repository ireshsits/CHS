var validator_amendment;
$(function() {
	initOnEvents();
	initValidationAmendment();
	
	$('#solution-amendment-submit').on('click', function(e){
		e.preventDefault();
		saveAmendment();
	});
});

function initOnEvents(){
    $('#modal_solution_amendment_form').on('show.bs.modal', function() {
    	initCKEditors();
    	checkEdit();
    });
    $('#modal_solution_amendment_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	validator_amendment.resetForm();
    	removeErrorPlacement();
    	CKEDITOR.instances['amendment'].setData('');
//    	CKEDITOR.instances['amendment'].on("instanceReady", function(event) {
////    		 try {
//    			 CKEDITOR.instances['amendment'].setData('');
//    	         CKEDITOR.instances['amendment'].destroy(true);
////    	       } catch (e) { }
//    	});
    });
}
function initCKEditors(){
	if($('#amendment').length !== 0) {
		if(!CKEDITOR.instances['amendment']){
			CKEDITOR.replace( 'amendment',{
				toolbarGroups: [
			 		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
			 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			 		{ name: 'paragraph',   groups: [ 'list','align']}
				]
			} );
		}
	}
}
function checkEdit(){
	
}
function initValidationAmendment(){

	validator_amendment = $("#solution-amendment-form").validate({
//		focusCleanup: true,
		rules: { 
			
			"amendment": { 
				required: true               
			},
			
		},
		messages: {
			
			"amendment": { 
				required: "Amendment is Required."               
			}, 
			
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
function saveAmendment() {
	if($("#solution-amendment-form").valid()){
		var formData = new FormData($("#solution-amendment-form")[0]);
		formData.append('amendment', CKEDITOR.instances['amendment'].getData());
		$.ajax({
			url: custom_url + '/'+$('#modal_solution_amendment_form input[name=flow]').val(),
//			url: '/dashboard/complaints/solutions/amendment',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'JSON',
			beforeSend:function(){
				$('#solution-amendment-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#solution-amendment-submit').prop('disabled',false);
//				if($('#modal_solution_amendment_form #refresh').val()){
//					refreshTable($('#modal_solution_amendment_form #refresh_table').val());
//				}
				$('#modal_solution_amendment_form').modal('hide');
    			alertService({
    				title : 'Success !',
    				text : "Amendment saved successfully.",
					redirectUrl: data.redirect_url,
    				type : 'success',
    			});
			},
			error: function (data) {
				$('#solution-amendment-submit').prop('disabled',false);
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
