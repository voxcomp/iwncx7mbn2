@extends('layouts.app',['hideHeader',true])

@section('title')
	Make A Donation
@stop

@section('content')
    <div class="row">
        <div class="col col-sm-12 col-md-8 col-md-offset-2">
		    @if (session()->has('message'))
			    <div class="alert alert-warning">
			        {{ session()->get('message') }}
			    </div>
			@endif
			@if ($errors->any())
			    <div class="alert alert-danger">
				    <p>There was an error with your submission:</p>
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif
			@if(isset($cause))
				<p>Your generous gift to the Dr Joshua Smith, DVM Memorial Fund will provide support for sporting canines in their fight against cancer. On behalf of Czar’s Promise, we thank you for your donation and for providing hope to those facing a cancer diagnosis.</p>
			@else
				<p>Thank you for supporting Czar's Promise and our mission of Inspiring Hope and Funding Research. Your generous donation will provide support for companion animals diagnosed with cancer, and grant funding for canine and pediatric cancer research within Madison, WI and surrounding communities. We are truly grateful to each of you who give so generously of your kind hearts.</p>
			@endif
			<form action="{{ route('donate.post') }}" id="payment-form" method="post">
			    {{ csrf_field() }}
			    @if(isset($event) && !is_null($event->id) && !isset($registrant) && !isset($team))
			    	<p>Your donation will go toward our goal of raising <strong>${{$event->goal}}</strong> at our <strong>{{$event->title}}</strong>.</p>
			    	{{ html()->hidden('event', $event->slug) }}
			    @elseif(isset($registrant) && !is_null($registrant->id))
			    	<p>Your donation will go toward our goal of raising <strong>${{$event->goal}}</strong> at our <strong>{{$event->title}}</strong> in the name of <strong>{{$registrant->fname}} {{$registrant->lname}}</strong>.</p>
			    	{{ html()->hidden('registrant', $registrant->slug) }}
			    @elseif(isset($team) && !is_null($team->id))
			    	<p>Your donation will go toward our goal of raising <strong>${{$event->goal}}</strong> at our <strong>{{$event->title}}</strong> for the team <strong>{{$team->name}}</strong>.</p>
			    	{{ html()->hidden('team', $team->slug) }}
			    @endif
				@if(isset($cause))
			    	{{ html()->hidden('cause', $cause) }}
				@endif
				<p>&nbsp;</p>
			    <img src="/images/donate.jpg" class="img-responsive" alt="">
			    <hr style="margin-top:0;">
			    <h2>Contact Information</h2>
		        <div class="form-group">
					<label for="fname" class="col col-sm-3">Name</label>
					<div class="clearfix visible-xs"></div>
					<div class="col col-sm-4 col-xs-6{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="fname" type="text" class="form-control" name="fname" placeholder="First" value="{{old('fname')}}" required autocomplete="off">
				        @if ($errors->has('fname'))
				            <span class="help-block">
				                <strong>{{ $errors->first('fname') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-4 col-xs-6{{ $errors->has('lname') ? ' has-error' : '' }}">
						<input id="lname" type="text" class="form-control" name="lname" placeholder="Last" value="{{old('lname')}}" required autocomplete="off">
				        @if ($errors->has('lname'))
				            <span class="help-block">
				                <strong>{{ $errors->first('lname') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
		        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email" class="col col-sm-3">E-mail</label>
					<div class="col col-sm-8">
						<input id="email" type="email" class="form-control col col-sm-9" name="email" value="{{old('email')}}" required autocomplete="off">
				        @if ($errors->has('email'))
				            <span class="help-block">
				                <strong>{{ $errors->first('email') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
		        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
					<label for="phone" class="col col-sm-3">Phone</label>
					<div class="col col-sm-8">
						<input id="phone" type="text" class="form-control col col-sm-8" name="phone" value="{{old('phone')}}" required autocomplete="off">
				        @if ($errors->has('phone'))
				            <span class="help-block">
				                <strong>{{ $errors->first('phone') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
		        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
					<label for="address" class="col col-sm-3">Address</label>
					<div class="col col-sm-8">
						<input id="address" type="text" class="form-control col col-sm-8" name="address" value="{{old('address')}}" required autocomplete="off">
				        @if ($errors->has('address'))
				            <span class="help-block">
				                <strong>{{ $errors->first('address') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
		        <div class="form-group">
					<div class="col col-sm-3 col-sm-offset-3 col-xs-5{{ $errors->has('city') ? ' has-error' : '' }}">
						<input id="city" type="text" class="form-control" placeholder="City" name="city" value="{{old('city')}}" required autocomplete="off">
				        @if ($errors->has('city'))
				            <span class="help-block">
				                <strong>{{ $errors->first('city') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-2 col-xs-3 no-padding{{ $errors->has('state') ? ' has-error' : '' }}">
						<input id="state" type="text" class="form-control" placeholder="State" name="state" value="{{old('state')}}" required autocomplete="off">
				        @if ($errors->has('state'))
				            <span class="help-block">
				                <strong>{{ $errors->first('state') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-3 col-xs-4{{ $errors->has('zip') ? ' has-error' : '' }}">
						<input id="zip" type="text" class="form-control" placeholder="Zip" name="zip" value="{{old('zip')}}" required autocomplete="off">
				        @if ($errors->has('zip'))
				            <span class="help-block">
				                <strong>{{ $errors->first('zip') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
				@if(!isset($cause))
			        <div class="form-group">
						<div class="col col-sm-8">
							<input id="mailinglist" type="checkbox" class="" name="mailinglist" value="1" checked> Join our mailing list
						</div>
			        </div>
				    <hr>
				@else
			    	{{ html()->hidden('mailinglist', 0) }}
				@endif
		        <div class="form-group">
					<div class="col col-sm-8">
						<input id="anonymous" type="checkbox" class="" name="anonymous" value="1"> I would like my donation to be anonymous.
					</div>
		        </div>
			    <hr>
		        <div class="form-group">
					<label for="comment" class="col col-sm-3">Comments or Message</label>
					<div class="col col-sm-8">
						<textarea class="form-control col col-sm-8" name="comment" value="{{old('comment')}}"></textarea>
					</div>
		        </div>
			    <hr>
				@include('parts.donations')
			    <hr>
			    <h2>Payment Information</h2>
				@if(!isset($cause))
					<div class="row">
						<div class="col col-xs-12 col-sm-4"><p><strong>Would you like to make this a recurring monthly donation?:</strong></p></div>
						<div class="visible-xs clearfix"></div>
					    <div class="col col-xs-6 col-sm-3 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block recurring" data-recurring="YES">YES</a></div>
					    <div class="col col-xs-6 col-sm-3 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block recurring active" data-recurring="NO">NO</a></div>
					</div>
				@endif
				<input type="hidden" id="recurring" name="recurring" value="NO">
			    @if(\Auth::check() && \Auth::user()->isAdmin())
				    	{{ html()->checkbox('nopayment', null, 1) }} Offline (cash or check) Payment
				    	<p class="black-note">Payment information below is not required.</p>
			    @endif
		    	<div class="form-group">
					<label for="cardname" class="col col-sm-3">Name on Card</label>
					<div class="col col-sm-8">
						<input id="cardname" type="text" class="form-control" name="cardname" value="{{old('cardname')}}">
					</div>
		        </div>
		        <div class="form-group">
			        <div class="col col-sm-8 col-sm-offset-3"
				        <!-- Stripe Element -->
				        <div id="card-element" class="form-control">
					      <!-- a Stripe Element will be inserted here. -->
					    </div>
					    <div id="payment-request-button">
					    </div>
					</div>
		        </div>
		        <p>&nbsp;</p>
			    <div class="form-group">
			        <div class="col col-sm-12 rtecenter">
			            <p><a href="{{ env('WP_PATH') }}/privacy-policy" target="_blank">View our privacy policy</a></p>
			        </div>
			    </div>
			    <h3 class="rtecenter amount-confirm" style="display:none;">A payment for <span class="final-amount"></span> will be submitted.</h3>
			    <div class="form-group">
			        <div class="col-sm-12 rtecenter">
			            <button class="btn btn-primary" type="submit">Submit Donation</button>
			            <p class="red-note">Please only click the submit button ONCE or multiple payments may be captured.</p>
			        </div>
			    </div>
			    <div class="form-group">
			        <div class="col-sm-12 error hide">
			            <div class="alert-danger alert" role="alert" id="card-errors"></div>
			        </div>
			    </div>
			</form>
        </div>
    </div>
@stop

@section('footer')
<script src="https://js.stripe.com/v3/"></script>
<script>
	$(window).on("load",function() {
		var emailTimer;
		var couponTimer;
		var stripe = Stripe('{{ $stripe_pk }}');
		var elements = stripe.elements({
			fonts: [{
					cssSrc: 'https://fonts.googleapis.com/css?family=Montserrat',
				}]
			});
		var style = {
			base: {
				fontSize: '14px',
				iconColor: '#121212',
				color: "#575757",
				fontWeight: 500,
				fontFamily: "Montserrat, Open Sans, sans-serif",
				fontSmoothing: 'antialiased',
				'::placeholder': {
		          color: '#121212',
		        },
		    },
			invalid: {
				iconColor: '#9E2A23',
				color: '#9E2A23',
			},
   		}
		var card = elements.create('card', {style: style});
		card.mount('#card-element');

		var paymentRequest = stripe.paymentRequest({
			country: 'US',
			currency: 'usd',
			total: {
				label: 'Donation',
				amount: 20000,
			},
		});
	
		card.addEventListener('change', function(event) {
		  var displayError = document.getElementById('card-errors');
		  if (event.error) {
		    displayError.textContent = event.error.message;
		  } else {
		    displayError.textContent = '';
		  }
		});
		
		// Create a token or display an error when the form is submitted.
		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function(event) {
		  event.preventDefault();
		  var continueProcess = true;

		    @if(\Auth::check() && \Auth::user()->isAdmin())
		    	if($('input[name=nopayment]').is(':checked')) {
					var form = document.getElementById('payment-form');
					form.submit();
		    	} else {
		    @endif
		  
				var el = $('#coupon');
				if(el.val()!='' && el.parent().hasClass("has-error")) {
					continueProcess = false;
				}
				if(continueProcess) {
				  var options = {
					  name: document.getElementById('cardname').value,
				  };
				  stripe.createToken(card,options).then(function(result) {
				    if (result.error) {
				      // Inform the customer that there was an error
				      var errorElement = document.getElementById('card-errors');
				      errorElement.textContent = result.error.message;
				      $('form .error').fadeIn(300);
				    } else {
				      // Send the token to your server
				      stripeTokenHandler(result.token);
				    }
				  });
				}
		    @if(\Auth::check() && \Auth::user()->isAdmin())
		    	}
		    @endif
		});
		function stripeTokenHandler(token) {
		  // Insert the token ID into the form so it gets submitted to the server
		  var form = document.getElementById('payment-form');
		  var hiddenInput = document.createElement('input');
		  hiddenInput.setAttribute('type', 'hidden');
		  hiddenInput.setAttribute('name', 'stripeToken');
		  hiddenInput.setAttribute('value', token.id);
		  form.appendChild(hiddenInput);
		
		  form.submit();
		}
	});
</script>
@stop