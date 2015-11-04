
@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Measurement Unit</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="measurementunit/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Measurement Unit</span></a>
					</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
							Measurement Group
							</th>
							<th>
								 Unit
							</th>
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($measurementunit as $m){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $m->id;?>
							</td>
							<td class="center">
								<?php echo $m->gname;?>
							</td>
							<td class="center"><?php echo $m->uname;?></td>
							<td class="center">
						<span><a class="action-icons c-edit" href="measurementunit/edit/<?php echo $m->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="measurementunit/delete/<?php echo $m->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection



