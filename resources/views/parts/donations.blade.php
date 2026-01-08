@if(isset($register))
<h3 class="{{(isset($stripe))?'gray-bkg padding-8':''}}">Choose Additional Donation Amount (OPTIONAL):</h3>
@else
<h3 class="{{(isset($stripe))?'gray-bkg padding-8':''}}">Choose a Donation Amount:</h3>
@endif
<div class="row">
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="500">$500</a></div>
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="250">$250</a></div>
    <div class="clearfix visible-xs"></div>
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="100">$100</a></div>
    <div class="clearfix hidden-xs hidden-md hidden-lg"></div>
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="50">$50</a></div>
    <div class="clearfix visible-xs"></div>
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="25">$25</a></div>
    <div class="col col-xs-6 col-sm-4 col-md-2 col-spacing"><a href="#" class="btn btn-danger btn-block donate-amount" data-amount="other">Other</a></div>
</div>
<div class="{{ $errors->has('amount') ? ' has-error' : '' }}">
    @if ($errors->has('amount'))
        <span class="help-block">
            <strong>{{ $errors->first('amount') }}</strong>
        </span>
    @endif
</div>
<div class="form-group" id="other-amount" style="display:none;">
	<label for="otheramount" class="col col-sm-3">Donation Amount</label>
	<div class="col col-sm-8">
		<input id="otheramount" type="text" class="form-control col col-sm-9" name="otheramount" value="{{old('otheramount')}}" autocomplete="off">
	</div>
</div>
<input type="hidden" name="amount" value="0" required>
