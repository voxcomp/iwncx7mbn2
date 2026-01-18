@extends('layouts.admin')

@section('title')
	Registrant Review
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
	
	<div class="row-spacing">
		{{ html()->form('POST', route('admin.registrant.save', $registrant->slug))->id('event_register_form')->open() }}
		<div class="row">
			<div class="col col-md-6 col-spacing">
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
			    <h3 class="gray-bkg padding-8">Billing Address</h3>
				@include('parts.addressform')
			    <h3 class="gray-bkg padding-8">Shipping Address</h3>
				@include('parts.shipaddressform')
			</div>
			<div class="col col-md-6 col-spacing">
			    <h3 class="gray-bkg padding-8">Registrant</h3>
				<div class="row">
					<div class="col col-sm-6 col-spacing">
						<div class="form-group">
							<label for="tshirt">T-Shirt Size</label><br>
							{{ html()->select('shirt', ['xxxl' => 'XXXL', 'xxl' => 'XXL', 'xl' => 'XL', 'l' => 'L', 'm' => 'M', 's' => 'S', 'youthl' => 'Youth L', 'youthm' => 'Youth M', 'youths' => 'Youth S'], old('shirt', isset($user['shirt']) ? $user['shirt'] : 'l'))->class('form-control') }}
						</div>
						<div class="form-group">
							{{ html()->checkbox('shipshirt', isset($user['shipshirt']) && $user['shipshirt'] ? true : false, '1')->class('shipshirt')->attribute('onchange', 'showShipping()') }} Mail my shirt and swag
						</div>
					</div>
					<div class="col col-sm-6">
						<label for="pets">Number of Pets Attending</label>
						{{ html()->text('pets', 0)->class('form-control') }}
					</div>
				</div>
				<p>&nbsp;</p>
				<div class="form-group shipping" @if(!(isset($user['shipshirt']) && $user['shipshirt'])) style="display:none;" @endif>
					<div class="col col-sm-12 col-spacing">
					    <h3 class="gray-bkg padding-8">Shipping Address</h3>
					    {{ html()->checkbox('copybilling', false, '1')->id('copybilling')->attribute('onchange', 'copyBilling()') }} Same as my billing address
						@include('parts.shipaddressform')
					</div>
				</div>
			</div>
		</div>
		@if(!is_null($registrant->pagetitle))
	    <h3 class="gray-bkg padding-8">Personal Page</h3>
	    <div class="row">
		    <div class="col-sm-6 col-spacing">
	            <label for="teampagetitle">Page Title</label>
	            {{ html()->text('pagetitle', $registrant->pagetitle)->class('form-control') }}
		    </div>
	    </div>
		<div class="row">
			<div class="col-sm-6 col-spacing">
	            <label for="pagecontent">Page Content</label>
	            {{ html()->textarea('pagecontent', old('pagecontent', $registrant->pagecontent))->class('form-control')->id('pagecontent_ckeditor') }}
	            <div class="black-note">To upload an image, click the image button on the toolbar, then click the Upload tab.</div>
			</div>
			<div class="col-sm-6 col-spacing">
				<div class="red-note">Page Preview: If an image is too large, right click on the image and save to the desktop.  Then edit the image and use the editor to alter the page content.</div>
				<hr>
				{!!htmlspecialchars_decode($registrant->pagecontent)!!}
			</div>
		</div>
		@endif
	@if(!is_null($registrant->team) && !is_null($registrant->team->pagetitle))
	    <h3 class="gray-bkg padding-8">Team Page</h3>
	    <div class="row">
		    <div class="col-sm-6 col-spacing">
	            <label for="teampagetitle">Page Title</label>
	            {{ html()->text('teampagetitle', $registrant->team->pagetitle)->class('form-control') }}
		    </div>
	    </div>
		<div class="row">
			<div class="col-sm-6 col-spacing">
	            <label for="teampagecontent">Page Content</label>
	            {{ html()->textarea('teampagecontent', old('pagecontent', $registrant->team->pagecontent))->class('form-control')->id('teampagecontent_ckeditor') }}
	            <div class="black-note">To upload an image, click the image button on the toolbar, then click the Upload tab.</div>
			</div>
			<div class="col-sm-6 col-spacing">
				<div class="red-note">Page Preview: If an image is too large, right click on the image and save to the desktop.  Then edit the image and use the editor to alter the page content.</div>
				<hr>
				{!!htmlspecialchars_decode($registrant->team->pagecontent)!!}
			</div>
		</div>
		@endif
		<div class="row">
	        <div class="col-sm-12 rteright">
	            <button class="btn btn-primary" type="submit">Save Registrant</button>
	        </div>
		</div>
	</div>
@stop

@section('footer')
<script src="/ckeditor/ckeditor.js"></script>
<script>
	@if(!is_null($registrant->pagetitle))
	    CKEDITOR.replace( 'pagecontent_ckeditor', { customConfig:'config-basic.js' }  );
	@endif
	@if(!is_null($registrant->team) && !is_null($registrant->team->pagetitle))
	    CKEDITOR.replace( 'teampagecontent_ckeditor', { customConfig:'config-basic.js' }  );
    @endif
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
