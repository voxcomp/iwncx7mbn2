@extends('layouts.image',['contentAlign'=>'middle','imageClass'=>'image-full', 'image'=>'/images/login-background.jpg'])

@section('title')
	Resend E-mail Validation
@stop

@section('content')
	<h2>Thank you for creating an account!</h2>
	<h3>Just one more step....</h3>
	<p>In order to prevent spam, it is necessary for us to verify your e-mail address.  You should receive an e-mail from us shortly, please click on the link provided to validate your address and complete your account set up.<br><br>If you do not receive the e-mail shortly, please check your spam folder.</p>
@stop
