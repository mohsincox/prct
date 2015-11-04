
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add New Measurement Unit</h6>
					</div>
					<div class="widget_content">
						<h3>Create  Measurement Unit</h3>
						
						<form action="/IMS/measurementunit/register" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
					
									<label class="field_title">Measurement Group Name<span class="req">*</span></label>
									<div class="form_input">
										<select class="chzn-select" style=" width:100%" name="mgid"> 
                                    <?php $mm=DB::table('measurementgroup')->get(); ?>  
                                     <?php foreach($mm as $gg) { ?>
                         <option value="<?php echo $gg->id; ?>" <?php echo 'selected'?>><?php echo $gg->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Unit<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" required />
									</div>
								</div>
								</li>
						
								<li>
								<div class="form_grid_12">
									<div class="form_input">
									      <button type="submit" class="btn_small btn_blue"><span>Save</span></button>
										<button type="reset" class="btn_small btn_orange"><span>Reset</span></button>
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

