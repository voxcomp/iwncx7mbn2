@extends('layouts.admin')

@section('title')
	Event Management
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	@if(isset($event))
		<h2>Editing Event {{$event->title}}</h2>
	@else
		<h2>Create A New Event</h2>
	@endif
	<form method="POST" action="/event/manage/edit{{(isset($event))?'/'.$event->slug:''}}" accept-charset="UTF-8" id="event_form" name="event_form" enctype="multipart/form-data" autocomplete="off">
		{{ csrf_field() }}
		@if(isset($event))
		    {{ method_field('PATCH') }}
		@endif
		@if(isset($event))
		    <p>&nbsp;</p>
		    <h3><a href="{{route('event.sponsor',[$event->slug])}}"><i class="fa fa-gift"></i> Manage Event Sponsors</a></h3>
		    <p>&nbsp;</p>
		@endif
		<div class="row">
			<div class="col col-sm-6">
		        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="title" type="text" class="form-control" placeholder="Title" name="title" value="{{old('title',(isset($event))?$event->title:'')}}">
		
		                @if ($errors->has('title'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('title') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			</div>
			<div class="col col-sm-6">
		        <div class="form-group{{ $errors->has('short') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="short" type="text" class="form-control" placeholder="Short Name" name="short" value="{{old('short',(isset($event))?$event->short:'')}}">
		
		                @if ($errors->has('short'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('short') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			</div>
		</div>
	    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
	        <div class="col col-sm-12">
	            <textarea id="description-ckeditor" class="form-control" placeholder="Description" name="description">{{old('description',(isset($event))?$event->description:'')}}</textarea>
	
	            @if ($errors->has('description'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('description') }}</strong>
	                </span>
	            @endif
	        </div>
	    </div>
        <p>&nbsp;</p>
		<div class="row">
			<div class="col col-sm-4">
		        <div class="form-group{{ $errors->has('event_date') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        <label for="event_date">Event Date</label><br>
		                <input id="event_date" type="text" class="form-control datepicker" placeholder="Choose a Date" name="event_date" value="{{old('event_date',date('m/d/Y',(isset($event))?$event->event_date:time()))}}">
		
		                @if ($errors->has('event_date'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('event_date') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('goal') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        <p>
				        <label for="goal">Fundraising Goal</label>
		                <input id="goal" type="text" name="goal" readonly style="border:0; color:#f6931f; font-weight:bold;">
				        </p>
				        <div id="goal_slider"></div>
		
		                @if ($errors->has('goal'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('goal') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			</div>
			<div class="col col-sm-5 col-sm-offset-2">
		        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        @if(isset($event))
				        	<p><img src="/storage/public/{{$event->image}}" class="img-responsive" style="width:300px;"></p>
				        @endif
				        <label for="image">Upload{{(isset($event))?' New':''}} Event Image</label><br>
		                <input id="image" type="file" class="form-control" name="image">
		
		                @if ($errors->has('image'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('image') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			</div>
		</div>
        @if(isset($event))
        	@foreach($event->costs as $cost)
        		{!!Form::hidden('costs[]',$cost->cost.":".date("m/d/Y",$cost->ends),['class'=>'cost'.$cost->cost])!!}
        	@endforeach
        @endif
	</form>
    <p>&nbsp;</p>
    <h3>Event Registration Costs</h3>
    <div class="{{ $errors->has('cost') ? ' has-error' : '' }}">
        @if ($errors->has('cost'))
            <span class="help-block">
                <strong>{{ $errors->first('cost') }}</strong>
            </span>
        @endif
    </div>
    <p>Add one or more registration costs, each with an expiration date.</p>
	<div class="row">
		<div class="col col-sm-4">
	        <div class="form-group">
		        <div class="col col-sm-12">
			        <p>
			        <label for="cost">Registration Cost</label>
	                <input id="cost" type="text" name="cost" readonly style="border:0; color:#f6931f; font-weight:bold;">
			        </p>
			        <div id="cost_slider"></div>
		        </div>
	        </div>
	        <div class="form-group">
		        <div class="col col-sm-12">
			        <label for="ends">Cost Valid Until</label><br>
	                <input id="ends" type="text" class="form-control datepicker" placeholder="Choose a Date" name="ends">
		        </div>
	        </div>
            <button onclick="addCost();" class="btn btn-danger">Save Cost</button>
		</div>
		<div class="col col-sm-4 col-sm-offset-2">
		    <div class="costs">
		        @if(isset($event))
		        	@foreach($event->costs->sortBy('cost') as $cost)
		        		<div class="cost{{$cost->cost}}"><a onclick="deleteCost('cost{{$cost->cost}}')" class="red-text"><i class="fa fa-trash"></i></a> <strong>${{$cost->cost}}.00</strong> ends on <strong>{{date("m/d/Y",$cost->ends)}}</strong></div>
		        	@endforeach
		        @endif
		    </div>
		</div>
	</div>
    <hr>
    <div class="form-group">
        <div class="col-sm-12 rtecenter">
            <button onclick="document.event_form.submit();" class="btn btn-primary">Save Event</button>
        </div>
    </div>
    @if(isset($event))
        <div class="form-group rtecenter">
            <a class="btn btn-link" onclick="AjaxConfirmDialog('Are you sure you want to permanently delete {{$event->title}}?<br><br>This will delete all donation, sponsor and participant history.', 'Delete Event', '{{ route('event.delete',[$event->slug]) }}', '{{ route('home') }}', '')">
                Delete Event
            </a>
        </div>
    @endif
@stop

@section('footer')
<script src="/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'description-ckeditor' );
    
	  ( function($) {
		window.deleteCost = function(id) {
			window.event.cancel
			$('.'+id).remove();
		}
		
		window.addCost = function() {
			var costs = document.forms.event_form.elements['costs[]'];
			console.log(costs);
			var cost = $('#cost').val().replace('$','');
			if(costs!==undefined) {
				for(var i = 0; i < costs.length; i++) {
					if(costs[i].className=='cost'+cost) {
						AlertDialog('A cost of $'+cost+' has already been entered.', 'Cost Exists');
						return;
					}
				}
			}
			
			//var ends = new Date($('#ends').val()+' 23:59').getTime() / 1000;
			if($('#ends').val()!='') {
				$('.costs').append('<div class="cost'+cost+'"><a onclick="deleteCost(\'cost'+cost+'\')" class="red-text"><i class="fa fa-trash"></i></a> <strong>$'+cost+'.00</strong> ends on <strong>'+$('#ends').val()+'</strong></div>');
				$('#event_form').append('<input type="hidden" name="costs[]" value="'+cost+":"+$('#ends').val()+'" class="cost'+cost+'">');
			}
		}
		
		  
	    $( "#goal_slider" ).slider({
	      value:{{str_replace('$','',old('goal',(isset($event))?$event->goal:'10000'))}},
	      min: 0,
	      max: 250000,
	      step: 10000,
	      slide: function( event, ui ) {
	        $( "#goal" ).val( "$" + ui.value );
	      }
	    });
	    $( "#goal" ).val( "$" + $( "#goal_slider" ).slider( "value" ) );

	    $( "#cost_slider" ).slider({
	      value:35,
	      min: 0,
	      max: 150,
	      step: 1,
	      slide: function( event, ui ) {
	        $( "#cost" ).val( "$" + ui.value );
	      }
	    });
	    $( "#cost" ).val( "$" + $( "#cost_slider" ).slider( "value" ) );
	  } )(jQuery);
</script>
@stop