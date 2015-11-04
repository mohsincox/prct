@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Return / Replace</h6>
					</div>
					<div class="widget_content">

					</div>
						<div id="#tabs-1">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					
					<div class="widget_content">
						<div class=" page_content">
							<div class="invoice_container">

<div class="clear"></div>

							<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table class="display data_tbl">
					<thead>
						<tr>
							<th>
								Invoice No
							</th>
							<th>
							    Item Name
							</th>
							<th>
								Serial No
							</th>
							<th>
								Sales Date
							</th>
							<th>
							    return Status
							</th>
							<th>
							    Feedback
							</th>
							
						</tr>
					</thead>
					<tbody>
						<?php foreach($value as $items){?>
					<tr class="gradeA">
					    <td class="center">
							<?php echo $items->ivnno;?>
						</td>
						<td class="center">
							<?php echo $items->name;?>
						</td>
						<td class="center">
							<?php echo $items->slno;?>
						</td>
						<td class="center">
						  <?php $date=date_create($items->created_at); echo date_format($date,"Y-m-d");?>
						</td>	
						<td class="center" id='{{$items->id."_status"}}'>
							@if($items->status == 1)
								<button style="background-color: green;" onclick="active_to_inactive('{{$items->id}}')">Active</button>
							@elseif($items->status == 0)
								<button id='{{$items->id."_status_button"}}' style="background-color: red;" onclick="inactive_to_active('{{$items->id}}')">Inactive</button>	
							@endif
						</td>
						<td class="center" id='{{$items->id."_feedback"}}'><?php echo $items->feedback;?></td>
						
						</tr>
						<?php }?>
		
					</tbody>
						
				</table>
								</div>
								
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
				</div>
</div></div></div>

@endsection

