@extends('layouts.mail')

@section('content')
	<p>Unfortunately we could not approve your team page for the {{$team->event->title}} event.</p>
	<p>&nbsp;</p>
	<p>In order for the page to be approved, you must log in to your account to make the necessary modifications to the page content.</p>
	@if(!is_null($team->adminnotes) && !empty($team->adminnotes))
		<p>To help you determine the problems, we have made some notes:</p>
		<p>{{$team->adminnotes}}</p>
	@endif
@stop

@section('buttontext')
	Click here to log in
@stop