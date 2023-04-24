var validator;
var table;
var categoryLevelCount;
var filters = {
		date_from: '',
		date_to: '',
		branch_department_id_fk: '',
		complaint_mode_id_fk: '',
		category_id_fk: new Array(),
//		sub_category_id_fk: '',
		area_id_fk: '',
		status: '',
		type:'ALL'
}

var selects = ['#branch_department_id_fk','#complaint_mode_id_fk',
//				'#category_id_fk', '#sub_category_id_fk',
				'#area_id_fk','#status', '#select-type'];
var selectClasses = ['.select-branch-search','.select-complaint-mode-search','.select-category-search', '.select-sub-category-search','.select-area-search','.select-report-analysis-status-search'];
$(function() {
	initPageSelectors();
	initType();
	initCategoryFilters();
	init_validation_report();
	eventHandler();
});

function initCategoryFilters(){
	categoryLevelCount = parseInt($('#category_levels').val());
	var row = '';
	var sizeClass='col-md-4';
	if(categoryLevelCount > 4)
		var sizeClass = 'col-md-2';
	else if(categoryLevelCount > 3)
		var sizeClass = 'col-md-3';
		
	for(var i = 1; i<=categoryLevelCount; i++){
		row+='<div class="'+sizeClass+' col-xs-12">'+
				'<div class="form-group">'+
					'<label for="category_level_'+i+'">Category level '+i+':</label>'+
			           '<select data-placeholder="Select Category level '+i+'" data-index='+i+' style="width: 100% !important;" class="form-control select-category-search-'+i+' select2-close category_id_fk-error error-placement" onselect="onSelect(this)" id="category_id_fk_'+i+'">'+
                       '</select>'+
					'</div>'+
				'</div>';
	}
	
	$('.categoryFilterRow').html(row);
	
	for(var i = 1; i<=categoryLevelCount; i++){
		initCategorySelectors(i);
	}
	
}

function initPageSelectors(){
	initArea();
	initComplaintModes();
	initReportComplaintStatus();

	var init_selectors = [	
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
		}
//		{
//			class: '.select-category-search',
//			url: '/dashboard/categories/get/search/all',
//			noResults_label: '',
//			display_text_box: '',
//			id: '#category_id_fk',
//			modal_name: "",
//			inputLength: 0,
//			noResults_btn: false,
//			autofocus: false,
//			disabled: false
//		},
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
//			disabled: false
//		},
		
		
		

	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
}

function initCategorySelectors(level){
	var init_selectors = [
		{
			class: '.select-category-search-'+level,
			url: '/dashboard/categories/get/search/all/'+level,
			noResults_label: '',
			display_text_box: '',
			id: '#category_id_fk_',
			modal_name: "",
			noResults_btn: false,
			autofocus: false,
			disabled: false
		}
	];
	
	$.each(init_selectors, function(index, selector){
		initSetupSelectorsWithAjax(selector);
	});
	
	categoryEventHandler(level);
}
function categoryEventHandler(i){
	
	$('#category_id_fk_'+i).on('select2:select', function (e) {
		/**
		 * Parse the inverse of the categry level to query easily in filters
		 */
		var index = (categoryLevelCount - parseInt($(this).data('index'))) + 1;
		filters.category_id_fk[index] = $(e.currentTarget).find("option:selected").val();
	  });

	$('#category_id_fk_'+i).on('select2:unselect', function (e) {		
		/**
		 * Parse the inverse of the categry level to query easily in filters
		 */
		var index = (categoryLevelCount - parseInt($(this).data('index'))) + 1;
		filters.category_id_fk[index] = '';
	});
}
Date.prototype.subtractDays = function(d) {  
    this.setTime(this.getTime() - (d*24*60*60*1000));  
    return this;  
} 
function eventHandler() {
	dateHandler();
	$("#reports-table").hide();
	$('.filter-switchery').on('change', function(){
		if ($(this).prop('checked')) {
			$('.filter-display').show();	
		}else{
			$('.filter-display').hide();
			destroyTable('dashboard/reports');
			$("#reports-table").hide();
			
			$.each(selectClasses, function(index, element){
				$(element).trigger({
					type: 'select2:unselect'
				});
				destroySelect2(element);
			});
			initPageSelectors();
			initCategoryFilters(); // CR4
			filters.category_id_fk = new Array(); // CR4
		}
	});
	$('.filter-switchery').trigger('change');

	$.each(selects, function(index, element){
		$(element).on('select2:select', function (e) {
//Removed in CR3			
//			if(element == '#category_id_fk'){
//				filters.category_id_fk = $(e.currentTarget).find("option:selected").val();
//				destroySelect2('.select-sub-category-search');
//				initSubCategorySelector(false, filters.category_id_fk);
//			}
			if(element == '#branch_department_id_fk'){
				filters.branch_department_id_fk = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#complaint_mode_id_fk'){
				filters.complaint_mode_id_fk = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#category_id_fk'){
				filters.category_id_fk = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#sub_category_id_fk'){
				filters.category_id_fk = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#area_id_fk'){
				// filters.category_id_fk = $(e.currentTarget).find("option:selected").val();
				filters.area_id_fk = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#status'){
				filters.status = $(e.currentTarget).find("option:selected").val();
			}
			if(element == '#select-type'){
				filters.type = $(e.currentTarget).find("option:selected").val();
			}
			
			removeErrorPlacement(element);
		});
		$(element).on('select2:unselect', function (e) {
//removed in CR3			
//			if(element == '#category_id_fk'){
//				removeErrorPlacement('.select-sub-category-search');
//				destroySelect2('.select-sub-category-search');
//				initSubCategorySelector(true);
//			}			
			if(element == '#branch_department_id_fk'){
				filters.branch_department_id_fk = '';
			}
			if(element == '#complaint_mode_id_fk'){
				filters.complaint_mode_id_fk ='';
			}
//			if(element == '#category_id_fk'){
//				filters.category_id_fk = '';
//			}
			if(element == '#sub_category_id_fk'){
				filters.category_id_fk = '';
			}
			if(element == '#area_id_fk'){
				// filters.category_id_fk = '';
				filters.area_id_fk = '';
			}
			if(element == '#status'){
				filters.status = '';
			}
		});
	});
	
	$('#report-submit').on('click', function(e){
		e.preventDefault();
		initReport(); 
	});
	
	$(".detail-table").parents('td').css("text-align", "inherit");
}
function dateHandler(){
	
	$dates = ['#report_date_from','#report_date_to'];
	$.each($dates, function(index, element){
		$(element).on('change', function(){
			if(element == '#report_date_from'){
				if($(this).val() !== '')
					filters.date_from = $(this).val();
				else
					filters.date_from = '';
			}
			if(element == '#report_date_to'){
				if($(this).val() !== '')
					filters.date_to = $(this).val();
				else
					filters.date_to = '';
			}
		});
	});
	
	$('#report_date_to').attr('max', new Date().toISOString().split("T")[0]);
	$('#report_date_from').attr('max', new Date().toISOString().split("T")[0]);
	date = new Date();
	var from=$('#report_date_from').val(date.subtractDays(15).toISOString().split("T")[0]);
	var to=$('#report_date_to').val(new Date().toISOString().split("T")[0]).trigger('change');

	$('#report_date_from').val(date.subtractDays(15).toISOString().split("T")[0]).trigger('change');
	$('#report_date_to').val(new Date().toISOString().split("T")[0]).trigger('change');
}
function initReport(){
	$("#reports-table").hide();
	destroyTable('dashboard/reports');
	if($("#report-form").valid()){
		init_report();
	}
}
/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
	return '<div class="slider" name>'+
			'<table class="detail-table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%">'+
	        '<tr>'+
	            '<td width="30%">Account Number:</td>'+
	            '<td>'+d.account_no+'</td>'+
	        '</tr>'+
	        '<tr>'+
	            '<td width="30%">Resolved By:</td>'+
        	    '<td>'+d.resolved_by+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td width="30%">Event Closed Date:</td>'+
    	        '<td>'+d.close_date+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td width="30%">1st Reminder:</td>'+
                '<td>'+d.reminder_1+'</td>'+
            '</tr>'+
            '<tr>'+
                '<td width="30%">2nd Reminder:</td>'+
                '<td>'+d.reminder_2+'</td>'+
             '</tr>'+
             '<tr>'+
                '<td width="30%">Final Reminder:</td>'+
                '<td>'+d.reminder_3+'</td>'+
            '</tr>'+        
	        '<tr>'+
        		'<td width="30%">Complaint:</td>'+
        		'<td>'+decodeToHtml(d.complaint)+'</td>'+
        	'</tr>'+
	    '</table>'+
	  '</div>';
}
function detailPanelTrigger(element, table){
    // Add event listener for opening and closing details
	$(element+' tbody').off('click', 'td.details-control');
    $(element+' tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            $('div.slider', row.child()).slideUp( function () {
                row.child.hide();
                tr.removeClass('shown');
            } );
        }
        else {
            // Open this row
            row.child( format(row.data())).show();
            tr.addClass('shown');
            $('div.slider', row.child()).slideDown();
        }
    } );
}
function initSubCategorySelector(disabled, id){
	initSetupSelectorsWithAjax(
			{
				class: '.select-sub-category-search',
				url: '/dashboard/sub_categories/get/search/all',
				noResults_label: '',
				display_text_box: '',
				id: '#sub_category_id_fk',
				modal_name: "",
				inputLength: 0,
				noResults_btn: false,
				autofocus: false,
				disabled: disabled,
				resource_id: id
			});
}
function init_report() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_Complaint_Reports');
	
	var handleDataTableButtons = function() {
		table = $(".reports-table").DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
//		  dom: "Blfrtip",
		  serverSide:true,
		  buttons: [],
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/reports/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				$("#reports-table").show();
				  return res;
			  },
			  data: function (d) {
				  d.branch_department_id_fk = filters.branch_department_id_fk,
				  d.complaint_mode_id_fk = filters.complaint_mode_id_fk,
				  d.category_id_fk = filters.category_id_fk,
//				  d.sub_category_id_fk = filters.sub_category_id_fk,
				  d.area_id_fk = filters.area_id_fk,
				  d.status = filters.status,
				  d.date_from = filters.date_from,
				  d.date_to = filters.date_to,
				  d.type = filters.type
			  }
	       },
	        columns: [
	        	{
	                className:'details-control',
	                orderable:false,
	                searchable:false,
	                data:null,
	                defaultContent: ''
	            },
	           
	        	{
	                data: 'open_date',
	                name: 'open_date'
	            },
	            {
	                data: 'reference_number',
	                name: 'Reference_Number'
	            },
	            {
	                data: 'type',
	                name: 'type'
	            },
	            {
	                data: 'branch_name',
	                name: 'Branch_Code/Department',
	                orderable:false
	            },
	            {
	                data: 'area_name',
	                name: 'area.name'
	            },
	            {
	                data: 'category_1',
	                name: 'category.name'
	            },
	            {
	                data: 'category_2',
	                name: 'category.name'
	            },
	            {
	                data: 'category_3',
	                name: 'category.name'
	            },
	            {
	                data: 'nic',
	                name: 'complainant.nic'
	            },
	            {
	                data: 'complainant_name',
	                name: 'complainant.first_name'
	            },
	           
	        ],
	    
		});
	};

	TableManageButtons = function() {
	  "use strict";
	  return {
		init: function() {
		  handleDataTableButtons();
		  detailPanelTrigger('.reports-table', table);
		}
	  };
	}();

	TableManageButtons.init();
};
function init_validation_report(){

	$("#report-form").validate({
		rules : {
			"report_date_from" : {
				required : true
			},
			"report_date_to" : {
				required : true
			}

		},
		messages : {
			"report_date_from" : {
				required : "From date is required"
			},
			"report_date_to" : {
				required : "To date is required"
			}
		},
		highlight: function( label ) {
			$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function( label ) {
			$(label).closest('.form-group').removeClass('has-error');
			label.remove();
		},
		errorPlacement: function( error, element ) {
			var placement = element.closest('.input-group');
			if (!placement.get(0)) {
				placement = element;
			}
			if (error.text() !== '') {
				placement.after(error);
			}
		},
		submitHandler: function(form) {
		}
	});
}
window.exportReport = function(mode){
	if($("#report-form").valid()){
		window.actionExport({filters: filters, mode:mode, flow:'dashboard/reports'});
	}
}