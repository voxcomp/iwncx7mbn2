@extends('layouts.mail')

@section('content')
<p>{{$fname}},</p>

<p>Thank you for your donation of ${{$amount}} to the Dr Joshua Smith, DVM Memorial Fund. Your generous gift will provide support for sporting canines in their fight against cancer. On behalf of Czar's Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.

<p>Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.</p>

<p>With best regards,</p>                                                                      

<p>Beth Viney &mdash; Founder</p>

<p>Czar’s Promise<br>
<a href="http://www.czarspromise.com">www.czarspromise.com</a></p>


@stop

@section('buttontext')
	Cancel My Donation
@stop
