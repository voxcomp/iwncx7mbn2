@extends('layouts.app')

@section('title')
	{{$event->title}} Sign Up Confirmation
@stop

@section('content')
	<p>Thank you for registering for the {{$event->title}} event. @if($registrant->paid>0) Your support and attendance at our walk will truly support our goal of raising ${{number_format($event->goal,2)}}.@endif</p>
	
	<p>Join us as we celebrate our 5th year of providing funding for pediatric and companion animal cancer research at UW Health – American Family Children’s Hospital and UW School of Veterinary Medicine in Madison, WI. In addition, your support ensures local families whose canines and felines have been diagnosed with cancer, receive the emotional, educational and financial support needed as their companion animals receive chemotherapy, radiation or palliative care treatment.</p>
	
	@if(empty($registrant->pagetitle) && !is_null($user))
		<p>Next, you'll want to log into your account and set up your personal fundraising page. You can post a personal message or story about why this cause is important to you, upload personal photos, set your personal fundraising goal, and more. Once your page is finished, you can share it via email or social media to begin fundraising.</p>
	@else
		<p>We will work as quickly as possible to review your personal fundraising page so that you may begin fundraising.</p>
	@endif
	
	@if(!is_null($team))
		<p>You are now a member of team {{$team->name}}. Your team captain's name is {{$team->captain->fname}} {{$team->captain->lname}} who can be reached at <a href="mailto:{{$team->captain->email}}?subject=Team%20{{$team->name}}%20Question">{{$team->captain->email}}</a> to start planning your team fundraising efforts!</p>
	@endif
	
	@if(!is_null($donation))
		<p>On behalf of Czar's Promise, we thank you for your donation of ${{$donation->amount}} to Czar's Promise, a 501(c)(3) nonprofit organization, EIN 47-2163857. Your contribution is greatly appreciated and will help support companion animals and children within our community in their fight against cancer.</p>
		<p>Your {{date('Y')}} contribution is tax-deductible to the extent allowed by law. No goods or services were provided in exchange for your generous financial donation.</p>
	@endif
	
	@if(!is_null($user))
		<p>A user account has been created for you.  Please check your e-mail and click on the validation link that has been sent to activate your new account.</p>
	@endif
	<p>On behalf of myself, our Czar’s Promise Board of Directors and our Founding Committee, we are so grateful for your support, and we can’t wait to see you {{date("l, F d, Y", $event->event_date)}} at McKee Farms Park in Fitchburg, WI!</p>
	<p>&nbsp;</p>
	<p>With paws of appreciation,<br>Beth Viney &mdash; Founder, Executive Director and Czar's Mom<br>Czar's Promise<br>P.O. Box 5061<br>Madison, WI 53705<br>608-235-7269<br><a href="mailto:beth.viney@czarspromise.com">beth.viney@czarspromise.com</a><br><a href="http://www.czarspromise.com">www.czarspromise.com</a><br><em>Inspiring Hope. Funding Research.</em></p>		
@stop
