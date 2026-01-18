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
		<h2>{{$event->title}} Sponsors &amp; Vendors</h2>
	@endif
	
	<div class="row">
		<div class="row-same-height row-full-height">
		@foreach($event->sponsors as $strip=>$sponsor)
			<div class="col col-sm-4{{($strip%2==0)?' even':' odd'}} col-sm-height col-full-height col-top">
				<div class="row">
					<div class="col col-sm-2 col-xs-4">
						<p>&nbsp;</p>
			            <a href="#" class="font-26 red-text" onclick="AjaxConfirmDialog('Are you sure you want to permanently delete {{$sponsor->name}} from {{$event->title}}?', 'Delete Sponsor', '{{ route('event.sponsor.delete',[$sponsor->id]) }}', '{{route('event.sponsor',[$event->slug])}}', '')"><i class="fa fa-trash"></i></a>
					</div>
					<div class=" col col-sm-10 col-xs-8">
						<h4>{{$sponsor->name}}</h4>
						@if($sponsor->presenting)
							<div><i class="fa fa-check"></i> Presenting Sponsor</div>
						@endif
						@if($sponsor->vendor)
							<div><i class="fa fa-check"></i> Vendor</div>
						@endif
						<p>{{$sponsor->url}}</p>
						<p><img src="/storage/public/{{$sponsor->filename}}" class="img-responsive" style="width:150px;"></p>
					</div>
				</div>
			</div>
			@if(($strip+1)%3==0 && $strip>0)
				</div>
				<hr>
				<div class="row-same-height row-full-height">
			@endif
		@endforeach
		</div>
	</div>
	<p>&nbsp;</p>
	<hr>
	<p>&nbsp;</p>
	<h3>Add A New Sponsor</h3>
	<form method="POST" action="{{route('event.sponsor.add',[$event->slug])}}" accept-charset="UTF-8" id="sponsor_form" name="sponsor_form" enctype="multipart/form-data" autocomplete="off">
		{{ csrf_field() }}
		<div class="row">
			<div class="col col-sm-6">
		        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="name" type="text" class="form-control" placeholder="Name" name="name" value="{{old('name')}}">
		
		                @if ($errors->has('name'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('name') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
		        <div class="form-group">
			        <div class="col col-sm-12">
				        {{ html()->checkbox('presenting', old('presenting') ? true : false, 1) }} Presenting Sponsor
			        </div>
		        </div>
		        <div class="form-group">
			        <div class="col col-sm-12">
				        {{ html()->checkbox('vendor', old('presenting') ? true : false, 1) }} Vendor
			        </div>
		        </div>
		        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
		                <input id="url" type="text" class="form-control" placeholder="Link/URL" name="url" value="{{old('url')}}">
		
		                @if ($errors->has('url'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('url') }}</strong>
		                    </span>
		                @endif
			        </div>
		        </div>
			</div>
			<div class="col col-sm-6">
		        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
			        <div class="col col-sm-12">
				        <label for="image">Upload Sponsor Image/Logo</label><br>
		                <input id="image" type="file" class="form-control" name="image">
		
		                @if ($errors->has('image'))
		                    <span class="help-block">
		                        <strong>{{ $errors->first('image') }}</strong>
		                    </span>
		                @endif
		                <div class="black-note">Images must fit into 600x600 pixels and be less than 512KB in size.</div>
			        </div>
		        </div>
			</div>
		</div>
	    <div class="form-group">
	        <div class="col-sm-12 rteright">
	            <input type="submit" value="Save Sponsor" class="btn btn-primary">
	        </div>
	    </div>
	</form>
	<p>&nbsp;</p>
	<p><a href="{{route('event.edit',[$event->slug])}}"><i class="fa fa-chevron-circle-left"></i> Back To Editing {{$event->title}}</a></p>
@stop

@section('footer')
@stop