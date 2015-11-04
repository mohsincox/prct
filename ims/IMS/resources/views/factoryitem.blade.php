@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Factory Inventory</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="factoryitem/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Inventory</span></a>
					</div>
						
						<table class="display data_tbl">
						<thead>
						<tr>
						    <th>Category Name</th>
							<th>Item Model Name</th>
							<th>Total Quantity</th>
							<th>Remaining Quantity</th>
							<th>Sales Quantity</th>
							<th>Damaged</th>
							<th>Measurement Unit</th>
						</tr>
						</thead>
						<tbody>
						
						
						<?php foreach($factoryitem as $items){?>
						<tr class="gradeX">
						<td class="center"><?php echo $items->cname;?></td>
						<td class="center"><?php echo $items->subname;?></td>
						<td class="center"><a href="factoryitem/view/<?php echo $items->itemid;?>"><?php echo $items->quantity;?></a></td>
						<td class="center"><a href="factoryitem/remain/<?php echo $items->itemid;?>">
						<?php 
						  $cnt = DB::table('factioyitems')
						      ->select(DB::raw('count(factioyitems.itemsid) as count'))
							  ->where('itemsid',$items->itemid)
							  ->where('sale_product',0)
							  ->where('status',1)
							  ->first();  
						  echo $cnt->count;
						?>
						  </a>
						</td>
						<td class="center"><a href="factoryitem/sales/<?php echo $items->itemid;?>">
						<?php 
						  $cnt = DB::table('factioyitems')
						      ->select(DB::raw('count(factioyitems.itemsid) as count'))
							  ->where('itemsid',$items->itemid)
							  ->where('sale_product',1)
							  ->first();  
						  echo $cnt->count;
						?>
						  </a>
						</td>
						<td class="center"><a href="factoryitem/damaged/<?php echo $items->itemid;?>">
						<?php 
						  $cnt = DB::table('factioyitems')
						      ->select(DB::raw('count(factioyitems.itemsid) as count'))
							  ->where('itemsid',$items->itemid)
							  ->where('status',0)
							  ->first();  
						  echo $cnt->count;
						?>
						</a></td>
						<td class="center"><?php echo $items->nname;?></td>
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

