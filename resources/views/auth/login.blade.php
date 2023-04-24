@extends('auth.layout.auth-layout') 

@section('page-title', 'Complaint Handling System | SignIn') 

@section('meta-description') 
     Complaint Handling System | SignIn 
@endsection 

@section('canonical-url')
<link rel="canonical" href="{{ URL::to('/login') }}">
@endsection 

@section('page-css')
<link href="{{ asset('dashboard/css/login/fontawesome/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ mix('dashboard/compiled/css/login/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('dashboard/css/login/fonts-login/fonts.css') }}" rel="stylesheet" type="text/css">
<link href="{{ mix('dashboard/compiled/css/login/style.min.css') }}" rel="stylesheet" type="text/css">
@endsection 

@section('page-header-js') 
@endsection

@section('page-header')
<script type="text/javascript" src="{{ asset('dashboard/js/login/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/login/imagesloaded.pkgd.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/login/jquery-3.5.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/login/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('dashboard/js/login/validator.min.js') }}"></script>
@endsection 

@section('content')
<!-- main content -->
<a href="#" class="fxt-logo" style="position: absolute; z-index: 999; width: 200px; top: 35px; left: 50px;">
	<img src="{{asset('storage/images/common/auth/logo.png')}}" alt="Logo" style="width: 200px;">
</a>
<!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
<section class="cuz-tmt loaded" data-bg-image="{{asset('storage/images/common/auth/01.jpg')}}" style="background-image: url({{env("FILE_URL")}}/storage/images/common/auth/01.jpg)">

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
					<h2>Login into your account</h2>
					<div class="fxt-form">
					
                      <!-- Form Start -->
						<form method="POST" action="{{ route('login.post') }}">
						
						<!-- Email Start -->
						@csrf
					@if(config('app.authentication') == 'LDAP')
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-1 @error('email') is-invalid @enderror">
									<input id="email" type="text" class="form-control" 
									name="username" placeholder="{{ __('Username') }}" value="{{ old('username') }}" required autocomplete="email" autofocus>
								</div>
							</div>
							@error('username')
	                        <span class="invalid-feedback" role="alert">
	                           <strong>{{ $message }}</strong>
	                         </span>
	                    	@enderror
					@elseif(config('app.authentication') == 'UPM')
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-1 @error('email') is-invalid @enderror">
									<input id="email" type="text" class="form-control" 
									name="empId" placeholder="{{ __('Employee Id') }}" value="{{ old('empId') }}" required autofocus>
								</div>
							</div>
							@error('empId')
	                        <span class="invalid-feedback" role="alert">
	                           <strong>{{ $message }}</strong>
	                         </span>
	                    	@enderror
					@else
					
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-1 @error('userNameOrEmail') is-invalid @enderror">
									<input id="email" type="text" class="form-control" 
									name="userNameOrEmail" placeholder="{{ __('E-Mail Or Username') }}" value="{{ old('userNameOrEmail') }}" 
									required autocomplete="email" autofocus>
								</div>
    							@error('userNameOrEmail')
    	                        <span class="error-msg" role="alert">
    	                           <strong>{{ $message }}</strong>
    	                         </span>
    	                    	@enderror
							</div>
					@endif
						<!--  Email End -->
						
					    <!--  Password Start -->		
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-1 @error('password') is-invalid @enderror">
									<input id="password" type="password" class="form-control" 
									name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
								</div>
    							@error('password')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        		@enderror
							</div>
						<!-- Password End -->
							
						<!-- Remember me section Start -->
							<div class="form-group" style="display: none;">
								<div class="fxt-transformY-50 fxt-transition-delay-3">
									<div class="fxt-checkbox-area">
										<div class="checkbox">
											<input id="checkbox1 remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}> 
											<label for="checkbox1" class="checkbox">{{ __('Remember Me') }}</label>
										</div>
									</div>
								</div>
							</div>
						<!-- Remember me section End -->							
							
						<!-- Login Section Start -->							
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-4">
									<button type="submit" class="fxt-btn-fill">{{ __('Login') }}</button>
								</div>
							</div>							
						<!-- Login Section End -->	
						
						</form>
						<!-- 					Form End -->
					</div>
					@include('auth.partials.footer')
				</div>
			</div>
		</div>
	</div>
</section>
<!-- //main content -->
@endsection

<!-- Original HTML CODE -->

<!--<section class="cuz-tmt loaded" data-bg-image="{{asset('storage/images/common/auth/01.jpg')}}" style="background-image: url(storage/images/common/auth/01.jpg)">

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
					<h2>Login into your account</h2>
					<div class="fxt-form">
						<form method="POST" action="{{ route('login.post') }}">
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-1">
									<input type="email" id="email" class="form-control"
										name="email" placeholder="Email" required="required">
								</div>
							</div>
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-2">
									<input id="password" type="password" class="form-control"
										name="password" placeholder="********" required="required"> <i
										toggle="#password"
										class="fa fa-fw toggle-password field-icon fa-eye"></i>
								</div>
							</div>
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-3">
									<div class="fxt-checkbox-area">
										<div class="checkbox">
											<input id="checkbox1" type="checkbox"> 
											<label for="checkbox1">Keep me logged in</label>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="fxt-transformY-50 fxt-transition-delay-4">
									<button type="submit" class="fxt-btn-fill">Log in</button>
								</div>
							</div>
						</form>
					</div>
					<div class="fxt-footer">
						<div class="fxt-transformY-50 fxt-transition-delay-9"
							style="font-size: smaller;">
							<p>&copy; 2019 - 2020 Sampath IT Solutions. All Rights Reserved.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>-->
