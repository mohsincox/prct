@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Users</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
									<a href="users/addnew"><span class="icon add_co"></span><span class="btn_link">add New User</span></a>
								</div>	
						<table class="display data_tbl">
						<thead>
						<tr>
						
							<th>
								 ID
							</th>
							<th>
								 Type
							</th>
							<th>
								 Name
							</th>
							<th>
								 Email
							</th>
							
						</tr>
						</thead>
						<tbody>

						<?php foreach($users as $users){?>
						<tr class="gradeA">
							<td class="center">
								<?php echo $users->id;?>
							</td>
							<td class="center">
								<?php echo $users->usergroupname;?>
							</td>
							<td class="center">
								<?php echo $users->username;?>
							</td>
							<td class="center">
								<?php echo $users->email;?>
							</td>
							
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>
@endsection
