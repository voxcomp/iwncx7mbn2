@extends('layouts.textmail')

@section('content')
Unfortunately we could not approve your personal page for the {{$registrant->event->title}} event.

In order for your page to be approved, you must log in to your account to make the necessary modifications to your page content.

@if(!is_null($registrant->adminnotes) && !empty($registrant->adminnotes))
	To help you determine the problems, we have made some notes:
	{{$registrant->adminnotes}}
@endif
@stop

@section('buttontext')
	Click here to log in
@stop