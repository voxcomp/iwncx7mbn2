@extends('layouts.textmail')

@section('content')
Unfortunately we could not approve your team page for the {{$team->event->title}} event.

In order for the page to be approved, you must log in to your account to make the necessary modifications to the page content.

@if(!is_null($team->adminnotes) && !empty($team->adminnotes))
	To help you determine the problems, we have made some notes:
	{{$team->adminnotes}}
@endif
@stop

@section('buttontext')
	Click here to log in
@stop