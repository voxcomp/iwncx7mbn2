@extends('layouts.app',['hideHeader'=>true])

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">{{$team->name}}</span>
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
<!-- 	<h2 style="margin-top:0;">{{$team->pagetitle}}</h2> -->
	<div class="row">
		<div class="col col-sm-9">
			{!!htmlspecialchars_decode($team->pagecontent)!!}
		</div>
		<div class="col col-sm-3">
			<h4>Our Members</h4>
			@foreach($team->members as $member)
				@if(!empty($member->registrant->pagetitle))
					<a href="{{$member->registrant->pageshorturl}}">{{$member->registrant->fname}} {{$member->registrant->lname}}</a><br>
				@else
					{{$member->registrant->fname}} {{$member->registrant->lname}}<br>
				@endif
			@endforeach
		</div>
	</div>
	<p>&nbsp;</p>
	<p><a href="{{route('donate.team',[$event->slug,$team->slug])}}" class="btn btn-primary">Sponsor us today!</a>
	<p>&nbsp;</p>
	<p><strong>If you'd like to join this team, <a href="/register/{{$event->slug}}">register for the {{$event->title}} event</a> today!</strong></p>
	<p>&nbsp;</p>
	<p><a href="{{route('event.view',[$event->slug])}}">Learn more about the {{$event->title}} event.</a></p>
@stop

@section('rightsidebar')
	@if(!empty($team->photo))
		<div class="block rtecenter">
			<div class="content">
				<img class="img-responsive" alt="{{$team->name}}" src="/storage/{{$team->photo}}">
			</div>
		</div>
	@endif
	<div class="block">
		<h3>Our Fundraising Goal: ${{$team->goal}}</h3>
		<div class="event-goal">
			<div class="event-goal-mask">
				<img src="/images/goal-mask.png" class="img-full">
			</div>
			<div class="event-goal-raised" style="height:{{$team->eventDonationPercent($event)}}%"></div>
		</div>
		<div class="rtecenter padding-top-15">Raised to date: ${{number_format($team->eventDonations($event),2)}}</div>
	</div>
	<p class="rtecenter"><a href="{{route('donate.team',[$event->slug,$team->slug])}}" class="btn btn-primary">Sponsor us today!</a>
	@if(isset($donors))
	<div class="block">
		<h3>Supporters</h3>
		<div class="content">
			<ol>
				@foreach($donors as $donor)
					@if($donor->anonymous)
						<li>Anonymous (${{$donor->amount}})</li>
					@else
						<li>{{$donor->fname}} {{$donor->lname}} (${{$donor->amount}})</li>
					@endif
				@endforeach
			</ol>
		</div>
	</div>
	@endif
@stop

@section('panel')

@stop
