@extends('layouts.app',['hideHeader'=>true,'promise'=>true])

@section('title')
	Czar's Promise Wall Donation
@stop

@section('content')
    <div class="row">
        <div class="col col-sm-12 col-md-8 col-md-offset-2">
		    @if (session()->has('message'))
			    <div class="alert alert-warning">
			        {{ session()->get('message') }}
			    </div>
			@endif
			<p>On behalf of Czar’s Promise, we thank you for your kind donation in honor of Promise Day.  If submitted, your photo along with the designated name, will be automatically uploaded to our Promise Wall on our Czar’s Promise web page.  Your donation, will allow us to continue to provide support to local families whose companion animals have been diagnosed with cancer and are undergoing chemotherapy treatment, as well as fund grant research to help find a cure in our companion animals and children.</p>
			<p><a href="https://www.czarspromise.com/czars-promise-wall-fundraiser">Back To Website</a> or <a href="/promise-wall">View Promise Wall</a></p>
        </div>
    </div>
@stop
