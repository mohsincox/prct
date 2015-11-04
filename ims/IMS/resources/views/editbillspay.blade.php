

@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Add Bill</h6>
					</div>
					<div class="widget_content">
						<h3>Create New Bill</h3>
						
						<form action="/IMS/billspay/register" method="post" enctype="multipart/form-data" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
								<li>
								<div class="form_grid_12">
					
									<label class="field_title">Purchase Name</label>
									<div class="form_input">
										<select class="chzn-select" style=" width:680px" name="purchaseid"> 
                                    <?php $mm=DB::table('purchase')->where('status',NULL)->get(); ?> 
                                     <?php foreach($mm as $gg) { ?>
                         <option value="<?php echo $gg->id; ?>" <?php echo 'selected'?>><?php echo $gg->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Date</label>
									<div class="form_input">
									<input name="purchasedate" type="text" tabindex="1" class="datepicker" value="<?php echo $billspay->purchasedate;?>"/>
										
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Amount</label>
									<div class="form_input">
										<input name="amount" type="text" tabindex="1" value="<?php echo $billspay->amount;?>" />
									</div>
								</div>
								</li>
								<!--<li>
								<div class="form_grid_12">
									<label class="field_title">File Upload</label>
									<div class="form_input">
										<input name="file" type="file" tabindex="1"  />
									</div>
								</div>
								</li>-->
						
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


