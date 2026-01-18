@extends((\Auth::user()->isAdmin())?'layouts.admin':'layouts.app')

@section('head')
	@if(!\Auth::user()->isAdmin())
		<script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/decoupled-document/ckeditor.js"></script>
	@endif
@endsection

@section('title')
	My Account
@stop

@section('content')
	@if (session('validationMessage'))
	    <div class="alert alert-success">
	        {{ trans('auth.validationMessage') }}
	    </div>
	@endif
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	
	@if(\Auth::user()->isAdmin())
		@include('parts.homeAdmin')
	@else
		@include('parts.homeUser',['events'=>$events,'myevents'=>$myevents])
	@endif
@stop

@section('footer')
	@if(!\Auth::user()->isAdmin())
		<script>
			var sections = [
				@foreach($myevents as $key=>$event)
					'page{{$event->id}}','teampage{{$event->id}}',
				@endforeach
			];
			sections.forEach(function(value) {
				DecoupledEditor
					.create( document.querySelector( '#'+value+'_content' ) )
					.then( editor => {
						const toolbarContainer = document.querySelector( '#'+value+'_toolbar' );
			
						toolbarContainer.appendChild( editor.ui.view.toolbar.element );
					} )
					.catch( error => {
						console.error( error );
					} );
			});
			(function($) {
				$(document).ready(function() {
					@foreach($myevents as $event)
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
					@endforeach
				});
			})(jQuery);
			@foreach($myevents as $event)
			    $( "#pagegoal_slider{{$event->id}}" ).slider({
			      value:{{str_replace('$','',old('pagegoal'.$event->id,$event->registrant->goal))}},
			      min: 0,
			      max: {{round($event->goal/4)}},
			      step: 25,
			      slide: function( event, ui ) {
			        $( "#pagegoal{{$event->id}}" ).val( "$" + ui.value );
			      }
			    });
				$( "#pagegoal{{$event->id}}" ).val( "$" + $( "#pagegoal_slider{{$event->id}}" ).slider( "value" ) );

				@if(isset($event->team))
				    $( "#teampagegoal{{$event->id}}_slider" ).slider({
				      value:{{str_replace('$','',old('teampagegoal'.$event->id,$event->team->goal))}},
				      min: 0,
				      max: {{round($event->goal/3)}},
				      step: 50,
				      slide: function( event, ui ) {
				        $( "#teampagegoal{{$event->id}}" ).val( "$" + ui.value );
				      }
				    });
					$( "#teampagegoal{{$event->id}}" ).val( "$" + $( "#teampagegoal{{$event->id}}_slider" ).slider( "value" ) );
				@endif
			@endforeach
		</script>
	@endif
@stop