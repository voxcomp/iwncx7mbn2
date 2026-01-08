@extends('layouts.textmail')

@section('content')
{{$fname}},

Today you have made a difference. Your generous gift of ${{$amount}} will provide support for companion animals, their families and children within our community in their fight against cancer. On behalf of Czar’s Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.

Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.

@if(!empty($link))
Thank you for generously choosing to make this a monthly donation. In the event you wish to cancel your donation, click the link below.
Please save this e-mail.
@endif

With best regards,                                                                       

Beth Viney – Founder                   
Czar’s Promise
www.czarspromise.com
@stop

@section('buttontext')
	Cancel My Donation
@stop
