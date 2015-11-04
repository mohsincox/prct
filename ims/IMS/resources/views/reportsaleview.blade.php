

@extends('masterpage')

@section('content')
<?php
use App\Models\Info;
?>
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Report On sales</h6>
						<h6><strong>From Date :</strong> &nbsp;<?php  $date=date_create($fromdate); echo date_format($date,"d/m/Y");?></h6>
						
						<h6><strong>To Date:</strong>&nbsp;<?php  $date=date_create($todate); echo date_format($date,"d/m/Y");?></h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
									<a href="printpdf/<?php echo $fromdate;?>/<?php echo $todate;?>"><span class="icon doc_pdf_co"></span><span class="btn_link">Print</span></a>
								</div>
						<!--<button class="btn_small btn_blue"><a href="printpdf/<?php echo $fromdate;?>/<?php echo $todate;?>"><span>Print</span></a></button>-->
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>Serial No.</th>
							<th>Customer Name</th>
							<th>Customer Address</th>
							<th>Voucher No.</th>							
							<th>Sales Date</th>
							<!--<th>Sales Amount</th>
							<th>Cash Amount</th>
							<th>Dues Amount</th>
							<th>Sales Type</th>
							-->
						</tr>
						</thead>
						<tbody>
						<?php  $i=1; $sum = 0; foreach($value as $p){?>
						<tr class="gradeC">
					    <td class="center"><?php echo $i;?></td>
						<td class="center"><?php echo $p->cname;?></td>
						<td class="center"><?php echo $p->address;?></td>
						<td class="center"><a href="/IMS/physicalsales/print/<?php echo $p->id;?>"><?php echo $p->name;?></a></td>						
						<td class="center"><?php $date=date_create($p->salesdate); echo date_format($date,"d/m/Y");?></td>

						<!--
						<td class="center">
						<?php 
						    $var = array($p->id);
							$spname="salesanalyst";
							$value=Info::callinfo($var,$spname);
							foreach($value as $v){
								$samount=$v->amount;
								echo $samount.'-';
							}
							$sum=$sum+$samount;
							 echo $sum;
						?>
						</td>
						<td class="center">
						<?php 
						    $var = array($p->cid);
							$spname="cashanalyst";
							$value=Info::callinfo($var,$spname);
							foreach($value as $v){
								$camount=$v->amount;
								echo $camount;
							}
						?>
						</td>
						
						<td class="center">
						<?php 
						    $damount=$sum-$camount;
							if($damount>0){
									echo $damount;
							}
						?>
						</td>
						<td class="center">
						<?php if($damount<0){ 
								echo '<span style="color:green">Cash</span>';
						 }else{ 
						 echo '<span style="color:red">Dues</span>';
						 }
						 ?>
						</td>
						-->
						</tr>
						<?php $i++; }?>
						
						</tbody>
						
						</table>
						
					</div>
					
				</div>
			
</div></div></div>

@endsection





