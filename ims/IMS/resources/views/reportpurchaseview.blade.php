

@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Report On Purchases</h6>
						<h6><strong>From Date:</strong><?php echo $fromdate;?></h6>
						
						<h6><strong>To Date:</strong><?php echo $todate;?></h6>
					</div>
					<div class="widget_content">
					
					<div class="btn_30_dark">
						<a href="printpdf/<?php echo $fromdate;?>/<?php echo $todate;?>"><span class="icon doc_pdf_co"></span><span class="btn_link">Print</span></a>
					</div>
						
						
						<table class="display data_tbl">
						<thead>
						<tr>
					<th>Serial No.</th>
					<th>Supplier Name</th>
					<th>Purchases Bill No.</th>
					<th>Purchases Bill Date</th>
					
					<th>Supplier Bill No.</th>
					<th>Supplier Bill Date</th>
							
						</tr>
						</thead>
						<tbody>
						<?php $i=1;?>
						<?php foreach($value as $p){?>
						<tr class="gradeA">
						<td class="center"><?php echo $i;?></td>
						<td class="center"><?php echo $p->suppliersname;?></td>
						<td class="center"><a href="/IMS/purchase/pdf/<?php echo $p->id;?>"><?php echo $p->name;?></a></td>
						<td class="center"><?php echo $p->purchasedate;?></td>
						
						<td class="center"><?php echo $p->suppliersbillno;?></td>
						<td class="center"><?php echo $p->suppliersbilldate;?></td>
							
						</tr>
						<?php $i++;}?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection





