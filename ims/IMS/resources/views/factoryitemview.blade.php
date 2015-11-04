@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Factory Inventory</h6>
					</div>
					<div class="widget_content">
						<div class="form_grid_12">
							<button class="btn_small btn_blue" onclick="download_csv()">Download</button>									</div>
						</div>

						<table class="display data_tbl csv">
						<thead>
						<tr>
						    <th>Item Name</th>
							<th>Serial No</th>
							<th>Date</th>
							<th class="remove_data">Status</th>
							<th class="remove_data">Feedback</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($factoryitemview as $items){?>
						<tr class="gradeA">
						<td class="center"><?php echo $items->itemsname;?></td>
						<td class="center"><?php echo $items->slno;?></td>
						<!-- <td class="center"><?php $date=date_create($items->created_at); echo date_format($date,"d/m/Y");?></td> -->
						<td class="center"><?php $date=date_create($items->created_at); echo date_format($date,"Y-m-d");?></td>
						<td class="center remove_data" id='{{$items->id."_status"}}'>
							@if($items->status == 1)
								<button style="background-color: green;" onclick="active_to_inactive('{{$items->id}}')">Active</button>
							@elseif($items->status == 0)
								<button id='{{$items->id."_status_button"}}' style="background-color: red;" onclick="inactive_to_active('{{$items->id}}')">Inactive</button>	
							@endif
						</td>
						<td class="center remove_data" id='{{$items->id."_feedback"}}'><?php echo $items->feedback;?></td>
						
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

<script>
	function download_csv() {
		var url = window.location.pathname;
		var url = url.split('/');
		var url_length = url.length;
		url_length = url_length - 1; 
		var id = url[url_length];

		var search_value = $('#DataTables_Table_0_filter').find('input').val();	
		$_token = "{{ csrf_token() }}";
			$.ajax({
				type: "POST",
				url: ''+id+'/download_csv',
				data: {
						_token: $_token,
						search_value: search_value
					},
				success: function (response) {
					if(response.download_csv_data.length > 0){
						var table_data = [];
						table_data.push(["Item Name","Serial No","Date"]);

						for(var i=0; i<response.download_csv_data.length; i++){
							table_data.push([JSON.stringify(response.item.name), JSON.stringify(response.download_csv_data[i].slno), JSON.stringify(response.download_csv_data[i].created_at)]);
						}

						var csvContent = "data:text/csv;charset=utf-8,";
				        var dataString
				        table_data.forEach(function (infoArray, index) {

				            dataString = infoArray.join(",");
				            csvContent += index < table_data.length ? dataString + "\n" : dataString;

				        });
				        var encodedUri = encodeURI(csvContent);
				        var link = document.createElement("a");
				        link.setAttribute("href", encodedUri);
				        link.setAttribute("download", "backup.csv");

				        link.click();
					}else {
						alert('There is no data to download csv file');
					}
				}
			});
	}	

	function active_to_inactive(factory_item_id) {
		$('#'+factory_item_id+'_status').empty();
		$('#'+factory_item_id+'_status').append('<button id="'+factory_item_id+'_status_button" style="background-color: red;" onclick="inactive_to_active('+factory_item_id+')">Inactive</button>');

		$('#'+factory_item_id+'_feedback').empty();
		$('#'+factory_item_id+'_feedback').append('<input type="text" onfocusout="save_feedback(this.value,'+factory_item_id+')">');
	}

	function save_feedback(feedback, factory_item_id){
		if($('#'+factory_item_id+'_status_button').text() == 'Inactive'){
			$_token = "{{ csrf_token() }}";
	        $.ajax({
	            type: "POST",
	            url: "<?php echo $item_id; ?>/save_feedback",
	            data: {
					_token: $_token,
					factory_item_id: factory_item_id,
					status: 0,
					feedback: feedback
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
	}

	function inactive_to_active(factory_item_id) {
		if($('#'+factory_item_id+'_status_button').text() == 'Inactive'){
			$_token = "{{ csrf_token() }}";
	        $.ajax({
	            type: "POST",
	            url: "<?php echo $item_id; ?>/remove_feedback",
	            data: {
					_token: $_token,
					factory_item_id: factory_item_id,
					status: 1,
					},
	            success: function (response) {
	            	if(response == 0){
	            		alert("Not found this item.");
	            	} else{
	            		$('#'+factory_item_id+'_status').empty();
						$('#'+factory_item_id+'_feedback').empty();
						$('#'+factory_item_id+'_status').append('<button style="background-color: green;" onclick="active_to_inactive('+factory_item_id+')">Active</button>');
	            	}
	           	}
	        });
		}
	}	
</script>

@endsection

