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
var pTable;
var cTable;

$(function() {
//	init_pending_complaints();
//	init_completed_complaints();
	var tablesClasses = [
				'.'+type+'-table-pending', '.'+type+'-table-forward','.'+type+'-table-inprogress-escalated',
				'.'+type+'-table-completed', '.'+type+'-table-closed'];
	$.each(tablesClasses, function(index, tclass){
		var element =  $(tclass);
		if (typeof(element) != 'undefined' && element != null)
			init_complaint_tables(tclass);
	});
	eventHandler();
});

function eventHandler(){
	$(".detail-table").parents('td').css("text-align", "inherit");
}

/* Formatting function for row details - modify as you need */
function format ( d ) {
    // `d` is the original data object for the row
    var type = 'Complaint';
    var typeUser = 'Complainant';
    if (d.type == 'CMPLA') {
    	type = 'Complaint';
    	typeUser = 'Complainant';
    } else {
    	type = 'Compliment';
    	typeUser = 'Complimenter\'s';
    }

	var row = '<div class="slider '+d.priority_level_text+'" name>'+
			'<table class="detail-table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;width:100%">';
		if(!d.is_reporting_complaint){    
			row+='<tr>'+
	            '<td width="30%">Users:</td>'+
	            '<td>'+displayComplaintUsers(d.complaint_users)+'</td>'+
	        '</tr>';
		}
		if(d.priority_level){
	        row+='<tr>'+
	        	'<td width="30%">Priority level:</td>'+
	        	'<td class="'+d.priority_level_text+'">'+d.priority_level_text+'</td>'+
	        '</tr>';
		}
		if(d.account_no){
	        row+='<tr>'+
	        	'<td width="30%">Account/Card Number:</td>'+
	        	'<td>'+d.account_no+'</td>'+
	        '</tr>';
		}
	     	row+='<tr>'+
	        	'<td width="30%">Reminder Count:</td>'+
	        	'<td>'+d.reminder_count+'</td>'+
	        '</tr>';
		if(d.complainant){
			row+='<tr>'+
	            // '<td width="30%">Complainant Name:</td>'+
	            '<td width="30%">'+typeUser+' Name:</td>'+ // CR4
	            '<td>'+d.complainant.first_name+' '+d.complainant.last_name+'</td>'+
	        '</tr>'+
	        '<tr>'+
	        	// '<td width="30%">Complainant NIC:</td>'+
	        	'<td width="30%">'+typeUser+' NIC:</td>'+ // CR4
	        	'<td>'+d.complainant.nic+'</td>'+
	        '</tr>'+
	    		// '<td width="30%">Complainant Contact Number:</td>'+
	    		'<td width="30%">'+typeUser+' Contact Number:</td>'+ // CR4
	    		'<td>'+d.complainant.contact_no+'</td>'+
	    	'</tr>';
		}
		if(d.close_date){
	    	row+='</tr>'+
    			'<td width="30%">Event Closed Date:</td>'+
    			'<td>'+d.close_date+'</td>'+
    		'</tr>';
		}
	        row+='<tr>'+
            	// '<td width="30%">Complaint:</td>'+
            	'<td width="30%">'+type+':</td>'+ // CR4
            	'<td id="complaint-'+d.id+'">'+decodeToHtml(d.complaint)+'</td>'+
            '</tr>';
	    if(d.reject_reason){
	    	row+='<tr>'+
    	    	// '<td width="30%">Complaint Reject Reason:</td>'+
    	    	'<td width="30%">'+type+' Reject Reason:</td>'+ // CR4
    	    	'<td>'+d.reject_reason+'</td>'+
            '</tr>';
	    }
		if(d.complaint_notification_other_users){
            row+='<tr>'+
                    '<td width="30%">Notify To:</td>'+
                    '<td>'+displayComplaintNotificationOtherUsers(d.complaint_notification_other_users)+'</td>'+
                '</tr>';
        }
	    '</table>'+
	  '</div>';
	
	return row;
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
//            $('#complaint-'+row.data().id).empty().html(row.data().complaint);
            tr.addClass('shown');
            $('div.slider', row.child()).slideDown();
        }
    } );
}

function init_complaint_tables(element) {
	var table;
	if( typeof ($.fn.DataTable) === 'undefined'){ return; }
	console.log('init_Complaints');
	
	var handleDataTableButtons = function() {
		 $.extend( $.fn.dataTable.defaults, {
	        autoWidth: true,
	        dom: '<"datatable-header"fBlp><"datatable-scroll-wrap"t><"datatable-footer"ip>',
	        language: {
	            search: '<span>Filter:</span> _INPUT_',
	            lengthMenu: '<span>Show:</span> _MENU_',
	            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
	        }
	    });
		table = $(element).DataTable({
		//  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
		  "order": [[ 0, "desc" ]],
		//  dom: "Blfrtip",
		  buttons: [],
		  serverSide: true,
		  responsive: true,
		  ajax: {
			  url: custom_url + "/dashboard/complaints/get/all",
			  type: "POST",
			  dataFilter: function (res) {
				  return res;
			  },
			  data: function (d) {
				  if(element == '.'+type+'-table-pending'){
					 d.table = 'PEN',
					 d.status = 'PEN,REJ',
					 d.type = typeCode
				  }else if(element == '.'+type+'-table-forward'){
					 d.table = 'FORWD',
					 d.status = 'INP,ESC,REP',
					 d.type = typeCode
				  }else if(element == '.'+type+'-table-inprogress-escalated'){
					 d.table = 'INPESC',
					 d.status = 'INP,ESC,REP',
					 d.type = typeCode
			  	  }else if(element == '.'+type+'-table-completed'){
			  		 d.table = 'COM',
					 d.status = 'COM',
					 d.type = typeCode
			  	  }else if(element == '.'+type+'-table-closed'){
				  	 d.table = 'CLO',
					 d.status = 'CLO',
					 d.type = typeCode
			  	  }
			  }
	       },
	        columns: [
		         {
	                data: 'complaint_id_pk',
	                name: 'complaint_id_pk',
					visible:false,
	                orderable: true,
	                searchable: false,
            	},
	            {
	                className:'details-control',
	                orderable:false,
	                searchable:false,
	                data:null,
	                defaultContent: ''
	            },
	            {
	                data: 'reference_number',
	                name: 'reference_number'
	            },
	            {
	                data: 'area_name',
	                name: 'area.name'
	            },
	            {
	                data: 'category_name',
	                name: 'category.name'
	            },
	            {
	                data: 'open_date',
	                name: 'open_date'
	            },
	            {
	                data: 'complaint_mode',
	                name: 'complaintMode.name'
	            },
	            {
	            	data: null,
	            	name: 'Source',
	                orderable: false,
	                searchable: false,
	            },
	            {
	            	data: null,
	            	name: 'status',
	            },
	            {
	                data: null,
	                name: null,
	                orderable: false,
	                searchable: false,
	            }
	        ],
	        createdRow: function (nRow, aData, iDataIndex) {
	        	
	        	$(nRow).addClass(aData.priority_level_text);
	        	
	            var fileRow = '';
	            fileRow += displayFile(aData);
	            $('td:eq(6)', nRow).html(fileRow);
	            
	            var statusRow = '';
	            statusRow += actionStatus((aData.reopened.status?aData.reopened.type+'ROPEN':aData.status));
	            $('td:eq(7)', nRow).html(statusRow);
	            
	            var actionRow = '';
	            actionRow += actionEdit({data: aData, flow: 'dashboard/complaints', modal: false});
	            $('td:eq(8)', nRow).html(actionRow);
	            
	        },
	        drawCallback: function ( settings ) {
	            var count = table.rows().data().length;
				if(element == '.'+type+'-table-pending'){
					$('.badge-pending').html(count);
				}else if(element == '.'+type+'-table-forward'){
					$('.badge-forward').html(count);
				}else if(element == '.'+type+'-table-inprogress-escalated'){
					$('.badge-inprogress-escalated').html(count);
				}else if(element == '.'+type+'-table-completed'){
					$('.badge-completed').html(count);
				}else if(element == '.'+type+'-table-closed'){
					$('.badge-closed').html(count);
				}
			},
	        initComplete: function (settings, json) {
//				console.log('running');
//	            var count = table.rows().data().length;
//				if(element == '.'+type+'-table-pending'){
//					$('.badge-pending').html(count);
//				}else if(element == '.'+type+'-table-forward'){
//					$('.badge-forward').html(count);
//				}else if(element == '.'+type+'-table-inprogress-escalated'){
//					$('.badge-inprogress-escalated').html(count);
//				}else if(element == '.'+type+'-table-completed'){
//					$('.badge-completed').html(count);
//				}else if(element == '.'+type+'-table-closed'){
//					$('.badge-closed').html(count);
//				}
            }
		});
	};

	TableManageButtons = function() {
	  "use strict";
	  return {
		init: function() {
		  handleDataTableButtons();
		  detailPanelTrigger(element, table);
		}
	  };
	}();

	TableManageButtons.init();
	
};