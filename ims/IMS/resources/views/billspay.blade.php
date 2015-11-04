
@extends('masterpage')

@section('content')

<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Bills Pay</h6>
					</div>
					<div class="widget_content" style="width:900px;">
					<div class="btn_30_dark">
						<a href="billspay/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Bill</span></a>
					</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>ID</th>
					<th>Purchase Name</th>
					<th>Bills  Date</th>
					<th>Amount</th>
					
					<th>Action</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($billspay as $m){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $m->id;?>
							</td>
							<td class="center"><?php echo $m->pname;?></td>
						<td class="center"><?php echo $m->purchasedate;?></td>
						<td class="center"><?php echo $m->amount;?></td>
						<!--<td><?php echo $m->file;?></td>-->
							<td class="center">
						<span><a class="action-icons c-edit" href="billspay/edit/<?php echo $m->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="billspay/delete/<?php echo $m->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div>

@endsection



