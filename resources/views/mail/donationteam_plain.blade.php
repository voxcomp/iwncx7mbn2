@extends('layouts.textmail')

@section('content')
@if($anonymous)
Congratulations! An anonymous contributor has made a donation of ${{$amount}} toward your team's fundraising goal! Log in to see the latest updates and your team standings.
@else
Congratulations! {{$fname}} {{$lname}} has made a donation of ${{$amount}} toward your team's fundraising goal! Log in to see the latest updates and your team standings.

Don't forget to send a personal note to {{$fname}} ({{$email}}) to thank them for their donation!
@endif
Personal Note:
@if(!empty($comment))
{{$comment}}
@endif
@stop

@section('buttontext')
	Click here to log in
@stop