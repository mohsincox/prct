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
use DB;
class TrialbalanceController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('purchase');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function index()
	{
		return view('trailbalance');
	}
	 public function today(Request $request)
	{
	     
        /* $coatype= DB::table('coatype')->get();
         foreach($coatype as $ctype){
			echo '<b>'.$ctype->name.'</b><br>'; 
			$coa= DB::table('coa')->where('coatypeid',$ctype->id)->get();
			foreach($coa as $c){
				echo $c->name.'<br>'; 
			}
		 }
		 */
		/* PDF::AddPage('L');
		  $coatype= DB::table('coatype')->get();
		  $html='';
		  foreach($coatype as $ctype){
			  $html1='<b>'.$ctype->name.'</b><br>';
			  $coa= DB::table('coa')->where('coatypeid',$ctype->id)->get();
			  $html2='';
			  foreach($coa as $c){
				$html3='<span style="width:100px;">'.$c->name.'</span>total<br>';
                $html2=$html2.$html3;				
			  }
			  $html=$html.$html1.$html2;
		  }
		  PDF::writeHTML($html, true, false, true, false, '');
		  PDF::Output('trailbalance.pdf');
         die(); 
          */   		 
		 $profile=Companyprofile::get();
		
		foreach($profile as $com){
			$cid=$com->id;
			$cname=$com->name;
			$aaddress=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
			//print_r($trialbalance);
			//die();
			
			$date=Combo::callcombo('currentdate');
			//print_r($date);
			foreach($date as $d){
				$curdate=$d->curdate;
			}
			$fromdate=CommonController::date_format($curdate);
            $todate=CommonController::date_format($curdate);
            $var = array($fromdate,$todate);   
			$fdate=date_create($curdate);
			$tdate=date_create($curdate);
			PDF::AddPage();
		 
		$html1='<p></p>
				<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$aaddress.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>
				</div>
			
					
					<div>
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						<u>Trail Balance</u></h2>
						<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						
						
								'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
								 
							</div>

		  <div>
		  <table border="" style="background-color:#FFFFFF; width:100%; padding:20px;">	
			  <tr>
			    <th>&nbsp;&nbsp;Particulars</th> 
			   	
				<th style>Debit</th>
				<th style>Credit</th>
			   </tr>';
			  	
		$trialbalance = DB::table('coa') 
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->where('coa.id','<>',14)
							->where('coa.id','<>',15)
							->orderBy('coa.coatypeid', 'asc')
							->get();
		$html2='';
		$coatypeid_cur = -1;
		$totaldebit=0;
		$totalcredit=0;
		foreach($trialbalance as $t){
                //$instatus=DB::table('coa')->
			    if($t->inid==1){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					//$debit='Debit';
					$debit=$valu->cash;
					$credit=NULL;
					}
				}else if($t->inid==2){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					$debit=NULL;
					//$credit='Credit';
					$credit=$valu->cash;
					}
				}else if($t->inid==3){
					//$instatus=DB::table('coa')->
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}
				if($debit==NULL && $credit==NULL){
					$h='';
				}else{
					if($coatypeid_cur !=  $t->coatypeid){
						$debit_total_head = 0;
						$credit_total_head = 0;
						foreach($trialbalance as $tr){
							if($tr->coatypeid == $t->coatypeid){
							    if($tr->inid==1){
									$var = array($tr->id,$fromdate,$todate);
									$spname="todaypettycash";
									$value=Info::callinfo($var,$spname);
									foreach($value as $valu){ 
									//$debit='Debit';
									$debit_head=$valu->cash;
									$credit_head=NULL;
									}
									$debit_total_head = $debit_total_head + $debit_head;
								}else if($tr->inid==2){
									$var = array($tr->id,$fromdate,$todate);
									$spname="todaypettycash";
									$value=Info::callinfo($var,$spname);
									foreach($value as $valu){ 
									$debit_head = NULL;
									//$credit='Credit';
									$credit_head = $valu->cash;
									}
									$credit_total_head = $credit_total_head + $credit_head;
								}else if($tr->inid==3){
									$var = array($tr->id,$fromdate,$todate);
									$spname="todaypettycash";
									$value=Info::callinfo($var,$spname);
									$debit_head=NULL;
									$credit_head=NULL;
									$debitCredit='cebit Credit';
								}
							// if($debit==NULL && $credit==NULL){
							// 	$h='';
							// }else{

							// }
							}	
						}		

						 // if($t->inid==1){
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td><b></b></td><td></td></tr>'; 
						 // }else{
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td><td><b></b></td></tr>';  
						 // }
						if(($t->inid == 1) || ($t->inid == 2)){
							if($debit_total_head==0){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;</td><td style="background-color:#ffffff;"><b>'.number_format($credit_total_head, 2, '.', '').'</b></td></tr>'; 
							}else if($credit_total_head==0){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;"><b>'.number_format($debit_total_head, 2, '.', '').'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;</td></tr>'; 
							}
						}
						 // if($t->inid==1){
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td><b></b></td><td></td></tr>'; 
						 // }elseif ($t->inid==2){
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td><td><b></b></td></tr>';  
						 // }elseif ($t->inid==3){
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b></b></td><td></td><td><b></b></td></tr>';  
						 // }
						
					}
					$coatypeid_cur = $t->coatypeid;
			     	
			     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$debit.'</td><td>'.$credit.'</td></tr>';
				
				}
				;
			   $html2=$html2.$h;
               $totaldebit=$totaldebit+$debit;
		       $totalcredit=$totalcredit+$credit;			   
		}
		
		if($totaldebit>$totalcredit){
			$difftotal=$totaldebit-$totalcredit;
			$tdebit=$totaldebit;
			$tcredit=number_format($totalcredit+$difftotal, 2, '.', '');
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td></td><td>'.number_format($difftotal, 2, '.', '').'</td></tr>';
		}else if($totaldebit<$totalcredit){
			$difftotal=$totalcredit-$totaldebit;
			$tcredit=$totalcredit;
			$tdebit=number_format($totaldebit+$difftotal, 2, '.', '');
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td>'.number_format($difftotal, 2, '.', '').'</td><td></td></tr>';
		}else if($totaldebit==$totalcredit){
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td></td><td></td></tr>';
		}
        $h3='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Grand Total</td><td>'.number_format($tdebit, 2, '.', '').'</td><td>'.number_format($tcredit, 2, '.', '').'</td>
					</tr>';		
		$html4='</table>';
        
 		$html=$html1.$html2.$h4.$h3.$html4;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('trailbalance.pdf');
            
	}
	
	 public function fromtoday(Request $request)
	  
	{
		$profile=Companyprofile::get();
		
		foreach($profile as $com){
			$cid=$com->id;
			$cname=$com->name;
			$aaddress=$com->address;
			$tele=$com->telephone;
			$mobile=$com->mobile;
			$email=$com->email;
			$url=$com->url;
			$file=$com->file;
		}
		
			$fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($fromdate,$todate);
	      
           $var = array($fromdate,$todate);
			
			
           $fdate=date_create($fromdate);
		$tdate=date_create($todate);
		PDF::AddPage();
		 
		$html1='<p></p>
				<div>
						<table>
							<tr>
								<td style="width:20%">
									<img src="uploads/'.$file.'" alt="logo" height="150";>
								</td>
								<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$aaddress.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
								</td>
							</tr>

						</table>
				</div>
			
					
					<div>
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						<u>Trail Balance</u></h2>
						<h4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						
						
								'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
								 
							</div>

		  <div>
		  <table border="" style="background-color:#FFFFFF; width:100%; padding:20px;">	
			  <tr>
			    <th>&nbsp;&nbsp;Particulars</th> 
			   	
				<th style>Debit</th>
				<th style>Credit</th>
			   </tr>';
			  	
		$trialbalance = DB::table('coa') 
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->where('coa.id','<>',14)
							->where('coa.id','<>',15)
							->orderBy('coa.coatypeid', 'asc')
							->get();
	
		$html2='';
		
		$coatypeid_cur = -1;
		$totaldebit=0;
		$totalcredit=0;
		foreach($trialbalance as $t){

			    if($t->inid==1){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					//$debit='Debit';
					$debit=$valu->cash;
					$credit=NULL;
					}
				}else if($t->inid==2){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					$debit=NULL;
					//$credit='Credit';
					$credit=$valu->cash;
					}
				}else if($t->inid==3){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}
				if($debit==NULL && $credit==NULL){
					$h='';
				}else{
					if($coatypeid_cur !=  $t->coatypeid){
						$debit_total_head = 0;
						$credit_total_head = 0;
						foreach($trialbalance as $tr){
							if($tr->coatypeid == $t->coatypeid){

						    if($tr->inid==1){
								$var = array($tr->id,$fromdate,$todate);
								$spname="todaypettycash";
								$value=Info::callinfo($var,$spname);
								foreach($value as $valu){ 
								//$debit='Debit';
								$debit_head=$valu->cash;
								$credit_head=NULL;
								}
								$debit_total_head = $debit_total_head + $debit_head;
							}else if($tr->inid==2){
								$var = array($tr->id,$fromdate,$todate);
								$spname="todaypettycash";
								$value=Info::callinfo($var,$spname);
								foreach($value as $valu){ 
								$debit_head = NULL;
								//$credit='Credit';
								$credit_head = $valu->cash;
								}
								$credit_total_head = $credit_total_head + $credit_head;
							}else if($tr->inid==3){
								$var = array($tr->id,$fromdate,$todate);
								$spname="todaypettycash";
								$value=Info::callinfo($var,$spname);
								$debit_head=NULL;
								$credit_head=NULL;
								$debitCredit='cebit Credit';
							}
							// if($debit==NULL && $credit==NULL){
							// 	$h='';
							// }else{

							// }
							}	
						}		

						 // if($t->inid==1){
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td><b></b></td><td></td></tr>'; 
						 // }else{
							// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td><td><b></b></td></tr>';  
						 // }
						if(($t->inid == 1) || ($t->inid == 2)){
							if($debit_total_head==0){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;</td><td style="background-color:#ffffff;"><b>'.number_format($credit_total_head, 2, '.', '').'</b></td></tr>'; 
							}else if($credit_total_head==0){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;"><b>'.number_format($debit_total_head, 2, '.', '').'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;</td></tr>'; 
							}
							
						}
					}
					$coatypeid_cur = $t->coatypeid;
			     	
			     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$debit.'</td><td>'.$credit.'</td></tr>';
				
				}
				;
			   $html2=$html2.$h;
               $totaldebit=$totaldebit+$debit;
		       $totalcredit=$totalcredit+$credit;	 			   
		}
		
		if($totaldebit>$totalcredit){
			$difftotal=$totaldebit-$totalcredit;
			$tdebit=$totaldebit;
			$tcredit=number_format($totalcredit+$difftotal, 2, '.', '');
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td></td><td>'.number_format($difftotal, 2, '.', '').'</td></tr>';
		}else if($totaldebit<$totalcredit){
			$difftotal=$totalcredit-$totaldebit;
			$tcredit=$totalcredit;
			$tdebit=number_format($totaldebit+$difftotal, 2, '.', '');
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td>'.number_format($difftotal, 2, '.', '').'</td><td></td></tr>';
		}else if($totaldebit==$totalcredit){
			$h4='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Diff in opening balance</td><td></td><td></td></tr>';
		}
        $h3='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;Grand Total</td><td>'.number_format($tdebit, 2, '.', '').'</td><td>'.number_format($tcredit, 2, '.', '').'</td>
					</tr>';			
		$html4='</table>';
        
 		$html=$html1.$html2.$h4.$h3.$html4;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('trailbalance.pdf');	
            
	}

}
