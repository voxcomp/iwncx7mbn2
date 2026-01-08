@extends('layouts.app')

@section('title')
	{{$event->title}}<br>Sponsor &amp; Vendor Opportunities
@stop

@section('content')
<div class="medium-content-area">
    @if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<p>Thank you for your interest in joining the {{$event->title}} as a community partner sponsor or vendor! <strong>Sponsorships for this event will be available beginning June 1, 2025.</strong><br><br>For questions in regard to our sponsor programs, please email us at <a href="mailto:beth.viney@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}%20Sponsorship%20Inquiry">beth.viney@czarspromise.com</a> and provide your name, address, email and phone, and we will be in touch as soon as possible! It is truly because of you, our community partner sponsors and vendors, that {{date("l, F j",$event->event_date)}} will be such a special day for all participants - human and canine!</p>
</div>
@endsection
