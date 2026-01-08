@extends((\Auth::user()->isAdmin())?'layouts.admin':'layouts.app')

@section('title')
	Unauthorized
@stop

@section('content')
	<p>Bad dog.....you aren't allowed in this area!</p> 
@stop
