@extends('layouts.app')

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">{{date("l F d, Y",$event->event_date)}}</span>
@stop

@section('pageimage')
	/storage/public/{{$event->image}}
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="table-container-all">
		<div class="table-content-all script">
			<a href="{{route('donate',[$event->slug])}}"><img src="/images/icon-donate.jpg" class="img-responsive"><br>
			Donate</a>
		</div>
		@if((\Auth::check() && ($event->participants->where('email',\Auth::user()->email)->count()==0)) || !\Auth::check())
			<div class="table-content-all script">
				<a href="{{route('event.register',[$event->slug])}}"><img src="/images/icon-register.jpg" class="img-responsive"><br>
				Sign Up Now</a>
			</div>
		@elseif(\Auth::check() && ($event->participants->where('email',\Auth::user()->email)->count()>0))
			<div class="table-content-all script">
				<img src="/images/icon-register.jpg" class="img-responsive"><br>
				Signed Up!
			</div>
		@endif
		<div class="table-content-all script">
			<a href="{{route('volunteer',[$event->slug])}}"><img src="/images/icon-volunteer.jpg" class="img-responsive"><br>
			Volunteer</a>
		</div>
		<div class="table-content-all script">
			<a href="{{route('sponsor',[$event->slug])}}"><img src="/images/icon-sponsors.jpg" class="img-responsive"><br>
			Sponsor</a>
		</div>
	</div>
	<p>&nbsp;</p>
	<div class="row">
		<div class="col col-sm-6">
			<h4>PERSONAL PAGES</h4>
			<?php $empty=true; ?>
			@foreach($event->participants->sortBy(function($item) { return $item->lname.', '.$item->fname; }) as $registrant)
				@if(!empty($registrant->pagetitle))
					<?php $empty=false; ?>
					<div>{{$registrant->lname}}, {{$registrant->fname}} (<a href="{{$registrant->page_short}}">View Page</a>)</div>
				@endif
			@endforeach
			@if($empty)
				<p>No participants have set up donation pages.</p>
			@endif
		</div>
		<div class="col col-sm-6">
			<h4>TEAM PAGES</h4>
			<?php $empty=true; ?>
			@foreach($event->teams->sortBy('name') as $team)
				@if(!empty($team->pagetitle))
					<?php $empty=false; ?>
					<div>{{$team->name}} (<a href="{{$team->page_short}}">View Page</a>)</div>
				@endif
			@endforeach
			@if($empty)
				<p>No teams have set up donation pages.</p>
			@endif
		</div>
	</div>
	<p>&nbsp;</p>
	<p><a href="{{route('event.view',[$event->slug])}}"><i class="fa fa-chevron-circle-left"></i> Back to the {{$event->title}} Main Page</a></p>
@stop

@section('rightsidebar')
	<div class="block">
		<h3>Presenting Sponsor</h3>
		<div class="content">
			@foreach($event->sponsors->where('presenting',1) as $sponsor)
				<img src="/storage/public/{{$sponsor->filename}}" class="img-responsive">
			@endforeach
		</div>
	</div>
	<div class="block">
		<h3>Fundraising Goal: ${{$event->goal}}</h3>
		<div class="event-goal">
			<div class="event-goal-mask">
				<img src="/images/goal-mask.png" class="img-full">
			</div>
			<div class="event-goal-raised" style="height:{{$event->percent()}}%"></div>
		</div>
		<div class="rtecenter padding-top-15 font-18"><strong>Raised to date:<br>${{number_format($event->raised(),2)}}</strong></div>
	</div>
@stop

@section('panel')
	@if($event->sponsors->where('presenting',0)->where('vendor',0)->count())
		<hr>
		<h2 class="red-text rtecenter">Please Support Our Sponsors</h2>
		<div class="content rtecenter">
			@foreach($event->sponsors->where('presenting',0)->where('vendor',0) as $sponsor)
				@if(!empty($sponsor->url))
					<a href="{{$sponsor->url}}" target="_blank">
				@endif
				<img src="/storage/public/{{$sponsor->filename}}" class="img-responsive" style="width:150px;margin:15px;">
				@if(!empty($sponsor->url))
					</a>
				@endif
			@endforeach
		</div>
	@endif
	@if($event->sponsors->where('presenting',0)->where('vendor',1)->count())
		<hr>
		<h2 class="red-text rtecenter">Please Visit Our Vendors</h2>
		<div class="content rtecenter">
			@foreach($event->sponsors->where('presenting',0)->where('vendor',1) as $sponsor)
				@if(!empty($sponsor->url))
					<a href="{{$sponsor->url}}" target="_blank">
				@endif
				<img src="/storage/public/{{$sponsor->filename}}" class="img-responsive" style="width:150px;margin:15px;">
				@if(!empty($sponsor->url))
					</a>
				@endif
			@endforeach
		</div>
	@endif
@stop
