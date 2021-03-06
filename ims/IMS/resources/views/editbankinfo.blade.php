@extends('masterpage')

@section('content')

<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Bank Information</h6>
					</div>
										@if (count($errors) > 0)
            <div>
              
              <ul>
                @foreach ($errors->all() as $error)
                  <li style="color:red;"><b>{{ $error }}</b></li>
                @endforeach
              </ul>
            </div>
          @endif
					<div class="widget_content">
						<h3>Edit Bank Information</h3>
						<form action="/IMS/bankinfo/edit/<?php echo $bankinfo->id;?>" method="post"  class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="id" value="<?php echo $bankinfo->id; ?>">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Bank Name</label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="<?php echo $bankinfo->name;?>" />
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
