@extends('layouts.mail')

@section('content')
	<p>Registration for {{$event->title}} @if($registrant->paid>0) <br>Paid: ${{$registrant->paid}}@endif</p>
	<p>{{$registrant->fname}} {{$registrant->lname}}<br>{{$registrant->address}}<br>{{$registrant->city}} {{$registrant->state}} {{$registrant->zip}}<br><br>{{$registrant->phone}}<br>{{$registrant->email}}</p>
	<p>Shirt size selected: {{strtoupper($registrant->shirt)}}</p>
	@if($registrant->shipshirt)
	<p>Ship shirt and swag to:</p>
	<p>{{$registrant->fname}} {{$registrant->lname}}<br>{{$registrant->shipaddress}}<br>{{$registrant->shipcity}} {{$registrant->shipstate}} {{$registrant->shipzip}}</p>
	@endif
	
	@if(empty($registrant->pagetitle) && !is_null($user))
		<p>No personal page has been created.</p>
	@else(!empty($registrant->pagetitle))
		<p>A personal page is available for approval.</p>
	@endif
	
	@if(!is_null($team))
		<p>Member of team {{$team->name}}. Team captain's name is {{$team->captain->fname}} {{$team->captain->lname}} &lt;{{$team->captain->email}}&gt;</p>
	@endif
	
	@if(!is_null($donation))
		<p>Donation was made for ${{$donation->amount}}</p>
	@endif
	
	@if(!is_null($user))
		<p>A user account has been created.</p>
	@endif
@stop