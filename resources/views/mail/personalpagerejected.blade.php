@extends('layouts.mail')

@section('content')
	<p>Unfortunately we could not approve your personal page for the {{$registrant->event->title}} event.</p>
	<p>&nbsp;</p>
	<p>In order for your page to be approved, you must log in to your account to make the necessary modifications to your page content.</p>
	@if(!is_null($registrant->adminnotes) && !empty($registrant->adminnotes))
		<p>To help you determine the problems, we have made some notes:</p>
		<p>{{$registrant->adminnotes}}</p>
	@endif
@stop

@section('buttontext')
	Click here to log in
@stop