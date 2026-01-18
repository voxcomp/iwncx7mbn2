		<div class="form-group{{ $errors->has('shipaddress') ? ' has-error' : '' }}">
	        <div class="col col-sm-12">
	            <input id="shipaddress" type="text" class="form-control" name="shipaddress" value="{{ old('shipaddress',(isset($user['shipaddress']))?$user['shipaddress']:'') }}" autofocus placeholder="Address">
	            @if ($errors->has('shipaddress'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('shipaddress') }}</strong>
	                </span>
	            @endif
	        </div>
		</div>
		<div class="row">
			<div class="col col-sm-6 col-xs-12 col-spacing{{ $errors->has('shipcity') ? ' has-error' : '' }}">
	            <input id="shipcity" type="text" class="form-control" name="shipcity" value="{{ old('shipcity',(isset($user['shipcity']))?$user['shipcity']:'') }}" placeholder="City">
	            @if ($errors->has('shipcity'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('shipcity') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="visible-xs clearfix"></div>
			<div class="col col-sm-2 col-xs-4 col-spacing{{ $errors->has('shipstate') ? ' has-error' : '' }}">
	            {{ html()->select('shipstate', ['AL' => 'AL', 'AK' => 'AK', 'AS' => 'AS', 'AZ' => 'AZ', 'AR' => 'AR', 'CA' => 'CA', 'CO' => 'CO', 'CT' => 'CT', 'DE' => 'DE', 'DC' => 'DC', 'FM' => 'FM', 'FL' => 'FL', 'GA' => 'GA', 'GU' => 'GU', 'HI' => 'HI', 'ID' => 'ID', 'IL' => 'IL', 'IN' => 'IN', 'IA' => 'IA', 'KS' => 'KS', 'KY' => 'KY', 'LA' => 'LA', 'ME' => 'ME', 'MH' => 'MH', 'MD' => 'MD', 'MA' => 'MA', 'MI' => 'MI', 'MN' => 'MN', 'MS' => 'MS', 'MO' => 'MO', 'MT' => 'MT', 'NE' => 'NE', 'NV' => 'NV', 'NH' => 'NH', 'NJ' => 'NJ', 'NM' => 'NM', 'NY' => 'NY', 'NC' => 'NC', 'ND' => 'ND', 'MP' => 'MP', 'OH' => 'OH', 'OK' => 'OK', 'OR' => 'OR', 'PW' => 'PW', 'PA' => 'PA', 'PR' => 'PR', 'RI' => 'RI', 'SC' => 'SC', 'SD' => 'SD', 'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT', 'VT' => 'VT', 'VI' => 'VI', 'VA' => 'VA', 'WA' => 'WA', 'WV' => 'WV', 'WI' => 'WI', 'WY' => 'WY'], old('shipstate', isset($user['shipstate']) ? $user['shipstate'] : 'WI'))->class('form-control')->id('shipstate') }}
	            @if ($errors->has('shipstate'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('shipstate') }}</strong>
	                </span>
	            @endif
			</div>
			<div class="col col-sm-4 col-xs-8 col-spacing{{ $errors->has('shipzip') ? ' has-error' : '' }}">
	            <input id="shipzip" type="text" class="form-control" name="shipzip" value="{{ old('shipzip',(isset($user['shipzip']))?$user['shipzip']:'') }}" placeholder="Zip Code">
	            @if ($errors->has('shipzip'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('shipzip') }}</strong>
	                </span>
	            @endif
			</div>
		</div>