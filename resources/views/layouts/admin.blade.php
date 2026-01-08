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
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" type="text/css">

	@yield('head')
</head>

<body class="admin">
    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
        {{ csrf_field() }}
    </form>
    
    @if(View::hasSection('title'))
		<div class="page-title-container" style="background-image:url(/images/secondary-page-image.jpg);">
			<div class="page-title-content">
				<h1 class="page-header">@yield('title')</h1>
			</div>
		</div>
	@endif

	<div class="main-container">
	  <div class="row-same-height row-full-height">
	      <aside class="col-sm-2 col-sm-height col-full-height col-top admin-menu-container" role="complementary">
	        @include('parts.adminMenu')
	      </aside>
	
        @if(View::hasSection('rightsidebar'))
            <section class="col-sm-7 content-column col-sm-height col-full-height col-top">
        @else
         	<section class="col-sm-10 content-column col-sm-height col-full-height col-top">
        @endif
	      <a id="main-content"></a>
	      <div class="page-content">
	       	  @yield('content')
	      </div>
	    </section>
	
	    @if(View::hasSection('rightsidebar')):
	      <aside class="col-sm-3 col-sm-height col-full-height col-top" role="complementary">
	        @yield('rightsidebar')
	      </aside>
	    @endif
	  </div>
	</div>
    
	@include('parts.footer')

	<div id="dialog-confirm"></div>

    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/theme.js') }}" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	@yield('footer')
</body>
</html>
