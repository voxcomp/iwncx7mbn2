@extends('layouts.image',['contentAlign'=>'top','imageClass'=>'', 'image'=>'/images/registration-background.jpg'])

@section('title')
	Register
@stop

@section('content')
    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('fname') ? ' has-error' : '' }}">
                <input id="fname" type="text" class="form-control" name="fname" value="{{ old('fname') }}" placeholder="First Name" required autofocus>

                @if ($errors->has('fname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fname') }}</strong>
                    </span>
                @endif
        </div>
        <div class="form-group{{ $errors->has('lname') ? ' has-error' : '' }}">
                <input id="lname" type="text" class="form-control" name="lname" value="{{ old('lname') }}" placeholder="Last Name" required autofocus>

                @if ($errors->has('lname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lname') }}</strong>
                    </span>
                @endif
        </div>
		<hr>
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
        </div>
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required>

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="black-note">If you have previously signed up for events with this e-mail address, your registrations will be linked to your user account.</div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
        </div>

        <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </div>
        <div class="form-group">
	        <p><a href="{{route('login')}}"><i class="fa fa-chevron-circle-left"></i> Back to login</a></p>
        </div>
    </form>
@endsection

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