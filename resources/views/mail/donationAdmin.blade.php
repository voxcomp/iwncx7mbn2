@extends('layouts.mail')

@section('content')
	<p>{{$fname}} {{$lname}} has made a donation of ${{$amount}} on {{date('m/d/Y')}}.</p>
	@if($recurring)
	<p>This is a recurring (monthly) donation.</p>
	@endif
	<p>&nbsp;</p>
	<p>E-mail: {{$email}}<br>
	   Phone: {{$phone}}<br>
	   Address: {{$address}}, {{$city}}, {{$state}} {{$zip}}</p>
	<p>Message:<br>
		{{$comment}}</p>
@stop