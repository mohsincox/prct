<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Companyprofile;
use App\Models\Voucherbankpayment;
use App\Models\Voucherpayment;
use App\Models\Voucherbankreceive;
use App\Models\Voucherreceive;
use App\Models\Vouchercontra;
use App\Http\Controllers\Common\CommonController;
use App\Models\Bankaccount;
use App\Models\Customer;
use App\Models\Bankbook;
use App\Models\Coa;
use App\Models\Supplier;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Combo;
use DB;
use PDF;


class LedgerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('generalledger');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	
	public function index()
	{
		$s=Supplier::get();
		$c=Customer::get();
		$coa=Coa::get();
		$bankaccount=Bankaccount::joining();
		$voucher = Voucher::orderBy('id', 'desc')->take(10)->get();
		return view('ledger', compact('bankaccount', 'c','s','voucher','coa'));
	}
	
	public function bankbookview(Request $request)
	{$bankaccount=Bankaccount::joining();
	return view('bankbook', compact('bankaccount'));
	 
	}
	
	public function bankbook(Request $request)
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
		
		
		
		
	 $date=$request->input('submit');
	 $baccid=$request->input('baccid');
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
           $var = array($baccid,$fromdate,$todate);
			//print_r($var);
            $spname="bankbook";
            $value=Info::callinfo($var,$spname);
			//print_r($value);die();
			$b = DB::table('bankaccount')->where('id',$baccid)->first();
			//$openbalance=$b->openbalance;
			$previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));
			$debitbalance= DB::table('bankbook')
			->join('voucher', 'voucher.id', '=', 'bankbook.vid')
			->where('bankbook.dc',1)
			->where('voucher.vstatus',1)
			->where('bankbook.baccid',$baccid)
			->where('bankbook.created_at', '<=', $previousdate)
			->sum('bankbook.amount');
			//echo $debitbalance; die();
			$creditbalance= DB::table('bankbook')
			->join('voucher', 'voucher.id', '=', 'bankbook.vid')
			->where('bankbook.dc',0)
			->where('voucher.vstatus',1)
			->where('bankbook.baccid',$baccid)
			->where('bankbook.created_at', '<=', $previousdate)
			->sum('bankbook.amount');
			$openbalance=$b->openbalance+$debitbalance-$creditbalance;
			$bid=$b->bankid;
			$bank = DB::table('bankinfo')->where('id',$bid)->first();
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
					
					             <h2>
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<u>Account Statement</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Bank Name:'.$bank->name.'</td>
										<td></td>
										<td>Opening Date:'.$b->opendate.'</td>
										
									</tr>
									<tr>
									
										<td>Current Deposite:'.$b->openbalance.'</td>
										<td></td>
										<td>Expire Date:'.$b->exdate.'</td>
										
									</tr>
									<tr>
									
										<td>A/C Number:'.$b->name.'</td>
										<td></td>
										<td>Interest Rate:'.$b->rate.'</td>
										
									</tr>
									<tr>
									
										<td>From:'.$fromdate.' To '.$todate.'</td>
										<td></td>
										<td>Currency:BDT</td>
										
									</tr>
									
									
								
								</table> 
							</div>
						
						<div>

								 <table border="1" style="width:100%; padding:20px;">
									<tr>
										
										<td style="width:20%">Accounts Code:</td>
										
										<td style="width:80%">'.$b->code.'</td>
										
									</tr>
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$b->name.'('.$bank->name.')</td>
										
									</tr>
									
									
								</table> 

		  <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Date</th>
				<th>Particulars</th>
				<th >&nbsp;Supplier Name</th>
				<th >&nbsp;Customer Name</th>
				<th >&nbsp;Voucher No</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Debit</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Credit</th>
				<th>&nbsp;&nbsp;Balance</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Balance B/F</th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th>&nbsp;&nbsp;'.number_format($openbalance, 2, '.', '').'</th>
			  </tr>';
			  $html2='';
			  $balance=$openbalance;
			  $debit=$balance;
			  $credit=0;

			  foreach($value as $v){		
			    if($v->type==5){
					 $h1= '<tr>
				<td style="background-color:#ffffff;">&nbsp;'.$v->created_at.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->sname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->cname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'/'.$v->status.'">'.$v->vnno.'</a></td>';
				}else{
					  $h1= '<tr>
				<td style="background-color:#ffffff;">&nbsp;'.$v->created_at.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->sname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->cname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->vnno.'</a></td>';
				} 
			   
				if($v->type==1){
					$balance=$balance-$v->amount;
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$credit=$credit+$v->amount;
				}else if($v->type==3){
					$balance=$balance+$v->amount;
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$debit=$debit+$v->amount;
				}
				else if($v->type==5){
					if($v->status==1){
						
					$balance=$balance+$v->amount;
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$debit=$debit+$v->amount;
					}
					else if($v->status==2){
					$balance=$balance-$v->amount;
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$credit=$credit+$v->amount;
					}
					else if($v->status==3){
					
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					if($v->dc==1){
						$balance=$balance+$v->amount;
						$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
						<td style="background-color:#ffffff;"></td>
						<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
						</tr>';
					}else if($v->dc==0){
						$balance=$balance-$v->amount;
						$h2='<td style="background-color:#ffffff;"></td>
						<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
						<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
						</tr>';
					}
					
					$debit=$debit+$v->amount;
					}
				}
				
				$h=$h1.$h2;
			    $html2=$html2.$h;
				}
				$total=$debit-$credit;
			 $h3='';
			$html2=$html2.$h3;	
			  $html3='</table></div>
						<div>
					
						</div>';
						
						 $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbp.pdf');
		}
			
	 
	 else if($date=='fromdate'){
		    $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($baccid,$fromdate,$todate);
            $spname="bankbook";
            $value=Info::callinfo($var,$spname);
		    $b = DB::table('bankaccount')->where('id',$baccid)->first();
			$openbalance=$b->openbalance;
			$previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));
			$debitbalance= DB::table('bankbook')
			->join('voucher', 'voucher.id', '=', 'bankbook.vid')
			->where('bankbook.dc',1)
			->where('voucher.vstatus',1)
			->where('bankbook.baccid',$baccid)
			->where('bankbook.created_at', '<=', $previousdate)
			->sum('bankbook.amount');
			//echo $debitbalance; die();
			$creditbalance= DB::table('bankbook')
			->join('voucher', 'voucher.id', '=', 'bankbook.vid')
			->where('bankbook.dc',0)
			->where('voucher.vstatus',1)
			->where('bankbook.baccid',$baccid)
			->where('bankbook.created_at', '<=', $previousdate)
			->sum('bankbook.amount');
			$openbalance=$b->openbalance+$debitbalance-$creditbalance;
			
			$bid=$b->bankid;
			$bank = DB::table('bankinfo')->where('id',$bid)->first();
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
					
					                <h2>
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<u>Account Statement</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Bank Name:'.$bank->name.'</td>
										<td></td>
										<td>Opening Date:'.$b->opendate.'</td>
										
									</tr>
									<tr>
									
										<td>Current Deposite:'.$b->openbalance.'</td>
										<td></td>
										<td>Expire Date:'.$b->exdate.'</td>
										
									</tr>
									<tr>
									
										<td>A/C Number:'.$b->name.'</td>
										<td></td>
										<td>Interest Rate:'.$b->rate.'</td>
										
									</tr>
									<tr>
									
										<td>From:'.$fromdate.' To '.$todate.'</td>
										<td></td>
										<td>Currency:BDT</td>
										
									</tr>
									
									
								
								</table> 
							</div>
						
						<div>

								 <table border="1" style="width:100%; padding:20px;">
									<tr>
										
										<td style="width:20%">Accounts Code:</td>
										
										<td style="width:80%">'.$b->code.'</td>
										
									</tr>
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$b->name.'('.$bank->name.')</td>
										
									</tr>
									
									
								</table> 

		  <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Date</th>
				<th>Particulars</th>
				<th >&nbsp;Supplier Name</th>
				<th >&nbsp;Customer Name</th>
				<th >&nbsp;Voucher No</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Debit</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Credit</th>
				<th>&nbsp;&nbsp;Balance</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Balance B/F</th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th>&nbsp;&nbsp;'.number_format($openbalance, 2, '.', '').'</th>
			  </tr>';
			  $html2='';
			  $balance=$openbalance;
			  $debit=$balance;
			  $credit=0;

			  foreach($value as $v){		
			    if($v->type==5){
					 $h1= '<tr>
				<td style="background-color:#ffffff;">&nbsp;'.$v->created_at.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->sname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->cname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/contravoucher/pdf/'.$v->vid.'/'.$v->type.'/'.$v->status.'">'.$v->vnno.'</a></td>';
				}else{
					  $h1= '<tr>
				<td style="background-color:#ffffff;">&nbsp;'.$v->created_at.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->sname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->cname.'</a></td>
				<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/voucher/pdf/'.$v->vid.'/'.$v->type.'">'.$v->vnno.'</a></td>';
				} 
			   
				if($v->type==1){
					$balance=$balance-$v->amount;
					if($balance==NULL)
					{echo "<h1>No Data Found</h1>";die();}
					else{
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$credit=$credit+$v->amount;
					}
					}
				else if($v->type==3){
					$balance=$balance+$v->amount;
					if($balance==NULL)
					{echo "<h1>No Data Found</h1>";die();}
					else{
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$debit=$debit+$v->amount;
					}}
				else if($v->type==5){
					if($v->status==1){
						
					$balance=$balance+$v->amount;
					if($balance==NULL)
					{echo "<h1>No Data Found</h1>";die();}
					else{
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$debit=$debit+$v->amount;
					}}
					else if($v->status==2){
					$balance=$balance-$v->amount;
					if($balance==NULL)
					{echo "<h1>No Data Found</h1>";die();}
					else{
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='
					<td style="background-color:#ffffff;">&nbsp;&nbsp;</td>
					<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$credit=$credit+$v->amount;
					}}
					else if($v->status==3){
					$balance=$balance+$v->amount;
					if($balance==NULL)
					{echo "<h1>No Data Found</h1>";die();}
					else{
					//$s = DB::table('suppliers')->where('id',$v->sid)->first();
					$h2='<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$v->amount.'</td>
					<td style="background-color:#ffffff;"></td>
					<td>&nbsp;&nbsp;'.number_format($balance, 2, '.', '').'</td>
					</tr>';
					$debit=$debit+$v->amount;
					}}
				}
				
				$h=$h1.$h2;
			    $html2=$html2.$h;
				}
				$total=$debit-$credit;
			 $h3='<tr>
				<td  colspan="5" align="right"  style="background-color:#ffffff;">Total</td>
				<td    style="background-color:#ffffff;">'.number_format($debit, 2, '.', '').'</td>
				<td    style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($credit, 2, '.', '').'</td>
				<td    style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($total, 2, '.', '').'</td>
				</tr>';
			$html2=$html2.$h3;	
			  $html3='</table></div>
						<div>
					
						</div>';
						
						 $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbp.pdf');
		}
		
	 }
	 
	 
	 
		
	}
	 
    	

