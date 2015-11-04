@extends('masterpage')

@section('content')

<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Account Information</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Account Information</h3>
						<form action="/IMS/bankaccount/edit/<?php echo $bankaccount->id;?>" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="<?php echo $bankaccount->id; ?>">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Account Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" value="<?php echo $bankaccount->code;?>" readonly />
									</div>
								</div>
								<div class="form_grid_6">
									<label for="name" class="field_title">Account Title<span class="req">*</span></label>
								<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="accotitle"> 
									 
                                    <?php $mm=DB::table('banktitle')->get(); ?> 
                                     <?php foreach($mm as $gg) { ?>
                         <option value="<?php echo $gg->name; ?>" <?php if($gg->id==$bankaccount->bankid) echo 'selected'?>><?php echo $gg->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
								</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Account Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $bankaccount->name;?>" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Expire Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="exdate" type="text" tabindex="1" class="datepicker" value="<?php echo $bankaccount->exdate;?>" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Bank Name<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="bankid"> 
                                    
                                     <?php foreach($bankinfo as $a) { ?>
                         <option value="<?php echo $a->id; ?>" <?php if($a->id==$bankaccount->bankid)echo 'selected'?>><?php echo $a->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Interest Rate<span class="req">*</span></label>
									<div class="form_input">
										<input name="rate" type="number" tabindex="1" step="any" value="<?php echo $bankaccount->rate;?>" required style=" width:100%" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title">Branch Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="branchname" type="text" tabindex="1" value="<?php echo $bankaccount->branchname;?>" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Opening Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="opendate" type="text" tabindex="1" class="datepicker" value="<?php echo $bankaccount->opendate;?>" required  />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								
								<li>
								<div class="form_grid_6">
									<label class="field_title">Opening Balance</label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" tabindex="1" value="<?php echo $bankaccount->openbalance;?>" style=" width:100%" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
									<li>
								<div class="form_grid_4">
									
								</div>
								<div class="form_grid_4">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Edit</span></button>
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
