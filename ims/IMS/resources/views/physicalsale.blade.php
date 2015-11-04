
@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Physical Sales</h6>
					</div>
					<div class="widget_content">
					<div class="btn_30_dark">
						<a href="physicalsales/addnew"><span class="icon add_co"></span><span class="btn_link">Add New Sales</span></a>
					</div>
					<?php 
					  //print_r($physicalsale); die();
					?>	
						<table class="wtbl_list">
						<thead>
						<tr>
							<th>Serial No.</th>
							<th>Customer Name</th>
							<th>Sales Bill No.</th>
							<th>Sales Date</th>
							<th>View</th>
							@if(Auth::id()==1)
							<th>Status</th>
						    @endif
							<th>Pre Dues</th>
							<th>Pre B</th>
						</tr>
						</thead>
						<tbody>
						<?php $i=1;?>
						<?php foreach($physicalsale as $p){?>
						<tr class="gradeC">
							<td><?php echo $i;?></td>
							<td>
							<?php 
							     // echo $p->customerid;
								  $customers = DB::table('customers')->where('id',$p->customerid)->first();
								  echo $customers->name;
							?>
							</td>
							<td><?php echo $p->name;?></td>
							<td><?php echo $p->salesdate;?></td>
							<td><div class="btn_30_blue">
								<a href="physicalsales/view/<?php echo $p->id;?>"><span class="icon doc_page_co"></span><span class="btn_link">View</span></a>
							</div>
						</td>		
						 @if(Auth::id()==1)
						<td>
						<div class="btn_30_dark">
						    @if($p->status == 1)
							
						    <button class="btn_small btn_blue" onclick="approved_to_unapproved('{{$p->id}}')">Approved</button>
							<a href="physicalsales/printtoken/<?php echo $p->id;?>">Print Token</a>	
							<a href="physicalsales/print/<?php echo $p->id;?>">Bill</a>
							<a href="physicalsales/challan/<?php echo $p->id;?>">Challan</a>
							@elseif($p->status == 0)
								<button  class="btn_small btn_orange"  onclick="unapproved_to_approved('{{$p->id}}')">Unapproved</button>
								<button  class="btn_small btn_blue"  onclick="cancel_status('{{$p->id}}')">Cancel</button>	
							@endif	
						</div>
						</td>
						@endif
						<td><?php echo $p->previousdue;?></td>
						<td><?php echo $p->presentbalance;?></td>
						</tr>
						<?php $i++;}?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

<script>

function cancel_status(sales_id) {	            			
		    //alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "physicalsales/cancel_status",
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
	            url: "physicalsales/save_unapproved",
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
	            url: "physicalsales/save_approved",
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





