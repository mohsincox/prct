<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Info;
use App\Models\Combo;
use App\Models\Companyprofile;
use App\Http\Controllers\Common\CommonController;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use DB;

class CashController extends Controller {
public function index()
	{
		
		return view('cashbook');
		

	}
	

     public function today(Request $request)
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
            $spname="rptcash";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
			
			foreach($value as $valu){ 
					$id=$valu->id;
					$amount=$valu->amount;
		
		    }
			//print_r($value);die();
			$var1=array($todate);
			//print_r($var1);die();
			$spname1="totalcashcollection";
			$value1=Info::callinfo($var1,$spname1);
			//print_r($value1);die();
			foreach($value1 as $valu){ 
					//$id=$valu->id;
					$opening_balance=$valu->cash;
		
		    }
			$coa = DB::table('coa')->where('id',1)->first();
			$coabalance=$coa->openbalance;
			//echo $coabalance;
            $openbalance=$opening_balance+$coabalance;
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

<h3>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<u>Cash A/C Book</h3></u></div>
<div><strong> From Date:'.$fromdate.' &nbsp;&nbsp;&nbsp;&nbsp; To Date:'.$todate.' </strong></div></div>
<table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Date</th>
				<th>Particulars</th>
				<th>Vch Type</th>
				<th>Vch No</th>
				<th>Debit</th>
				<th>Credit</th>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;"></td>
				<td colspan="3" style="background-color:#ffffff;">Opening Balance</td>
				<td style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$dtotal=$openbalance;
		$ctotal=0;
		foreach($value as $valu){ 
		        if(substr($valu->vnno,0,1)=='v'){
					//$vnno='Voucher';
					$voucher = DB::table('voucher')->where('id',$valu->id)->first();
					if($voucher->type==2){
					   $vnno='Cash Payment A/C';
					   $type='Payment';
					   $damount=NULL;
					   $camount=$valu->amount;
					   $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					}else if($voucher->type==4){
					   $vnno='Cash Collection A/C'; 
                       $type='Receipt'; 
					   $damount=$valu->amount;
                       $camount=NULL;
						$link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type; 					   
					}else if($voucher->type==5){
						if($voucher->status==1){
						   $vnno='Cash Contra  A/C'; 
						   $type='Receipt Contra';
                           $damount=$valu->amount;						   
                           $camount=NULL;	
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;  						   
                        }					   
						else if($voucher->status==2){
							 $vnno='Bank Contra A/C';
							 $type='Payment Contra';
                             $damount=NULL;
                             $camount=$valu->amount;
                             $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;							 
						}
					  
					}
					else if($voucher->type==6){
					   $vnno='bKash Collection A/C';
                       $coa = DB::table('coa')->where('id',4)->first();
                       if($coa->increasetypeid==1){
						  $type='Receipt';  
						  $damount=$valu->amount;
						  $camount=NULL;
						  $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					   }else if($coa->increasetypeid==2){
						   $type='Payment';  
						   $damount=NULL;
						   $camount=$valu->amount;
						   $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					   } 					   
					}else if($voucher->type==7){
					   $vnno='SAP Collection A/C';
                       $coa = DB::table('coa')->where('id',5)->first();					   
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type; 						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
						   $damount=NULL;
                           $camount=$valu->amount;	
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   }    					   
					}else if($voucher->type==8){
					   $vnno='KCS Collection A/C';
					   $coa = DB::table('coa')->where('id',6)->first();
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
						   $camount=$valu->amount;
                           $damount=NULL;
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   } 					   
					}else if($voucher->type==9){
					   $vnno='MBank Collection A/C';
					   $coa = DB::table('coa')->where('id',7)->first(); 
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
                           $camount=$valu->amount;
                           $damount=NULL; 
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   }  					   
					}
				}else{
					$pettycash = DB::table('pettycash')->where('id',$valu->id)->first();				
					$coa = DB::table('coa')->where('id',$pettycash->particular)->first();
					$vnno=$coa->name;
					if($coa->increasetypeid==1){
						  $type='Receipt'; 
                          $damount=$valu->amount;
                          $camount=NULL; 						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment';
						   $damount=NULL;
                           $camount=$valu->amount;						   
					   }  
					 $link='http://192.168.1.8/IMS/ledgerentry/pdf/'.$valu->id;  
				}
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->created_at.'</td>
			    <td style="background-color:#ffffff;">'.$vnno.'</td>
				<td style="background-color:#ffffff;">'.$type.'</td>
				<td style="background-color:#ffffff;"><a href="'.$link.'">'.$valu->vnno.'</a></td>
				<td style="background-color:#ffffff;">'.$damount.'</td>
				<td style="background-color:#ffffff;">'.$camount.'</td>
				</tr>';
				$html2=$html2.$html;
				$i++;
				$dtotal=$dtotal+$damount;
		        $ctotal=$ctotal+$camount;
		} 			
		$html3='<tr>
				<td></td>
				<td colspan="3" >Total</td>
				<td>'.number_format($dtotal, 2, '.', '').'</td>
				<td>'.number_format($ctotal, 2, '.', '').'</td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;"></td>
				<td colspan="4" style="background-color:#ffffff;">Closing Balance</td>
				<td style="background-color:#ffffff;">'.number_format($dtotal-$ctotal, 2, '.', '').'</td>
			  </tr>
			  <tr>
				<td></td>
				<td colspan="3" >Sub Total</td>
				<td>'.number_format($dtotal, 2, '.', '').'</td>
				<td>'.number_format($ctotal+$dtotal-$ctotal, 2, '.', '').'</td>
			  </tr>
		</table></div>'; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		    PDF::Output('cash.pdf');


		
            
	}
	
	 public function fromtoday(Request $request)
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
	       
			$fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($fromdate,$todate);
	      
           // $var = array($fromdate,$todate);
			
		
            $spname="rptcash";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
			
			foreach($value as $valu){ 
					$id=$valu->id;
					$amount=$valu->amount;
		
		    }
			//print_r($value);die();
			$var1=array($fromdate);
			
			//print_r($var1);die();
			$spname1="totalcashcollection";
			$value1=Info::callinfo($var1,$spname1);
			//print_r($value1);die();
			foreach($value1 as $valu){ 
					//$id=$valu->id;
					$opening_balance=$valu->cash;
		
		    }
				
           $fdate=date_create($fromdate);
		   $tdate=date_create($todate);
            $coa = DB::table('coa')->where('id',1)->first();
			$coabalance=$coa->openbalance;
			//echo $coabalance;
            $openbalance=$opening_balance+$coabalance;
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

<h3>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<u>Cash A/C Book</h3></u></div>
<div><strong>From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").' </strong></div></div>
<table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;Date</th>
				<th>Particulars</th>
				<th>Vch Type</th>
				<th>Vch No</th>
				<th>Debit</th>
				<th>Credit</th>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;"></td>
				<td colspan="3" style="background-color:#ffffff;">Opening Balance</td>
				<td style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$dtotal=$openbalance;
		$ctotal=0;
		foreach($value as $valu){ 
		        if(substr($valu->vnno,0,1)=='v'){
					//$vnno='Voucher';
					$voucher = DB::table('voucher')->where('id',$valu->id)->first();
					if($voucher->type==2){
					   $vnno='Cash Payment A/C';
					   $type='Payment';
					   $damount=NULL;
					   $camount=$valu->amount;
					   $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					}else if($voucher->type==4){
					   $vnno='Cash Collection A/C'; 
                       $type='Receipt'; 
					   $damount=$valu->amount;
                       $camount=NULL;
						$link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type; 					   
					}else if($voucher->type==5){
						if($voucher->status==1){
						   $vnno='Cash Contra  A/C'; 
						   $type='Receipt Contra';
                           $damount=$valu->amount;						   
                           $camount=NULL;	
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;  						   
                        }					   
						else if($voucher->status==2){
							 $vnno='Bank Contra A/C';
							 $type='Payment Contra';
                             $damount=NULL;
                             $camount=$valu->amount;
                             $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;							 
						}
					  
					}
					else if($voucher->type==6){
					   $vnno='bKash Collection A/C';
                       $coa = DB::table('coa')->where('id',4)->first();
                       if($coa->increasetypeid==1){
						  $type='Receipt';  
						  $damount=$valu->amount;
						  $camount=NULL;
						  $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					   }else if($coa->increasetypeid==2){
						   $type='Payment';  
						   $damount=NULL;
						   $camount=$valu->amount;
						   $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;
					   } 					   
					}else if($voucher->type==7){
					   $vnno='SAP Collection A/C';
                       $coa = DB::table('coa')->where('id',5)->first();					   
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type; 						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
						   $damount=NULL;
                           $camount=$valu->amount;	
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   }    					   
					}else if($voucher->type==8){
					   $vnno='KCS Collection A/C';
					   $coa = DB::table('coa')->where('id',6)->first();
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
						   $camount=$valu->amount;
                           $damount=NULL;
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   } 					   
					}else if($voucher->type==9){
					   $vnno='MBank Collection A/C';
					   $coa = DB::table('coa')->where('id',7)->first(); 
                       if($coa->increasetypeid==1){
						  $type='Receipt';
                          $damount=$valu->amount;
                          $camount=NULL;
                          $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment'; 
                           $camount=$valu->amount;
                           $damount=NULL; 
                           $link='http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type;						   
					   }  					   
					}
				}else{
					$pettycash = DB::table('pettycash')->where('id',$valu->id)->first();				
					$coa = DB::table('coa')->where('id',$pettycash->particular)->first();
					$vnno=$coa->name;
					if($coa->increasetypeid==1){
						  $type='Receipt'; 
                          $damount=$valu->amount;
                          $camount=NULL; 						  
					   }else if($coa->increasetypeid==2){
						   $type='Payment';
						   $damount=NULL;
                           $camount=$valu->amount;						   
					   }  
					 $link='http://192.168.1.8/IMS/ledgerentry/pdf/'.$valu->id;  
				}
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->created_at.'</td>
			    <td style="background-color:#ffffff;">'.$vnno.'</td>
				<td style="background-color:#ffffff;">'.$type.'</td>
				<td style="background-color:#ffffff;"><a href="'.$link.'">'.$valu->vnno.'</a></td>
				<td style="background-color:#ffffff;">'.$damount.'</td>
				<td style="background-color:#ffffff;">'.$camount.'</td>
				</tr>';
				$html2=$html2.$html;
				$i++;
				$dtotal=$dtotal+$damount;
		        $ctotal=$ctotal+$camount;
		} 			
		$html3='<tr>
				<td></td>
				<td colspan="3" >Total</td>
				<td>'.number_format($dtotal, 2, '.', '').'</td>
				<td>'.number_format($ctotal, 2, '.', '').'</td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;"></td>
				<td colspan="4" style="background-color:#ffffff;">Closing Balance</td>
				<td style="background-color:#ffffff;">'.number_format($dtotal-$ctotal, 2, '.', '').'</td>
			  </tr>
			  <tr>
				<td></td>
				<td colspan="3" >Sub Total</td>
				<td>'.number_format($dtotal, 2, '.', '').'</td>
				<td>'.number_format($ctotal+$dtotal-$ctotal, 2, '.', '').'</td>
			  </tr>
		</table></div>'; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		    PDF::Output('cash.pdf');

			
	}
	
			

}
