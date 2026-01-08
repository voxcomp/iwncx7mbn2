@extends('layouts.mail')

@section('content')
	<p>There are currently <strong>{{$count}}</strong> personal pages waiting for review.</p>
@stop

@section('buttontext')
	Click here to log in
@stop