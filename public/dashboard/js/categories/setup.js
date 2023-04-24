var validator;
$(function() {
	initSelectors();
	initCategoryStatus();
	initParentCategory(0, true);
	initcolorPicker();
	initValidationCategory();
	eventHandler();
	initOnEvents();
	$('#category-setup-submit').on('click', function(){
		saveCategory();
	});
	$('#displayParentCategory').hide();
});

function initParentCategory(level, disabled){
/**
 * resource_level carry the category level to be filterd.
 */
	var init_selectors = [
		{
			class: '.select-category-search',
			url: '/dashboard/categories/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#category_id_fk',
			modal_name: "",
			inputLength: 0,
			resource_level: level,
			noResults_btn: false,
			autofocus: false,
			disabled: disabled
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
}

function initOnEvents(){
    $('#modal_category_form').on('show.bs.modal', function() {
    	checkCategoryEdit();
    });
    $('#modal_category_form').on('hidden.bs.modal', function () {
    	$(this).find('form')[0].reset();
    	validator.resetForm();
    	removeErrorPlacement();
    	
    	var selects = ['#category_status', '#category_level', '#parent_category_id'];
		$.each(selects, function(index, element){
			$(element).trigger({
				type: 'select2:unselect'
			});
			clearSelect2(element);
		});
		initCategoryStatus();
		initSelectors();
		initParentCategory(0, true);
		$('#displayParentCategory').hide();
    });
    
    var selects = ['#category_status', '#category_level'];
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
	$('#simple-color-picker').colorpicker();
}

function checkCategoryEdit(){
	if($('#modal_category_form #mode').val() == 'edit'){
		var params = {'id' : $('#modal_category_form #category_id_pk').val() };
		$.ajax({
			url: custom_url + '/dashboard/categories/getById/'+ encodeURIComponent($.param(params)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setCategoryEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}
}

function setCategoryEditData(data){
	$('#category_name').val(data.name);
	$('#category_status').val(data.status).trigger('change');
	$('#category_level').val(data.category_level).trigger('change');
	$('#simple-color-picker').val(data.color).trigger('change');
	$('#category_status').trigger({
	    type: 'select2:select'
	});
	$('#category_level').trigger({
	    type: 'select2:select'
	});
	if(data.category_level > 1){
		var category = new Option(data.parent_category.name, data.parent_category.category_id_pk, true, true);
		$('.select-category-search').append(category).trigger('change');
		$('#displayParentCategory').show();
	}
	
	$("textarea[name='reject_reason']").val(data.reject_reason);
}

function initValidationCategory(){

	validator = $("#category-setup-form").validate({
//		focusCleanup: true,
		rules: { 
			"name": { 
				required: true               
			},
			"status": { 
				required: true               
			},
			"category_level": { 
				required: true               
			},
			"parent_category_id": { 
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
			"status": { 
				required: "Status is Required"               
			}, 
			"category_level": { 
				required: "Level is Required"               
			},
			"parent_category_id": {
				required: "Parent Category is Required"
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

function saveCategory() {
	if($("#category-setup-form").valid()){
		
		var formData = $("#category-setup-form").serialize();
		$.ajax({
			url: custom_url + '/dashboard/categories/save',
			type: 'POST',
			data: formData,
			dataType: 'JSON',
			beforeSend:function(){
				$('#category-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#category-setup-submit').prop('disabled',false);
				if($('#modal_category_form #refresh').val()){
					refreshTable($('#modal_category_form #refresh_table').val());
				}
				$('#modal_category_form').modal('hide');
				alertService({
					title: 'Success !',
					text: "Category saved successfully.",
					type: 'success',
				});
			},
			error: function (data) {
				$('#category-setup-submit').prop('disabled',false);
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
	var selects = ['#category_status', '#category_level'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			if(element == '#category_status'){
				var status = $(e.currentTarget).find("option:selected").val();
				  if(status == 'REJ'){
					  $('#displayRejectReason').show();
				  }else{
					  $('#displayRejectReason').hide();
				  }
			}
			if(element == '#category_level'){
				clearSelect2('.select-category-search');
				var level = $(e.currentTarget).find("option:selected").val();
				if(parseInt(level) !== 1){
					initParentCategory(parseInt(level-1), false);
					$('#displayParentCategory').show();
				}else{
					initParentCategory(0, true);
					$('#displayParentCategory').hide();
				}
			}
			
		  });

	  $(element).on('select2:unselect', function (e) {
			if(element == '#category_level'){				
				initParentCategory(0, true);
				$('#displayParentCategory').hide();
			}
	  });
	});
	
	$('#active-category').on('click', function(){
		$('#active-category').hide();
		$('#inactive-category').show();
	});
	
	$('#inactive-category').on('click', function(){
		$('#inactive-category').hide();
		$('#active-category').show();
	});
	
	
}

		