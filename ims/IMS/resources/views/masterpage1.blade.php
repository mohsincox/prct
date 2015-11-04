<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Inventory</title>

	<!-- Fonts -->
	<link href="{{ asset('/css/style.css') }}" rel="stylesheet">

	<link rel="stylesheet" href="http://bootstraptaste.com/theme/niceadmin/css/bootstrap-theme.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<!--<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> -->

</head>
<body>
	<div class="container-fixed">
		<header class="header dark-bg">
			<div class="row">
				<div class="col-md-11">
					<ul class="nav pull-right top-menu">
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<span class="username">{{ Auth::user()->name }}</span>
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li class="eborder-top">
                                <a href="/IMS/company"><i class="icon_profile"></i> Company Profile</a>
                            </li>
                           
                            
                            <li>
                                <a href="{{ url('/auth/logout') }}"><i class="icon_key_alt"></i> Log Out</a>
							</li>
                        </ul>
                    </li>
                  
                </ul>
           
		</div>	
	</div>


 </header>	
<aside>
<div class="nav-side-menu">
    <div class="brand">IMS Logo</div>
    <i class="fa fa-bars fa-2x toggle-btn" data-toggle="collapse" data-target="#menu-content"></i>
  
        <div class="menu-list">
			
			
            <ul id="menu-content" class="menu-content collapse out">
                <li>
                  <a href="#">
                  <i class="fa fa-dashboard fa-lg"></i> Dashboard
                  </a>
                </li>
                <?php $accountmenus=DB::table('menus')->get(); ?>
				<?php foreach($accountmenus as $memu){ ?>
					<li  data-toggle="collapse" data-target="#products<?php echo $memu->id; ?>" class="collapsed active">
					  <a href="#"><i class="fa fa-gift fa-lg"></i><?php echo $memu->name; ?><span class="arrow"></span></a>
					</li>
					<ul class="sub-menu collapse" id="products<?php echo $memu->id; ?>">
					     <?php  
						  $menuid=$memu->id;
						  $accountsubmenus = DB::table('submenus')->join('userspermission', 'submenus.id', '=', 'userspermission.submenuid')
						                  ->select('submenus.name', 'submenus.link', 'userspermission.status')
										  ->where('submenus.menuid',$menuid)
										  ->where('userspermission.userid',Auth::user()->id)
										  ->get();
						?>
						 <?php foreach($accountsubmenus as $submemu){ ?> 
						    <?php if($submemu->status==1){ ?>      
							<li><a href="/IMS/<?php echo $submemu->link; ?>"><?php echo $submemu->name; ?></a></li>
				             <?php }?>
						
						<?php }?>

					</ul>
                <?php }?>

            </ul>
			
			
			
     </div>
</div>
</aside>
	<section id="main-content">
		<section class="wrapper">            
			
   
            
			
			
			@yield('content')
				
			


          </section>
      </section>
	  
	  <footer>
	    <div class="row">
			<div class="col-md-12">
				<div class="footer_copyright">
					Copyright &copy; 2014 -Admin All rights reserved.<br>
					by <a href="#">Sascafs </a>
				</div>
			</div>
		</div>
	  </footer>
	  
  
      

	<!-- Scripts -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	 <script>
  $(function() {
    $( "#purchase_date" ).datepicker();
  });
  </script>
  <script>
  $(function() {
    $( "#supplier_bill_date" ).datepicker();
  });
  </script>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

</body>
</html>
