@extends('layouts.mail')

@section('content')
<p>{{$fname}},</p>

<p>Today you have made a difference. Your generous gift of ${{$amount}} will provide support for companion animals, their families and children within our community in their fight against cancer. On behalf of Czar’s Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.</p>

<p>Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.</p>
@if(!empty($link))
<p>Thank you for choosing to make this a monthly donation. In the event you wish to cancel your donation, click the button or link below.<br>
Please save this e-mail.</p>
@endif
<p>With best regards,</p>                                                                      

<p>Beth Viney &mdash; Founder</p>

<p>Czar’s Promise<br>
<a href="http://www.czarspromise.com">www.czarspromise.com</a></p>


@stop

@section('buttontext')
	Cancel My Donation
@stop
