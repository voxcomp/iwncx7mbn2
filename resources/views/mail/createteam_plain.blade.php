@extends('layouts.textmail')

@section('content')
	Thank you for registering {{$team->name}} to walk at the {{$team->event->title}}!
	
	We're thrilled to have your team join our effort to provide compassionate support, education, and funding for canine and pediatric cancer research and companion animal cancer treatment within Madison, WI and the surrounding areas.
	
	Thank you for your support, and we can’t wait to see you on {{date('l, F d, Y',$team->event->event_date)}}!
@stop