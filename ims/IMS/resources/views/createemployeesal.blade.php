@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Employee Salary</h6>
					</div>
					<div class="widget_content">
						<h3>Create Employee Salary</h3>
						<form action="/IMS/employeesal/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								
							<li>
							<div class="form_grid_6">
									<label class="field_title">Employee Name<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" onchange="salary_detail(this.value)" style=" width:100%" name="ename" required > 
                                    	<option value="-1" selected="">Select Employee</option>
                                    <?php $acc=DB::table('employeeinfo')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>"><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
										
									</div>
								</div>
								
								
								<div class="clear"></div>
							</li>
							<li id='salary_detail_div'>
							</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Particulars<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="pname" required > 
                                    <?php $acc=DB::table('particulars')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
										
									</div>
								</div>
								<div class="form_grid_6">
									<label for="name" class="field_title">Amount<span class="req">*</span></label>
									<div class="form_input">
									<input name="amount" type="number" step="any" style="width:100%" tabindex="1" required />
									</div>
								</div>
								
								<div class="clear"></div>
							</li>
							<li>

							<div class="form_grid_6">
								<label class="field_title">Description<span class="req">*</span></label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" required />
									</div>
								</div>
								<div class="form_grid_6">
									<label for="name" class="field_title">Date<span class="req">*</span></label>
									<div class="form_input">
									<input name="vdate" type="text" style="width:100%" tabindex="1" class="datepicker" required />
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
	<script type="text/javascript">
		$(function (){
			$('#salary_detail_div').hide();
		});

		function salary_detail(employee_id){
			if(employee_id != -1){
				$_token = "{{ csrf_token() }}";
				$.ajax({
					type: "POST",
					url: "addnew/get_employee_salary",
					data: {
						_token: $_token,
						employee_id: Number(employee_id),
					},
					success: function (response) {
						if(response == 0){
							alert('Not found this employee');
						} else {
							$('#salary_detail_div').empty();
							$('#salary_detail_div').show();
							$('#salary_detail_div').append('<div class="form_grid_6">'+
									'<label for="name" class="field_title"><span style="color: blue">Basic Salary</span></label>'+
									'<div class="form_input">'+
									'<input type="number" step="any" style="width:100%" tabindex="1" value="'+response.basic_salary+'" readonly/>'+
									'</div>'+
								'</div>'+
								'<div class="form_grid_6">'+
									'<label for="name" class="field_title"><span style="color: blue">Total Salary</span></label>'+
									'<div class="form_input">'+
									'<input type="number" step="any" style="width:100%" tabindex="1" value="'+response.total_salary+'" readonly/>'+
									'</div>'+
								'</div>'+
								'<div class="clear"></div>');
						}
					}
				});			
			} else {
				$('#salary_detail_div').hide();
			}
		}

		function fetch_select(bankaccount_id)
		{
			var arr = bankaccount_id.split("+");
			//alert(arr[1]);
			
			$_token = "{{ csrf_token() }}";
			$.ajax({
				type: 'post',
				url: 'get_bankaccount_code',
				data: {
					_token: $_token, 	 
					bankaccount_id: arr[0]
				},
				success: function (response) {
					$('#code').val(response.code);
				}
			});
			
		}

	</script>
@endsection
