@extends('layouts.textmail')

@section('content')
	{{$fname}} {{$lname}} has made a donation of ${{$amount}} on {{date('m/d/Y')}}.
	@if($recurring)
	This is a recurring (monthly) donation.
	@endif
	
	E-mail: {{$email}}
	Phone: {{$phone}}
	Address: {{$address}}, {{$city}}, {{$state}} {{$zip}}
	
	Message:
	{{$comment}}
@stop