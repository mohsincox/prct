@extends('masterpage')

@section('content')


	
	<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Bank Book</h6>
					</div>
	<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Bank Book</a></li>

   
 
  </ul>
  <div id="tabs-1">
	<div class="widget_top">					
		<h3>Bank Book</h3>
	</div>
	<div class="widget_content">
	<form action="generalledger/bankbook" method="post" role="form" class="form_container left_label">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<ul>
		
		<li>
			<div class="form_grid_12">
			<label class="field_title">Bank Account<span class="req">*</span></label>
			<div class="form_input">
			     <select class="chzn-select"  name="baccid" required> 
				  <?php 

					echo "<option value='' Selected>Select</option>";
					foreach($bankaccount as $gg){
						if($gg->coastatus==1){
					echo "<option value='".$gg->id."' >".$gg->name."(".$gg->bankname.")"."</option>";
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

