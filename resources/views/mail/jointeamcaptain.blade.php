@extends('layouts.mail')

@section('content')
	<p>{{$registrant->fname}} {{$registrant->lname}} has joined {{$team->name}} for the Czar's Promise {{$team->event->title}} event! You can contact this new member by e-mail at <a href="mailto::{{$registrant->email}}?subject={{$team->name}}">{{$registrant->email}}</a> or by phone at {{$registrant->phone}}.</p>
@stop