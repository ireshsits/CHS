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
$(function() {
	init_modes();
	eventHandler();
});

function eventHandler(){
	
}

function init_modes() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	
	var handleDataTableButtons = function() {
		table = $(".modes-table").DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  dom: "Blfrtip",
		  buttons: [
//			{
//			  extend: "csv",
//			  className: "btn-sm btn-gray custom-margin-btn",
//              exportOptions: {
//                  columns: [ 1, 2, 3, 4 ,5 ]
//              }
//			},
//			{
//			  extend: "excel",
//			  className: "btn-sm btn-gray custom-margin-btn",
//              exportOptions: {
//                  columns: [ 1, 2, 3, 4 ,5 ]
//              }
//			},
//			{
//			  extend: "pdfHtml5",
//			  className: "btn-sm btn-gray custom-margin-btn",
//              exportOptions: {
//                  columns: [ 1, 2, 3, 4 ,5 ]
//              }
//			},
//			{
//			  extend: "print",
//			  className: "btn-sm btn-gray custom-margin-btn",
//              exportOptions: {
//                  columns: [ 1, 2, 3, 4 ,5 ]
//              }
//			},
		  ],
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/modes/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				  return res;
			  },
			  data: function (d) {
			  }
	       },
	        columns: [
	            {
	                data: 'name',
	                name: 'Name'
	            },
	            {
	                data: 'code',
	                name: 'Code'
	            },
	           
	            {
	            	data: null,
	            	name: 'Color',
	                orderable: false,
	                searchable: false,
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
	            }
	        ],
	        createdRow: function (nRow, aData, iDataIndex) {
	        	var colorRow = '';
		        colorRow += displayColor(aData);
		        $('td:eq(2)', nRow).html(colorRow);
	            var statusRow = '';
	            statusRow += actionStatus(aData.status);
	            $('td:eq(3)', nRow).html(statusRow);
	            
	            var actionRow = '';
	            actionRow += actionEdit({data: aData, flow: 'dashboard/modes', modal: true});
	            $('td:eq(4)', nRow).html(actionRow);
	            
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