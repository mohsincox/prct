
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Item Category</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Item Category</h3>
						<?php $userid=1;?>
						<form action="register" method="post" <?php echo $itemsubgroup->id;?> class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="id" value="<?php echo $itemsubgroup->id; ?>">
						
							<ul>
								<li>	<div class="form_grid_12">
									<label class="field_title">Item Group Name<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="itemgroupid"> 
                                    <?php $acc=DB::table('itemsgroup')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php if($items->id==$itemsubgroup->itemgroupid)echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Item Subgroup Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $itemsubgroup->name;?>" required />
									</div>
								</div>
								</li>
							
								
								
								<li>
								<div class="form_grid_12">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Edit</span></button>
									</div>
								</div>
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

