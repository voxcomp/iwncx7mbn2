@extends('layouts.app',['hideHeader'=>true])

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">{{$registrant->fname}} {{$registrant->lname}}</span>
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
	<h2 style="margin-top:0;">{{$registrant->pagetitle}}</h2>
	{!!htmlspecialchars_decode($registrant->pagecontent)!!}
	<p>&nbsp;</p>
	<p><a href="{{route('donate',[$event->slug,$registrant->slug])}}" class="btn btn-primary">Sponsor me today!</a>
	<p>&nbsp;</p>
	<p><a href="{{route('event.view',[$event->slug])}}">Learn more about the {{$event->title}} event.</a></p>
@stop

@section('rightsidebar')
	@if($registrant->user_id!=0)
		@if($registrant->user->photo!='male.png')
			<div class="block rtecenter">
				<div class="content">
					<img class="img-responsive" alt="{{$registrant->fname}} {{$registrant->lname}}" src="/storage/{{$registrant->user->photo}}">
				</div>
			</div>
		@endif
	@endif
	<div class="block">
		<h3>My Fundraising Goal: ${{$registrant->goal}}</h3>
		<div class="event-goal">
			<div class="event-goal-mask">
				<img src="/images/goal-mask.png" class="img-full">
			</div>
			<div class="event-goal-raised" style="height:{{$registrant->eventDonationPercent($event)}}%"></div>
		</div>
		<div class="rtecenter padding-top-15">Raised to date: ${{number_format($registrant->eventDonations($event),2)}}</div>
	</div>
	<p class="rtecenter"><a href="{{route('donate',[$event->slug,$registrant->slug])}}" class="btn btn-primary">Sponsor me today!</a>
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
