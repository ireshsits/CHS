<!DOCTYPE html>
<html lang="en">
<head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{asset('storage/images/common/favicon.ico')}}" type="image/ico" />
	<meta name="description"content="@yield('meta-description')">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    
    @yield('other-meta-tags')
	<title>@yield('page-title')</title>

	<!-- Global stylesheets -->

	<!-- Bootstrap -->
    <link href="{{ asset('dashboard/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>   
    <link href="{{ asset('dashboard/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">    
    <link href="{{ asset('dashboard/vendors/build/css/custom.min.css') }}" rel="stylesheet">
    <link href="{{ mix('dashboard/compiled/css/my-custom.min.css') }}" rel="stylesheet">
	<link href="{{ mix('dashboard/compiled/css/plugins/sweetalert2.min.css') }}">
	<link href="{{ mix('dashboard/compiled/css/preloader.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ mix('dashboard/compiled/css/fonts.min.css') }}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

    <!-- page css -->
    @yield('page-css')
    @include('dashboard.partials.page-global-css')
    <!-- end page css -->
    
    <!-- Core JS files -->
<!--     <script src="{{ asset('dashboard/vendors/jquery/dist/jquery.min.js')}}"></script> -->
	<script src="{{ asset('dashboard/compiled/js/plugins/jquery.min.js')}}"></script>
    <script src="{{ asset('dashboard/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
	<script src="{{ mix('dashboard/compiled/js/plugins/sweetalert2.all.min.js') }}"></script>
	<script src="{{ mix('dashboard/compiled/js/alerts/sweetalert2.min.js') }}"></script>
	<script src="{{ mix('dashboard/compiled/js/navigation/navigation.min.js')}}"></script>
	
	<!-- page header js -->
	@yield('page-header-js')
	<!-- end page header -->
	<script type="text/javascript">
		var admin_roles = "{{RoleHelper::getAdminRoles('RAW_NAMES')}}".split("|");
		var admin_view_roles = "{{RoleHelper::getAdminViewRoles('RAW_NAMES')}}".split("|");
		var zonal_admin_roles = "{{RoleHelper::getZonalAdminRoles('RAW_NAMES')}}".split("|");
		var regional_admin_roles = "{{RoleHelper::getRegionalAdminRoles('RAW_NAMES')}}".split("|");
		var branch_admin_roles = "{{RoleHelper::getBranchAdminRoles('RAW_NAMES')}}".split("|");
		var complaint_raise_roles = "{{RoleHelper::getComplaintRaiseRoles('RAW_NAMES')}}".split("|");
		var complaint_view_roles = "{{RoleHelper::getComplaintViewRoles('RAW_NAMES')}}".split("|");
		var solution_view_roles = "{{RoleHelper::getSolutionViewRoles('RAW_NAMES')}}".split("|");
		var ccc_user_roles = "{{RoleHelper::getCCCRoles('RAW_NAMES')}}".split("|");
		var auth_user_role = "{{Auth::user()->getRoleNames()}}".replace(/quot/g,'').replace(/[^_a-zA-Z]/gi,'');
		var AuthUser = {!! (Auth::user()) ? json_encode(Auth::user()) : null !!};
		//common data
		var notification_logo = "{{$notification_logo}}";
		//common data
	    window.Laravel = {!! json_encode([
//         	'user' => Auth::user(),
        	'csrfToken' => csrf_token(),
            'vapidPublicKey' => config('webpush.vapid.public_key'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
        ]) !!};
    var custom_url = "{{env("CUSTOM_URL")}}";
    var file_url = "{{env("FILE_URL")}}";

	</script>
	<!--Preloader -->
	<script>
	$(window).on('load', function(){
		setTimeout(function() {
		$(".loader , .loader-wrapper").fadeOut("slow");
		},100);
	});
// 	window.addEventListener("beforeunload", function (e) {
// 		  var confirmationMessage = "\o/";
// 		  document.getElementById('logout-form').submit();
// 		  (e || window.event).returnValue = confirmationMessage; //Gecko + IE
// 		  return confirmationMessage;                            //Webkit, Safari, Chrome
// 		});
	</script>
</head>


  <body class="nav-sm">
	<div class="loader-wrapper">
	  <div class="loader"></div>
	</div>
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="#" class="site_title">
<!--               <i class="fa fa-comments"></i>  -->
					<img src="{{ asset('storage/images/common/logo-icon.png') }}" alt="" class="img-circle profile_img profile_img-main-cuz">
              <span style="font-family: 'Alata', sans-serif; color: darkslategray;">INFINITY CHS</span>
              
              </a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{ asset('storage/images/users/man.png') }}" alt="" class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ Auth::user()->first_name .' '.Auth::user()->last_name }}</h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            	@include('dashboard.partials.side-menu')
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
<!--               <a data-toggle="tooltip" data-placement="top" title="Settings"> -->
<!--                 <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> -->
<!--               </a> -->
<!--               <a data-toggle="tooltip" data-placement="top" title="FullScreen"> -->
<!--                 <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span> -->
<!--               </a> -->
<!--               <a data-toggle="tooltip" data-placement="top" title="Lock"> -->
<!--                 <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> -->
<!--               </a> -->
              <a data-toggle="tooltip" data-placement="top" title="Logout" 
              			href="{{ route('logout') }}" 
              			onclick="event.preventDefault();
                  			document.getElementById('logout-form').submit();">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        @include('dashboard.partials.header')
        <!-- /top navigation -->



        <!-- page content -->
        @yield('content')
        <!-- /page content -->
        
        <!-- Modal blade-->
			@include('dashboard.partials.modals')
		<!-- Modal blade-->

        <!-- footer content -->
        	@include('dashboard.partials.footer')
        <!-- /footer content -->
      </div>
    </div>
    
    <!-- DateJS -->
    <script src="{{ asset('dashboard/vendors/DateJS/build/date.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('dashboard/vendors/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('dashboard/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('dashboard/vendors/build/js/custom.min.js')}}"></script>
		
	<!-- Pusher Js -->
<!-- 	<script src="{{ asset('js/app.js')}}"></script> -->
<!-- 	<script src="{{ asset('js/enable-push.js')}}"></script> -->
<!-- 	<script src="/js/app.js"></script> -->
<!--     <script src="/js/enable-push.js"></script> -->
	<!-- page js -->
	<script type="text/javascript">
		$('#menu_toggle').on('click', function(){
			if ($('body').hasClass('nav-md')){
				$('.site_title > .profile_img').removeClass('profile_img-cuz');
			}else{
				$('.site_title > .profile_img').addClass('profile_img-cuz');
			}
		});
	</script>
	@yield('page-js')
    @yield('modal-js')
	<!-- page js -->
  </body>


</html>
