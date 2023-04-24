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
	init_zones();
	eventHandler();
});

function eventHandler(){
	$(".detail-table").parents('td').css("text-align", "inherit");
}

function displayRegions(regions){
	var row = '<div><ul>';
	$.each(regions, function(index,region){
		row+='<li>'+region.name+'</li>';
	});
	return row;
}

/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
	return '<div class="slider" name>'+
			'<table class="detail-table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%">'+
			'<tr>'+
            '<td width="30%">Regions:</td>'+
            '<td>'+displayRegions(d.regions)+'</td>'+
            '</tr>'+
//	        '<tr>'+
//	            '<td width="30%">Rejected By:</td>'+
//	            '<td>'+d.rejected_by+'</td>'+
//	        '</tr>'+        
//	        '<tr>'+
//	        	'<td width="30%">Reject Reason:</td>'+
//	        	'<td>'+d.reject_reason+'</td>'+
//	        '</tr>'+
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


function init_zones() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_Complaints');
	
	var handleDataTableButtons = function() {
		table = $(".zones-table").DataTable({
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
			  url: custom_url + "/dashboard/zones/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				  return res;
			  },
			  data: function (d) {
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
	                data: 'name',
	                name: 'Name'
	            },
	            {
	                data: 'number',
	                name: 'Number'
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
	                data: 'regions_count',
	                name: 'Regions Count'
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
	            var statusRow = '';
	            statusRow += actionStatus(aData.status);
	            $('td:eq(6)', nRow).html(statusRow);
	            
	            var actionRow = '';
	            actionRow += actionEdit({data: aData, flow: 'dashboard/zones', modal: false});
	            $('td:eq(7)', nRow).html(actionRow);
	            
	        }
		});
	};

	TableManageButtons = function() {
	  "use strict";
	  return {
		init: function() {
		  handleDataTableButtons();
		  detailPanelTrigger('.zones-table', table);
		}
	  };
	}();

	TableManageButtons.init();
	
};