@extends('masterpage')

@section('content')

<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Chart of Account</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Chart of Account</h3>
						<form action="/IMS/coa/edit/<?php echo $coa->id;?>" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="<?php echo $coa->id; ?>">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Code<span class="req"></span></label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" value="<?php echo $coa->code;?>"  readonly  />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $coa->name;?>" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Description<span class="req"></span></label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" value="<?php echo $coa->description;?>"  />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Opening Balance</label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" style=" width:100%" tabindex="1" value="<?php echo $coa->openbalance;?>" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Increase To<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="increasetypeid"> 
                                    
                                     <?php foreach($increasetype as $a) { ?>
                         <option value="<?php echo $a->id; ?>" <?php if($a->id==$coa->increasetypeid)echo 'selected'?>><?php echo $a->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Tax Rate</label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="taxrateid"> 
                                    
                                     <?php foreach($taxrate as $a) { ?>
                         <option value="<?php echo $a->id; ?>" <?php if($a->id==$coa->taxrateid)echo 'selected'?>><?php echo $a->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								
								<div class="form_grid_6">
									<label class="field_title">Coa Type<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="coatypeid" required > 
                                    
                                     <?php foreach($coatype as $a) { ?>
                         <option value="<?php echo $a->id; ?>" <?php if($a->id==$coa->coatypeid)echo 'selected'?>><?php echo $a->name; ?></option>
                                    	   <?php }?>
                                 
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
