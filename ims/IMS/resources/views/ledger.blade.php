@extends('masterpage')

@section('content')


	
	<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>General Ledger</h6>
					</div>
	<div id="tabs">
  <ul>
   
    <li><a href="#tabs-2">Customers Ledger</a></li>
    <li><a href="#tabs-3">Suppliers Ledger</a></li>
	<li><a href="#tabs-4">Sub Ledger</a></li>
   
 
  </ul>

  <div id="tabs-2">
     <div class="widget_top">					
		<h3>Customers</h3>
	</div>
	
    <div class="widget_content">
							<form action="customersledger/fromtoday" method="post" role="form" class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="userid" value="{{ Auth::id() }}">
	<ul>
		
		<li>
											<div class="form_grid_12">
								<label class="field_title">Customers<span class="req">*</span></label>
									<div class="form_input">
									  <select class="chzn-select" onchange="set_amountbrv()"  name="cid" required > 
										  <?php 

											echo "<option value='' Selected>Select</option>";
											echo "<option value='0'>ALL</option>";
											foreach($c as $gg){
												if($gg->coastatus==1){
											echo "<option value='".$gg->id."' >".$gg->name."</option>";
												}}?>
											</select>
									</div>
								</div>
		</li>
		
	    <li>
			<div class="form_grid_12">
			<label class="field_title">Today</label>
			<div class="form_input">
			     <button type="submit"  class="btn_small btn_blue" name="submit" value="today"><span>Submit</span></button>
			</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">From Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="fromdate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">To Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="todate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
				<div class="form_input">
					<button type="submit" class="btn_small btn_blue" name="submit" value="fromdate"><span>Submit</span></button>
				</div>
			</div>
		</li>
	</ul>
	</form>
	</div> 	
	
    
  </div>
  <div id="tabs-3">
        <div class="widget_top">					
		<h3>Suppliers</h3>
	</div>
	<div class="widget_content">
							<form action="suppliersledger/fromtoday" method="post" role="form" class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="userid" value="{{ Auth::id() }}">
	<ul>
		
		<li>
											<div class="form_grid_12">
								<label class="field_title">Suppliers<span class="req">*</span></label>
									<div class="form_input">
									  <select class="chzn-select" onchange="set_amountbrv()"  name="sid" required > 
										  <?php 

											echo "<option value='' Selected>Select</option>";
											echo "<option value='0'>ALL</option>";
											foreach($s as $gg){
												if($gg->coastatus==1){
											echo "<option value='".$gg->id."' >".$gg->name."</option>";
												}}?>
											</select>
									</div>
								</div>
		</li>
		
	    <li>
			<div class="form_grid_12">
			<label class="field_title">Today</label>
			<div class="form_input">
			     <button type="submit"  class="btn_small btn_blue" name="submit" value="today"><span>Submit</span></button>
			</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">From Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="fromdate" type="datetime" tabindex="1" class="datepicker" />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">To Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="todate" type="datetime" tabindex="1" class="datepicker" />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
				<div class="form_input">
					<button type="submit" class="btn_small btn_blue" name="submit" value="fromdate"><span>Submit</span></button>
				</div>
			</div>
		</li>
	</ul>
	</form>
	</div> 	
    
   </div>
 <div id="tabs-4">
        <div class="widget_top">					
		<h3>Sub Ledger</h3>
	</div>
	
   
	
    <div class="widget_content">
							<form action="ledgerentry/printpdf" method="post" role="form" class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="userid" value="{{ Auth::id() }}">
	<ul>
		
		<li>
						<div class="form_grid_12">
								<label class="field_title">Sub Ledger<span class="req">*</span></label>
									<div class="form_input">
									    <select data-placeholder="Ledger Option" name="id"  class="chzn-select"  tabindex="15">
											<option value=""></option>
											<?php
											   $coatype = DB::table('coatype')->where('id','<>',8)->where('id','<>',9)->where('id','<>',17)->where('id','<>',18)->where('id','<>',7)->get();
											   foreach($coatype as $ctype){ 
											?>
											<optgroup label="<?php echo $ctype->name;?>">
											 <?php
											   $coa = DB::table('coa')->where('coatypeid',$ctype->id)->where('id','<>',1)->get();
											   foreach($coa as $c){ 
											?>
											<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
											<?php }?>
											<?php }?>
											</optgroup>
										</select>
									  <!--<select  data-placeholder="Your Favorite Football Team" multiple class="chzn-select" onchange="set_amountbrv()"  name="cid" required > 
										 
										 <?php 

											echo "<option value='' Selected>Select</option>";
											
											foreach($coa as $gg){
												if($gg->coatypeid==9){
											echo "<option value='".$gg->id."' >".$gg->name."</option>";
												}}?>
											</select>
										-->	
									</div>
								</div>
		</li>
		
	    <li>
			<div class="form_grid_12">
			<label class="field_title">Today</label>
			<div class="form_input">
			     <button type="submit"  class="btn_small btn_blue" name="submit" value="today"><span>Submit</span></button>
			</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">From Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="fromdate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
			<label class="field_title">To Date<span class="req">*</span></label>
				<div class="form_input">
					<input name="todate" type="datetime" tabindex="1" class="datepicker"  />
				</div>
			</div>
		</li>
		<li>
			<div class="form_grid_12">
				<div class="form_input">
					<button type="submit" class="btn_small btn_blue" name="submit" value="fromdate"><span>Submit</span></button>
				</div>
			</div>
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

<script>
	function set_amount(){
		$('#supplier_amount').val($('#bank_amount').val());
	}
	function c(){
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

