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
var search = "";
var ref_no = $("#reference_number").val();
var nic = $("#nic").val();
var account_no = $("#account_no").val();
var contact_no = $("#contact_no").val();

$(function() {
//	init_pending_complaints();
//	init_completed_complaints();
    var tablesClasses = [
//                            '.'+type+'-table-pending', 
//                            '.'+type+'-table-forward',
//                            '.'+type+'-table-inprogress-escalated',
//                            '.'+type+'-table-completed', 
                            '.'+type+'-table-search'
                        ];
    $.each(tablesClasses, function(index, tclass){
        var element =  $(tclass);
        if (typeof(element) != 'undefined' && element != null)
            init_search_tables(tclass);
    });
    
//    initComplaintRefNumber();
//    initComplaintCustomerNIC();
//    initComplaintAccountNo();
    initPageSelectors();
    eventHandler();
//    initSearchValidation();
    
});

// select boxes
window.initComplaintRefNumber = function(){
    $.ajax({
        url: custom_url + '/dashboard/search/get/filter',
        type: 'POST',
        dataType: 'JSON',
        beforeSend:function(){
        },
        data: { 
            type: 'REF'
        },
        success: function (data) {
            $('.select-reference-number-search').select2({
                placeholder: "Select / Insert Ref No.",
                minimumResultsForSearch: 10,
                data: data.items,
                allowClear: true,
                tags: true
            });
        }
    });
};

window.initComplaintCustomerNIC = function(){
    $.ajax({
        url: custom_url + '/dashboard/search/get/filter',
        type: 'POST',
        dataType: 'JSON',
        beforeSend:function(){
        },
        data: { 
            type: 'NIC'
        },
        success: function (data) {
            $('.select-nic-search').select2({
                placeholder: "Select / Insert NIC",
                minimumResultsForSearch: 1,
                data: data.items,
                allowClear: true,
                tags: true
            });
        }
    });
};

window.initComplaintAccountNo = function(){
    $.ajax({
        url: custom_url + '/dashboard/search/get/filter',
        type: 'POST',
        dataType: 'JSON',
        beforeSend:function(){
        },
        data: { 
            type: 'ACCNO'
        },
        success: function (data) {
            $('.select-account-no-search').select2({
                placeholder: "Select / Insert Account No.",
                minimumResultsForSearch: 1,
                data: data.items,
                allowClear: true,
                tags: true
            });
        }
    });
};

// all select boxes
function initPageSelectors() {

    var init_selectors = [	
        {
            class: '.select-reference-number-search',
            type: 'REF',
            placeholder: 'Select / Insert Ref No.'
        },
        {
            class: '.select-nic-search',
            type: 'NIC',
            placeholder: 'Select / Insert NIC'
        },
        {
            class: '.select-account-no-search',
            type: 'ACCNO',
            placeholder: 'Select / Insert Account/Card No.'
        },
        {
            class: '.select-contact-no-search',
            type: 'CNTNO',
            placeholder: 'Select / Insert Contact No.'
        }
    ];

    $.each(init_selectors, function(index, selector){
//        initSetupSelectorsWithAjax(selector);
        $.ajax({
            url: custom_url + '/dashboard/search/get/filter',
            type: 'POST',
            dataType: 'JSON',
            beforeSend:function(){
            },
            data: { 
                type: selector.type
            },
            success: function (data) {
                $(selector.class).select2({
                    placeholder: selector.placeholder,
                    minimumResultsForSearch: 1,
                    data: data.items,
                    allowClear: true,
                    tags: true
                });
            }
        });
        
    });
    
}

/*
 * table action function
 */

window.editByFlow = function(flow, id, modal, subMode, sid) {
    
    var editURI = {'mode': 'edit', 'id': id};
    var URL = custom_url + '/dashboard/search/complaint/'+encodeURIComponent($.param(editURI));
    console.log('Redirect to URL >> '+URL);
//    window.location = URL;
//    URL = '/'+flow+'/setup/'+encodeURIComponent($.param(editURI));
    window.open(URL, '_blank');
    
};

window.tableActions = function(data) {
 
    var row = '';

    if(data.flow == 'dashboard/complaints'){

        row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';

    }

    return row;

};

/*
 * Validate search fields
 */
function initSearchValidation(){
	
    jQuery.validator.addMethod("nicFormat", function(value, element) {
        return this.optional( element ) || /^([0-9]{9}[x|X|v|V]|[0-9]{12})$/.test( value );
    }, 'The NIC format invalid.');

    jQuery.validator.addMethod("nicNumericValidity", function(value, element) {
        return this.optional( element ) || !(/([0-9])\1{9,}/i.test( value ));
    }, 'Invalid numeric format.');

    $("#complaint-search-form").validate({
//            ignore: 'input[type=hidden]',
        rules: {
            "reference_number":{
//                required: true               
            },
            "nic": { 
//                        required: true,
                nicFormat: true,
                nicNumericValidity: true
            }, 
            "account_no":{       
                number: true
            }
        },
        messages: {
            "reference_number":{          
                number: "Reference number invalid."
            },
            "nic": { 
                required: "Nic is required."               
            }, 
            "account_no":{          
                number: "Account number invalid. Enter only numbers."
            }		
        },
        highlight: function( label ) {
            $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
//            if($(label).is('select')){
//                $(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-success').addClass('has-select-error');
//            }
        },
        success: function( label ) {
            $(label).closest('.form-group').removeClass('has-error');
//            if($(label).is('select')){
//                $(label).parent().find('.select2-container .selection .select2-selection').removeClass('has-select-error');
//            }
            label.remove();
        },
        errorPlacement: function( error, element ) {
            if(element.hasClass('select2-hidden-accessible')){
                error.appendTo( element.parent() );
            } else {
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


/**
 * Search function
 */
$("#btn-search").on("click", function() {

    initSearchValidation();
    
    ref_no = $("#reference_number").val();
    nic = $("#nic").val();
    account_no = $("#account_no").val();
    contact_no = $("#contact_no").val();

    var tablesClasses = [
//        '.'+type+'-table-pending', 
//        '.'+type+'-table-forward',
//        '.'+type+'-table-inprogress-escalated',
//        '.'+type+'-table-completed', 
        '.'+type+'-table-search'
    ];

    $.each(tablesClasses, function(index, tclass){
        var element =  $(tclass);
        if (typeof(element) != 'undefined' && element != null)
            $(element).DataTable().ajax.reload()
    });

});

/**
 * Search Clear function
 */
$("#btn-search-clear").on("click", function() {

    $("#reference_number").val("");
    $("#nic").val("");
    $("#account_no").val("");
    $("#contact_no").val("");
    
    ref_no = $("#reference_number").val();
    nic = $("#nic").val();
    account_no = $("#account_no").val();
    contact_no = $("#contact_no").val();

    var tablesClasses = [
//        '.'+type+'-table-pending', 
//        '.'+type+'-table-forward',
//        '.'+type+'-table-inprogress-escalated',
//        '.'+type+'-table-completed', 
        '.'+type+'-table-search'
    ];
    
    initPageSelectors();

    $.each(tablesClasses, function(index, tclass){
        var element =  $(tclass);
        if (typeof(element) != 'undefined' && element != null)
            $(element).DataTable().ajax.reload()
    });

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
            if(!d.is_reporting_complaint) { 
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

function init_search_tables(element) {

    var table;
    if( typeof ($.fn.DataTable) === 'undefined'){ return; }
    console.log('init_Complaints');

    var handleDataTableButtons = function() {
        
        $.extend($.fn.dataTable.defaults, {
            autoWidth: true,
            dom: '<"datatable-header"Blp><"datatable-scroll-wrap"t><"datatable-footer"ip>',
//            dom: '<"datatable-header"fBlp><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
//                search: '<span>Filter:</span> _INPUT_',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            }
        });
        
        table = $(element).DataTable({
            //  "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
            "order": [[ 0, "desc" ]],
//            dom: "Blfrtip", // Blfrtip
            buttons: [],
            serverSide: true,
            responsive: true,
            ajax: {
                url: custom_url + "/dashboard/search/get/results",
                type: "POST",
                dataFilter: function (res) {
                    return res;
                },
                data: function (d) {
                    if(element == '.'+type+'-table-search'){
                        d.table = 'SEARCH',
                        d.status = '',
                        d.type = typeCode,
                        d.search = search,
                        d.ref_no = ref_no,
                        d.nic = nic,
                        d.account_no = account_no,
                        d.contact_no = contact_no
                    }
                }
            },
            columns: [
                {
                    data: 'complaint_id_pk',
                    name: 'complaint_id_pk',
                    visible:false,
                    orderable: true,
                    searchable: false
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
                    data: 'complainant',
                    name: 'customer'
                },
                {
                    data: 'complainant',
                    name: 'nic'
                },
                {
                    data: 'account_no',
                    name: 'account_no'
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
//                {
//                    data: null,
//                    name: 'Source',
//                    orderable: false,
//                    searchable: false
//                },
                {
                    data: null,
                    name: 'status'
                },
                {
                    data: null,
                    name: null,
                    orderable: false,
                    searchable: false
                }
            ],
            createdRow: function (nRow, aData, iDataIndex) {

                $(nRow).addClass(aData.priority_level_text);

//                var fileRow = '';
//                fileRow += displayFile(aData);
//                $('td:eq(6)', nRow).html(fileRow);
                console.log(aData);
                var customerRow = '';
                customerRow = aData.complainant_id_fk ? aData.complainant.first_name+' '+aData.complainant.last_name : '';
                $('td:eq(2)', nRow).html(customerRow);
                
                var customerNICRow = '';
                customerNICRow = aData.complainant_id_fk ? aData.complainant.nic : '';
                $('td:eq(3)', nRow).html(customerNICRow);
                
                var statusRow = '';
                statusRow += actionStatus((aData.reopened.status?aData.reopened.type+'ROPEN':aData.status));
                $('td:eq(9)', nRow).html(statusRow);

                var actionRow = '';
                actionRow += tableActions({data: aData, flow: 'dashboard/complaints', modal: false});
                $('td:eq(10)', nRow).html(actionRow);

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