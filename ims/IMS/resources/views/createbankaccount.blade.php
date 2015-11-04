@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Account Information</h6>
					</div>
					<div class="widget_content">
						<h3>Create New Account</h3>
						<form action="/IMS/bankaccount/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Account Code<span class="req">*</span></label>
									<div class="form_input">
									<input name="code" type="text" tabindex="1" value="<?php echo 'B-'.rand();?>" readonly />
									</div>
								</div>
								<div class="form_grid_6">
									<label for="name" class="field_title">Account Title<span class="req">*</span></label>
								<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="accotitle"> 
                                    <?php $mm=DB::table('banktitle')->get(); ?> 
                                     <?php foreach($mm as $gg) { ?>
                         <option value="<?php echo $gg->name; ?>" <?php echo 'selected'?>><?php echo $gg->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
								</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Account Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title"> Expire Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="exdate" type="text" tabindex="1" class="datepicker" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<!--<li>
								<div class="form_grid_12">
									<label class="field_title">Bank Name:</label>
									<div class="form_input">
										<input name="bankid" type="text" tabindex="1" />
									</div>
								</div>
								</li>-->
								<li>
								<div class="form_grid_6">
			<label for="name" class="field_title">Bank Name<span class="req">*</span></label>
				<div class="form_input">
                  <select class="chzn-select" style=" width:100%" name="bankid">   
				  <?php 
					echo "<option Selected>Select</option>";
					foreach($bankinfo as $gg){
					echo "<option value=".$gg->id.">".$gg->name."</option>";
				  }?>
                    </select>
				</div>
			  </div>
			  
				<div class="form_grid_6">
					<label class="field_title"> Interest Rate<span class="req">*</span></label>
						<div class="form_input">
							<input name="rate" type="number" step="any" tabindex="1" required style=" width:100%" />
						</div>
					</div>
					<div class="clear"></div>
				</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Branch Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="branchname" type="text" tabindex="1" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title"> Opening Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="opendate" type="text" tabindex="1" class="datepicker" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								
								<li>
								<div class="form_grid_6">
									<label class="field_title"> Opening Balance<span class="req">*</span></label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" tabindex="1" required style=" width:100%" />
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
@endsection
