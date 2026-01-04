
@extends('default')
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/css/pages/login/classic/login-1.css')}}">
	<style>
		.login-aside {
			/* style="background-image: url(assets/media/bg/bg-4.jpg);" */
			/* background-image:  url('assets/media/bg/bg-4.jpg'); */
			background: url(assets/media/bg/bg-4.jpg) no-repeat center center fixed; 
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
			}
		}
	</style>
@endsection

@section('content')
<body id="kt_body" class="quick-panel-right demo-panel-right offcanvas-right header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-static page-loading">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Login-->
		<div class="login login-4 login-signin-on d-flex flex-row-fluid" id="kt_login">
			<div class="d-flex flex-center flex-row-fluid bgi-size-cover bgi-position-top bgi-no-repeat" style="background-image: url('assets/media/bg/bg-3.jpg');">
				<div class="login-form text-center p-7 position-relative overflow-hidden">
					<!--begin::Login Header-->
					<div class="d-flex flex-center mb-15">
						<a href="#">
							<img src="{{asset('/assets/images/logo/uniq_logo.png')}}" class="max-h-75px max-w-105px" alt="" />
						</a>
					</div>
					<!--end::Login Header-->
					<!--begin::Login Sign in form-->
					<div class="login-signin">
						<div class="mb-20">
							<h3>Нэвтрэх</h3>
							{{-- <div class="text-muted font-weight-bold">Enter your details to login to your account:</div> --}}
						</div>
						@if ($message = Session::get('message'))
						<div class="alert alert-danger alert-block">
							<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>{{ $message }}</strong>
						</div>
						@endif

						<form class="form" id="kt_login_signin_form" method="POST" action="{{route('do.login')}}">
							@csrf
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="text"placeholder="{{trans('display.username')}}" name="username" value="{{ old('username') }}" autocomplete="off" />
							</div>
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="{{trans('display.login_password')}}" name="password" autocomplete="off"/>
							</div>
							<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
								<div class="checkbox-inline mr-3">
									<label class="checkbox m-0 text-muted">
									<input type="checkbox" name="remember" />
									<span></span>{{trans('display.login_remember_me')}}</label>
								</div>
								<a href="javascript:;" id="kt_login_forgot" class="text-muted text-hover-primary">{{trans('display.login_forgot_password')}}</a>
							</div>
							<button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4">{{trans('display.login_do_login')}}</button>
						</form>
						{{-- <div class="mt-10">
							<span class="opacity-70 mr-4">Don't have an account yet?</span>
							<a href="javascript:;" id="kt_login_signup" class="text-muted text-hover-primary font-weight-bold">Sign Up!</a>
						</div> --}}
					</div>

					<!--end::Login Sign in form-->
					<!--begin::Login Sign up form-->
					{{-- <div class="login-signup">
						<div class="mb-20">
							<h3>Sign Up</h3>
							<div class="text-muted font-weight-bold">Enter your details to create your account</div>
						</div>
						<form class="form" id="kt_login_signup_form">
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Fullname" name="fullname" />
							</div>
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
							</div>
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Password" name="password" />
							</div>
							<div class="form-group mb-5">
								<input class="form-control h-auto form-control-solid py-4 px-8" type="password" placeholder="Confirm Password" name="cpassword" />
							</div>
							<div class="form-group mb-5 text-left">
								<div class="checkbox-inline">
									<label class="checkbox m-0">
									<input type="checkbox" name="agree" />
									<span></span>I Agree the
									<a href="#" class="font-weight-bold ml-1">terms and conditions</a>.</label>
								</div>
								<div class="form-text text-muted text-center"></div>
							</div>
							<div class="form-group d-flex flex-wrap flex-center mt-10">
								<button id="kt_login_signup_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Sign Up</button>
								<button id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Cancel</button>
							</div>
						</form>
					</div> --}}
					<!--end::Login Sign up form-->
					<!--begin::Login forgot password form-->
					{{-- <div class="login-forgot">
						<div class="mb-20">
							<h3>Forgotten Password ?</h3>
							<div class="text-muted font-weight-bold">Enter your email to reset your password</div>
						</div>
						<form class="form" id="kt_login_forgot_form">
							<div class="form-group mb-10">
								<input class="form-control form-control-solid h-auto py-4 px-8" type="text" placeholder="Email" name="email" autocomplete="off" />
							</div>
							<div class="form-group d-flex flex-wrap flex-center mt-10">
								<button id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-2">Request</button>
								<button id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-2">Cancel</button>
							</div>
						</form>
					</div> --}}
					<!--end::Login forgot password form-->
				</div>
			</div>
		</div>
		<!--end::Login-->
	</div>
</body>
@section('javascript')
@endsection
