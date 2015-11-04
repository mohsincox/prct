<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Combo;
use App\Models\Companyprofile;
use App\Http\Controllers\Common\CommonController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;


class ReportlossprofitController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('reportlossprofit');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		
		return view('reportlossprofit');
		

	}
	public function addnew()
	{
		
		//return view('createphysicalsale');
	}

     public function today(Request $request)
	{
	        $date=Combo::callcombo('currentdate');
			//print_r($date);
			foreach($date as $d){
				$curdate=$d->curdate;
			}
			$fromdate=CommonController::date_format($curdate);
            $todate=CommonController::date_format($curdate);
			$fromdate=date("Y-m-d");
            $todate=date("Y-m-d");
            $var = array($fromdate,$todate);
            $spname="totalsales";
            $value=Info::callinfo($var,$spname);
			$spname1="totalcash";
            $value1=Info::callinfo($var,$spname1);
			//print_r($value);
			//print_r($value1) ;
			return view('reportlossprofitview', compact('value','value1','fromdate','todate'));
            
	}
	
	 public function fromtoday(Request $request)
	{
	        $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($fromdate,$todate);
			//print_r($var);
            $spname="totalsales";
            $value=Info::callinfo($var,$spname);
			$spname1="totalcash";
            $value1=Info::callinfo($var,$spname1);
			//print_r($value);
			return view('reportlossprofitview', compact('value','value1','fromdate','todate'));        
	}
	public function fromtodaysalesview(Request $request,$fromdate,$todate)
	{
		$profile=Companyprofile::get();
		//print_r($profile);
		foreach($profile as $com){
			$id=$com->id;
			$cname=$com->name;
			$address=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		
		
		     $var = array($fromdate,$todate);
			//print_r($var);
            $spname="totalsalesview";
            $value=Info::callinfo($var,$spname);
			PDF::AddPage();
			$html1=' 
		<p></p>
					<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>	
					</div>

<div>

<h3>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<u>Total Sales</h3></u></div>
<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%">&nbsp;&nbsp;&nbsp;&nbsp;ID</th>
				<th>Name</th>
				<th>Sales Date</th>
				<th style="width:40%">Customer Name</th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		foreach($value as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->id.'"   target="_blank">'.$valu->name.'</a></td>
				
				<td style="background-color:#ffffff;">'.$valu->salesdate.'</td>
				
				<td style="background-color:#ffffff;">'.$valu->cname.'</td></tr>';
				$html2=$html2.$html;
				$i++;
		} 			
		$html3='</table></div>'; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		    PDF::Output('fromtodaysalesview.pdf');
	}
	
	
	public function pdf(Request $request,$pid)
	{
		
		$profile=Companyprofile::get();
		//print_r($profile);
		foreach($profile as $com){
			$id=$com->id;
			$cname=$com->name;
			$address=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		$var = array($pid);
		$spname="viewsales";
		$value=Info::callinfo($var,$spname);
		foreach($value as $valu){ 
				$id=$valu->id;
				$sailname=$valu->sname;
				$salesdate=$valu->salesdate;
				$cusname=$valu->cname;
				$phone=$valu->phone;
				$preaddress=$valu->preaddress;
				
		} 		
		//$pdate=date_create($purchasedate);
		//$sdate=date_create($suppliersbilldate);
		
		
		$spname1="salesdetailsview";
		$value1=Info::callinfo($var,$spname1);
		$sum=0;
		foreach($value1 as $valu){ 
				$a=$valu->amount;	
				$sum=$sum+$a;
		} 
		PDF::AddPage();
		 
		$html1='
			<p></p>
					<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>	
					</div><div>
					<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
			   <th>SI No</th>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Measurement Unit</th>
				<th>Rate</th>
				<th>Amount</th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$sum=0;
		foreach($value1 as $valu){ 
				$html='<tr><td>'.$i.'</td>
				<td style="background-color:#ffffff;">'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">'.$valu->quantity.'</td>
				<td style="background-color:#ffffff;">'.$valu->mname.'</td>
				<td style="background-color:#ffffff;">'.$valu->rate.'</td>
				<td style="background-color:#ffffff;">'.$valu->amount.'</td></tr>';
				$html2=$html2.$html;
				$i++;
				$sum=$sum+$valu->amount;
		} 
		$html3='<tr><td colspan="5" align="right">';
		$html4='';
        
 		$html5='Total:</td><td style="background-color:#ffffff;">'.number_format($sum, 2, '.', '').'</td></tr>';
		
		$html6='</table><h4>Amount in word:'.CommonController::convertNumberToWord($sum).' Taka Only</h4></div>
		                  <div></div>
						<div class="col-md-12"  style=" background-color: #ffffff;color:#000000;">
						<h3>&nbsp;&nbsp;&nbsp; Received By
		                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												
						  Prepared By
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						


						Approved By
						  </h3>
						
						
							
						</div>'
						; 
		
        $html=$html1.$html2.$html3.$html4.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('sales.pdf');
		
	}
	
	public function fromtodaycashview(Request $request,$fromdate,$todate)
	{
		$profile=Companyprofile::get();
		//print_r($profile);
		foreach($profile as $com){
			$id=$com->id;
			$cname=$com->name;
			$address=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		
		
		     $var = array($fromdate,$todate);
			//print_r($var);
            $spname="totalcashview";
            $value=Info::callinfo($var,$spname);
			//print_r($value); die();
			PDF::AddPage();
			$html1=' 
			<p></p>
					<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>	
					</div>

<div>

<h3>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<u>Total Cash</h3></u></div>
<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%">&nbsp;&nbsp;&nbsp;&nbsp;ID</th>
				<th>Voucher NO.</th>
				<th>Voucher Date</th>
				<th style="width:25%">Customer Name</th>
				<th>Amount</th>
			  </tr>
';
			  
		$html2= '';
		$i=1;
		$sum=0;
		foreach($value as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$valu->vnno.'</a></td>
				
				<td style="background-color:#ffffff;">'.$valu->vdate.'</td>
				
				<td style="background-color:#ffffff;">'.$valu->name.'</td>
				
				<td style="background-color:#ffffff;">'.$valu->amount.'</td></tr>';
				$html2=$html2.$html;
				$i++;
				$sum=$sum+$valu->amount;
		} 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">';
		$html4='';
        
 		$html5='Total:</td><td style="background-color:#ffffff;">'.number_format($sum, 2, '.', '').'</td></tr>';
		
		$html6='</table></div><div></div>
		
						<div class="col-md-12"  style=" background-color: #ffffff;color:#000000;">
						<h3>&nbsp;&nbsp;&nbsp; Received By
		                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												
						  Prepared By
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						


						Approved By
						  </h3>
						
						
							
						</div>'
						; 
		
        $html=$html1.$html2.$html3.$html4.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('sales.pdf');
	}
}
