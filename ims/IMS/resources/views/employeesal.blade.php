@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Employee Salary</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="employeesal/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Employee Salary</span></a>
					</div>
						
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								 Employee Name
							</th>
							<th>
								Amount
							</th>
							<th>
								Particulars
							</th>
							
							<th>
								Date
							</th>
							<th>
								Description
							</th>
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($employeesal as $salary){?>
						<tr class="gradeA">
							<td class="center">
								 <?php echo $salary->id;?>
							</td>
							<td class="center">
								<?php echo $salary->ename;?>
							</td>
							<td class="center">
								<?php echo $salary->amount;?>
							</td>
							<td class="center">
								<?php echo $salary->pname;?>
							</td>
							<td class="center">
								<?php echo $salary->vdate;?>
							</td>
							<td class="center">
								<?php echo $salary->description;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="employeesal/edit/<?php echo $salary->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="employeesal/delete/<?php echo $salary->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

