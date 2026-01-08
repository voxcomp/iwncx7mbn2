@extends('layouts.app')

@section('title')
	Thank You for Volunteering!
@stop

@section('content')
<div class="medium-content-area">
	<p>Dear {{$volunteer->fname}},</p> 

	<p>Thank you for volunteering for the {{$event->title}}!</p>

	<p>We are so appreciative to have you join our effort to provide compassionate support, education, and funding for canine and pediatric cancer research and companion animal cancer treatment within Madison, WI and the surrounding areas.<br>
	Our Czar's Promise volunteer coordinator will be in touch with you very soon in regard to volunteer opportunities leading up to and on {{date("l, F d, Y", $event->event_date)}}.  Should you have any questions prior to our engagement, please contact us at <a href="mailto:cpvolunteers@czarspromise.com?subject={{str_replace(" ","%20",$event->short)}}%20Volunteer">cpvolunteers@czarspromise.com</a>.</p> 

	<p>Thank you for your support!</p>

	<p>The Czar's Promise Team</p>

	<p class="black-note">Czar’s Promise is a 501(c)(3) organization.</p>
	
	<p><a href="{{route('event.view',$event->slug)}}"><i class="fa fa-chevron-left"></i> Back to the event</a></p>
</div>
@stop
