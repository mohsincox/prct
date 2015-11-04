@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Physical Sales</h6>
					</div>
					<div class="widget_content">
						<h3>Sales Order</h3>
						<form action="/IMS/physicalsales/register" id="form_submit" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">	
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="name" value="<?php echo 'I-'.rand();?>">
							<ul>
								<li>
								<div class="form_grid_4">
									<label class="field_title"> Sales Date:<span class="req">*</span></label>
									<div class="form_input">
										<input onchange="check_sales_date(this.value)" id="sales_date" value="<?php if(Session::has('physcl_sale_sales_date')){ echo Session::get('physcl_sale_sales_date'); } ?>" name="sales_date" type="text" tabindex="1" class="datepicker" required />
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title"> Customer Name:<span class="req">*</span></label>
									<div class="form_input">
										 <?php $customers=DB::table('customers')->get(); ?>  
										<select  name="customersid" id="customer" style="width:100%" class="chzn-select" tabindex="2" /> 
											<?php
												$exist_supplier = 0;
												if(Session::has('physcl_sale_customer')){
													$supplier_id = (int)Session::get('physcl_sale_customer');
													$exist_supplier = 1;
												}
												if (Session::has('new_customer_id')){
													$supplier_id = (int)Session::get('new_customer_id');
													$exist_supplier = 1;
												}
											foreach($customers as $s){
												if($exist_supplier == 1){ 
													if($supplier_id == $s->id){ ?>
													<option value="<?php echo $s->id; ?>" selected=""><?php echo $s->name; ?></option>
												<?php
													} else { ?>
													 <option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
												<?php	
													}
												} else{ ?>
												<option value="<?php echo $s->id; ?>"><?php echo $s->name; ?></option>
											<?php 	
												}
											}?>
										</select>
										</div>
										</div>
										<div class="form_grid_2">
										<div class="btn_30_dark">
											<a href="#" onclick="pop()"><span class="icon add_co"></span><span class="btn_link">New Customer</span></a>
										</div>	
									</div>
								
								<div class="clear"></div>
								</li>
							</ul>
							<h3>Select Item:<span class="req">*</span></h3>
							<ul>
							
							   <li>
									<div class="form_grid_4">
										<label class="field_title">Category:<span class="req">*</span></label>
										<div class="form_input">
											<select class="chzn-select" onchange="select_category(this.value)">  
												<option value="-1" selected="">Select Category</option>
											<?php  $itemssubgroup= DB::table('itemssubgroup')->where('itemgroupid',1)->get();
												foreach ($itemssubgroup as $i){
											?>
												<option value="<?php echo $i->id;?>"><?php echo $i->name;?></option>
											<?php } ?>	
										</select>	
										</div>
									</div>
									<div class="form_grid_4">
										<label class="field_title"> Select Item:<span class="req">*</span></label>
										<div class="form_input">
											<select id='select_item'  onchange= "createInvoice()"> 
												<option value="-1" selected="">Select Item</option>
											</select>	
										</div>
									</div>
									<div class="form_grid_4">
										
									</div>
									<div class="clear"></div>
								</li>
								
							</ul>
							<ul>
							<div class="form_grid_12">
							     <table class="display">
								    <thead>
										<tr>
											<th>Item</th>
											<th>Serial No</th>
											<th>Quantity</th>
											<th>Messurement Unit</th>
											<th>Rate</th>
											<th>Amount</th>
											<th>Delete</th>
										</tr>
									</thead>
									<tbody id='table'>
									<?php 
										if (Session::has('physcl_sale_invoice_info'))
										{ 
										if(count(Session::get('physcl_sale_invoice_info')) > 0){ 
										foreach (Session::get('physcl_sale_invoice_info') as $invoice) {
										?>
										<tr id='<?php echo $invoice->id; ?>'>
											<td><input type="hidden" name="itemid[]" value='<?php echo $invoice->id; ?>' readonly><input  name="itemcode[]" id="<?php echo $invoice->id.'_item'; ?>" value='<?php echo $invoice->item; ?>' readonly></td>
											<td><div id="<?php echo $invoice->id.'_select_product'; ?>" style="height:50px;width:200px; overflow: auto; border: 1px solid red"></div></td>
											<?php if($invoice->serial_no_exist == 1){ ?>
												<td><input type="number" id="<?php echo $invoice->id.'_quantity'; ?>" name="qnt[]" value='<?php echo $invoice->quantity; ?>' readonly></td>
											<?php } else { ?>
												<td><input type="number" oninput="input_quantity(this.value, '<?php echo $invoice->id; ?>')" id="<?php echo $invoice->id.'_quantity'; ?>" name="qnt[]" value='<?php echo $invoice->quantity; ?>' ></td>
											<?php } ?>
											<td><input  type="hidden" id="'<?php echo $invoice->id.'_measurement_unit'; ?>'" name="measurementid[]" value="'<?php echo $invoice->measurement_unit; ?>'"><input id="<?php echo $invoice->id.'_measurement_unit_name'; ?>" name="measurement_unit_name[]" value="<?php echo $invoice->measurement_unit_name; ?>" readonly></td>
											<!-- <td><select class="form-control" onchange="session_function()" id="<?php echo $invoice->id.'_measurement_unit'; ?>" name="measurementid[]"> <option value="-1">Select Measurement Unit</option><?php  $measurement_unit = DB::table("measurementunit")->get(); foreach ($measurement_unit as $i){ ?><option value="<?php echo $i->id;?>" <?php if($i->id == $invoice->measurement_unit){ ?> selected="" <?php } ?> > <?php echo $i->name;?></option><?php } ?></select></td> -->
											<td><input oninput="input_rate(this.value, '<?php echo $invoice->id; ?>')" type="number" name="rate[]" value="<?php echo $invoice->rate; ?>"></td>
											<td><input name="amount[]" value="<?php echo $invoice->amount; ?>" readonly></td>
											<td><a href="#" onclick="delete_item('<?php echo $invoice->id; ?>')">Delete</a></td>
										</tr>
										
									<?php } ?>	
                                        <tr>
											<td></td><td></td><td></td><td></td><td><strong>Sub Total:</strong></td><td><input type="number" id="sub_total" name="sub_total" value="<?php echo Session::get('physcl_sale_sub_total'); ?>" readonly=""></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td></td><td><strong>Discount:</strong></td><td><input type="number" id="discount" oninput="input_discount(this.value)" name="discount" value="<?php echo Session::get('physcl_sale_discount'); ?>"></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td></td><td><strong>Gross Total:</strong></td><td><input type="number" id="gross_total" name="gross_total" value="<?php echo Session::get('physcl_sale_gross_total'); ?>" readonly=""></td><td></td>
										</tr>
									<?php }
									}
									?>
									</tbody>
								 </table>
							</div>
							</ul>
							
							<ul>
							
							   <li>
								<div class="form_grid_6">
									<div class="form_input">
									    <button id="cancel_item" class="btn_small btn_orange"><span>Cancel</span></button>
									</div>
								</div>
								<div class="form_grid_6">
									<div class="form_input">
									    <button type="submit"  class="btn_small btn_blue"><span>Save</span></button>
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
							</ul>
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Serial No </label>
									<div class="form_input">
										<textarea rows="10" cols="30" id="serial_no" name='serial_no'><?php if(Session::has('physcl_sale_serial_no')){ echo Session::get('physcl_sale_serial_no'); } ?></textarea>
									</div>
								</div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		
		
	</div>	

<script>

	$(function() {
		<?php 
		if (Session::has('physcl_sale_invoice_info')) { 
			if(count(Session::get('physcl_sale_invoice_info')) > 0){ 
				foreach (Session::get('physcl_sale_invoice_info') as $invoice) { ?>
					$_token = "{{ csrf_token() }}";
			        $.ajax({
			            type: "POST",
			            url: "get_item_info",
			            data: {
							_token: $_token,
							item_id: <?php echo $invoice->id; ?>
							},
			            success: function (response) {
			            	if(response.item_info.sstatus == 1){
				            	if(response.product_slno.length > 0){
				            		for(var i=0; i<response.product_slno.length; i++){
				            			var name = "product_slno_"+response.item_info.id+"[]";
				            			var checked_product = '-1';
				            			<?php 
				            			foreach ($invoice->product_slno as $product_slno) { ?>
				            				var pro_slno = '<?php echo $product_slno; ?>';
				            				if(pro_slno == response.product_slno[i].f_id){
				            					checked_product = pro_slno;
				            				}
									<?php
				            			}
				            		?>		
				            			if(checked_product == '-1'){
				            				$('#'+response.item_info.id+'_select_product').append('<input onchange="select_product('+response.item_info.id+')" type="checkbox" class="'+response.item_info.id+'_checkbox" value="'+response.product_slno[i].f_id+'" name="'+name+'" ><label for="'+response.product_slno[i].f_id+'">'+response.product_slno[i].slno+'</label><br>');
				            			} else{
				            				$('#'+response.item_info.id+'_select_product').append('<input onchange="select_product('+response.item_info.id+')" type="checkbox" class="'+response.item_info.id+'_checkbox" checked value="'+response.product_slno[i].f_id+'" name="'+name+'" ><label for="'+response.product_slno[i].f_id+'">'+response.product_slno[i].slno+'</label><br>');
				            			}
				            		}

				            	} else{
				            		$('#'+response.item_info.id+'_select_product').append("No product");
				            	}
				            } else {
				            	$('#'+response.item_info.id+'_select_product').append("No serial number");
				            }	
			            }
			        });    	

<?php			
					
				}
			}		
		}
?>
	});

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
	    } else{
	    	$('#select_item').append("<option value='-1' selected=''>Select Item</option>");
	    }
	}     

function createInvoice(){
		var item_id = $('#select_item').val();

		if(item_id != '-1'){
			$_token = "{{ csrf_token() }}";
	        $.ajax({
	            type: "POST",
	            url: "get_item_info",
	            data: {
					_token: $_token,
					item_id: item_id
					},
	            success: function (response) {
	            	var check_exist = 0;
	            	var total_amount = 0;
	            	var discount = 0;
	            	if($('#table tr').length > 0){
	            		discount = Number($('#discount').val());
	    				others_exp = Number($('#others_exp').val());
	            		$('#table tr').each(function() {
	            			if($(this).find('td:eq(0)').html() == ''){
	            				$(this).remove();
	            			}	
							else if($(this).attr('id') == response.item_info.id){
								check_exist = 1;
							}

							if($(this).find('td:eq(0)').html() != ''){
								total_amount = total_amount + Number($(this).find('td:eq(5) input').val());
							}
						});
	            	}
	            	total_amount = round(total_amount, 2);
	            	if(check_exist == 0){
	            		if(response.item_info.sstatus == 1){
							$('#table').append( '<tr id='+ response.item_info.id +'><td><input  type="hidden" name="itemid[]" id="item_list" value='+ response.item_info.id+' readonly><input  id="'+response.item_info.id+'_item" name="itemcode[]" value="'+ response.item_info.name+'" readonly></td><td><div id="'+response.item_info.id+'_select_product" style="height:50px;width:200px; overflow: auto; border: 1px solid red"></div></td><td><input type="number" step="any" id="'+response.item_info.id+'_quantity" name="qnt[]" value="0" readonly></td><td><input  type="hidden" id="'+response.item_info.id+'_measurement_unit" name="measurementid[]" value="'+ response.item_info.mesid+'"><input id="'+response.item_info.id+'_measurement_unit_name" name="measurement_unit_name[]" value="'+ response.item_info.measurement_unit_name+'" readonly></td><td><input oninput="input_rate(this.value, '+ response.item_info.id +')" type="number" step="any" name="rate[]" value=""></td><td><input name="amount[]" value="0" readonly></td><td><a href="#" onclick="delete_item('+ response.item_info.id +')">Delete</a></td></tr>' );
	            		} else{
	            			$('#table').append( '<tr id='+ response.item_info.id +'><td><input  type="hidden" name="itemid[]" id="item_list" value='+ response.item_info.id+' readonly><input  id="'+response.item_info.id+'_item" name="itemcode[]" value="'+ response.item_info.name+'" readonly></td><td><div id="'+response.item_info.id+'_select_product" style="height:50px;width:200px; overflow: auto; border: 1px solid red"></div></td><td><input type="number" oninput="input_quantity(this.value, '+ response.item_info.id +')" step="any" id="'+response.item_info.id+'_quantity" name="qnt[]" value=""></td><td><input  type="hidden" id="'+response.item_info.id+'_measurement_unit" name="measurementid[]" value="'+ response.item_info.mesid+'"><input id="'+response.item_info.id+'_measurement_unit_name" name="measurement_unit_name[]" value="'+ response.item_info.measurement_unit_name+'" readonly></td><td><input oninput="input_rate(this.value, '+ response.item_info.id +')" type="number" step="any" name="rate[]" value=""></td><td><input name="amount[]" value="0" readonly></td><td><a href="#" onclick="delete_item('+ response.item_info.id +')">Delete</a></td></tr>' );
	            		}
	            		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            		
	            		if(response.item_info.sstatus == 1){
		            		if(response.product_slno.length > 0){
			            		for(var i=0; i<response.product_slno.length; i++){
			            			var name = "product_slno_"+response.item_info.id+"[]";
			            			$('#'+response.item_info.id+'_select_product').append('<input onchange="select_product('+response.item_info.id+')" type="checkbox" class="'+response.item_info.id+'_checkbox" value="'+response.product_slno[i].f_id+'" name="'+name+'" ><label for="'+response.product_slno[i].f_id+'">'+response.product_slno[i].slno+'</label><br>');
			            		}
			            	} else{
			            		$('#'+response.item_info.id+'_select_product').append("No product");
			            	}
		            	} else {
		            		$('#'+response.item_info.id+'_select_product').append("No serial number");
		            	}	 
	            	} else {
	            		var gross_total = total_amount - discount;
	            		gross_total = round(gross_total, 2);
	            		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
	            	}	
	            }
	        });    
			session_function();
		}	
		
	}

	function select_product(id){
		var itemid_arr = $('input[name="itemid[]"]').map(function () {
							    return this.value; 
							}).get();
		$('#serial_no').empty();
		for(var i = 0; i<itemid_arr.length;i++){
			if($('#'+itemid_arr[i]+'_select_product').text() != 'No serial number'){
				var quantity = 0;
				$("input[name='product_slno_"+itemid_arr[i]+"[]']:checked:enabled").each( function () {
	       			$('#serial_no').append($("label[for='"+ $(this).val() +"']").text()+ ', ');
	       			quantity++;
	   			});
	   			$('#'+itemid_arr[i]+'_quantity').val(quantity);
			}	
		}
		calculation(id);
		session_function();
		// if($(that).is(':checked')) {
		// 	 alert($(that).val());
		// 	 var slno = $("label[for='"+id+"']").text();
		// 	 $('#serial_no').append(slno+',');
		// } else{
		// 	alert('remove');
		// }
	}

	function input_quantity(value, id) {
		var quantity = Number(value);
			if(quantity > 0){
				$_token = "{{ csrf_token() }}";
				$.ajax({
					type: "POST",
					url: "get_factory_item",
					data: {
							_token: $_token,
							item_id: id,
						},
					success: function (response) {
						if(quantity <= response){
							var rate = Number($('#'+id+'').find('td:eq(4) input').val());
							var current_amount = quantity*rate;
							current_amount = round(current_amount, 2);

							$('#'+id+'').find('td:eq(5) input').val(current_amount);
							var total_amount = 0;
						    if($('#table tr').length > 0){
						    	var discount = Number($('#discount').val());
						        $('#table tr').each(function() {
							        if($(this).find('td:eq(0)').html() == ''){
							            $(this).remove();
							        }	
									if($(this).find('td:eq(0)').html() != ''){
										total_amount = total_amount + Number($(this).find('td:eq(5) input').val());
									}
								});
								total_amount = round(total_amount, 2);
								var gross_total = total_amount - discount;
								gross_total = round(gross_total, 2);
								$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
								$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
								$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
					    	}
					    	
					    	// set_serial_no();
					    	session_function();
						} else {
							alert(response+' products are available.');
							$('#'+id+'').find('td:eq(2) input').val('0');
							calculation();
							// set_serial_no();
							session_function();
						}
					}
				});
			} else {
				// set_serial_no();
				session_function();
			}
		
	}

	function set_serial_no(){
		var itemid_arr = $('input[name="itemid[]"]').map(function () {
							    return this.value; 
							}).get();
		$('#serial_no').empty();
		for(var i = 0; i<itemid_arr.length;i++){
			if($('#'+itemid_arr[i]+'_select_product').text() != 'No serial number'){
				var quantity = 0;
				$("input[name='product_slno_"+itemid_arr[i]+"[]']:checked:enabled").each( function () {
	       			$('#serial_no').append($("label[for='"+ $(this).val() +"']").text()+ ', ');
	       			quantity++;
	   			});
				$('#'+itemid_arr[i]+'_quantity').val(quantity);
			}
		}
		// calculation(id);
		session_function();



		// $('#serial_no').val('');
		// var serial_no = '';
		// if($('#table tr').length > 0){
		// 	$('#table tr').each(function() {
		// 		if($(this).find('td:eq(0)').html() != ''){
		// 			var item_id = $(this).attr('id');
		// 			var quantity = Number($(this).find('td:eq(2) input').val());
		// 			if(quantity > 0){
		// 				$_token = "{{ csrf_token() }}";
		// 				$.ajax({
		// 					type: "POST",
		// 					url: "get_factory_item",
		// 					data: {
		// 							_token: $_token,
		// 							item_id: item_id,
		// 						},
		// 					success: function (response) {
		// 						for(var i=0; i < quantity; i++){
		// 							serial_no = serial_no + response[i].slno+',';
		// 						}
		// 						var current_serial_no = serial_no;
		// 						$('#serial_no').val(current_serial_no.slice(0, -1));
		// 						session_function();
		// 					}
		// 				});		
		// 			}
		// 		}
		// 	});
		// }

		
	}

	function calculation(id){
		var rate = Number($('#'+id+'').find('td:eq(4) input').val());
		var quantity = Number($('#'+id+'').find('td:eq(2) input').val());
		var current_amount = quantity*rate;
		current_amount = round(current_amount, 2);

		$('#'+id+'').find('td:eq(5) input').val(current_amount);
		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(5) input').val());
				}
			});
			total_amount = round(total_amount, 2);
			var gross_total = total_amount - discount;
			gross_total = round(gross_total, 2);
			$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}

    	session_function();
	}

	function input_rate(value, id) {
		var rate = Number(value);
		var quantity = Number($('#'+id+'').find('td:eq(2) input').val());
		var current_amount = quantity*rate;
		current_amount = round(current_amount, 2);

		$('#'+id+'').find('td:eq(5) input').val(current_amount);
		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(5) input').val());
				}
			});
			total_amount = round(total_amount, 2);
			var gross_total = total_amount - discount;
			gross_total = round(gross_total, 2);
			$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    		$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}

    	session_function();
		
	}

	function input_discount(discount){
		discount = Number(discount);
		var sub_total = Number($('#sub_total').val());
		// var others_exp = Number($("#others_exp").val());
		var gross_total = sub_total - discount;
		gross_total = round(gross_total, 2);
		$('#gross_total').val(gross_total);
		session_function();
	}

	// function input_others_exp(others_exp){
	// 	others_exp = Number(others_exp);
	// 	var sub_total = Number($('#sub_total').val());
	// 	var discount = Number($("#discount").val());
	// 	var gross_total = sub_total - discount + others_exp;
	// 	$('#gross_total').val(gross_total);
	// 	session_function();
	// }

	function delete_item(id){
		$('#'+id+'').remove();

		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	    	// var others_exp = Number($('#others_exp').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(5) input').val());
				}
			});
			if($('#table tr').length > 0){
				var gross_total = total_amount - discount;
				$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    			$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
				$('#table').append( '<tr><td></td><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    		}
    	}
    	set_serial_no();
    	// session_function();
		
	}

	function session_function() {
		var session = [];
		 if($('#table tr').length > 0){
		    $('#table tr').each(function() {
				if($(this).find('td:eq(0)').html() != ''){
					var product_slno = [];
					$("input[name='product_slno_"+this.id+"[]']:checked:enabled").each( function () {
		       			product_slno.push(
		       				$(this).val()
		       			);	
		   			});
					
					var serial_no_exist = 1;
					if($('#'+this.id+'_select_product').text() == 'No serial number'){
						serial_no_exist = 0;
					}	
					session.push({
						id: this.id,
						item: $('#'+this.id+'_item').val(),
						product_slno: product_slno,
						serial_no_exist: serial_no_exist,
						quantity: $(this).find('td:eq(2) input').val(),
						measurement_unit: Number($('#'+this.id+'_measurement_unit').val()),
						measurement_unit_name: $('#'+this.id+'_measurement_unit_name').val(),
						rate: $(this).find('td:eq(4) input').val(),
						amount: $(this).find('td:eq(5) input').val()
					});
				}
			});
		}	

			$_token = "{{ csrf_token() }}";
			$.ajax({
				type: "POST",
				url: "session_invoice",
				data: {
						_token: $_token,
						invoice_info: JSON.stringify(session),
						sub_total: Number($('#sub_total').val()),
						discount: Number($('#discount').val()),
						// others_exp: Number($('#others_exp').val()),
						gross_total: Number($('#gross_total').val()),
						serial_no: $('#serial_no').val(),
						sales_date: $('#sales_date').val(),
						customer: $('#customer').val(),
					},
				success: function (response) {
					// console.log(response);
				    //alert('OK');
				}
			});
	}

	function round(value, exp) {
	  if (typeof exp === 'undefined' || +exp === 0)
	    return Math.round(value);

	  value = +value;
	  exp  = +exp;

	  if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
	    return NaN;

	  value = value.toString().split('e');
	  value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

	  value = value.toString().split('e');
	  return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
	}

	function check_sales_date(sales_date){
		sales_date = new Date(sales_date).toDateString("yyyy-MM-dd");
		var current_date = new Date().toDateString("yyyy-MM-dd");
		if(sales_date != current_date){
			alert('Please select current date.');
			$('#sales_date').val("");
		} else {
			session_function();
		}
	}

	// function check_credit_limit(){
	// 	// return false;
	// 	var customer_id = $('#customer').val();
	// 	$_token = "{{ csrf_token() }}";
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "get_customer_info",
	// 		data: {
	// 			_token: $_token,
	// 			customer_id: customer_id
	// 		},
	// 		success: function (response) {
	// 			var credit_limit = Number(response.creditlimit);
	// 			var previous_due = Number(response.openbalance);
	// 			var gross_total = $('#gross_total').val();
	// 			if((gross_total + previous_due) > credit_limit){
	// 				alert('Your due is greater than your credit limit. Please paid your due amount.');
	// 				return false;
	// 			}
	// 			// return false;
	// 		}
	// 	});		
		
	// }

</script>
 
 <script>
	$( "#save_button" ).click(function( event ) {
	  //alert('dsdsdsds');	
	  event.preventDefault();
	  var customer_id = $('#customer').val();
		$_token = "{{ csrf_token() }}";
		$.ajax({
			type: "POST",
			url: "get_customer_info",
			data: {
				_token: $_token,
				customer_id: customer_id
			},
			success: function (response) {
				var credit_limit = Number(response.creditlimit);
				var previous_due = Number(response.lastdue);
				var gross_total = Number($('#gross_total').val());
				//alert(''+previous_due);
				if((gross_total + previous_due) > credit_limit){
					alert('Your due is greater than your credit limit('+credit_limit+'). Please paid your due amount.');
				} else {
					$('#form_submit').submit();
				}
			}
		});	
	});

	$( "#cancel_item" ).click(function( event ) {
	  event.preventDefault();
	  $('#table').remove();
		$_token = "{{ csrf_token() }}";
		$.ajax({
			type: "POST",
			url: "invoice_remove",
			data: {
				_token: $_token,
			},
			success: function (response) {
				location.reload();
			}
		}); 
	});

	function pop() {
    	bootbox.dialog({
               closeButton: false,
	            message: '<div class="grid_container">'+ 
	            '<div class="grid_12 full_block">'+
				'<div class="widget_wrap">'+
				'<div class="widget_top">'+
					'<span class="h_icon list_image">'+'</span>'+
						'<h6>Add New Customer</h6>'+
				'</div>'+
	            	'<div class="widget_content">' +'<br>'+
						'<div class="form_input">'+
							'<strong>&nbsp;Customer Code:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="customer_code" id="customer_code" type="text" tabindex="1" placeholder="Code"  value="<?php echo"C-".(rand()); ?>" readonly />'+
						'</div>'+
								
						'<div class="clear">'+
						'</div>'+
						'<br>'+
									
						'<div class="form_input">'+
							'<strong>&nbsp;Customer Name:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="customer_name" id="customer_name" type="text" tabindex="1" placeholder="Customer Name" />'+
						'</div>'+
						
						'<div class="clear">'+
						'</div>'+
						'<br>'+

						'<div class="form_input">'+
							'<strong>&nbsp;Opening Balance:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="opening_balance"  id="opening_balance" type="number" step="any" tabindex="1"  value="0.00"/>'+
						'</div>'+
						
						'<div class="clear">'+
						'</div>'+
						'<br>'+
									
						'<div class="form_input">'+
							'<strong>&nbsp;Customer Address:</strong>&nbsp;<input name="preaddress" id="preaddress" type="text" tabindex="1" placeholder="Present Address" />'+
						'</div>'+
						'</div>'+'<br>'+
						'</div>'+'<br>'+
						'</div>'+
						'</div>',	
                buttons: {
                    success: {
                        label: "Save",
                        className: "btn_small btn_blue",
                        callback: function () {
                            var customer_code = $('#customer_code').val();
                            var customer_name = $('#customer_name').val();
                            var opening_balance = $('#opening_balance').val();
                            var preaddress = $('#preaddress').val();
	                            $_token = "{{ csrf_token() }}";
						        $.ajax({
						            type: "POST",
						            url: "customer_register",
						            data: {
						                  _token: $_token,
						                  customer_code: customer_code,
						                  customer_name: customer_name,
						                  opening_balance: opening_balance,
						                  preaddress: preaddress
						                  },
						            success: function (response) {
						              if(response == 1){
						                window.location.reload();
						              }
						            } 
						        }); 
                        }
                    },
                    danger: {
				      label: "cancel",
				      className: "btn_small btn_orange",
				      callback: function() {
				        window.location.reload();
				      }
				    }
                }
            }
        );
    }
</script>
 
@endsection

