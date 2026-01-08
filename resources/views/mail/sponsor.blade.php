@extends('layouts.mail')

@section('content')
	<p>Thank you for sponsoring {{$event->title}} at the {{$submission->level}} level! Your donation of ${{$submission->paid}}.00 will assist in achieving our goals!
	<p>Today you have made a difference. Your generous gift will provide support for companion animals, their families and children within our community in their fight against cancer. On behalf of Czar's Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.</p>

	<p>Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.</p>
	<p>With best regards,<br>Beth Viney &mdash; Founder<br>Czar's Promise<br><a href="http://www.czarspromise.com">www.czarspromise.com</a></p>
@stop