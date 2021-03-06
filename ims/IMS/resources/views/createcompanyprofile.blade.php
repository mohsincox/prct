@extends('masterpage')

@section('content')
<div id="content">
<?php 
$userid=1;
foreach($profile as $com){
$id=$com->id;
}
?>
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Information of Company</h6>
					</div>
					<div class="widget_content">
						<h3>Information of Company</h3>
						<form action="/IMS/companyprofile/register" method="post" class="form_container left_label" enctype="multipart/form-data">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Name</label>
									<div class="form_input">
										<input name="name" type="text"  placeholder="Name" tabindex="1" value="<?php if(!empty($com)){echo $com->name;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Image</label>
									<div class="col-sm-6">
      <img src="uploads/<?php if(!empty($com)){echo $com->file;} ?>" class="hidden-print header-log" id="header-logo" alt="" height="100" width="100">
	</div>
									<div class="form_input">
										<input name="file" type="file" placeholder="Name" tabindex="1" required />
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Address</label>
									<div class="form_input">
										<input name="address" type="text" placeholder="Address" tabindex="1" value="<?php if(!empty($com)){echo $com->address;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Telephone</label>
									<div class="form_input">
										<input name="telephone" type="text" placeholder="Telephone" tabindex="1" value="<?php if(!empty($com)){echo $com->telephone;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Mobile</label>
									<div class="form_input">
										<input name="mobile" type="text" placeholder="Mobile" tabindex="1" value="<?php if(!empty($com)){echo $com->mobile;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Email</label>
									<div class="form_input">
										<input name="email" type="text" placeholder="Email" tabindex="1" value="<?php if(!empty($com)){echo $com->email;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<label class="field_title">Url</label>
									<div class="form_input">
										<input name="url" type="text" placeholder="Url" tabindex="1" value="<?php if(!empty($com)){echo $com->url;} ?>"/>
									</div>
								</div>
								</li>
								
								<li>
								<div class="form_grid_12">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Submit</span></button>
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
