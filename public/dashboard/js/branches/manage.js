/* ------------------------------------------------------------------------------
*
*  # Buttons extension for Datatables. HTML5 examples
*
*  Specific JS code additions for datatable_extension_buttons_html5.html page
*
*  Version: 1.1
*  Latest update: Mar 6, 2016
*
* ---------------------------------------------------------------------------- */
var table;
var columns;
$(function() {
	init();
	init_branches();
	eventHandler();
});

function init(){
	 if($('#branch-select-form #region_id_pk').length !== 0){
		 columns = [
         {
             data: 'name',
             name: 'Name'
         },	            
         {
             data: 'sol_id',
             name: 'Sol Id'
         },
         {
             data: 'manager_name',
             name: 'Manager Name'
         },
         {
             data: 'manager_email',
             name: 'Manager Email'
         },
         {
           	data: 'type',
          	name: 'Type',
          },
         {
         	data: null,
         	name: 'Status',
         },
         {
             data: null,
             name: null,
             orderable: false,
             searchable: false,
         }]
	 }else{
		 columns = [{
             className:'details-control',
             orderable:false,
             searchable:false,
             data:null,
             defaultContent: ''
         },
         {
             data: 'name',
             name: 'Name'
         },	            
         {
             data: 'sol_id',
             name: 'Sol ID'
         },	            
         {
             data: 'region_name',
             name: 'Region Name'
         },
         {
             data: 'manager_name',
             name: 'Manager Name'
         },
         {
             data: 'manager_email',
             name: 'Manager Email'
         },
         {
          	data: 'type',
         	name: 'Type',
         },
         {
         	data: null,
         	name: 'Status',
         },
         {
             data: null,
             name: null,
             orderable: false,
             searchable: false,
         }]
	 }
}

function eventHandler(){
//	$(".detail-table").parents('td').css("text-align", "inherit");
}


function init_branches() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_Branch/Departments');
	
	var handleDataTableButtons = function() {
		table = $(".branch-department-table").DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  dom: "Blfrtip",
		  buttons: [
		  ],
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/branches/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				  return res;
			  },
			  data: function (d) {
				  if($('#branch-select-form #region_id_pk').length !== 0){
					  d.region_id_fk = $('#branch-select-form #region_id_pk').val();
				  }
			  }
	       },
	        columns: columns,
	        createdRow: function (nRow, aData, iDataIndex) { 
	            if($('#branch-select-form #region_id_pk').length !== 0){
	            	var typeRow='';
	            	typeRow += decodeToHtml(aData.type);
		            $('td:eq(4)', nRow).html(typeRow);
		            var statusRow = '';
		            statusRow += actionStatus(aData.status);
		            $('td:eq(5)', nRow).html(statusRow);
		            var detachRow = '';
		            detachRow += actionDetach({data: aData, flow: '/dashboard/regions/sync_branch'});
		            $('td:eq(6)', nRow).html(detachRow);
	            }else{
		            var statusRow = '';
		            statusRow += actionStatus(aData.status);
		            $('td:eq(7)', nRow).html(statusRow);
		            var actionRow = '';
		            actionRow += actionEdit({data: aData, flow: 'dashboard/branches', modal: true});
		            $('td:eq(8)', nRow).html(actionRow);	
	            }
	            
	        }
		});
	};

	TableManageButtons = function() {
	  "use strict";
	  return {
		init: function() {
		  handleDataTableButtons();
		}
	  };
	}();
	TableManageButtons.init();
	
};