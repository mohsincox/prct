@extends('masterpage')

@section('content')
<div id="content">
	<div class="grid_container">
		<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Daily Collection</h6>
					</div>
					<div class="widget_content">
					
						<div class="btn_30_dark">
									<a href="printcollection" target="_blank"><span class="icon doc_pdf_co"></span><span class="btn_link">Print</span></a>
								</div>
						<table class="wtbl_list">
						<thead>
						<tr>
						    <th>
								 SL no.
							</th>
							<th>
								 Name
							</th>
							<th>
								Present Address
							</th>
							<th>
								 Amount
							</th>
							<th>
								 Voucher No.
							</th>
							<th>
								Bank Amount
							</th>
							<th>
								 Cash
							</th>
							<th>
								 Bkash
							</th>
							<th>
								 SAP
							</th>
							<th>
								 KCS
							</th>
							<th>
								 MBank
							</th>
							<th>
								 Total
							</th>
						</tr>
						</thead>
						
						<tbody>
						        <?php $i=1;?>
								<?php foreach($c as $p){?>
								<tr class="gradeX">
								    <td class="center"><?php echo $i;?></td>
									<td class="center"><?php echo $p->name;?></td>
									<td class="center"><?php echo $p->preaddress;?></td>
									<td class="center"><?php echo $p->amount;?></td>
									<td class="center"><a href="/IMS/voucher/pdf/<?php echo $p->id;?>/<?php echo $p->type;?>" target="_blank"><?php echo $p->vnno;?></a></td>
									<td class="center"><?php if($p->type==3) {echo $p->amount;}?></td>
									<td class="center"><?php if($p->type==4) {echo $p->amount;}?></td>
									<td class="center"><?php if($p->type==6) {echo $p->amount;}?></td>
									<td class="center"><?php if($p->type==7) {echo $p->amount;}?></td>
									<td class="center"><?php if($p->type==8) {echo $p->amount;}?></td>
									<td class="center"><?php if($p->type==9) {echo $p->amount;}?></td>
									<td><?php echo $p->amount;?></td>
								</tr>
								<?php $i++; }?>
								<tr>
								    <td colspan="5" align="right">Total:</td>
									<td><?php foreach($c6 as $c){ $cash=$c->cash; echo $cash; }?></td>
									<td><?php foreach($c7 as $c){ $cash=$c->cash; echo $cash; }?></td>
									<td><?php foreach($bkash as $c){ $cash=$c->cash; echo $cash; }?></td>
									<td><?php foreach($sap as $c){ $cash=$c->cash; echo $cash; }?></td>
									<td><?php foreach($kcs as $c){ $cash=$c->cash; echo $cash; }?></td>
									<td><?php foreach($mbank as $c){ $cash=$c->cash; echo $cash; }?></td>
									
								    <td><?php foreach($c5 as $c){ $cash=$c->cash; echo $cash; }?></td> 
								</tr>
							</tbody>	
						
						
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

