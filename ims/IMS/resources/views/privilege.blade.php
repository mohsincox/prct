
@extends('masterpage')

@section('content')


<SCRIPT language="javascript">
$(function(){
 
    // add multiple select / deselect functionality
    $("#selectall").click(function () {
          $('.case').attr('checked', this.checked);
    });
 
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    $(".case").click(function(){
 
        if($(".case").length == $(".case:checked").length) {
            $("#selectall").attr("checked", "checked");
        } else {
            $("#selectall").removeAttr("checked");
        }
 
    });
});
</SCRIPT>
<div id="content">
		<div class="grid_container">
<div class="grid_12">
	<div class="widget_wrap">
		<div class="widget_top">
			<span class="h_icon blocks_images"></span>
				<h6>User Permission</h6>
		</div>
		<div class="widget_content">
			<form class="form-horizontal" action="/IMS/userspermission/register" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="userid" value="{{ Auth::id() }}">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
				<table class="">
					<thead>
						<tr>
							<th>
								Access
							</th>
							<th>
								Permission
							</th>
							
						</tr>
					</thead>
					<tbody>
							<?php $accountsubmenus = DB::table('submenus')->where('menuid','<>',0)->get();?>
							<?php foreach($accountsubmenus as $submemu){ 
								$userspermission = DB::table('userspermission')
													->where('submenuid', $submemu->id)
													->where('userid', $id)
													->select('status')
													->first();
								?> 
						<tr class="gradeX">
						<td class="center">
								<input type="hidden" name="submunuid[]" value="<?php echo $submemu->id; ?>">
									<?php echo $submemu->name; ?>
						</td>
						<td class="center">
						<?php if(!empty($userspermission)){ ?>
							<input type="radio" name="<?php echo $submemu->id; ?>" value="1" <?php if($userspermission->status == 1){ ?>checked="checked" <?php } ?> >Active
							<input type="radio" name="<?php echo $submemu->id; ?>" value="0" <?php if($userspermission->status == 0){ ?>checked="checked" <?php } ?>>Inactive
						<?php } else { ?>
							<input type="radio" name="<?php echo $submemu->id; ?>" value="1" checked="checked">Active
							<input type="radio" name="<?php echo $submemu->id; ?>" value="0">Inactive
						<?php } ?>
						</td>
							
						</tr>
							<?php }?>
		
					</tbody>
						
				</table>
				<tr>
					<td>
						<button type="submit" class="btn_small btn_blue">Save</button>
					</td>
				</tr>
			</form>
		</div>
	</div>
</div></div></div>

@endsection



