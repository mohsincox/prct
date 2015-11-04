
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Measurement Unit</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Measurement Unit</h3>
						<?php $userid=1;?>
						<form action="register" method="post" <?php echo $measurementunit->id;?> class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="id" value="<?php echo $measurementunit->id; ?>">
						
							<ul>
								<li>
								<div class="form_grid_12">
					
									<label class="field_title">Measurement Group Name<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="mgid"> 
                                    <?php foreach($mg as $a) { ?>
                         <option value="<?php echo $a->id; ?>" <?php if($a->id==$measurementunit->mgid)echo 'selected'?>><?php echo $a->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Unit<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $measurementunit->name;?>" required  />
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


