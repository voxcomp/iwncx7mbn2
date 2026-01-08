@extends('layouts.app',['hideHeader'=>true])

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">{{$team->name}}</span>
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
	<p><strong>This team has not yet set up their fundraising page.</strong></p>
	<p>&nbsp;</p>
	<p><a href="{{route('event.view',[$event->slug])}}">Learn more about the {{$event->title}} event.</a></p>
@stop