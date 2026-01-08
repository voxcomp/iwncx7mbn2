@extends('layouts.admin')

@section('title')
	Reports
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif

	{!!Form::open(['route'=>'get.generalreport'])!!}
		<h1>General Reports</h1>
		<hr>
		<h3>Date Range:</h3>
		<div class="row">
			<div class="col col-sm-3 col-spacing">
				<a href="#" class="btn btn-danger btn-option show-all active">Show All</a>
				<input type="hidden" name="all" value="1">
			</div>
			<div class="col col-sm-1 col-spacing rtecenter"><br><strong>- OR -</strong><br></div>
			<div class="col col-sm-4 col-spacing">
		        <div class="{{ $errors->has('start_date') ? ' has-error' : '' }}">
				        <label for="start_date">Start Date</label><br>
		                <input id="start_date" type="text" class="form-control datepicker disable-show-all" placeholder="Start Date" name="start_date" value="{{old('start_date')}}">
		
		                @if ($errors->has('start_date'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('start_date') }}</strong>
		                    </span>
		                @endif
		        </div>
			</div>
			<div class="col col-sm-4 col-spacing">
		        <div class="{{ $errors->has('end_date') ? ' has-error' : '' }}">
				        <label for="end_date">End Date</label><br>
		                <input id="end_date" type="text" class="form-control datepicker disable-show-all" placeholder="End Date" name="end_date" value="{{old('end_date')}}">
		
		                @if ($errors->has('end_date'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('end_date') }}</strong>
		                    </span>
		                @endif
		        </div>
			</div>
		</div>
		<h3>Choose Report Type:</h3>
		<div class="row">
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="donations">Donors</a></div>
			<input type="hidden" name="report" value="" required>
		</div>
		<p>&nbsp;</p>
	    <div class="form-group">
	        <div class="col-sm-12 rtecenter">
	            <input type="submit" value="Download Report" class="btn btn-primary">
	        </div>
	    </div>
	{!!Form::close()!!}
		<p>&nbsp;</p>
	{!!Form::open(['route'=>'reports'])!!}
		<h1>Event Specific Reports</h1>
		<hr>
		<h3>Choose Event:</h3>
		<div class="row">
			@foreach($events as $key=>$event)
			    <div class="col col-xs-6 col-sm-4 col-spacing">
				    <a href="#" class="btn btn-danger btn-block btn-option event" data-event="{{$event->id}}">
					    <img src="/storage/public/{{$event->image}}" class="img-responsive"><br>
					    {{$event->short}}
					</a>
				</div>
			@endforeach
		</div>
		<input type="hidden" name="event" value="" required>
		<div class="{{ $errors->has('event') ? ' has-error' : '' }}">
		    @if ($errors->has('event'))
		        <span class="help-block">
		            <strong>{{ $errors->first('event') }}</strong>
		        </span>
		    @endif
		</div>
		<p>&nbsp;</p>
		<h3>Choose Report Type:</h3>
		<div class="row">
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="sponsors">Sponsors</a></div>
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="registrants">Participants</a></div>
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="teams">Teams</a></div>
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="donations">Donors</a></div>
		    <div class="col col-xs-6 col-sm-4 col-spacing"><a href="#" class="btn btn-danger btn-block btn-option report-type" data-report="progress">Progress Report</a></div>
			<input type="hidden" name="report" value="" required>
		</div>
		<p>&nbsp;</p>
	    <div class="form-group">
	        <div class="col-sm-12 rtecenter">
	            <input type="submit" value="Download Report" class="btn btn-primary">
	        </div>
	    </div>
	{!!Form::close()!!}
@stop

@section('footer')
	<script>
		(function($) {
			$(document).ready(function() {
				$('.show-all').click(function() {
					$('input[name=all]').val(!$('input[name=all]').val());
				});
				$('.disable-show-all').change(function() {
					$('.show-all').removeClass('active');
					$('input[name=all]').val(0);
				});
				$('.report-type').click(function() {
					$('input[name=report]').val(($(this).hasClass('active'))?$(this).data('report'):'');
				});
				$('.event').click(function() {
					$('input[name=event]').val(($(this).hasClass('active'))?$(this).data('event'):'');
				});
			});
		})(jQuery);
	</script>
@stop