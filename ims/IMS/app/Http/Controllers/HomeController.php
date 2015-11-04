<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Voucher;
use App\Models\Companyprofile;
use App\Models\Physicalsale;
use App\Models\Combo;
use App\Models\Info;
use App\Models\Sale;
use App\Models\Bankaccount;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Factoryitem;
use PDF;
use DB;
class HomeController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	
	public function index()
	{
		$now = new \DateTime('now');
   		$month = $now->format('m');
		$c=Purchase::get();
		$c1=Physicalsale::get();
		$var = array($month);
		$spname="salesreport";
		$c2=Info::callinfo($var,$spname);
		$var1 = array($month-1);
		$spname1="salesreportp";
		$c3=Info::callinfo($var1,$spname1);
		$spname2="todaysales";
		$c4=Combo::callcombo($spname2);
		$spname3="todaycash";
		$c5=Combo::callcombo($spname3);
		$spname4="todaybankcollection";
		$c6=Combo::callcombo($spname4);
		$spname5="todaycashcollection";
		$c7=Combo::callcombo($spname5);
		$spname6="todaycontracollection";
		$c8=Combo::callcombo($spname6);
		$spbkash="todaybkashcollection";
		$bkash=Combo::callcombo($spbkash);
		$spsap="todaysapcollection";
		$sap=Combo::callcombo($spsap);
		$spkcs="todaykcscollection";
		$kcs=Combo::callcombo($spkcs);
		$spmbank="todaymbankcollection";
		$mbank=Combo::callcombo($spmbank);
       // print_r($c4);
		$sales_info = Sale::orderBy('created_at', 'desc')->take(5)->get();
		
		$purchase_info = Purchase::orderBy('created_at', 'desc')->take(5)->get();
		
		$bankaccount_info = Bankaccount::orderBy('created_at', 'desc')->take(5)->get();

		return view('home', compact('c', 'c1', 'c2', 'c3', 'sales_info', 'purchase_info', 'bankaccount_info','c4','c5','c6','c7','c8','bkash','sap','kcs','mbank'));
	}
	
	public function dailycollection()
	{
	    $spname="todaycollection";
		$c=Combo::callcombo($spname);
		$spname3="todaycash";
		$c5=Combo::callcombo($spname3);
		$spname4="todaybankcollection";
		$c6=Combo::callcombo($spname4);
		$spname5="todaycashcollection";
		$c7=Combo::callcombo($spname5);
		$spbkash="todaybkashcollection";
		$bkash=Combo::callcombo($spbkash);
		$spsap="todaysapcollection";
		$sap=Combo::callcombo($spsap);
		$spkcs="todaykcscollection";
		$kcs=Combo::callcombo($spkcs);
		$spmbank="todaymbankcollection";
		$mbank=Combo::callcombo($spmbank);
		//print_r($c);
		return view('dailycollection',compact('c','c5','c6','c7','bkash','sap','kcs','mbank'));
    }	
	
    public function search(Request $request)
	{
		$inno=$request->input('inno');
	    $value=Factoryitem::innosales($inno);
		//print_r($value);
		$profile=Companyprofile::get();
		return view('search',compact('profile','value','inno'));
    }

    public function return_item(Request $request)
	{
		if($request->ajax()){
			$factory_item_id = $request->input('factory_item_id');
			$factory_item = Factoryitem::find($factory_item_id);
			if($factory_item !== null){
				$next_factory_item = Factoryitem::where('itemsid', $factory_item->itemsid)
													->where('status', 1)
													->where('salesid', NULL)
													->where('sale_product', 0)
													->first();
				if($next_factory_item !== null){
					$next_factory_item->salesid = $factory_item->salesid;
					$next_factory_item->sale_product = 1;
					$next_factory_item->save();

					$factory_item->status = 0;
					$factory_item->save();

					return response()->json(1);// return successfully
				} else{
					return response()->json(2);// Not available this item.
				}								

			} else{
				return response()->json(0);//Not found thid product.
			}
		}	
    }

    public function return_item_ina_to_act(Request $request)
	{
		if($request->ajax()){
			$factory_item_id = $request->input('factory_item_id');
			$factory_item = Factoryitem::find($factory_item_id);
			if($factory_item !== null){
				$last_update_factory_item = Factoryitem::where('itemsid', $factory_item->itemsid)
													->where('salesid', $factory_item->salesid)
													->where('status', 1)
													->where('sale_product', 1)
													->orderBy('id', 'DESC')
													->first();
				if($last_update_factory_item !== null){
					$last_update_factory_item->salesid = NULL;
					$last_update_factory_item->sale_product = 0;
					$last_update_factory_item->save();

					$factory_item->status = 1;
					$factory_item->save();

					return response()->json(1);// return successfully
				} else{
					return response()->json(2);// Not found last inactive
				}								

			} else{
				return response()->json(0);//Not found thid product.
			}
		}	
    }

	
	
	
	/*public function printcollection()
	{
	    return 'printcoleection';
    }	*/
		public function printcollection()
	{
		
		$profile=Companyprofile::get();
		
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
		 $spname="todaycollection";
		$c=Combo::callcombo($spname);
		$spname3="todaycash";
		$c5=Combo::callcombo($spname3);
		$spname4="todaybankcollection";
		$c6=Combo::callcombo($spname4);
		$spname5="todaycashcollection";
		$c7=Combo::callcombo($spname5);
		$spname6="todaybkashcollection";
		$c8=Combo::callcombo($spname6);
		$spsap="todaysapcollection";
		$csap=Combo::callcombo($spsap);
		$spkcs="todaykcscollection";
		$ckcs=Combo::callcombo($spkcs);
		$spmbank="todaymbankcollection";
		$cmbank=Combo::callcombo($spmbank);
	foreach($c6 as $cc){ $totalbankcash=$cc->cash;}
	foreach($c7 as $ccc){ $totalhandcash=$ccc->cash;}
	foreach($c8 as $ccc){ $totalbkash=$ccc->cash;}	
	foreach($csap as $ccc){ $totalsap=$ccc->cash;}	
	foreach($ckcs as $ccc){ $totalkcs=$ccc->cash;}	
	foreach($cmbank as $ccc){ $totalmbank=$ccc->cash;}	
		$sum=0;
		foreach($c as $p){ 
				$a=$p->amount;	
				$sum=$sum+$a;
		} 
		
		PDF::AddPage('L');
		 
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
						
						<h2>
					    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<u>Statement of Daily Collection</u></h2><h4><br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Date: '. date("d/m/Y") .'  To  '. date("d/m/Y") .'</h4>
						</div>
						
				<table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
					<tr>
						<th style="width:3%;">SL</th>
						<th style="width:10%;">Name</th>
						<th style="width:12%;">Present Address</th>
						<th>Amount</th>
						<th style="width:7%;">Voucher No.</th>
						<th>Bank Amount</th>
						<th>Cash</th>
						<th>BKash</th>
						<th>SAP</th>
						<th>KCS</th>
						<th>MBank</th>
						<th>Total</th>
					</tr>';
				
		$html2= '';
		 $i=1;
		 $sum=0;
		
		foreach($c as $p){ 
		        if($p->type==3){
					$bankamount=$p->amount;
					$cashamount='';
					$bkash='';
					$sap='';
					$kcs='';
					$mbank='';
				}else if($p->type==4){
					$bankamount='';
					$cashamount=$p->amount;
					$bkash='';
					$sap='';
					$kcs='';
					$mbank='';
				}else if($p->type==6){
					$bankamount='';
					$cashamount='';
					$bkash=$p->amount;
					$sap='';
					$kcs='';
					$mbank='';
				}else if($p->type==7){
					$bankamount='';
					$cashamount='';
					$bkash='';
					$sap=$p->amount;
					$kcs='';
					$mbank='';
				}else if($p->type==8){
					$bankamount='';
					$cashamount='';
					$bkash='';
					$sap='';
					$kcs=$p->amount;
					$mbank='';
				}else if($p->type==9){
					$bankamount='';
					$cashamount='';
					$bkash='';
					$sap='';
					$kcs='';
					$mbank=$p->amount;
				}
				$html='<tr><td style="background-color:#ffffff;">'.$i.'</td>
				<td style="background-color:#ffffff;">'.$p->name.'</td>
				<td style="background-color:#ffffff;">'.$p->preaddress.'</td>
				<td style="background-color:#ffffff;">'.$p->amount.'</td>
				<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$p->id.'/'.$p->type.'"   target="_blank">'.$p->vnno.'</a></td>					
				<td style="background-color:#ffffff;">'.$bankamount.'</td>
				<td style="background-color:#ffffff;">'.$cashamount.'</td>
				<td style="background-color:#ffffff;">'.$bkash.'</td>
				<td style="background-color:#ffffff;">'.$sap.'</td>
				<td style="background-color:#ffffff;">'.$kcs.'</td>
				<td style="background-color:#ffffff;">'.$mbank.'</td>
				<td style="background-color:#ffffff;">'.$p->amount.'</td>
				</tr>';
				$html2=$html2.$html;
				$i++;
				$sum=$sum+$p->amount;
		} 
		$html3='<tr><td colspan="5" align="right" style="background-color:#ffffff;">';
		$html4='';
		$html5='Total:</td>
				<td style="background-color:#ffffff;">'.$totalbankcash.'</td>
				<td style="background-color:#ffffff;">'.$totalhandcash.'</td>
				<td style="background-color:#ffffff;">'.$totalbkash.'</td>
				<td style="background-color:#ffffff;">'.$totalsap.'</td>
				<td style="background-color:#ffffff;">'.$totalkcs.'</td>
				<td style="background-color:#ffffff;">'.$totalmbank.'</td>
				<td style="background-color:#ffffff;">'.number_format($sum, 2, '.', '').'</td></tr>';
		$html6='</table><h4>Amount in word:'.CommonController::convertNumberToWord(number_format($sum, 2, '.', '')).' Taka Only</h4></div>
						<div>
					
						</div>
						<div class="col-md-12"  style=" background-color: #ffffff;color:#000000;">
						<h3>&nbsp;&nbsp;&nbsp; Prepared By
		                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;						
						  Checked By
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


						Approved By
						  </h3>
						
						
							
						</div>'
						; 
		
        $html=$html1.$html2.$html3.$html4.$html5.$html6;
		
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vp.pdf');
		
		} 
		
		
	

}
