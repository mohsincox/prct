
@extends('masterpage')

@section('content')
<?php 
$userid=1;
foreach($profile as $com){
$id=$com->id;
}
//echo $id;

?>
	<div id="content">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Invoice for Monthly bill</h6>
					</div>
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">
							<div class="header">

<h1><?php if(!empty($com)){echo $com->name;} ?></h1><br><address>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(!empty($com)){echo $com->address;} ?><br>
&nbsp;&nbsp;&nbsp;&nbsp;
Tel:<?php if(!empty($com)){echo $com->telephone;} ?> ,Mobile: <?php if(!empty($com)){echo $com->mobile;} ?><br>

E-mail:<?php if(!empty($com)){echo $com->email;} ?><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php if(!empty($com)){echo $com->url;} ?> " target="_blank"> <?php if(!empty($com)){echo $com->url;} ?></a><br>

</address>



</div><br>
<div class="clear"></div>
							
								<div class="invoice_action_bar">
									
									<div class="btn_30_light">
										<a href="#" title="Print"><span class="icon printer_co"></span></a>
									</div>
									
								</div>
							
								<span class="clear"></span>
								<div class="grid_12 invoice_title">
									<h5><u>INVOICE/BILL</u></h5>
								</div>
								<?php foreach($value as $p){?>
								<div class="grid_6 invoice_to">
									<ul>
										<li>
										<strong><span>Name/Title:<?php echo $p->cname;?></span></strong>
										<span>Address:<?php echo $p->preaddress;?></span>
										<span>Mobile No:<?php echo $p->phone;?></span>
										<?php $discount=$p->discount;?>
										</li>
									</ul>
								</div>
								<div class="grid_6 invoice_from">
									<ul>
										<li>
										<strong><span>Date:<?php echo $p->salesdate;?></span></strong>
										<span>SI No:<?php echo $p->id;?></span>
										<span>Voucher No:<?php echo $p->sname;?></span>
										
										</li>
									</ul>
								</div><?php }?>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table>
										<thead>
										<tr class=" gray_sai">
											<th>
												Item Name
											</th>
											<th>
												Quantity
											</th>
											<th>
												Measurement Unit
											</th>
											<th>
												Rate
											</th>
											
											<th>
												Amount(BDT)
											</th>
										</tr>
										</thead>
										<tbody>
										<?php 
										$sum=0;
										?>
										<?php foreach($value1 as $p){?>
										<tr>
											<td class="center"><?php echo $p->iname;?></td>
											<td class="center"><?php echo $p->quantity;?></td>
											<td class="center"><?php echo $p->mname;?></td>
											<td class="center"><?php echo $p->rate;?></td>
											<td class="center"><?php echo $p->amount;?></td>
										</tr>
											<?php 
										$a=$p->amount;
										$sum=$sum+$a;}
										?>
									    
										<tr>
											<td colspan="4" class="grand_total">
												Sub Total:
											</td>
											<td>
												<?php echo number_format($sum, 2, '.', '');?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Discount:
											</td>
											<td>
												<?php echo number_format($discount, 2, '.', '');?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Gross Total:
											</td>
											<td>
												<?php echo number_format($sum-$discount, 2, '.', '');?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Previous due:
											</td>
											<td>
											<?php if($value[0]->status == 1){
														echo number_format($sum-$discount - $value[0]->openbalance, 2, '.', '');
													} else {
														echo number_format($value[0]->openbalance, 2, '.', '');
													}	
											?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Present Balance:
											</td>
											<td>
											<?php  //echo $value[0]->status.'<br/>'; 
											if($value[0]->status == 1){
														echo number_format($value[0]->openbalance, 2, '.', '');
													} else {
														echo number_format($sum-$discount+$value[0]->openbalance, 2, '.', '');
													}	
											?>	
											</td>
										</tr>
										</tbody>
										</table>
									</div>
									<p class="amount_word">
										Amounts in word: <span><?php echo App\Http\Controllers\Common\CommonController::convertNumberToWord(number_format($sum-$discount, 2, '.', '')); ?> Taka Only</span>
									</p>
								
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>

@endsection

