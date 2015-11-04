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


class PlaccountController  extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('customers');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function index()
	{
		return view('placcount');
	}
	 public function today(Request $request)
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
					             <h2>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<u>Profit/Loss Account</u></h2>
						<h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
								 
							</div>

			<table border="0" style="background-color:#FFFFFF; padding:20px;">	
			  <tr><td>				
			  	<table border="0" style="background-color:#FFFFFF; padding:20px;">	
				  <tr>
				    <th>Particulars</th> 
				   	
					<th style>Debit</th>
				   </tr>';
			  	
		$trialbalance = DB::table('coa')
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->orderBy('coa.coatypeid', 'asc')
							->whereIn('coatype.id', [5,6,11,17,18])
							->get();
	
		$html2='';
		
		$coatypeid_cur = -1;
		$total_debit = 0;
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
					$total_debit = $total_debit + $debit;
				}
				
				else if($t->inid==3){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}

				if(($t->inid==1) || ($t->inid==3)){
					if($debit==NULL && $credit==NULL){
						$h='';
					}else{
						if($coatypeid_cur !=  $t->coatypeid){
							$debit_total_head = 0;
							// $credit_total_head = 0;
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
									}
									// else if($tr->inid==2){
									// 	$var = array($tr->id,$fromdate,$todate);
									// 	$spname="todaypettycash";
									// 	$value=Info::callinfo($var,$spname);
									// 	foreach($value as $valu){ 
									// 	$debit_head = NULL;
									// 	//$credit='Credit';
									// 	$credit_head = $valu->cash;
									// 	}
									// 	$credit_total_head = $credit_total_head + $credit_head;
									// }
									else if($tr->inid==3){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										$debit_head=NULL;
										$credit_head=NULL;
										$debitCredit='cebit Credit';
									}
								}	
							}


							// $var = array($t->coatypeid,$fromdate,$todate);
							// $spname="totalpettycash";
							// $value=Info::callinfo($var,$spname);
							
							// foreach($value as $vs){
							// 	$amount=$vs->cash;
							// }
							 // if($t->inid==1){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($debit_total_head, 2, '.', '').'</b></td></tr>'; 
							 // }else{
								// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td><td><b>'.$amount.'</b></td></tr>';  
							 // }
							
						}
						$coatypeid_cur = $t->coatypeid;
				     	
				     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$debit.'</td></tr>';
					}
					$html2=$html2.$h;
				}	
			   		
		}
			
		$html4='</table></td>';
        
 		$html=$html1.$html2.$html4;
		
		$html1 = $html.'<td>				
			  	<table border="0" style="background-color:#FFFFFF; padding:20px;">	
				  <tr>
				    <th>Particulars</th> 
					<th style>Credit</th>
				   </tr>';
			  	
		$trialbalance = DB::table('coa') 
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->orderBy('coa.coatypeid', 'asc')
							->whereIn('coatype.id', [5,6,11,17,18])
							->get();
	
		$html2='';
		
		$coatypeid_cur = -1;
		$total_credit = 0;
		foreach($trialbalance as $t){

				if($t->inid==2){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					$debit=NULL;
					//$credit='Credit';
					$credit=$valu->cash;
					}
					$total_credit = $total_credit + $credit;
				}else if($t->inid==3){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}
				if(($t->inid==2) || ($t->inid==3)){
					if($debit==NULL && $credit==NULL){
						$h='';
					}else{
						if($coatypeid_cur !=  $t->coatypeid){
							// $debit_total_head = 0;
							$credit_total_head = 0;
							foreach($trialbalance as $tr){
								if($tr->coatypeid == $t->coatypeid){
									if($tr->inid==2){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										foreach($value as $valu){ 
										$debit_head = NULL;
										//$credit='Credit';
										$credit_head = $valu->cash;
										}
										$credit_total_head = $credit_total_head + $credit_head;
									}
									else if($tr->inid==3){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										$debit_head=NULL;
										$credit_head=NULL;
										$debitCredit='cebit Credit';
									}
								}	
							}

							// $var = array($t->coatypeid,$fromdate,$todate);
							// $spname="totalpettycash";
							// $value=Info::callinfo($var,$spname);
							
							// foreach($value as $vs){
							// 	$amount=$vs->cash;
							// }
							//  if($t->inid==1){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($credit_total_head, 2, '.', '').'</b></td></tr>'; 
							 // }else{
								// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td></tr>';  
							 // }
							
						}
						$coatypeid_cur = $t->coatypeid;
				     	
				     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$credit.'</td></tr>';
					}
			   		$html2=$html2.$h;	
			   	}	
		}
			
		$html4='</table></td></tr>';
        
 		$html5 = '<tr>
 					<td>
 					<table>';
	 						if($total_debit < $total_credit){
		 						$html5 = $html5.'
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Net Profit</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit - $total_debit, 2, '.', '').'</b></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit, 2, '.', '').'</b></td>
 						</tr>';
 					} else{
 						$html5 = $html5.'
 						<tr>
 							<td></td>
 							<td></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit, 2, '.', '').'</b></td>
 						</tr>';
 					}
 					$html5 = $html5.'</table>
 					</td>
 					<td>
 					<table>';
	 						if($total_debit > $total_credit){
		 						$html5 = $html5.'
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Net Profit</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit - $total_credit, 2, '.', '').'</b></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit, 2, '.', '').'</b></td>
 						</tr>';
 					} else {
 						$html5 = $html5.'
 						<tr>
 							<td></td>
 							<td></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit, 2, '.', '').'</b></td>
 						</tr>';
 					}

 					$html5 = $html5.'</table>
 					</td>
 				  </tr>
 				  
 				</table>';

 		$html=$html1.$html2.$html4.$html5;


        			
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
	      
           // $var = array($fromdate,$todate);
			
			
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
					             <h2>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<u>Profit/Loss Account</u></h2>
						<h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
								 
							</div>

		  <table border="0" style="background-color:#FFFFFF; padding:20px;">	
			  <tr><td>				
			  	<table border="0" style="background-color:#FFFFFF; padding:20px;">	
				  <tr>
				    <th>Particulars</th> 
				   	
					<th style>Debit</th>
				   </tr>';
			  	
		$trialbalance = DB::table('coa')
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->orderBy('coa.coatypeid', 'asc')
							->whereIn('coatype.id', [5,6,11,17,18])
							->get();
	
		$html2='';
		
		$coatypeid_cur = -1;
		$total_debit = 0;
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
					$total_debit = $total_debit + $debit;
				}
				// else if($t->inid==2){
				// 	$var = array($t->id,$fromdate,$todate);
				// 	$spname="todaypettycash";
				// 	$value=Info::callinfo($var,$spname);
				// 	foreach($value as $valu){ 
				// 	$debit=NULL;
				// 	//$credit='Credit';
				// 	$credit=$valu->cash;
				// 	}
				// }
				else if($t->inid==3){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}

				if(($t->inid==1) || ($t->inid==3)){
					if($debit==NULL && $credit==NULL){
						$h='';
					}else{
						if($coatypeid_cur !=  $t->coatypeid){
							$debit_total_head = 0;
							// $credit_total_head = 0;
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
									}
									// else if($tr->inid==2){
									// 	$var = array($tr->id,$fromdate,$todate);
									// 	$spname="todaypettycash";
									// 	$value=Info::callinfo($var,$spname);
									// 	foreach($value as $valu){ 
									// 	$debit_head = NULL;
									// 	//$credit='Credit';
									// 	$credit_head = $valu->cash;
									// 	}
									// 	$credit_total_head = $credit_total_head + $credit_head;
									// }
									else if($tr->inid==3){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										$debit_head=NULL;
										$credit_head=NULL;
										$debitCredit='cebit Credit';
									}
								}	
							}


							// $var = array($t->coatypeid,$fromdate,$todate);
							// $spname="totalpettycash";
							// $value=Info::callinfo($var,$spname);
							
							// foreach($value as $vs){
							// 	$amount=$vs->cash;
							// }
							 // if($t->inid==1){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($debit_total_head, 2, '.', '').'</b></td></tr>'; 
							 // }else{
								// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td><td><b>'.$amount.'</b></td></tr>';  
							 // }
							
						}
						$coatypeid_cur = $t->coatypeid;
				     	
				     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$debit.'</td></tr>';
					}
					$html2=$html2.$h;
				}	
			   		
		}
				
		$html4='</table></td>';
        
 		$html=$html1.$html2.$html4;
		
		$html1 = $html.'<td>				
			  	<table border="0" style="background-color:#FFFFFF; padding:20px;">	
				  <tr>
				    <th>Particulars</th> 
					<th style>Credit</th>
				   </tr>';
			  	
		$trialbalance = DB::table('coa') 
			                ->join('coatype', 'coatype.id', '=', 'coa.coatypeid')
							->select('coa.id as id','coa.name as coaname','coa.coatypeid' ,'coatype.name as coatypename','coa.increasetypeid as inid')
							->orderBy('coa.coatypeid', 'asc')
							->whereIn('coatype.id', [5,6,11,17,18])
							->get();
	
		$html2='';
		
		$coatypeid_cur = -1;
		$total_credit = 0;
		foreach($trialbalance as $t){

			 //    if($t->inid==1){
				// 	$var = array($t->id,$fromdate,$todate);
				// 	$spname="todaypettycash";
				// 	$value=Info::callinfo($var,$spname);
				// 	foreach($value as $valu){ 
				// 	//$debit='Debit';
				// 	$debit=$valu->cash;
				// 	$credit=NULL;
				// 	}
				// }
				if($t->inid==2){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					foreach($value as $valu){ 
					$debit=NULL;
					//$credit='Credit';
					$credit=$valu->cash;
					}
					$total_credit = $total_credit + $credit;
				}else if($t->inid==3){
					$var = array($t->id,$fromdate,$todate);
					$spname="todaypettycash";
					$value=Info::callinfo($var,$spname);
					$debit=NULL;
					$credit=NULL;
					$debitCredit='cebit Credit';
				}
				if(($t->inid==2) || ($t->inid==3)){
					if($debit==NULL && $credit==NULL){
						$h='';
					}else{
						if($coatypeid_cur !=  $t->coatypeid){
							// $debit_total_head = 0;
							$credit_total_head = 0;
							foreach($trialbalance as $tr){
								if($tr->coatypeid == $t->coatypeid){
								 //    if($tr->inid==1){
									// 	$var = array($tr->id,$fromdate,$todate);
									// 	$spname="todaypettycash";
									// 	$value=Info::callinfo($var,$spname);
									// 	foreach($value as $valu){ 
									// 	//$debit='Debit';
									// 	$debit_head=$valu->cash;
									// 	$credit_head=NULL;
									// 	}
									// 	$debit_total_head = $debit_total_head + $debit_head;
									// }
									if($tr->inid==2){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										foreach($value as $valu){ 
										$debit_head = NULL;
										//$credit='Credit';
										$credit_head = $valu->cash;
										}
										$credit_total_head = $credit_total_head + $credit_head;
									}
									else if($tr->inid==3){
										$var = array($tr->id,$fromdate,$todate);
										$spname="todaypettycash";
										$value=Info::callinfo($var,$spname);
										$debit_head=NULL;
										$credit_head=NULL;
										$debitCredit='cebit Credit';
									}
								}	
							}

							// $var = array($t->coatypeid,$fromdate,$todate);
							// $spname="totalpettycash";
							// $value=Info::callinfo($var,$spname);
							
							// foreach($value as $vs){
							// 	$amount=$vs->cash;
							// }
							//  if($t->inid==1){
								$html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($credit_total_head, 2, '.', '').'</b></td></tr>'; 
							 // }else{
								// $html2 = $html2.'<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.$t->coatypename.'</b></td><td></td></tr>';  
							 // }
							
						}
						$coatypeid_cur = $t->coatypeid;
				     	
				     	$h='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$t->coaname.'</td><td>'.$credit.'</td></tr>';
					}
			   		$html2=$html2.$h;	
			   	}	
		}
		$html4='</table></td></tr>';
        
 		$html5 = '<tr>
 					<td>
 					<table>';
	 						if($total_debit < $total_credit){
		 						$html5 = $html5.'
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Net Profit</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit - $total_debit, 2, '.', '').'</b></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit, 2, '.', '').'</b></td>
 						</tr>';
 					} else{
 						$html5 = $html5.'
 						<tr>
 							<td></td>
 							<td></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit, 2, '.', '').'</b></td>
 						</tr>';
 					}
 					$html5 = $html5.'</table>
 					</td>
 					<td>
 					<table>';
	 						if($total_debit > $total_credit){
		 						$html5 = $html5.'
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Net Profit</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit - $total_credit, 2, '.', '').'</b></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_debit, 2, '.', '').'</b></td>
 						</tr>';
 					} else {
 						$html5 = $html5.'
 						<tr>
 							<td></td>
 							<td></td>
 						</tr>
 						<tr>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>Total</b></td>
 							<td style="background-color:#ffffff;">&nbsp;&nbsp;<b>'.number_format($total_credit, 2, '.', '').'</b></td>
 						</tr>';
 					}

 					$html5 = $html5.'</table>
 					</td>
 				  </tr>
 				</table>';

 		$html=$html1.$html2.$html4.$html5;
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('placcount.pdf');
	}
}
