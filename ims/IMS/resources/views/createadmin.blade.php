<!DOCTYPE HTML>
<html>

<!-- Mirrored from lab.westilian.com/bingo/01/blue/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Aug 2015 06:01:15 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width"/>
<title>Inventory Management System</title>
<link href="admin/css/reset.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/layout.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/themes.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/typography.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/styles.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/shCore.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/jquery.jqplot.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/data-table.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/form.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/ui-elements.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/wizard.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/sprite.css" rel="stylesheet" type="text/css" media="screen">
<link href="admin/css/gradient.css" rel="stylesheet" type="text/css" media="screen">
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
<script src="admin/js/jquery-1.7.1.min.js"></script>
<script src="admin/js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="admin/js/chosen.jquery.js"></script>
<script src="admin/js/uniform.jquery.js"></script>
<script src="admin/js/bootstrap-dropdown.js"></script>
<script src="admin/js/bootstrap-colorpicker.js"></script>
<script src="admin/js/sticky.full.js"></script>
<script src="admin/js/jquery.noty.js"></script>
<script src="admin/js/selectToUISlider.jQuery.js"></script>
<script src="admin/js/fg.menu.js"></script>
<script src="admin/js/jquery.tagsinput.js"></script>
<script src="admin/js/jquery.cleditor.js"></script>
<script src="admin/js/jquery.tipsy.js"></script>
<script src="admin/js/jquery.peity.js"></script>
<script src="admin/js/jquery.simplemodal.js"></script>
<script src="admin/js/jquery.jBreadCrumb.1.1.js"></script>
<script src="admin/js/jquery.colorbox-min.js"></script>
<script src="admin/js/jquery.idTabs.min.js"></script>
<script src="admin/js/jquery.multiFieldExtender.min.js"></script>
<script src="admin/js/jquery.confirm.js"></script>
<script src="admin/js/elfinder.min.js"></script>
<script src="admin/js/accordion.jquery.js"></script>
<script src="admin/js/autogrow.jquery.js"></script>
<script src="admin/js/check-all.jquery.js"></script>
<script src="admin/js/data-table.jquery.js"></script>
<script src="admin/js/ZeroClipboard.js"></script>
<script src="admin/js/TableTools.min.js"></script>
<script src="admin/js/jeditable.jquery.js"></script>
<script src="admin/js/duallist.jquery.js"></script>
<script src="admin/js/easing.jquery.js"></script>
<script src="admin/js/full-calendar.jquery.js"></script>
<script src="admin/js/input-limiter.jquery.js"></script>
<script src="admin/js/inputmask.jquery.js"></script>
<script src="admin/js/iphone-style-checkbox.jquery.js"></script>
<script src="admin/js/meta-data.jquery.js"></script>
<script src="admin/js/quicksand.jquery.js"></script>
<script src="admin/js/raty.jquery.js"></script>
<script src="admin/js/smart-wizard.jquery.js"></script>
<script src="admin/js/stepy.jquery.js"></script>
<script src="admin/js/treeview.jquery.js"></script>
<script src="admin/js/ui-accordion.jquery.js"></script>
<script src="admin/js/vaidation.jquery.js"></script>
<script src="admin/js/mosaic.1.0.1.min.js"></script>
<script src="admin/js/jquery.collapse.js"></script>
<script src="admin/js/jquery.cookie.js"></script>
<script src="admin/js/jquery.autocomplete.min.js"></script>
<script src="admin/js/localdata.js"></script>
<script src="admin/js/excanvas.min.js"></script>
<script src="admin/js/jquery.jqplot.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.dateAxisRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.cursor.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.logAxisRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.canvasTextRenderer.min.js"></script>
<script src="admin/admin/js/chart-plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.highlighter.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.pieRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.barRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.pointLabels.min.js"></script>
<script src="admin/js/chart-plugins/jqplot.meterGaugeRenderer.min.js"></script>
<script src="admin/js/custom-scripts.js"></script>
<script>
	$(function () {
    $.jqplot._noToImageButton = true;
    var prevYear = [["2011-08-01",398], ["2011-08-02",255.25], ["2011-08-03",263.9], ["2011-08-04",154.24],
    ["2011-08-05",210.18], ["2011-08-06",109.73], ["2011-08-07",166.91], ["2011-08-08",330.27], ["2011-08-09",546.6],
    ["2011-08-10",260.5], ["2011-08-11",330.34], ["2011-08-12",464.32], ["2011-08-13",432.13], ["2011-08-14",197.78],
    ["2011-08-15",311.93], ["2011-08-16",650.02], ["2011-08-17",486.13], ["2011-08-18",330.99], ["2011-08-19",504.33],
    ["2011-08-20",773.12], ["2011-08-21",296.5], ["2011-08-22",280.13], ["2011-08-23",428.9], ["2011-08-24",469.75],
    ["2011-08-25",628.07], ["2011-08-26",516.5], ["2011-08-27",405.81], ["2011-08-28",367.5], ["2011-08-29",492.68],
    ["2011-08-30",700.79], ["2011-08-31",588.5], ["2011-09-01",511.83], ["2011-09-02",721.15], ["2011-09-03",649.62],
    ["2011-09-04",653.14], ["2011-09-06",900.31], ["2011-09-07",803.59], ["2011-09-08",851.19], ["2011-09-09",2059.24],
    ["2011-09-10",994.05], ["2011-09-11",742.95], ["2011-09-12",1340.98], ["2011-09-13",839.78], ["2011-09-14",1769.21],
    ["2011-09-15",1559.01], ["2011-09-16",2099.49], ["2011-09-17",1510.22], ["2011-09-18",1691.72],
    ["2011-09-19",1074.45], ["2011-09-20",1529.41], ["2011-09-21",1876.44], ["2011-09-22",1986.02],
    ["2011-09-23",1461.91], ["2011-09-24",1460.3], ["2011-09-25",1392.96], ["2011-09-26",2164.85],
    ["2011-09-27",1746.86], ["2011-09-28",2220.28], ["2011-09-29",2617.91], ["2011-09-30",3236.63]];
    var currYear = [["2011-08-01",796.01], ["2011-08-02",510.5], ["2011-08-03",527.8], ["2011-08-04",308.48],
    ["2011-08-05",420.36], ["2011-08-06",219.47], ["2011-08-07",333.82], ["2011-08-08",660.55], ["2011-08-09",1093.19],
    ["2011-08-10",521], ["2011-08-11",660.68], ["2011-08-12",928.65], ["2011-08-13",864.26], ["2011-08-14",395.55],
    ["2011-08-15",623.86], ["2011-08-16",1300.05], ["2011-08-17",972.25], ["2011-08-18",661.98], ["2011-08-19",1008.67],
    ["2011-08-20",1546.23], ["2011-08-21",593], ["2011-08-22",560.25], ["2011-08-23",857.8], ["2011-08-24",939.5],
    ["2011-08-25",1256.14], ["2011-08-26",1033.01], ["2011-08-27",811.63], ["2011-08-28",735.01], ["2011-08-29",985.35],
    ["2011-08-30",1401.58], ["2011-08-31",1177], ["2011-09-01",1023.66], ["2011-09-02",1442.31], ["2011-09-03",1299.24],
    ["2011-09-04",1306.29], ["2011-09-06",1800.62], ["2011-09-07",1607.18], ["2011-09-08",1702.38],
    ["2011-09-09",4118.48], ["2011-09-10",1988.11], ["2011-09-11",1485.89], ["2011-09-12",2681.97],
    ["2011-09-13",1679.56], ["2011-09-14",3538.43], ["2011-09-15",3118.01], ["2011-09-16",4198.97],
    ["2011-09-17",3020.44], ["2011-09-18",3383.45], ["2011-09-19",2148.91], ["2011-09-20",3058.82],
    ["2011-09-21",3752.88], ["2011-09-22",3972.03], ["2011-09-23",2923.82], ["2011-09-24",2920.59],
    ["2011-09-25",2785.93], ["2011-09-26",4329.7], ["2011-09-27",3493.72], ["2011-09-28",4440.55],
    ["2011-09-29",5235.81], ["2011-09-30",6473.25]];
    var plot1 = $.jqplot("chart1", [prevYear, currYear], {
        seriesColors: ["rgba(78, 135, 194, 0.7)", "rgb(211, 235, 59)"],
        title: 'Monthly Revenue',
        highlighter: {
            show: true,
            sizeAdjust: 1,
            tooltipOffset: 9
        },
        grid: {
            background: 'rgba(57,57,57,0.0)',
            drawBorder: false,
            shadow: false,
            gridLineColor: '#666666',
            gridLineWidth: 2
        },
        legend: {
            show: true,
            placement: 'outside'
        },
        seriesDefaults: {
            rendererOptions: {
                smooth: true,
                animation: {
                    show: true
                }
            },
            showMarker: false
        },
        series: [
            {
                fill: true,
                label: '2010'
            },
            {
                label: '2011'
            }
        ],
        axesDefaults: {
            rendererOptions: {
                baselineWidth: 1.5,
                baselineColor: '#444444',
                drawBaseline: false
            }
        },
        axes: {
            xaxis: {
                renderer: $.jqplot.DateAxisRenderer,
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
                    formatString: "%b %e",
                    angle: -30,
                    textColor: '#dddddd'
                },
                min: "2011-08-01",
                max: "2011-09-30",
                tickInterval: "7 days",
                drawMajorGridlines: false
            },
            yaxis: {
                renderer: $.jqplot.LogAxisRenderer,
                pad: 0,
                rendererOptions: {
                    minorTicks: 1
                },
                tickOptions: {
                    formatString: "$%'d",
                    showMark: false
                }
            }
        }
    });
});
/*=================
CHART 8
===================*/
$(function(){
  var plot2 = $.jqplot ('chart8', [[3,7,9,1,5,3,8,2,5]], {
      // Give the plot a title.
      title: 'Plot With Options',
      // You can specify options for all axes on the plot at once with
      // the axesDefaults object.  Here, we're using a canvas renderer
      // to draw the axis label which allows rotated text.
      axesDefaults: {
        labelRenderer: $.jqplot.CanvasAxisLabelRenderer
      },
      // Likewise, seriesDefaults specifies default options for all
      // series in a plot.  Options specified in seriesDefaults or
      // axesDefaults can be overridden by individual series or
      // axes options.
      // Here we turn on smoothing for the line.
      seriesDefaults: {
		  shadow: false,   // show shadow or not.
          rendererOptions: {
              smooth: true
          }
      },
      // An axes object holds options for all axes.
      // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
      // Up to 9 y axes are supported.
      axes: {
        // options for each axis are specified in seperate option objects.
        xaxis: {
          label: "X Axis",
          // Turn off "padding".  This will allow data point to lie on the
          // edges of the grid.  Default padding is 1.2 and will keep all
          // points inside the bounds of the grid.
          pad: 0
        },
        yaxis: {
          label: "Y Axis"
        }
      },
		grid: {
         borderColor: '#ccc',     // CSS color spec for border around grid.
        borderWidth: 2.0,           // pixel width of border around grid.
        shadow: false               // draw a shadow for grid.
    }
    });
});
</script>
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
			<li><a href="#" title="Dashboard"><span class="icon_block m_dashboard">Dashboard</span></a></li>
			<li><a href="#" title="Projects"><span class="icon_block m_projects">Projects</span></a></li>
			<li><a href="#" title="Events"><span class="icon_block m_events">Events</span></a></li>
			<li><a href="#" title="Address Book"><span class="icon_block p_book">Address Book</span></a></li>
			<li><a href="#" title="Media"><span class="icon_block m_media">Media</span></a></li>
			<li><a href="#" title="Settings"><span class="icon_block m_settings">Settings</span></a></li>
		</ul>
	</div>
	<div id="start_menu">
		<ul>
			<li class="jtop_menu">
			<div class="icon_block black_gel">
				<span class="start_icon">Quick Menu</span>
			</div>
			<ul class="black_gel">
				<li><a href="#"><span class="list-icon graph_b">&nbsp;</span>Analytics<span class="mnu_tline">Tagline</span></a></li>
				<li><a href="#"><span class="list-icon cog_4_b">&nbsp;</span>Settings<span class="mnu_tline">Tagline</span></a></li>
				<li><a href="#"><span class="list-icon vault_b">&nbsp;</span>The Archive<span class="mnu_tline">Tagline</span></a></li>
				<li><a href="#"><span class="list-icon list_images_b">&nbsp;</span>Task List<span class="mnu_tline">Tagline</span></a></li>
				<li><a href="#"><span class="list-icon documents_b">&nbsp;</span>Content List<span class="mnu_tline">Tagline</span></a>
				</li>
				<li><a href="#"><span class="list-icon folder_b">&nbsp;</span>Media<span class="mnu_tline">Tagline</span></a>
				</li>
				<li><a href="#"><span class="list-icon phone_3_b">&nbsp;</span>Contact<span class="mnu_tline">Tagline</span></a>
				</li>
				<li><a href="#"><span class="list-icon users_b">&nbsp;</span>User<span class="mnu_tline">Tagline</span></a>
				<ul>
					<li><a href="#"><span class="list-icon user_2_b">&nbsp;</span>Add New User<span class="mnu_tline">Tagline</span></a></li>
					<li><a href="#"><span class="list-icon money_b">&nbsp;</span>Paid Users<span class="mnu_tline">Tagline</span></a></li>
					<li><a href="#"><span class="list-icon users_2_b">&nbsp;</span>All Users<span class="mnu_tline">Tagline</span></a></li>
				</ul>
				</li>
			</ul>
			</li>
		</ul>
	</div>
	<div id="sidebar">
		<div id="secondary_nav">
			<ul id="sidenav" class="accordion_mnu collapsible">
				<li><a href="#"><span class="nav_icon computer_imac"></span> Dashboard</a></li>
				 <?php $accountmenus=DB::table('menus')->get(); ?>
				
				 <?php foreach($accountmenus as $memu){ ?>
				  <li><a href="#"><span class="nav_icon frames"></span> <?php echo $memu->name; ?><span class="up_down_arrow">&nbsp;</span></a>
				  <ul class="acitem">
				  <?php  
						  $menuid=$memu->id;
						  $accountsubmenus = DB::table('submenus')
										  ->where('submenus.menuid',$menuid)
										  ->get();
				?>
				 <?php foreach($accountsubmenus as $submemu){ ?> 
				    <li><a href="/IMS/<?php echo $submemu->link; ?>"><span class="list-icon">&nbsp;</span><?php echo $submemu->name; ?></a></li>
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
			<div class="logo">
				<img src="admin/images/logo.png" width="160" height="60" alt="Ekra">
			</div>
			<div id="responsive_mnu">
				<a href="#responsive_menu" class="fg-button" id="hierarchybreadcrumb"><span class="responsive_icon"></span>Menu</a>
				<div id="responsive_menu" class="hidden">
					<ul>
						<li><a href="#"> Dashboard</a>
						<ul>
							<li><a href="dashboard.html">Dashboard Main</a></li>
							<li><a href="dashboard-01.html">Dashboard 01</a></li>
							<li><a href="dashboard-02.html">Dashboard 02</a></li>
							<li><a href="dashboard-03.html">Dashboard 03</a></li>
							<li><a href="dashboard-04.html">Dashboard 04</a></li>
						</ul>
						</li>
						<li><a href="#"> Forms</a>
						<ul>
							<li><a href="form-elements.html">All Forms Elements</a></li>
							<li><a href="left-label-form.html">Left Label Form</a></li>
							<li><a href="top-label-form.html">Top Label Form</a></li>
							<li><a href="form-xtras.html">Additional Forms (3)</a></li>
							<li><a href="form-validation.html">Form Validation</a></li>
							<li><a href="signup-form.html">Signup Form</a></li>
							<li><a href="content-post.html">Content Post Form</a></li>
							<li><a href="wizard.html">wizard</a></li>
						</ul>
						</li>
						<li><a href="table.html"> Tables</a></li>
						<li><a href="ui-elements.html">User Interface Elements</a></li>
						<li><a href="buttons-icons.html">Butons And Icons</a></li>
						<li><a href="widgets.html">All Widgets</a></li>
						<li><a href="#">Pages</a>
						<ul>
							<li><a href="post-preview.html">Content</a></li>
							<li><a href="login-01.html" target="_blank">Login 01</a></li>
							<li><a href="login-02.html" target="_blank">Login 02</a></li>
							<li><a href="login-03.html" target="_blank">Login 03</a></li>
							<li><a href="forgot-pass.html" target="_blank">Forgot Password</a></li>
						</ul>
						</li>
						<li><a href="typography.html">Typography</a></li>
						<li><a href="#">Grid</a>
						<ul>
							<li><a href="content-grid.html">Content Grid</a></li>
							<li><a href="form-grid.html">Form Grid</a></li>
						</ul>
						</li>
						<li><a href="chart.html">Chart/Graph</a></li>
						<li><a href="gallery.html">Gallery</a></li>
						<li><a href="calendar.html">Calendar</a></li>
						<li><a href="file-manager.html">File Manager</a></li>
						<li><a href="#">Error Pages</a>
						<ul>
							<li><a href="403.html" target="_blank">403</a></li>
							<li><a href="404.html" target="_blank">404</a></li>
							<li><a href="505.html" target="_blank">405</a></li>
							<li><a href="500.html" target="_blank">500</a></li>
							<li><a href="503.html" target="_blank">503</a></li>
						</ul>
						</li>
						<li><a href="invoice.html">Invoice</a></li>
						<li><a href="#">Email Templates</a>
						<ul>
							<li><a href="email-templates/forgot-pass-email-template.html" target="_blank">Forgot Password</a></li>
							<li><a href="email-templates/registration-confirmation-email-template.html" target="_blank">Registaion Confirmation</a></li>
						</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="header_right">
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
			<div id="user_nav">
				<ul>
					<li class="user_thumb"><a href="#"><span class="icon"><img src="images/user_thumb.png" width="30" height="30" alt="User"></span></a></li>
					<li class="user_info"><span class="user_name">Administrator</span><span><a href="#">Profile</a> &#124; <a href="#">Settings</a> &#124; <a href="#">Help&#63;</a></span></li>
					<li class="logout"><a href="#"><span class="icon"></span>Logout</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="page_title">
		<span class="title_icon"><span class="computer_imac"></span></span>
		<h3>Dashboard</h3>
		<div class="top_search">
			<form action="#" method="post">
				<ul id="search_box">
					<li>
					<input name="" type="text" class="search_input" id="suggest1" placeholder="Search...">
					</li>
					<li>
					<input name="" type="submit" value="" class="search_btn">
					</li>
				</ul>
			</form>
		</div>
	</div>
	<div class="switch_bar">
		<ul>
			<li>
			<a href="#"><span class="stats_icon current_work_sl"></span><span class="label">Analytics</span></a>
			</li>
			<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><span class="stats_icon user_sl"><span class="alert_notify orange">30</span></span><span class="label"> Users</span></a>
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
			<li><a href="#"><span class="stats_icon administrative_docs_sl"></span><span class="label">Content</span></a></li>
			<li><a href="#"><span class="stats_icon finished_work_sl"><span class="alert_notify blue">30</span></span><span class="label">Task List</span></a></li>
			<li><a href="#"><span class="stats_icon config_sl"></span><span class="label">Settings</span></a></li>
			<li><a href="#"><span class="stats_icon archives_sl"></span><span class="label">Archive</span></a></li>
			<li><a href="#"><span class="stats_icon address_sl"></span><span class="label">Contact</span></a></li>
			<li><a href="#"><span class="stats_icon folder_sl"></span><span class="label">Media</span></a></li>
			<li><a href="#"><span class="stats_icon category_sl"></span><span class="label">Explorer</span></a></li>
			<li><a href="#"><span class="stats_icon calendar_sl"><span class="alert_notify orange">30</span></span><span class="label">Events</span></a></li>
			<li><a href="#"><span class="stats_icon lightbulb_sl"></span><span class="label">Support</span></a></li>
			<li><a href="#"><span class="stats_icon bank_sl"><span class="alert_notify blue">30</span></span><span class="label">Order List</span></a></li>
		</ul>
	</div>
	<div id="content">
		<div class="grid_container">
			<div class="grid_12 full_block">
				<div class="widget_wrap">
					<div class="widget_content">
						<div class="data_widget black_g chart_wrap">
							<div id="chart1">
							</div>
						</div>
					</div>
				</div>
			</div>
			<span class="clear"></span>
			<div class="social_activities">
				<div class="activities_s">
					<div class="block_label">
						User Activities<span>54854</span>
					</div>
					<div class="activities_chart">
						<span class="activities_chart">100,150,130,100,250,280,350,250,400,450,280,350,250,400,</span>
					</div>
				</div>
				<div class="activities_s">
					<div class="block_label">
						Visits Rate<span>80%</span>
					</div>
					<div class="visit_chart">
						<span class="activities_chart">500,450,100,500,550, 400,300,550,480,500,320,400,450</span>
					</div>
				</div>
				<div class="comments_s">
					<div class="block_label">
						Comments<span>17000</span>
					</div>
					<span class="badge_icon comment_sl"></span>
				</div>
				<div class="views_s">
					<div class="block_label">
						Paid Members<span>1500</span>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				<div class="user_s">
					<div class="block_label">
						New User's<span>12000</span>
					</div>
					<span class="badge_icon customers_sl"></span>
				</div>
			</div>
			<!-- <div class="grid_12">
				<div class="widget_wrap collapsible_widget">
					<div class="widget_top active">
						<span class="h_icon"></span>
						<h6>Active Collapsible Widget</h6>
					</div>
					<div class="widget_content">
						<h3>Header</h3>
						<p>
							 Cras erat diam, consequat quis tincidunt nec, eleifend a turpis. Aliquam ultrices feugiat metus, ut imperdiet erat mollis at. Curabitur mattis risus sagittis nibh lobortis vel.
						</p>
						<div id="chart8" class="chart_block">
						</div>
					</div>
				</div>
			</div> -->
		<!-- 	<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon graph"></span>
						<h6>Statistics</h6>
					</div>
					<div class="widget_content">
						<div class="stat_block">
							<h4>4,355 people visited this site</h4>
							<table>
							<tbody>
							<tr>
								<td>
									Visitors
								</td>
								<td>
									3000
								</td>
								<td class="min_chart">
									<span class="bar">20,30,50,200,250,280,350</span>
								</td>
							</tr>
							<tr>
								<td>
									Unique Visitors
								</td>
								<td>
									2000
								</td>
								<td class="min_chart">
									<span class="line">20,30,50,200,250,280,350</span>
								</td>
							</tr>
							<tr>
								<td>
									New Visitors
								</td>
								<td>
									1000
								</td>
								<td class="min_chart">
									<span class="line">20,30,50,200,250,280,350</span>
								</td>
							</tr>
							</tbody>
							</table>
							<div class="stat_chart">
								<div class="pie_chart">
									<span class="inner_circle">1/1.5</span>
									<span class="pie">1/1.5</span>
								</div>
								<div class="chart_label">
									<ul>
										<li><span class="new_visits"></span>New Visitors: 7000</li>
										<li><span class="unique_visits"></span>Unique Visitors: 3000</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon users"></span>
						<h6>Recent Users</h6>
					</div>
					<div class="widget_content">
						<div class="user_list">
							<div class="user_block">
								<div class="info_block">
									<div class="widget_thumb">
										<img src="images/user-thumb1.png" width="40" height="40" alt="User">
									</div>
									<ul class="list_info">
										<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
										<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
										<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
									</ul>
								</div>
								<ul class="action_list">
									<li><a class="p_edit" href="#">Edit</a></li>
									<li><a class="p_del" href="#">Delete</a></li>
									<li><a class="p_reject" href="#">Suspend</a></li>
									<li class="right"><a class="p_approve" href="#">Approve</a></li>
								</ul>
							</div>
							<div class="user_block">
								<div class="info_block">
									<div class="widget_thumb">
										<img src="images/user-thumb1.png" width="40" height="40" alt="user">
									</div>
									<ul class="list_info">
										<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
										<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
										<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
									</ul>
								</div>
								<ul class="action_list">
									<li><a class="p_edit" href="#">Edit</a></li>
									<li><a class="p_del" href="#">Delete</a></li>
									<li><a class="p_reject" href="#">Suspend</a></li>
									<li class="right"><a class="p_approve" href="#">Approve</a></li>
								</ul>
							</div>
							<div class="user_block">
								<div class="info_block">
									<div class="widget_thumb">
										<img src="images/user-thumb1.png" width="40" height="40" alt="user">
									</div>
									<ul class="list_info">
										<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
										<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
										<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
									</ul>
								</div>
								<ul class="action_list">
									<li><a class="p_edit" href="#">Edit</a></li>
									<li><a class="p_del" href="#">Delete</a></li>
									<li><a class="p_reject" href="#">Suspend</a></li>
									<li class="right"><a class="p_approve" href="#">Approve</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div> -->
			<span class="clear"></span>
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon list_images"></span>
						<h6>Task List</h6>
					</div>
					<div class="widget_content">
						<h3>Task list with label badge</h3>
						<p>
							 Cras erat diam, consequat quis tincidunt nec, eleifend a turpis. Aliquam ultrices feugiat metus, ut imperdiet erat mollis at. Curabitur mattis risus sagittis nibh lobortis vel.
						</p>
						<table class="display" id="action_tbl">
						<thead>
						<tr>
							<th class="center">
								<input name="checkbox" type="checkbox" value="" class="checkall">
							</th>
							<th>
								 Id
							</th>
							<th>
								 Task
							</th>
							<th>
								 Dead Line
							</th>
							<th>
								 Priority
							</th>
							<th>
								 Status
							</th>
							<th>
								 Complete Date
							</th>
							<th>
								 Action
							</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="center tr_select ">
								<input name="checkbox" type="checkbox" value="">
							</td>
							<td>
								<a href="#">01</a>
							</td>
							<td>
								<a href="#" class="t-complete">Pellentesque ut massa ut ligula ... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								<span class="badge_style b_high">High</span>
							</td>
							<td class="center">
								<span class="badge_style b_done">Done</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Done</a></span>
							</td>
						</tr>
						<tr>
							<td class="center tr_select ">
								<input name="checkbox" type="checkbox" value="">
							</td>
							<td>
								<a href="#">02</a>
							</td>
							<td>
								<a href="#" class="t-complete">Nulla non ante dui, sit amet ... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								<span class="badge_style b_low">Low</span>
							</td>
							<td class="center">
								<span class="badge_style b_done">Done</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Done</a></span>
							</td>
						</tr>
						<tr>
							<td class="center tr_select ">
								<input name="checkbox" type="checkbox" value="">
							</td>
							<td>
								<a href="#">03</a>
							</td>
							<td>
								<a href="#" class="t-complete">Aliquam eu pellentesque... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								<span class="badge_style b_medium">Medium</span>
							</td>
							<td class="center">
								<span class="badge_style b_done">Done</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Done</a></span>
							</td>
						</tr>
						<tr>
							<td class="center tr_select">
								<input name="checkbox" type="checkbox" value="">
							</td>
							<td>
								<a href="#">04</a>
							</td>
							<td>
								<a href="#">Maecenas egestas alique... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								<span class="badge_style b_high">High</span>
							</td>
							<td class="center">
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td class="center sdate">
								 -
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Done</a></span>
							</td>
						</tr>
						</tbody>
						<tfoot>
						<tr>
							<th class="center">
								<input name="checkbox" type="checkbox" value="" class="checkall">
							</th>
							<th>
								 Id
							</th>
							<th>
								 Task
							</th>
							<th>
								 Dead Line
							</th>
							<th>
								 Priority
							</th>
							<th>
								 Status
							</th>
							<th>
								 Complete Date
							</th>
							<th>
								 Action
							</th>
						</tr>
						</tfoot>
						</table>
					</div>
				</div>
			</div>
			<span class="clear"></span>
			<div class="grid_12">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon documents"></span>
						<h6>Content</h6>
					</div>
					<div class="widget_content">
						<h3>Content Table</h3>
						<p>
							 Cras erat diam, consequat quis tincidunt nec, eleifend a turpis. Aliquam ultrices feugiat metus, ut imperdiet erat mollis at. Curabitur mattis risus sagittis nibh lobortis vel.
						</p>
						<table class="display data_tbl">
						<thead>
						<tr>
							<th>
								 Id
							</th>
							<th>
								 Details
							</th>
							<th>
								 Submit Date
							</th>
							<th>
								 Submited By
							</th>
							<th>
								 Status
							</th>
							<th>
								 Publish Date
							</th>
							<th>
								 Action
							</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<a href="#">01</a>
							</td>
							<td>
								<a href="#">Pellentesque ut massa ut ligula ... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								 Jaman
							</td>
							<td class="center">
								<span class="badge_style b_done">Publish</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
							</td>
						</tr>
						<tr>
							<td>
								<a href="#">02</a>
							</td>
							<td>
								<a href="#">Nulla non ante dui, sit amet ... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								 Jhon
							</td>
							<td class="center">
								<span class="badge_style b_done">Publish</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
							</td>
						</tr>
						<tr>
							<td>
								<a href="#">03</a>
							</td>
							<td>
								<a href="#">Aliquam eu pellentesque... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								 Mike
							</td>
							<td class="center">
								<span class="badge_style b_done">Publish</span>
							</td>
							<td class="center sdate">
								 3rd FEB 2012
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
							</td>
						</tr>
						<tr>
							<td>
								<a href="#">04</a>
							</td>
							<td>
								<a href="#">Maecenas egestas alique... </a>
							</td>
							<td class="sdate center">
								 1st FEB 2012
							</td>
							<td class="center">
								 Sam
							</td>
							<td class="center">
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td class="center sdate">
								 -
							</td>
							<td class="center">
								<span><a class="action-icons c-edit" href="#" title="Edit">Edit</a></span><span><a class="action-icons c-delete" href="#" title="delete">Delete</a></span><span><a class="action-icons c-approve" href="#" title="Approve">Publish</a></span>
							</td>
						</tr>
						</tbody>
						<tfoot>
						<tr>
							<th>
								 Id
							</th>
							<th>
								 Details
							</th>
							<th>
								 Submit Date
							</th>
							<th>
								 Submited By
							</th>
							<th>
								 Status
							</th>
							<th>
								 Publish Date
							</th>
							<th>
								 Action
							</th>
						</tr>
						</tfoot>
						</table>
					</div>
				</div>
			</div>
			<span class="clear"></span>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon shopping_cart_3"></span>
						<h6>Recent Order</h6>
					</div>
					<div class="widget_content">
						<table class="wtbl_list">
						<thead>
						<tr>
							<th>
								 Order ID
							</th>
							<th>
								 Titile
							</th>
							<th>
								 Status
							</th>
							<th>
								 Amount
							</th>
						</tr>
						</thead>
						<tbody>
						<tr class="tr_even">
							<td>
								 #36542
							</td>
							<td>
								 Gold Pack
							</td>
							<td>
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td>
								 $50/m
							</td>
						</tr>
						<tr class="tr_odd">
							<td>
								 #38544
							</td>
							<td>
								 Silver Pack
							</td>
							<td>
								<span class="badge_style b_confirmed">Confirmed</span>
							</td>
							<td>
								 $20/m
							</td>
						</tr>
						<tr class="tr_even">
							<td class="noborder_b round_l">
								 #39542
							</td>
							<td class="noborder_b">
								<span>Platinum Pack</span>
							</td>
							<td class="noborder_b">
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td class="noborder_b round_r">
								 $80/m
							</td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="grid_6">
				<div class="widget_wrap tabby">
					<div class="widget_top">
						<span class="h_icon bended_arrow_right"></span>
						<h6>Tabby Widget</h6>
						<div id="widget_tab">
							<ul>
								<li><a href="#tab1" class="active_tab">Recent Users<span class="alert_notify blue">25</span></a></li>
								<li><a href="#tab2">Recent Comments<span class="alert_notify orange">25</span></a></li>
							</ul>
						</div>
					</div>
					<div class="widget_content">
						<div id="tab1">
							<div class="user_list">
								<div class="user_block">
									<div class="info_block">
										<div class="widget_thumb">
											<img src="images/user-thumb1.png" width="40" height="40" alt="User">
										</div>
										<ul class="list_info">
											<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
											<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
											<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
										</ul>
									</div>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Suspend</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
								<div class="user_block">
									<div class="info_block">
										<div class="widget_thumb">
											<img src="images/user-thumb1.png" width="40" height="40" alt="user">
										</div>
										<ul class="list_info">
											<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
											<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
											<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
										</ul>
									</div>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Suspend</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
								<div class="user_block">
									<div class="info_block">
										<div class="widget_thumb">
											<img src="images/user-thumb1.png" width="40" height="40" alt="user">
										</div>
										<ul class="list_info">
											<li><span>Name: <i><a href="#">Zara Zarin</a></i></span></li>
											<li><span>IP: 194.132.12.1 Date: 13th Jan 2012</span></li>
											<li><span>User Type: Paid, <i>Package Name:</i><b>Gold</b></span></li>
										</ul>
									</div>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Suspend</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div id="tab2">
							<div class="post_list">
								<div class="post_block">
									<h6><a href="#">Sed eu adipiscing nisi. Maecenas dapibus lacinia pretium. Praesent eget lectus ac odio euismod consequat. </a></h6>
									<ul class="post_meta">
										<li><span>Posted By:</span><a href="#">Joe Smith</a></li>
										<li><span>Date:</span><a href="#"> 30th April 2012</a></li>
										<li class="total_post"><span>Total Post: </span><a href="#">30</a></li>
									</ul>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Reject</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
								<div class="post_block">
									<h6><a href="#">Sed eu adipiscing nisi. Maecenas dapibus lacinia pretium. Praesent eget lectus ac odio euismod consequat. </a></h6>
									<ul class="post_meta">
										<li><span>Posted By:</span><a href="#">Joe Smith</a></li>
										<li><span>Date:</span><a href="#"> 30th April 2012</a></li>
										<li class="total_post"><span>Total Post: </span><a href="#">30</a></li>
									</ul>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Reject</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
								<div class="post_block">
									<h6><a href="#">Sed eu adipiscing nisi. Maecenas dapibus lacinia pretium. Praesent eget lectus. </a></h6>
									<ul class="post_meta">
										<li><span>Posted By:</span><a href="#">Joe Smith</a></li>
										<li><span>Date:</span><a href="#"> 30th April 2012</a></li>
										<li class="total_post"><span>Total Post: </span><a href="#">30</a></li>
									</ul>
									<ul class="action_list">
										<li><a class="p_edit" href="#">Edit</a></li>
										<li><a class="p_del" href="#">Delete</a></li>
										<li><a class="p_reject" href="#">Reject</a></li>
										<li class="right"><a class="p_approve" href="#">Approve</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<span class="clear"></span>
		</div>
		<span class="clear"></span>
	</div>
</div>
</body>

<!-- Mirrored from lab.westilian.com/bingo/01/blue/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Aug 2015 06:02:39 GMT -->
</html>