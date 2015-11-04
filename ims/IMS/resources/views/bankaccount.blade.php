@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Bank Account Information</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="bankaccount/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Account</span></a>
					</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								 Account Code
							</th>
							<th>
								 Account No.
							</th>
							
							
							<th>
								 Bank Name
							</th>
							<th>
								 Branch Name
							</th>
							<th>
								 Opening Balance
							</th>
							
							<th>
								 Action
							</th>
							
						</tr>
						</thead>
						<tbody>
						
						<?php //print_r();
						foreach($bankaccount as $account){?>
						<tr class="gradeA">
							<td class="center">
								 <?php echo $account->id;?>
							</td>
							<td class="center">
								<?php echo $account->code;?>
							</td>
							<td class="center">
								<?php echo $account->name;?>
							</td>
							
							<td class="center">
								<?php echo $account->bankname;?>
							</td>
							<td class="center">
								<?php echo $account->branchname;?>
							</td>
							<td class="center">
								<?php echo $account->openbalance;?>
							</td>
							
							<td class="center">
						<span><a class="action-icons c-edit" href="bankaccount/edit/<?php echo $account->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="bankaccount/delete/<?php echo $account->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span><span></span><span></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

