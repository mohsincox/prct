<?php namespace App\Http\Controllers;

use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Physicalsale;
use App\Models\Salesdetails;
use App\Models\Companyprofile;
use App\Models\Info;
use App\Models\Customer;
use App\Models\Factoryitem;
use App\Models\Customersledger;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Item;
use Session;
use PDF;
use DB;

class PhysicalsaleController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('physicalsales');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
			$c = Physicalsale::orderBy('id','desc')->take(10)->get(); //print_r($c);die();
			return view('physicalsale')->with('physicalsale',$c);
	}
	public function addnew()
	{
		// if (Session::has('physcl_sale_invoice_info'))
		// 	{
		// 	    Session::forget('physcl_sale_invoice_info');
		// 	    Session::forget('physcl_sale_sub_total');
		// 	    Session::forget('physcl_sale_discount');
		// 	    Session::forget('physcl_sale_gross_total');
		//      	Session::forget('physcl_sale_serial_no');
		// 	    Session::forget('physcl_sale_sales_date');
		// 	    Session::forget('physcl_sale_customer');
		// 	}
		return view('createphysicalsale');
	}

	public function get_item_by_category(Request $request) {
		if($request->ajax()){
			$category_id = $request->input('category_id');
			$iteminfo = Item::where('itemssubgroupid', $category_id)->get();
			return response()->json($iteminfo);
		}
	}

    public function get_item_info(Request $request) {
		if($request->ajax()){
			$item_id = $request->input('item_id');
			// $iteminfo = Item::find($item_id);
			$iteminfo = Item::get_measurement_unit($item_id);
			$product_slno = Item::get_product_slno($item_id);
			return response()->json(array('item_info' => $iteminfo, 'product_slno' => $product_slno));
		}
	}

	public function get_factory_item(Request $request) {
		if($request->ajax()){
			$item_id = $request->input('item_id');
			$item = Item::find($item_id);
			// $factory_items = Factoryitem::where('itemsid', $item_id)
			// 							->where('status', 1)
			// 							->where('sale_product', 0)
			// 							->orderBy('id', 'asc')
			// 							->get();
			return response()->json($item->quantity);
		}
	}

	public function session_invoice(Request $request)
	{
		if($request->ajax()){
			if (Session::has('physcl_sale_invoice_info'))
			{
			    Session::forget('physcl_sale_invoice_info');
			   	Session::forget('physcl_sale_sub_total');
			    Session::forget('physcl_sale_discount');
			    Session::forget('physcl_sale_gross_total');
			    Session::forget('physcl_sale_serial_no');
			    Session::forget('physcl_sale_sales_date');
			    Session::forget('physcl_sale_customer');
			}


			$invoice_info = json_decode($request->input('invoice_info'));
			$sub_total = $request->input('sub_total');
			$discount = $request->input('discount');
			$gross_total = $request->input('gross_total');
			$serial_no = $request->input('serial_no');
			$sales_date = $request->input('sales_date');
			$customer = $request->input('customer');

			Session::put('physcl_sale_invoice_info', $invoice_info);
			Session::put('physcl_sale_sub_total', $sub_total);
			Session::put('physcl_sale_discount', $discount);
			Session::put('physcl_sale_gross_total', $gross_total);
			Session::put('physcl_sale_serial_no', $serial_no);
			Session::put('physcl_sale_sales_date', $sales_date);
			Session::put('physcl_sale_customer', $customer);
			return response()->json();
		}
	}

	public function invoice_remove(Request $request)
	{
		if($request->ajax()){
			if (Session::has('physcl_sale_invoice_info'))
			{
			    Session::forget('physcl_sale_invoice_info');
			   	Session::forget('physcl_sale_sub_total');
			    Session::forget('physcl_sale_discount');
			    Session::forget('physcl_sale_gross_total');
			    Session::forget('physcl_sale_serial_no');
			    Session::forget('physcl_sale_sales_date');
			    Session::forget('physcl_sale_customer');
			}
			return response()->json();
		}
	}

	public function get_customer_info(Request $request) {
		if($request->ajax()){
			$customer_id = $request->input('customer_id');
			$customer_info = Customer::find($customer_id);
			return response()->json($customer_info);
		}
	}

	public function register(Request $request) {
		$invoice_info = Session::get('physcl_sale_invoice_info');


			$grosstotal=$request->input('gross_total');
            $salesamount = DB::table('sales')->where('customerid',$request->input('customersid'))->where('status',1)->sum('gamount');
            $receiveamount = DB::table('voucher')->where('cid',$request->input('customersid'))->where('vstatus',1)->whereIn('type', [3,4,6,7,8,9])->sum('amount'); 
            $creditlimit= DB::table('customers')->where('id',$request->input('customersid'))->first(); 
            $closebalance=(($creditlimit->openbalance+$receiveamount)-$salesamount); 
			//$total=$closebalance+$grosstotal;
			//echo $closebalance;
			//die();
           // echo 'C-'.$creditlimit->creditlimit.'T-'.$total; die();			
			//if($closebalance<=$creditlimit->creditlimit){	
			//save sales
			
			 $qnt=$request->input('qnt');
			$rate=$request->input('rate');
			$count_row = 0;
			foreach ($qnt as $key => $value) {
				if(($value != '') && ($rate[$key] != '')){
					$count_row++;
				}
			}
			if($count_row > 0){
				$customer = Customer::find($request->input('customersid'));
				$serial_no = $request->input('serial_no');
				$discount=$request->input('discount');
				 $u = new Physicalsale();
				 /*
                 $customer = Customer::find($sales->customerid);
					if($customer->lastdue==0){
					$sales->previousdue=$customer->openbalance;
					}else{
					 $sales->previousdue=$customer->lastdue;	
					}
					$sales->presentbalance=$sales->gamount+$sales->previousdue;
					$customer->lastdue = $sales->presentbalance;
					$customer->save();				
				 */ 
				 $u->name = $request->input('name');
				 $u->salesdate = CommonController::date_format($request->input('sales_date'));
				 $u->customerid = $request->input('customersid');
				 $u->discount = $request->input('discount');
				 $u->userid = $request->input('userid');
				 if($customer->bstatus==0){
					$u->previousdue=$customer->openbalance;
				 }else{
					 $u->previousdue=$customer->lastdue;	
				 }
				 $u->presentbalance=$grosstotal+$u->previousdue;
				 $u->save();
				 $customer->lastdue = $u->presentbalance;
				 $customer->bstatus=1;
				 $customer->save();
				 $LastInsertId = $u->id;
				 //echo $LastInsertId;
				if($LastInsertId!=NULL){
				// 	//echo $LastInsertId.'<br>';			
				 	$itemid=$request->input('itemid');
				 	$qnt=$request->input('qnt');
				 	$measurementid=$request->input('measurementid');
				 	$rate=$request->input('rate');
				 	$amount=$request->input('amount');
					$sum=0;
				 	foreach($itemid as $item =>$value){
				 		if(($qnt[$item] != '') && ($rate[$item] != '')){
					 		$u = new Salesdetails();
					 		$u->salesid = $LastInsertId;
							$u->itemid = $value;
							$u->quantity = $qnt[$item];
							$u->mesid = $measurementid[$item];
					 		$u->rate = $rate[$item];
				     		$u->amount = $amount[$item];
					 		$u->userid = $request->input('userid');
					 		$u->save();
		                    $sum=$sum+$amount[$item];

		                    if($invoice_info[$item]->serial_no_exist == 1){
		                    	foreach ($invoice_info[$item]->product_slno as $slno_id) {
		                    		$factory_item = Factoryitem::find($slno_id);
		                    		$factory_item->salesid = $LastInsertId;
									$factory_item->sale_product = 1;
									$factory_item->save();
		                    	}
		                	} else{
		                		$sale_product_item  = Item::find($value);
								$sale_product_item->quantity = $sale_product_item->quantity - $qnt[$item];
								$sale_product_item->save();
		                	}
					 		
					 	// 	$factory_items = Factoryitem::where('itemsid', $value)
							// 					->where('status', 1)
							// 					->where('sale_product', 0)
							// 					->orderBy('id', 'asc')
							// 					->take($qnt[$item])
							// 					->get();

							// foreach ($factory_items as $fac_item) {
							// 	$fac_item->salesid = $LastInsertId;
							// 	$fac_item->sale_product = 1;
							// 	$fac_item->save();
							// }
						}	
				 	}
					
					$dis=$sum-$discount;
					$c = new Customersledger();
					$c->sv=$LastInsertId;
					$c->cid = $request->input('customersid');
					$c->amount = $dis;
					$c->dc = 0;
					$c->save();
	                Physicalsale::where('id',$LastInsertId)->update(array('gamount' => $dis));				
			    }

				if (Session::has('physcl_sale_invoice_info')) {
				    Session::forget('physcl_sale_invoice_info');
				    Session::forget('physcl_sale_sub_total');
				    Session::forget('physcl_sale_discount');
				    Session::forget('physcl_sale_gross_total');
				    Session::forget('physcl_sale_serial_no');
				    Session::forget('physcl_sale_sales_date');
				    Session::forget('physcl_sale_customer');
				}
			} else{
				return Redirect('physicalsales/addnew');
			}
			return Redirect('physicalsales');
			
			//save sales
			
			//}else{
				//echo '-S'.$salesamount.'-R'.$receiveamount.'-C'.$creditlimit->creditlimit.'-O'.$creditlimit->openbalance.'<br>';
			//    echo 'Total'.$closebalance.'<br>';
			//	echo 'Your Credit is'.$creditlimit->creditlimit;
			// }	
			/*
			
			*/
	}

	public function customer_register(Request $request) {
		if($request->ajax() && ($request->method() == 'POST')){
			$customer_code = $request->input('customer_code');
			$customer_name = $request->input('customer_name');
			$opening_balance = $request->input('opening_balance');
			$preaddress = $request->input('preaddress');

			$customer = new Customer();
			$customer->code = $customer_code;
			$customer->name = $customer_name;
			$customer->openbalance = $opening_balance;
			$customer->preaddress = $preaddress;
			$customer->save();

			Session::flash('new_customer_id', $customer->id);
			return response()->json(1);
		}	
	}	
	public function view(Request $request,$pid)
	{
	    $profile=Companyprofile::get();
		$var = array($pid);
	 	$spname="viewsales";
	 	$value=Info::callinfo($var,$spname);
	 	//print_r($value);
	 	$spname1="salesdetailsview";
		
	 	$value1=Info::callinfo($var,$spname1);
		return view('viewphysicalsale',compact('profile','value','value1'));
				
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
				$discount=$valu->discount;
				$status= $valu->status;
				$gamount= $valu->gamount;
				$previousdue= $valu->previousdue;
				$presentbalance= $valu->presentbalance;
				//echo $status.'<br>';
		} 
        //die();		
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
		 
		$html1='<p></p>
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;
						<u>Invoice/Bill</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Customer Name:'.$cusname.'</td>
										<td></td>
										<td>Voucher No:'.$sailname.'</td>
										
									</tr>
									<tr>
									
										<td>Address:'.$preaddress.'</td>
										<td></td>
										<td>Date:'.$salesdate.'</td>
										
									</tr>
										<tr>
									
										<td>Mobile No:'.$phone.'</td>
										<td></td>
										<td></td>
										
									</tr>
									
								
								</table> 
							</div>

		  <div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
			   <th style="width:9%;">&nbsp;&nbsp;SI No</th>
				<th style="width:23%;">&nbsp;&nbsp;&nbsp;Item Name</th>
				<th style="width:12%;">&nbsp;&nbsp;Quantity</th>
				<th style="width:20%;">Measurement Unit</th>
				<th >&nbsp;&nbsp;Rate</th>
				<th style="width:20%;">&nbsp;&nbsp;Amount</th>
			  </tr>';
			  
		$html2= '';
		$i=1;
		$sum=0;
		foreach($value1 as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->quantity.'</td>
				<td style="background-color:#ffffff;">'.$valu->mname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$valu->rate.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;'.$valu->amount.'</td></tr>';
				$html2=$html2.$html;
				$i++;
				$sum=$sum+$valu->amount;
		} 
		$html3='<tr><td colspan="5" align="right" style="background-color:#ffffff;">';
		$html4='';
        
 		$html5='Sub Total:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($sum, 2, '.', '').'</td></tr>';
		$html6='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Discount:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($discount, 2, '.', '').'</td></tr>
		        <tr><td colspan="5" align="right" style="background-color:#ffffff;">Gross Total:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($sum-$discount, 2, '.', '').'</td></tr>';
		$gtotal=$sum-$discount;		
		if($status==1){
			$html7='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Previous due:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($value[0]->previousdue, 2, '.', '').'</td></tr>';
			$pdue=$value[0]->openbalance;
		}else{
			$html7='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Previous due:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($value[0]->openbalance, 2, '.', '').'</td></tr>';
		}		
		if($status==1){
			 //$prebalance=$gtotal+$pdue;
			/*$html8='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Present Balance:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($value[0]->openbalance, 2, '.', '').'</td></tr>';
			*/
			$html8='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Present Balance:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($presentbalance, 2, '.', '').'</td></tr>';
		}else{
			$html8='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Present Balance:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;'.number_format($value[0]->openbalance, 2, '.', '').'</td></tr>';
		}				
		
		$html9='</table><h4>Amount in word:'.CommonController::convertNumberToWord(number_format($gamount, 2, '.', '')).' Taka Only</h4></div>
					<div></div>
					<div></div><div></div>
						
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
		
        $html=$html1.$html2.$html3.$html4.$html5.$html6.$html7.$html8.$html9;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('sales.pdf');
		
	}
	
	public function challan(Request $request,$pid)
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
		$spname2="challanitem";
		$value2=Info::callinfo($var,$spname2);
		$value3=Info::callinfo($var,$spname2);
		//print_r($var); die();
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
				</div>
		
		<div>
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;
						<u>CHALLAN</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Customer Name:'.$cusname.'</td>
										<td></td>
										<td>Voucher No:'.$sailname.'</td>
										
									</tr>
									<tr>
									
										<td>Address:'.$preaddress.'</td>
										<td></td>
										<td>Date:'.$salesdate.'</td>
										
									</tr>
										<tr>
									
										<td>Mobile No:'.$phone.'</td>
										<td></td>
										<td></td>
										
									</tr>
									
								
								</table> 
							</div>
		
		  <div> 
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">		
			  <tr>
				<th style="width:15%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SI No</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Code</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;Description</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</th>
				
			  </tr>';
			  
		$html2= '';
		$i=1;
		$total=0;
		foreach($value1 as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->icode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->quantity.'</td>
				
				</tr>';
				$html2=$html2.$html;
				$i++;
				$total=$total+$valu->quantity;
		}
		$html3='<tr><td colspan="3" align="right" style="background-color:#ffffff;">';
		$html4='';
        
 		$html5='Total:</td><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$total.'</td></tr>';
	
		
		$html6='<tr><td colspan="4">';
		$html7='';
        foreach($value3 as $v){
			$factioyitems = DB::table('factioyitems')->where('itemsid',$v->id)->where('salesid',$id)->get();
			//print_r($factioyitems);
			$h='<span style="color:green;">'.$id.$v->id.$v->name.'</span>(';
			$h1='';
			foreach($factioyitems as $f){
				$h2=''.$f->slno.',';
				$h1=$h1.$h2;
			}
			$h3=')<br>';
			$html=$h.$h1.$h3;
			$html7=$html7.$html;
		}
 		$html8='</td></tr>';
		$html9='</table><h4>Amount in word:'.CommonController::convertNumberToWord(number_format($total, 2, '.', '')).' Pcs Only</h4></div>
		
					<div>
					

						</div>
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
		
        $html=$html1.$html2.$html3.$html4.$html5.$html6.$html7.$html8.$html9;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('challan.pdf');
		
	}
	
	public function printtoken(Request $request,$pid)
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
		$spname2="challanitem";
		$value2=Info::callinfo($var,$spname2);
		$value3=Info::callinfo($var,$spname2);
		$sum=0;
		foreach($value1 as $valu){ 
				$a=$valu->amount;	
				$sum=$sum+$a;
		} 
		
		PDF::AddPage('P','A5');
		 
		$html1='
			<p></p>
				<div>
					<table>
						<tr>
							
							<td style="width:85% font-size:30%">
								<h2>'.$cname.'</h2>
								
								'.$address.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
							</td>
						</tr>
<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
						
						<u>Gate Pass</u></h2>
					</table>	
				</div>
		<div></div>
		<div>
					             
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>No:<u>'.$sailname.'</u></td>
										
										<td>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Date:<u>'.$salesdate.'</u></td>
										
									</tr>
									<tr>
										
										<td>Name:'.$cusname.'</td>
										
									</tr>
									<tr>
									<td>Address:'.$preaddress.'</td>
									</tr>	
								
								</table> 
							</div>
		
		  <div> 
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">		
			  <tr>
				<th style="width:15%;">&nbsp;&nbsp;&nbsp;SI No</th>
				
				<th style="width:50%;">&nbsp;&nbsp;&nbsp;Description</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</th>
				
			  </tr>';
			  
		$html2= '';
		$i=1;
		$total=0;
		foreach($value1 as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;('.$valu->icode.')'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$valu->quantity.'</td>
				
				</tr>';
				$html2=$html2.$html;
				$i++;
				$total=$total+$valu->quantity;
		}
		
		$html3='<tr><td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($total, 2, '.', '').'</td>
				
				</tr></table></div>
		
					<div>
					

						</div>
						<div class="col-md-12"  style=" background-color: #ffffff;color:#000000;">
						<h3>&nbsp;&nbsp;&nbsp; Received By

						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						


						Approved By
						  </h3>
						
						
							
						</div>'
						; 
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('token.pdf');
		
	}
	
	public function save_approved(Request $request)
	{
			if($request->ajax()){
				$sales_id=$request->input('sales_id');
				$sales=Physicalsale::find($sales_id);
				if(!empty($sales)){
					/*$customer = Customer::find($sales->customerid);
					if($customer->lastdue==0){
					$sales->previousdue=$customer->openbalance;
					}else{
					 $sales->previousdue=$customer->lastdue;	
					}
					$sales->presentbalance=$sales->gamount+$sales->previousdue;
					$customer->lastdue = $sales->presentbalance;
					$customer->save();
					*/
					$sales->status = 1;
					$sales->save();
				}else{
					return response()->json(0);
				}
				return response()->json(1);
			}
	}
	
	public function save_unapproved(Request $request)
	{
		    
			if($request->ajax()){
				$sales_id=$request->input('sales_id');
				$sales=Physicalsale::find($sales_id);
				if(!empty($sales)){
					/*$customer = Customer::find($sales->customerid);
					$customer->lastdue = $customer->lastdue - $sales->gamount;
					$customer->save();
					$sales->previousdue=0;
					$sales->presentbalance=0;
					*/
					$sales->status = 0;
					$sales->save();
				}else{
					return response()->json(0);
				}
				return response()->json(1);
			}
	}
	
	public function cancel_status(Request $request)
	{
		    
			if($request->ajax()){
				$sales_id=$request->input('sales_id');
				$sales=Physicalsale::find($sales_id);
				if(!empty($sales)){
					$s = DB::table('sales')->where('id',$sales_id)->first();
					DB::table('customers')->where('id', $s->customerid)->update(['lastdue' => $s->previousdue]);
				    $sales->delete();
					DB::table('salesdetails')->where('salesid',$sales_id)->delete();
				}else{
					return response()->json(0);
				}
				return response()->json(1);
			}
	}
	
}
