@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>User Group</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
									<a href="usersgroup/addnew"><span class="icon add_co"></span><span class="btn_link">add New User</span></a>
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
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($usersgroup as $users){?>
						<tr class="gradeA">
							<td class="center">
								 <?php echo $users->id;?>
							</td>
							<td class="center">
								<?php echo $users->name;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="usersgroup/editusersgroup/<?php echo $users->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="usersgroup/delete/<?php echo $users->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

