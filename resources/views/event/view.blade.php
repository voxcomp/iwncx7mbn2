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
			<div class="table-content-all script">
				<a href="{{route('event.register',[$event->slug])}}"><img src="/images/icon-register.jpg" class="img-responsive"><br>
				Sign Up Now</a>
			</div>
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
	{!!htmlspecialchars_decode($event->description)!!}
	<p>&nbsp;</p>
	<div class="row"><div class="row-same-height row-full-height">
		<div class="col col-sm-6 col-spacing col-sm-height col-full-height col-bottom">
			<h4>Registration Costs</h4>
			<?php $last = 0; ?>
			@foreach($event->costs->sortBy('ends') as $key=>$cost)
				<div>${{$cost->cost}} {{($last==0)?'before '.date('F j, Y',$cost->ends+86400):'from '.date('F j',($last+86400)).' - '.date('F j, Y',$cost->ends)}}</div>
				<?php $last = $cost->ends; ?>
			@endforeach
			<div>12 and under - Free</div>
		</div>
		<div class="col col-sm-6 col-sm-height col-full-height col-bottom">
			@if((\Auth::check() && ($event->participants->where('email',\Auth::user()->email)->count()==0)) || !\Auth::check())
				<p><a href="{{route('event.register',[$event->slug])}}" class="btn btn-primary">Sign Up Now</a>
			@endif
		</div>
	</div></div>
	<p>&nbsp;</p>
	<p>ALL registered participants receive a {{$event->title}} t-shirt and a community partners gift pack! 
	<p>&nbsp;</p>
	<h4>Czar's Promise Contact Information</h4>
	<div class="row">
		<div class="col col-sm-6">
			<p>Czar's Promise<br>PO Box 5061<br>Madison, WI 53705</p>
		</div>
		<div class="col col-sm-6">
			<p>General Information - <a href="mailto:info@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}">info@czarspromise.com</a><br>
			Sponsorship - <a href="mailto:beth.viney@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}">beth.viney@czarspromise.com</a><br>
			Volunteer - <a href="mailto:cpvolunteers@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}">cpvolunteers@czarspromise.com</a></p>
		</div>
	</div>
	<p>Czar's Promise is a 501 (c)(3) organization, #47-2163857</p>
@stop

@section('rightsidebar')
	<div class="block">
		<h3>Legacy Sponsor</h3>
		<div class="content">
			<a href="https://edingersurgicaloptions.com/" target="_blank"><img src="/storage/public/edinger-surgical-options-1648563719.jpeg" class="img-responsive"></a>
		</div>
	</div>
	@if($event->sponsors->where('presenting',1)->count()>0)
	<div class="block">
		<h3>Guardian Sponsor</h3>
		<div class="content">
			@foreach($event->sponsors->where('presenting',1) as $sponsor)
				@if(!empty($sponsor->url))
					<a href="{{$sponsor->url}}" target="_blank">
				@endif
				<img src="/storage/public/{{$sponsor->filename}}" class="img-responsive">
				@if(!empty($sponsor->url))
					</a>
				@endif
			@endforeach
		</div>
	</div>
	@endif
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
	<div class="block">
		<div class="content rtecenter">
			<h4><a href="{{route('event.page.list',$event->slug)}}" class="btn btn-primary wrap">DONATE TO A SPECIFIC PARTICIPANT OR TEAM</a></h4>
		</div>
	</div>
	@if(isset($teams))
	<div class="block">
		<h3>Top Teams</h3>
		<div class="content">
			<ol>
				@foreach($teams as $team)
					<li>{!!(!empty($team->pageurl))?'<a href="'.$team->pageurl.'">':''!!}{{$team->name}}{!!(!empty($team->pageurl))?'</a>':''!!} (${{$team->eventDonations($event)}})</li>
				@endforeach
			</ol>
		</div>
	</div>
	@endif
	@if(isset($participants))
	<div class="block">
		<h3>Top Individuals</h3>
		<div class="content">
			<ol>
				@foreach($participants as $individual)
					<li>{!!(!empty($individual->pagetitle))?'<a href="'.$individual->pageurl.'">':''!!}{{$individual->fname}} {{$individual->lname}}{!!(!empty($individual->pagetitle))?'</a>':''!!} (${{$individual->eventDonations($event)}})</li>
				@endforeach
			</ol>
		</div>
	</div>
	@endif
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
