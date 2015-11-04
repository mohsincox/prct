@extends('masterpage')

@section('content')


	
	<div class="widget_top">
	
						<span class="h_icon list_image"></span>
						<h6>Ledger Entry</h6>
					</div>
	<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Single Ledger Entry</a></li>
    <li><a href="#tabs-2">Multiple Ledger Entry</a></li>

 
  </ul>
  <div id="tabs-1">
	<div class="widget_top">
						
						<h3>Single Ledger Entry</h3>
					</div>
						<div class="widget_content" >
						
						<form action="/IMS/ledgerentry/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label  class="field_title">Particulars<span class="req">*</span></label>
				<div class="form_input">
                	<select data-placeholder="Ledger Option" style="width:100%;" class="chzn-select"  name="particular" tabindex="15">
											<option value=""></option>
											<?php
											   $coatype = DB::table('coatype')->get();
											   foreach($coatype as $ctype){ 
											?>
											<optgroup label="<?php echo $ctype->name;?>">
											 <?php
									$coa = DB::table('coa')->where('coatypeid',$ctype->id)->where('fixedstatus',0)->get();
											   foreach($coa as $c){ 
											?>
											<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
											<?php }?>
											<?php }?>
											</optgroup>
										</select>
				</div>
			  </div>
								<div class="form_grid_6">
									<label class="field_title">Description:<span class="req">*</span></label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Amount:<span class="req">*</span></label>
									<div class="form_input">
									<input name="amount" type="number" step="any" style="width:100%" tabindex="1" required />
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Date:<span class="req">*</span></label>
									<div class="form_input">
									<input name="edate" type="datetime" step="any" style="width:100%" tabindex="1" class="datepicker" required />
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
  <div id="tabs-2">
  	<div class="widget_top">
						
						<h3>Multiple Ledger Entry</h3>
					</div>
						<div class="widget_content" >
						
<form action="/IMS/ledgerentry/register" method="post" class="form_container left_label">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="userid" value="{{ Auth::id() }}">
						
							<ul>
						
								<li>
											<div class="form_grid_6">		
			<label  class="field_title">Particulars<span class="req">*</span></label>
				<div class="form_input">
                	<select data-placeholder="Ledger Option" style="width:100%" class="chzn-select"  name="particular" tabindex="15">
											<option value=""></option>
											<?php
											   $coatype = DB::table('coatype')->get();
											   foreach($coatype as $ctype){ 
											?>
											<optgroup label="<?php echo $ctype->name;?>">
											 <?php
											   $coa = DB::table('coa')->where('coatypeid',$ctype->id)->where('increasetypeid',3)->get();
											   foreach($coa as $c){ 
											?>
											<option value="<?php echo $c->id;?>"><?php echo $c->name;?></option>
											<?php }?>
											<?php }?>
											</optgroup>
										</select>
				</div>
			  </div>
								<div class="form_grid_6">
									<label class="field_title">Description:<span class="req">*</span></label>
									<div class="form_input">
										<input name="description" type="text" tabindex="1" />

									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								<div class="form_grid_6">
									<label class="field_title">Amount:<span class="req">*</span></label>
									<div class="form_input">
									<input name="amount" type="number" step="any" style="width:100%" tabindex="1" required />
									</div>
								</div>
								<div class="form_grid_6">
									<label class="field_title">Date:<span class="req">*</span></label>
									<div class="form_input">
									<input name="edate" type="datetime" step="any" style="width:100%" tabindex="1" class="datepicker" required />
									</div>
								</div>
								<div class="clear"></div>
								</li>
								<li>
								
								<div class="form_grid_6">
									<label for="name" class="field_title">Increase To:<span class="req">*</span></label>
									<div class="form_input">
									<select class="chzn-select" style=" width:100%" name="instatus" required >  
									
									  <option value="1">Debit</option>
									  <option value="2">Credit</option>

									</select> 
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
	  <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>


<div id="content">
		<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						
					</div>
					<div class="widget_content">
					
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								Particulars
							</th>
							<th>
								Amount
							</th>
							<th>
								Description
							</th>
							<th>
								 Print
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($pettycash as $cash){?>
						<tr class="gradeX">
							<td class="center">
								 <?php echo $cash->id;?>
							</td>
							<td class="center">
								<?php echo $cash->particular;?>
							</td>
							<td class="center">
								<?php echo $cash->amount;?>
							</td>
							<td class="center">
								<?php echo $cash->description;?>
							</td>
							<td class="center">
							    <div class="btn_30_orange">
									<a href="/IMS/ledgerentry/pdf/<?php echo $cash->id;?>"><span class="icon doc_access_co"></span><span class="btn_link">Print</span></a>
								</div>
							</td>
							<!--<td class="center">
						<span><a class="action-icons c-edit" href="ledgerentry/edit/<?php //echo $cash->id;?>" title="Edit">Edit</a></span><span><a class="action-icons c-Delete" href="ledgerentry/delete/<?php //echo $cash->id;?>" title="delete" onclick="return confirm('Are you sure you want to delete ?');">Delete</a></span>
							</td>-->
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

@endsection

