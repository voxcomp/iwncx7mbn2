<header class="header">
	@if(!isset($hideHeader))
		<div class="donate-bar visible-xs hidden-sm hidden-md hidden-lg"><a href="/donate">DONATE</a></div>
		<div class="top-bar">
			<a href="/donate"><img alt="" src="/images/donate-bone.png" style="margin-top: 5px; width: 94px;" class="img-responsive"></a>
		</div>
	@endif
	@if(isset($promise))
		<div class="donate-bar visible-xs hidden-sm hidden-md hidden-lg"><a href="/promise-wall">PROMISE WALL</a></div>
		<div class="top-bar">
			<a href="/promise-wall" class="btn btn-primary">Promise Wall</a>
		</div>
	@endif
	@if(isset($wall))
		<div class="donate-bar visible-xs hidden-sm hidden-md hidden-lg"><a href="/promise-wall/donate">DONATE</a></div>
		<div class="top-bar">
			<a href="/promise-wall/donate" class="btn btn-primary">Donate</a>
		</div>
	@endif
	
		<div class="row main-navigation"><div class="row-same-height row-full-height">
			<div class="col col-sm-3 col-sm-height col-full-height col-bottom">
				<div class="clearfix">
				      <button type="button" class="pull-right navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
						<a class="logo" href="/" title="Home">
							<img class="img-responsive" src="/images/logo.png" alt="{{ config('app.name', 'Laravel') }}" />
						</a>
				</div>
			</div>
			<div class="col col-sm-9 rteright col-sm-height col-full-height col-bottom header-links">
				@if(!isset($hideHeaderLinks) && !isset($hideHeader))
					@if(!\Auth::check())
						<p><a href="/login">Log In</a></p>
					@else
						<p><a href="/home">Dashboard</a>&nbsp;&nbsp; &nbsp;<a href="/account">My Profile</a>&nbsp;&nbsp; &nbsp;<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</a></p>
					@endif
				@endif
				@if(isset($wall) || isset($promise))
					<p><a href="https://www.czarspromise.com/czars-promise-wall-fundraiser">Back To Website</a></p>
				@endif
			</div>
		</div></div>
</header>