@extends('layouts.app')

@section('title')
	{{$event->title}}<span class="hidden-xs"> - </span><span class="visible-xs"></span><span class="smaller">Sign Up</span>
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	<div class="medium-content-area">
		{{ html()->form('POST', route('event.register.step2', $event))->id('event_register_form')->open() }}
		    {{ method_field('PATCH') }}
		    @if(!\Auth::check())
			    <h3 class="gray-bkg padding-8">User Account</h3>
			    <p>Create an account to manage your event registration and fundraising pages, create or join a team, and easily register for future events.</p>
			    <div class="small-content-area">
			        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
				        <div class="col col-sm-12">
			                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">
			
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
			        <div class="form-group">
				        <div class="col col-sm-12">
			                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
				        </div>
			        </div>
			    </div>
			    <p>&nbsp;</p>
			@endif
		    <h3 class="gray-bkg padding-8">Join A Team</h3>
		    <p><strong>Optional:</strong> Join or start a team.  If you do not see your team name, you can join after registration.</p>
		    <div class="form-group">
				<div class="col col-sm-6">
		            <label for="team">Join a Team</label>
		            {{ html()->select('team', ['0' => 'Choose a team'] + $event->teams->pluck('name', 'id')->toArray(), old('team'))->class('form-control') }}
				</div>
				<div class="col col-sm-6">
		            <label for="newteam">Create a new team</label><br>
		            {{ html()->text('newteam', old('newteam'))->id('newteam')->class('form-control')->attribute('onkeyup', 'teamPageDetails(this.value)') }}
				</div>
		    </div>
		    <div class="row-spacing" id="teamPageDetails" style="display:none;">
			    <p>Every team receives a page to use for their fundraising efforts.  Each page must be approved by an administrator before it will be available unless you have previously had a page approved.</p>
			    <div class="form-group">
					<div class="col col-sm-6">
			            <label for="pagetitle">Team Page Title</label>
			            {{ html()->text('teampagetitle', old('teampagetitle'))->class('form-control') }}
					</div>
			    </div>
			    <div class="form-group">
					<div class="col col-sm-12">
			            <label for="pagecontent">Team Page Content</label>
			            {{ html()->textarea('teampagecontent', old('teampagecontent'))->class('form-control')->id('teampagecontent_ckeditor') }}
			            <div class="black-note">To upload an image, simply drag and drop it onto the editor.  Double-click the image to change its properties (i.e. alignment).</div>
					</div>
			    </div>
		        <div class="form-group{{ $errors->has('teampagegoal') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        <p>
				        <label for="teampagegoal">Team Fundraising Goal</label>
		                <input id="teampagegoal" type="text" name="teampagegoal" readonly style="border:0; color:#f6931f; font-weight:bold;">
				        </p>
				        <div id="teampagegoal_slider"></div>
		
		                @if ($errors->has('teampagegoal'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('teampagegoal') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		    </div>
		    <p>&nbsp;</p>
		    <h3 class="gray-bkg padding-8">Personal Fundraising Page</h3>
		    <p>Every participant receives a personal page to use for their fundraising efforts.  Each page must be approved by an administrator before it will be available unless you have previously had a page approved.</p>
		    <div class="form-group">
				<div class="col col-sm-6">
		            <label for="pagetitle">Page Title</label>
		            {{ html()->text('pagetitle', old('pagetitle'))->class('form-control') }}
				</div>
		    </div>
		    <div class="form-group">
				<div class="col col-sm-12">
		            <label for="pagecontent">Page Content</label>
		            {{ html()->textarea('pagecontent', old('pagecontent'))->class('form-control')->id('pagecontent_ckeditor') }}
		            <div class="black-note">To upload an image, simply drag and drop it onto the editor.  Double-click the image to change its properties (i.e. alignment).</div>
				</div>
		    </div>
	        <div class="form-group{{ $errors->has('pagegoal') ? ' has-error' : '' }}">
		        <div class="col col-sm-12">
			        <p>
			        <label for="pagegoal">Personal Fundraising Goal</label>
	                <input id="pagegoal" type="text" name="pagegoal" readonly style="border:0; color:#f6931f; font-weight:bold;">
			        </p>
			        <div id="pagegoal_slider"></div>
	
	                @if ($errors->has('pagegoal'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('pagegoal') }}</strong>
	                    </span>
	                @endif
		        </div>
	        </div>
		    <p>&nbsp;</p>
		    <div class="form-group">
		        <div class="col-sm-12 rtecenter">
			        @if($donation==0 && $registranttype!='adult')
			            <input type="submit" class="btn btn-primary" value="Submit Registration">
			        @else
			            <input type="submit" class="btn btn-primary" value="Continue to Payment">
			        @endif
		        </div>
		    </div>
		{{ html()->form()->close() }}
	</div>
@stop

@section('rightsidebar')
	<div class="block">
		<div><img class="img-full" src="/storage/public/{{$event->image}}" alt="{{$event->short}}"></div>
		<h2 class="rtecenter">{{$event->title}}</h2>
		<h4 class="rtecenter">{{date("l",$event->event_date)}}<br>{{date("F d, Y",$event->event_date)}}</h4>
	</div>
	<div class="block">
		<h3>Fundraising Goal: ${{$event->goal}}</h3>
		<div class="event-goal">
			<div class="event-goal-mask">
				<img src="/images/goal-mask.png" class="img-full">
			</div>
			<div class="event-goal-raised" style="height:{{$event->percent()}}%"></div>
		</div>
		<div class="rtecenter padding-top-15">Raised to date: ${{number_format($event->raised(),2)}}</div>
	</div>
@stop

@section('footer')
<script src="/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'pagecontent_ckeditor', { customConfig:'config-basic.js' }  );
    CKEDITOR.replace( 'teampagecontent_ckeditor', { customConfig:'config-basic.js' }  );
	(function($) {
		window.teamPageDetails = function(v) {
			if(v.length>0) {
				$("#teamPageDetails").slideDown(300);
			} else {
				$("#teamPageDetails").slideUp(300);
				$("input[name=teampagetitle]").val('');
				CKEDITOR.instances['teampagecontent_ckeditor'].updateElement();
				CKEDITOR.instances['teampagecontent_ckeditor'].setData('');
			}
		}
	    $( "#pagegoal_slider" ).slider({
	      value:{{str_replace('$','',old('pagegoal',1000))}},
	      min: 0,
	      max: {{round($event->goal/4)}},
	      step: 25,
	      slide: function( event, ui ) {
	        $( "#pagegoal" ).val( "$" + ui.value );
	      }
	    });
	    $( "#pagegoal" ).val( "$" + $( "#pagegoal_slider" ).slider( "value" ) );

	    $( "#teampagegoal_slider" ).slider({
	      value:{{str_replace('$','',old('teampagegoal',1000))}},
	      min: 0,
	      max: {{round($event->goal/3)}},
	      step: 50,
	      slide: function( event, ui ) {
	        $( "#teampagegoal" ).val( "$" + ui.value );
	      }
	    });
	    $( "#teampagegoal" ).val( "$" + $( "#teampagegoal_slider" ).slider( "value" ) );

		$(document).ready(function() {
			var usernameTimer;
			var usernameElement = document.getElementById('username');
			usernameElement.addEventListener('keyup',function(event) {
				clearTimeout(usernameTimer);
				if(usernameElement.value.length>3) {
					usernameTimer = setTimeout(function() { uniqueUsername(usernameElement.value,'username'); },450);
				}
			});

			var teamTimer;
			var teamElement = document.getElementById('newteam');
			teamElement.addEventListener('keyup',function(event) {
				clearTimeout(teamTimer);
				if(teamElement.value.length>3) {
					teamTimer = setTimeout(function() { uniqueTeam('{{$event->slug}}',teamElement.value,'newteam'); },450);
				}
			});
		});
	})(jQuery);
</script>
@stop