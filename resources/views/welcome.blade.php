@extends('layouts.app',['classes'=>'front'])

@section('content')
	<div class="row" style="position:relative;">
		<img src="/images/welcome.jpg" class="img-responsive img-full" alt="" style="position: relative;z-index: 1;">
		<div class="overlay bottom rtecenter">
			<h1>Our Events</h1>
			<p><a href="#eventlist" class="btn btn-primary">SEE LIST BELOW</a></p>
		</div>
	</div>
	<p>&nbsp;</p>
	<div class="container" id="eventlist">
		<div class="row">
		@foreach($events as $key=>$event)
			<div class="col col-sm-4 col-spacing"><a href="/event/{{$event->slug}}" class="btn btn-event btn-block">{{$event->short}}</a></div>
			@if($key%3==0 && $key>0)
				<div class="clearfix"></div>
			@endif
		@endforeach
		</div>
	</div>
@endsection