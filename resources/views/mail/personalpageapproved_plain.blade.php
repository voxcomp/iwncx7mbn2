@extends('layouts.textmail')

@section('content')
Congratulations, your personal fundraising page for the {{$registrant->event->title}} event is live!

Share the link with your friends and family. To help with that, we've created a short link that is easier to share:
{{$shortlink}}
@stop

@section('buttontext')
	Click here to view your page
@stop