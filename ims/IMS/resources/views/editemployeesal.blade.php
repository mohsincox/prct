
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Employee Salary</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Employee Salary</h3>
						<?php $userid=1;?>
						<form action="register" method="post" <?php echo $employeesal->id;?> class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="id" value="<?php echo $employeesal->id; ?>">
						
						
											<ul>
								
							<li>
							<div class="form_grid_6">
									<label class="field_title">Employee Name<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="ename" required > 
                                    <?php $acc=DB::table('employeeinfo')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php if($items->id==$employeesal->eid)echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
										
									</div>
								</div>
								
								<div class="form_grid_6">
									<label for="name" class="field_title">Amount<span class="req">*</span></label>
									<div class="form_input">
									<input name="amount" type="number" step="any" style="width:100%" tabindex="1" value="<?php echo $employeesal->amount;?>" required />
									</div>
								</div>
								<div class="clear"></div>
							</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Particulars<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="pname" required > 
                                    <?php $acc=DB::table('particulars')->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php if($items->id==$employeesal->pid)echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>
										
									</div>
								</div>
								
								<div class="form_grid_6">
									<label for="name" class="field_title">Date<span class="req">*</span></label>
									<div class="form_input">
									<input name="vdate" type="text" style="width:100%" tabindex="1" class="datepicker" value="<?php echo $employeesal->vdate;?>" required />
									</div>
								</div>
								<div class="clear"></div>
							</li>
							<li>

							<div class="form_grid_6">
								<label class="field_title">Description<span class="req">*</span></label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" value="<?php echo $employeesal->description;?>" required />
									</div>
								</div>
								
								<div class="clear"></div>
								</li>
								
								
								
							<li>
								<div class="form_grid_4">
									
								</div>
								<div class="form_grid_4">
									<div class="form_input">
									    <button type="submit" class="btn_small btn_blue"><span>Edit</span></button>
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

