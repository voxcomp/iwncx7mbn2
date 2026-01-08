@extends('layouts.textmail')

@section('content')
@if($anonymous)
Congratulations! An anonymous contributor has made a donation of ${{$amount}} toward your {{(!empty($event))?$event:''}} fundraising goal! Log in to see the latest updates and your fundraising progress.
@else
Congratulations! {{$fname}} {{$lname}} has made a donation of ${{$amount}} toward your {{(!empty($event))?$event:''}} fundraising goal! Log in to see the latest updates and your fundraising progress.

Don't forget to send a personal note to {{$fname}} ({{$email}}) to thank them for their donation!
@endif
@if(!empty($comment))
Personal Note:
{{$comment}}
@endif
@stop

@section('buttontext')
	Click here to log in
@stop