<?php namespace App\Http\Controllers;

use App\Http\Controllers\Common\CommonController;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Info;
use App\Models\Companyprofile;
use App\Models\Purchasedetails;
use App\Models\Suppliersledger;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Item;
use Session;
use PDF;
use DB;

class PurchaseController  extends Controller {
	
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
		//$c=Purchase::get();
		$c = Purchase::joining();
		$d=Purchasedetails::get();
		//print_r($d);
		return view('purchase')->with('purchase',$c);
		

	}

	// public function get_purchase_detail(Request $request) {
	// 	if($request->ajax()){
	// 		$id = $request->input('id');
	// 		$var = array($id); 
	// 		$spname="viewpurchase"; 
	// 		$value= Info::callinfo($var,$spname); 
	// 		$pur_info = '';
	// 		foreach($value as $p){ 
	// 			$pur_info = $pur_info.'<tr class="gradeX"> <td>'.$p->pname.'</td><td>'.$p->purchasedate.'</td><td>'.$p->sname.'</td> <td>'.$p->suppliersbillno.'</td> <td>'.$p->suppliersbilldate.'</td> </tr>'; 
	// 		}
	// 		//$bankaccount_info = Bankaccount::find($bankaccount_id);
	// 		return response()->json($pur_info);
	// 	}
	// } 

	public function addnew()
	{
		// if (Session::has('invoice_info') && Session::has('total_amount'))
		// 	{
		// 	    Session::forget('invoice_info');
		// 	    Session::forget('sub_total');
		// 	    Session::forget('discount');
		// 	    Session::forget('others_exp');
		// 	    Session::forget('gross_total');
		// 	    Session::forget('purchase_date');
		// 	    Session::forget('supplier');
		// 	    Session::forget('supplier_bill_no');
		// 	    Session::forget('supplier_bill_date');
		// 	    Session::forget('supplier_challan_no');
		// 	}
		return view('createpurchase');
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
			return response()->json($iteminfo);
		}
	}

	public function session_invoice(Request $request)
	{
		if($request->ajax()){
			// if (Session::has('invoice_info') && Session::has('total_amount'))
			// {
			    Session::forget('invoice_info');
			    Session::forget('sub_total');
			    Session::forget('discount');
			    Session::forget('others_exp');
			    Session::forget('gross_total');
			    Session::forget('purchase_date');
			    Session::forget('supplier');
			    Session::forget('supplier_bill_no');
			    Session::forget('supplier_bill_date');
			    Session::forget('supplier_challan_no');
			// }


			$invoice_info = json_decode($request->input('invoice_info'));
			$sub_total = $request->input('sub_total');
			$discount = $request->input('discount');
			$others_exp = $request->input('others_exp');
			$gross_total = $request->input('gross_total');
			$purchase_date = $request->input('purchase_date');
			$supplier = $request->input('supplier');
			$supplier_bill_no = $request->input('supplier_bill_no');
			$supplier_bill_date = $request->input('supplier_bill_date');
			$supplier_challan_no = $request->input('supplier_challan_no');

			Session::put('invoice_info', $invoice_info);
			Session::put('sub_total', $sub_total);
			Session::put('discount', $discount);
			Session::put('others_exp', $others_exp);
			Session::put('gross_total', $gross_total);
			Session::put('purchase_date', $purchase_date);
			Session::put('supplier', $supplier);
			Session::put('supplier_bill_no', $supplier_bill_no);
			Session::put('supplier_bill_date', $supplier_bill_date);
			Session::put('supplier_challan_no', $supplier_challan_no);
			return response()->json();
		}
	}

	public function invoice_remove(Request $request)
	{
		if($request->ajax()){
			// if (Session::has('invoice_info') && Session::has('total_amount'))
			// {
			    Session::forget('invoice_info');
			    Session::forget('sub_total');
			    Session::forget('discount');
			    Session::forget('others_exp');
			    Session::forget('gross_total');
			    Session::forget('purchase_date');
			    Session::forget('supplier');
			    Session::forget('supplier_bill_no');
			    Session::forget('supplier_bill_date');
			    Session::forget('supplier_challan_no');
			// }
			return response()->json();
		}
	}
	
	public function register(Request $request) {
			// echo $request->input('sub_total');
			// echo $request->input('discount');
			// echo $request->input('others_exp');
			// echo $request->input('gross_total'); die();

			$qnt=$request->input('qnt');
			$rate=$request->input('rate');

			$count_row = 0;
			foreach ($qnt as $key => $value) {
				if(($value != '') && ($rate[$key] != '')){
					$count_row++;
				}
			}
			if($count_row > 0){
				$u = new Purchase();
				$u->name = $request->input('name');
				if(!empty($request->input('purchase_date'))){
					$u->purchasedate = CommonController::date_format($request->input('purchase_date'));
				}
				$u->suppliersid = $request->input('supplierid');
				$u->suppliersbillno = $request->input('supplier_bill_no');
				$u->challanno = $request->input('supplier_challan_no');
				if(!empty($request->input('supplier_bill_date'))){
					$u->suppliersbilldate = CommonController::date_format($request->input('supplier_bill_date'));
				}
				$u->sub_total = $request->input('sub_total');
				$u->discount = $request->input('discount');
				$u->others_exp = $request->input('others_exp');
				$u->gross_total = $request->input('gross_total');
				$u->old_sub_total = $request->input('sub_total');
				$u->old_discount = $request->input('discount');
				$u->old_others_exp = $request->input('others_exp');
				$u->old_gross_total = $request->input('gross_total');
				$u->userid = $request->input('userid');
				$u->save();
				$LastInsertId = $u->id;
				if($LastInsertId!=NULL){
					//echo $LastInsertId.'<br>';			
					$itemid=$request->input('itemid');
					$qnt=$request->input('qnt');
					$measurementid=$request->input('measurementid');
					$rate=$request->input('rate');
					$amount=$request->input('amount');
					$sum=0;
					foreach($itemid as $item =>$value){
						if(($qnt[$item] != '') && ($rate[$item] != '')){
							$u = new Purchasedetails();
							$u->purchaseid = $LastInsertId;
							$u->itemid = $value;
							$u->quantity = $qnt[$item];
							$u->old_quantity = $qnt[$item];
							$u->mesid = $measurementid[$item];
							$u->rate = $rate[$item];
							$u->old_rate = $rate[$item];
							$u->amount = $amount[$item];
							$u->old_amount = $amount[$item];
							$u->userid = $request->input('userid');
							$u->save();
							$sum=$sum+$amount[$item];
						}
					    // echo 'itemid-'.$value.'quantity-'.$qnt[$item].'measureid-'.$measurementid[$item].'rate-'.$rate[$item].'amount-'.$amount[$item].'<br>';
					}				
				}
	                //$dis=$sum-$discount;
					$c = new Suppliersledger();
					$c->puv=$LastInsertId;
					$c->sid = $request->input('supplierid');
					$c->amount = $request->input('gross_total');
					$c->save();
				// if (Session::has('invoice_info') && Session::has('total_amount')) {
				    Session::forget('invoice_info');
				    Session::forget('sub_total');
				    Session::forget('discount');
				    Session::forget('others_exp');
				    Session::forget('gross_total');
				    Session::forget('purchase_date');
				    Session::forget('supplier');
				    Session::forget('supplier_bill_no');
				    Session::forget('supplier_bill_date');
				    Session::forget('supplier_challan_no');
				// }
			} else{
				return Redirect('purchase/addnew');
			}	
			return Redirect('purchase');
	}

	public function supplier_register(Request $request) {
		if($request->ajax() && ($request->method() == 'POST')){
			$supplier_code = $request->input('supplier_code');
			$supplier_name = $request->input('supplier_name');
			$preaddress = $request->input('preaddress');
			// save data
			$supplier = new Supplier();
			$supplier->code = $supplier_code;
			$supplier->name = $supplier_name;
			$supplier->preaddress = $preaddress;
			$supplier->save();

			Session::flash('new_supplier_id', $supplier->id);
			return response()->json(1);
		}	
	}	

	public function view(Request $request,$pid)
	{	 $profile=Companyprofile::get();
		$var = array($pid);
		$spname="viewpurchase";
		$value=Info::callinfo($var,$spname);
		//print_r($value);
		$spname1="purchasedetailsview";
		$value1=Info::callinfo($var,$spname1);
		return view('viewpurchase', compact('profile','value','value1'));			
	}

	public function edit(Request $request,$pid=NULL) {	
		if($request->method() == 'POST'){
			$edit_purchase = Purchase::find($request->input('purchase_id'));
			if(!empty($request->input('purchase_date'))){
				$edit_purchase->purchasedate = CommonController::date_format($request->input('purchase_date'));
			}
			if(!empty($request->input('supplier_bill_date'))){
				$edit_purchase->suppliersbilldate = CommonController::date_format($request->input('supplier_bill_date'));
			}
			$edit_purchase->suppliersbillno = $request->input('supplier_bill_no');
			$edit_purchase->challanno = $request->input('supplier_challan_no');

			$edit_purchase->sub_total = $request->input('sub_total');
			$edit_purchase->discount = $request->input('discount');
			$edit_purchase->others_exp = $request->input('others_exp');
			$edit_purchase->gross_total = $request->input('gross_total');
			$edit_purchase->save();

			$purchase_detail_id = $request->input('purchase_detail_id');
			$qnt=$request->input('qnt');
			// $measurementid=$request->input('measurementid');
			$rate=$request->input('rate');
			$amount=$request->input('amount');
			// $sum=0;
			foreach($purchase_detail_id as $item =>$value){
				if(($qnt[$item] != '') && ($rate[$item] != '')){
					$u = Purchasedetails::find($value);
					$u->quantity = $qnt[$item];
					$u->rate = $rate[$item];
					$u->amount = $amount[$item];
					$u->save();
				}
			}			
			DB::table('suppliersledger') ->where('puv', $request->input('purchase_id')) ->update(['amount' => $request->input('gross_total')]);
			
			return Redirect('purchase');
		} 
		$var = array($pid);
		$spname="viewpurchase";
		$data['purchase_info'] = Info::callinfo($var,$spname);

		$spname1="purchasedetailsview";
		$data['invoice_info'] = Info::callinfo($var,$spname1);

		$data['purchase'] = Purchase::find($pid);
		return view('edit_purchase', $data);			
	}
	
	
		public function popdf(Request $request,$pid)
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
		$var = array($pid);
		$spname="viewpurchase";
		$value=Info::callinfo($var,$spname);
		foreach($value as $valu){ 
				$pname=$valu->pname;
				$purchasedate=$valu->purchasedate;
				$sname=$valu->sname;
				$address=$valu->address;
				$challanno=$valu->challanno;
				$suppliersbillno=$valu->suppliersbillno;
				$suppliersbilldate=$valu->suppliersbilldate;
				$discount=$valu->old_discount;
				$others_exp=$valu->old_others_exp;
				$gross_total=$valu->old_gross_total;
				$status=$valu->status;
				
		} 		
		$pdate=date_create($purchasedate);
		$sdate=date_create($suppliersbilldate);
		
		
		$spname1="purchasedetailsview";
		$value1=Info::callinfo($var,$spname1);

		$sum=0;
		foreach($value1 as $valu){ 
				$a=$valu->old_amount;	
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
								
								'.$aaddress.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
							</td>
						</tr>

					</table>	
				</div>
						
						  <div>';
						  
					
					            $hst='<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  &nbsp;&nbsp;<u>PURCHASE ORDER</u></h2>';
						
							  
													 $hex='<table border="0" style="width:100%">
								 <tr>
										
										<td>Supplier Name:'.$sname.'</td>
										<td></td>
										<td>Voucher No:'.$pname.'</td>
										
									</tr>
									<tr>
									
										<td>Bill No:'.$suppliersbillno.'</td>
										<td></td>
										<td>Challan No:'.$challanno.'</td>
										
									</tr>
									<tr>
									
										<td>Address:'.$address.'</td>
										<td></td>
										<td>Purchase Date:'.date_format($pdate,"d/m/Y").'</td>
										
									</tr>
									
									
								
								</table> 
							</div>
							<div>
						
						
					<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">
					  <tr>
						<th>Item Name</th>
						<th>Quantity</th>
						<th>Measurement Unit</th>
						<th>Rate</th>
						<th>Amount</th>
					  </tr>';
		$html1=$html1.$hst.$hex;	
		$html2= '';
		foreach($value1 as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">'.$valu->old_quantity.'</td>
				<td style="background-color:#ffffff;">'.$valu->mname.'</td>
				<td style="background-color:#ffffff;">'.$valu->old_rate.'</td>
				<td style="background-color:#ffffff;">'.$valu->old_amount.'</td></tr>';
				$html2=$html2.$html;
				
		} 
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">';
		$html4='';
        
 		$html5='Sub Total:</td><td style="background-color:#ffffff;">'.number_format($sum, 2, '.', '').'</td></tr>';
		$html6='
		      <tr><td colspan="4" align="right" style="background-color:#ffffff;">Discount:</td><td style="background-color:#ffffff;">'.number_format($discount, 2, '.', '').'</td></tr>';
 		$html7='
		       <tr><td colspan="4" align="right" style="background-color:#ffffff;">Others Exp.:</td><td style="background-color:#ffffff;">'.number_format($others_exp, 2, '.', '').'</td></tr>';

		$html8='
		       <tr><td colspan="4" align="right" style="background-color:#ffffff;">Gross Total:</td><td style="background-color:#ffffff;">'.number_format($gross_total, 2, '.', '').'</td></tr>';
				
		$html9='</table><h4>Amount in word:'.CommonController::convertNumberToWord(number_format($gross_total, 2, '.', '')).' Taka Only</h4></div>
					<div>
					
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
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
		
		PDF::Output('popurchase.pdf');
		
	}
	
	public function pdf(Request $request,$pid)
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
		$var = array($pid);
		$spname="viewpurchase";
		$value=Info::callinfo($var,$spname);
		foreach($value as $valu){ 
				$pname=$valu->pname;
				$purchasedate=$valu->purchasedate;
				$sname=$valu->sname;
				$address=$valu->address;
				$challanno=$valu->challanno;
				$suppliersbillno=$valu->suppliersbillno;
				$suppliersbilldate=$valu->suppliersbilldate;
				$discount=$valu->discount;
				$others_exp=$valu->others_exp;
				$gross_total=$valu->gross_total;
				$status=$valu->status;
				
		} 		
		$pdate=date_create($purchasedate);
		$sdate=date_create($suppliersbilldate);
		
		
		$spname1="purchasedetailsview";
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
								
								'.$aaddress.'
									<br>Tel:'.$tele.',Mobile:'.$mobile.'
									<br>E-mail:'.$email.'
									<br>'.$url.'
								 
							</td>
						</tr>

					</table>	
				</div>
						
						  <div>';
						  
					
					            $hst='<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						  &nbsp;&nbsp;<u>PURCHASE VOUCHER</u></h2>';
						
							  
								 $hex='<table border="0" style="width:100%">
								 <tr>
										
										<td>Supplier Name:'.$sname.'</td>
										<td></td>
										<td>Voucher No:'.$pname.'</td>
										
									</tr>
									<tr>
									
										<td>Bill No:'.$suppliersbillno.'</td>
										<td></td>
										<td>Challan No:'.$challanno.'</td>
										
									</tr>
									<tr>
									
										<td>Address:'.$address.'</td>
										<td></td>
										<td>Purchase Date:'.date_format($pdate,"d/m/Y").'</td>
										
									</tr>
									
									
								
								</table> 
							</div>
							<div>
						
						
					<table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">
					  <tr>
						<th>Item Name</th>
						<th>Quantity</th>
						<th>Measurement Unit</th>
						<th>Rate</th>
						<th>Amount</th>
					  </tr>';
		$html1=$html1.$hst.$hex;			  
		$html2= '';
		foreach($value1 as $valu){ 
				$html='<tr><td style="background-color:#ffffff;">'.$valu->iname.'</td>
				<td style="background-color:#ffffff;">'.$valu->quantity.'</td>
				<td style="background-color:#ffffff;">'.$valu->mname.'</td>
				<td style="background-color:#ffffff;">'.$valu->rate.'</td>
				<td style="background-color:#ffffff;">'.$valu->amount.'</td></tr>';
				$html2=$html2.$html;
				
		} 
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">';
		$html4='';
        
 		$html5='Sub Total:</td><td style="background-color:#ffffff;">'.number_format($sum, 2, '.', '').'</td></tr>';
		$html6='
		      <tr><td colspan="4" align="right" style="background-color:#ffffff;">Discount:</td><td style="background-color:#ffffff;">'.number_format($discount, 2, '.', '').'</td></tr>';
 		$html7='
		       <tr><td colspan="4" align="right" style="background-color:#ffffff;">Others Exp.:</td><td style="background-color:#ffffff;">'.number_format($others_exp, 2, '.', '').'</td></tr>';

		$html8='
		       <tr><td colspan="4" align="right" style="background-color:#ffffff;">Gross Total:</td><td style="background-color:#ffffff;">'.number_format($gross_total, 2, '.', '').'</td></tr>';
				
		$html9='</table><h4>Amount in word:'.CommonController::convertNumberToWord(number_format($gross_total, 2, '.', '')).' Taka Only</h4></div>
					<div>
					
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
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
		
		PDF::Output('purchase.pdf');
		
	}
	
	public function save_approved(Request $request)
	{
		    
			if($request->ajax()){
				$sales_id=$request->input('sales_id');
				$sales=Purchase::find($sales_id);
				if(!empty($sales)){
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
				$sales=Purchase::find($sales_id);
				if(!empty($sales)){
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
				$sales=Purchase::find($sales_id);
				if(!empty($sales)){
					$sales->cstatus = 1;
					$sales->save();
				}else{
					return response()->json(0);
				}
				return response()->json(1);
			}
	}
	
}
