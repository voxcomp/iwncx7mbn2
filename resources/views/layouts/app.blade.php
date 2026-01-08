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

<body{{ (isset($classes))?' class='.$classes:'' }}>
    <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
        {{ csrf_field() }}
    </form>
    
    @if(isset($hideHeader))
    	@include('parts.header',['hideHeader'=>$hideHeader])
    @else
		@include('parts.header')
	@endif
	<div class="page-container">
	    @if(View::hasSection('title'))
			<div class="page-title-container" style="background-image:url(/images/secondary-page-image.jpg);">
				<div class="page-title-content">
					<div class="container-fluid">
						<h1 class="page-header">@yield('title')</h1>
					</div>
				</div>
			</div>
		@endif
		
	    @if(View::hasSection('pageimage'))
	    	<div class="page-image">
		    	<img src="@yield('pageimage')" class="img-responsive img-full" alt="">
	    	</div>
		@endif
		
		<div class="main-container container-fluid">
		  <div class="row">
		    @if(View::hasSection('leftsidebar'))
		      <aside class="col-sm-3" role="complementary">
		        @yield('leftsidebar')
		      </aside>
		    @endif
		
	        @if(View::hasSection('leftsidebar') && View::hasSection('rightsidebar'))
	            <section class="col-sm-6 content-column">
	        @elseif(View::hasSection('leftsidebar') || View::hasSection('rightsidebar'))
	            <section class="col-sm-9 content-column">
	        @else
	         	<section class="col-sm-12 content-column">
	        @endif
		      <a id="main-content"></a>
		      @if(isset($fullwidth))
		      	@yield('content')
		      @else
			      <div class="page-content">
			       	  @yield('content')
			      </div>
			  @endif
		    </section>
		
		    @if(View::hasSection('rightsidebar'))
		      <aside class="col-sm-3" id="right-sidebar" role="complementary">
		        @yield('rightsidebar')
		      </aside>
		    @endif
		
		  </div>
		</div>
	
	    @if(View::hasSection('panel'))
			<div class="page-panel">
				@yield('panel')
			</div>
		@endif
	    
		@include('parts.footer')
	</div>

	<div id="dialog-confirm"></div>

    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/theme.js') }}" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	@yield('footer')
</body>
</html>
