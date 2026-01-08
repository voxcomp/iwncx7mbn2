<div class="row">
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{route('event.list')}}">List Events</a>
	</div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{route('event.create')}}">Create Event</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{route('user.search.page')}}">User Search</a>
	</div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{ route('user.create') }}">Create User</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{ route('registrant.search') }}">Search Registrations</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{route('admin.personalpage.review')}}">Moderate Pages</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{ route('admin.donations') }}">Donations</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{ route('coupons') }}">Discount Codes</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{route('reports')}}">Reports</a>
	</div>
	<div class="clearfix row-spacing"></div>
	<div class="col col-spacing col-sm-12 col-xs-6">
		<a class="btn btn-warning btn-block" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log Out</a></p>
	</div>
</div>