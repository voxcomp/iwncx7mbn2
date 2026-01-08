@extends('layouts.textmail')

@section('content')
	Thank you for joining {{$team->name}}! Your team captain is {{$team->captain->fname}} {{$team->captain->lname}} ({{$team->captain->email}}). Your personal fundraising efforts will now be combined with the other members of {{$team->name}}.
@stop