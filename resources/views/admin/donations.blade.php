@extends('layouts.admin')

@section('title')
	Donation List
@stop

@section('content')
	@if (session()->has('message'))
	    <div class="alert alert-warning">
	        {{ session()->get('message') }}
	    </div>
	@endif

	<table class="table">
		<thead>
			<th>&nbsp;</th>
			<th>Date</th>
			<th>Name</th>
			<th>Amount</th>
			<th>Event</th>
			<th>Team</th>
			<th>Participant</th>
			<th>Cause</th>
		</thead>
		<tbody>
			@foreach($donations as $donation)
				<tr class="@if ($loop->index%2) odd @endif">
					<td><a title="Edit Donation" class="edit" href="{{route('admin.donation.edit',[$donation->id])}}"><i class="fa fa-pencil"></i></a><br><br><a title="Delete Donation" class="delete" onclick="AjaxConfirmDialog('Are you sure you want to delete this donation for <strong>${{ $donation->amount }}</strong> by <strong>{{$donation->fname}} {{$donation->lname}}</strong> on <strong>{{ date('m/d/Y',strtotime($donation->created_at)) }}</strong> permanently?', 'Delete Donation', '{{ route('admin.donation.delete',[$donation->id]) }}', '{{ route('admin.donations') }}', '', false, $(this).parent().parent())"><i class="fa fa-minus-circle"></i></a></td>
					<td>{{ date('m/d/Y',strtotime($donation->created_at)) }}</td>
					<td>{{ $donation->fname.' '.$donation->lname }}<br>{{ $donation->email }}<br>{{ $donation->address }}<br>{{ $donation->city }}, {{ $donation->state }} {{ $donation->zip }}</td>
					<td>${{ number_format($donation->amount,2) }}@if($donation->recurring=='YES')<br>(recurring)@endif</td>
					<td>@if(!is_null($donation->event)){{ $donation->event->title }}@endif</td>
					<td>@if(!is_null($donation->team)){{ $donation->team->name }}@endif</td>
					<td>@if(!is_null($donation->registrant)){{ $donation->registrant->fname }} {{ $donation->registrant->lname }}@endif</td>
					<td>{{$donation->cause}}</td>
				</tr>
			@endforeach
	</table>
	
@stop

@section('footer')
	<script>
		(function($) {
			$(document).ready(function() {
			});
		})(jQuery);
	</script>
@stop