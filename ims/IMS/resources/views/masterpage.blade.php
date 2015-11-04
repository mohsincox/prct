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
<div id="actionsBox" class="actionsBox">
	<div id="actionsBoxMenu" class="menu">
		<span id="cntBoxMenu">0</span>
		<a class="button box_action">Archive</a>
		<a class="button box_action">Delete</a>
		<a id="toggleBoxMenu" class="open"></a>
		<a id="closeBoxMenu" class="button t_close">X</a>
	</div>
	<div class="submenu">
		<a class="first box_action">Move...</a>
		<a class="box_action">Mark as read</a>
		<a class="box_action">Mark as unread</a>
		<a class="last box_action">Spam</a>
	</div>
</div>
<div id="left_bar">
	<div id="primary_nav" class="g_blue">
		<ul>
			<li><a href="/IMS/" title="Dashboard"><span class="icon_block m_dashboard">Dashboard</span></a></li>
			<li><a href="#" title="Projects"><span class="icon_block m_projects">Projects</span></a></li>
			<li><a href="#" title="Events"><span class="icon_block m_events">Events</span></a></li>
			<li><a href="#" title="Address Book"><span class="icon_block p_book">Address Book</span></a></li>
			<li><a href="#" title="Media"><span class="icon_block m_media">Media</span></a></li>
			<li><a href="/IMS/companyprofile" title="Settings"><span class="icon_block m_settings">Settings</span></a></li>
		</ul>
	</div>
	<div id="start_menu">
		<ul>
			<li class="jtop_menu">
			<div class="icon_block black_gel">
				<span class="start_icon">Quick Menu</span>
			</div>
			<ul class="black_gel">
				<li><a href="#"><span class="list-icon cog_4_b">&nbsp;</span>Setup<span class="mnu_tline">Setup</span></a>
				<ul>
					<li><a href="/IMS/suppliers"><span class="list-icon cog_4_b">&nbsp;</span>Vendor/Supplier<span class="mnu_tline">Vendor/Supplier</span></a></li>
					<li><a href="/IMS/customers"><span class="list-icon cog_4_b">&nbsp;</span>Customer/Client<span class="mnu_tline">Customer/Client</span></a></li>
					<li><a href="/IMS/bankinfo"><span class="list-icon cog_4_b">&nbsp;</span>Bank Information<span class="mnu_tline">Bank Information</span></a></li>
					<li><a href="/IMS/bankaccount"><span class="list-icon cog_4_b">&nbsp;</span>Bank Account<span class="mnu_tline">Bank Account</span></a></li>

				</ul>
				</li>
				<li><a href="#"><span class="list-icon graph_b">&nbsp;</span>Inventory<span class="mnu_tline">Inventory</span></a>
				<ul>
					<li><a href="/IMS/itemgroup"><span class="list-icon graph_b">&nbsp;</span>Item Group<span class="mnu_tline">Item Group</span></a></li>
					<li><a href="/IMS/itemsubgroup"><span class="list-icon graph_b">&nbsp;</span>Item Category<span class="mnu_tline">Item Category</span></a></li>
					<li><a href="/IMS/itemmaster"><span class="list-icon graph_b">&nbsp;</span>Item Model<span class="mnu_tline">Item Model</span></a></li>
					<li><a href="/IMS/measurementgroup"><span class="list-icon graph_b">&nbsp;</span>Measurement Group<span class="mnu_tline">Measurement Group</span></a></li>
					<li><a href="/IMS/measurementunit"><span class="list-icon graph_b">&nbsp;</span>Measurement Unit<span class="mnu_tline">Measurement Unit</span></a></li>
				
				</ul>
				</li>
				<li><a href="#"><span class="list-icon vault_b">&nbsp;</span>Sales & Commercial<span class="mnu_tline">Sales & Commercial</span></a>
				<ul>
					<li><a href="/IMS/purchase"><span class="list-icon vault_b">&nbsp;</span>Purchase Order<span class="mnu_tline">Purchase Order</span></a></li>
					<li><a href="/IMS/physicalsales"><span class="list-icon vault_b">&nbsp;</span>Physical Sales<span class="mnu_tline">Physical Sales</span></a></li>
					<li><a href="/IMS/reportpurchases"><span class="list-icon vault_b">&nbsp;</span>Report on Purchases<span class="mnu_tline">Report on Purchases</span></a></li>
					<li><a href="/IMS/reportssale"><span class="list-icon vault_b">&nbsp;</span>Report on Sales<span class="mnu_tline">Report on Sales</span></a></li>

				</ul>
				</li>
				<li><a href="#"><span class="list-icon list_images_b">&nbsp;</span>Accounting<span class="mnu_tline">Accounting</span></a>
				<ul>
					<li><a href="/IMS/generalledger"><span class="list-icon list_images_b">&nbsp;</span>General Ledger<span class="mnu_tline">General Ledger</span></a></li>
					<!--<li><a href="/IMS/audittrial"><span class="list-icon list_images_b">&nbsp;</span>Audit Trail<span class="mnu_tline">Audit Trail</span></a></li>-->
					<li><a href="/IMS/balancesheet"><span class="list-icon list_images_b">&nbsp;</span>Balance Sheet<span class="mnu_tline">Balance Sheet</span></a></li>
					<li><a href="/IMS/coa"><span class="list-icon list_images_b">&nbsp;</span>Chart of Accounts<span class="mnu_tline">Chart of Accounts</span></a></li>
					<li><a href="/IMS/voucher"><span class="list-icon list_images_b">&nbsp;</span>Voucher Entry<span class="mnu_tline">Voucher Entry</span></a></li>
				</ul>
				</li>
				<li><a href="#"><span class="list-icon documents_b">&nbsp;</span>POS<span class="mnu_tline">POS</span></a>
				<ul>
					<li><a href="/IMS/reportlossprofit"><span class="list-icon documents_b">&nbsp;</span>Loss & Profit Analys<span class="mnu_tline">Loss & Profit Analys</span></a></li>
					<!--<li><a href="/IMS/stokrinout"><span class="list-icon documents_b">&nbsp;</span>Stock In Out Analys<span class="mnu_tline">Stock In Out Analys</span></a></li>-->
				</ul>
				</li>
				<li><a href="#"><span class="list-icon folder_b">&nbsp;</span>Factory Inventory<span class="mnu_tline">Factory Inventory</span></a>
				<ul>
					<li><a href="/IMS/factoryitem"><span class="list-icon folder_b">&nbsp;</span>Factory Item Master<span class="mnu_tline">Factory Item Master</span></a></li>
					<li><a href="/IMS/fstokin"><span class="list-icon folder_b">&nbsp;</span>Stock In Out Analyst<span class="mnu_tline">Stock In Out Analyst</span></a></li>
				</ul>
				</li>
				
				<li><a href="#"><span class="list-icon users_b">&nbsp;</span>User<span class="mnu_tline">user</span></a>
				<ul>
					<li><a href="/IMS/usersgroup/addnew"><span class="list-icon user_2_b">&nbsp;</span>Add New User<span class="mnu_tline">New user</span></a></li>
					<li><a href="/IMS/usersgroup"><span class="list-icon money_b">&nbsp;</span>User Group<span class="mnu_tline">User group</span></a></li>
					<li><a href="/IMS/users"><span class="list-icon users_2_b">&nbsp;</span>All Users<span class="mnu_tline">User</span></a></li>
					<li><a href="/IMS/userspermission"><span class="list-icon users_2_b">&nbsp;</span>User Permission<span class="mnu_tline">User permission</span></a></li>
				</ul>
				</li>
			</ul>
			</li>
		</ul>
	</div>
	<div id="sidebar">
		<div id="secondary_nav">
			<ul id="sidenav" class="accordion_mnu collapsible">
				<li><a href="/IMS/"><span class="nav_icon computer_imac"></span> Dashboard</a></li>
				 <?php $accountmenus=DB::table('menus')->get(); ?>
				
				 <?php foreach($accountmenus as $memu){ ?>
				  <li><a href="#"><span class="nav_icon frames"></span> <?php echo $memu->name; ?><span class="up_down_arrow">&nbsp;</span></a>
				  <ul class="acitem">
				  <?php  
						  $menuid=$memu->id;
						  /*$accountsubmenus = DB::table('submenus')
										  ->where('submenus.menuid',$menuid)
										  ->get();
							*/
                            $accountsubmenus = DB::table('submenus')
										  ->join('userspermission', 'submenus.id', '=', 'userspermission.submenuid')
										  ->where('submenus.menuid',$menuid)
										  ->where('userspermission.userid',Auth::id())
										  ->select('submenus.name', 'submenus.link', 'userspermission.status')
										  ->get();								
				?>
				 <?php foreach($accountsubmenus as $submemu){ ?> 
						    <?php if($submemu->status==1){ ?>   
				
				    <li><a href="/IMS/<?php echo $submemu->link; ?>" target="_blank"><span class="list-icon">&nbsp;</span><?php echo $submemu->name; ?></a></li>
				 <?php }?>
				  <?php }?>
				  </ul>
				  </li>
				  <?php }?>
			</ul>
		</div>
	</div>
</div>
<div id="container">





	<div id="header" class="blue_lin">
		<div class="header_left">
			<!--<div class="logo">
				<img src="{{ asset('admin/images/logo.jpg') }}" width="1000" height="60" alt="logo">
			</div>-->
			<div id="responsive_mnu">
				<a href="#responsive_menu" class="fg-button" id="hierarchybreadcrumb"><span class="responsive_icon"></span>Menu</a>
				<div id="responsive_menu" class="hidden">
					<ul>
						<li><a href="/IMS/"> Dashboard</a></li>
				 <?php $accountmenus=DB::table('menus')->get(); ?>
				
				 <?php foreach($accountmenus as $memu){ ?>
				  <li><a href="#"><span class="nav_icon frames"></span> <?php echo $memu->name; ?><span class="up_down_arrow">&nbsp;</span></a>
				  <ul class="acitem">
				  <?php  
						  $menuid=$memu->id;
						  /*$accountsubmenus = DB::table('submenus')
										  ->where('submenus.menuid',$menuid)
										  ->get();
						*/
                         $accountsubmenus = DB::table('submenus')
										  ->join('userspermission', 'submenus.id', '=', 'userspermission.submenuid')
										  ->where('submenus.menuid',$menuid)
										  ->where('userspermission.userid',Auth::id())
										  ->select('submenus.name', 'submenus.link', 'userspermission.status')
										  ->get();		
//print_r($accountsubmenus);										  
				?>
				  <?php foreach($accountsubmenus as $submemu){ ?> 
						    <?php if($submemu->status==1){ ?>   
				    <li><a href="/IMS/<?php echo $submemu->link; ?>" target="_blank"><span class="list-icon">&nbsp;</span><?php echo $submemu->name; ?></a></li>
					   <?php }?>
				 <?php }?>
				  </ul>
				  </li>
				  <?php }?>
					</ul>
				</div>
			</div>
		</div>
		<div class="header_right">
		    <!--
			<div id="top_notification">
				<ul>
					<li class="dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle alert"><span class="icon"></span><span class="alert_notify orange">30</span></a>
					<div class="notification_list dropdown-menu pull-left blue_d">
						<div class="white_lin nlist_block">
							<ul>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Cras erat diam, consequat quis tincidunt nec, eleifend.</a>
								</div>
								</li>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Donec neque leo, ullamcorper eget aliquet sit amet.</a>
								</div>
								</li>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Nam euismod dolor ac lacus facilisis imperdiet.</a>
								</div>
								</li>
							</ul>
							<span class="btn_24_blue"><a href="#">View All</a></span>
						</div>
					</div>
					</li>
					<li class="inbox dropdown">
					<a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="icon"></span><span class="alert_notify blue">30</span></a>
					<div class="notification_list dropdown-menu blue_d">
						<div class="white_lin nlist_block">
							<ul>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Cras erat diam, consequat quis tincidunt nec, eleifend.</a>
								</div>
								</li>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Donec neque leo, ullamcorper eget aliquet sit amet.</a>
								</div>
								</li>
								<li>
								<div class="nlist_thumb">
									<img src="images/photo_60x60.jpg" width="40" height="40" alt="img">
								</div>
								<div class="list_inf">
									<a href="#">Nam euismod dolor ac lacus facilisis imperdiet.</a>
								</div>
								</li>
							</ul>
							<span class="btn_24_blue"><a href="#">View All</a></span>
						</div>
					</div>
					</li>
				</ul>
			</div>
			-->
			<div id="user_nav">
				<ul>
					<li class="user_thumb"><a href="#"><span class="icon"><img src="{{ url('admin/images/user_thumb.png') }}" width="30" height="30" alt="User"></span></a></li>
					<li class="user_info"><span class="user_name">{{ Auth::user()->name }}</span><span><a href="/IMS/companyprofile">Company Profile</a> &#124; <a href="/IMS/users/changepass">Change Pasword&#63;</a></span></li>
					<li class="logout"><a href="{{ url('/auth/logout') }}"><span class="icon"></span>Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="page_title">
		<span class="title_icon"><span class="computer_imac"></span></span>
		<h3>Dashboard</h3>
		<div class="top_search">
		<!--	<form action="#" method="post">
				<ul id="search_box">
					<li>
					<input name="" type="text" class="search_input" id="suggest1" placeholder="Search...">
					</li>
					<li>
					<input name="" type="submit" value="" class="search_btn">
					</li>
				</ul>
			</form>
		-->	
		</div>
	</div>
	
	
	<div class="switch_bar">
	<?php 
		$items = DB::table('items')->count();
		$purchase = DB::table('purchase')->count();
		$sale = DB::table('sales')->count();
		$customer = DB::table('customers')->count(); 
		$supplier = DB::table('suppliers')->count();
	?>
		<ul>
		    <li><a href="/IMS/physicalsales/addnew"><span class="stats_icon bank_sl"></span><span class="label">Sales</span></a></li>
			<li><a href="/IMS/purchase/addnew"><span class="stats_icon bank_sl"></span><span class="label">Purchases</span></a></li>
			<li><a href="/IMS/customers/addnew" ><span class="stats_icon user_sl"></span><span class="label">Client</span></a></li>
			<li><a href="/IMS/suppliers/addnew" ><span class="stats_icon user_sl"></span><span class="label">Supplier</span></a></li>
			<li><a href="/IMS/itemmaster/addnew"><span class="stats_icon archives_sl"></span><span class="label">Items</span></a></li>
			<li><a href="/IMS/home/search"><span class="stats_icon finished_work_sl"></span></span><span class="label">Return</span></a></li>
			<li><a href="/IMS/companyprofile"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
			<li><a href="/IMS/itemmaster"><span class="stats_icon calendar_sl"><span class="alert_notify blue"><?php echo $items; ?></span></span><span class="label">Items</span></a></li>
			<li><a href="/IMS/customers"><span class="stats_icon user_sl"><span class="alert_notify orange"><?php echo $customer; ?></span></span><span class="label">Customer</span></a></li>
			<li><a href="/IMS/suppliers"><span class="stats_icon user_sl"><span class="alert_notify orange"><?php echo $supplier; ?></span></span><span class="label">Supplier</span></a></li>
			<li><a href="/IMS/physicalsales"><span class="stats_icon category_sl"><span class="alert_notify blue"><?php echo $sale; ?></span></span><span class="label">Sales</span></a></li>
			<li><a href="/IMS/purchase"><span class="stats_icon lightbulb_sl"><span class="alert_notify blue"><?php echo $purchase; ?></span></span><span class="label">Purchases</span></a></li>
			
		</ul>
	</div>
	<div id="content">
		@yield('content')
		
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