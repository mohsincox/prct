<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Companyprofile;
use App\Models\Info;
use App\Models\Combo;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\CommonController;
use Session;
use PDF;

class ReportssaleController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('reportssale');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		
		return view('reportsales');
		

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
            $var = array($fromdate,$todate);
            $spname="rptsales";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			return view('reportsaleview', compact('value','fromdate','todate'));
            
	}
	
	 public function fromtoday(Request $request)
	{
	        $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($fromdate,$todate);
			//print_r($var);
            $spname="rptsales";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			return view('reportsaleview', compact('value','fromdate','todate'));
            
	}
	
	 public function printpdf(Request $request,$fromtoday,$today)
	
		
		{
		
			$profile=Companyprofile::get();
		
		foreach($profile as $com){
			$cid=$com->id;
			$cname=$com->name;
			$address=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		    $var = array($fromtoday,$today);
            $spname="rptsales";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
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
<u>Reports on Sales</h3></u></div>
<div><strong> From Date:'.$fromtoday.' &nbsp;&nbsp;&nbsp;&nbsp; To Date:'.$today.' </strong></div></div>
<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="">Serial No.</th>
				<th style="">Customer Name</th>
				<th>Voucher No.</th>
				<th>Sales Date</th>
				<th>Gross Total</th>
				<th>Previous Due</th>
				<th>Present Balance</th>
				<th>Type</th>
				
			  </tr>';
			  
		$html2= '';
		$i=1;
		$sum=0;
		foreach($value as $valu){ 
		        if($valu->previousdue<=0){
					$previousdue=' ';
				}else{
					$previousdue=$valu->previousdue;
				}
				
				if($valu->presentbalance<=0){
					$presentbalance=' ';
				}else{
					$presentbalance=$valu->presentbalance;
				}
				
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;">'.$valu->cname.'</td>
				<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->id.'"   target="_blank">'.$valu->name.'</a></td>
				<td style="background-color:#ffffff;">'.$valu->salesdate.'</td>
				<td style="background-color:#ffffff;">'.$valu->gamount.'</td>
				<td style="background-color:#ffffff;">'.$previousdue.'</td>
				<td style="background-color:#ffffff;">'.$presentbalance.'</td>';
				if($valu->presentbalance<=0){
				$d='<td style="background-color:#ffffff; color:green">Cash</td>';
				}else{
					$d='<td style="background-color:#ffffff; color:red">Due</td>';
				}
				$e='</tr>';
				$html2=$html2.$html.$d.$e;
				$i++;
				$sum=$sum+$valu->gamount;
		} 
		$hl1='<tr>
			<td colspan="4" >Grant Total</td>
			<td>'.number_format($sum, 2, '.', '').'</td>
			<td style="background-color:#ffffff;"></td>
			<td style="background-color:#ffffff;"></td>
		</tr>';	
		$html2=$html2.$hl1;
		$html3='</table></div>'; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		    PDF::Output('sales.pdf');
	}

}
