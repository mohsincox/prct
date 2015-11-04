

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
						<h6>Report On Loss & Profit</h6>
						<h6><strong>From Date :</strong> &nbsp;<?php  $date=date_create($fromdate); echo date_format($date,"d/m/Y");?></h6>
						
						<h6><strong>To Date:</strong>&nbsp;<?php  $date=date_create($todate); echo date_format($date,"d/m/Y");?></h6>
					</div>
					<div class="widget_content">
					
						<div class="social_activities">
				<div class="activities_s"  style="background-color:#80B2AC">
					<div class="block_label">
						<a href="fromtoday/sales/<?php echo $fromdate;?>/<?php echo $todate;?>" target="_blank">Total Sales:<span><?php foreach($value as $c){ $sales=$c->sales; echo $sales;}?></span></a>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				<div class="activities_s" style="background-color:#80B2AC">
					<div class="block_label">
						<a href="fromtoday/cash/<?php echo $fromdate;?>/<?php echo $todate;?>" target="_blank">Total Cash<span><?php foreach($value1 as $c){ $cash=$c->cash; echo $cash; }?></span></a>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				<div class="activities_s" style="background-color:#80B2AC">
					<div class="block_label">
						Total Due<span><?php  echo number_format($sales-$cash,2,".",""); ?></span>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				
				
			</div>
						
						
					</div>
					
				</div>
			
</div></div></div>

@endsection





