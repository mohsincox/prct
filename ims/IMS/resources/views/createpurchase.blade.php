@extends('masterpage')

@section('content')

<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Purchase Order</h6>
					</div>
					<div class="widget_content">
						<h3>Purchase Order</h3>
						<form action="/IMS/purchase/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="name" value="<?php echo 'P-'.rand();?>">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Purchase Date:<span class="req">*</span></label>
									<div class="form_input">
										<input onchange="session_function()" id="purchase_date" value="<?php if(Session::has('purchase_date')){ echo Session::get('purchase_date'); } ?>" name="purchase_date" type="text" tabindex="1"  class="datepicker">
									</div>
								</div>
								<div class="form_grid_4">
									<label class="field_title"> Supplier Name:<span class="req">*</span></label>
									<div class="form_input">
										 <?php $supplier=DB::table('suppliers')->get(); ?>  
										<select onchange="session_function()" name="supplierid" id="supplier" style=" width:100%" class="chzn-select" tabindex="2" required /> 
											<?php
												$exist_supplier = 0;
												if(Session::has('supplier')){
													$supplier_id = (int)Session::get('supplier');
													$exist_supplier = 1;
												}
												if (Session::has('new_supplier_id')){
													$supplier_id = (int)Session::get('new_supplier_id');
													$exist_supplier = 1;
												}
											foreach($supplier as $s){
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
										</div></div>
										<div class="form_grid_2">
										<div class="btn_30_dark">
											<a href="#" onclick="pop()"><span class="icon add_co"></span><span class="btn_link">New Supplier</span></a>
										</div>
									</div>
								
								<span class="clear"></span>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Supplier Bill No:<span class="req">*</span></label>
									<div class="form_input">
										<input  type="text" oninput="session_function()" id="supplier_bill_no" value="<?php if(Session::has('supplier_bill_no')) echo Session::get('supplier_bill_no'); ?>" name="supplier_bill_no"/>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Bill Date:<span class="req">*</span> </label>
									<div class="form_input">
									    <input onchange="session_function()" id="supplier_bill_date" value="<?php if(Session::has('supplier_bill_date')){ echo Session::get('supplier_bill_date');} ?>" name="supplier_bill_date" type="text" class="datepicker"/>
									</div>
								</div>
								<span class="clear"></span>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Challan No:<span class="req">*</span></label>
									<div class="form_input">
										<input  onchange="session_function()" id="supplier_challan_no" type="text" name="supplier_challan_no" value="<?php if(Session::has('supplier_challan_no')){ echo Session::get('supplier_challan_no');} ?>"/>
									</div>
								</div>
								<div class="form_grid_6">
									
								</div>
								<span class="clear"></span>
								</li>
								
							</ul>
							<ul>
							
							   <li>
									<div class="form_grid_4">
										<label class="field_title">Category:<span class="req">*</span></label>
										<div class="form_input">
											<select class="chzn-select" onchange="select_category(this.value)">  
												<option value="-1" selected="">Select Category</option>
											<?php  $itemssubgroup= DB::table('itemssubgroup')->where('itemgroupid',2)->get();
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
									<span class="clear"></span>
								</li>
								
							</ul>
							<ul>
							<div class="form_grid_12">
							     <table class="display">
								    <thead>
										<tr>
											<th>Item</th>
											<th>Quantity</th>
											<th>Messurement Unit</th>
											<th>Rate</th>
											<th>Amount</th>
											<th>Delete</th>
										</tr>
									</thead>
									<tbody id='table'>
									<?php 
										if (Session::has('invoice_info') && Session::has('sub_total'))
									{
										if(count(Session::get('invoice_info')) > 0){ 
										foreach (Session::get('invoice_info') as $invoice) {
										?>
										<tr id='<?php echo $invoice->id; ?>'>
											<td><input type="hidden" name="itemid[]" value='<?php echo $invoice->id; ?>' readonly><input  name="itemcode[]" id="<?php echo $invoice->id.'_item'; ?>" value='<?php echo $invoice->item; ?>' readonly></td>
											<td><input oninput="input_quantity(this.value, '<?php echo $invoice->id; ?>')" type="number" name="qnt[]" value='<?php echo $invoice->quantity; ?>'></td>
											<td><input  type="hidden" id="'<?php echo $invoice->id.'_measurement_unit'; ?>'" name="measurementid[]" value="'<?php echo $invoice->measurement_unit; ?>'"><input id="<?php echo $invoice->id.'_measurement_unit_name'; ?>" name="measurement_unit_name[]" value="<?php echo $invoice->measurement_unit_name; ?>" readonly></td>
											<!-- <td><select class="form-control" onchange="session_function()" id="<?php echo $invoice->id.'_measurement_unit'; ?>" name="measurementid[]"> <option value="-1">Select Measurement Unit</option><?php  $measurement_unit = DB::table("measurementunit")->get(); foreach ($measurement_unit as $i){ ?><option value="<?php echo $i->id;?>" <?php if($i->id == $invoice->measurement_unit){ ?> selected="" <?php } ?> > <?php echo $i->name;?></option><?php } ?></select></td> -->
											<td><input oninput="input_rate(this.value, '<?php echo $invoice->id; ?>')" type="number" name="rate[]" value="<?php echo $invoice->rate; ?>"></td>
											<td><input name="amount[]" value="<?php echo $invoice->amount; ?>" readonly></td>
											<td><a href="#" onclick="delete_item('<?php echo $invoice->id; ?>')">Delete</a></td>
										</tr>
										
									<?php } ?>		
										<tr>
											<td></td><td></td><td></td><td><strong>Sub Total:</strong></td><td><input type="number" id="sub_total" name="sub_total" value="<?php echo Session::get('sub_total'); ?>" readonly=""></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Discount:</strong></td><td><input type="number" id="discount" oninput="input_discount(this.value)" name="discount" value="<?php echo Session::get('discount'); ?>"></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Others Exp:</strong></td><td><input type="number" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="<?php echo Session::get('others_exp'); ?>"></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Gross Total:</strong></td><td><input type="number" id="gross_total" name="gross_total" value="<?php echo Session::get('gross_total'); ?>" readonly=""></td><td></td>
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
									    <button type="submit" class="btn_small btn_blue"><span>Save</span></button>
									</div>
								</div>
								<span class="clear"></span>
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
	            	var others_exp = 0;
	            	if($('#table tr').length > 0){
	            		discount = Number($('#discount').val());
	    				others_exp = Number($('#others_exp').val());
	            		$('#table tr').each(function() {
	            			if($(this).find('td:eq(0)').html() == ''){
	            				$(this).remove();
	            			}	
							else if($(this).attr('id') == response.id){
								check_exist = 1;
							}

							if($(this).find('td:eq(0)').html() != ''){
								total_amount = total_amount + Number($(this).find('td:eq(4) input').val());
							}
						});
	            	}
	            	total_amount = round(total_amount, 2);
	            	if(check_exist == 0){
						$('#table').append( '<tr id='+ response.id +'><td><input  type="hidden" name="itemid[]" value='+ response.id+' readonly><input  id="'+response.id+'_item" name="itemcode[]" value="'+ response.name+'" readonly></td><td><input oninput="input_quantity(this.value, '+ response.id +')" type="number" step="any" name="qnt[]" value=""></td><td><input  type="hidden" id="'+response.id+'_measurement_unit" name="measurementid[]" value="'+ response.mesid+'"><input id="'+response.id+'_measurement_unit_name" name="measurement_unit_name[]" value="'+ response.measurement_unit_name+'" readonly></td><td><input oninput="input_rate(this.value, '+ response.id +')" type="number" step="any" name="rate[]" value=""></td><td><input name="amount[]" value="0" readonly></td><td><a href="#" onclick="delete_item('+ response.id +')">Delete</a></td></tr>' );	            		
	            		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            	} else {
	            		var gross_total = total_amount - discount  + others_exp;
	            		gross_total = round(gross_total, 2);
	            		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
	            		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
						$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
	            	}	
	            }
	        });    
			
			session_function();
		}	
		
	}

	function input_quantity(value, id) {
		var quantity = Number(value);
		var rate = Number($('#'+id+'').find('td:eq(3) input').val());
		var current_amount = quantity*rate;
		current_amount = round(current_amount, 2);

		$('#'+id+'').find('td:eq(4) input').val(current_amount);
		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	    	var others_exp = Number($('#others_exp').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(4) input').val());
				}
			});
			total_amount = round(total_amount, 2);
	        var gross_total = total_amount - discount  + others_exp;
	        gross_total = round(gross_total, 2);
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}

    	session_function();
	}

	function input_rate(value, id) {
		var rate = Number(value);
		var quantity = Number($('#'+id+'').find('td:eq(1) input').val());
		var current_amount = quantity*rate;
		current_amount = round(current_amount, 2);

		$('#'+id+'').find('td:eq(4) input').val(current_amount);
		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	    	var others_exp = Number($('#others_exp').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(4) input').val());
				}
			});
			total_amount = round(total_amount, 2);
			var gross_total = total_amount - discount  + others_exp;
			gross_total = round(gross_total, 2);
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}

    	session_function();
		
	}

	function input_discount(discount){
		discount = Number(discount);
		var sub_total = Number($('#sub_total').val());
		var others_exp = Number($("#others_exp").val());
		var gross_total = sub_total - discount + others_exp;
		gross_total = round(gross_total, 2);
		$('#gross_total').val(gross_total);
		session_function();
	}

	function input_others_exp(others_exp){
		others_exp = Number(others_exp);
		var sub_total = Number($('#sub_total').val());
		var discount = Number($("#discount").val());
		var gross_total = sub_total - discount + others_exp;
		gross_total = round(gross_total, 2);
		$('#gross_total').val(gross_total);
		session_function();
	}

	function delete_item(id){
		$('#'+id+'').remove();

		var total_amount = 0;
	    if($('#table tr').length > 0){
	    	var discount = Number($('#discount').val());
	    	var others_exp = Number($('#others_exp').val());
	        $('#table tr').each(function() {
		        if($(this).find('td:eq(0)').html() == ''){
		            $(this).remove();
		        }	
				if($(this).find('td:eq(0)').html() != ''){
					total_amount = total_amount + Number($(this).find('td:eq(4) input').val());
				}
			});
			if($('#table tr').length > 0){
				var gross_total = total_amount - discount  + others_exp;
				$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
				$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
				$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    		}
    	}

    	session_function();
		
	}

	function session_function() {
		var session = [];
		 if($('#table tr').length > 0){
		    $('#table tr').each(function() {
				if($(this).find('td:eq(0)').html() != ''){
					session.push({
						id: this.id,
						// item: $(this).find('td:eq(0) input').val(),
						item: $('#'+this.id+'_item').val(),
						quantity: $(this).find('td:eq(1) input').val(),
						measurement_unit: Number($('#'+this.id+'_measurement_unit').val()),
						measurement_unit_name: $('#'+this.id+'_measurement_unit_name').val(),
						rate: $(this).find('td:eq(3) input').val(),
						amount: $(this).find('td:eq(4) input').val()
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
						others_exp: Number($('#others_exp').val()),
						gross_total: Number($('#gross_total').val()),
						purchase_date: $('#purchase_date').val(),
						supplier: $('#supplier').val(),
						supplier_bill_no: $('#supplier_bill_no').val(),
						supplier_bill_date: $('#supplier_bill_date').val(),
						supplier_challan_no: $('#supplier_challan_no').val()
					},
				success: function (response) {
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

	function check_purchase_date(purchase_date){
		purchase_date = new Date(purchase_date).toDateString("yyyy-MM-dd");
		var current_date = new Date().toDateString("yyyy-MM-dd");
		if(purchase_date != current_date){
			alert('Please select current date.');
			$('#purchase_date').val("");
		} else {
			session_function();
		}
	}

	function check_bill_date(bill_date){
		bill_date = new Date(bill_date).toDateString("yyyy-mm-dd");
		var current_date = new Date().toDateString("yyyy-mm-dd");
		if(Date.parse(bill_date) > Date.parse(current_date)){
			alert('Please select a date that is less than or equal from current date.');
			$('#supplier_bill_date').val("");
		} else {
			session_function();
		}
	}

</script>
 
 <script>
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
    	var box = bootbox.dialog({
    		closeButton: false,
 			message:  
 			'<div class="grid_container">'+ 
	            '<div class="grid_12 full_block">'+	
					'<div class="widget_wrap">'+
						'<div class="widget_top">'+
							'<span class="h_icon list_image">'+'</span>'+
							'<h6> Add New Supplier</h6>'+
						'</div>'+
	            			'<div class="widget_content">' +'<br>'+
								'<div class="form_input">'+
									'<strong>&nbsp;Supplier Code:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="supplier_code" id="supplier_code" type="text" tabindex="1" placeholder="Code"  value="<?php echo"S-".(rand()); ?>" readonly />'+
								'</div>'+
								
								'<div class="clear">'+
								'</div>'+
								
									'<br>'+
								'<div class="form_input">'+
									'<strong>&nbsp;Supplier Name:</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="supplier_name" id="supplier_name" type="text" tabindex="1" placeholder="Supplier Name" />'+
								'</div>'+
								'<div class="clear">'+
								'</div>'+'<br>'+
								'<div class="form_input">'+
									'<strong>&nbsp;Supplier Address:</strong>&nbsp;<input name="preaddress" id="preaddress" type="text" tabindex="1" placeholder="present Address" />'+
								'</div>'+
								'<br>'+
							'</div>'+'<br>'+
					'</div>'+'<br>'+
				'</div>'+
			'</div>',	
                buttons: {
                    success: {
                        label: "Save",
                        className: "btn_small btn_blue",
                        callback: function () {
                            var supplier_code = $('#supplier_code').val();
                            var supplier_name = $('#supplier_name').val();
							var preaddress = $('#preaddress').val();
	                            $_token = "{{ csrf_token() }}";
						        $.ajax({
						            type: "POST",
						            url: "supplier_register",
						            data: {
						                  _token: $_token,
						                  supplier_code: supplier_code,
						                  supplier_name: supplier_name,
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
	bootbox.hideAll(box);
    }
</script>      
@endsection

