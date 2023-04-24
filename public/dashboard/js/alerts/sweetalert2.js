/* ------------------------------------------------------------------------------
*
*  # PNotify notifications
*
*  Specific JS code additions for components_notifications_pnotify.html page
*
*  Version: 1.1
*  Latest update: Feb 1, 2016
*
* ---------------------------------------------------------------------------- */
const Swal = require('sweetalert2');
$(function() {

});

window.alertService = function(params) {
	Swal.fire({
        title: params.title,
        text: params.text,
        html: params.html,
        type: alertType(params.type),
        showCancelButton: params.cancelButton,
        animation: true,
//        timer: '5000'
    }).then(function(isConfirm) {
	    if (isConfirm.value) {
	    	if(params.redirectUrl){
	    		window.location.replace(params.redirectUrl);
	    	}
	      } else {
	    	  
	      }
    });
}

function alertType(type){
    switch(type){
        case 'success': return 'success'; break;
        case 'error': return 'error'; break;
        case 'warning': return 'warning'; break;
        case 'info': return 'info'; break;
        case 'question': return 'question'; break;
    }
}