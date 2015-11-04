<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Combo;
use App\Models\Companyprofile;
use App\Http\Controllers\Common\CommonController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;

class ReportpurchaseController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('reportpurchases');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		
		return view('reportpurchase');
		

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
            $spname="rptpurchase";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			return view('reportpurchaseview', compact('value','fromdate','todate'));
            
	}
	
	 public function fromtoday(Request $request)
	{
	        $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($fromdate,$todate);
			//print_r($var);
            $spname="rptpurchase";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			return view('reportpurchaseview', compact('value','fromdate','todate'));
            
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
            $spname="rptpurchase";
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
<u>Reports on Purchases</h3></u></div>
<div><strong> From Date:'.$fromtoday.' &nbsp;&nbsp;&nbsp;&nbsp; To Date:'.$today.' </strong></div></div>
<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%">Serial No.</th>
				<th>Supplier Name</th>
				<th>Purchases Bill No.</th>
				<th>Purchases Bill Date</th>
				
				<th>Supplier Bill No.</th>
				<th>Supplier Bill Date</th>
				<th>Amount</th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$total=0.00;
		foreach($value as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
			    <td style="background-color:#ffffff;">'.$valu->suppliersname.'</td>
				<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/purchase/pdf/'.$valu->id.'"   target="_blank">'.$valu->name.'</a></td>
				<td style="background-color:#ffffff;">'.$valu->purchasedate.'</td>
				<td style="background-color:#ffffff;">'.$valu->suppliersbillno.'</td>
				<td style="background-color:#ffffff;">'.$valu->suppliersbilldate.'</td>
				<td style="background-color:#ffffff;">'.$valu->amount.'</td>
				</tr>';
				$html2=$html2.$html;
				$i++;
				$total=$total+$valu->amount;
		} 			
		$html3='<tr><td  colspan="5"></td><td>Total</td><td>'.number_format($total, 2, '.', '').'</td></tr></table></div>'; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		    PDF::Output('purchases.pdf');


}
}
