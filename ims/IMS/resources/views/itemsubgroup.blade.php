@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Item Category</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="itemsubgroup/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Item Category</span></a>
					</div>
					
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								 Item Group Name
							</th>
							<th>
								 Item Subgroup Name
							</th>
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($itemsubgroup as $items){?>
						<tr class="gradeC">
							<td class="center">
								<?php echo $items->id;?>
							</td>
							<td class="center">
								<?php echo $items->name;?>
							</td>
							<td class="center">
								<?php echo $items->subname;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="itemsubgroup/edititemsubgroup/<?php echo $items->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="itemsubgroup/delete/<?php echo $items->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>


@endsection






