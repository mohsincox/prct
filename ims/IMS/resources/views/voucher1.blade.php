@extends('masterpage')

@section('content')


	
	<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Voucher Entry</h6>
					</div>
	<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Bank Payment Voucher</a></li>
    <li><a href="#tabs-2">Payment Voucher</a></li>
    <li><a href="#tabs-3">Bank Receive Voucher</a></li>
	<li><a href="#tabs-4">Receive Voucher</a></li>
    <li><a href="#tabs-5">Contra Voucher</a></li>
 
  </ul>
  <div id="tabs-1">
	<div class="widget_top">
						
						<h3>Bank Payment Voucher</h3>
					</div>
						<div class="widget_content" >
						
							<form action="voucher/register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
						<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
						
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label for="name" class="field_title">Bank Account</label>
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
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="amount" id="bank_amount" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Suppliers:</label>
									<div class="form_input">
										<select class="chzn-select" onchange="set_amount()" style=" width:100%" name="sid" > 
				  <?php 

					echo "<option Selected>Select</option>";
					foreach($s as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Amount:</label>
									<div class="form_input">
										<input name="supplieramount" id="supplier_amount" type="text" tabindex="1"  />
									</div>
								</div>
								<div class="clear"></div>
								</li>
							
								<li>
								<div class="form_grid_6">
									<label class="field_title">Cheque No:</label>
									<div class="form_input">
										<input name="checkno" type="text" tabindex="1"  />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Issue Date:</label>
									<div class="form_input">
										<input name="vdate" type="text" tabindex="1" class="datepicker" />
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
						
						<h3>Payment Voucher</h3>
					</div>
						<div class="widget_content" >
						
							<form action="voucher/registerp" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
						<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
						
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label for="name" class="field_title">Cash</label>
				<div class="form_input">
				<?php $cash = DB::table('coa')->where('id',1)->get(); ?>
                  <select class="chzn-select" style=" width:100%" name="baccid" > 
				  <?php 

					
					foreach($cash as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
				</div>
			  </div>
								<div class="form_grid_6">
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="amount" id="bank_amountpv" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Suppliers:</label>
									<div class="form_input">
										<select class="chzn-select" onchange="set_amountpv()" style=" width:100%" name="sid" > 
				  <?php 

					echo "<option Selected>Select</option>";
					foreach($s as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Amount:</label>
									<div class="form_input">
										<input name="samount" id="supplier_amountpv" type="text" tabindex="1" readonly="" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
							
								<li>
							
								
								<div class="form_grid_6">
									<label class="field_title">Issue Date:</label>
									<div class="form_input">
										<input name="vdate" type="text" tabindex="1" class="datepicker" />
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
						
						<h3>Bank Receive Voucher</h3>
					</div>
						<div class="widget_content" >
						
							<form action="voucher/customers/register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
						<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
						
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label for="name" class="field_title">Bank Account</label>
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
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="amount" id="bank_amountbrv" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Customers:</label>
									<div class="form_input">
										<select class="chzn-select" onchange="set_amountbrv()" style=" width:100%" name="cid" > 
				  <?php 

					echo "<option Selected>Select</option>";
					foreach($c as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Amount:</label>
									<div class="form_input">
										<input name="camount" id="supplier_amountbrv" type="text" tabindex="1"  />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
							
								<li>
								<div class="form_grid_6">
									<label class="field_title">Cheque No:</label>
									<div class="form_input">
										<input name="checkno" type="text" tabindex="1"  />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Issue Date:</label>
									<div class="form_input">
										<input name="vdate" type="text" tabindex="1" class="datepicker" />
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
  <div id="tabs-4">
  <div class="widget_top">
						
						<h3>Receive Voucher</h3>
					</div>
						<div class="widget_content" >
						
							<form action="voucher/receive/register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
						<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label for="name" class="field_title">Cash</label>
				<div class="form_input">
				<?php $cash = DB::table('coa')->where('id',1)->get(); ?>
                  <select class="chzn-select" style=" width:100%" name="baccid" > 
				  <?php 

					
					foreach($cash as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
				</div>
			  </div>
								<div class="form_grid_6">
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="amount" id="bank_amountrv" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Customers:</label>
									<div class="form_input">
										<select class="chzn-select" onchange="set_amountrv()" style=" width:100%" name="cid" > 
				  <?php 

					echo "<option Selected>Select</option>";
					foreach($c as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Amount:</label>
									<div class="form_input">
										<input name="camount" id="supplier_amountrv" type="text" tabindex="1"  />
									</div>
								</div>
								<div class="clear"></div>
								</li>
							
								<li>
							
								
								<div class="form_grid_6">
									<label class="field_title">Issue Date:</label>
									<div class="form_input">
										<input name="vdate" type="text" tabindex="1" class="datepicker" />
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
  <div id="tabs-5">
  <div class="widget_top">
						
						<h3>Contra Voucher</h3>
					</div>
					<div class="widget_content" >
					<form action="voucher/contra/register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="1">
						<input type="hidden" name="vnno" value="<?php echo"v-".(rand()); ?>">
					<ul>
  <li>
  
		<div class="form_grid_6">		
			<label for="name" class="field_title">Cash</label>
				<div class="form_input">
				<?php $cash = DB::table('coa')->where('id',1)->get(); ?>
                  <select class="chzn-select" style=" width:100%" name="baccid" > 
				  <?php 

					
					foreach($cash as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."</option>";
					}?>
                    </select>
				</div>
			  </div>
			  <div class="form_grid_6">
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="amount" id="bank_amountcv" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								<li>
											<div class="form_grid_6">		
			<label for="name" class="field_title">Bank Account</label>
				<div class="form_input">
                  <select class="chzn-select" onchange="set_amountcv()" style=" width:100%" name="baccid" > 
				  <?php 

					echo "<option Selected>Select</option>";
					foreach($bankaccount as $gg){
						
					echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
					}?>
                    </select>
				</div>
			  </div>
								<div class="form_grid_6">
									<label class="field_title">Amount: </label>
									<div class="form_input">
										<input name="bamount" id="supplier_amountcv" type="text" tabindex="1"  />
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
										<li>
								<div class="form_grid_6">
									<label class="field_title">Cheque No:</label>
									<div class="form_input">
										<input name="checkno" type="text" tabindex="1"  />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Issue Date:</label>
									<div class="form_input">
										<input name="vdate" type="text" tabindex="1" class="datepicker" />
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
  </script>

<div class="grid_12">

	<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Voucher</h6>
					</div>
					<div class="widget_content" style="width:900px;">
						<button class="btn_small btn_blue"><a href="#"><span>Print Pdf</span></a></button>
						
						<table class="display data_tbl_search">
						<thead>
						<tr>
						
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
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($voucher as $v){?>
						<tr class="gradeX">
							<td>
								 <?php echo $v->id;?>
							</td>
							<td>
								<?php echo $v->vnno;?>
							</td>
							<td>
								<?php echo $v->vdate;?>
							</td>
							<td>
								<a href="/IMS/voucher/pdf/<?php echo $v->type;?>"><?php echo $v->type;?></a>
							</td>
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
						</div>
						
						

</div>
<script>
	function set_amount(){
		$('#supplier_amount').val($('#bank_amount').val());
	}
	function set_amountpv(){
		$('#supplier_amountpv').val($('#bank_amountpv').val());
	}
	function set_amountbrv(){
		$('#supplier_amountbrv').val($('#bank_amountbrv').val());
	}
	function set_amountrv(){
		$('#supplier_amountrv').val($('#bank_amountrv').val());
	}
	function set_amountcv(){
		$('#supplier_amountcv').val($('#bank_amountcv').val());
	}
</script>
 
	

	
	
@endsection

