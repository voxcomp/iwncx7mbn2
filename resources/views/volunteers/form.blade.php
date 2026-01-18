@extends('layouts.app')

@section('title')
	{{$event->title}} Volunteer Opportunities
@stop

@section('content')
<div class="medium-content-area">
    @if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<p>Thank you for your interest in volunteering at the {{$event->title}}! Please complete the form below and our Czar's Promise volunteer coordinator will be in touch with you very soon!</p>
	<p>For additional questions or requests for volunteer opportunities, please email us at <a href="mailto:cpvolunteers@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}%20Volunteer">cpvolunteers@czarspromise.com</a>.</p>
	{{ html()->form('POST', route('volunteer.submission', $event->slug))->id('volunteer_form')->open() }}
        <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
	        <div class="col col-sm-12">
                <input id="company" type="text" class="form-control" name="company" value="{{ old('company') }}" placeholder="Company" autofocus>

                @if ($errors->has('company'))
                    <span class="help-block">
                        <strong>{{ $errors->first('company') }}</strong>
                    </span>
                @endif
	        </div>
        </div>
        <div class="form-group">
	        <div class="col col-sm-6{{ $errors->has('fname') ? ' has-error' : '' }} col-spacing">
                <input id="fname" type="text" class="form-control" name="fname" value="{{ old('fname') }}" placeholder="First Name" required>

                @if ($errors->has('fname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fname') }}</strong>
                    </span>
                @endif
	        </div>
	        <div class="col col-sm-6{{ $errors->has('lname') ? ' has-error' : '' }}">
                <input id="lname" type="text" class="form-control" name="lname" value="{{ old('lname') }}" placeholder="Last Name" required>

                @if ($errors->has('lname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lname') }}</strong>
                    </span>
                @endif
	        </div>
        </div>
        <p>&nbsp;</p>
        <div class="form-group">
	        <div class="col col-sm-6{{ $errors->has('phone') ? ' has-error' : '' }} col-spacing">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone" required>

                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
	        </div>
	        <div class="col col-sm-6{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
	        </div>
        </div>
        <p>&nbsp;</p>
		<div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
	        <div class="col col-sm-12">
	            <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required placeholder="Address">
	            @if ($errors->has('address'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('address') }}</strong>
	                </span>
	            @endif
	        </div>
		</div>
		<div class="row">
			<div class="col col-sm-6 col-xs-12 col-spacing{{ $errors->has('city') ? ' has-error' : '' }}">
	            <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required placeholder="City">
	            @if ($errors->has('city'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('city') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="visible-xs clearfix"></div>
			<div class="col col-sm-2 col-xs-4 col-spacing{{ $errors->has('state') ? ' has-error' : '' }}">
	            {{ html()->select('state', ['AL' => 'AL', 'AK' => 'AK', 'AS' => 'AS', 'AZ' => 'AZ', 'AR' => 'AR', 'CA' => 'CA', 'CO' => 'CO', 'CT' => 'CT', 'DE' => 'DE', 'DC' => 'DC', 'FM' => 'FM', 'FL' => 'FL', 'GA' => 'GA', 'GU' => 'GU', 'HI' => 'HI', 'ID' => 'ID', 'IL' => 'IL', 'IN' => 'IN', 'IA' => 'IA', 'KS' => 'KS', 'KY' => 'KY', 'LA' => 'LA', 'ME' => 'ME', 'MH' => 'MH', 'MD' => 'MD', 'MA' => 'MA', 'MI' => 'MI', 'MN' => 'MN', 'MS' => 'MS', 'MO' => 'MO', 'MT' => 'MT', 'NE' => 'NE', 'NV' => 'NV', 'NH' => 'NH', 'NJ' => 'NJ', 'NM' => 'NM', 'NY' => 'NY', 'NC' => 'NC', 'ND' => 'ND', 'MP' => 'MP', 'OH' => 'OH', 'OK' => 'OK', 'OR' => 'OR', 'PW' => 'PW', 'PA' => 'PA', 'PR' => 'PR', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VT' => 'VT', 'VI' => 'VI', 'VA' => 'VA', 'WA' => 'WA', 'WV' => 'WV', 'WI' => 'WI', 'WY' => 'WY'], old('state'))->class('form-control')->required() }}
	            @if ($errors->has('state'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('state') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="col col-sm-4 col-xs-8 col-spacing{{ $errors->has('zip') ? ' has-error' : '' }}">
	            <input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" required placeholder="Zip Code">
	            @if ($errors->has('zip'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('zip') }}</strong>
	                </span>
	            @endif
			</div>
		</div>
        <p>&nbsp;</p>
        <div class="form-group">
            <div class="col-sm-12 rtecenter">
                <input type="submit" class="btn btn-primary" name="submit" value="Sign Me Up!">
            </div>
        </div>
    {{ html()->form()->close() }}
</div>
@endsection
