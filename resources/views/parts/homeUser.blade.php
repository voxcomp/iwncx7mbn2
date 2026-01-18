@if($myevents->count()>0)
	<div class="row">
		<div class="row-same-height row-full-height">
			<div class="col col-sm-{{($events->count()>0)?9:12}} col-sm-height col-full-height col-top">
				<h2>My Events</h2>
				@foreach($myevents as $key=>$event)
					<div class="row-spacing">
<!-- 						<p><img src="/storage/public/{{$event->image}}" class="img-responsive"> -->
						<h1 style="margin-top:0;"><a href="{{route('event.view',[$event->slug])}}">{{$event->title}}</a><br><span class="smaller">{{date("l, F d, Y",$event->event_date)}}</span></h1>
						@if($event->event_date > time())
						<div class="drop-link">Fundraising Page Details</div>
						<div class="drop-area">
							@if((isset($event->team) && $event->team->registrant_id==$event->registrant->id))
							    <div class="row-spacing" id="teamPageDetails{{$event->id}}" style="{{(isset($event->team) && $event->team->registrant_id==$event->registrant->id)?'':'display:none;'}}">
									<h4>Team Fundraising Page</h4>
									@if(isset($event->team) && !empty($event->team->pagetitle))
										@if($event->team->moderated && $event->team->reviewed)
											<p><strong>Your team fundraising page URL is:</strong><br><a href="{{$event->team->page}}">{{$event->team->page}}</a></p>
											<p><strong>Sharable URL:</strong> {{$event->team->page_short}}
										@elseif(!$event->team->moderated)
											<p><strong>Your team page is still under review and will be live soon.</strong></p>
										@elseif(!$event->team->reviewed)
											<p><strong>Your team page has been reviewed but was not approved.  Please make any necessary changes.</strong></p>
											@if(!empty($event->team->adminnotes))
												<p><strong>The following administrative notes were made:</strong><br>{{$event->team->adminnotes}}</p>
											@endif
										@endif
									@endif
									@if(!empty($event->team->pagetitle))
										<p>Edit your team fundraising page details below:</p>
									@else
									    <p>Every team receives a page to use for their fundraising efforts.  Each page must be approved by an administrator before it will be available unless you have previously had a page approved.</p>
									@endif
									{!!Form::open(['route'=>['event.team.page',$event->team->slug]])!!}
									    <div class="form-group">
											<div class="col col-sm-6">
									            <label for="teampagetitle{{$event->id}}">Team Page Title</label>
									            {!!Form::text('teampagetitle'.$event->id,old('teampagetitle'.$event->id,(isset($event->team))?$event->team->pagetitle:''),['class'=>'form-control'])!!}
											</div>
									    </div>
									    <div class="form-group">
											<div class="col col-sm-12">
												{!! Form::hidden('teampagecontent'.$event->id, (isset($event->team))?$event->team->pagecontent:'')!!}
												<div id="teampage{{$event->id}}_toolbar"></div>
												<div id="teampage{{$event->id}}_content">
													{!! (isset($event->team))?$event->team->pagecontent:'' !!}
												</div>
									            {{--<label for="teampagecontent{{$event->id}}">Team Page Content</label>
									            {!!Form::textarea('teampagecontent'.$event->id,old('teampagecontent'.$event->id,(isset($event->team))?$event->team->pagecontent:''),['class'=>'form-control','id'=>'teampagecontent'.$event->id.'_ckeditor'])!!}
									            <div class="black-note">To upload an image, simply drag and drop it onto the editor.  Double-click the image to change its properties (i.e. alignment).</div>--}}
											</div>
									    </div>
								        <div class="form-group{{ $errors->has('teampagegoal'.$event->id) ? ' has-error' : '' }}">
									        <div class="col col-sm-12">
										        <p>
										        <label for="teampagegoal{{$event->team->id}}">Team Fundraising Goal</label>
								                <input id="teampagegoal{{$event->id}}" type="text" name="teampagegoal{{$event->id}}" readonly style="border:0; color:#f6931f; font-weight:bold;">
										        </p>
										        <div id="teampagegoal{{$event->id}}_slider"></div>
								
								                @if ($errors->has('teampagegoal'.$event->id))
								                    <span class="help-block">
								                        <strong>{{ $errors->first('teampagegoal'.$event->id) }}</strong>
								                    </span>
								                @endif
									        </div>
								        </div>
									    <div class="form-group">
										    <div class="col col-sm-12 rteright">
											    {!!Form::submit('Save Team Page',['class'=>'btn btn-primary'])!!}
										    </div>
									    </div>
								    {!!Form::close()!!}
							    </div>
							@endif
							<hr>
							<h4>Personal Fundraising Page</h4>
							@if(!empty($event->registrant->pagetitle))
								@if($event->registrant->moderated && $event->registrant->reviewed)
									<p><strong>Your personal fundraising page URL is:</strong><br><a href="{{$event->personal_page}}">{{$event->personal_page}}</a></p>
									<p><strong>Sharable URL:</strong> {{$event->personal_page_short}}
								@elseif(!$event->registrant->moderated)
									<p><strong>Your personal page is still under review and will be live soon.</strong></p>
								@elseif(!$event->registrant->reviewed)
									<p><strong>Your personal page has been reviewed but was not approved.  Please make any necessary changes.</strong></p>
									@if(!empty($event->registrant->adminnotes))
										<p><strong>The following administrative notes were made:</strong><br>{{$event->registrant->adminnotes}}</p>
									@endif
								@endif
							@endif
							@if(!empty($event->registrant->pagetitle))
								<p>Edit your personal fundraising page details below:</p>
							@else
								<p>Every participant receives a personal fundraising page to use for promoting to your friends and family in collecting donations for participating. Once submitted, your page will be reviewed by an administrator and then approved for going live.</p>
							@endif

							{!!Form::open(['route'=>['event.personal.page',$event->registrant->slug]])!!}
							    <div class="form-group">
									<div class="col col-sm-6">
							            <label for="pagetitle{{$event->id}}">Page Title</label>
							            {!!Form::text('pagetitle'.$event->id,old('pagetitle'.$event->id,$event->registrant->pagetitle),['class'=>'form-control'])!!}
									</div>
							    </div>
							    <div class="form-group">
									<div class="col col-sm-12">
										{!!Form::hidden('pagecontent'.$event->id, htmlspecialchars_decode($event->registrant->pagecontent))!!}
										<div id="page{{$event->id}}_toolbar"></div>
										<div id="page{{$event->id}}_content">
											{!! htmlspecialchars_decode($event->registrant->pagecontent) !!}
										</div>
							            {{--<label for="pagecontent{{$event->id}}">Page Content</label>
							            {!!Form::textarea('pagecontent'.$event->id,old('pagecontent'.$event->id,htmlspecialchars_decode($event->registrant->pagecontent)),['class'=>'form-control','id'=>'pagecontent'.$event->id.'_ckeditor'])!!}
							            <div class="black-note">To upload an image, simply drag and drop it onto the editor.  Double-click the image to change its properties (i.e. alignment).</div>--}}
									</div>
							    </div>
						        <div class="form-group{{ $errors->has('pagegoal'.$event->id) ? ' has-error' : '' }}">
							        <div class="col col-sm-12">
								        <p>
								        <label for="pagegoal{{$event->id}}">Personal Fundraising Goal</label>
						                <input id="pagegoal{{$event->id}}" type="text" name="pagegoal{{$event->id}}" readonly style="border:0; color:#f6931f; font-weight:bold;">
								        </p>
								        <div id="pagegoal_slider{{$event->id}}"></div>
						
						                @if ($errors->has('pagegoal'.$event->id))
						                    <span class="help-block">
						                        <strong>{{ $errors->first('pagegoal'.$event->id) }}</strong>
						                    </span>
						                @endif
							        </div>
						        </div>
							    <div class="form-group">
								    <div class="col col-sm-12 rteright">
									    {!!Form::submit('Save Personal Page',['class'=>'btn btn-primary'])!!}
								    </div>
							    </div>
							{!!Form::close()!!}
						</div>
						@endif
						<div class="row">
							<div class="col col-sm-6">
								<p><strong>Personal Goal:</strong> ${{$event->personal_goal}}.00<br>
									<strong>Total Raised:</strong> ${{$event->personal_raised}}.00{!!($event->personal_raised>$event->personal_goal)?' <i class="red-text fa fa-thumbs-up"></i>':''!!}</p>
							</div>
							<div class="col col-sm-6">
								@if(isset($event->team))
									<p><strong>Team Name:</strong> {{$event->team->name}} @if($event->event_date > time())(<a onclick="AjaxConfirmDialog('Are you sure you want to leave {{$event->team->name}}?.', 'Delete Team', '{{ route('event.leave.team',[$event->slug,$event->registrant->slug]) }}', '{{ route('home') }}', '')">Leave Team <i class="fa fa-sign-out"></i></a>)@endif<br>
										@if($event->team->registrant_id==$event->registrant->id)
											<strong><span class="red-text">You are team captain</span></strong><br>
										@endif
										<strong>Team Members:</strong> {{$event->team->members->count()}}</p>
										<p><strong>Team Goal:</strong> ${{$event->team->goal}}.00<br>
										<strong>Total Raised:</strong> ${{$event->team->eventDonations($event)}}.00{!!($event->team->eventDonations($event)>$event->team->goal)?' <i class="red-text fa fa-thumbs-up"></i>':''!!}</p>
									@if(empty($event->team->pagetitle) && $event->team->registrant_id==$event->registrant->id)
										<p>&nbsp;</p>
										@if($event->event_date > time() && 0)
										<p>To further your fundraising efforts, please set up your team fundraising page in the section below.</p>
										@endif
									@endif
								@elseif($event->event_date > time())
									{!!Form::open(['route'=>['event.join.team',$event->slug,$event->registrant->slug]])!!}
									    <h3 class="gray-bkg padding-8" style="margin-top:0;">Join A Team</h3>
									    <p><strong>Optional:</strong> Join or start a team.  If you do not see your team name, you can join after registration.</p>
									    <div class="form-group">
											@if($event->teams->count() > 0)
												<div class="col col-sm-6">
										            <label for="team{{$event->id}}">Join a Team</label>
										            {!!Form::select('team'.$event->id,['0'=>'Choose a team']+$event->teams->pluck('name','id')->toArray(),old('team'.$event->id),['class'=>'form-control'])!!}
												</div>
											@endif
											<div class="col col-sm-6">
									            <label for="newteam{{$event->id}}">Create a new team</label><br>
									            {!!Form::text('newteam'.$event->id,old('newteam'.$event->id),['id'=>'newteam'.$event->id,'class'=>'form-control', 'onkeyup'=>'teamPageDetails(this.value)'])!!}
									            <div class="black-note">After creating your team, you will be able to set up your team fundraising page in the section below.</div>
											</div>
									    </div>
									    <div class="form-group">
										    <div class="col col-sm-12 rteright">
											    {!!Form::submit('Join Team',['class'=>'btn btn-primary'])!!}
										    </div>
									    </div>
									{!!Form::close()!!}
								@endif
							</div>
						</div>
					</div>
					@if($myevents->count()>1)
						<hr>
					@endif
				@endforeach
			</div>
			@if($events->count()>0)
				<div class="col col-sm-3 col-sm-height col-full-height col-top padding-bottom-25 gray-bkg">
					<h2>Upcoming Events</h2>
					@foreach($events as $event)
						<p><img src="/storage/public/{{$event->image}}" class="img-full"></p>
						<h4 class="rtecenter"><a href="{{route('event.view',[$event->slug])}}">{{$event->title}}</a></h4>
						<h5 class="rtecenter">{{date("l",$event->event_date)}}<br>{{date("F d, Y",$event->event_date)}}</h5>
						<p class="rtecenter"><a href="{{route('event.register',[$event->slug])}}" class="btn btn-primary">Sign Up Today!</a></p>
						@if($events->count()>1)
							<hr>
						@endif
					@endforeach
				</div>
			@endif
		</div>
	</div>
@else
	<script>
		window.location = "{{config('app.url')}}";
	</script>
	@if($events->count()>0)
		<div class="row-spacing">
			<h2>Upcoming Events</h2>
			@foreach($events as $event)
				<p><img src="/storage/public/{{$event->image}}" class="img-full"></p>
				<h3><a href="{{route('event.view',[$event->slug])}}">{{$event->title}}</a></h3>
				<h4>{{date("l, F d, Y", $event->event_date)}}</h4>
				{!!htmlspecialchars_decode($event->description)!!}
				<p class="rtecenter"><a href="{{route('event.register',[$event->slug])}}" class="btn btn-primary">Sign Up Today!</a></p>
				@if($events->count()>1)
					<hr>
				@endif
			@endforeach
		</div>
	@endif
@endif