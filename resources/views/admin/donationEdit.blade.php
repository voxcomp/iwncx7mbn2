@extends('layouts.admin')

@section('title')
	Donation
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
			<form action="{{ route('admin.donation.save',[$donation->id]) }}" id="donation-form" method="post">
			    {{ csrf_field() }}
			    <h2>Donation of ${{number_format($donation->amount,2)}} made on {{ date('m/d/Y',strtotime($donation->created_at)) }}</h2>
				<p>&nbsp;</p>
				<div class="form-group">
					<label for="event" class="col col-sm-3">Event</label>
					<div class="clearfix visible-xs"></div>
					<div class="col col-sm-8">
						<select name="event" class="form-control">
							<option value="0" selected>Choose...</option>
							@foreach($events as $event)
								<option value="{{$event->id}}"@if(!is_null($donation->event) && $donation->event->id==$event->id) selected @endif>{{$event->title}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="event" class="col col-sm-3">Team</label>
					<div class="clearfix visible-xs"></div>
					<div class="col col-sm-8">
						<select name="team" class="form-control">
							<option value="0" selected>Choose...</option>
							@foreach($teams as $team)
								<option value="{{$team->id}}"@if(!is_null($donation->team) && $donation->team->id==$team->id) selected @endif>{{$team->name}} ({{$team->event->short}})</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="event" class="col col-sm-3">Participant</label>
					<div class="clearfix visible-xs"></div>
					<div class="col col-sm-8">
						<select name="registrant" class="form-control">
							<option value="0" selected>Choose...</option>
							@foreach($registrants as $registrant)
								<option value="{{$registrant->id}}"@if(!is_null($donation->registrant) && $donation->registrant->id==$registrant->id) selected @endif>{{$registrant->lname}}, {{$registrant->fname}} ({{$registrant->event->short}})</option>
							@endforeach
						</select>
					</div>
				</div>
				<p>&nbsp;</p>
		        <div class="form-group">
					<label for="fname" class="col col-sm-3">Name</label>
					<div class="clearfix visible-xs"></div>
					<div class="col col-sm-4 col-xs-6{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="fname" type="text" class="form-control" name="fname" placeholder="First" value="{{old('fname',$donation->fname)}}" required autocomplete="off">
				        @if ($errors->has('fname'))
				            <span class="help-block">
				                <strong>{{ $errors->first('fname') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-4 col-xs-6{{ $errors->has('lname') ? ' has-error' : '' }}">
						<input id="lname" type="text" class="form-control" name="lname" placeholder="Last" value="{{old('lname',$donation->lname)}}" required autocomplete="off">
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
						<input id="email" type="email" class="form-control col col-sm-9" name="email" value="{{old('email',$donation->email)}}" required autocomplete="off">
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
						<input id="phone" type="text" class="form-control col col-sm-8" name="phone" value="{{old('phone',$donation->phone)}}" required autocomplete="off">
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
						<input id="address" type="text" class="form-control col col-sm-8" name="address" value="{{old('address',$donation->address)}}" required autocomplete="off">
				        @if ($errors->has('address'))
				            <span class="help-block">
				                <strong>{{ $errors->first('address') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
		        <div class="form-group">
					<div class="col col-sm-3 col-sm-offset-3 col-xs-5{{ $errors->has('city') ? ' has-error' : '' }}">
						<input id="city" type="text" class="form-control" placeholder="City" name="city" value="{{old('city',$donation->city)}}" required autocomplete="off">
				        @if ($errors->has('city'))
				            <span class="help-block">
				                <strong>{{ $errors->first('city') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-2 col-xs-3 no-padding{{ $errors->has('state') ? ' has-error' : '' }}">
						<input id="state" type="text" class="form-control" placeholder="State" name="state" value="{{old('state',$donation->state)}}" required autocomplete="off">
				        @if ($errors->has('state'))
				            <span class="help-block">
				                <strong>{{ $errors->first('state') }}</strong>
				            </span>
				        @endif
					</div>
					<div class="col col-sm-3 col-xs-4{{ $errors->has('zip') ? ' has-error' : '' }}">
						<input id="zip" type="text" class="form-control" placeholder="Zip" name="zip" value="{{old('zip',$donation->zip)}}" required autocomplete="off">
				        @if ($errors->has('zip'))
				            <span class="help-block">
				                <strong>{{ $errors->first('zip') }}</strong>
				            </span>
				        @endif
					</div>
		        </div>
			    <hr>
			    <div class="form-group">
			        <div class="col-sm-12 rtecenter">
			            <button class="btn btn-primary" type="submit">Save Donation</button>
			        </div>
			    </div>
			</form>
        </div>
    </div>
@stop

@section('footer')
@stop