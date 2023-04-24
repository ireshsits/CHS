var validator;
$(function() {
	initSelectors();
	initValidationZone();
	zoneEventHandler();
	$('#zone-setup-submit').on('click', function(){
		saveZone();
	});
	checkZoneEdit();
});

function initSelectors(){
	var init_selectors = [
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
			class: '.select-region-search',
			url: '/dashboard/regions/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#user_id',
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: false
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});

	initZoneStatus();
}

function checkZoneEdit(){
	if($('#zone-setup-form #mode').val() == 'edit'){
		var params = {'id' : $('#zone-setup-form #zone_id_pk').val() };
		$.ajax({
			url: custom_url + '/dashboard/zones/getById/'+ encodeURIComponent($.param(params)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setZoneEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}
}

function setZoneEditData(data){
	$('#zone_name').val(data.name);
	$('#zone_number').val(data.number);
	if(data.manager){
		var manager = new Option(data.manager.first_name+' '+data.manager.last_name+' - <code>'+data.manager.email+'</code>', data.manager.user_id_pk, true, true);
		$('#zone_manager').append(manager).trigger('change');
	}
	$('#zone_status').val(data.status).trigger('change');
	$('#zone_status').trigger({
	    type: 'select2:select'
	});
	$("textarea[name='reject_reason']").val(data.reject_reason);
}

function initValidationZone(){

	validator = $("#zone-setup-form").validate({
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

function saveZone() {
	if($("#zone-setup-form").valid()){
		var formData = $("#zone-setup-form").serialize();
		$.ajax({
			url: custom_url + '/dashboard/zones/save',
			type: 'POST',
			data: formData,
			dataType: 'JSON',
			beforeSend:function(){
				$('#zone-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#zone-setup-submit').prop('disabled',false);
				alertService({
					title: 'Success !',
					text: "Zone saved successfully.",
					type: 'success',						
					redirectUrl: data.redirect_url,
					cancelButton: false
				});
			},
			error: function (data) {
				$('#zone-setup-submit').prop('disabled',false);
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

function zoneEventHandler() {
	var selects = ['#zone_status', '#zone_region'];
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			if(element == '#zone_status'){
				var status = $(e.currentTarget).find("option:selected").val();
				  if(status == 'REJ'){
					  $('#displayRejectReason').show();
				  }else{
					  $('#displayRejectReason').hide();
				  }
			}
			if(element == '#zone_region'){
				associateRegion($(e.currentTarget).find("option:selected").val());
			}
		  });
	});
	
	var selects = ['#zone_status','#zone_manager'];
	$.each(selects, function(index, element){
		$(element).trigger({
			type: 'select2:unselect'
		});
		destroySelect2(element);
	});
	initSelectors();
	
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
		});
	});
	
	$('#active-zone').on('click', function(){
		$('#active-zone').hide();
		$('#inactive-zone').show();
	});
	
	$('#inactive-zone').on('click', function(){
		$('#inactive-zone').hide();
		$('#active-zone').show();
	});
	
	
}

function associateRegion(id){
	$.ajax({
		url: custom_url + '/dashboard/zones/sync_region',
		type: 'POST',
		data: {'id': id, 'mode': 'associate', 'zone_id_pk': $('#region-select-form #zone_id_pk').val()},
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			clearSelect2('.select-region-search');
			refreshTable('dashboard/regions');
		},
		error: function (data) {
			var res = data.responseJSON;
			serverErrorHandler(res);
		},
		complete:function(){
		}
	});
}

		