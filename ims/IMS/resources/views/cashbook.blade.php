
@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Cash Book</h6>
					</div>
					
						<div class="widget_content">
						<h3>Cash Book</h3>
						<form action="cashbook/today" method="post" role="form" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
								<label class="field_title">Today</label>
									<div class="form_input">
									  <button type="submit" class="btn_small btn_blue"><span>Submit</span></button>
									</div>
								</div>
								</li>
							</ul>
						</form>
					</div>
					
					<div class="widget_content">
						
						<form action="cashbook/fromtoday" method="post" role="form" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title">From Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="fromdate" type="datetime" tabindex="1" class="datepicker" required />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">To Date<span class="req">*</span></label>
									<div class="form_input">
										<input name="todate" type="datetime" tabindex="1" class="datepicker" required />
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
	</div></div></div>
@endsection


