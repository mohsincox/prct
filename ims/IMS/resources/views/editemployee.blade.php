@extends('masterpage')

@section('content')

		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Information of Employee</h6>
					</div>
					<div class="widget_content">
						<h3>Information of Employee</h3>
						<form action="register" method="post" <?php echo $employee->id;?> class="form_container left_label" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="<?php echo $employee->id; ?>">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
						

							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text"  placeholder="Name" tabindex="1" required value="<?php echo $employee->name;?>"/>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Designation<span class="req">*</span></label>
									<div class="form_input">
										<input name="designation" type="text"  placeholder="Designation" tabindex="1" required value="<?php echo $employee->designation;?>"/>
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title">Present Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="preaddress" type="text"  placeholder="Present Address" tabindex="1" required value="<?php echo $employee->preaddress;?>" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Permanent Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="peraddress" type="text"  placeholder="Permanent Address" tabindex="1" required value="<?php echo $employee->peraddress;?>" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Joining Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="joindate" type="text"  placeholder="Joining Date" tabindex="1" required class="datepicker" value="<?php echo $employee->joindate;?>"/>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Salary<span class="req">*</span></label>
									<div class="form_input">
										<input name="salary" type="number" step="any" style="width:100%" placeholder="Salary" tabindex="1" required value="<?php echo $employee->salary;?>" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Employee Type<span class="req">*</span></label>
									<div class="form_input">
										<input name="employeetype" type="text"  placeholder="Employee Type" tabindex="1" required value="<?php echo $employee->employeetype;?>" />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">User ID<span class="req">*</span><span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="uid" required > 
                                    <?php $user=DB::table('users')->get(); ?>  
                                     <?php foreach($user as $users) { ?>
                         <option value="<?php echo $users->id; ?>" <?php echo 'selected'?>><?php echo $users->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Image<span class="req">*</span></label>
									<div class="col-sm-6">
										<img src="http://flyte.asia/IMS/uploads/<?php if(!empty($employee)){echo $employee->file;} ?>" class="hidden-print header-log" id="header-logo" alt="" height="100" width="100">
									</div>
									<div class="form_input">
										<input name="file" type="file" placeholder="Image Upload" tabindex="1" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>

								
								<li>
								<div class="form_grid_6">
									
								</div>
								<div class="form_grid_6">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Edit</span></button>
									</div>
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
