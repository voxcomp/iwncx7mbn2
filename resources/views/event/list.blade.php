@extends('layouts.admin')

@section('title')
	Event Management
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	
	@foreach($events as $strip=>$event)
		<div class="row listing{{($strip%2==0)?' even':' odd'}}">
			<div class="col col-xs-3 col-sm-1">
				<a class="btn btn-danger col-spacing" onclick="AjaxConfirmDialog('Are you sure you want to permanently delete {{$event->title}}?<br><br>This will delete all donation, sponsor and participant history.', 'Delete Event', '{{ route('event.delete',[$event->slug]) }}', '{{ route('home') }}', '')"><i class="fa fa-trash"></i></a> 
				<a href="{{route('event.edit',[$event->slug])}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
			</div>
			<div class="col col-sm-2 col-xs-9">
				<img src="/storage/public/{{$event->image}}" class="img-responsive">
			</div>
			<div class="clearfix visible-xs"></div>
			<div class="col col-sm-9">
				<h3 style="margin-top:0;"><a href="/event/{{$event->slug}}">{{$event->title}}</a></h3>
				<div class="row">
					<div class="col col-sm-6">
						<p><strong>Event Date:</strong> {{date("m/d/Y",$event->event_date)}}<br>
							<strong>Participants:</strong> {{$event->participants->count()}}<br>
							<strong>Goal:</strong> {{number_format($event->goal,2)}}<br>
							<strong>Total Raised:</strong> {{number_format($event->raised(),2)}}</p>
					</div>
					<div class="col col-sm-6">
						<p><strong>Volunteers:</strong> {{$event->volunteerSubmissions->count()}}<br>
							<strong>Sponsors:</strong> {{$event->sponsorSubmissions->count()}}<br>
							<strong>Total Sponsored:</strong> {{number_format($event->sponsorSubmissions->sum('paid')+$event->sponsorSubmissions->sum('inkind_value'),2)}}
</p>
					</div>
				</div>
			</div>
		</div>
		<hr>
	@endforeach
@stop
