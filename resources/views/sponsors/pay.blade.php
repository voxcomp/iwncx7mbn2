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
			<p>Thank you for supporting Czar's Promise and our mission of Inspiring Hope and Funding Research. Your generous donation will provide support for companion animals diagnosed with cancer, and grant funding for canine and pediatric cancer research within Madison, WI and surrounding communities. We are truly grateful to each of you who give so generously of your kind hearts.</p>
			{{ html()->form('POST', route('sponsor.payment', $event->slug))->id('payment-form')->open() }}
			    {{ method_field('PATCH') }}
		        <div class="form-group">
					<div class="col col-sm-8">
						<input id="mailinglist" type="checkbox" class="" name="mailinglist" value="1" checked> Join our mailing list
					</div>
		        </div>
			    <h2>Payment Information</h2>
		    	<div class="form-group">
					<label for="cardname" class="col col-sm-3">Name on Card</label>
					<div class="col col-sm-8">
						<input id="cardname" type="text" class="form-control" name="cardname" value="{{old('cardname')}}" required>
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
			    <h3 class="rtecenter amount-confirm">A payment for ${{$cost}}.00 will be submitted.</h3>
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
				label: '{{$event->short}} Sponsorship',
				amount: {{$cost}},
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