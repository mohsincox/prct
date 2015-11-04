@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Stock In Out Analyst</h6>
						<h6><strong>From Date:</strong><?php echo $fromdate;?></h6>
						
						<h6><strong>To Date:</strong><?php echo $todate;?></h6>
					</div>
					<div class="widget_content">
					<!--<div class="btn_30_dark">
						<a href="printpdf/<?php// echo $fromdate;?>/<?php //echo $todate;?>"><span class="icon doc_pdf_co"></span><span class="btn_link">Print</span></a>
					</div>-->
					
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 Branch Name
							</th>
							<th>
								Items Name
							</th>
							<th>
								 Serial No.
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($factioyitems as $users){?>
						<tr class="gradeA">
							<td class="center">
								 <?php echo $users-> branchname;?>
							</td>
							<td class="center">
								<?php echo $users->itemsname;?>
							</td>
							<td class="center">
								<?php echo $users->slno;?>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

