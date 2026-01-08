@extends('layouts.mail')

@section('content')
@if($anonymous)
<p><strong>Congratulations!</strong> An anonymous contributor has made a donation of ${{$amount}} toward your {{(!empty($event))?$event:''}} fundraising goal! Log in to see the latest updates and your fundraising progress.</p>
@else
<p><strong>Congratulations!</strong> {{$fname}} {{$lname}} has made a donation of ${{$amount}} toward your {{(!empty($event))?$event:''}} fundraising goal! Log in to see the latest updates and your fundraising progress.</p>
<p>Don't forget to send a personal note to {{$fname}} ({{$email}}) to thank them for their donation!</p>
@endif
@if(!empty($comment))
<p>Personal Note:<br>
{{$comment}}</p>
@endif
@stop

@section('buttontext')
	Click here to log in
@stop