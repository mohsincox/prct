
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Report On Sales</h6>
					</div>
					<div class="widget_content">
						<h3>Report On Sales</h3>
						<form action="" method="post" role="form" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
									<label class="field_title">From Date</label>
									<div class="form_input">
										<input name="fromdate" type="datetime" tabindex="1" class="datepicker" />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">To Date</label>
									<div class="form_input">
										<input name="todate" type="datetime" tabindex="1" class="datepicker" />
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


