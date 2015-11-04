
@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
			
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						</br>
						<h6><strong>From Date:</strong><?php echo $fromdate;?></h6>
						
						<h6><strong>To Date:</strong><?php echo $todate;?></h6>
					</div>
					
								
							
								
					<div class="widget_content" >
						<h3>Purchase</h3>
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								Name
							</th>
							<th>
								 Amount
							</th>
							<th>
								 Measurement Unit
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($value as $p){?>
						<tr class="gradeX">
							<td>
								 <?php echo $p->name;?>
							</td>
							<td>
								<?php echo $p->fcnt;?>
							</td>
							<td>
								<?php echo $p->mname;?>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
						
						
					</div>
					<div class="widget_content" >
						<h3>Stock In Out Analyst</h3>
						<table class="display data_tbl">
					
						<thead>
						<tr>
							<th>
								 Name
							</th>
							<th>
								 Amount
							</th>
							<th>
								 Measurement Unit
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($value1 as $p){?>
						<tr class="gradeX">
							<td>
								 <?php echo $p->name;?>
							</td>
							<td>
								<?php echo $p->cnt;?>
							</td>
							<td>
								<?php echo $p->mnane;?>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
						
						</table>
						
						
					</div>
				</div>
</div></div></div>

@endsection


						

