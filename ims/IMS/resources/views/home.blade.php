@extends('masterpage')

@section('content')

<script>
	$(function () {
    $.jqplot._noToImageButton = true;
    // var prevYear = [["2011-08-01",398], ["2011-08-02",255.25], ["2011-08-03",263.9], ["2011-08-04",154.24],
    // ["2011-08-05",210.18], ["2011-08-06",109.73], ["2011-08-07",166.91], ["2011-08-08",330.27], ["2011-08-09",546.6],
    // ["2011-08-10",260.5], ["2011-08-11",330.34], ["2011-08-12",464.32], ["2011-08-13",432.13], ["2011-08-14",197.78],
    // ["2011-08-15",311.93], ["2011-08-16",650.02], ["2011-08-17",486.13], ["2011-08-18",330.99], ["2011-08-19",504.33],
    // ["2011-08-20",773.12], ["2011-08-21",296.5], ["2011-08-22",280.13], ["2011-08-23",428.9], ["2011-08-24",469.75],
    // ["2011-08-25",628.07], ["2011-08-26",516.5], ["2011-08-27",405.81], ["2011-08-28",367.5], ["2011-08-29",492.68],
    // ["2011-08-30",700.79], ["2011-08-31",588.5], ["2011-09-01",511.83], ["2011-09-02",721.15], ["2011-09-03",649.62],
    // ["2011-09-04",653.14], ["2011-09-06",900.31], ["2011-09-07",803.59], ["2011-09-08",851.19], ["2011-09-09",2059.24],
    // ["2011-09-10",994.05], ["2011-09-11",742.95], ["2011-09-12",1340.98], ["2011-09-13",839.78], ["2011-09-14",1769.21],
    // ["2011-09-15",1559.01], ["2011-09-16",2099.49], ["2011-09-17",1510.22], ["2011-09-18",1691.72],
    // ["2011-09-19",1074.45], ["2011-09-20",1529.41], ["2011-09-21",1876.44], ["2011-09-22",1986.02],
    // ["2011-09-23",1461.91], ["2011-09-24",1460.3], ["2011-09-25",1392.96], ["2011-09-26",2164.85],
    // ["2011-09-27",1746.86], ["2011-09-28",2220.28], ["2011-09-29",2617.91], ["2011-09-30",3236.63]];
    // var currYear = [["2011-08-01",796.01], ["2011-08-02",510.5], ["2011-08-03",527.8], ["2011-08-04",308.48],
    // ["2011-08-05",420.36], ["2011-08-06",219.47], ["2011-08-07",333.82], ["2011-08-08",660.55], ["2011-08-09",1093.19],
    // ["2011-08-10",521], ["2011-08-11",660.68], ["2011-08-12",928.65], ["2011-08-13",864.26], ["2011-08-14",395.55],
    // ["2011-08-15",623.86], ["2011-08-16",1300.05], ["2011-08-17",972.25], ["2011-08-18",661.98], ["2011-08-19",1008.67],
    // ["2011-08-20",1546.23], ["2011-08-21",593], ["2011-08-22",560.25], ["2011-08-23",857.8], ["2011-08-24",939.5],
    // ["2011-08-25",1256.14], ["2011-08-26",1033.01], ["2011-08-27",811.63], ["2011-08-28",735.01], ["2011-08-29",985.35],
    // ["2011-08-30",1401.58], ["2011-08-31",1177], ["2011-09-01",1023.66], ["2011-09-02",1442.31], ["2011-09-03",1299.24],
    // ["2011-09-04",1306.29], ["2011-09-06",1800.62], ["2011-09-07",1607.18], ["2011-09-08",1702.38],
    // ["2011-09-09",4118.48], ["2011-09-10",1988.11], ["2011-09-11",1485.89], ["2011-09-12",2681.97],
    // ["2011-09-13",1679.56], ["2011-09-14",3538.43], ["2011-09-15",3118.01], ["2011-09-16",4198.97],
    // ["2011-09-17",3020.44], ["2011-09-18",3383.45], ["2011-09-19",2148.91], ["2011-09-20",3058.82],
    // ["2011-09-21",3752.88], ["2011-09-22",3972.03], ["2011-09-23",2923.82], ["2011-09-24",2920.59],
    // ["2011-09-25",2785.93], ["2011-09-26",4329.7], ["2011-09-27",3493.72], ["2011-09-28",4440.55],
    // ["2011-09-29",5235.81], ["2011-09-30",6473.25]];
    // var prevYear = [[03,50], [09,3000],[14,5000],[20,3000],[26,5000]];
    // var currYear = [[02,2100], [11,50500], [15,7000], [22,25000],[28,45000]];
    <?php 
	    $output_pre_mon = "";
	    $prev_month_year = '';
	 	foreach ($c3 as $c) {
	 			$date = new \DateTime($c->created_at);
   				$day = $date->format('d');
				$output_pre_mon = $output_pre_mon."[".$day.','.$c->total.'],';
				$date_times= strtotime($c->created_at);
    			$prev_month_year = date('M-Y', $date_times);
    			// $prev_month = date('M', $date_times);
		    }    
    	$output_pre_mon = substr($output_pre_mon, 0, -1);
    ?>	
	var pre_month = [<?php echo $output_pre_mon; ?>]; 

     
    <?php 
     	$output_curr_mon = "";
     	$curr_month_year = "";
	 	foreach ($c2 as $c) {
	 			$date = new \DateTime($c->created_at);
   				$day = $date->format('d');
				$output_curr_mon = $output_curr_mon."[".$day.','.$c->total.'],';
				$date_times= strtotime($c->created_at);
    			$curr_month_year = date('M-Y', $date_times);
    			// $curr_month = date('M', $date_times);
		    }    
    	$output_curr_mon = substr($output_curr_mon, 0, -1);
    ?>	
	var curr_month = [<?php echo $output_curr_mon; ?>];


    var plot1 = $.jqplot("chart1", [pre_month, curr_month], {
        seriesColors: ["rgba(78, 135, 194, 0.7)", "rgb(211, 235, 59)"],
        title: 'Sales Analyst',
        highlighter: {
            show: true,
            formatString:'%s-(%s)',
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
                label: '<?php echo $prev_month_year; ?>'
            },
            {
                label: '<?php echo $curr_month_year; ?>'
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
                //renderer: $.jqplot.CategoryAxisRenderer,
                //ticks:[0,30],
            //     // tickRenderer: $.jqplot.CanvasAxisTickRenderer,
            //     // tickOptions: {
            //     //     formatString: "%b %e",
            //     //     angle: -30,
            //     //     textColor: '#dddddd'
            //     // },
               	min: 1,
                max: 31,
                tickInterval: 1,
            //     // drawMajorGridlines: false
            },
            yaxis: {
                renderer: $.jqplot.LogAxisRenderer,
                pad: 0,
                rendererOptions: {
                    minorTicks: 1
                },
                tickOptions: {
                    formatString: "%'d",
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
<?php  //print_r($c3);
//print_r($c2);
//echo $output;
?>
<div class="grid_container">
            <!--
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
			-->
			<span class="clear"></span>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon shopping_cart_3"></span>
						<h6>Recent Sales</h6>
					</div>
					<div class="widget_content">
						<table class="display data_tbl_search">
						<thead>
						<tr>
							<th>
								ID
							</th>
							<th>
								Name
							</th>
							<th>
								Sales Date
							</th>
							<th>
								 View
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i=1; ?>
						@foreach ($sales_info as $sale)
							<tr class="tr_even">
	    						<td class="center">{{ $i }}</td>
	    						<td class="center">{{ $sale->name }}</td>
	    						<td class="center">{{ $sale->salesdate }}</td>
	    						<td class="center"><span><a class="action-icons c-edit" href="physicalsales/view/{{ $sale->id }}" title="View" target="_blank">View</a></span></td>
	    					</tr>
                         <?php $i++; ?> 							
						@endforeach
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon shopping_cart_3"></span>
						<h6>Recent Purchases</h6>
					</div>
					<div class="widget_content">
						<table class="display data_tbl_search">
						<thead>
						<tr>
							<th>
								 ID
							</th>
							<th>
								Name
							</th>
							<th>
								Purchase Date
							</th>
							<th>
								View
							</th>
						</tr>
						</thead>
						<tbody>
						<?php $i=1; ?>
						@foreach ($purchase_info as $purchase)
							<tr class="tr_even">
	    						<td class="center">{{ $i }}</td>
	    						<td class="center">{{ $purchase->name }}</td>
	    						<td class="center">{{ $purchase->purchasedate }}</td>
	    						<td class="center"><span><a class="action-icons c-edit" href="purchase/view/{{ $purchase->id }}" title="View" target="_blank">View</a></span></td>
	    					</tr>
                          <?php $i++; ?> 							
						@endforeach
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<span class="clear"></span>
			<div class="social_activities">
				<div class="activities_s"  style="background-color:#A4BDFB">
					<div class="block_label">
						Total Sales:<span><?php foreach($c4 as $c){ $sales=$c->sales; echo $sales;}?></span>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				<div class="activities_s" style="background-color:#A4BDFB">
					<div class="block_label">
						Total Cash<span><?php foreach($c5 as $c){ $cash=$c->cash; echo $cash; }?></span>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				<div class="activities_s" style="background-color:#A4BDFB">
					<div class="block_label">
						Total Due<span><?php  echo number_format($sales-$cash,2,".",""); ?></span>
					</div>
					<span class="badge_icon bank_sl"></span>
				</div>
				
				<div class="user_s" style="background-color:#A4BDFB">
					<div class="block_label">
						Total Inventory<span>12000</span>
					</div>
					<span class="badge_icon archives_sl"></span>
				</div>
			</div>
			<span class="clear"></span>
			<div class="grid_9">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Today Collection</h6>
					</div>
					<div class="widget_content">
					
						<table class="display">
						<thead>
						<tr>
							<th>
								 Bank
							</th>
							<th>
								 Cash
							</th>
							<th>
								BKash
							</th>
							<th>
								 SAP
							</th>
							<th>
								 KCS
							</th>
							<th>
								 MBank
							</th>
							<th>
								Total
							</th>
							
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="center">
							     
								 <?php foreach($c6 as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								  <?php foreach($c7 as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								 <?php foreach($bkash as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								 <?php foreach($sap as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								 <?php foreach($kcs as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								 <?php foreach($mbank as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							<td class="center">
								<a href="dailycollection" target="_blank" ><?php foreach($c5 as $c){ $cash=$c->cash; echo $cash; }?></a>
							</td>
							
						</tr>
						
						
						</tbody>
						
						</table>
					</div>
				</div>
</div>

		<div class="grid_3">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon blocks_images"></span>
						<h6>Contra Voucher</h6>
					</div>
					<div class="widget_content">
					
						<table class="display">
						<thead>
						<tr>
							<th>
								Total
							</th>
						
							
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="center">
							<?php foreach($c8 as $c){ $cash=$c->cash; echo $cash; }?>
							</td>
							
						</tr>
						
						
						</tbody>
						
						</table>
					</div>
				</div>
</div>
			<span class="clear"></span>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon shopping_cart_3"></span>
						<h6>Bank Account Information</h6>
					</div>
					<div class="widget_content">
						<table class="display data_tbl_search">
						<thead>
						<tr>
							<th>
								ID
							</th>
							<th>
								Account Code
							</th>
							<th>
								Account Name
							</th>
							
						</tr>
						</thead>
						<tbody>
						<?php $i=1; ?>
						@foreach ($bankaccount_info as $bankaccount)
							<tr class="tr_even">
	    						<td class="center">{{ $i }}</td>
	    						<td class="center">{{ $bankaccount->code }}</td>
	    						<td class="center">{{ $bankaccount->name }}</td>
	    					</tr>
                         <?php $i++; ?> 	   							
						@endforeach
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="grid_6">
				<div class="widget_wrap">
					<div class="widget_top">
						<span class="h_icon shopping_cart_3"></span>
						<h6>Recent Order</h6>
					</div>
					<div class="widget_content">
						<table class="display data_tbl_search">
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
							<td class="center">
								 #36542
							</td>
							<td class="center">
								 Gold Pack
							</td>
							<td class="center">
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td class="center">
								 $50/m
							</td>
						</tr>
						<tr class="tr_odd">
							<td class="center">
								 #38544
							</td>
							<td class="center">
								 Silver Pack
							</td>
							<td class="center">
								<span class="badge_style b_confirmed">Confirmed</span>
							</td>
							<td class="center">
								 $20/m
							</td>
						</tr>
						<tr class="tr_even">
							<td class="center">
								 #39544
							</td>
							<td class="center">
								<span>Platinum Pack</span>
							</td>
							<td class="center">
								<span class="badge_style b_pending">Pending</span>
							</td>
							<td class="center">
								 $80/m
							</td>
						</tr>
						<tr class="tr_even">
							<td class="center">
								 #39542
							</td>
							<td class="center">
								<span>Platinum Pack</span>
							</td>
							<td class="center">
								<span class="badge_style b_confirmed">Confirmed</span>
							</td>
							<td class="center">
								 $80/m
							</td>
						</tr>
						</tbody>
						</table>
					</div>
				</div>
			</div>
			<span class="clear"></span>
		</div>
		<span class="clear"></span>
@endsection
