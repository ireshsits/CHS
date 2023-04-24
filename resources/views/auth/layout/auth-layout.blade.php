<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('page-title')</title>
 <!-- Meta-Tags -->   

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">    
    <meta http-equiv="x-ua-compatible" content="ie=edge">    
    <meta name="description" content="INFINITY CHS">    
    <link rel="shortcut icon" href="{{asset('storage/images/common/favicon.ico')}}" type="image/ico" />
    
    @yield('other-meta-tags')
    
	<!-- page css -->
    @yield('page-css')
    <!-- end page css -->
	
</head>
<body>
	<div class="signupform">
		@yield('content')		
	</div>
</body>

</html>