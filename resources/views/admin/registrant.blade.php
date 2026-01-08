@extends('layouts.admin')

@section('title')
	Registrant Review
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	
	<div class="row-spacing">
		<h1 style="margin-top:0;">{{$event->registrant->fname}} {{$event->registrant->lname}} <a href="{{route('admin.registrant.edit',$event->registrant->slug)}}"><span class="red-note">(edit registration)</span></a><br><span class="smaller">{{$event->title}}</span></h1>
		<div class="row">
			<div class="col col-sm-6">
				<p><strong>Registered On:</strong> {{date("m/d/Y",strtotime($event->registrant->created_at))}}<br>
					<strong>Personal Goal:</strong> ${{$event->personal_goal}}.00<br>
					<strong>Total Raised:</strong> ${{$event->personal_raised}}.00{!!($event->personal_raised>$event->personal_goal)?' <i class="red-text fa fa-thumbs-up"></i>':''!!}</p>
			</div>
			<div class="col col-sm-6">
				@if(isset($event->team))
					@if($event->team->captain->id == $event->registrant->id)
						<p><strong>Team Name:</strong> {{$event->team->name}} (<a onclick="AjaxConfirmDialog('Are you sure you want to remove all members from the team and leave permanently?', 'Delete Team', '{{ route('event.leave.team',[$event->slug,$event->registrant->slug]) }}', '{{ route('registrant.view',[$event->registrant->slug]) }}', '')">Leave Team <i class="fa fa-sign-out"></i></a>)<br>
					@else
						<p><strong>Team Name:</strong> {{$event->team->name}} (<a onclick="AjaxConfirmDialog('Are you sure you want to leave the team?', 'Leave Team', '{{ route('event.leave.team',[$event->slug,$event->registrant->slug]) }}', '{{ route('registrant.view',[$event->registrant->slug]) }}', '')">Leave Team <i class="fa fa-sign-out"></i></a>)<br>
					@endif
						@if($event->team->registrant_id==$event->registrant->id)
							<strong><span class="red-text">Team Captain</span></strong><br>
						@endif
						<strong>Team Members:</strong> {{$event->team->members->count()}}</p>
						<p><strong>Team Goal:</strong> ${{$event->team->goal}}.00<br>
						<strong>Total Raised:</strong> ${{$event->team->eventDonations($event)}}.00{!!($event->team->eventDonations($event)>$event->team->goal)?' <i class="red-text fa fa-thumbs-up"></i>':''!!}</p>
				@else
					{!!Form::open(['route'=>['event.join.team',$event->slug,$event->registrant->slug, 'registrant.view']])!!}
					    <h3 class="gray-bkg padding-8" style="margin-top:0;">Join A Team</h3>
					    <p><strong>Join or start a team.</strong></p>
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
@stop

@section('footer')
	<script>
		(function($) {
			$(document).ready(function() {
				@if(!isset($event->team))
					var teamTimer{{$event->id}};
					var teamElement{{$event->id}} = document.getElementById('newteam{{$event->id}}');
					teamElement{{$event->id}}.addEventListener('keyup',function(event) {
						clearTimeout(teamTimer{{$event->id}});
						if(teamElement{{$event->id}}.value.length>3) {
							teamTimer{{$event->id}} = setTimeout(function() { uniqueTeam('{{$event->slug}}',teamElement{{$event->id}}.value,'newteam{{$event->id}}'); },450);
						}
					});
				@endif
			});
		})(jQuery);
	</script>
@stop