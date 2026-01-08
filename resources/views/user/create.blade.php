@extends((\Auth::user()->isAdmin())?'layouts.admin':'layouts.app')

@section('title')
	New User
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="row">	
		<div class="col col-sm-6 col-md-6">
			{!! Form::open(array('route' => 'user.save','files'=>true, 'id'=>'profile_form')) !!}
			    @include('parts.profileform')
		        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="username" type="text" class="form-control" name="username" placeholder="Username">
		
		                @if ($errors->has('username'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('username') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="password" type="password" class="form-control" name="password" placeholder="Password">
		
		                @if ($errors->has('password'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('password') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="password_confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
		
		                @if ($errors->has('password_confirmation'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('password_confirmation') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			    <div class="form-group">
				    <div class="col col-sm-3">
					    <label for="user_type">User Type</label>
				    </div>
					<div class="col col-sm-9">
						{!!Form::select('user_type',['auth'=>'User','admin'=>'Administrator'],old('user_type','auth'),['class'=>'form-control'])!!}
					</div>
			    </div>
				<hr>
			{!! Form::close() !!}
		</div>
		<div class="visible-xs"><hr></div>
		<div class="col col-sm-6 col-md-4 col-md-offset-1">
			@include('parts.profilephoto')
		</div>
	</div>
	<div id="profile-buttons">
	    <div class="form-group">
	        <div class="col-sm-12 rtecenter">
	            <button onclick="document.getElementById('profile_form').submit();" class="btn btn-primary">
	                Create User
	            </button>
	        </div>
	    </div>
	</div>
	<div id="profile-photo-save" style="display:none;">
		<p class="rtecenter"><strong>Please save the cropped profile photo before saving.</strong></p>
	</div>
@stop

@section('footer')
<script>
	(function($) {
		$(document).ready(function() {
			var usernameTimer;
			var usernameElement = document.getElementById('username');
			usernameElement.addEventListener('keyup',function(event) {
				clearTimeout(usernameTimer);
				if(usernameElement.value.length>3) {
					usernameTimer = setTimeout(function() { uniqueUsername(usernameElement.value,'username'); },450);
				}
			});
		});
	})(jQuery);
</script>
@stop
