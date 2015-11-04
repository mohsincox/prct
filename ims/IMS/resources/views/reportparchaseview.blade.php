
@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Report On Purchases</h6>
					</div>
					<div class="widget_content">
						
						<table class="display data_tbl">
						<thead>
						<tr>
						<th>ID</th>
					<th>Name</th>
					<th>Purchases Date</th>
					<th>Supplier ID</th>
					
					<th>Supplier Bill No.</th>
					<th>Supplier Bill Date</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($value as $p){?>
						<tr class="gradeC">
						<td class="center"><?php echo $p->id;?></td>
						<td class="center"><?php echo $p->name;?></td>
						<td class="center"><?php echo $p->purchasedate;?></td>
						<td class="center"><?php echo $p->suppliersid;?></td>
						<td class="center"><?php echo $p->suppliersbillno;?></td>
						<td class="center"><?php echo $p->suppliersbilldate;?></td>
				
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection





