				<h3 clas="rtecenter">Profile Photo</h3>
				@php ($photo = old('photo',(isset($existing['photo']))?$existing['photo']:((Auth::check())?'/storage/'.Auth::user()->photo:'')))
				<div class="profile-photo-container"><img class="img-responsive" id="profile-photo" @if(!empty($photo)) src="{{ $photo }}" @endif style="" /></div>
				<p>&nbsp;</p>
				<div class="photo-directions" style="display:none;">
					<div class="photo-loading rtecenter" style="display:none;">
						<p><img src="/images/wait.gif" style="width:80px;height:80px;display:inline-block;"></p>
						<p>Please wait.</p>
					</div>
					<div class="photo-save-btn">
						<p class="rtecenter"><button data-method="rotate" data-option="-90" class="btn btn-event btn-rotate"><i class="fa fa-undo"></i></button>&nbsp;&nbsp;<button data-method="rotate" data-option="90" class="btn btn-event btn-rotate"><i class="fa fa-repeat"></i></button>
						<p>Use the cropping tool to position and size your photo before continuing.  When you have it ready, click the Save button.</p>
						<p class="rtecenter"><button class="btn btn-primary" onClick="SubmitCroppedPhoto()">Save Photo</button></p>
					</div>
				</div>
				<h4>Upload A Photo</h4>
			    {!! Form::open(array('route' => 'data.profilePhoto','files'=>true, 'id'=>'profile_photo_form')) !!}
			    	<div class="form-group">
						<input type="file" class="image" id="profile-photo-field" name="photo" value="">
						<input type="hidden" name="photoData" value="">
						<input type="hidden" name="original" value="">
						<div id="photoStatus" style="display:none;"></div>
						<div class="black-note">Please make sure your photo:
							<ul>
								<li>Is a PNG or JPG file</li>
								<li>Does not have a space in the name</li>
								<li>Is at least 200x200 pixels</li>
							</ul>
						</div>
			            <span class="photo help-block">
			            </span>
			    	</div>
		        {!! Form::close() !!}
