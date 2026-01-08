@extends('layouts.admin')

@section('title')
	Discount Codes
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
			<th>Code</th>
			<th>Amount</th>
			<th>Max Uses</th>
			<th>Valid From</th>
			<th>Valid To</th>
			<th>Last Used</th>
			<th>Uses</th>
		</thead>
		<tbody>
			@foreach ($coupons as $coupon)
				<tr class="@if(!$coupon->active) disabled-text @endif @if ($loop->index%2) odd @endif @if (isset($existing['id']) && $existing['id']==$coupon->id) selected @endif" >
					<td><a title="Delete Coupon Code" class="delete" onclick="AjaxConfirmDialog('Are you sure you want to delete <strong>{{ $coupon->name }}</strong> permanently?', 'Delete Discount Code', '{{ route('coupons.delete',[$coupon->id]) }}', '{{ route('coupons') }}', '', false, $(this).parent().parent())"><i class="fa fa-minus-circle"></i></a></td>
					<td><a href="{{ route('coupons.edit',[$coupon->id]) }}">{{ $coupon->name }}</a></td>
					<td>@if($coupon->discount_type=="dollar")<i class="fa fa-usd"></i>@endif{{ $coupon->amount }}@if($coupon->discount_type=="percent")<i class="fa fa-percent"></i>@endif</td>
					<td>{{ $coupon->maxuse }}</td>
					<td>{{ date('m/d/Y',$coupon->valid_from) }}</td>
					<td>{{ date('m/d/Y',$coupon->valid_to) }}</td>
					<td>@if($coupon->last_used!=0){{ date('m/d/Y',$coupon->last_used) }}@endif</td>
					<td>{{ $coupon->used }}</td>
				</tr>
			@endforeach
	</table>
	
	<hr>
    @if (isset($existing))
    	<h4 class="scroll-to">Modify Discount Code</h4>
	    <form id="coupons_form" name="coupons_form" role="form" method="POST" action="{{ route('coupons.save') }}">
		    {{ method_field('PATCH') }}
			<input type="hidden" name="id" value="{{ old('id',(isset($existing['id']))?$existing['id']:0) }}">
	@else
    	<h4>Add A New Coupon</h4>
	    <form id="coupons_form" name="coupons_form" role="form" method="POST" action="{{ route('coupons.create') }}">
	@endif
        {{ csrf_field() }}

	    <div class="form-group">
	            <div class="col col-sm-6{{ $errors->has('active') ? ' has-error' : '' }}">
	                <div>Coupon Status</div>
	                <label for="active-1" class="control-label">Active</label>
	                <input id="active-1" class="radios" type="radio" class="form-control" name="active" value="1" @if (old('active',(isset($existing['active']))?$existing['active']:'')=='1') checked="checked" @endif>&nbsp;
	                <label for="active-2" class="control-label">Disabled</label>
	                <input id="active-2" class="radios" type="radio" class="form-control" name="active" value="0" @if (old('active',(isset($existing['active']))?$existing['active']:'')=='0') checked="checked" @endif>
	                @if ($errors->has('active'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('active') }}</strong>
	                    </span>
	                @endif
	            </div>
	    </div>
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
		        <div class="col col-sm-6">
		            <label for="name" class="control-label">Coupon Name</label><br>
	                <input id="name" type="text" class="form-control" name="name" value="{{ old('name',(isset($existing['name']))?$existing['name']:'') }}" required>
	
	                @if ($errors->has('name'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('name') }}</strong>
	                    </span>
	                @endif
		        </div>
		        <div class="col col-sm-6">
		            <label for="maxuse" class="control-label">Maximum Number of Uses</label><br>
	                <input id="maxuse" type="text" class="form-control" name="maxuse" value="{{ old('maxuse',(isset($existing['maxuse']))?$existing['maxuse']:'0') }}" required>
					<div class="black-note">Enter 0 for no use limit</div>
	                @if ($errors->has('maxuse'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('maxuse') }}</strong>
	                    </span>
	                @endif
		        </div>
        </div>
        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
		        <div class="col col-sm-6">
		            <label for="amount" class="control-label">Discount Value</label><br>
	                <input id="amount" type="text" class="form-control" name="amount" value="{{ old('amount',(isset($existing['amount']))?$existing['amount']:'') }}" required>
	
	                @if ($errors->has('amount'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('amount') }}</strong>
	                    </span>
	                @endif
		        </div>
		        <div class="col col-sm-6">
		            <label for="discount_type" class="control-label">Discount Type</label><br>
	                <select id="discount_type" class="form-control" name="discount_type" required>
		                <option @if (old('discount_type',(isset($existing['discount_type']))?$existing['discount_type']:'dollar')=="dollar") selected @endif value="dollar">Dollar</option>
		                <option @if (old('discount_type',(isset($existing['discount_type']))?$existing['discount_type']:'dollar')=="percent") selected @endif value="percent">Percent</option>
	                </select>
	
	                @if ($errors->has('discount_type'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('discount_type') }}</strong>
	                    </span>
	                @endif
		        </div>
        </div>
	    <div class="form-group">
	            <div class="col col-sm-6{{ $errors->has('valid_from') ? ' has-error' : '' }}">
	                <label for="valid_from" class="control-label">Valid From</label><br>
	                <input id="valid_from" class="form-control datepicker" type="text" class="form-control" name="valid_from" value="{{ old('valid_from',(isset($existing['valid_from']))?$existing['valid_from']:'') }}" autocomplete="off" required>
	                @if ($errors->has('valid_from'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('valid_from') }}</strong>
	                    </span>
	                @endif
	            </div>
	            <div class="col col-sm-6{{ $errors->has('valid_to') ? ' has-error' : '' }}">
	                <label for="valid_to" class="control-label">Valid To</label><br>
	                <input id="valid_to" class="form-control datepicker" type="text" class="form-control" name="valid_to" value="{{ old('valid_to',(isset($existing['valid_to']))?$existing['valid_to']:'') }}" autocomplete="off" required>
	                @if ($errors->has('valid_to'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('valid_to') }}</strong>
	                    </span>
	                @endif
	            </div>
	    </div>
	    <div class="form-group">
	            <div class="col col-sm-12 rtecenter">
	                <button type="submit" class="btn btn-primary">
	                    Save Discount Code
	                </button>
	            </div>
	    </div>
    </form>
@stop
