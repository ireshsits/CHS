var owners_count = 0;
var report_purpose_complaint_areas;
var report_purpose_complaint_branch_departments;
$(function() {
	initSelectors();
});

function initSelectors(){
	var init_selectors = [	
		{
			class: '.select-nic-search',
			url: '/dashboard/complainants/get/all',
			noResults_label: 'New',
			display_text_box: '.new-nic',
			modal_name: "",
			inputLength: 1,
			noResults_btn: true,
			autofocus: false,
			disabled: false
		},
		{
			class: '.select-branch-search',
			url: '/dashboard/branches/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#branch_department_id_fk',
			modal_name: "",
			inputLength: 0,
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},
		{
			class: '.select-area-search',
			url: '/dashboard/areas/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#area_id_fk',
			modal_name: "",
			inputLength: 0,
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},
		{
			class: '.select-category-search',
			url: '/dashboard/categories/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#category_id_fk',
			modal_name: "",
			inputLength: 0,
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},
//		{
//			class: '.select-sub-category-search',
//			url: '/dashboard/sub_categories/get/search/all',
//			noResults_label: '',
//			display_text_box: '',
//			id: '#sub_category_id_fk',
//			modal_name: "",
//			inputLength: 0,
//			noResults_btn: false,
//			autofocus: false,
//			disabled: true
//		},
//		{
//			class: '.select-department-owner-search',
//			url: '/dashboard/branches/get/users/all',
//			noResults_label: '',
//			display_text_box: '',
//			id: '#department_owner_id',
//			modal_name: "",
//			noResults_btn: false,
//			autofocus: false,
//			disabled: true
//		}
//		,
//		{
//			class: '.select-escalate-person-search',
//			url: '/dashboard/sub_categories/get/all',
//			noResults_label: '',
//			display_text_box: '',
//			id: '#escalated_to',
//			modal_name: "",
//			noResults_btn: false,
//			autofocus: false,
//			disabled: true
//		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
	
	initTitle();
	initComplaintModes();
	initStatusByField('.select-complaint-plevel-search','priority_level', true);
	initValidation();
	initEscalatePerson(true);
	initNotifyToUsersSelector();
	eventHandler();
	getConfigurations();
	setTimeout(function(){
		checkEdit();
	},300);
	
}

function getBranchDepartmentTypeText(type){
	switch(type){
		case 'BRN' : return 'Branch'; break;
		case 'DEPT' : return 'Dept'; break;
		case 'SPDEPT' : return 'Sp. Dept'; break;
		case 'HODEPT' : return 'HO. Dept'; break;
	}
}

function generateBranchOwner(user){
	owners_count = $('.branch-owners > .countable-div').length;
//	var elements=$('.branch-owners').clone();
//	var row=elements[0].innerHTML;
	var row='';
	row+='<div class="countable-div branch-owner-'+owners_count+'">'+
		  '<div class="col-md-6 col-xs-12">'+
			'<div class="form-group">';
			if(owners_count == 0)
				row+='<label for="branch_dept_name">Branch / Department <span class="required">*</span>  :</label>';
                row+='<select data-placeholder="Select Branch / Dept" class="form-control select-branch-search-'+owners_count+' select2-close branch_department_id_fk-error error-placement branch_dept_rule" onselect="onSelect(this)" name="branch_department_id_fk[]" id="branch_department_id_fk_'+owners_count+'">'+
                '</select>'+
			'</div>'+
		  '</div>'+
		  '<div class="col-md-4 col-xs-12">'+
			'<div class="form-group">';
            if(owners_count == 0)
                // row+='<label for="complaint_owner"><span class="complaintowner_label">Complaint</span> Owner <span class="required">*</span>  :</label>';
				row+='<label for="complaint_owner"><span class="complaintowner_label">Complaint</span> Owner  :</label>';
                row+='<select data-placeholder="Select Owner" class="form-control select-department-owner-search-'+owners_count+' select2-close branch_department_user-error error-placement branch_dept_user_rule" onselect="onSelect(this)" name="branch_department_user[]" id="department_owner_id_'+owners_count+'">'+
                '</select>'+
			'</div>'+
		  '</div>';
          if(owners_count !== 0){
		  row+='<div class="col-md-2 col-xs-12">'+
				'<div class="form-group">'+
					'<label for="complaint_owner">&nbsp;</label>'+
					'<button class="btn btn-danger" id="remove-branch-owner-'+owners_count+'"><i class="fa fa-trash-o"></i></button>'+
				'</div>'+
			   '</div>';
          }else{
		   row+='<div class="col-md-2 col-xs-12">'+
					'<div class="form-group">'+
						'<label for="complaint_owner">&nbsp;</label>'+
						'<button class="btn btn-primary add-branch-owner" id="add-branch-owner"><i class="fa fa-plus"></i></button>'+
					'</div>'+
				'</div>';
          }
	   row+='</div>';
	
	$('.branch-owners').append(row);
	
	var init_selectors = [
		{
			class: '.select-branch-search-'+owners_count,
			url: '/dashboard/branches/get/search/all',
			noResults_label: '',
			display_text_box: '',
			id: '#branch_department_id_fk_'+owners_count,
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: false
		},
		{
			class: '.select-department-owner-search-'+owners_count,
			url: '/dashboard/branches/get/users/all',
			noResults_label: '',
			display_text_box: '',
			id: '#department_owner_id_'+owners_count,
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: true
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
	
	branchOwnerEventHandler(owners_count);
	
	if(owners_count == 0){
		$('#add-branch-owner').on('click', function(e){
			e.preventDefault();
			generateBranchOwner();
		})
	}
	//set edit Data
	if(user){
		if (user.department) {
			var branch = new Option(user.department.name +' - <code>'+user.department.sol_id+' - '+getBranchDepartmentTypeText(user.department.type)+'</code>', user.department.branch_department_id_pk, true, true);
		} else {
			var branch = new Option(user.user.department_user.department.name +' - <code>'+user.user.department_user.department.sol_id+' - '+getBranchDepartmentTypeText(user.user.department_user.department.type)+'</code>', user.user.department_user.department.branch_department_id_pk, true, true);
		}
		
		$('.select-branch-search-'+owners_count).append(branch).trigger('change');
		if (!user.department) {
			/**
			 * initiate the owner select
			 * @returns
			 */
			initDepartmentOwnerSelector(false, owners_count, user.user.department_user.department.branch_department_id_pk);
			
			var branch = new Option(user.user.first_name+' '+user.user.last_name +' - <code>'+user.user.email+'</code>', user.user.user_id_pk, true, true);
			$('.select-department-owner-search-'+owners_count).append(branch).trigger('change');
		}
	}
	
        $('.select-branch-search-'+owners_count).rules("add", 
            {
                required: true,
                messages: {
                    required: "Select branch/department."
                  }
            });
        // $('.select-department-owner-search-'+owners_count).rules("add", 
        //     {
        //         required: true,
        //         messages: {
        //             required: "Select owner."
        //           }
        //     });
//	initValidation();
}

function checkEdit(){
	if($('#complaint-setup-form #mode').val() == 'edit'){
		var searchURI = {id: $('#complaint-setup-form #complaint_id_pk').val()}
		$.ajax({
			url: custom_url + '/dashboard/complaints/getById/'+encodeURIComponent($.param(searchURI)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});
	}else if($('#complaint-action-setup-form #mode').val() == 'edit'){
		var searchURI = {id: $('#complaint-action-setup-form #complaint_solution_id_pk').val()}
		$.ajax({
			url: custom_url + '/dashboard/complaints/solutions/getById/'+encodeURIComponent($.param(searchURI)),
			type: 'GET',
			dataType: 'JSON',
			beforeSend:function(){
			},
			success: function (data) {
				setSolutionEditData(data);
			},
			error: function (data) {
			},
			complete:function(){
			}
		});	
	}else{
		generateBranchOwner();
	}
	
}

function setEditData(complaint){
	// Disable autoupload @init data loading
	initDataLoadingFlag = true;
	console.log('initDataLoadingFlag >> ' +initDataLoadingFlag);
	
	if(complaint.complainant){
		var complainantNIC = new Option(complaint.complainant.nic, complaint.complainant.nic, true, true);
		$('.select-nic-search').append(complainantNIC).trigger('change');
		$('.select-title-search').val(complaint.complainant.title).trigger('change');
	}else{
		//Hide customer detail part
		$('#customer_switch').bootstrapSwitch('state', false, false);
	}
	
	if(complaint.type == 'CMPLA'){
		$('#type_switch').bootstrapSwitch('state', true, true);
	}else{
		$('#type_switch').bootstrapSwitch('state', false, false);
	}
	/**
	 * CR2 has multiple department users
	 */
	$.each(complaint.complaint_users, function(index, user){
		if(user.user_role == 'OWNER'){
			generateBranchOwner(user);
		}
	});
	
	var category = new Option(complaint.category.name, complaint.category.category_id_pk, true, true);
	$('.select-category-search').append(category).trigger('change');
	$('.select-category-search').trigger({
	    type: 'select2:select'
	});
	//removed in CR3
	//var subCategory = new Option(complaint.sub_category.name, complaint.sub_category.sub_category_id_pk, true, true);
	//$('.select-sub-category-search').append(subCategory).trigger('change').prop('disabled',false);
	
//	var complainantMode = new Option(complaint.complaint_mode.name, complaint.complaint_mode.complaint_mode_id_pk, true, true);
//	$('.select-complaint-mode-search').append(complainantMode).trigger('change');
	
	//Added in CR3
	var area = new Option(complaint.area.name, complaint.area.area_id_pk, true, true);
	$('.select-area-search').append(area).trigger('change');
	
	$('#displayAttachment').html(displayFile(complaint));
	if(complaint.source_status && complaint.source_url !== null){
		$('#displayAttachment').append('<i id="remove-source" class="fa fa-trash fa-lg pointer-to" aria-hidden="true">');
	}
	
	$('#remove-source').click(function(){
		$('#source_ref').val('delete');
		$('#displayAttachment').remove();
	});
	
//	$('#priority_level').val(complaint.priority_level).trigger('change');
	setTimeout(function(){
		$('.select-complaint-mode-search').val(complaint.complaint_mode.complaint_mode_id_pk).trigger('change');
		$('.select-complaint-plevel-search').val(complaint.priority_level).trigger('change');
//		CKEDITOR.instances['complaint'].on( "instanceReady", function( event ){
			$('#complaint').html(complaint.complaint);
			CKEDITOR.instances['complaint'].setData(complaint.complaint);
//		});
	},300);

	// Added in CR5
	let notifiy_other_users = [];
	
	$.each(complaint.complaint_notification_other_users, function(index, user){

		var markup = "<div class='select2-result-repository clearfix'>"+
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + user.user.first_name +" "+ user.user.last_name +"</div>"+
    	"<div class='select2-result-repository__description'><code>" + user.user.email + "</code></div>";

		// var title = user.user.first_name+''+user.user.last_name+'-'+user.user.email;
		var notifiy_other_user = new Option(markup, user.user_id_fk, true, true);
		notifiy_other_users.push(notifiy_other_user);
	});
	$('.select-notify-to-search').append(notifiy_other_users).trigger('change');
	$('.select-notify-to-search').trigger({
	    type: 'select2:select'
	});

	setTimeout(function(){
		initDataLoadingFlag = false;
		console.log('initDataLoadingFlag >> ' +initDataLoadingFlag);
	},350);
}

function setSolutionEditData(solution){

	initDataLoadingFlag = true;
	console.log('initDataLoadingFlag >> ' +initDataLoadingFlag);
	
	if(solution){
		setTimeout(function(){
//		CKEDITOR.instances['action_taken'].on( "instanceReady", function( event ){
			$('#action_taken').html(solution.action_taken);
			CKEDITOR.instances['action_taken'].setData(solution.action_taken);
//		});
		},300);
	}
		
	setTimeout(function(){
		initDataLoadingFlag = false;
		console.log('initDataLoadingFlag >> ' +initDataLoadingFlag);
	},350);
}

function getConfigurations() {
	
		$.ajax({
			url: custom_url + '/dashboard/complaints/get/configurations',
			type: 'GET',
			processData: false,
			contentType: false,
			dataType: 'JSON',
			beforeSend:function() {
				
			},
			success: function (data) {
				
				if(data.status == true) {
					report_purpose_complaint_areas = data.configurations.report_purpose_areas;
					report_purpose_complaint_branch_departments = data.configurations.report_purpose_branch_departments;
				}

			},
			error: function (data) {
				
			},
			complete:function(){
			}
		});
   
}

function initValidation(){
	
	jQuery.validator.addMethod("nicFormat", function(value, element) {
		  return this.optional( element ) || /^([0-9]{9}[x|X|v|V]|[0-9]{12})$/.test( value );
		}, 'The nic format invalid.');
	
	jQuery.validator.addMethod("nicNumericValidity", function(value, element) {
	      return this.optional( element ) || !(/([0-9])\1{9,}/i.test( value ));
	    }, 'Invalid numeric format.');

	jQuery.validator.addClassRules({
	  branch_dept_rule: {
	    required: true
	  },
	  branch_dept_user_rule: {
	    // required: true
		// reportingComplaint: true
	  }
	});

	$("#complaint-setup-form").validate({
//		ignore: 'input[type=hidden]',
		rules: { 
			"nic": { 
				required: true,
				nicFormat: true,
		        nicNumericValidity: true
			}, 
			"account_no":{
//				required: true,             
				number: true
			}, 
			"open_date":{
				required: true               
			},
			"title": {
				required: true
			}, 
			"first_name":{
				required: true               
			}, 
			"last_name":{
				required: true               
			}, 
			"contact_no":{
				required: true               
			}, 
			"branch_department_id_fk":{
				required: true               
			},
//			"branch_department_id_fk[]":{
//				required: true
//			},
//			"branch_department_user[]": {
//				required: true
//			},
			"category_id_fk":{
				required: true               
			},
			"area_id_fk":{
				required:true
			},
//			Removed in CR3 
//			"sub_category_id_fk":{
//				required: true               
//			},
			"priority_level": {
				required: true
			},
			"complaint_mode_id_fk":{
				required: true               
			},
			"complaint":{
				required: true               
			}
		},
		messages: {
			"nic": { 
				required: "Nic is required."               
			}, 
			"account_no":{          
				number: "Account number invalid."
			}, 
			"open_date":{
				required: "Complaining date is required."              
			},
			"title":{
				required: "Title is required."               
			},  
			"first_name":{
				required: "First name is required."               
			}, 
			"last_name":{
				required: "Last name is required."               
			}, 
			"contact_no":{
				required: "Contact number is required."               
			}, 
			"branch_department_id_fk":{
				required: "Select branch name."               
			}, 
//			"branch_department_id_fk[]":{
//				required: "Select branch name."               
//			},
//			"branch_department_user[]": {
//				required: "Select owner."
//			},
			"category_id_fk": {
				required: "Select category."   
			},
			"area_id_fk": {
				required: "Select area."
			}, 
//			"sub_category_id_fk":{
//				required: "Select sub category."           
//			},
			"priority_level": {
				required: "Select priority level."
			},
			"complaint_mode_id_fk":{
				required: "Select mode."               
			},
			"complaint":{
				required: "Complaint is required."               
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
	
	$("#complaint-action-setup-form").validate({
		rules: {
			'action_taken': {
				required: true
			},
			"branch_department_id_fk":{
				required: true               
			}
		},
		messages: {
			'action_taken': {
				required: "The action taken is required"
			},
			"branch_department_id_fk":{
				required: "Select branch name"               
			},
			'escalated_to': {
				required: "Select escalate person"
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

function Save() {
	removeErrorPlacement();
    if($("#complaint-setup-form").valid()){

		var formData = new FormData($("#complaint-setup-form")[0]);
		formData.append('complaint', CKEDITOR.instances['complaint'].getData());
		$.ajax({
			url: custom_url + '/dashboard/complaints/save_complaint',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'JSON',
			beforeSend:function(){
				$('#complaint-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				
				if(data.status == 'true'){
					alertService({
						title: 'Success !',
						html:'Complaint saved successfully.<div>&nbsp;</div>Reference Number: '+data.reference_number+' <div id="referenceNo" style="display: none;">'+data.reference_number+'</div>'+
							 '<button type="button" id="btnCopy"><i class="fa fa-copy"></i></button>',
						type: 'success',
						redirectUrl: data.redirect_url,
						cancelButton: false
					});
				}else{
					alertService({
						title: 'Error !',
						text: (data.message) ? data.message : "Complaint saved failed.",
						type: 'error',
						cancelButton: false
					});
					$('#complaint-setup-submit').prop('disabled',false);
				}
			},
			error: function (data) {
				$('#complaint-setup-submit').prop('disabled',false);
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

function SaveReply(){
	removeErrorPlacement();
    if($("#complaint-action-setup-form").valid()){
    	
		var formData = new FormData($("#complaint-action-setup-form")[0]);
		formData.append('action_taken', CKEDITOR.instances['action_taken'].getData());
		formData.append('remarks', CKEDITOR.instances['remarks'].getData());
		$.ajax({
			url: custom_url + '/dashboard/complaints/save_reply',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'JSON',
			beforeSend:function(){
				$('#complaint-action-setup-submit').prop('disabled',true);
			},
			success: function (data) {
				$('#complaint-action-setup-submit').prop('disabled',false);
				if(data.status){
					alertService({
						title: 'Success !',
						text: "Complaint reply saved successfully.",
						type: 'success',
						redirectUrl: data.redirect_url,
						cancelButton: false
					});
				}else{
					alertService({
						title: 'Error !',
						text: "Complaint reply failed.",
						type: 'error',
						cancelButton: false
					});
				}
			},
			error: function (data) {
				$('#complaint-action-setup-submit').prop('disabled',false);
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

function initCKEditors(){
	if($('#complaint').length !== 0) {
		CKEDITOR.replace( 'complaint',{
			toolbarGroups: [
		 		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		 		{ name: 'paragraph',   groups: [ 'list','align']}
			]
		} );
	}
	if($('#action_taken').length !== 0) {
		CKEDITOR.replace( 'action_taken',{
			toolbarGroups: [
		 		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		 		{ name: 'paragraph',   groups: [ 'list','align']}
			]
		} );
	}
	if($('#remarks').length !== 0) {
		CKEDITOR.replace( 'remarks',{
			toolbarGroups: [
		 		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		 		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		 		{ name: 'paragraph',   groups: [ 'list','align']}
			]
		} );
	}
}

function clearCkEditors(){
	if($('#complaint').length !== 0)
	CKEDITOR.instances['complaint'].setData('');
	if($('#action_taken').length !== 0)
	CKEDITOR.instances['action_taken'].setData('');
	if($('#remarks').length !== 0)
	CKEDITOR.instances['remarks'].setData('');
}

function eventHandler() {

    $(document).on('click', '#btnCopy' , function(ev) { 
      copyToClipboard('referenceNo');
    });
    
	initCKEditors();
	$('.cuz-col').find('span > ol').addClass('cuz-complaint');

	$('#open_date').val(new Date().toISOString().split('T')[0]).attr('disabled',true);
	$('#branch_code').attr('disabled', true);
	
	$('.escalation-display').hide();
	
	// Primary file input
	$(".file-styled-primary").uniform({
		fileButtonClass: 'action btn bg-blue'
	});
	$('#complaint-setup-submit').on('click', function(){
		Save();
	});
	$('#complaint-action-setup-submit').on('click', function(){
		SaveReply();
	});
	
	var selects = ['#complainant_title','#complainant_nic','#branch_department_id_fk', '#category_id_fk', '#area_id_fk', '#sub_category_id_fk', '#escalated_to', '#complaint_mode_id_fk','#priority_level'];
	var selectors=['.select-branch-search','.select-escalate-person-search']
	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
			removeErrorPlacement(element);
			
			if(element == '#branch_department_id_fk'){
				var id = $(e.currentTarget).find("option:selected").val();
				destroySelect2('.select-escalate-person-search');
				initEscalatePerson(false, id);
				$(element).on('select2:unselect', function (e) {
					removeErrorPlacement('.select-escalate-person-search');
					destroySelect2('.select-escalate-person-search');
					initEscalatePerson(true);
				});
			}
			if(element == '#category_id_fk'){
//				Removed in CR3
//				var id = $(e.currentTarget).find("option:selected").val();
//				destroySelect2('.select-sub-category-search');
//				initSubCategorySelector(false, id);
//				$(element).on('select2:unselect', function (e) {
//					removeErrorPlacement('.select-sub-category-search');
//					destroySelect2('.select-sub-category-search');
//					initSubCategorySelector(true);
//				});
			}
			if(element == '#complainant_nic'){
				var nic = $(e.currentTarget).find("option:selected").val();
				setComplainant(nic);
				$(element).on('select2:unselect', function (e) {
					$('#first_name, #last_name, #contact_no').val('');
				});
			}
		  });
	});
	
	$('input[type="file"]').on('change',function(){
		$('#source_ref').val('new');
		removeErrorPlacement('#attachment');
	});
	
	$('.escalation-switchery').on('change', function(){
		if ($(this).prop('checked')) {
			$('.escalation-display').show();
			$('.action-display').hide();
			initValidation();
			
			$.each(selectors, function(index, element){
				$(element).trigger({
					type: 'select2:unselect'
				});
				destroySelect2(element);
			});
			initSelectors();
		}else{
			$('.action-display').show();
			$('.escalation-display').hide();
		
		}
		
	});
	
	 $('#reset').click(function(e) {
		e.preventDefault();
		removeErrorPlacement();
		$.each(selects, function(index, element){
				removeErrorPlacement(element);
				removeErrorPlacement('.select-escalate-person-search');
				destroySelect2(element);
		});
		$('#complainant_nic, #first_name, #last_name, #contact_no, #account_no, #attachment').val('');
		$('#customer_switch').bootstrapSwitch('state', true, true);
		$('.customer').show();
		$('#type_switch').bootstrapSwitch('state', true, true);
		clearCkEditors();
		$('.branch-owners').empty();
		owners_count = 0;
		generateBranchOwner();
		initSelectors();
		initNotifyToUsersSelector();
	 });
	
	$('#customer_switch').bootstrapSwitch('state', true, true);
	$('#type_switch').bootstrapSwitch('state', true, true);
	
	$('#customer_switch').on('switchChange.bootstrapSwitch', function(){
		$('.customer').toggle();
	});

	$('#type_switch').on('switchChange.bootstrapSwitch', function() {
		var status = $(this).bootstrapSwitch('state');

		if ($(this).bootstrapSwitch('state') == true) {
			$('.accno_label').html("Complainant");
			$('.opendate_label').html("Complaining");
			$('.complaint_label').html("Complaint");
			$('.complaintowner_label').html("Complaint");
			$('.accno_input').attr("placeholder", "Complaint AN / CN");
		} else {
			$('.accno_label').html("Complimenter's");
			$('.opendate_label').html("Complimenting");
			$('.complaint_label').html("Compliment");
			$('.complaintowner_label').html("Compliment");
			$('.accno_input').attr("placeholder", "Compliment AN / CN");
		}
	});

}

function branchOwnerEventHandler(i){
	
//	for(var i=0;i<=owners_count;i++){
		$('#branch_department_id_fk_'+i).on('select2:select', function (e) {
			removeErrorPlacement('#branch_department_id_fk_'+i);
			var id = $(e.currentTarget).find("option:selected").val();
			syncUsers(id);
			destroySelect2('.select-department-owner-search-'+i);
			initDepartmentOwnerSelector(false,i,id);
		  });

		$('#branch_department_id_fk_'+i).on('select2:unselect', function (e) {
			removeErrorPlacement('.select-department-owner-search-'+i);
			destroySelect2('.select-department-owner-search-'+i);
			initDepartmentOwnerSelector(true,i);
		});
		
		$('#remove-branch-owner-'+i).on('click', function(e){
			e.preventDefault();
			$('.branch-owner-'+i).remove()
		});
//	}
}

function initDepartmentOwnerSelector(disabled, i, id){
	initSetupSelectorsWithAjax(
			{
				class: '.select-department-owner-search-'+i,
				url: '/dashboard/branches/get/users/all',
				noResults_label: 'No Users Found!',
				display_text_box: '',
				id: '#department_owner_id_'+i,
				modal_name: "",
				inputLength: 0,
				noResults_btn: false,
				autofocus: false,
				disabled: disabled,
				resource_id: id
			});
}
//Removed in CR3
//function initSubCategorySelector(disabled, id){
//	initSetupSelectorsWithAjax(
//			{
//				class: '.select-sub-category-search',
//				url: '/dashboard/sub_categories/get/search/all',
//				noResults_label: '',
//				display_text_box: '',
//				id: '#sub_category_id_fk',
//				modal_name: "",
//				inputLength: 0,
//				noResults_btn: false,
//				autofocus: false,
//				disabled: disabled,
//				resource_id: id
//			});
//}

function initEscalatePerson(disabled, id){
	initSetupSelectorsWithAjax(
			{
				class: '.select-escalate-person-search',
				url: '/dashboard/branches/get/users/all',
				noResults_label: 'No Users Found!',
				display_text_box: '',
				id: '#escalated_to',
				modal_name: "",
				inputLength: 0,
				noResults_btn: false,
				autofocus: false,
				disabled: disabled,
				resource_id: id
			});
}
//Removed in CR2
//function setBranchCode(element, id){
//	var searchURI = {'id': id};
//	$.ajax({
//		url: '/dashboard/branches/getById/'+encodeURIComponent($.param(searchURI)),
//		type: 'GET',
//		processData: false,
//		contentType: false,
//		dataType: 'JSON',
//		beforeSend:function(){
//		},
//		success: function (data) {
//			$('.branch_code').val(data.sol_id);
//		},
//		error: function (data) {
//		},
//		complete:function(){
//		}
//	});
//}

function initNotifyToUsersSelector() {
	initSetupSelectorsWithAjax({
		class: '.select-notify-to-search',
		url: '/dashboard/users/get/all',
		noResults_label: '',
		display_text_box: '',
		id: '#complaint_notify_to',
		modal_name: "",
		inputLength: 0,
		noResults_btn: false,
		autofocus: false,
		disabled: false
	});
}

function setComplainant(nic){
	var searchURI = {'nic': nic};
	$.ajax({
		url: custom_url + '/dashboard/complainants/getByNIC/'+encodeURIComponent($.param(searchURI)),
		type: 'GET',
		processData: false,
		contentType: false,
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			$('#complainant_title').val(data.title).trigger('change');
			$('#first_name').val(data.first_name);
			$('#last_name').val(data.last_name);
			$('#contact_no').val(data.contact_no);
			var ids = ['#first_name','#last_name', '#contact_no'];
			$.each(ids, function(index, element){
				removeErrorPlacement(element);
			});
		},
		error: function (data) {
		},
		complete:function(){
		}
	});
}

function sendReminder(id){
	var searchURI = {'id': id};
	$.ajax({
		url: custom_url + '/dashboard/complainants/getByNIC/'+encodeURIComponent($.param(searchURI)),
		type: 'GET',
		processData: false,
		contentType: false,
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			$('#first_name').val(data.first_name);
			$('#last_name').val(data.last_name);
			$('#contact_no').val(data.contact_no);
			var ids = ['#first_name','#last_name', '#contact_no'];
			$.each(ids, function(index, element){
				removeErrorPlacement(element);
			});
		},
		error: function (data) {
		},
		complete:function(){
		}
	});
}

function syncUsers(id){
	var searchURI = {'id': id};
	$.ajax({
		url: custom_url + '/dashboard/branches/users/sync/'+encodeURIComponent($.param(searchURI)),
		type: 'GET',
		processData: false,
		contentType: false,
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
		},
		error: function (data) {
		},
		complete:function(){
		}
	});
}