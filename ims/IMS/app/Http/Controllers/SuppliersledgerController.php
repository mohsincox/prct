<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Combo;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Bankaccount;
use App\Models\Suppliersledger;
use App\Models\Voucher;
use App\Models\Companyprofile;
use App\Http\Controllers\Common\CommonController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use DB;


class SuppliersledgerController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
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
	    $cid=$request->input('sid');
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
			if($cid==0){
				$spname="suppliersledgerall";
			}else{
				 $spname="suppliersledger";
			}
           
			
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
					foreach($value as $valu){ 
					$ccode=$valu->scode;
					$cname=$valu->sname;
		
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
			   $openbalance = DB::table('suppliers')->sum('openbalance');
			   $cname='All Suppliers A/C';
			  // echo $openbalance;
			}else{
			   $suppliers = DB::table('suppliers')->where('id', $cid)->first();
               $openbalance=$suppliers->openbalance; 	
			}
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
									SUPPLIER A/C</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
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
		$ctotal=0;
		$dtotal=0;
		foreach($value as $valu){ 
		        if($valu->vstatus==1 OR $valu->pustatus==1){
					if($valu->vid!=NULL){
						if($valu->vtype==1){
							$acc='By BANK A/C';
						}else if($valu->vtype==2){
							$acc='By CASH A/C';
						}
					}else if($valu->sid!=NULL){
						$acc='To PURCHASE A/C';
					}
					$html='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$valu->created_at.'</td>
					<td style="background-color:#ffffff;">'.$acc.'</td>
					';
					if($valu->pavoucher!=NULL){
					$h6='<td style="background-color:#ffffff;">Payment</td>';
					}else{
					$h6='<td style="background-color:#ffffff;">Purchase</td>';
					}
					
					if($valu->pavoucher!=NULL){
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->vid.'/'.$valu->vtype.'"   target="_blank">'.$valu->pavoucher.'</a></td>';
					}else{
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/purchase/pdf/'.$valu->puid.'"   target="_blank">'.$valu->puvoucher.'</a></td>';
					}
					
					if($valu->puvoucher!=NULL){
				    $credit=	$valu->amount;
					//$dtotal=$dtotal+$debit;
					$ctotal=$ctotal+$credit;
                    $debit=0.00;					
					$h1='<td style="background-color:#ffffff;">&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;'.$credit.'</td>';
					//$debit=$debit+$valu->amount;
					}
					
					if($valu->pavoucher!=NULL){
					$debit= $valu->amount;	
					//$ctotal=$ctotal+$credit;
					$dtotal=$dtotal+$debit;
					$credit=0.00;
					$h1='<td style="background-color:#ffffff;">&nbsp;'.$debit.'</td>
					<td style="background-color:#ffffff;">&nbsp;</td>';
					//$credit=$credit+$valu->amount;
					}
					
					$ob=($ob+$credit)-$debit;
					$h3='<td>'.number_format($ob, 2, '.', '').'</td></tr>';
					$htmll=$html.$h6.$h5.$h1.$h3;
					$html2=$html2.$htmll;
				}	
				$i++;
				$sum=$sum+$valu->amount;
		} 			
		$subtotal=($openbalance+$ctotal)-$dtotal;
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;"></td><td>'.number_format($dtotal, 2, '.', '').'</td><td>'.$ctotal.'</td><td>'.number_format($subtotal, 2, '.', '').'</td></tr>';
		
        
 		$html5='<tr><td colspan="6" align="right" style="background-color:#ffffff;">Closing Balance:</td><td>&nbsp;&nbsp;'.number_format($ob, 2, '.', '').'</td></tr>';
		$html6='</table></div>';
		//$html4='DEBIT:'.$debit.'CRedit:'.$credit.'';				
		
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('suppliersledger.pdf');
		}else{
			echo '<h1 style="color:red;">No data found<h1>';
		}

		
	 }
	 
		    
		else if($date=='fromdate'){
			
			

		   //echo $cid; die();
		    $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
			//$fromdate=date("Y-m-d");
            //$todate=date("Y-m-d");
            $var = array($cid,$fromdate,$todate);
			//print_r($var);
			if($cid==0){
				$spname="suppliersledgerall";
			}else{
				 $spname="suppliersledger";
			}
           
			
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
					foreach($value as $valu){ 
					$ccode=$valu->scode;
					$cname=$valu->sname;
		
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
			   $openbalance = DB::table('suppliers')->sum('openbalance');
			   $cname='All Suppliers A/C';
			  // echo $openbalance;
			}else{
			   $suppliers = DB::table('suppliers')->where('id', $cid)->first();
               $openbalance=$suppliers->openbalance; 	
			}
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
									SUPPLIER A/C</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
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
		$ctotal=0;
		$dtotal=0;
		foreach($value as $valu){ 
		        if($valu->vstatus==1 OR $valu->pustatus==1){
					if($valu->vid!=NULL){
						if($valu->vtype==1){
							$acc='By BANK A/C';
						}else if($valu->vtype==2){
							$acc='By CASH A/C';
						}
					}else if($valu->sid!=NULL){
						$acc='To PURCHASE A/C';
					}
					$html='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$valu->created_at.'</td>
					<td style="background-color:#ffffff;">'.$acc.'</td>
					';
					if($valu->pavoucher!=NULL){
					$h6='<td style="background-color:#ffffff;">Payment</td>';
					}else{
					$h6='<td style="background-color:#ffffff;">Purchase</td>';
					}
					
					if($valu->pavoucher!=NULL){
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->vid.'/'.$valu->vtype.'"   target="_blank">'.$valu->pavoucher.'</a></td>';
					}else{
					$h5='<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/purchase/pdf/'.$valu->puid.'"   target="_blank">'.$valu->puvoucher.'</a></td>';
					}
					
					if($valu->puvoucher!=NULL){
				    $credit=	$valu->amount;
					//$dtotal=$dtotal+$debit;
					$ctotal=$ctotal+$credit;
                    $debit=0.00;					
					$h1='<td style="background-color:#ffffff;">&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;'.$credit.'</td>';
					//$debit=$debit+$valu->amount;
					}
					
					if($valu->pavoucher!=NULL){
					$debit= $valu->amount;	
					//$ctotal=$ctotal+$credit;
					$dtotal=$dtotal+$debit;
					$credit=0.00;
					$h1='<td style="background-color:#ffffff;">&nbsp;'.$debit.'</td>
					<td style="background-color:#ffffff;">&nbsp;</td>';
					//$credit=$credit+$valu->amount;
					}
					
					$ob=($ob+$credit)-$debit;
					$h3='<td>'.number_format($ob, 2, '.', '').'</td></tr>';
					$htmll=$html.$h6.$h5.$h1.$h3;
					$html2=$html2.$htmll;
				}	
				$i++;
				$sum=$sum+$valu->amount;
		} 			
		$subtotal=($openbalance+$ctotal)-$dtotal;
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;"></td><td>'.number_format($dtotal, 2, '.', '').'</td><td>'.$ctotal.'</td><td>'.number_format($subtotal, 2, '.', '').'</td></tr>';
		
        
 		$html5='<tr><td colspan="6" align="right" style="background-color:#ffffff;">Closing Balance:</td><td>&nbsp;&nbsp;'.number_format($ob, 2, '.', '').'</td></tr>';
		$html6='</table></div>';
		//$html4='DEBIT:'.$debit.'CRedit:'.$credit.'';				
		
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('suppliersledger.pdf');
		}else{
			echo '<h1 style="color:red;">No data found<h1>';
		}



		
        } 
	}
	
	
}
