$(function() {
	var url = window.location;
	var linkRef = url.pathname.substring(1, url.pathname.length).split('/');
	var className = linkRef[0];
	var liName = linkRef[1];
	if(linkRef[2])
		var subliName = linkRef[2];
	$('ul').find('.'+className).addClass('active');
	$('ul').find('.'+className).find('ul').css('display','block');
	if ($.inArray(className, ['sections','authors','publishers','languages','holidays']) !== -1) {
			liName = className;
	}
	$('ul').find('.'+className).find('ul').find('.'+liName).addClass('menu-active-clr');
	setInterval(function(){
		if (navigator.onLine) {
			$('.online-status').find('span').removeClass('bg-success bg-grey');
			$('.online-status').find('span').addClass('bg-success');
			$('.online-status').find('span').html('ONLINE');
		}else{
			$('.online-status').find('span').removeClass('bg-success bg-grey');
			$('.online-status').find('span').addClass('bg-grey');
			$('.online-status').find('span').html('OFFLINE');
		}
	},2000);
});