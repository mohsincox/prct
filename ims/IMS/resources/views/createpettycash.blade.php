@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Ledger Entry</h6>
					</div>
					<div class="widget_content">
						<h3>Create Ledger Entry</h3>
						<form action="/IMS/ledgerentry/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								
							<li>
								<div class="form_grid_12">
									<label class="field_title">Particulars<span class="req">*</span></label>
									<div class="form_input">
									<!--<select class="chzn-select" style=" width:100%" name="particular" required > 
                                    <?php //$acc=DB::table('coa')->where('coatypeid',8)->get(); ?>  
                                     <?php //foreach($acc as $items) { ?>
                         <option value="<?php //echo $items->id; ?>" <?php //echo 'selected'?>><?php //echo $items->name; ?></option>
                                    	   <?php //}?>
                                 
                                </select>-->
								<select data-placeholder="Ledger Option" style="width:100%;" class="chzn-select"  name="particular" tabindex="15">
											<option value=""></option>
											<?php
											   $coatype = DB::table('coatype')->get();
											   foreach($coatype as $ctype){ 
											?>
											<optgroup label="<?php echo $ctype->name;?>">
											 <?php
											   $coa = DB::table('coa')->where('coatypeid',$ctype->id)->get();
											   foreach($coa as $c){ 
											?>
											<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
											<?php }?>
											<?php }?>
											</optgroup>
										</select>
										
									</div>
								</div>
							
								<li>
								
								<div class="form_grid_12">
									<label for="name" class="field_title">Amount<span class="req">*</span></label>
									<div class="form_input">
									<input name="amount" type="number" step="any" style="width:100%" tabindex="1" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
						  </li>
						  <li>
							<div class="form_grid_12">
								<label class="field_title">Description</label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" />
									</div>
								</div>
								
								
								</li>
								<li>
								
								<div class="form_grid_12">
									<label for="name" class="field_title">Date<span class="req">*</span></label>
									<div class="form_input">
									<input name="edate" type="datetime" step="any" style="width:100%" tabindex="1" class="datepicker" required />
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
		function fetch_select(bankaccount_id)
		{
			var arr = bankaccount_id.split("+");
			//alert(arr[1]);
			
			$_token = "{{ csrf_token() }}";
			$.ajax({
				type: 'post',
				url: 'get_bankaccount_code',
				data: {
					_token: $_token, 	 
					bankaccount_id: arr[0]
				},
				success: function (response) {
					$('#code').val(response.code);
				}
			});
			
		}

	</script>
@endsection
