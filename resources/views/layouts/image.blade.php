<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"><!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title><!-- Styles -->
	
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#e44856">
	<meta name="theme-color" content="#E34856">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">

	@yield('head')
</head>

<body class="imagepage">
    @if(View::hasSection('title'))
		<div class="page-title-container" style="background-image:url(/images/secondary-page-image.jpg);">
			<div class="page-title-content">
				<h1 class="page-header">@yield('title')</h1>
			</div>
		</div>
	@endif

	<div class="imagepage-container">
		<div class="row-same-height row-full-height">
			<div class="col col-sm-12 col-md-5 col-lg-3 col-sm-height col-full-height col-{{$contentAlign}}">
				<div class="imagepage-content-container">
					<div class="imagepage-content">
						<p><img class="img-responsive" id="logo" src="/images/logo.png" alt="{{ config('app.name', 'Laravel') }}" /></p>
						<p>&nbsp;</p>
						@yield('content')
					</div>
					@include('parts.footer-small')
				</div>
			</div>
			<div class="col col-md-7 col-lg-9 col-sm-height col-full-height image-panel {{$imageClass}} hidden-sm hidden-xs" style="background-image:url({{$image}});"></div>
		</div>
	</div>

	<div id="dialog-confirm"></div>

    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	@yield('footer')
</body>
</html>
