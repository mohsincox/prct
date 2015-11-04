@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Item Group</h6>
					</div>
					<div class="widget_content">
						<div class="btn_30_dark">
							<a href="itemgroup/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Item Group</span></a>
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
						<?php foreach($itemsgroup as $items){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $items->id;?>
							</td>
							<td class="center">
								<?php echo $items->name;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="itemgroup/edititemgroup/<?php echo $items->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="itemgroup/delete/<?php echo $items->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

