
@extends('masterpage')

@section('content')


<div id="content">
		<div class="grid_container">
<div class="grid_12">
	<div class="widget_wrap">
		<div class="widget_top">
			<span class="h_icon blocks_images"></span>
				<h6>Damaged Quantity</h6>
		</div>
		<div class="widget_content">
			<button onclick="download_csv()">Download</button>
				<table class="display data_tbl">
					<thead>
						<tr>
							
							<th>
								Name
							</th>
							<th>
								Serial No.
							</th>
							<th>
								Created Date
							</th>
							<th>
								Damaged Date
							</th>
							<th>
								Feedback
							</th>
							
						</tr>
					</thead>
					<tbody>
						<?php foreach($value as $v){?>
					<tr class="gradeC">
						<td class="center">
							<?php echo $v->name;?>
						</td>
						<td class="center">
							<?php echo $v->slno;?>
						</td>
						<td class="center">
						  <?php $date=date_create($v->created_at); echo date_format($date,"Y-m-d");?>
						</td>
						<td class="center">
						  <?php $date=date_create($v->updated_at); echo date_format($date,"Y-m-d");?>
						</td>
						<td class="center">
							<?php echo $v->feedback;?>
						</td>
							
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
				url: ''+id+'/download_csv_damaged',
				data: {
						_token: $_token,
						search_value: search_value
					},
				success: function (response) {
					if(response.download_csv_data.length > 0){
						var table_data = [];
						table_data.push(["Name","Serial No","Created Date","Damaged Date"]);

						for(var i=0; i<response.download_csv_data.length; i++){
							table_data.push([JSON.stringify(response.item.name), JSON.stringify(response.download_csv_data[i].slno), JSON.stringify(response.download_csv_data[i].created_at),JSON.stringify(response.download_csv_data[i].updated_at)]);
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
</script>	
@endsection



