@extends('layouts.admin')

@section('title')
	Registrant Search
@stop

@section('content')
	<div class="row">
		<div class="col col-sm-4 form-column col-sm-offset-3">
		    <form method="POST" id="search_form" action="{{ route('registrant.search') }}">
		        {{ csrf_field() }}
		
				<p>Use one or more fields to search for registrants.  Partial search are allowed, i.e. "pat" will locate both "pat" and "patterson".</p>
				<div class="red-note search-result"></div>
				    <div class="form-group">
					    <div class="col col-sm-3">
						    <label for="Event">Event</label>
					    </div>
						<div class="col col-sm-9">
							{!!Form::select('event',$events,old('event',array_shift($events)),['class'=>'form-control'])!!}
						</div>
				    </div>
		        <div class="form-group{{ $errors->has('fname') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="fname" type="text" class="form-control" name="fname" value="{{ old('fname') }}" placeholder="First Name">
		
		                @if ($errors->has('fname'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('fname') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('lname') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="lname" type="text" class="form-control" name="lname" value="{{ old('lname') }}" placeholder="Last Name">
		
		                @if ($errors->has('lname'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('lname') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail Address">
		
		                @if ($errors->has('email'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('email') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone">
		
		                @if ($errors->has('phone'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('phone') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		
		        <div class="form-group">
		            <div class="col-sm-12 rtecenter">
		                <button type="submit" class="btn btn-primary">
		                    Search
		                </button>
		            </div>
		        </div>
		    </form>
		</div>
		<div class="col col-sm-8 data-column" style="display:none;">
			<div class="search-results">
			</div>
		</div>
	</div>
@endsection