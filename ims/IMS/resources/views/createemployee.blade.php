@extends('masterpage')

@section('content')
<div id="content">
<?php 
$userid=1;
foreach($employee as $com){
$id=$com->id;
}
?>
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Information of Employee</h6>
					</div>
					<div class="widget_content">
						<h3>Information of Employee</h3>
						<form action="register" method="post" class="form_container left_label" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text"  placeholder="Name" tabindex="1" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Designation<span class="req">*</span></label>
									<div class="form_input">
										<input name="designation" type="text"  placeholder="Designation" tabindex="1" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title">Present Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="preaddress" type="text"  placeholder="Present Address" tabindex="1" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Permanent Address<span class="req">*</span></label>
									<div class="form_input">
										<input name="peraddress" type="text"  placeholder="Permanent Address" tabindex="1" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Joining Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="joindate" type="text"  placeholder="Joining Date" tabindex="1" class="datepicker" required />
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Employee Type<span class="req">*</span></label>
									<div class="form_input">
										<input name="employeetype" type="text"  placeholder="Employee Type" tabindex="1" required />
									</div>
								</div>
								
								<div class="clear"></div>
								</li>
								<li>
								
								
								<div class="form_grid_6">
									<label class="field_title">User ID<span class="req">*</span></label>
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
										<label class="field_title">Basic Salary<span class="req">*</span></label>
										<div class="form_input">
											<input name="basic_salary" id="basic_salary" type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="Basic Salary" tabindex="1" required />
										</div>
									</div>
									<div class="form_grid_6">
										<label class="field_title">House Rent<span class="req">*</span></label>
										<div class="form_input">
											<input name="house_rent" id="house_rent" type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="House Rent" tabindex="1" required />
										</div>
									</div>

									<div class="clear"></div>
								</li>
								<li>
									<div class="form_grid_6">
										<label class="field_title">Medical Expense<span class="req">*</span></label>
										<div class="form_input">
											<input name="medical_expense" id="medical_expense" type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="Medical Expense" tabindex="1" required />
										</div>
									</div>
									<div class="form_grid_6">
										<label class="field_title">Food Expense<span class="req">*</span></label>
										<div class="form_input">
											<input name="food_expense" id='food_expense' type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="Food Expense" tabindex="1" required />
										</div>
									</div>

									<div class="clear"></div>
								</li>
								<li>
									<div class="form_grid_6">
										<label class="field_title">Conveyance</label>
										<div class="form_input">
											<input name="conveyance" id='conveyance' type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="Conveyance" tabindex="1" />
										</div>
									</div>
									<div class="form_grid_6">
										<label class="field_title">Entertain Allowance</label>
										<div class="form_input">
											<input name="entertain_allowance" id="entertain_allowance" type="number" oninput="calculate_total()" step="any" style="width:100%" placeholder="Entertain Allowance" tabindex="1"/>
										</div>
									</div>
									<div class="clear"></div>
								</li>
								<li>
									<div class="form_grid_6">
										<label class="field_title">Total Salary</label>
										<div class="form_input">
											<input name="total_salary" id="total_salary" type="number" step="any" style="width:100%" placeholder="Total Salary" tabindex="1" required readonly="" />
										</div>
									</div>
									<div class="clear"></div>
								</li>	
								<li>
								<div class="form_grid_6">
									<label class="field_title">Image<span class="req">*</span></label>
									<div class="col-sm-6">
										<img src="uploads/<?php if(!empty($com)){echo $com->file;} ?>" id="show_image" class="hidden-print header-log" id="header-logo" alt="" height="100" width="100">
									</div>
									<div class="form_input">
										<input name="file" id="imgInp" type="file" placeholder="Image Upload" tabindex="1" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_6">
									
								</div>
								<div class="form_grid_6">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Save</span></button>
										<button type="reset" class="btn_small btn_orange"><span>Reset</span></button>
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
<script type="text/javascript">
	function calculate_total(){
		var basic_salary = Number($('#basic_salary').val());
		var house_rent = Number($('#house_rent').val());
		var medical_expense = Number($('#medical_expense').val());
		var food_expense = Number($('#food_expense').val());
		var conveyance = Number($('#conveyance').val());
		var entertain_allowance = Number($('#entertain_allowance').val());
		var total_salary = basic_salary + house_rent + medical_expense + food_expense + conveyance + entertain_allowance;
		$('#total_salary').val(total_salary);
	}

	function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();

	        reader.onload = function (e) {
	            $('#show_image').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#imgInp").change(function(){
	    readURL(this);
	});
</script>

@endsection
