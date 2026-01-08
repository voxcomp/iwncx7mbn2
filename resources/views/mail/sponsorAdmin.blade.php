@extends('layouts.mail')

@section('content')
<p>{{$submission->fname}} {{$submission->lname}} has chosen a sponsorship level of <strong>{{$submission->level}}</strong> for the {{$event->title}} event.</p>
<p>A payment of ${{$submission->paid}} was made on {{date('m/d/Y g:i a')}}.</p>
<p>&nbsp;</p>
<p><strong>Company:</strong> {{$submission->company}}<br>
	<strong>E-mail:</strong> {{$submission->email}}<br>
   <strong>Phone:</strong> {{$submission->phone}}<br>
   <strong>Address:</strong> {{$submission->address}}, {{$submission->city}}, {{$submission->state}} {{$submission->zip}}</p>
@if(!empty($submission->image))
	<p>The uploaded image for the sponsor has been attached.</p>
@else
	<p>No image was uploaded for this sponsor.</p>
@endif
@stop