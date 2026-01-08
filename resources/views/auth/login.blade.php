@extends('layouts.image',['contentAlign'=>'middle','imageClass'=>'image-full', 'image'=>'/images/login-background.jpg'])

@section('title')
	Login
@stop

@section('content')
    @if (session('validationMessage'))
	    <div class="alert alert-success">
	        {{ trans('auth.validationMessage') }}
	    </div>
	@endif
	@if (session('validationWarning'))
	    <div class="alert alert-warning nohide">
	        <p>{{ trans('auth.validationWarning') }}</p>
	        <p><a href="/user/validate">{{ trans('auth.validationLink') }}</a></p>
	    </div>
	@endif
	@if (session('invalidUsername'))
	    <div class="alert alert-warning nohide">
	        {{ trans('auth.invalidUsername') }}
	    </div>
	@endif
    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="text-align:left;">
                <input id="username" type="username" class="form-control" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}" style="text-align:left;">
                <input id="password" type="password" class="form-control" placeholder="Password" name="password" required>

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
        </div>

        <div class="form-group" style="text-align:left;">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12 padding-top-15">
                <p><button type="submit" class="btn btn-primary">
                    Login
                </button></p>

                <p><a href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a></p>
            </div>
        </div>

        <div class="form-group">
	        <p>If you do not have an account, <a href="{{route('register')}}">register now</a></p>
        </div>

    </form>
@endsection
