@extends('masterpage')

@section('content')


<div class="widget_top">
	<span class="h_icon list_image"></span>
		<h6>Contra Voucher Entry</h6>
</div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Cash Contra</a></li>
    <li><a href="#tabs-2">Bank Contra</a></li>
    <li><a href="#tabs-3">Bank To Bank Contra</a></li>
  </ul>
  <div id="tabs-1">
	<div class="widget_top">
		<h3>Cash Contra</h3>
	</div>
	<div class="widget_content" >
						
	<form action="contravoucher/cashregister" method="post"  class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="userid" value="1">
	<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
						
						
	<ul>
						
		<li>
			<div class="form_grid_6">		
			<label for="name" class="field_title">Cash A/C<span class="req">*</span></label>
			<div class="form_input">
					<?php $cash = DB::table('coa')->where('id',1)->get(); ?>
				<select class="chzn-select" style=" width:100%" name="cashid" > 
					<?php 
					foreach($cash as $gg){
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
				</select>
			</div>
			</div>
			<div class="form_grid_6">
				<label class="field_title">Debit:<span class="req">*</span></label>
				<div class="form_input">
					<input name="amount" id="bankdebit" oninput="debitToCredit(this.value,'select_bank','bankcredit')" type="text" tabindex="1" required  />
				</div>
			</div>
			<div class="clear"></div>
		</li>
		<li>
			<div class="form_grid_6">
				<label class="field_title">Bank A/C:<span class="req">*</span></label>
				<div class="form_input">
				<select class="chzn-select" id="select_bank" onchange="set_bank(this.value)" style=" width:100%" name="baccid" > 
				<?php 

				echo "<option value='-1' Selected>Select</option>";
				foreach($bankaccount as $gg){
						
				echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
				}?>
				</select>
				</div>
			</div>
			<div class="form_grid_6">
				<label class="field_title">Credit:<span class="req">*</span></label>
				<div class="form_input">
					<input name=""  id="bankcredit" type="text" tabindex="1" readonly="" />
				</div>
			</div>
			<div class="clear"></div>
		</li>
							
		<li>
			<div class="form_grid_6">
				<label class="field_title">Cheque No:</label>
				<div class="form_input">
					<input name="checkno" type="text" tabindex="1"/>
				</div>
			</div>
			<div class="form_grid_6">
				<label class="field_title">Issue Date:<span class="req">*</span></label>
				<div class="form_input">
					<input name="vdate" type="text" tabindex="1" class="datepicker" required />
				</div>
			</div>
			<div class="clear"></div>
		</li>
							
		<li>
			<div class="form_grid_1" >
				<div class="form_input" >
					<button type="submit" class="btn_small btn_blue"><span>Save</span></button>
				</div>
			</div>
			<div class="form_grid_11" >
									
			</div>
			<div class="clear"></div>
		</li>
	</ul>
							
	</form>
						
	</div>
				
</div>
 <div id="tabs-2">
	<div class="widget_top">
		<h3>Bank Contra</h3>
	</div>
	<div class="widget_content" >
						
		<form action="contravoucher/bankregister" method="post"  class="form_container left_label">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="userid" value="1">
		<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">				
						
		<ul>
						
			<li>
				<div class="form_grid_6">
					<label class="field_title">Bank A/C:<span class="req">*</span></label>
					<div class="form_input">
					<select class="chzn-select" style=" width:100%" name="baccid" > 
					<?php 

					echo "<option Selected>Select</option>";
					foreach($bankaccount as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
					}?>
					</select>
					</div>
				</div>
				<div class="form_grid_6">
					<label class="field_title">Debit:<span class="req">*</span></label>
					<div class="form_input">
					<input name="amount" id="cashdebit"  oninput="debitToCredit(this.value,'select_cash','cashcredit')" type="text" tabindex="1" required  />
					</div>
									
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="form_grid_6">		
					<label for="name" class="field_title">Cash A/C<span class="req">*</span></label>
					<div class="form_input">
					<?php $cash = DB::table('coa')->where('id',1)->get(); ?>
					<select class="chzn-select" id='select_cash' onchange="set_cash(this.value)" style=" width:100%" name="cashid" > 
					<?php 
					echo "<option value='-1' Selected>Select</option>";
					foreach($cash as $gg){
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
					</select>
					</div>
				</div>

				<div class="form_grid_6">
					<label class="field_title">Credit:<span class="req">*</span></label>
					<div class="form_input">
					<input name=""  id="cashcredit" type="text" tabindex="1" readonly="" />
					</div>
				</div>
				<div class="clear"></div>
			</li>
							
			<li>
				<div class="form_grid_6">
					<label class="field_title">Cheque No:</label>
					<div class="form_input">
						<input name="checkno" type="text" tabindex="1" />
					</div>
				</div>
				<div class="form_grid_6">
					<label class="field_title">Issue Date:<span class="req">*</span></label>
					<div class="form_input">
					<input name="vdate" type="text" tabindex="1" class="datepicker" required />
					</div>
				</div>
				<div class="clear"></div>
			</li>
							
			<li>
				<div class="form_grid_1" >
					<div class="form_input" >
						<button type="submit" class="btn_small btn_blue"><span>Save</span></button>
					</div>
				</div>
				<div class="form_grid_11" >
									
				</div>
				<div class="clear"></div>
			</li>
		</ul>
							
		</form>
						
	</div>
</div>
<div id="tabs-3">
	<div class="widget_top">
						
		<h3>Bank To Bank Contra</h3>
	</div>
	<div class="widget_content" >
						
		<form action="contravoucher/banktobankregister" method="post"  class="form_container left_label">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="userid" value="1">
		<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">					
						
		<ul>
						
			<li>
				<div class="form_grid_6">
					<label class="field_title">Bank A/C:<span class="req">*</span></label>
					<div class="form_input">
					<select class="chzn-select" style=" width:100%" name="baccid" > 
					<?php 

					echo "<option Selected>Select</option>";
					foreach($bankaccount as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
					}?>
					</select>
					</div>
				</div>
				<div class="form_grid_6">
					<label class="field_title">Debit:<span class="req">*</span></label>
					<div class="form_input">
					<input name="amount" id="banckToBanckDebit"  oninput="debitToCredit(this.value,'select_bankToBank','banckToBanckCredit')" type="text" tabindex="1" required  />
					</div>
									
				</div>
				<div class="clear"></div>
				</li>
				<li>
					<div class="form_grid_6">
						<label class="field_title">Bank A/C:<span class="req">*</span></label>
						<div class="form_input">
						<select class="chzn-select" id='select_bankToBank' onchange="set_bankToBank(this.value)" style=" width:100%" name="cashid" > 
						<?php 

						echo "<option value='-1' Selected>Select</option>";
						foreach($bankaccount as $gg){
						
						echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
						}?>
						</select>
						</div>
					</div>

					<div class="form_grid_6">
					<label class="field_title">Credit:<span class="req">*</span></label>
						<div class="form_input">
							<input name=""  id="banckToBanckCredit" type="text" tabindex="1" readonly="" />
						</div>
					</div>
					<div class="clear"></div>
				</li>
							
				<li>
				<div class="form_grid_6">
					<label class="field_title">Cheque No:</label>
					<div class="form_input">
						<input name="checkno" type="text" tabindex="1" />
					</div>
				</div>
				<div class="form_grid_6">
					<label class="field_title">Issue Date:<span class="req">*</span></label>
					<div class="form_input">
					<input name="vdate" type="text" tabindex="1" class="datepicker" required />
					</div>
				</div>
				<div class="clear"></div>
				</li>
							
					<li>
						<div class="form_grid_1" >
							<div class="form_input" >
								<button type="submit" class="btn_small btn_blue"><span>Save</span></button>
							</div>
							</div>
							<div class="form_grid_11" >
									
							</div>
							<div class="clear"></div>
					</li>
		</ul>
							
		</form>
						
	</div>
</div>

</div>	
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  
  	function set_bank(selected_value){
  		if(selected_value != -1){
	    	$('#bankcredit').val($('#bankdebit').val());
		} else {
			$('#bankcredit').val('');
		}
	}
   	function set_cash(selected_value){
   		if(selected_value != -1){
	    	$('#cashcredit').val($('#cashdebit').val());
	    } else {
	    	$('#cashcredit').val('');
	    }	
	}	

	function set_bankToBank(selected_value){
		if(selected_value != -1){
			$('#banckToBanckCredit').val($('#banckToBanckDebit').val());
		} else {
			$('#banckToBanckCredit').val('');
		}	
	}


	function debitToCredit(input_debit, selected_id, debit_id){
		if($('#'+selected_id+'').val() != -1){
			$('#'+debit_id+'').val(input_debit);
		}
	}
     function approved_to_unapproved(sales_id) {	            			
		    //alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "voucher/save_unapproved",
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
		   // alert(sales_id);	
            $_token = "{{ csrf_token() }}";
            $.ajax({
	            type: "POST",
	            url: "voucher/save_approved",
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

<div id="content">
<div class="grid_container">
<div class="grid_12">
					
					
						
						<table class="display data_tbl_search">
						<thead>
						<tr>
						
    <?php //print_r($voucher);?>    
	
	<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Contra Voucher</h6>
					</div>
					<div class="widget_content">
							<th>
								ID
							</th>
							<th>
								 Voucher No.
							</th>
							<th>
								 Voucher Date
							</th>
							<th>
								 Type
							</th>
							<th>
								 Status
							</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach($voucher as $v){?>
						<tr class="gradeA">
							<td class="center">
							   <?php echo $v->id;?>	
							</td>
							<td class="center">
								<?php echo $v->vnno;?>
							</td>	
							<td class="center">
								<?php echo $v->vdate;?>
							</td>
							<td class="center">
							    <div class="btn_30_orange">
									<!--<a href="/IMS/voucher/pdf/<?php echo $v->id;?>/<?php echo $v->type;?>">-->
									<a href="IMS/contravoucher/pdf/<?php echo $v->id;?>/<?php echo $v->type;?>/<?php echo $v->status;?>/"><span class="icon doc_access_co"></span><span class="btn_link">Print</span></a>
								</div>
							</td>
							<td class="center">
							<div class="btn_30_dark">
						     @if($v->vstatus == 1)							
								<button class="btn_small btn_blue" onclick="approved_to_unapproved('{{$v->id}}')">Approved</button>							
							@elseif($v->vstatus == 0)
								<button  class="btn_small btn_orange"  onclick="unapproved_to_approved('{{$v->id}}')">Unapproved</button>	
							@endif	
							
						</div> 
							</td>
						</tr>
						<?php }?>
						</tbody>
						
						</table>
						</div>
						
						

</div></div></div>

 
	

	
	
@endsection

