@extends((\Auth::user()->isAdmin())?'layouts.admin':'layouts.app')

@section('title')
	My Account ({{old('username',$user->username)}})
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="row">	
		<div class="col col-sm-6 col-md-6">
			{{ html()->form('POST', route('user.update'))->acceptsFiles()->id('profile_form')->open() }}
			    {{ method_field('PATCH') }}
			    @if(!\Auth::user()->isAdmin())
			        <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
				        <div class="col col-sm-12">
			                <input id="current_password" type="password" class="form-control" placeholder="Current Password" name="current_password">
			
			                @if ($errors->has('current_password'))
			                    <span class="help-block">
			                        <strong>{{ $errors->first('current_password') }}</strong>
			                    </span>
			                @endif
					        <div class="clearfix red-note">
						        Current password is needed to make any changes
					        </div>
				        </div>
			        </div>
					<hr>
				@else
					<input type="hidden" name="cred" value="{{ old('cred',(isset($existing['cred']))?$existing['cred']:\Crypt::encrypt($user->username)) }}">
				    <div class="form-group">
					    <div class="col col-sm-3">
						    <label for="user_type">User Type</label>
					    </div>
						<div class="col col-sm-9">
							{{ html()->select('user_type', ['auth' => 'User', 'admin' => 'Administrator'], old('user_type', $user->user_type))->class('form-control') }}
						</div>
				    </div>
				@endif
			    @include('parts.profileform')
			    <div class="form-group">
					<div class="col col-sm-8">
						<input id="join" type="checkbox" class="" name="join" value="1" {{(old('join',$user->join)==1)?"checked":''}}> Join our mailing list
						<div class="red-note">Uncheck to remove your e-mail address from our mailing list</div>
					</div>
			    </div>
				<hr>
			    @if(\Auth::user()->isAdmin())
			        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
				        <div class="col col-sm-12">
			                <input id="username" type="text" class="form-control" name="username" placeholder="Username" value="{{old('username',$user->username)}}">
			
			                @if ($errors->has('username'))
			                    <span class="help-block">
			                        <strong>{{ $errors->first('username') }}</strong>
			                    </span>
			                @endif
				        </div>
			        </div>
				@endif
		        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        <div class="clearfix black-note">
					        Needed only if you would like to change your password
				        </div>
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
			{{ html()->form()->close() }}
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
	                Save Profile
	            </button>
	        </div>
	    </div>
	    @if(\Auth::check())
	        <div class="form-group rtecenter">
	            <a class="btn btn-link" onclick="AjaxConfirmDialog('Are you sure you want to permanently delete your account?<br><br>This will remove your profile and history.', 'Delete Account', '{{ route('user.delete',[$user->slug]) }}', '{{ route((\Auth::user()->isAdmin())?'user.search':'front') }}', '')">
	                Delete Account
	            </a>
	        </div>
	    @endif
	</div>
	<div id="profile-photo-save" style="display:none;">
		<p class="rtecenter"><strong>Please save your cropped profile photo before saving your profile.</strong></p>
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

