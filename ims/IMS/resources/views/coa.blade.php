@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Chart of Account Information</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="coa/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Chart of Account</span></a>
					</div>
					<div class="btn_30_dark">
						<a href="bankaccountcoa/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Bank Account</span></a>
					</div>
					<div class="btn_30_dark">
						<a href="customercoa/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Customer Account</span></a>
					</div>
					<div class="btn_30_dark">
						<a href="suppliercoa/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Supplier Account</span></a>
					</div>
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								 Code
							</th>
							<th>
								 Name
							</th>
							<th>
								 Description
							</th>
							<th>
								 COA Type
							</th>
							<th>
								 Increase To
							</th>
							<th>
								 Tax Rate
							</th>
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						
						<?php 
						//print_r($coa);
						foreach($coa as $c){?>
						<tr class="gradeC">
							<td class="center">
								 <?php echo $c->id;?>
							</td>
							<td class="center">
								<?php echo $c->code;?>
							</td>
							<td class="center">
								<?php echo $c->name;?>
							</td>
							<td>
								<?php echo $c->description;?>
							</td>
							<td class="center">
								<?php echo $c->coatypename;?>
							</td>
							<td class="center">
								<?php echo $c->increasetypename;?>
							</td>
							<td class="center">
								<?php echo $c->taxratename;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-edit"  href="coa/edit/<?php echo $c->id;?>" title="Edit">Edit</a></span>
						<span><a class="action-icons c-Delete" href="coa/delete/<?php echo $c->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

