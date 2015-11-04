@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Change Password</h6>
					</div>
					@if (count($errors) > 0)
						<div class="form_grid_12" style="color: red">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="widget_content">
						<h3>Change Password</h3>
						<form action="changepass" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> Old Password:</label>
									<div class="form_input">
										<input name="old_password" type="password" tabindex="1" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> New Password:</label>
									<div class="form_input">
										<input name="new_password" type="password" tabindex="1" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> Confirm Password:</label>
									<div class="form_input">
										<input name="confirm_password" type="password" tabindex="1" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Submit</span></button>
									</div>
								</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
@endsection
