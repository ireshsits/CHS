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
	init_regions();
	eventHandler();
});

function init(){
	 if($('#region-select-form #zone_id_pk').length !== 0){
		 columns = [
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
             data: 'number',
             name: 'Number'
         },	            
         {
             data: 'zone_name',
             name: 'Zone Name'
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
             data: 'branches_count',
             name: 'Branches Count'
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
	$(".detail-table").parents('td').css("text-align", "inherit");
}

function displayBranches(branches){
	var row = '<div><ul>';
	$.each(branches, function(index,brn){
		row+='<li>'+brn.name+'</li>';
	});
	return row;
}

/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
	return '<div class="slider" name>'+
			'<table class="detail-table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%">'+
			'<tr>'+
            '<td width="30%">Branches:</td>'+
            '<td>'+displayBranches(d.branches)+'</td>'+
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


function init_regions() {
	
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_Regions');
	
	var handleDataTableButtons = function() {
		table = $(".regions-table").DataTable({
		  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  dom: "Blfrtip",
		  buttons: [
		  ],
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/regions/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				  return res;
			  },
			  data: function (d) {
				  if($('#region-select-form #zone_id_pk').length !== 0){
					  d.zone_id_fk = $('#region-select-form #zone_id_pk').val();
				  }
			  }
	       },
	        columns: columns,
	        createdRow: function (nRow, aData, iDataIndex) { 
	            if($('#region-select-form #zone_id_pk').length !== 0){
		            var statusRow = '';
		            statusRow += actionStatus(aData.status);
		            $('td:eq(4)', nRow).html(statusRow);
		            var detachRow = '';
		            detachRow += actionDetach({data: aData, flow: '/dashboard/zones/sync_region'});
		            $('td:eq(5)', nRow).html(detachRow);
	            }else{
		            var statusRow = '';
		            statusRow += actionStatus(aData.status);
		            $('td:eq(7)', nRow).html(statusRow);
		            var actionRow = '';
		            actionRow += actionEdit({data: aData, flow: 'dashboard/regions', modal: false});
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
		  detailPanelTrigger('.regions-table', table);
		}
	  };
	}();

	TableManageButtons.init();
	
};