<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Combo;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Bankaccount;
use App\Models\Voucher;
use App\Models\Companyprofile;
use App\Http\Controllers\Common\CommonController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use DB;

class CustomersledgerController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function report()
	{
		$s=Supplier::get();
		$c=Customer::get();
		$bankaccount=Bankaccount::joining();
		$voucher = Voucher::orderBy('id', 'desc')->take(10)->get();
		return view('ledger', compact('bankaccount', 'c','s','voucher'));
	}
	public function addnew()
	{
		
		//return view('createphysicalsale');
	}

     public function fromtoday(Request $request)
	{
		$profile=Companyprofile::get();
		//print_r($profile);
		foreach($profile as $com){
			$id=$com->id;
			$coname=$com->name;
			$address=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		$date=$request->input('submit');
	 $cid=$request->input('cid');
	 if($date=='today'){
		   $date=Combo::callcombo('currentdate');
			//print_r($date);
			foreach($date as $d){
				$curdate=$d->curdate;
			}
			$fromdate=CommonController::date_format($curdate);
            $todate=CommonController::date_format($curdate);
			$fromdate=date("Y-m-d");
            $todate=date("Y-m-d");
            $var = array($cid,$fromdate,$todate);
			//print_r($var);
			//die();
			if($cid==0){
				$spname="customersledgerall";
			}else{
				 $spname="customersledger";
			}
           
		   $value=Info::callinfo($var,$spname);
		   /*if($value==NULL)
			   echo "No data found";
		   else
			print_r($value);
			die();
			*/
			
            //$value=Info::callinfo($var,$spname);
			//print_r($value);
			$c = DB::table('customers')->where('id',$cid)->first();
			//$c = DB::table('customersledger')->where('id',$cid)->first();
			$previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));
			$debitbalance= DB::table('customersledger')
			->select('customersledger.id','customersledger.rv','customersledger.sv','customers.name as cname','customers.code as ccode','customersledger.amount','customersledger.created_at')
			->join('customers', 'customersledger.cid', '=', 'customers.id')
			->where('customersledger.rv', '<>', 0)
			->where('customersledger.cid' , $cid)
			->where('customersledger.created_at', '<=', $previousdate)
			->sum('customersledger.amount');
			//->get();
			//print_r($debitbalance);
			
			$creditbalance= DB::table('customersledger')
			->select('customersledger.id','customersledger.rv','customersledger.sv','customers.name as cname','customers.code as ccode','customersledger.amount','customersledger.created_at')
			->join('customers', 'customersledger.cid', '=', 'customers.id')
			->where('customersledger.sv', '<>', 0)
			->where('customersledger.cid' , $cid)
			->where('customersledger.created_at', '<=', $previousdate)
			->sum('customersledger.amount');
			//->get();
			//print_r($creditbalance);
			$openbalance=$c->openbalance+$debitbalance-$creditbalance;
			//echo $openbalance;
			//$bid=$b->bankid;
			$name_code = DB::table('customersledger')
			->select('customers.name as cname','customers.code as ccode','customersledger.created_at','voucher.vstatus','voucher.vnno as rvoucher','voucher.type as vtype','sales.status as sstatus','sales.name as svoucher')
			->join('customers', 'customersledger.cid', '=', 'customers.id')
			->join('voucher', 'customersledger.rv', '=', 'voucher.id')
			->join('sales', 'customersledger.sv', '=', 'sales.id')
			->where('customersledger.cid', $cid)
			->where('sales.customerid', $cid)
			->where('voucher.cid', $cid)
			->where('customersledger.created_at', $todate)
			->get();
			foreach($name_code as $valu){ 
					$ccode=$valu->ccode;
					$cname=$valu->cname;
		
		    }
			foreach($value as $valu){ 
					$ccode=$valu->ccode;
					$cname=$valu->cname;
		
		    }
			/*
			if($name_code==null)
				echo "Data Not Found";
			else
			print_r($name_code);
			
			die();
				*/	
			   
			     
              			   
			
			PDF::AddPage('A4');
			$html1=' 
								<p></p>
					<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$coname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>	
					</div>
					<div>

					   <h2>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									CUSTOMER A/C</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
								</h4>
								 
							</div>
							
								<div>

								 <table border="1" style="width:100%; padding:20px;">
									<tr>
										
										<td style="width:20%">Accounts Code:</td>
										
										<td style="width:80%">'.$ccode.'</td>
										
									</tr>
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$cname.'</td>
										
									</tr>
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>
				
				<th >&nbsp;Voucher Type</th>
				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Debit</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Credit</th>
				<th>&nbsp;&nbsp;Balance</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;" colspan="3">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		
		foreach($value as $valu){ 
		        if($valu->vstatus==1 OR $valu->sstatus==1){
					if($valu->vid!=NULL){
						if($valu->vtype==3){
							$acc='By BANK A/C';
						}else if($valu->vtype==4){
							$acc='By CASH A/C';
						}else if($valu->vtype==6){
							$acc='By BKash';
						}else if($valu->vtype==7){
							$acc='By SAP';
						}else if($valu->vtype==8){
							$acc='By KCS';
						}else if($valu->vtype==9){
							$acc='By MBank';
						}
					}else if($valu->sid!=NULL){
						$acc='To SALES A/C';
					}
					$html='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$valu->created_at.'</td>
					<td style="background-color:#ffffff;">'.$acc.'</td>
				';
					if($valu->rvoucher!=NULL){
					$h6='<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt</td>';
					}else{
					$h6='<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sales</td>';
					}
					
					if($valu->rvoucher!=NULL){
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->vid.'/'.$valu->vtype.'"   target="_blank">'.$valu->rvoucher.'</a></td>';
					}else{
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->sid.'"   target="_blank">'.$valu->svoucher.'</a></td>';
					}
					
					if($valu->svoucher!=NULL){
				    $debit=	$valu->amount;
					
                    				
					$h1='<td style="background-color:#ffffff;">&nbsp;'.$debit.'</td>
					<td style="background-color:#ffffff;">&nbsp;</td>';
					//$debit=$debit+$valu->amount;
					}
					
					if($valu->rvoucher!=NULL){
					$credit= $valu->amount;	
					
					
					$h1='<td style="background-color:#ffffff;">&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;'.$credit.'</td>';
					//$credit=$credit+$valu->amount;
					}
					
					
					$h3='<td>'.number_format($openbalance, 2, '.', '').'</td></tr>';
					$htmll=$html.$h6.$h5.$h1.$h3;
					$html2=$html2.$htmll;
				}	
				$i++;
				//$sum=$sum+$valu->amount;
		} 			
		//$subtotal=($openbalance+$dtotal)-$ctotal;
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;"></td><td>'.number_format($debitbalance, 2, '.', '').'</td><td>'.number_format($creditbalance, 2, '.', '').'</td><td>subtotal</td></tr>';
		
        
 		$html5='<tr><td colspan="6" align="right" style="background-color:#ffffff;">Closing Balance:</td><td>&nbsp;&nbsp;</td></tr>';
		$html6='</table></div>';
		//$html4='DEBIT:'.$debit.'CRedit:'.$credit.'';				
		
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('customersledger.pdf');
			
		
	 }
	 
		    
		else if($date=='fromdate'){
			//echo $cid; die();
		    $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($cid,$fromdate,$todate);
			//print_r($var);
			if($cid==0){
				$spname="customersledgerall";
			}else{
				 $spname="customersledger";
			}
           
			
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
					foreach($value as $valu){ 
					$ccode=$valu->ccode;
					$cname=$valu->cname;
		
		    }
		
		//print_r($value);die();
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
		if($value!=NULL){
		
		    $date=date('Y-m-d', strtotime($fromdate . " - 1 day")); 
		    $v= array($cid,$date);
			$s="customerbalance";
            $cvalue=Info::callinfo($v,$s);
			//print_r($cvalue);
			if($cvalue!=NULL){
				//print_r($cvalue);
			}
			if($cid==0){
			   $openbalance = DB::table('customers')->sum('openbalance');
			   $cname='All Customers A/C';
			  // echo $openbalance;
			}else{
			   $customers = DB::table('customers')->where('id', $cid)->first();
               $openbalance=$customers->openbalance; 	
			}
			PDF::AddPage('A4');
			$html1=' 
								<p></p><p></p>
					<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$coname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>	
					</div>
					<div>

					   <h2>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									CUSTOMER A/C</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
								'.date_format($fdate,"d-M-Y").'&nbsp;&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
								<div>

								 <table border="1" style="width:100%; padding:20px;">
									<tr>
										
										<td style="width:20%">Accounts Code:</td>
										
										<td style="width:80%">'.$ccode.'</td>
										
									</tr>
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$cname.'</td>
										
									</tr>
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>
				
				<th >&nbsp;Voucher Type</th>
				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Debit</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Credit</th>
				<th>&nbsp;&nbsp;Balance</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;" colspan="3">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$sum=0;
		$credit=0;
		$debit=0;
        $ob=$openbalance;
		$dtotal=0;
		$ctotal=0;
		foreach($value as $valu){ 
		        if($valu->vstatus==1 OR $valu->sstatus==1){
					if($valu->vid!=NULL){
						if($valu->vtype==3){
							$acc='By BANK A/C';
						}else if($valu->vtype==4){
							$acc='By CASH A/C';
						}else if($valu->vtype==6){
							$acc='By BKash';
						}else if($valu->vtype==7){
							$acc='By SAP';
						}else if($valu->vtype==8){
							$acc='By KCS';
						}else if($valu->vtype==9){
							$acc='By MBank';
						}
					}else if($valu->sid!=NULL){
						$acc='To SALES A/C';
					}
					$html='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$valu->created_at.'</td>
					<td style="background-color:#ffffff;">'.$acc.'</td>
					';
					if($valu->rvoucher!=NULL){
					$h6='<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receipt</td>';
					}else{
					$h6='<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sales</td>';
					}
					
					if($valu->rvoucher!=NULL){
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->vid.'/'.$valu->vtype.'"   target="_blank">'.$valu->rvoucher.'</a></td>';
					}else{
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->sid.'"   target="_blank">'.$valu->svoucher.'</a></td>';
					}
					
					if($valu->svoucher!=NULL){
				    $debit=	$valu->amount;
					$dtotal=$dtotal+$debit;
                    $credit=0.00;					
					$h1='<td style="background-color:#ffffff;">&nbsp;'.$debit.'</td>
					<td style="background-color:#ffffff;">&nbsp;</td>';
					//$debit=$debit+$valu->amount;
					}
					
					if($valu->rvoucher!=NULL){
					$credit= $valu->amount;	
					$ctotal=$ctotal+$credit;
					$debit=0.00;
					$h1='<td style="background-color:#ffffff;">&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;'.$credit.'</td>';
					//$credit=$credit+$valu->amount;
					}
					
					$ob=($ob+$debit)-$credit;
					$h3='<td>'.number_format($ob, 2, '.', '').'</td></tr>';
					$htmll=$html.$h6.$h5.$h1.$h3;
					$html2=$html2.$htmll;
				}	
				$i++;
				$sum=$sum+$valu->amount;
		} 			
		$subtotal=($openbalance+$dtotal)-$ctotal;
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;"></td><td>'.number_format($dtotal, 2, '.', '').'</td><td>'.number_format($ctotal, 2, '.', '').'</td><td>'.number_format($subtotal, 2, '.', '').'</td></tr>';
		
        
 		$html5='<tr><td colspan="6" align="right" style="background-color:#ffffff;">Closing Balance:</td><td>&nbsp;&nbsp;'.number_format($subtotal, 2, '.', '').'</td></tr>';
		$html6='</table></div>';
		//$html4='DEBIT:'.$debit.'CRedit:'.$credit.'';				
		
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('customersledger.pdf');
		}else{
			echo '<h1 style="color:red;">No data found<h1>';
		}		
        } 
	}
	
	
}
