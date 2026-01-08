@extends('layouts.textmail')

@section('content')
{{$fname}},

Thank you for your donation of ${{$amount}} to the Dr Joshua Smith, DVM Memorial Fund. Your generous gift will provide support for sporting canines in their fight against cancer. On behalf of Czar's Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.

Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.

With best regards,                                                                       

Beth Viney – Founder                   
Czar’s Promise
www.czarspromise.com
@stop

@section('buttontext')
	Cancel My Donation
@stop
