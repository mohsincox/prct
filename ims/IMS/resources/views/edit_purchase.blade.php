@extends('masterpage')

@section('content')

<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Purchase Order</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Purchase Order</h3>
						<form action="/IMS/purchase/edit" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="purchase_id" value="<?php echo $purchase_info[0]->id; ?>">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Purchase Date:<span class="req">*</span></label>
									<div class="form_input">
										<input id="purchase_date" value="<?php echo $purchase_info[0]->purchasedate; ?>" name="purchase_date" type="text" tabindex="1"  class="datepicker">
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Bill Date:<span class="req">*</span> </label>
									<div class="form_input">
									    <input id="supplier_bill_date" value="<?php echo $purchase_info[0]->suppliersbilldate ?>" name="supplier_bill_date" type="text" class="datepicker"/>
									</div>
								</div>
								<span class="clear"></span>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Supplier Bill No:<span class="req">*</span></label>
									<div class="form_input">
										<input  type="text" id="supplier_bill_no" value="<?php echo $purchase_info[0]->suppliersbillno; ?>" name="supplier_bill_no"/>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title"> Challan No:<span class="req">*</span></label>
									<div class="form_input">
										<input  type="text" name="supplier_challan_no" value="<?php echo $purchase_info[0]->challanno; ?>" />
									</div>
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
											<!-- <th>Delete</th> -->
										</tr>
									</thead>
									<tbody id='table'>
									<?php 
										foreach ($invoice_info as $invoice) {
										?>
										<tr id='<?php echo $invoice->id; ?>'>
											<td><input type="hidden" name="purchase_detail_id[]" value='<?php echo $invoice->id; ?>' readonly><input  name="itemcode[]" id="<?php echo $invoice->id.'_item'; ?>" value='<?php echo $invoice->iname; ?>' readonly></td>
											<td><input oninput="input_quantity(this.value, '<?php echo $invoice->id; ?>')" type="number" step="any" name="qnt[]" value='<?php echo $invoice->quantity; ?>'></td>
											<td><input  type="hidden" id="'<?php echo $invoice->id.'_measurement_unit'; ?>'" name="measurementid[]" value="'<?php echo $invoice->mname; ?>'"><input id="<?php echo $invoice->id.'_measurement_unit_name'; ?>" name="measurement_unit_name[]" value="<?php echo $invoice->mname; ?>" readonly></td>
											<td><input oninput="input_rate(this.value, '<?php echo $invoice->id; ?>')" type="number" step="any" name="rate[]" value="<?php echo $invoice->rate; ?>"></td>
											<td><input name="amount[]" value="<?php echo $invoice->amount; ?>" readonly></td>
										</tr>
										
									<?php } ?>		
										<tr>
											<td></td><td></td><td></td><td><strong>Sub Total:</strong></td><td><input type="number" id="sub_total" name="sub_total" value="<?php echo $purchase_info[0]->sub_total; ?>" readonly=""></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Discount:</strong></td><td><input type="number" id="discount" oninput="input_discount(this.value)" name="discount" value="<?php echo $purchase_info[0]->discount; ?>"></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Others Exp:</strong></td><td><input type="number" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="<?php echo $purchase_info[0]->others_exp; ?>"></td><td></td>
										</tr>
										<tr>
											<td></td><td></td><td></td><td><strong>Gross Total:</strong></td><td><input type="number" id="gross_total" name="gross_total" value="<?php echo $purchase_info[0]->gross_total; ?>" readonly=""></td><td></td>
										</tr>
									</tbody>
								 </table>
							</div>
							</ul>
							
							<ul>
							
							   <li>
								<div class="form_grid_6">
									<div class="form_input">
									    <a href="purchase"><button id="cancel_item" class="btn_small btn_orange"><span>Cancel</span></button></a>
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
	</div>
<script>
	function input_quantity(value, id) {
		var quantity = Number(value);
		var rate = Number($('#'+id+'').find('td:eq(3) input').val());
		var current_amount = quantity*rate;

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
	        var gross_total = total_amount - discount  + others_exp;
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}
	}

	function input_rate(value, id) {
		var rate = Number(value);
		var quantity = Number($('#'+id+'').find('td:eq(1) input').val());
		var current_amount = quantity*rate;

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
			var gross_total = total_amount - discount  + others_exp;
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Sub Total</strong></td><td><input id="sub_total" name="sub_total" value="'+total_amount+'" readonly></td><td></td></tr>');
    		$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Discount</strong></td><td><input type="number" step="any" id="discount" oninput="input_discount(this.value)" name="discount" value="'+discount+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Others Exp</strong></td><td><input type="number" step="any" id="others_exp" oninput="input_others_exp(this.value)" name="others_exp" value="'+others_exp+'"></td><td></td></tr>');
			$('#table').append( '<tr><td></td><td></td><td></td><td><strong>Gross Total</strong></td><td><input type="number" step="any" id="gross_total" name="gross_total" value="'+gross_total+'" readonly></td><td></td></tr>');
    	}
	}

	function input_discount(discount){
		discount = Number(discount);
		var sub_total = Number($('#sub_total').val());
		var others_exp = Number($("#others_exp").val());
		var gross_total = sub_total - discount + others_exp;
		$('#gross_total').val(gross_total);
	}

	function input_others_exp(others_exp){
		others_exp = Number(others_exp);
		var sub_total = Number($('#sub_total').val());
		var discount = Number($("#discount").val());
		var gross_total = sub_total - discount + others_exp;
		$('#gross_total').val(gross_total);
	}
</script>	

@endsection							