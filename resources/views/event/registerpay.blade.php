@extends('layouts.app',['hideHeader',true])

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">Payment</span>
@stop

@section('content')
    <div class="row">
        <div class="col col-sm-12 col-md-8 col-md-offset-2">
		    @if (session()->has('message'))
			    <div class="alert alert-warning">
			        {{ session()->get('message') }}
			    </div>
			@endif
		    @if (isset($message))
			    <div class="alert alert-warning">
			        {{ $message }}
			    </div>
			@endif
			<p>Thank you for supporting Czar's Promise and our mission of Inspiring Hope and Funding Research. Your generous donation will provide support for companion animals diagnosed with cancer, and grant funding for canine and pediatric cancer research within Madison, WI and surrounding communities. We are truly grateful to each of you who give so generously of your kind hearts.</p>
			<form action="{{ route('event.register.pay',[$event->slug]) }}" id="payment-form" name="payment-form" method="post">
			    {{ csrf_field() }}
		        <div class="form-group">
					<div class="col col-sm-8">
						<input id="mailinglist" type="checkbox" class="" name="mailinglist" value="1" checked> Join our mailing list
					</div>
		        </div>
		        @if($registranttype=='adult')
			        <p>Your event sign up cost: <strong>${{$cost}}</strong></p>
			    @else
			    	<p>Your event sign up cost: <strong>$0</strong></p>
			    @endif
		        @if($donation>0)
		        	<p>Your chosen donation: <strong>${{$donation}}</strong></p>
		        @endif
			    <h2>Payment Information</h2>
		        @if($registranttype=='adult')
			        <div class="form-group">
						<label for="coupon" class="col col-sm-3">Discount Code</label>
						<div class="col col-sm-4">
							<input id="coupon" type="text" class="form-control" name="coupon">
						</div>
			        </div>
				@endif
				@if(\Auth::check() && \Auth::user()->isAdmin())
						{!!Form::checkbox('nopayment',1)!!} Offline (cash or check) Payment
						<p class="black-note">Payment information below is not required.</p>
				@endif
		    	<div class="form-group">
					<label for="cardname" class="col col-sm-3">Name on Card</label>
					<div class="col col-sm-8">
						<input id="cardname" type="text" class="form-control" name="cardname" value="{{old('cardname')}}">
					</div>
		        </div>
		        <div class="form-group">
			        <div class="col col-sm-8 col-sm-offset-3">
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
		        @if($registranttype=='adult')
				    <h3 class="rtecenter amount-confirm">A payment for ${{$cost + $donation}}.00 will be submitted.</h3>
				@else
				    <h3 class="rtecenter amount-confirm">A payment for ${{$donation}}.00 will be submitted.</h3>
				@endif
			    <div class="form-group">
			        <div class="col-sm-12 rtecenter">
			            <button class="btn btn-primary" type="submit">Submit Payment</button>
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
        @if($registranttype=='adult')
		    var payamount = {{$cost}}.00;
		@else
		    var payamount = 0;
		@endif
		var donateamount = {{$donation}}.00;
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
				label: '{{$event->short}}',
				amount: {{ (($registranttype=='adult')?$cost:0) + $donation }},
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

		@if($registranttype=='adult')
			var couponElement = document.getElementById('coupon');
			couponElement.addEventListener('keyup',function(event) {
				clearTimeout(couponTimer);
				if(couponElement.value.length) {
					couponTimer = setTimeout(function() { checkCoupon(couponElement.value,'coupon',payamount,donateamount); },450);
				} else {
					$("#coupon").parent().removeClass("has-error").find(".alert").remove();
				}
			});
		@endif
		
		// Create a token or display an error when the form is submitted.
		var form = document.getElementById('payment-form');
		form.addEventListener('submit', function(event) {
		  event.preventDefault();

		@if(\Auth::check() && \Auth::user()->isAdmin())
		  if($('input[name=nopayment]').is(':checked')) {
			  var form = document.getElementById('payment-form');
			  form.submit();
		  } else {
		  @endif

		  var continueProcess = true;
		  var options = {
			  name: document.getElementById('cardname').value,
		  };

			var el = $('#coupon');
			if(el.val()!='' && el.parent().hasClass("has-error")) {
				continueProcess = false;
			}
			if(continueProcess) {
				if((window.payamount+window.donateamount)>0) {
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
				} else {
					var form = document.getElementById('payment-form');
					//console.log("nothing to pay");
					form.submit();
				}
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
		
		  //console.log("submitting payment");
		  form.submit();
		}
	});
</script>
@stop