var validator_sub;
$(function() {
	initCategory();
	initArea();
	initSubCategoryStatus();
	initcolorPicker();
	initValidationSubCategory();
	eventHandler();
	initOnEvents();

	$('#sub-category-setup-submit').on('click', function(){
		saveSubCategory();
	});
});

function initOnEvents(){
    $('#modal_sub_category_form').on('show.bs.modal', function() {
    	checkSubCategoryEdit();
    });
    $('#modal_sub_category_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	validator_sub.resetForm();
    	removeErrorPlacement();
    	var selects = ['#sub_category_status','#category_id_fk','#area_id_fk'];
		$.each(selects, function(index, element){
			$(element).trigger({
				type: 'select2:unselect'
			});
			destroySelect2(element);
		});
		initCategory();
		initArea();
		initSubCategoryStatus();
    });
    
    var selects = ['#sub_category_status','#category_id_fk','#area_id_fk'];
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
	$('#simple-color-picker1').colorpicker();
}

function checkSubCategoryEdit(){
	if($('#modal_sub_category_form #mode').val() == 'edit'){
		var params = {'id' : $('#modal_sub_category_form #sub_category_id_pk').val() };
		$.ajax({
			url: '/dashboard/sub_categories/getById/'+ encodeURIComponent($.param(params)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setSubCategoryEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}
}

function setSubCategoryEditData(data){
	$('#sub_category_name').val(data.name);
	$('#category_id_fk').val(data.category_id_fk).trigger('change');
	$('#sub_category_status').val(data.status).trigger('change');
	$('#simple-color-picker1').val(data.color);
	$('#area_id_fk').val(data.area_id_fk).trigger('change');
	$('#sub_category_status').trigger({
	    type: 'select2:select'
	});
	$("textarea[name='reject_reason']").html(data.reject_reason);
}

function initValidationSubCategory(){

	validator_sub = $("#sub-category-setup-form").validate({
//		focusCleanup: true,
		rules: { 
			
			"category_id_fk": { 
				required: true               
			},
			"name": { 
				required: true               
			},
			
			"status": { 
				required: true               
			},
			"color": { 
				required: true               
			},
			"area_id_fk": { 
				required: true               
			},
		},
		messages: {
			
			"category_id_fk": { 
				required: "Select Category."               
			}, 
			"name": { 
				required: "Sub Category Name is Required."               
			}, 
			
			"status": { 
				required: "Category Status is Required."               
			},
			"color": { 
				required: "Color is Required."               
			},
			"area_id_fk": { 
				required: "Select Area."               
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
function saveSubCategory() {
	if($("#sub-category-setup-form").valid()){
		
		var formData = $("#sub-category-setup-form").serialize();
		$.ajax({
			url: '/dashboard/sub_categories/save',
			type: 'POST',
			data: formData,
			dataType: 'JSON',
			beforeSend:function(){
				$('#sub-category-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#sub-category-setup-submit').prop('disabled',false);
				if($('#modal_sub_category_form #refresh').val()){
					refreshTable($('#modal_sub_category_form #refresh_table').val());
				}
				$('#modal_sub_category_form').modal('hide');
				alertService({
					title: 'Success !',
					text: "Sub-Category saved successfully.",
					type: 'success',
				});
			},
			error: function (data) {
				$('#sub-category-setup-submit').prop('disabled',false);
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
	var selects = ['#sub_category_status'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			if(element == '#sub_category_status'){
				var status = $(e.currentTarget).find("option:selected").val();
				  if(status == 'REJ'){
					  $('#displayRejectReasonSub').show();
				  }else{
					  $('#displayRejectReasonSub').hide();
				  }
			}
		  });
	});
	
	$('#active-sub_category').on('click', function(){
		$('#active-sub_category').hide();
		$('#inactive-sub_category').show();
	});
	
	$('#inactive-sub_category').on('click', function(){
		$('#inactive-sub_category').hide();
		$('#active-sub_category').show();
	});
	
	
}

		

