@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Purchase</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="purchase/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Purchase</span></a>
					</div>
						
						<table class="display data_tbl_search">
						<thead>
						<tr>
							
							<th>Serial No.</th>
							<th>Supplier Name</th>					
							<th>Purchase Bill No.</th>
							<!--<th>Supplier Bill No.</th>-->
							<th>Supplier Bill Date</th>
							<th>View</th>
							 @if(Auth::id()==1)
							<th>Status</th>
						      @endif
						</tr>
						</thead>
						<tbody>
						<?php $i=1;?>
						<?php foreach($purchase as $p){?>
						<tr class="gradeX">
							
								<td class="center"><?php echo $i;?></td>
								<td class="center"><?php echo $p->sname;?></td>
								<td class="center"><?php echo $p->pname;?></td>
								<!--<td class="center"><?php //echo $p->suppliersbillno;?></td>-->
								<td class="center"><?php echo $p->suppliersbilldate;?></td>
								
							<td class="center">
								<div class="btn_30_blue">
									<a href="/IMS/purchase/view/<?php echo $p->id;?>"><span class="icon doc_page_co"></span><span class="btn_link">View</span></a>
								</div>
								<div class="btn_30_orange">
									<a href="/IMS/purchase/popdf/<?php echo $p->id;?>"><span class="icon doc_access_co"></span><span class="btn_link">Print</span></a>
								</div>
						
							 </td>
							  @if(Auth::id()==1)
							 <td class="center">
							
							 <div class="btn_30_dark">
							     @if($p->status == 1)
									<button class="btn_small btn_blue" onclick="approved_to_unapproved('{{$p->id}}')">Approved</button></div>
								<div class="btn_30_dark">
									<a href="/IMS/purchase/pdf/<?php echo $p->id;?>" target="_blank">PRINT</a></div>
								@elseif($p->status == 0)
								<div class="btn_30_dark">
									<button  class="btn_small btn_orange"  onclick="unapproved_to_approved('{{$p->id}}')">Unapproved</button></div>
								<div class="btn_30_dark">
									<button  class="btn_small btn_blue"  onclick="cancel_status('{{$p->id}}')">Cancel</button></div>
									<div class="btn_30_dark">
									<a href="/IMS/purchase/edit/<?php echo $p->id;?>">EDIT</span></a></div>
								
									
								@endif	
								</div>
							  	
							 </td>
							  @endif
						</tr>
						<?php $i++;}?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div>
<script>
function cancel_status(sales_id) {	            			
		    //alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "purchase/cancel_status",
	            data: {
					_token: $_token,
					sales_id: sales_id,
					},
	            success: function (response) {
	            	if(response == 0){
	            		alert("Not found this item.");
	            	} else if(response == 1){
	            		location.reload();
	            	}
	           	}
	        });			
}

function approved_to_unapproved(sales_id) {	            			
		    //alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "purchase/save_unapproved",
	            data: {
					_token: $_token,
					sales_id: sales_id,
					},
	            success: function (response) {
	            	if(response == 0){
	            		alert("Not found this item.");
	            	} else if(response == 1){
	            		location.reload();
	            	}
	           	}
	        });			
}

function unapproved_to_approved(sales_id) {	            			
		    //alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "purchase/save_approved",
	            data: {
					_token: $_token,
					sales_id: sales_id,
					},
	            success: function (response) {
	            	if(response == 0){
	            		alert("Not found this item.");
	            	} else if(response == 1){
	            		location.reload();
	            	}
	           	}
	        });			
}
</script>

@endsection

