
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<meta name="description" content="Competition, Тэмцээн" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="author" content="Smart Data LLC">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Theme Styles(used by all pages)-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
		{!! HTML::style('/assets/css/bracket.css', array('media'=>'screen')) !!}
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		@yield('css')
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="{{asset('assets/images/logo/uniq_logo.ico')}}" />
		<title>Тэмцээний Удирдлагын Систем</title>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body>
		@yield('content')
		<!--begin::Global Config(global config for global JS scripts)-->

		<!--end::Global Theme Bundle-->
		@yield('javascript')
	</body>
	<!--end::Body-->
	
</html>