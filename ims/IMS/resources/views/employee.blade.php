@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Employee Infromation</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
									<a href="employee/addnew"><span class="icon add_co"></span><span class="btn_link">add New Employee</span></a>
								</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>

							<th>
								 Name
							</th>
							<th>
								 Designation
							</th>
							<th>
								 Joining Date
							</th>
							<th>
								 Present Address
							</th>
							<th>
								 Permanent Address
							</th>
							<th>
								Total Salary
							</th>
							
							
							<th>
								 Image
							</th>
							<th>
								 Action
							</th>
							
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($employee as $p){?>
						<tr class="gradeA">
						<td class="center"><?php echo $p->id;?></td>
						<td class="center"><?php echo $p->name;?></td>
						<td class="center"><?php echo $p->designation;?></td>
						<td class="center"><?php echo $p->joindate;?></td>
						<td class="center"><?php echo $p->peraddress;?></td>
						<td class="center"><?php echo $p->preaddress;?></td>
						<td class="center"><a href="#" onclick="pop('<?php echo $p->id; ?>')"><?php echo $p->total_salary;?></a></td>
						
						<td class="center">
						<img src="uploads/<?php if(!empty($p)){echo $p->file;} ?>" class="hidden-print header-log" id="header-logo" alt="" height="50" width="50">

						
					    <td class="center">
						<span><a class="action-icons c-edit" href="employee/edit/<?php echo $p->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="employee/delete/<?php echo $p->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
						</td>
					  </td>
							
						</tr>
					<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

<script type="text/javascript">
	function pop(employee_id) {
		$_token = "{{ csrf_token() }}";
		$.ajax({
			type: "POST",
			url: "employee/get_employee_salary",
			data: {
				_token: $_token,
				employee_id: employee_id,
			},
			success: function (response) {
				if(response == 0){
					alert('Not found this employee');
				} else {
					var box = bootbox.dialog({
			    		closeButton: false,
			 			message:  
			 			'<div class="grid_container">'+ 
				            '<div class="grid_12 full_block">'+	
								'<div class="widget_wrap">'+
									'<div class="widget_top">'+
										'<span class="h_icon list_image">'+'</span>'+
										'<h6>'+response.name+' Salary Detail</h6>'+
									'</div>'+
				            			'<div class="widget_content">' +'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Basic Salary:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.basic_salary+'" readonly />'+
											'</div>'+
										     '<br>'+
											
											'<div class="form_input">'+
												'<strong>&nbsp;House Rent:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.house_rent+'" readonly />'+
											'</div>'+ 
											
											'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Medical Expense:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.medical_expense+'" readonly />'+
											'</div>'+ 
											
											'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Food Expense:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.food_expense+'" readonly />'+
											'</div>'+ 
											
											'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Conveyance:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.conveyance+'" readonly />'+
											'</div>'+ 
											
											'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Entertain Allowance:</strong>&nbsp;<input type="text" tabindex="1"  value="'+response.entertain_allowance+'" readonly />'+
											'</div>'+ 
											
											'<br>'+
											'<div class="form_input">'+
												'<strong>&nbsp;Total Salary:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" tabindex="1"  value="'+response.total_salary+'" readonly />'+
											'</div>'+ 
											
											
										'</div>'+'<br>'+
								'</div>'+
							'</div>'+
						'</div>',	
			                buttons: {
			                    danger: {
							      label: "Close",
							      className: "btn_small btn_orange",
							      callback: function() {
							        window.location.reload();
							      }
							    }
			                }
			            }
			        );
				}
			} 
		}); 
    	
    }
</script>


@endsection







