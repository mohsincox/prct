
@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Measurement Group</h6>
					</div>
					<div class="widget_content" >
					<div class="btn_30_dark">
						<a href="measurementgroup/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Measurement Group</span></a>
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
						<?php foreach($measurementgroup as $m){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $m->id;?>
							</td>
							<td class="center">
								<?php echo $m->name;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="measurementgroup/edit/<?php echo $m->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="measurementgroup/delete/<?php echo $m->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection



