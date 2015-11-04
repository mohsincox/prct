@extends('masterpage')

@section('content')
<div id="content">
<div class="grid_container">
<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>User Group</h6>
					</div>
					<div class="widget_content">
						
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								Name
							</th>
							<th>
								 Email
							</th>
							<th>
								Privilege
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php foreach($users as $users){?>
						<tr class="gradeA">
							<td class="center">
								 <?php echo $users->name;?>
							</td>
							<td class="center">
								<?php echo $users->email;?>
							</td>
							<td class="center">
						<span><a class="action-icons c-suspend" href="/IMS/userspermission/privilege/<?php echo $users->id;?>" title="Privilege">Privilege</a></span>
							</td>
							
						</tr>
						<?php }?>
						
						</tbody>
						
						</table>
					</div>
				</div>
</div></div></div>

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> -->
    
@endsection
