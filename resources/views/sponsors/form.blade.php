@extends('layouts.app')

@section('title')
	{{$event->title}}<br>Sponsor &amp; Vendor Opportunities
@stop

@section('content')
<div class="medium-content-area">
    @if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<p>Thank you for your interest in joining the {{$event->title}} as a community partner sponsor or vendor! For questions in regard to our sponsor programs, please email us at <a href="mailto:beth.viney@czarspromise.com?subject={{str_replace(" ","%20",$event->title)}}%20Sponsorship%20Inquiry">beth.viney@czarspromise.com</a> and provide your name, address, email and phone, and we will be in touch as soon as possible! It is truly because of you, our community partner sponsors and vendors, that {{date("l, F j",$event->event_date)}} will be such a special day for all participants - human and canine!</p>
	{!!Form::open(['route'=>['sponsor.submission',$event->slug],'files'=>true, 'id'=>'sponsor_form'])!!}
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
	            {!! Form::select('state', ['AL'=>'AL','AK'=>'AK','AS'=>'AS','AZ'=>'AZ','AR'=>'AR','CA'=>'CA','CO'=>'CO','CT'=>'CT','DE'=>'DE','DC'=>'DC','FM'=>'FM','FL'=>'FL','GA'=>'GA','GU'=>'GU','HI'=>'HI','ID'=>'ID','IL'=>'IL','IN'=>'IN','IA'=>'IA','KS'=>'KS','KY'=>'KY','LA'=>'LA','ME'=>'ME','MH'=>'MH','MD'=>'MD','MA'=>'MA','MI'=>'MI','MN'=>'MN','MS'=>'MS','MO'=>'MO','MT'=>'MT','NE'=>'NE','NV'=>'NV','NH'=>'NH','NJ'=>'NJ','NM'=>'NM','NY'=>'NY','NC'=>'NC','ND'=>'ND','MP'=>'MP','OH'=>'OH','OK'=>'OK','OR'=>'OR','PW'=>'PW','PA'=>'PA','PR'=>'PR','RI'=>'RI','SC'=>'SC','SD'=>'SD','TN'=>'TN','TX'=>'TX','UT'=>'UT','VT'=>'VT','VI'=>'VI','VA'=>'VA','WA'=>'WA','WV'=>'WV','WI'=>'WI','WY'=>'WY'], old('state'), ['class'=>'form-control', 'required']) !!}
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
        <p><strong>Please pick your sponsorship level:</strong></p>
		<div class="form-group">
			<div class="col col-sm-6">
				{{-- <a class="link" onclick="AlertDialog('','Guardian Sponsor Level','https://www.czarspromise.com/sponsorlevel/guardian-sponsor',600,600)"> --}}
				@if(($event->sponsorSubmissions->where('level','Guardian')->count()<4 && time()<strtotime('first day of june this year')))
					{!!Form::radio('level','Guardian')!!} Guardian - 4 Available (${{\App\Http\Controllers\Controller::levelCost('Guardian')}})
				@else
					{!!Form::radio('level','Guardian',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Guardian - Presenting Sponsor (Sold Out)</span>
				@endif
				<br>
				@if(time()<strtotime('first day of august this year'))
					{!!Form::radio('level','Golden Retriever')!!} Golden Retriever (${{\App\Http\Controllers\Controller::levelCost('Golden Retriever')}})
				@else
					{!!Form::radio('level','Golden Retriever',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Golden Retriever (Sold Out)</span>
				@endif
				<br>
				@if(time()<strtotime('first day of august this year'))
					{!!Form::radio('level','Stage')!!} Stage - 1 Available (${{\App\Http\Controllers\Controller::levelCost('Stage')}})
				@else
					{!!Form::radio('level','Stage',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Stage (Sold Out)</span>
				@endif
				<br>
				@if($event->sponsorSubmissions->where('level','Heroes')->count()<1 && time()<strtotime('first day of june this year'))
					{!!Form::radio('level','Heroes')!!} Heroes - 1 Available (${{\App\Http\Controllers\Controller::levelCost('Heroes')}})
				@else
					{!!Form::radio('level','Heroes',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Heroes (Sold Out)</span>
				@endif
				<br>
				@if($event->sponsorSubmissions->where('level','Inspiring Hope')->count()<1 && time()<strtotime('first day of september this year'))
					{!!Form::radio('level','Inspiring Hope')!!} Inspiring Hope - 1 Available (${{\App\Http\Controllers\Controller::levelCost('Inspiring Hope')}})
				@else
					{!!Form::radio('level','Inspiring Hope',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Inspiring Hope (Sold Out)</span>
				@endif
				<br>
				@if(time()<strtotime('first day of september this year'))
					{!!Form::radio('level','Hound')!!} Hound (${{\App\Http\Controllers\Controller::levelCost('Hound')}})
				@else
					{!!Form::radio('level','Hound',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Hound (Sold Out)</span>
				@endif
			</div>
			<div class="col col-sm-6">
				@if(time()<strtotime('first day of october this year'))
					{!!Form::radio('level','Veterinary Partner')!!} Veterinary Partner (${{\App\Http\Controllers\Controller::levelCost('Veterinary Partner')}})<br>
				@else
					{!!Form::radio('level','Veterinary Partner',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Veterinary Partner (Sold Out)</span><br>
				@endif
				@if(time()<strtotime('first day of october this year'))
					{!!Form::radio('level','Bulldog')!!} Bulldog (${{\App\Http\Controllers\Controller::levelCost('Bulldog')}})<br>
				@else
						{!!Form::radio('level','Bulldog',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Bulldog (Sold Out)</span><br>
				@endif
				@if(time()<strtotime('first day of october this year'))
					{!!Form::radio('level','Non-Profit Partner')!!} Non-Profit Partner (${{\App\Http\Controllers\Controller::levelCost('Non-Profit Partner')}})<br>
				@else
						{!!Form::radio('level','Non-Profit Partner',false,['disabled'=>'disabled'])!!} <span class="disabled-text">Non-Profit Partner (Sold Out)</span><br>
				@endif
				@if(\Auth::check() && \Auth::user()->isAdmin())
					{!!Form::radio('level','In-Kind')!!} In-Kind (${{\App\Http\Controllers\Controller::levelCost('In-Kind')}})<br>
					<label for="inkindvalue">In-Kind Value ($):</label>{!!Form::text('inkindvalue',old('inkindvalue',0),['class'=>'form-control'])!!}
				@endif
			</div>
		</div>
		@if(!(\Auth::check() && \Auth::user()->isAdmin()))
        <p class="black-note"><a href="https://www.czarspromise.com/sponsorship-levels" target="_blank">Click here to learn more</a> about what is included with your sponsorship.</p>
        @endif
        <p>&nbsp;</p>
    	<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
	    	<div class="col col-sm-12">
		    	<p>Please upload your logo or the image you would like displayed on the {{$event->title}} event page. This is not required if you do not wish your sponsorship to be presented on the event page.</p>
				<input type="file" class="image" name="image" value="">
	            @if ($errors->has('image'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('image') }}</strong>
	                </span>
	            @endif
	            <div class="black-note">Images must be 1MB or smaller, and in PNG or JPG format.</div>
	    	</div>
    	</div>
        <p>&nbsp;</p>
	    @if(\Auth::check() && \Auth::user()->isAdmin())
	    	<div class="row-spacing">
		    	{!!Form::checkbox('nopayment',1)!!} Offline (cash or check) Payment
	    	</div>
	    @endif
        <div class="form-group">
            <div class="col-sm-12 rtecenter">
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
            </div>
        </div>
    {!!Form::close()!!}
</div>
@endsection
