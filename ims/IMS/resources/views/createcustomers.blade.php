@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Create New Customer</h6>
					</div>
					<div class="widget_content">
						<h3>Create New Customer</h3>
						
						<form action="register" method="post"  class="form_container left_label">
						
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" placeholder="Code" value="<?php echo"C-".(rand()); ?>" readonly />			
									</div>
								</div>
								<div class="clear"></div>
								</li>
							
							
								<li>
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" placeholder="Name" required />

										
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Present Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="preaddress" type="text" tabindex="1" placeholder="Present Address" required />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Opening Balance<span class="req">*</span></label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" tabindex="1" placeholder="Opening Balance" required style="width:100%" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Permanent Address<span class=""></span></label>
									<div class="form_input">
										<input name="peraddress" type="text" tabindex="1" placeholder="Permanent Address" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
									<li>
								<div class="form_grid_6">
									<label class="field_title"> Credit Limit<span class="req"></span></label>
									<div class="form_input">
										<input name="creditlimit" type="number" step="any" tabindex="1" placeholder="Credit Limit" style="width:100%" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Phone<span class="req">*</span></label>
									<div class="form_input">
										<input name="phone" type="text" tabindex="1" placeholder="Phone" required />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Email</label>
									<div class="form_input">
										<input name="email" type="email" tabindex="1" placeholder="Email" />

									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Fax</label>
									<div class="form_input">
										<input name="fax" type="text" tabindex="1" placeholder="Fax" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">URL</label>
									<div class="form_input">
										<input name="url" type="url" tabindex="1" placeholder="URL"style="width:100%" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_4">
									
								</div>
								<div class="form_grid_4">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Save</span></button>
										<button type="reset" class="btn_small btn_orange"><span>Reset</span></button>
									</div>
								</div>
								<div class="form_grid_4">
									
								</div>
								<div class="clear"></div>
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






