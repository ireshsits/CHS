var validator;
$(function() {
	initSelectors();
	initValidationRegion();
	regionEventHandler();
	checkRegionEdit();
	$('#region-setup-submit').on('click', function(){
		saveRegion();
	});
});

function initSelectors(){
	var init_selectors = [
		{
			class: '.select-zone-search',
			url: '/dashboard/zones/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#user_id',
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},		
		{
			class: '.select-user-search',
			url: '/dashboard/users/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#user_id',
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},		
		{
			class: '.select-branch-search',
			url: '/dashboard/branches/get/map/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#branch_department_id_fk',
			modal_name: "",
			inputLength: 0,
			noResults_btn: false,
			autofocus: false,
			disabled: false
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});

	initRegionStatus();
}

//function initOnEvents(){
//    $('#modal_region_form').on('show.bs.modal', function() {
//    	checkRegionEdit();
//    });
//    $('#modal_region_form').on('hidden.bs.modal', function () {
//    	$(this).find('form')[0].reset();
//    	validator.resetForm();
//    	removeErrorPlacement();
    	

//    });
    
//}

function checkRegionEdit(){
	if($('#region-setup-form #mode').val() == 'edit'){
		var params = {'id' : $('#region-setup-form #region_id_pk').val() };
		$.ajax({
			url: custom_url + '/dashboard/regions/getById/'+ encodeURIComponent($.param(params)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setRegionEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}
}

function setRegionEditData(data){
	$('#region_name').val(data.name);
	$('#region_number').val(data.number);
	if(data.manager){
		var manager = new Option(data.manager.first_name+' '+data.manager.last_name+' - <code>'+data.manager.email+'</code>', data.manager.user_id_pk, true, true);
		$('#region_manager').append(manager).trigger('change');
	}
	if(data.zone){
		var zone = new Option(data.zone.name+' - <code>'+data.zone.number+'</code>', data.zone.zone_id_pk, true, true);
		$('#region_zone').append(zone).trigger('change');
	}
	$('#region_status').val(data.status).trigger('change');
	$('#region_status').trigger({
	    type: 'select2:select'
	});

	$("textarea[name='solIds']").val(data.solIds);
	$("textarea[name='reject_reason']").val(data.reject_reason);
}

function initValidationRegion(){

	validator = $("#region-setup-form").validate({
//		focusCleanup: true,
		rules: { 
			"name": { 
				required: true               
			},
			"number": { 
				required: true             
			},
			"manager_id_fk": { 
				required: true               
			},
			"zone_id_fk": {
				required: true
			},
			"status": { 
				required: true               
			}
		},
		messages: {
			"name": { 
				required: "Name is Required"               
			},
			"number": { 
				required: "Number is Required"               
			},
			"manager_id_fk": {
				required: "Manager is Required"        
			},
			"zone_id_fk": {
				required: "Zone is Required"        
			},
			"status": { 
				required: "Status is Required"               
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

function saveRegion() {
	if($("#region-setup-form").valid()){
		
		var formData = $("#region-setup-form").serialize();
		$.ajax({
			url: custom_url + '/dashboard/regions/save',
			type: 'POST',
			data: formData,
			dataType: 'JSON',
			beforeSend:function(){
				$('#region-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#region-setup-submit').prop('disabled',false);
				alertService({
					title: 'Success !',
					text: "Region saved successfully.",
					type: 'success',						
					redirectUrl: data.redirect_url,
					cancelButton: false
				});
			},
			error: function (data) {
				$('#region-setup-submit').prop('disabled',false);
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

function regionEventHandler() {
	var selects = ['#region_status', '#region_manager', '#region_zone', '#region_branch'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			if(element == '#region_status'){
				var status = $(e.currentTarget).find("option:selected").val();
				  if(status == 'REJ'){
					  $('#displayRejectReason').show();
				  }else{
					  $('#displayRejectReason').hide();
				  }
			}
			if(element == '#region_branch'){
				associateBranch($(e.currentTarget).find("option:selected").val());
			}
			
		  });
		
		
	});
	
	var selects = ['#region_status', '#region_manager', '#region_zone'];
	$.each(selects, function(index, element){
		$(element).trigger({
			type: 'select2:unselect'
		});
		destroySelect2(element);
	});
	$('#region-setup-submit').prop('disabled',false);
	initSelectors();
	
    var selects = ['#region_status', '#region_manager', '#region_zone'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
		});
	});
	
	$('#active-region').on('click', function(){
		$('#active-region').hide();
		$('#inactive-region').show();
	});
	
	$('#inactive-region').on('click', function(){
		$('#inactive-region').hide();
		$('#active-region').show();
	});
	
	
}



function associateBranch(id){
	$.ajax({
		url: custom_url + '/dashboard/regions/sync_branch',
		type: 'POST',
		data: {'id': id, 'mode': 'associate', 'region_id_pk': $('#branch-select-form #region_id_pk').val()},
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			clearSelect2('.select-branch-search');
			refreshTable('dashboard/branches');
		},
		error: function (data) {
			var res = data.responseJSON;
			serverErrorHandler(res);
		},
		complete:function(){
		}
	});
}

		