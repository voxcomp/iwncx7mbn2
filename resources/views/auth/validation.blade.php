@extends('layouts.image',['contentAlign'=>'middle','imageClass'=>'image-full', 'image'=>'/images/login-background.jpg'])

@section('title')
	Resend E-mail Validation
@stop

@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/user/validate/resend') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
	        <div class="col col-sm-12">
                <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required>

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
        </div>
		<p>&nbsp;</p>
        <div class="form-group">
	        <div class="col-xs-6">
		        <p><a href="{{ route('login') }}"><span class="fa fa-lock small-icon"></span> Back To Login</a></p>
	        </div>
            <div class="col-xs-6 rteright">
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </div>
    </form>
@endsection
