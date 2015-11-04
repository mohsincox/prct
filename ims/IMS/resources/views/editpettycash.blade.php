
@extends('masterpage')

@section('content')

	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_image"></span>
						<h6>Edit Ledger Entry</h6>
					</div>
					<div class="widget_content">
						<h3>Edit Ledger Entry</h3>
						<?php $userid=1;?>
						<form action="register" method="post" <?php echo $pettycash->id;?> class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						<input type="hidden" name="id" value="<?php echo $pettycash->id; ?>">
						
							<ul>
								<li>	<div class="form_grid_12">
									<label class="field_title">Particulars<span class="req">*</span></label>
									<div class="form_input">
										<!--<select class="chzn-select" style=" width:100%" name="particular"> 
                                    <?php $acc=DB::table('coa')->where('coatypeid',8)->get(); ?>  
                                     <?php foreach($acc as $items) { ?>
                         <option value="<?php echo $items->id; ?>" <?php if($items->id==$pettycash->coatypeid)echo 'selected'?>><?php echo $items->name; ?></option>
                                    	   <?php }?>
                                 
                                </select>-->
								<select data-placeholder="Ledger Option" style="width:100%;" class="chzn-select"  name="particular" tabindex="15">
											<option value=""></option>
											<?php
											   $coatype = DB::table('coatype')->get();
											   foreach($coatype as $ctype){ 
											?>
											<optgroup label="<?php echo $ctype->name;?>">
											 <?php
											   $coa = DB::table('coa')->where('coatypeid',$ctype->id)->get();
											   foreach($coa as $c){ 
											?>
											<?php if($c->id==$pettycash->id){}?>
											<option value="<?php echo $c->id;?>" selected ><?php echo $c->name;?></option>
											<?php }?>
											<?php }?>
											</optgroup>
										</select>
								
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Amount<span class="req">*</span></label>
									<div class="form_input">
										<input name="amount" type="number" step="any" style=" width:100%" tabindex="1" value="<?php echo $pettycash->amount;?>" required />
									</div>
								</div>
								</li>
								<li>
								<div class="form_grid_12">
									<label class="field_title">Description<span class="req">*</span></label>
									<div class="form_input">
										<input name="description" type="text"  tabindex="1" value="<?php echo $pettycash->description;?>" required />
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

