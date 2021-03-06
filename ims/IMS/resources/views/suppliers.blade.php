@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Suppliers</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
									<a href="suppliers/addnew"><span class="icon add_co"></span><span class="btn_link">add New Supplier</span></a>
								</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<!--<th>
								 ID
							</th>
							-->
							<th>
								 Serial No.
							</th>
							<th>
								 Code
							</th>
							<th>
								 Name
							</th>
							<th>
								 Present Address
							</th>
							<th>
								 Permanent Address
							</th>
							<th>
								 Phone
							</th>
							<th>
								 Email
							</th>
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php $i=1;
						foreach($suppliers as $s){?>
						<tr class="gradeA">
						<!--<td class="center"><?php echo $s->id;?></td>-->
						<td class="center"><?php echo $i;?></td>
						<td class="center"><?php echo $s->code;?></td>
						<td class="center"><?php echo $s->name;?></td>
						<td class="center"><?php echo $s->preaddress;?></td>
						<td class="center"><?php echo $s->peraddress;?></td>
						<td class="center"><?php echo $s->phone;?></td>
						<td class="center"><?php echo $s->email;?></td>
					    <td class="center">
						<span><a class="action-icons c-edit" href="suppliers/edit/<?php echo $s->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="suppliers/delete/<?php echo $s->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>

					  </td>
							
						</tr>
						<?php $i++;}?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>



@endsection

