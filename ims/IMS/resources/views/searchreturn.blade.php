<!DOCTYPE HTML>
<html>

<!-- Mirrored from lab.westilian.com/bingo/01/blue/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Aug 2015 06:01:15 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width"/>
 
<title>Enterprise Resource Planning</title>
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
<link href="{{ asset('/admin/css/reset.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/layout.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/themes.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/typography.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/styles.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/shCore.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/jquery.jqplot.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/jquery-ui-1.8.18.custom.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/data-table.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/form.css') }}" rel="stylesheet" type="text/css" media="screen">
<link rel="icon" type="image/ico" href="img/jco.ico"/>
<link href="{{ asset('/admin/css/ui-elements.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/wizard.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/sprite.css') }}" rel="stylesheet" type="text/css" media="screen">
<link href="{{ asset('/admin/css/gradient.css') }}" rel="stylesheet" type="text/css" media="screen">


<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie/ie7.css" />
<![endif]-->
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie/ie8.css" />
<![endif]-->
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie/ie9.css" />
<![endif]-->
<!-- Jquery -->

<!--<script src="{{ asset('/admin/js/jquery-1.11.3.min.js') }}"></script>-->
<script src="{{ asset('/admin/js/jquery-1.7.1.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery-ui-1.8.18.custom.min.js') }}"></script>
<script src="{{ asset('/admin/js/chosen.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/uniform.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap-dropdown.js') }}"></script>
<script src="{{ asset('/admin/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ asset('/admin/js/sticky.full.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.noty.js') }}"></script>
<script src="{{ asset('/admin/js/selectToUISlider.jQuery.js') }}"></script>
<script src="{{ asset('/admin/js/fg.menu.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.tagsinput.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.cleditor.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.tipsy.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.peity.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.simplemodal.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.jBreadCrumb.1.1.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.colorbox-min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.idTabs.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.multiFieldExtender.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.confirm.js') }}"></script>
<script src="{{ asset('/admin/js/elfinder.min.js') }}"></script>
<script src="{{ asset('/admin/js/accordion.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/autogrow.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/check-all.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/data-table.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/ZeroClipboard.js') }}"></script>
<script src="{{ asset('/admin/js/TableTools.min.js') }}"></script>
<script src="{{ asset('/admin/js/jeditable.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/duallist.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/easing.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/full-calendar.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/input-limiter.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/inputmask.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/iphone-style-checkbox.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/meta-data.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/quicksand.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/raty.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/smart-wizard.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/stepy.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/treeview.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/ui-accordion.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/vaidation.jquery.js') }}"></script>
<script src="{{ asset('/admin/js/mosaic.1.0.1.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.collapse.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.cookie.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('/admin/js/localdata.js') }}"></script>
<script src="{{ asset('/admin/js/excanvas.min.js') }}"></script>
<script src="{{ asset('/admin/js/jquery.jqplot.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.dateAxisRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.cursor.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.logAxisRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.canvasTextRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/admin/js/chart-plugins/jqplot.canvasAxisTickRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.highlighter.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.pieRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.barRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.categoryAxisRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.pointLabels.min.js') }}"></script>
<script src="{{ asset('/admin/js/chart-plugins/jqplot.meterGaugeRenderer.min.js') }}"></script>
<script src="{{ asset('/admin/js/custom-scripts.js') }}"></script>
<!-- for csv -->
<script src="{{ asset('/admin/js/csv/csv.js') }}"></script>


</head>
<body id="theme-default" class="full_block">


<div id="container">

<div id="header" class="blue_lin"></div>
	
	
	

	<div id="content">
<?php 
$userid=1;
foreach($profile as $com){
$id=$com->id;
}

?>

	<div id="content">
		<div class="grid_container">
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list"></span>
						<h6>Invoice for Monthly bill</h6>
						
						<form action="view" method="post">
							    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								<ul id="search_box">
									<li>
									<input name="slno" type="text" class="search_input" id="suggest1" placeholder="Enter SL no...">
									</li>
									<li>
									<input name="" type="submit" value="" class="search_btn">
									</li>
								</ul>
							</form>
					</div>
					<div class="widget_content" style="padding-top:50px;">
						<div class=" page_content">
							<div class="invoice_container">
							<div class="header">

<h1><?php if(!empty($com)){echo $com->name;} ?></h1><br><address>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php if(!empty($com)){echo $com->address;} ?><br>
&nbsp;&nbsp;&nbsp;&nbsp;
Tel:<?php if(!empty($com)){echo $com->telephone;} ?> ,Mobile: <?php if(!empty($com)){echo $com->mobile;} ?><br>

E-mail:<?php if(!empty($com)){echo $com->email;} ?><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php if(!empty($com)){echo $com->url;} ?> " target="_blank"> <?php if(!empty($com)){echo $com->url;} ?></a><br>

</address>



</div><br>
<div class="clear"></div>
							
								<div class="invoice_action_bar">
									
									<div class="btn_30_light">
										<a href="#" title="Print"><span class="icon printer_co"></span></a>
									</div>
									
								</div>
							
								<span class="clear"></span>
								<div class="grid_12 invoice_title">
									<h5><u>INVOICE/BILL</u></h5>
								</div>
								
								<div class="grid_6 invoice_to">
									<ul>
										<li>
										<strong><span>Name/Title:</span></strong>
										<span>Address:</span>
										<span>Mobile No:</span>
										
										
										</li>
									</ul>
								</div>
								<div class="grid_6 invoice_from">
									<ul>
										<li>
										<strong><span>Date:</span></strong>
										<span>SI No:</span>
										<span>Voucher No:</span>
										
										</li>
									</ul>
								</div>
								<span class="clear"></span>
								<div class="grid_12 invoice_details">
									<div class="invoice_tbl">
										<table>
										<thead>
										<tr class=" gray_sai">
											<th>
												 No.
											</th>
											<th>
												 Date
											</th>
											<th>
												Item Model
											</th>
											<th>
												Serial No
											</th>
											
										</tr>
										</thead>
										<tbody>
										
										<tr>
											<td class="center"></td>
											<td class="center"></td>
											<td class="center"></td>
											<td class="center"></td>
											
										</tr>
										
									
										
										
										</tbody>
										</table>
									</div>
									
								</div>
								<span class="clear"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="clear"></span>
	</div>
	</div>
	</div>
<div class="footer">
	
	  <p> 
     &copy; Flyte Solutions - 2015<br><a href="http://www.flytesolutions.com" target="_blank">www.flytesolutions.com </a></p> 
	</div>

<script>
        $(function() {
        $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });
</script>
<script  src="{{ URL::asset('js/bootbox.js') }}"></script>
</body>

<!-- Mirrored from lab.westilian.com/bingo/01/blue/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Aug 2015 06:02:39 GMT -->
</html>


