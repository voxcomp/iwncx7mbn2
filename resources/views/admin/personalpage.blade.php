@extends('layouts.admin')

@section('title')
	Personal & Team Event Page Review
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif
	
	@if($registrants->count()>0)
		<h3>PERSONAL PAGES</h3>
		@foreach($registrants as $strip=>$registrant)
			<div class="row listing{{($strip%2==0)?' even':' odd'}}" id="pp{{$registrant->id}}-row">
				<div class="col col-sm-3 col-spacing">
					<p>Registrant: {{$registrant->fname}} {{$registrant->lname}}<br>
					<p>E-mail: <a href="mailto:{{$registrant->email}}?subject=Czar&#039;s%20Promise%20Personal%20Fundraising%20Page%20Content">{{$registrant->email}}</a></p>
					<p><strong>Page Title:</strong> {{$registrant->pagetitle}}</p>
				</div>
				<div class="col col-sm-9">
					{!!htmlspecialchars_decode($registrant->pagecontent)!!}
					<div class="row row-spacing">
						<div class="row-same-height row-full-height">
							<div class="col col-sm-9 col-sm-height col-full-height col-bottom col-spacing">
								<label for="pp{{$registrant->id}}">Notes To User:</label>
								{{ html()->textarea('pp' . $registrant->id, '')->id('pp' . $registrant->id)->class('form-control')->rows('3') }}
							</div>
							<div class="col col-sm-3 col-sm-height col-full-height col-bottom rteright">
								<button class="btn btn-danger btn-block" onclick="AjaxSubmission('{{route('admin.personalpage.fail',[$registrant->slug])}}', '', $('#pp{{$registrant->id}}').val(), false, $('#pp{{$registrant->id}}-row'), false, null)">Fail</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-sm-3 col-sm-offset-9">
							<button class="btn btn-success btn-block" onclick="AjaxSubmission('{{route('admin.personalpage.pass',[$registrant->slug])}}', '', '', false, $('#pp{{$registrant->id}}-row'), false, null)">Pass</button>
						</div>
					</div>
				</div>
			</div>
			<hr>
		@endforeach
	@else
		<h4>There are no personal fundraising pages to review.</h4>
	@endif
	<hr>
	@if($teams->count()>0)
		<h3>TEAM PAGES</h3>
		@foreach($teams as $strip=>$team)
			<div class="row listing{{($strip%2==0)?' even':' odd'}}" id="tp{{$team->slug}}-row">
				<div class="col col-sm-3 col-spacing">
					<p>Captain: {{$team->captain->fname}} {{$team->captain->lname}}<br>
					<p>E-mail: <a href="mailto:{{$team->captain->email}}?subject=Czar&#039;s%20Promise%20Team%20Fundraising%20Page%20Content">{{$team->captain->email}}</a></p>
					<p><strong>Page Title:</strong> {{$team->pagetitle}}</p>
				</div>
				<div class="col col-sm-9">
					{!!htmlspecialchars_decode($team->pagecontent)!!}
					<div class="row row-spacing">
						<div class="row-same-height row-full-height">
							<div class="col col-sm-9 col-sm-height col-full-height col-bottom col-spacing">
								<label for="tp{{$team->slug}}">Notes To User:</label>
								{{ html()->textarea('tp' . $team->slug, '')->id('tp' . $team->slug)->class('form-control')->rows('3') }}
							</div>
							<div class="col col-sm-3 col-sm-height col-full-height col-bottom rteright">
								<button class="btn btn-danger btn-block" onclick="AjaxSubmission('{{route('admin.teampage.fail',[$team->slug])}}', '', $('#tp{{$team->slug}}').val(), false, $('#tp{{$team->slug}}-row'), false, null)">Fail</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-sm-3 col-sm-offset-9">
							<button class="btn btn-success btn-block" onclick="AjaxSubmission('{{route('admin.teampage.pass',[$team->slug])}}', '', '', false, $('#tp{{$team->slug}}-row'), false, null)">Pass</button>
						</div>
					</div>
				</div>
			</div>
			<hr>
		@endforeach
	@else
		<h4>There are no team fundraising pages to review.</h4>
	@endif
@stop
