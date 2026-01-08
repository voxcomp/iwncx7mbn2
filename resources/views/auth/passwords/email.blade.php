@extends('layouts.image',['contentAlign'=>'middle','imageClass'=>'image-full', 'image'=>'/images/login-background.jpg'])

@section('title')
	Reset Password
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}

		<p>Please enter your username.  You will receive a link in your e-mail to reset your password.</p>
        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
        </div>

        <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Send Link
                </button>
        </div>
        <div class="form-group">
	        <p><a href="{{route('login')}}"><i class="fa fa-chevron-circle-left"></i> Back to login</a></p>
        </div>
    </form>
@endsection
