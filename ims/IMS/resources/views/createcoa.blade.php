@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Chart of Account Information</h6>
					</div>
					<div>
						@if (count($errors) > 0)
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <h2 style="color:red"><li>{{ $error }}</li></h2>
            @endforeach
        </ul>
    </div>
@endif
					</div>
					<div class="widget_content">
						<h3>Create New Chart of Account</h3>
						<form action="/IMS/coa/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Code</label>
									<div class="form_input">
										<input name="code" type="text" tabindex="1" value="<?php echo"A-".(rand()); ?>" readonly />
									</div>
								</div>
								
								<div class="form_grid_6">
									<label class="field_title">Name<span class="req">*</span></label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" required />
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
									<label for="name" class="field_title">Opening Balance<span class="req">*</span></label>
									<div class="form_input">
										<input name="openbalance" type="number" step="any" style=" width:100%" tabindex="1" required />
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
									<label for="name" class="field_title">Coa Type<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="coatypeid" required >  
									<?php 
										echo "<option value='' Selected>Select</option>";
										foreach($coatype as $c){
										echo "<option value=".$c->id.">".$c->name."</option>";
									}?>
									</select>
									</div>
								</div>
								<div class="form_grid_6">
									
									<div class="btn_30_blue">
										<a href="coatype/addnew">Create Coa Type</a>
										
									
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
