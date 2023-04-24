@extends('auth.layout.auth-layout') 

@section('page-title', 'Complaint Handling System | 500') 

@section('meta-description') 
     Complaint Handling System | 500
@endsection 

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/login') }}">
@endsection 

@section('page-css')
<link href="{{ asset('dashboard/css/login/fontawesome/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ mix('dashboard/compiled/css/login/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ mix('dashboard/compiled/css/login/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection 

@section('page-header-js') 
@endsection

@section('page-header')
@endsection 

@section('content')
<!-- main content -->
<a href="#" class="fxt-logo" style="position: absolute; z-index: 999; width: 200px; top: 35px; left: 50px;">
	<img src="{{asset('storage/images/common/auth/logo.png')}}" alt="Logo" style="width: 200px;">
</a>
<!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
<section class="cuz-tmt loaded" data-bg-image="{{asset('storage/images/common/auth/01.jpg')}}" style="background-image: url(storage/images/common/auth/01.jpg)">

	<div class="container">
		<div class="row align-items-center justify-content-center">
			<div class="col-lg-3">
				<div class="fxt-header">
					<h1 style="font-family: 'Roboto', sans-serif; font-weight: 700; letter-spacing: 2px; color: #f1f8ff; position: relative; text-transform: uppercase; font-size: 34px;">
						Infinity <span style="font-size: 40px;">CHS</span>
					</h1>
					<hr style="background-color: honeydew;">
				</div>
			</div>

			<div class="col-lg-6">
				<div class="fxt-content">
					<h1 class="error-page-title">500</h1>
					<h5 class="error-page-msg">Oops, an error has occurred. Internal server error!</h5>
					<a class="btn btn-primary btn-block content-group" href="{{ route('dashboard.home') }}"><i class="fa fa-home"></i> Go Back to Dashboard Home </a>
					@include('auth.partials.footer')
				</div>
			</div>
		</div>
	</div>
</section>
<!-- //main content -->
@endsection