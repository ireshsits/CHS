const Swal = require('sweetalert2');
$(function() {
	$('[data-popup="tooltip"]').tooltip();
});

window.redirectTo = function(e, url){
	console.log('Redireting to >>> '+url);
	e.preventDefault();
	 window.location.replace(url);
}

window.actionStatus = function(status){
	switch(status){
	case 'PEN'	: return '<span class="label label-default">Draft</span>'; break;
	case 'ESC'	: return '<span class="label label-info">Escalated</span>'; break;
//	case 'RES'	: return '<span class="label label-info">Responded</span>'; break;
	case 'REP'	: return '<span class="label label-info">Replied</span>'; break;
	case 'REPP'	: return '<span class="label label-info">Replied-Draft</span>'; break;
	case 'EREP'	: return '<span class="label label-info">Esc-Replied</span>'; break;
	case 'EREREP'	: return '<span class="label label-info">Esc-Replied,Replied</span>'; break;
	case 'REPTRNFR' : return '<span class="label label-info">Replied,Transfered</span>'; break;
	case 'EREPTRNFR': return '<span class="label label-info">Esc-Replied,Transfered</span>'; break;
	case 'COM'	: return '<span class="label label-success">Completed</span>'; break;
	case 'CLO'	: return '<span class="label label-success">Closed</span>'; break;
	case 'VFD'	: return '<span class="label label-success">Verified</span>'; break;
	case 'ACP'	: return '<span class="label label-success">Accepted</span>'; break;
	case 'NACP'	: return '<span class="label label-danger">Not-Accepted</span>'; break;
	case 'INP'	: return '<span class="label label-default">Inprogress</span>'; break;
	case 'ACT'	: return '<span class="label label-success">Active</span>'; break;
	case 'INACT': return '<span class="label label-warning">Inactive</span>'; break;
	case 'REJ'	: return '<span class="label label-danger">Rejected</span>'; break;
	case 'CLOREJ': return '<span class="label label-danger">Close-Re-Opened</span>'; break;
	case 'PAS'	: return '<span class="label label-info">Passed</span>'; break;
	case 'EXST'	: return '<span class="label label-sucess">Exists</span>'; break;
	case 'NEXST': return '<span class="label label-warning">Not Exists</span>'; break;
	case 'CLOROPEN': return '<span class="label label-warning">Re-Opened</span>'; break;
	// case 'COMROPEN': return '<span class="label label-warning">Admin Rejected</span>'; break;
	case 'COMROPEN': return '<span class="label label-warning">Admin Re-Opened</span>'; break; // CR4
	}
}

window.actionNew = function(flow, modal){
	if(!modal){
		var URL = custom_url + '/'+flow+'/setup';
		console.log('Redirect to URL >> '+URL);
		window.location = URL;
	}else{
		displaySetupModalInManage({mode: 'new', flow: flow, refreshFlow: flow });
	}
}

window.actionViewModal= function(flow, modal, id){
	if(!modal){
		var URL = custom_url + '/'+flow+'/setup';
		console.log('Redirect to URL >> '+URL);
		window.location = URL;
	}else{
		displayViewModalInManage({mode: 'new', flow: flow, refreshFlow: flow , id: id });
	}
}

window.actionView = function(data){
	var id = typeof data.data.access_number !== 'undefined' ? data.data.access_number : data.data.id;
	return '<button type="button" class="btn btn-success" href="javascript:void(0)" onclick="actionViewModal(\''+data.flow+'\', \''+data.modal+'\',\''+id+'\')">View</button>';
}

//window.isCurrentUserINPEscalated = function(escalations){
//	if(escalations){
//		var displayReminder = false;
//		$.each(escalations, function(index, escalation){
//			if(escalation.status == 'INP' && AuthUser && AuthUser.user_id_pk == escalation.escalated_to_fk){
//				displayReminder = true;
//			}
//		});
//		return displayReminder;
//	}else{
//		return false;
//	}
//}


window.actionEdit = function(data) {
	/**
	 * CR2 changes
	 */
	if($.inArray(auth_user_role, complaint_raise_roles) !== -1 && data.data.user_roles  && AuthUser.user_id_pk in data.data.user_roles){
			/**
			 * non admin_cc user roles
			 */
			if(data.data.user_roles[AuthUser.user_id_pk] == 'RECPT'){
				var row = '';
				if(data.flow == 'dashboard/complaints'){
					if(data.data.status == 'CLO'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1)
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
					}else if(data.data.status == 'COM'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1){
							row+='<a data-popup="tooltip" title="Mark as Closed" data-original-title="Close" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'CLO\')"><i class="fa fa-folder fa-lg"></i></a>&nbsp;&nbsp;';
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
						}
					}else if(data.data.status == 'PEN'|| data.data.status == 'REJ'){
				    	row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp;&nbsp;'+
		        		'<a data-popup="tooltip" title="Forward" data-original-title="Forward" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+',\'status\',\'INP\')"><i class="fa fa-mail-forward fa-lg"></i></a>&nbsp;&nbsp;'+
				    	'<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';	
					}else{
			        	row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
//			        		 '<a data-popup="tooltip" title="Reminder" data-original-title="Reminder" href="javascript:void(0);" onclick="reminderByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+',\'ALL\')"><i class="fa fa-bell fa-lg"></i></a>&nbsp;&nbsp;';    	
					}
				}else if(data.flow == 'dashboard/categories'){
					if(data.data.status !== null){
						row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
							'<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
					}
				}else if(data.flow == 'dashboard/sub_categories'){
					if(data.data.status !== null){
						row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
							'<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
					}
				}
				return row;
			/**
			 * CR2 chnages
			 */	
			}else if(data.data.user_roles[AuthUser.user_id_pk] == 'OWNER'){
				var row = '';
				if(data.flow == 'dashboard/complaints'){
//					if(data.data.status == 'CLO' || data.data.status == 'COM'){
//						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
					if(data.data.status == 'CLO'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1)
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
					}else if(data.data.status == 'COM'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1){
							row+='<a data-popup="tooltip" title="Mark as Closed" data-original-title="Close" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'CLO\')"><i class="fa fa-folder fa-lg"></i></a>&nbsp;&nbsp;';
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
						}
					}else if(data.data.status == 'ESC' || data.data.user_status[AuthUser.user_id_pk] == 'ESC') {
			        	row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
//			        		 '<a data-popup="tooltip" title="Reminder" data-original-title="Reminder" href="javascript:void(0);" onclick="reminderByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+',\'ESCALATED_ALL\')"><i class="fa fa-bell fa-lg"></i></a>&nbsp;&nbsp;';	   
					}else{
						row+='<a data-popup="tooltip" title="Reply" data-original-title="Reply" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-reply fa-lg"></i></a>&nbsp;&nbsp;';
						if(data.data.user_status[AuthUser.user_id_pk] == 'INP')
							row+='<a data-popup="tooltip" title="Reject" data-original-title="Reject" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',\'true\',\'status\',\'REJ\')"><i class="fa fa-ban fa-lg"></i></a>&nbsp;&nbsp;';
					}
			        return row;
				}
			/**
			 * CR2 chnages
			 */
			}else if(data.data.user_roles[AuthUser.user_id_pk] == 'ESCAL'){
				var row = '';
				if(data.flow == 'dashboard/complaints'){
//					if(data.data.status == 'CLO' || data.data.status == 'COM'){
//						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
					if(data.data.status == 'CLO'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1)
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
					}else if(data.data.status == 'COM'){
						row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
						if($.inArray(auth_user_role, admin_view_roles) !== -1){
							row+='<a data-popup="tooltip" title="Mark as Closed" data-original-title="Close" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'CLO\')"><i class="fa fa-folder fa-lg"></i></a>&nbsp;&nbsp;';
							row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
						}
//					}else if(data.data.status == 'ESC' && !isCurrentUserINPEscalated(data.data.escalations)) {
					}else if(data.data.status == 'ESC' && !(data.data.user_status[AuthUser.user_id_pk] == 'INP')){
			        	row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
//			        		'<a data-popup="tooltip" title="Reminder" data-original-title="Reminder" href="javascript:void(0);" onclick="reminderByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+',\'ESCALATED_ALL\')"><i class="fa fa-bell fa-lg"></i></a>&nbsp;&nbsp;';	   
					}else{
			        	row+='<a data-popup="tooltip" title="Reply" data-original-title="Reply" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-reply fa-lg"></i></a>&nbsp;&nbsp;';
			        	if(data.data.user_status[AuthUser.user_id_pk] == 'INP')
			        		row+='<a data-popup="tooltip" title="Reject" data-original-title="Reject" href="javascript:void(0);" onclick="updateStatus(\'dashboard/complaints/escalations\',\''+data.data.id+'\',true,\'status\',\'REJ\')"><i class="fa fa-ban fa-lg"></i></a>&nbsp;&nbsp;';
					}
			        return row;
				}
			}
	}
	else if($.inArray(auth_user_role, admin_view_roles) !== -1 || $.inArray(auth_user_role, zonal_admin_roles) !== -1 || $.inArray(auth_user_role, regional_admin_roles) !== -1 || $.inArray(auth_user_role, branch_admin_roles) !== -1) { //admin_view_roles
		var row = '';
		if(data.flow == 'dashboard/complaints'){
			if(data.data.status == 'CLO'){
				row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
				if($.inArray(auth_user_role, admin_view_roles) !== -1)
					row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
			}else if(data.data.status == 'COM'){
				row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
				if($.inArray(auth_user_role, admin_view_roles) !== -1){
					row+='<a data-popup="tooltip" title="Mark as Closed" data-original-title="Close" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'CLO\')"><i class="fa fa-folder fa-lg"></i></a>&nbsp;&nbsp;';
					row+='<a data-popup="tooltip" title="Re-open" data-original-title="Re-open" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',true,\'status\',\'INP\')"><i class="fa fa-folder-open fa-lg"></i></a>&nbsp;&nbsp;';
				}
			}else if(data.data.status == 'PEN'){
				row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
			}else{
	        	row+='<a data-popup="tooltip" title="View" data-original-title="View" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-eye fa-lg"></i></a>&nbsp;&nbsp;';
//	        		'<a data-popup="tooltip" title="Reminder" data-original-title="Reminder" href="javascript:void(0);" onclick="reminderByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+',\'ALL\')"><i class="fa fa-bell fa-lg"></i></a>&nbsp;&nbsp;';
			}
		}else if(data.flow == 'dashboard/categories'){
			if(data.data.status !== 'ACT'){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Active" data-original-title="Active" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'ACT\')"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
			if(data.data.status == 'ACT' ){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Inactive" data-original-title="Inactive" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'INACT\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
		}else if(data.flow == 'dashboard/sub_categories'){
			if(data.data.status !== 'ACT'){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Active" data-original-title="Active" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'ACT\')"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
			if(data.data.status == 'ACT' ){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Inactive" data-original-title="Inactive" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'INACT\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
		}else if(data.flow == 'dashboard/modes'){
			if(data.data.status !== 'ACT'){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Active" data-original-title="Active" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'ACT\')"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp'+
				 	 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
			if(data.data.status == 'ACT' ){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Inactive" data-original-title="Inactive" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'INACT\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
		}else if(data.flow == 'dashboard/zones'){
			if(data.data.status !== 'ACT'){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Active" data-original-title="Active" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'ACT\')"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
			if(data.data.status == 'ACT' ){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Inactive" data-original-title="Inactive" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'INACT\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
		}else if(data.flow == 'dashboard/regions'){
			if(data.data.status !== 'ACT'){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Active" data-original-title="Active" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'ACT\')"><i class="fa fa-check-square-o fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
			if(data.data.status == 'ACT' ){
				row+='<a data-popup="tooltip" title="Edit" data-original-title="Edit" href="javascript:void(0);" onclick="editByFlow(\''+data.flow+'\',\''+data.data.id+'\','+data.modal+')"><i class="fa fa-pencil fa-lg"></i></a>&nbsp'+
				     '<a data-popup="tooltip" title="Mark as Inactive" data-original-title="Inactive" href="javascript:void(0);" onclick="updateStatus(\''+data.flow+'\',\''+data.data.id+'\',false,\'status\',\'INACT\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp'+
					 '<a data-popup="tooltip" title="Delete" data-original-title="Delete" href="javascript:void(0);" onclick="deleteByFlow(\''+data.flow+'\','+data.data.id+',\'table\')"><i class="fa fa-trash fa-lg"></i></a>';
			}
		}
		  return row;
	}
}

window.actionDetach = function(data){
	if($.inArray(auth_user_role, admin_view_roles) !== -1 ){
		var row = '';
		if(data.flow == '/dashboard/zones/sync_region'){
		 row+='<a data-popup="tooltip" title="Detach" data-original-title="Detach" href="javascript:void(0);" onclick="dissociate(\''+data.flow+'\',\''+data.data.id+'\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp';
		}
		else if(data.flow == '/dashboard/regions/sync_branch'){
			 row+='<a data-popup="tooltip" title="Detach" data-original-title="Detach" href="javascript:void(0);" onclick="dissociate(\''+data.flow+'\',\''+data.data.id+'\')"><i class="fa fa-remove fa-lg"></i></a>&nbsp';
		}
		return row;
	}
}

window.dissociate = function(flow, id){
	$.ajax({
		url: custom_url + flow,
		type: 'POST',
		data: {'id': id, 'mode': 'dissociate'},
		dataType: 'JSON',
		beforeSend:function(){
		},
		success: function (data) {
			refreshTable(flow);
		},
		error: function (data) {
			var res = data.responseJSON;
			serverErrorHandler(res);
		},
		complete:function(){
		}
	});
}

window.getComplaint = function(id) {

	var searchURI = {id: id}
	var dataComplaint;

	$.ajax({
		url: custom_url + '/dashboard/complaints/getById/'+encodeURIComponent($.param(searchURI)),
		type: 'GET',
		dataType: 'JSON',
		// beforeSend:function() {
		// },
		// success: function (data) {
		// 	dataComplaint = data;
		// },
		// error: function (data) {
		// },
		// complete:function() {
		// }
	}).done(function(result) {
		dataComplaint = result;
	});

	return dataComplaint;

}


window.updateStatus = function(flow, id, modal, field, status, pageReload){
	/**
	 * for dashboard/escalations also id get passed is complaint id. not the escalation id.
	 */
	//count = typeof count !== 'undefined' ? count : 0;
	reloadType = typeof pageReload !== 'undefined' ?  'page' : 'table';
	
	if(modal){
		displaySetupModalInManage({mode:'edit', flow:flow, id:id, field:field, status:status, refreshFlow: flow, reloadType: reloadType, table: $('#table').val()});
	}else{
		var formData = {
				'id': id,
				'field': field,
				'status': status,
				//'count': count
				'table': $('#table').val()
		}
		
		if (!/[/]/.test(flow)) {
			var module = flow.slice(0, -1);
		}else{
			var module = flow.split('/')[flow.split('/').length-1].slice(0, -1);
		}
		if(module == 'complaint'){
			var alertModule = 'Complaint/Compliment';
		}else{
			var alertModule = toTitleCase(module);	
		}
		Swal.fire({
	        title: 'Warning !',
	        text: getStatusUpdateMessage(status,alertModule),
	        type: 'warning',
	        showCancelButton: true,
	        animation: true
	    }).then(function(isConfirm) {
	    	 if (isConfirm.value) {
	    			$.ajax({
	    				url : custom_url + '/' + flow + '/status',
	    				type : 'PUT',
	    				data: formData,
	    				dataType : 'JSON',
	    				beforeSend : function() {
	    				},
	    				success : function(data) {
	    					if(alertModule == 'Solution'){
	    		    			alertService({
	    		    				title : 'Success !',
	    		    				text : alertModule + " updated successfully.",
	    							redirectUrl: data.redirect_url,
	    		    				type : 'success',
	    		    			});
	    					}else{
		    					alertService({
		    						title : 'Success !',
		    						text : alertModule + " updated successfully.",
		    						type : 'success',
		    					});
		    					refreshTable(flow);
	    					}
	    				},
	    				error : function(data) {
	    					alertService({
	    						title : 'Failed !',
	    						text :  "Update failed.",
	    						type : 'error',
	    					});
	    				},
	    				complete : function() {
	    				}
	    			});		 
	    	   }else{
//			    	Swal.fire("Cancelled", "No any reminders sent.", "info");
	   	    		Swal.fire("Cancelled", alertModule +" not updated.", "info");
			    }
	    });

	
	}
}

window.displayFile = function(data){
	if(data.source_status && data.source_url !== null)
		return '<span class="label border-left-primary label-striped source-label"><a href='+(data.source_status && data.source_url !== null?data.source_url:data.default_source_url)+' target="_blank">'+(data.source && data.source !=  null? data.source: "Source")+'</a></span>';
	else
		return '<span class="label border-left-primary label-striped source-label">Not Found</span>'+'</a></span>';
}

window.displayColor = function(data){
	if(data.color !== null)
		return '<span data-popup="tooltip" title="'+data.color+'" data-original-title="View" class="badge" style="background-color:'+data.color+';">&nbsp;</span>'
	else
		return '';
}

window.refreshTable = function(flow) {
	if ($.fn.DataTable.isDataTable(getTableByFlow(flow)) ) {
		console.log('Refreshing Table: >>'+flow);
		var manageTable = $(getTableByFlow(flow)).DataTable();
//		manageTable.search('').columns().search('').order( [[ 0, 'asc' ]] ).draw(false);
//		manageTable.ajax.reload();
		manageTable.ajax.reload(null, false);
		manageTable.order( [[ 0, 'asc' ]] ).draw(false);
	}
}
window.refreshCalendar = function(flow) {
	console.log('Refreshing Calendar: >>'+flow);
	var calendar = $(getCalendarByFlow(flow));
	$(calendar).fullCalendar( 'refetchEvents' );
}

window.destroyTable = function(flow){
	if ($.fn.DataTable.isDataTable(getTableByFlow(flow)) ) {
		var manageTable = $(getTableByFlow(flow)).DataTable();
		manageTable.clear().destroy();
		console.log('Destroyed Table: >>'+flow);
	}
}

window.reminderByFlow = function(flow, id, modal, role){
	var params = {'role': role, 'id' : id };
		if (!/[/]/.test(flow)) {
			var module = flow.slice(0, -1);
		}else{
			var module = flow.split('/')[1].slice(0, -1);
		}
		if(module == 'complaint'){
			var alertModule = 'Complaint/Compliment';
		}else{
			var alertModule = toTitleCase(module);	
		}
		
		Swal.fire({
	        title: 'Warning !',
	        text: 'Will send reminders for owners and if any escaleted person(s). Continue?',
	        type: 'warning',
	        showCancelButton: true,
	        animation: true
	    }).then(function(isConfirm) {
		    if (isConfirm.value) {
		    	$.ajax({
		    		url : custom_url + '/' + flow + '/send_reminder/' + encodeURIComponent($.param(params)),
		    		type : 'GET',
		    		dataType : 'JSON',
		    		beforeSend : function() {
		    		},
		    		success : function(data) {
		    			alertService({
		    				title : 'Success !',
		    				text : alertModule + " reminder(s) sent successfully.",
		    				type : 'success',
		    			});
		    			refreshTable(flow);
		    		},
		    		error : function(data) {
		    			alertService({
		    				title : 'Failed !',
		    				text : alertModule + " reminder(s) sent failed.",
		    				type : 'error',
		    			});
		    		},
		    		complete : function() {
		    		}
		    	});
		    }else{
		    	Swal.fire("Cancelled", "No any reminders sent.", "info");
		    }
	    });
}

window.editByFlow = function(flow, id, modal, subMode, sid, table) {

	var activeTab = $(".tab-content").find(".active").attr('id');
	var preTable = $("#"+activeTab).data("ttype");

	if(typeof subMode !== 'undefined' && typeof sid !== 'undefined') {
		var editURI = {'mode': 'edit', 'id': id, 'subMode': subMode, 'sid': sid, 'pretable': preTable};
	} else {
		var editURI = {'mode': 'edit', 'id': id, 'pretable': preTable};
	}

	if(!modal) {
		var URL = custom_url + '/'+flow+'/setup/'+encodeURIComponent($.param(editURI));
		console.log('Redirect to URL >> '+URL);
		window.location = URL;
	} else {
		displaySetupModalInManage({mode: 'edit', flow: flow, id: id, refreshFlow: flow});
	}

}

window.deleteByFlow = function(flow, id, mode) {
	var params = {
		'id' : id
	};
	if (!/[/]/.test(flow)) {
		var module = flow.slice(0, -1);
	}else{
		var module = flow.split('/')[1].slice(0, -1);
	}
	if(module == 'complaint'){
		var alertModule = 'Complaint/Compliment';
	}else{
		var alertModule = toTitleCase(module);	
	}
	Swal.fire({
        title: 'Warning !',
        text: 'Deleting will remove the '+ alertModule +'. Continue?',
        type: 'warning',
        showCancelButton: true,
        animation: true
    }).then(function(isConfirm) {
	    if (isConfirm.value) {
	    	$.ajax({
	    		url : custom_url + '/' + flow + '/delete/' + encodeURIComponent($.param(params)),
	    		type : 'DELETE',
	    		dataType : 'JSON',
	    		beforeSend : function() {
	    		},
	    		success : function(data) {
	    			if(mode == 'table'){
		    			alertService({
		    				title : 'Success !',
		    				text : alertModule + " deleted successfully.",
		    				type : 'success',
		    			});
		    			refreshTable(flow);
	    			}else{
		    			alertService({
		    				title : 'Success !',
		    				text : alertModule + " deleted successfully.",
							redirectUrl: data.redirect_url,
		    				type : 'success',
		    			});
	    			}
	    		},
	    		error : function(data) {
	    			alertService({
	    				title : 'Failed !',
	    				text : "Deletion failed.",
	    				type : 'error',
	    			});
	    		},
	    		complete : function() {
	    		}
	    	});
	    }else{
	    	Swal.fire("Cancelled", alertModule +" is safe now.", "info");
	    }
    });
}

window.amendmentByFlow = function(flow, id, modal, subMode, sid){
	if(typeof subMode !== 'undefined' && typeof sid !== 'undefined'){
		var params = {mode: 'edit', flow: flow, id: id, refreshFlow: flow};
	}else{
		var params = {mode: 'new', flow: flow, id: id, refreshFlow: flow};
	}
	if(!modal){
	}else{
		displaySetupModalInManage(params);
	}
}

window.actionExport = function(data){
	var formData = {
			'filters': data.filters,
			'mode': data.mode
	}
	window.open(custom_url + '/' + data.flow + '/export?'+ $.param(formData));
}

window.decodeToHtml = function(complaint){
	return $('<div />').append($.parseHTML(complaint)).text();
}

window.displayComplaintUsers = function(users){
	var table = $('<table width="90%"/>');
	table.append('<tr><th>Name</th><th>Email</th><th>Branch/Dept</th><th>Role</th><th>Status</th></tr>' );
	$.each(users, function(index, user){
//		if(parseInt(AuthUser.user_id_pk) !== parseInt(user.user_id_fk))
			table.append( '<tr><td>' + user.user.first_name +' '+ user.user.last_name +'</td><td>' + user.user.email +'</td><td>' + user.user.department_user.department.name +'</td><td>' + (user.user_role == "RECPT"? "Recipient": (user.user_role == "OWNER"? "Owner" : "Escalate")) +'</td><td>'+actionStatus(user.status)+'</td></tr>' );
	});
	if(table.find("tr").length > 1)
		return table[0].outerHTML;
	else
		return 'No users found';
}

window.displayComplaintNotificationOtherUsers = function(users){
	var table = $('<table width="90%"/>');
	table.append('<tr><th>Name</th><th>Email</th></tr>' );
	$.each(users, function(index, user){
//		if(parseInt(AuthUser.user_id_pk) !== parseInt(user.user_id_fk))
			table.append( '<tr><td>' + user.user.first_name +' '+ user.user.last_name +'</td><td>' + user.user.email +'</td></tr>' );
	});
	if(table.find("tr").length > 1)
		return table[0].outerHTML;
	else
		return 'No users found';
}

window.copyToClipboard = function(id) {
	var $body = document.getElementsByTagName('body')[0];
	var element = document.getElementById(id).innerHTML;
	var $tempInput = document.createElement('INPUT');
	$body.appendChild($tempInput);
	$tempInput.setAttribute('value', element)
	$tempInput.select();
	document.execCommand('copy');
	$body.removeChild($tempInput);
}

function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}

function getComplaint(id) {

	var searchURI = {id: id}
	var url= custom_url + '/dashboard/complaints/getById/'+encodeURIComponent($.param(searchURI));
	let xmlHttpReq = new XMLHttpRequest();
	xmlHttpReq.open("GET", url, false); 
	xmlHttpReq.send(null);
	return JSON.parse(xmlHttpReq.responseText);
  }

function displaySetupModalInManage(data){
	
	var complaint;
	if (data.id) {
		complaint = getComplaint(data.id);
	}

	var modal;
	if (complaint && complaint.is_reporting_complaint != 'undefined' && complaint.is_reporting_complaint == true && data.status == 'CLO') {
		modal = getModalByFlow('dashboard/complaints/reporting');
		$(modal+' input[name=is_reporting]').val(complaint.is_reporting_complaint);
	} else {
		modal = getModalByFlow(data.flow);
	}

	// var modal = getModalByFlow(data.flow);
	$(modal+' #mode').val(data.mode);
	$(modal+' #refresh').val(true);
	$(modal+' #refresh_table').val(data.refreshFlow);
	if(data.reloadType)
		$(modal+' #refresh_type').val(data.reloadType);
	else
		$(modal+' #refresh_type').val('table');
	$(modal+' input[name=id]').val(data.id);
	$(modal+' input[name=flow]').val(data.flow);
	$(modal+' input[name=field]').val(data.field);
	$(modal+' input[name=status]').val(data.status);
	$(modal+' input[name=table]').val(data.table);
    $(modal).modal('show');
}

function displayViewModalInManage(data){
	var modal = getModalByFlow(data.flow);
	$(modal+' input[name=id]').val(data.id);
    $(modal).modal('show');
}

function getCalendarByFlow(flow){
	switch(flow){
	case 'holidays'	: return '#holiday-calendar'; break;
	default: 
	}
}

function getModalByFlow(flow){
	switch(flow){
	case 'dashboard/complaints'						: return '#modal_complaint_action_form';	
	case 'dashboard/complaints/escalations'			: return '#modal_complaint_action_form';
	case 'dashboard/complaints/solutions/amendment'	: return '#modal_solution_amendment_form';
	case 'dashboard/modes'	    					: return '#modal_mode_form';
	case 'dashboard/categories'						: return '#modal_category_form';
	case 'dashboard/sub_categories'					: return '#modal_sub_category_form';
//	case 'dashboard/zones'							: return '#modal_zone_form';
//	case 'dashboard/regions'						: return '#modal_region_form';
	case 'dashboard/complaints/reporting'			: return '#modal_reporting_complaint_action_form';	
	default: 
	}
}

window.viewResources = function(){
	$('.view-resources').show();
}

function getTableByFlow(flow){
	switch(flow){
	case 'dashboard/complaints'	   			: return '.lodged-tables';
	case 'dashboard/complaints/escalations'	: return '.lodged-tables';
	case 'dashboard/modes'	       			: return '.modes-table';
	case 'dashboard/categories'	   			: return '.categories-table';
	case 'dashboard/sub_categories'			: return '.sub-categories-table';
	case 'dashboard/zones'	   				: return '.zones-table';
	case 'dashboard/regions'				: return '.regions-table';
	case 'dashboard/reports'	   			: return '.reports-table';
	case 'dashboard/analysis'	   			: return '.analysis-table';
	case 'dashboard/month/resol'   			: return '.m-complaint-time-resolution-table';
	case 'dashboard/annual/resol'   		: return '.a-complaint-time-resolution-table';
	case 'dashboard/branches'				: return '.branch-department-table';
	case '/dashboard/zones/sync_region'		: return '.regions-table';
	case '/dashboard/regions/sync_branch'	: return '.branch-department-table';
	default: 
	}
}


window.getStatusUpdateMessage = function(status, alertModule){
	switch(status){
	case 'PEN'	: return 'Updating will remove all the related items. Continue?';
	case 'CLO'	: return 'Complaint/Compliment will Closed. Continue? ';
	case 'INP'	: return 'Complaint/Compliment will forwarded to Complaint Owner(s). Continue? ';
	case 'ACT'	: return  alertModule+' will Activated. Continue?';
	case 'INACT': return  alertModule+' will Inactive. Continue?';
	case 'REJ'	: return 'Complaint/Compliment will rejected and reopened. Continue? ';
	case 'VFD'	: return 'Solution will mark as Verified. Continue? ';
	case 'ACP'	: return 'Solution will mark as Accpeted. Continue? ';
	case 'NACP'	: return 'Solution will mark as Not Accepted. Continue? ';
	}
}