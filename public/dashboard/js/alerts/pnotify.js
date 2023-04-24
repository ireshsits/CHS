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

$(function() {

});

window.alertService = function(params) {
    new PNotify({
        title: params.title,
        text: params.text,
        icon: alertIcon(params.type),
        type: params.type,
        addclass: alertClass(params.type)
    });
}

function alertIcon(type){
    switch(type){
        case 'primary': return 'icon-info22'; break;
        case 'success': return 'icon-checkmark3'; break;
        case 'error': return 'icon-blocked'; break;
        case 'warning': return 'icon-info22'; break;
        case 'info': return 'icon-info22'; break;
    }
}

function alertClass(type){
    switch(type){
        case 'primary': return 'bg-primary'; break;
        case 'success': return 'bg-success'; break;
        case 'error': return 'bg-danger'; break;
        case 'warning': return 'bg-warning'; break;
        case 'info': return 'bg-info'; break;
    }
}

function init_PNotify() {
    "undefined" != typeof PNotify && (console.log("init_PNotify"),
    new PNotify({
        title: "PNotify",
        type: "info",
        text: "Welcome. Try hovering over me. You can click things behind me, because I'm non-blocking.",
        nonblock: {
            nonblock: !0
        },
        addclass: "dark",
        styling: "bootstrap3",
        hide: !1,
        before_close: function(a) {
            return a.update({
                title: a.options.title + " - Enjoy your Stay",
                before_close: null
            }),
            a.queueRemove(),
            !1
        }
    }))
}