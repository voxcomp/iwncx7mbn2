@extends('layouts.mail')

@section('content')
	<p>Thank you for registering {{$team->name}} to walk at the {{$team->event->title}}!</p>
	<p>We're thrilled to have your team join our effort to provide compassionate support, education, and funding for canine and pediatric cancer research and companion animal cancer treatment within Madison, WI and the surrounding areas.</p>

	<p>Thank you for your support, and we can’t wait to see you on {{date('l, F d, Y',$team->event->event_date)}}!</p>
@stop