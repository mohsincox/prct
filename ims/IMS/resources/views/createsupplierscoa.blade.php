@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Supplier Account Information</h6>
					</div>
					<div class="widget_content">
						<h3>Create New Supplier Account</h3>
						<form action="/IMS/coa/suppliers/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>				
								<li>
									<div class="form_grid_6">		
									<label for="name" class="field_title">Supplier Name</label>
								<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="name" onchange="fetch_select(this.value)" > 
									<?php 

									echo "<option value='' Selected>Select</option>";
									foreach($suppliers as $gg){
									if($gg->coastatus==0){
									echo "<option value='".$gg->id."+".$gg->name."' >".$gg->name."</option>";
									}}?>
									</select>
								</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" id="code" value="<?php echo"S-".(rand()); ?>" readonly="" />
									</div>
								</div>
								
			
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Description</label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" />
									</div>
								</div>
								<div class="form_grid_6">
									<label for="name" class="field_title">Tax Rate</label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="taxrateid">  
									<?php 
										echo "<option Selected>Select</option>";
										foreach($taxrate as $c){
										echo "<option value=".$c->id.">".$c->name."</option>";
									}?>
									</select>
									</div>
								</div>
								
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label for="name" class="field_title">Increase To<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="increasetypeid" required >  
									<?php 
										echo "<option value='' Selected>Select</option>";
										foreach($increasetype as $c){
										echo "<option value=".$c->id.">".$c->name."</option>";
									}?>
									</select>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label for="name" class="field_title">Coa Type<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="coatypeid" required >  
									<?php 
										foreach($coatype as $c){
										if($c->id==9){	
										    echo "<option value=".$c->id." Selected>".$c->name."</option>";
										}
									}?>
									</select>
									</div>
									
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_4">
									
								</div>
								<div class="form_grid_4">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Save</span></button>
										<button type="reset" class="btn_small btn_orange"><span>Reset</span></button>
									</div>
								</div>
								<div class="form_grid_4">
									
								</div>
								<div class="clear"></div>
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
	<script type="text/javascript">
		function fetch_select(supplier_id)
		{
			var arr = supplier_id.split("+");
			//alert(arr[0])
			$_token = "{{ csrf_token() }}";
			$.ajax({
				type: 'post',
				url: 'get_supplier_code',
				data: {
					_token: $_token, 	 
					supplier_id: arr[0]
				},
				success: function (response) {
					$('#code').val(response.code);
				}
			});
		}

	</script>
@endsection
