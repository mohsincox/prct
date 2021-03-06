@extends('masterpage')

@section('content')


	
	<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>HR and Payroll</h6>
					</div>
	<div id="tabs">

  <div id="tabs-2">
     <div class="widget_top">					
		<h3>Report Analyst</h3>
	</div>
	
    <div class="widget_content">
							<form action="reports/datewise" method="post" role="form" class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="userid" value="{{ Auth::id() }}">
	<ul>
		
		<li>
											<div class="form_grid_12">
								<label class="field_title">Employees<span class="req">*</span></label>
									<div class="form_input">
									  <select class="chzn-select" id="selected_employee" name="employee_id" required > 
										  <?php 

											echo "<option value='-1' Selected>Select Employee</option>";
											echo "<option value='0'>ALL</option>";
											foreach($employees as $employee){
											echo "<option value='".$employee->id."' >".$employee->name."</option>";
												}?>
											</select>
									</div>
								</div>
		</li>
		
	    <li>
			<div class="form_grid_12">
			<label class="field_title">Today</label>
			<div class="form_input">
			     <button type="submit"  onclick="return today_form_check()" class="btn_small btn_blue" name="submit" value="today"><span>Submit</span></button>
			</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">From Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="fromdate" id="fromdate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">To Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="todate" id="todate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
				<div class="form_input">
					<button type="submit" class="btn_small btn_blue" onclick="return from_to_date_form_check()" name="submit" value="fromdate"><span>Submit</span></button>
				</div>
			</div>
		</li>
	</ul>
	</form>
	</div>
	</div>
	</div>
	<script type="text/javascript">
		function today_form_check(){
			if($('#selected_employee').val() == -1){
				alert('Please select employee.');
				return false;
			}
		}
		function from_to_date_form_check(){
			if($('#selected_employee').val() == -1){
				alert('Please select employee.');
				return false;
			}

			if($('#fromdate').val() == ''){
				alert('Please select a date into FROM DATE input');
				return false;
			} else if($('#todate').val() == ''){
				alert('Please select a date into TO DATE input');
				return false;
			}

		}
	</script>
	@endsection