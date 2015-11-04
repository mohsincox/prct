<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Companyprofile;
use App\Models\Voucher;
use App\Models\Physicalsale;
use App\Models\Voucherbankpayment;
use App\Models\Voucherpayment;
use App\Models\Voucherbankreceive;
use App\Models\Voucherreceive;
use App\Models\Vouchercontra;
use App\Models\Voucherbkash;
use App\Models\Vouchersap;
use App\Models\Voucherkcs;
use App\Models\Vouchermbank;
use App\Models\Customersledger;
use App\Models\Suppliersledger;
use App\Http\Controllers\Common\CommonController;
use App\Models\Bankaccount;
use App\Models\Bankbook;
use App\Models\Customer;
use App\Models\Supplier;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Info;
use PDF;
use DB;

class VoucherController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('voucher');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$s=Supplier::get();
		$c=Customer::get();
		$bankaccount=Bankaccount::joining();
		$voucher = Voucher::orderBy('id', 'desc')->where('type','<>',5)->take(10)->get();
		return view('voucher', compact('bankaccount', 'c','s','voucher'));
	}
	
	public function register(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('supplieramount');
		$v->status = 2;
		$v->type = 1;
		$v->sid = $request->input('sid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherbankpayment();
          $m->vid = $LastInsertId;		  
		  $m->baccid = $request->input('baccid');		 
		  $m->sid = $request->input('sid');	  
		  $m->checkno = $request->input('checkno');
		  $m->userid = $request->input('userid');	
		  $m->save();
		  $b=new Bankbook();
          $b->vid = $LastInsertId;		  
		  $b->baccid = $request->input('baccid');		 
		  $b->sid = $request->input('sid');	
          $b->dc = 0;
		  $b->amount = $request->input('amount');	
		  $b->checkno = $request->input('checkno');
		  $b->userid = $request->input('userid');	
		  $b->save();
		}
         $c = new Suppliersledger();
		$c->pav=$LastInsertId;
		$c->sid = $request->input('sid');
		$c->amount = $request->input('amount');
		$c->save();			
		return redirect('voucher');
	}
	public function registerp(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('samount');
		$v->status = 2;
		$v->type = 2;
		$v->sid = $request->input('sid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherpayment();
          $m->vid = $LastInsertId;		  
		  $m->baccid = $request->input('baccid');	
		  
		  $m->sid = $request->input('sid');
		  
		  
		  $m->userid = $request->input('userid');	
		  $m->save();
		}	
        $c = new Suppliersledger();
		$c->pav=$LastInsertId;
		$c->sid = $request->input('sid');
		$c->amount = $request->input('amount');
		$c->save();	 		
		return redirect('voucher');
	}
	public function registerc(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 3;
		$v->cid = $request->input('cid');	
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherbankreceive();
          $m->vid = $LastInsertId;		  
		  $m->baccid = $request->input('baccid');		 
		  $m->cid = $request->input('cid');	  
		  $m->checkno = $request->input('checkno');
		  $m->userid = $request->input('userid');	
		  $m->save();
		  $b=new Bankbook();
          $b->vid = $LastInsertId;		  
		  $b->baccid = $request->input('baccid');		 
		  $b->cid = $request->input('cid');	  
		  $b->checkno = $request->input('checkno');
		  $b->dc = 1;
		  $b->amount = $request->input('amount');
		  $b->userid = $request->input('userid');	
		  $b->save();
		}
        $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->amount = $request->input('camount');
		$c->save();		
		return redirect('voucher');
	}
	public function registerr(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 4;
		$v->cid = $request->input('cid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherreceive();
          $m->vid = $LastInsertId;		  
		  $m->baccid = $request->input('baccid');		 
		  $m->cid = $request->input('cid');	  
		  //$m->checkno = $request->input('checkno');
		  $m->userid = $request->input('userid');	
		  $m->save();
		}
        $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->dc = 1;
		$c->amount = $request->input('camount');
		$c->save();		
		return redirect('voucher');
	}
	public function registercontra(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('bamount');
		$v->status = 3;
		$v->type = 5;
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Vouchercontra();
          $m->vid = $LastInsertId;		  
		  $m->baccid = $request->input('baccid');		 
		  //$m->cid = $request->input('cid');	  
		  $m->checkno = $request->input('checkno');
		  $m->userid = $request->input('userid');	
		  $m->save();
		}	 
		return redirect('voucher');
	}
	  //$m->checkno = $request->input('checkno');
		  //$m->userid = $request->input('userid');	
	public function registerbkash(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 6;
		$v->cid = $request->input('cid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherbkash();
          $m->vid = $LastInsertId;		  
		  $m->bkashno = $request->input('bkashno');		 
		  $m->cid = $request->input('cid');
		  $m->userid = $request->input('userid');
		  $m->save();
		}	
        $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->amount = $request->input('camount');
		$c->save();				
		return redirect('voucher');
	}
	
	public function registersap(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 7;
		$v->cid = $request->input('cid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Vouchersap();
          $m->vid = $LastInsertId;		  
		  $m->sapno = $request->input('sapno');		 
		  $m->cid = $request->input('cid');
		  $m->userid = $request->input('userid');
		  $m->save();
		}
        $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->amount = $request->input('camount');
		$c->save();		
		return redirect('voucher');
	}
	
	public function registerkcs(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 8;
		$v->cid = $request->input('cid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Voucherkcs();
          $m->vid = $LastInsertId;		  
		  $m->kcsno = $request->input('kcsno');		 
		  $m->cid = $request->input('cid');
		  $m->userid = $request->input('userid');
		  $m->save();
		}
         $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->amount = $request->input('camount');
		$c->save(); 		
		return redirect('voucher');
	}
	
	public function registermbank(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->amount = $request->input('amount');
		$v->amount = $request->input('camount');
		$v->status = 1;
		$v->type = 9;
		$v->cid = $request->input('cid');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		if($LastInsertId!=NULL){
		  $m = new Vouchermbank();
          $m->vid = $LastInsertId;		  
		  $m->mbankno = $request->input('mbankno');		 
		  $m->cid = $request->input('cid');
		  $m->userid = $request->input('userid');
		  $m->save();
		}	
         $c = new Customersledger();
		$c->rv=$LastInsertId;
		$c->cid = $request->input('cid');
		$c->amount = $request->input('camount');
		$c->save();		
		return redirect('voucher');
	}
	
	public function pdf(Request $request,$id,$type)
	{
		//echo $id.$type;
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
		
		if($type==1)
		{	
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){		
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$bname=$v->bname;
			$branchname=$v->branchname;
			$accno=$v->accno;
			$checkno=$v->checkno;
			$vdate=$v->vdate;
			$baid=$v->baid;
			$sid=$v->sid;
			$bacode=$v->bacode;
			$scode=$v->scode;
		
		}
	    $date=date_create($vdate); 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Bank Payment Voucher</u></h2>
								 <table border="0" style="width:100%">
									<tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
										
										<td>Bank Name:'.$bname.'</td>
										<td></td>
										<td>Branch Name:'.$branchname.'</td>
										
									</tr>
									<tr>
										<td>A/C No.'.$accno.'</td>
										<td></td>
										<td>Check No.'.$checkno.'</td>
										
									  </tr>
									<tr>
								   
									<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
									</tr>
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$sname = DB::table('suppliers')->where('id',$sid)->pluck('name');
        $bname = DB::table('bankaccount')->where('id',$baid)->pluck('name');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sname.'</td>
				<td style="background-color:#ffffff;">'.$scode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$bacode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
						<div></div>
					<div></div><div></div>
						
						<div style=" background-color: #ffffff;color:#000000;">
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbp.pdf');
		
		}
	
		else if($type==2)
			{	
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$sid=$v->sid;
			$scode=$v->scode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Payment Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
										<td>Unit/Place:</td>
										<td></td>
										<td>Manual V. No.</td>
										
									</tr>
									
									<tr>
								   
									<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
									</tr>
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$sname = DB::table('suppliers')->where('id',$sid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sname.'</td>
				<td style="background-color:#ffffff;">'.$scode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
		
		
		else if($type==3)
		{	
	                  $var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$bname=$v->bname;
			$branchname=$v->branchname;
			$accno=$v->accno;
			$checkno=$v->checkno;
			$vdate=$v->vdate;
			$baid=$v->baid;
			$cid=$v->cid;
			$bacode=$v->bacode;
			$ccode=$v->ccode;
		
		}
	    $date=date_create($vdate); 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Bank Receive Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
										<td>Bank Name:'.$bname.'</td>
										<td></td>
										<td>Branch Name:'.$branchname.'</td>
										
									</tr>
									<tr>
										<td>A/C No.'.$accno.'</td>
										<td></td>
										<td>Check No.'.$checkno.'</td>
										
									  </tr>
									<tr>
								   
									<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
									</tr>
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
        $bname = DB::table('bankaccount')->where('id',$baid)->pluck('name');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
				<td style="background-color:#ffffff;">'.$bacode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
						<div></div>
					<div></div><div></div>
						
						<div style=" background-color: #ffffff;color:#000000;">
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbp.pdf');
		}
		else if($type==4){
				
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$cid=$v->cid;
			$ccode=$v->ccode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
		PDF::AddPage();
		
	      
		$html1=' <p></p>
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Receive Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
									
										<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
										
									</tr>
									
								
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
		else if($type==5){
				
			 $var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$bname=$v->bname;
			$baname=$v->baname;
			$branchname=$v->branchname;
			$checkno=$v->checkno;
			$vdate=$v->vdate;
			$baid=$v->baid;
			$bacode=$v->bacode;
			
		
		}
	    $date=date_create($vdate); 
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Contra Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
										<td>Bank Name:'.$bname.'</td>
										<td></td>
										<td>Branch Name:'.$branchname.'</td>
										
									</tr>
									<tr>
										<td>A/C No.'.$baname.'</td>
										<td></td>
										<td>Check No.'.$checkno.'</td>
										
									  </tr>
									<tr>
								   
									<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
									</tr>
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$baname.'</td>
				<td style="background-color:#ffffff;">'.$bacode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbp.pdf');
		}
		
		else if($type==6){
				
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$cid=$v->cid;
			$ccode=$v->ccode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>bKash Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
									
										<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
										
									</tr>
									
								
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
		
		else if($type==7){
				
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$cid=$v->cid;
			$ccode=$v->ccode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>SAP Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
									
										<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
										
									</tr>
									
								
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
		
		
		else if($type==8){
				
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$cid=$v->cid;
			$ccode=$v->ccode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>KCS Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
									
										<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
										
									</tr>
									
								
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
		
		else if($type==9){
				
		$var = array($id,$type);
	 	$spname="voucher";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $v){	
			$vrno=$v->vrno;
			$mvno=$v->mvno;
			$amount=$v->amount;
			$cid=$v->cid;
			$ccode=$v->ccode;
			$vdate=$v->vdate;
		
		
		}
	    $date=date_create($vdate); 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>MBank Voucher</u></h2>
								 <table border="0" style="width:100%">
								 <tr>
										
										<td>Voucher No:'.$vrno.'</td>
										<td></td>
										<td>Manual V. No:'.$mvno.'</td>
										
									</tr>
									<tr>
									
										<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$vdate.'</td>
										
									</tr>
									
								
								</table> 
							</div>
					
					
			
			
				
		
		  <div> </div>
		  <table border="1 solid" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th style="width:10%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SL</th>
				<th style="width:30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:20%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		$cname = DB::table('customers')->where('id',$cid)->pluck('name');
     	$cashname = DB::table('coa')->where('id',1)->pluck('name');
        $cashcode = DB::table('coa')->where('id',1)->pluck('code');		
		$html2= '<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cashname.'</td>
				<td style="background-color:#ffffff;">'.$cashcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cname.'</td>
				<td style="background-color:#ffffff;">'.$ccode.'</td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>
			   <tr>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;"></td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total:</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
			  </tr>';
		
		$html3='</table><h4>Amount in word:'.CommonController::convertNumberToWord($amount).' Taka Only</h4></div>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('vbpp.pdf');
		
		}
	}
	
    public function save_approved(Request $request)
	{
		    
			if($request->ajax()){
				$sales_id=$request->input('sales_id');
				$voucher=Voucher::find($sales_id);
				if(!empty($voucher)){
					 if((($voucher->sid == NULL) && ($voucher->cid != NULL))){
						$customer = Customer::find($voucher->cid);
						$sales = Physicalsale::where('status', 1)
										 ->where('customerid',$voucher->cid)
										 ->where('presentbalance','<>',0)
										 ->orderBy('id', 'desc')
										 ->get();
				       	foreach($sales as $s){
							$s->presentbalance=$s->presentbalance - $voucher->amount;
							$s->previousdue=$s->previousdue - $voucher->amount;
							/*if($s->presentbalance<=0){
								$s->presentbalance=0;
							}
							if ($s->previousdue<=0){
								$s->previousdue=0;
							}
							*/
							$s->save();
						}
						$salesasc = Physicalsale::where('status', 1)
										 ->where('customerid',$voucher->cid)
										 ->orderBy('id', 'asc')
										 ->get();
						foreach($salesasc as $sasc){
							$presentbalance=$sasc->presentbalance;
						}
                        $customer->lastdue=$presentbalance;
						$customer->save();
					}
					$voucher->vstatus = 1;
					$voucher->save();
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
				$voucher=Voucher::find($sales_id);
				if(!empty($voucher)){
					 if((($voucher->sid == NULL) && ($voucher->cid != NULL))){
						$customer = Customer::find($voucher->cid);
						$sales = Physicalsale::where('status', 1)
										 ->where('customerid',$voucher->cid)
										 ->where('presentbalance','<>',0)
										 ->orderBy('id', 'desc')
										 ->get();
				       	foreach($sales as $s){
							$s->presentbalance=$s->presentbalance + $voucher->amount;
							$s->previousdue=$s->previousdue + $voucher->amount;
							$s->save();
						}
						$salesasc = Physicalsale::where('status', 1)
										 ->where('customerid',$voucher->cid)
										 ->orderBy('id', 'asc')
										 ->get();
						foreach($salesasc as $sasc){
							$presentbalance=$sasc->presentbalance;
						}
                        $customer->lastdue=$presentbalance;
						$customer->save();
					}
					$voucher->vstatus = 0;
					$voucher->save();
				}else{
					return response()->json(0);
				}
				return response()->json(1);
			}
	}
	
	
}
