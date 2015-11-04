<?php
   //use App\Models\Info;
   //$var = array(23);
  // $spname="viewpurchase";
 // $value= \App\Models\Info::callinfo($var,$spname);
 // print_r($value);
?>
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
						<h6>Purchase Information</h6>
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
									<h5><u>Purchase</u></h5>
								</div>
								<?php //print_r($value);?>
								<?php foreach($value as $p){?>
								<div class="grid_6 invoice_to">
									<ul>
										<li>
										<strong><span>Supplier Name:<?php echo $p->sname;?></span></strong>
										<span>Supplier Bill No:<?php echo $p->suppliersbillno;?></span>
										<span>Address:<?php echo $p->address;?></span>
										
										<?php //$discount=$p->discount;?>
										</li>
									</ul>
								</div>
								<div class="grid_6 invoice_from">
									<ul>
										<li>
										<strong><span>Voucher No:<?php echo $p->pname;?></span></strong>
										<span>Challan No:<?php echo $p->challanno;?></span>									
										<span>Purchase Date:<?php echo $p->purchasedate;?></span>
										<?php $sub_total=$p->old_sub_total;?>
										<?php $discount=$p->old_discount;?>
										<?php $gross_total=$p->old_gross_total;?>
										<?php $others_exp=$p->old_others_exp;?>
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
											<td class="center"><?php echo $p->old_quantity;?></td>
											<td class="center"><?php echo $p->mname;?></td>
											<td class="center"><?php echo $p->old_rate;?></td>
											<td class="center"><?php echo $p->old_amount;?></td>
										</tr>
											<?php 
										}
										?>
									
										<?php //$gross_total=$p->gross_total;?>
										<?php //$others_exp=$p->others_exp;?>
										<tr>
											<td colspan="4" class="grand_total">
												Sub Total:
											</td>
											<td>
												<?php echo $sub_total;?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Discount:
											</td>
											<td>
												<?php echo $discount;?>
											</td>
										</tr>
										<tr>
											<td colspan="4" class="grand_total">
												Others Exp.:
											</td>
											<td>
												<?php echo $others_exp;?>
											</td>
										</tr>
										
										<tr>
											<td colspan="4" class="grand_total">
												Gross Total:
											</td>
											<td>
												<?php echo $gross_total;?>
											</td>
										</tr>
										</tbody>
										</table>
									</div>
									<p class="amount_word">
										Amounts in word: <span><?php echo App\Http\Controllers\Common\CommonController::convertNumberToWord($gross_total); ?> Taka Only</span>
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

