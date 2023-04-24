/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
window.Pusher = require('pusher-js');
//var echo = require('laravel-echo');
import Echo from "laravel-echo";
import _ from 'lodash';
//import axios from "axios";
import VueMoment from 'vue-moment'

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Pusher.logToConsole = false;
//window.Echo = new echo.default({
//    broadcaster: 'pusher',
//    key: Laravel.pusher.key,
//    cluster: Laravel.pusher.cluster,
//    encrypted: true,
//    logToConsole: true,
//    authEndpoint: '/broadcasting/auth'
//});
window.Echo = new Echo({
	broadcaster: 'pusher',
	key: Laravel.pusher.key,
	cluster: Laravel.pusher.cluster,
	encrypted: true,
	logToConsole: false,
	forceTLS: true
});

var notifications = [];

//const NOTIFICATION_TYPES = {
//    follow: 'App\\Notifications\\NotifyUser'
//};

$(document).ready(function() {
    // check if there's a logged in user
	
    if(AuthUser.user_id_pk) {
    	// working code
/*        $.get('/dashboard/notifications/all', function (data) {
            addNotifications(data, ".msg_list");
        });
        
        window.Echo.private(`App.User.${AuthUser.user_id_pk}`)
        .notification((notification) => {
        	console.log(notification);
            addNotifications([notification], ".msg_list");
        }); */       

    	// working code end
        
//        window.Echo.private(`App.User.${AuthUser.user_id_pk}`)
////    	window.Echo.private(`User.${AuthUser.user_id_pk}`)
//        .listen((notification) => {
//        	console.log(notification);
//            addNotifications([notification], ".msg_list");
//        });
    }
});

function addNotifications(newNotifications, target) {
    notifications = _.concat(notifications, newNotifications);
    // show only last 5 notifications
    notifications.slice(0, 5);
    showNotifications(notifications, target);
}

function showNotifications(notifications, target) {
    if(notifications.length) {
        var htmlElements = notifications.map(function (notification) {
           return makeNotification(notification);
        });
        $(target).html(htmlElements.join(''));
        $('.notification-count').html($(target + ' li').length).show();
    } else {
        $(target).html('<li class="dropdown-header">No notifications</li>');
    }
}

// Make a single notification string
function makeNotification(notification) {
    var to = routeNotification(notification);
    return '<li>'+
	    		'<a href="' + to + '">'+
	    		 '<span class="image"><img src="'+notification_logo+'" alt="Profile Image" /></span>'+
	    		 '<span>'+
	    		 	'<span>John Smith</span><span class="time">3 mins ago</span>'+
	    		 '</span>'+
	    		 '<span class="message">'+notification.data.subject +' - '+notification.data.data.reference_number
	    		 '</span>'+
	    		'</a>'+
    	  '</li>';
}

// get the notification route based on it's type
function routeNotification(notification) {
    return '/dashboard/notifications/read/'+ notification.id;
}

// get the notification text based on it's type
function makeNotificationText(notification) {
    var text = '';
        const name = notification.data.follower_name;
        text += '<strong>' + name + '</strong> followed you';
    return text;
}

