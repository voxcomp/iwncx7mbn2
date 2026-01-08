@extends('layouts.app')

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">Sign Up</span>
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif
	<div class="medium-content-area">
		{!! Form::open(array('route' => ['event.register.step1',$event->slug], 'id'=>'event_register_form')) !!}
		    <h3 class="gray-bkg padding-8">Contact Information</h3>
	        <div class="row">
				<div class="col col-sm-6 col-spacing{{ $errors->has('fname') ? ' has-error' : '' }}">
		            <input id="fname" type="text" class="form-control" name="fname" value="{{ old('fname',(isset($user['fname']))?$user['fname']:'') }}" required autofocus placeholder="First Name">
		            @if ($errors->has('fname'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('fname') }}</strong>
		                </span>
		            @endif
				</div>
				<div class="col col-sm-6 col-spacing{{ $errors->has('email') ? ' has-error' : '' }}">
		            <input id="lname" type="text" class="form-control" name="lname" value="{{ old('lname',(isset($user['lname']))?$user['lname']:'') }}" required placeholder="Last Name">
		            @if ($errors->has('lname'))
		                <span class="help-block">
		                    <strong>{{ $errors->first('lname') }}</strong>
		                </span>
		            @endif
				</div>
	        </div>
	        <div class="row">
			    <div class="col col-sm-6 col-spacing{{ $errors->has('email') ? ' has-error' : '' }}">
			        <input id="email" type="email" class="form-control" name="email" value="{{ old('email',(isset($user['email']))?$user['email']:'') }}" autocomplete="off" required placeholder="E-mail Address">
			        @if ($errors->has('email'))
			            <span class="help-block">
			                <strong>{{ $errors->first('email') }}</strong>
			            </span>
			        @endif
			    </div>
			    <div class="col col-sm-6 col-spacing{{ $errors->has('phone') ? ' has-error' : '' }}">
			        <input id="phone" type="phone" class="form-control" name="phone" value="{{ old('phone',(isset($user['phone']))?$user['phone']:'') }}" autocomplete="off" required placeholder="Phone">
			        @if ($errors->has('phone'))
			            <span class="help-block">
			                <strong>{{ $errors->first('phone') }}</strong>
			            </span>
			        @endif
			    </div>
	        </div>
		    <p>&nbsp;</p>
		    <h3 class="gray-bkg padding-8">Billing Address</h3>
			@include('parts.addressform')
		    <p>&nbsp;</p>
		    <h3 class="gray-bkg padding-8">Registrant</h3>
			<div class="form-group">
				<div class="col col-sm-5 col-spacing">
		            {{Form::radio('registrant','adult',true)}} Adult - ${{$event->costs->where('ends','>',time())->sortBy('ends')->first()->cost}}.00<br>
		            {{Form::radio('registrant','youth')}} Youth - 12 and under FREE
				</div>
				<div class="col col-sm-7">
					<div class="row">
						<div class="col col-sm-6 col-spacing">
							<div class="form-group">
								<label for="tshirt">T-Shirt Size</label><br>
								{!!Form::select('tshirt',['noshirt'=>'No Shirt', 'xxxl'=>'XXXL', 'xxl'=>'XXL', 'xl'=>'XL', 'l'=>'L', 'm'=>'M', 's'=>'S', 'youthl'=>'Youth L', 'youthm'=>'Youth M', 'youths'=>'Youth S'],old('tshirt','noshirt'),['class'=>'form-control'])!!}
							</div>
							{{--
							<div class="form-group">
								{{Form::checkbox('shipshirt','1',(isset($user['shipshirt']) && $user['shipshirt'])?true:false,['class'=>'shipshirt','onchange'=>'showShipping()'])}} Mail my shirt and swag for an additional $8
							</div>--}}
							<div class="form-group red-note"><strong>Need to register prior to September 20, {{date("Y")}} to be assured of a t-shirt.</strong></div>
						</div>
						<div class="col col-sm-6">
							<label for="pets">Number of Pets Attending</label>
							{!!Form::text('pets',0,['class'=>'form-control'])!!}
						</div>
					</div>
				</div>
			</div>
			<div class="form-group shipping" @if(!(isset($user['shipshirt']) && $user['shipshirt'])) style="display:none;" @endif>
				<div class="col col-sm-12 col-spacing">
				    <h3 class="gray-bkg padding-8">Shipping Address</h3>
				    {{Form::checkbox('copybilling','1',false,['id'=>'copybilling','onchange'=>'copyBilling()'])}} Same as my billing address
					@include('parts.shipaddressform')
				</div>
			</div>
		    <p>&nbsp;</p>
		    @include('parts.donations',['stripe'=>1,'register'=>1])
		    <p>&nbsp;</p>
		    <h3 class="gray-bkg padding-8">Liability Terms</h3>
		    <div class="form-group">
				<div class="col col-sm-12">
		            {{Form::checkbox('liability','1',false)}} I have read and agree to the <a href="https://www.czarspromise.com/event-liability-waiver" target="_blank">liability terms and conditions</a>
				</div>
		    </div>
		    <p>&nbsp;</p>
		    <div class="form-group">
		        <div class="col-sm-12 rtecenter">
		            <input type="submit" class="btn btn-primary" value="Continue to Step 2">
		        </div>
		    </div>
		{!! Form::close() !!}
	</div>
@stop

@section('rightsidebar')
	<div class="block">
		<div><img class="img-full" src="/storage/public/{{$event->image}}" alt="{{$event->short}}"></div>
		<h2 class="rtecenter">{{$event->title}}</h2>
		<h4 class="rtecenter">{{date("l",$event->event_date)}}<br>{{date("F d, Y",$event->event_date)}}</h4>
	</div>
	<div class="block">
		<h3>Fundraising Goal: ${{$event->goal}}</h3>
		<div class="event-goal">
			<div class="event-goal-mask">
				<img src="/images/goal-mask.png" class="img-full">
			</div>
			<div class="event-goal-raised" style="height:{{$event->percent()}}%"></div>
		</div>
		<div class="rtecenter padding-top-15">Raised to date: ${{number_format($event->raised(),2)}}</div>
	</div>
@stop

@section('footer')
<script>
	(function($) {
		window.copyBilling = function() {
			if($('#copybilling').is(':checked')) {
				$('#shipaddress').val($('#address').val());
				$('#shipcity').val($('#city').val());
				$('#shipstate').val($('#state').val());
				$('#shipzip').val($('#zip').val());
			} else {
				$('#shipaddress').val('');
				$('#shipcity').val('');
				$('#shipstate').val('');
				$('#shipzip').val('');
			}
		}
		window.showShipping = function() {
			if($('.shipshirt').is(':checked')) {
				$('.shipping').slideDown(300);
				$('#shipaddress').prop('required',true);
				$('#shipcity').prop('required',true);
				$('#shipstate').prop('required',true);
				$('#shipzip').prop('required',true);
			} else {
				$('.shipping').slideUp(300);
				$('#shipaddress').prop('required',false);
				$('#shipcity').prop('required',false);
				$('#shipstate').prop('required',false);
				$('#shipzip').prop('required',false);
			}
		}
	})(jQuery);
</script>
@stop
