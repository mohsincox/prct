

@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Items Model Information</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="itemmaster/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Item Model</span></a>
					</div>
						
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							
							<th>
							Sub Group
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
						<?php foreach($items as $item){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $item->id;?>
							</td>
							
							<td class="center">
								 <?php echo $item->subname;?>
							</td>
							<td class="center">
								<?php echo $item->name;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit" href="itemmaster/edititemmaster/<?php echo $item->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="itemmaster/delete/<?php echo $item->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div>

@endsection



