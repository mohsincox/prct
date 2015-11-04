
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit User</h6>
					</div>
					<div class="widget_content">
						<h3>Edit User</h3>
						
						<form action="register" method="post" <?php echo $customers->id;?> class="form_container left_label">
						
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id" value="<?php echo $customers->id; ?>">
				<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" value="<?php echo $customers->code;?>" readonly />
									</div>
								</div>
								<div class="clear"></div>
								</li>
							
							
								<li>
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $customers->name;?>" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Present Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="preaddress" type="text" tabindex="1" value="<?php echo $customers->preaddress;?>" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Opening Balance<span class="req">*</span></label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" tabindex="1" value="<?php echo $customers->openbalance;?>" required style="width:100%" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Permanent Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="peraddress" type="text" tabindex="1"  value="<?php echo $customers->peraddress;?>" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Credit Limit<span class="req">*</span></label>
									<div class="form_input">
										<input name="creditlimit" type="number" tabindex="1" step="any" value="<?php echo $customers->creditlimit;?>" required style="width:100%" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Phone<span class="req">*</span></label>
									<div class="form_input">
										<input name="phone" type="text" tabindex="1" value="<?php echo $customers->phone;?>" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Email</label>
									<div class="form_input">
										<input name="email" type="email" tabindex="1" value="<?php echo $customers->email;?>" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Fax</label>
									<div class="form_input">
										<input name="fax" type="text" tabindex="1" value="<?php echo $customers->fax;?>" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">URL</label>
									<div class="form_input">
										<input name="url" type="url" tabindex="1" value="<?php echo $customers->url;?>" style="width:100%"/>
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_4">
									
								</div>
								<div class="form_grid_4">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Edit</span></button>
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

