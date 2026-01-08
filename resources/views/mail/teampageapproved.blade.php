@extends('layouts.mail')

@section('content')
	<p>Congratulations, your team fundraising page for the {{$team->event->title}} event is live!</p>
	<p>&nbsp;</p>
	<p>Share the link with your friends and family. To help with that, we've created a short link that is easier to share:</p>
	<p><strong>{{$shortlink}}</strong></p>
@stop

@section('buttontext')
	Click here to view your page
@stop