@extends('masterpage')

@section('content')
<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add User Group</h6>
					</div>
					@if (count($errors) > 0)
						<div class="form_grid_12" style="color: red">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<div class="widget_content">
						<h3>Create New User</h3>
						<form action="addnew" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> User Name:</label>
									<div class="form_input">
										<input name="name" type="text" tabindex="1" value="{{old('name')}}" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> User Email:</label>
									<div class="form_input">
										<input name="email" type="email" tabindex="1" value="{{old('email')}}" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> User Password:</label>
									<div class="form_input">
										<input name="password" type="password" tabindex="1" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title"> Confirm Passord:</label>
									<div class="form_input">
										<input name="confirm_password" type="password" tabindex="1" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
			<label for="name" class="field_title">User Group Name<span class=""></span></label>
				<div class="form_input">
                  <select class="chzn-select" style=" width:100%" name="usergroupid">   
				  <?php 
					echo "<option Selected>Select</option>";
					 $usergroups=DB::table('usergroups')->get(); 
					foreach($usergroups as $gg){
					echo "<option value=".$gg->id.">".$gg->name."</option>";
				  }?>
                    </select>
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
