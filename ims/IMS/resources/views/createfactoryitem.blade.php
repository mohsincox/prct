
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Factory Inventory</h6>
					</div>
					<div class="widget_content">
						<h3>Create Factory Inventory</h3>
						
						<form action="register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
							    <li>
								<div class="form_grid_12">
									<label class="field_title">Item Category<span class="req">*</span> </label>
									<div class="form_input">
										<select class="chzn-select" onchange="select_category(this.value)" style=" width:100%" name="itemsid" required > 
											<option value="-1">Select Category</option>
                                    		@foreach($item_sub_groups as $item_sub_group)
                                     			<option value="{{ $item_sub_group->id }}">{{ $item_sub_group->name }}</option>
                         					@endforeach	
                                		</select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Item Model<span class="req">*</span> </label>
									<div class="form_input">
										<select id='select_item' style=" width:100%" name="itemsid" required > 
											<option value="-1">Select Item</option>                                    
                                		</select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Quantity<span class="req">*</span></label>
									<div class="form_input">
										<input name="quantity" type="text" tabindex="1" required />
									</div>
								</div>
								</li>
								
								
								<li>
								<div class="form_grid_12">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Submit</span></button>
									</div>
								</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
<script>	
	function select_category(category_id){
		$('#select_item').empty();
		if(category_id != '-1'){
			$_token = "{{ csrf_token() }}";
	        $.ajax({
	            type: "POST",
	            url: "get_item_by_category",
	            data: {
					_token: $_token,
					category_id: category_id
					},
	            success: function (response) {
	            	if(response.length > 0){
	            		$('#select_item').append("<option value='-1' selected=''>Select Item</option>");
	            		for(var i=0; i<response.length; i++){
	            			$('#select_item').append("<option value='"+response[i].id+"'>"+response[i].name+"</option>");
	            		}
	            	} else{
	            		$('#select_item').append("<option value='-1' selected=''>No Item</option>");
	            	}	
	            }	
	        });
	    } else {
	    	$('#select_item').append("<option value='-1' selected=''>Select Item</option>");
	    }
	}
</script>	
@endsection

