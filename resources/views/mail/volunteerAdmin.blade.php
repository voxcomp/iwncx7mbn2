@extends('layouts.mail')

@section('content')
	<p>{{$submission->fname}} {{$submission->lname}} wants to volunteer for the {{$event->title}} event.</p>
	<p>&nbsp;</p>
	<p><strong>Company:</strong> {{$submission->company}}<br>
		<strong>E-mail:</strong> {{$submission->email}}<br>
	   <strong>Phone:</strong> {{$submission->phone}}<br>
	   <strong>Address:</strong> {{$submission->address}}, {{$submission->city}}, {{$submission->state}} {{$submission->zip}}</p>
@stop