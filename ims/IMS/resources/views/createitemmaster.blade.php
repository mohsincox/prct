
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Create Item Model</h6>
					</div>
					<div class="widget_content">
						<h3>Add New Item Model</h3>
						
						<form action="register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
							<li>
								<div class="form_grid_6">
									<label class="field_title">Item Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" value="<?php echo "I-".rand();?>" readonly />
									</div>
								</div>
										<div class="form_grid_6">
					
									<label class="field_title">Measurement Unit<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="mesid"> 
                                    <?php $acc=DB::table('measurementunit')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								
							
								<div class="clear"></div>
								</li>
								
								<li>
								<div class="form_grid_6">
									<label class="field_title">Item Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" required />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Quantity</label>
									<div class="form_input">
										<input name="quantity" type="number" step="any" tabindex="1" style=" width:100%" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
					
									<label class="field_title">Item Group Name<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="itemssubgroupid"> 
                                    <?php $acc=DB::table('itemssubgroup')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Price</label>
									<div class="form_input">
										<input name="price" type="number" step="any" tabindex="1" style=" width:100%" />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								
								<li>
								
								
								<div class="form_grid_6">
									<label class="field_title">Generate Serial Number</label>
									<div class="form_input">
										<input type="checkbox" name="sstatus" value="1"/>
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

