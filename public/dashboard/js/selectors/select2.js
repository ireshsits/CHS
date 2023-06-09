var globTerm;
window.initSetupSelectorsWithAjax = function(selector) {
	console.log('Initializing Selector >>'+selector.class);
	$(selector.class).select2({
		minimumResultsForSearch: 10, //Infinity = never display search box
		minimumInputLength: selector.inputLength,
		allowClear: true,
		ajax: {
			url: custom_url + selector.url,
			type: 'POST',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				globTerm = params.term;
				if(selector.resource_id){
					return {
						name: params.term,
						resource_id: selector.resource_id,
						page: params.page
					};
				}else if(selector.resource_level){
					return {
						name: params.term,
						level: selector.resource_level,
						page: params.page
					};
				}else{
					return {
						name: params.term,
						page: params.page
					};
				}
			},
			beforeSend: function () {
			},
			processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.items,
					pagination: {
						more: (params.page * 30) < data.total_count
					}
				};
			},
			cache: true
		},
		language: {
			noResults: function () {
				var row = "No results found ";
				if(selector.noResults_btn){
//					row+= "<button class='pull-right btn btn-info btn-sm m-b-10 waves-effect waves-light' onClick='displaySetupModal(\"" + selector.modal_name + "\")'>" + selector.noResults_label + "<i class='icon-play3 position-right'></i></button>";
					row+= "<button class='pull-right btn btn-info btn-sm m-b-10 waves-effect waves-light' onClick='addValueToField(\""+selector.class+"\",\""+selector.display_text_box+"\")'>" + selector.noResults_label + "<i class='icon-play3 position-right'></i></button>";
				}
				return row;
			}
		},
		escapeMarkup: function (markup) { return markup; },
		templateResult: formatRepo,
	});
	
	if(selector.autofocus){
		$(selector.class).select2('open');
	}
	$(selector.class).prop("disabled", selector.disabled);
}

function formatRepo (repo) {
    if (repo.loading) return repo.text;
    
    if(repo.display_complainant){
        var markup = "<div class='select2-result-repository clearfix'>"+
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.first_name +" "+ repo.last_name +"</div>"+
    	"<div class='select2-result-repository__description'><code>" + repo.nic + "</code></div>";
        return markup;
    }else if(repo.display_branch_department){
        var markup = "<div class='select2-result-repository clearfix'>"+
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.name +"</div>"+
    	"<div class='select2-result-repository__description'><code>" + pad(repo.sol_id,3) + " - "+repo.type_text+"</code></div>";
        return markup;
    }else if(repo.display_branch_department_user){
        var markup = "<div class='select2-result-repository clearfix'>"+
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.first_name +" "+ repo.last_name +"</div>"+
    	"<div class='select2-result-repository__description'><code>" + repo.email + "</code></div>";
        return markup;
    }else if(repo.display_user){
        var markup = "<div class='select2-result-repository clearfix'>"+
        "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.first_name +" "+ repo.last_name +"</div>"+
    	"<div class='select2-result-repository__description'><code>" + repo.email + "</code></div>";
        return markup;
    }else{
    	return repo.text;
    }
}

function pad(n, width, z) {
  z = z || '0';
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

window.initSelectors = function(){
	$('.select').select2({
		allowClear: true,
		minimumResultsForSearch: 10
	});
}

window.initSelectorsNoClear = function(params){
	if(params.search){
		$('.select-no-clear').select2();
	}else{
		$('.select-no-clear').select2({
			minimumResultsForSearch: 10
		});
	}
}

window.initCategory = function(){
	$.ajax({
		url: custom_url + "/dashboard/categories/get/search/all",
		type: 'POST',
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			// Select with category section
			$('.select-category-search').select2({
				data: data.items,
				allowClear: true,
			});
		}
	});
	$(".select-category-search").trigger({
		type: 'select2:select'
	});
}

window.initArea = function(){

	$.ajax({
		url: custom_url + "/dashboard/areas/get/search/all",
		type: 'POST',
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			// Select with area section
			$('.select-area-search').select2({
				data: data.items,
				allowClear: true,
			});
		}
	});
	$(".select-area-search").trigger({
		type: 'select2:select'
	});
}
window.initsubCategory = function(){

	$.ajax({
		url: custom_url + "/dashboard/sub_categories/get/search/all",
		type: 'POST',
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			// Select with sub-category
			$('.select-sub-category-search').select2({
				data: data.items,
				allowClear: true,
			});
		}
	});
}
window.initComplaintUsers = function(id){
	$.ajax({
		url: custom_url + "/dashboard/complaints/get/users/all",
		type: 'POST',
		data: {resource_id: id},
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			$('.select-complaint-user-search').select2({
				data: data.items,
				allowClear: true,
			});
		}
	});
}

window.initRole = function(){
	//Data
	var array_data = [
		{id: 'BM', text: 'BM'},
	    {id: 'RM', text: 'RM'},
	    {id: 'CM', text: 'CM'},
	    {id: 'AGM', text: 'AGM'},
	    {id: 'DGM', text: 'DGM'},
	    {id: 'SDGM', text: 'SDGM'},
	    {id: 'HODEPT', text: 'HODEPT'},
	    {id: 'MKTG', text: 'MKTG'},
	    {id: 'CCC', text: 'CCC'},
	];

	// Loading array data
	$(".select-system-role-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-system-role-search").trigger({
		type: 'select2:select'
	});
	
}

//window.initCategory = function(){
//	$.ajax({
//		url: "/dashboard/sub_categories/get/all",
//		type: 'POST',
//		dataType: 'JSON',
//		beforeSend:function(){
//		},
//		success: function (data) {
//			// Select with category
//			$('.select-category-search').select2({
//				data: $.map(data.items, function (obj) {
//					  obj.id = obj.code; // replace id with the code
//					  return obj;
//					}),
//				allowClear: true,
//			});
//		}
//	});
//}

window.initYear = function(){
	//Data
    startYear = new Date().getFullYear();
    var array_data = [];
    for(var i=0; i<5; i++){
    	var year = startYear - i;
		array_data.push({id: year, text: year});
	}

	// Loading array data
	$(".select-year-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
}

//window.initOwner = function(){
//	//Data
//	var array_data = [
//	    {id: 'ALL', text: 'All'},
////	    {id: 'MNGR', text: 'Manager'},
////	    {id: 'AMNGR', text: 'Assistant Manager'},
////	    {id: 'DPHD', text: 'Department Head'},
////	    {id: 'ADPHD', text: 'Assistant Department Head'},
//	];
//
//	// Loading array data
//	$(".select-complaintowner-search").select2({
//	    placeholder: "Click to load data",
//	    minimumResultsForSearch: Infinity,
//	    data: array_data
//	});
//	
//}

window.initType = function(){
	//Data
	var array_data = [
		{id: 'ALL', text: 'All'},
	    {id: 'CMPLA', text: 'Complaints'},
	    {id: 'CMPLI', text: 'Compliments'},
	];

	// Loading array data
	$(".select-type-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-type-search").trigger({
		type: 'select2:select'
	});
	
}

window.initModeStatus = function(){
	//Data
	var array_data = [
	    {id: 'ACT', text: 'Active'},
	    {id: 'INACT', text: 'Inactive'},

	];

	// Loading array data
	$(".select-modestatus-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-modestatus-search").trigger({
		type: 'select2:select'
	});
	
}
window.initZoneStatus = function(){
	//Data
	var array_data = [
//	    {id: 'PEN', text: 'Draft'},
	    {id: 'ACT', text: 'Active'},
	    {id: 'INACT', text: 'Inactive'},
//	    {id: 'REJ', text: 'Reject'},

	];

	// Loading array data
	$(".select-zonestatus-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-zonestatus-search").trigger({
		type: 'select2:select'
	});
	
}
window.initRegionStatus = function(){
	//Data
	var array_data = [
//	    {id: 'PEN', text: 'Draft'},
	    {id: 'ACT', text: 'Active'},
	    {id: 'INACT', text: 'Inactive'},
//	    {id: 'REJ', text: 'Reject'},

	];

	// Loading array data
	$(".select-regionstatus-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-regionstatus-search").trigger({
		type: 'select2:select'
	});
	
}
window.initCategoryStatus = function(){
	//Data
	var array_data = [
//	    {id: 'PEN', text: 'Draft'},
	    {id: 'ACT', text: 'Active'},
	    {id: 'INACT', text: 'Inactive'},
//	    {id: 'REJ', text: 'Reject'},

	];

	// Loading array data
	$(".select-categorystatus-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-categorystatus-search").trigger({
		type: 'select2:select'
	});
	
}
window.initSubCategoryStatus = function(){
	//Data
	var array_data = [
//	    {id: 'PEN', text: 'Draft'},
	    {id: 'ACT', text: 'Active'},
	    {id: 'INACT', text: 'Inactive'},
//	    {id: 'REJ', text: 'Reject'},

	];

	// Loading array data
	$(".select-sub-categorystatus-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	$(".select-sub-categorystatus-search").trigger({
		type: 'select2:select'
	});
	
}
window.initReportComplaintStatus = function(){
	//Data
	var array_data = [
		{id: 'ALL', text: 'All'},
	    {id: 'PEN', text: 'Draft'},
	    {id: 'INP', text: 'Inprogress'},
	    {id: 'ESC', text: 'Escalated'},
	    {id: 'REP', text: 'Replied'},
	    {id: 'COM', text: 'Completed'},
	    {id: 'CLO', text: 'Closed'},
	    {id: 'REJ', text: 'Rejected'},

	];

	// Loading array data
	$(".select-report-analysis-status-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 10, //Infinity,
	    data: array_data
	});
	
}

window.initTitle = function(){
	//Data
	var array_data = [
		{id: 'Mr', text: 'Mr'},
	    {id: 'Mrs', text: 'Mrs'},
	    {id: 'Ms', text: 'Ms'},
	    {id: 'Mstr', text: 'Mstr'},
	    {id: 'Miss', text: 'Miss'},
	    {id: 'Prof', text: 'Prof'},
	    {id: 'Dr', text: 'Dr'},
	    {id: 'Rev', text: 'Rev'}

	];

	// Loading array data
	$(".select-title-search").select2({
	    placeholder: "Click to load data",
	    minimumResultsForSearch: 0, //Infinity,
	    data: array_data
	});
	
}


//window.initEscalatePerson = function(disabled, id){
//	id = typeof id !== 'undefined' ? id : 0;
//	disabled = typeof disabled !== 'undefined' ? disabled : false;
//	if(id !== 0){
//		var params = {'id' : id };
//		$.ajax({
//			url: '/dashboard/complaints/escalations/getEscalateToById/' + encodeURIComponent($.param(params)),
//			type: 'get',
//			dataType: 'JSON',
//			beforeSend:function(){
//				clearSelect2('.select-escalate-person-search');
//				destroySelect2('.select-escalate-person-search');
//			},
//			success: function (data) {
//				// Select with person-search
//				$('.select-escalate-person-search').select2({
//				    placeholder: "Select Person",
//					data: data.items,
//					allowClear: true,
//				});
//			}
//		});
//	}else{
//		var array_data = [
//			{id: '', text: 'Select Person'}
//		];
//
//		// Loading array data
//		$('.select-escalate-person-search').select2({
//		    placeholder: "Select Person",
//		    minimumResultsForSearch: Infinity,
//		    data: []
//		});	
//	}
//	
//	$(".select-escalate-person-search").prop('disabled', disabled);
//	
//}

window.initComplaintModes = function(){
	$.ajax({
		url: custom_url + '/dashboard/complaints/modes/search/all',
		type: 'get',
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			// Select with complaint mode-search
			$('.select-complaint-mode-search').select2({
			    placeholder: "Select mode",
			    minimumResultsForSearch: 10,
				data: data.items,
				allowClear: true,
			});
		}
	});
}

window.initStatusByField = function(className, status, allowClear){
	var url = getstatusCallFlowByClass(className, status);
	$.ajax({
		url: custom_url + url,
		type: 'get',
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			// Select with complaint mode-search
			$(className).select2({
			    placeholder: "Select",
			    minimumResultsForSearch: 10,
				data: data.items,
				allowClear: allowClear,
			});
		}
	});
}

window.addValueToField = function(element, new_element){
	hideSelector(element);
	$(new_element).val(globTerm).prop('disabled',false).show().focus();
}

window.displaySetupModal = function(modal) {
    hideSelect2Dropdown();
    $('#'+modal+ ' #mode').val('new');
    $('#'+modal+ ' #refresh').val(false);
    $('#'+modal).modal('show');
}

window.clearSelect2 = function(select){
	if ($(select).hasClass("select2-hidden-accessible")){
		console.log('Clearing Select2 option >>'+select);
//		$(select).select2('data', null);
		$(select).val(null).trigger("change");
	}
}

window.destroySelect2 = function(select){
	if ($(select).hasClass("select2-hidden-accessible")){
		$(select).val(null).trigger("change");
		$(select).select2("destroy");
		console.log('Destroyed Select2 option');
	}
}

function hideSelect2Dropdown(element) {
	if ($(element).hasClass("select2-hidden-accessible")){
		$(element).select2("close");
	}
}
function hideSelector(element){
	hideSelect2Dropdown(element);
	removeErrorPlacement(element);
	$(element).next('.select2-container').removeClass('select2-container').addClass('select2-container-hide').remove();
	$(element).remove();
}

function getstatusCallFlowByClass(className, status){
	switch(className){
	case '.select-complaint-plevel-search'	: return '/dashboard/complaints/get/enum_values/'+status; break;
	default: 
	}
}
