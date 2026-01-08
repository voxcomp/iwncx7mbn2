@extends('layouts.textmail')

@section('content')
	{{$registrant->fname}} {{$registrant->lname}} has joined {{$team->name}} for the Czar's Promise {{$team->event->name}} event! You can contact this new member by e-mail at {{$registrant->email}} or by phone at {{$registrant->phone}}.
@stop