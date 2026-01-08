        <input type="hidden" name="photo" id="photo" value="{{ old('photo',(isset($user['photo']))?$user['photo']:'') }}">
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
	    <hr>
		@include('parts.addressform')