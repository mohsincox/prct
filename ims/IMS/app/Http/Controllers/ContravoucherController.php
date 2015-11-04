<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Bankaccount;
use App\Models\Vouchercontra;
use App\Models\Voucher;
use App\Models\Supplier;
use App\Models\Companyprofile;
use App\Models\Info;
use App\Models\Bankbook;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Common\CommonController;
use DB;
use PDF;
class ContravoucherController   extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('contravoucher');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function index()
	{
		$c=Customer::get();
		$bankaccount=Bankaccount::joining();
		$voucher = Voucher::orderBy('id', 'desc')->where('type',5)->take(10)->get();
		return view('contravouchar', compact('bankaccount', 'c','voucher'));

	}

	public function cashregister(Request $request)
	{
		
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->status = 1;
		$v->type = 5;
		$v->amount = $request->input('amount');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		//echo $LastInsertId;
		if($LastInsertId!=NULL){
		$b = new Vouchercontra();
		$b->vid = $LastInsertId;	
        $b->baccid = $request->input('baccid');			
		$b->cashid = $request->input('cashid');		   
		$b->checkno = $request->input('checkno');
		$b->userid = $request->input('userid');	
		$b->save();
		  $b=new Bankbook();
          $b->vid = $LastInsertId;		  
		  $b->baccid = $request->input('baccid');		 
		  //$b->sid = $request->input('sid');
          $b->dc = 1; 
		  $b->amount = $request->input('amount');	
		  $b->checkno = $request->input('checkno');
		  $b->userid = $request->input('userid');	
		  $b->save();
		}
		return redirect('contravoucher');
	}
	
	public function bankregister(Request $request)
	{
		
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->status = 2;
		$v->type = 5;
		$v->amount = $request->input('amount');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		//echo $LastInsertId;
		if($LastInsertId!=NULL){
		$b = new Vouchercontra();
		$b->vid = $LastInsertId;	
        $b->baccid = $request->input('baccid');			
		$b->cashid = $request->input('cashid');		   
		$b->checkno = $request->input('checkno');
		$b->userid = $request->input('userid');	
		$b->save();
		  $b=new Bankbook();
          $b->vid = $LastInsertId;		  
		  $b->baccid = $request->input('baccid');		 
		  //$b->sid = $request->input('sid');
          $b->dc = 0;
		  $b->amount = $request->input('amount'); 	
		  $b->checkno = $request->input('checkno');
		  $b->userid = $request->input('userid');	
		  $b->save();
		}
		return redirect('contravoucher');
	}
	
	public function banktobankregister(Request $request)
	{
		$v= new Voucher();
		$v->vnno = $request->input('vnno');	
		$v->vdate = CommonController::date_format($request->input('vdate'));
		$v->status = 3;
		$v->type = 5;
		$v->amount = $request->input('amount');
		$v->userid = $request->input('userid');
		$v->save();
	    $LastInsertId = $v->id;
		//echo $LastInsertId;
		if($LastInsertId!=NULL){
		$b = new Vouchercontra();
		$b->vid = $LastInsertId;	
        $b->baccid = $request->input('baccid');			
		$b->cashid = $request->input('cashid');		   
		$b->checkno = $request->input('checkno');
		$b->userid = $request->input('userid');	
		$b->save();
		  $b=new Bankbook();
          $b->vid = $LastInsertId;		  
		  $b->baccid = $request->input('baccid');
          $b->dc = 1; 
		  $b->amount = $request->input('amount');	
		  //$b->sid = $request->input('sid');	  
		  $b->checkno = $request->input('checkno');
		  $b->userid = $request->input('userid');	
		  $b->save();
		  $b1=new Bankbook();
          $b1->vid = $LastInsertId;		  
		  $b1->baccid = $request->input('cashid');
          $b1->dc = 0;
		  $b1->amount = $request->input('amount');	
		  //$b->sid = $request->input('sid');	  
		  $b1->checkno = $request->input('checkno');
		  $b1->userid = $request->input('userid');	
		  $b1->save();
		}
		return redirect('contravoucher');
		
	}
		public function pdf(Request $request,$id,$type,$status)
	{
		
		//echo $id.$type.$status;
		$voucher = DB::table('voucher')->where('id',$id)->first();
		//print_r($voucher);
		
		$vnno=$voucher->vnno;
		$amount=$voucher->amount;
		$sid=$voucher->sid;
		$cid=$voucher->cid;
		$type=$voucher->type;
		$vdate=$voucher->vdate;
		//echo '<br>';
		$vouchercontra = DB::table('vouchercontra')->where('vid',$voucher->id)->first();
		//print_r($vouchercontra);
		
		$vid=$vouchercontra->vid;
		$baccid=$vouchercontra->baccid;
		$checkno=$vouchercontra->checkno;
		$cashid=$vouchercontra->cashid;
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
		if($status==1){
		
	    $date=date_create($vdate); 
		PDF::AddPage();
		
	     $bname = DB::table('bankinfo')->where('id',$baccid)->first();
         $brancename = DB::table('bankaccount')->where('id',$baccid)->first();
         		 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Cash Contra</u></h2>
								 <table border="0" style="width:100%">
									<tr>
										
										<td>Voucher No:'.$vnno.'</td>
										<td></td>
										<td>Manual V. No:'.$type.'</td>
										
									</tr>
									<tr>
										
										<td>Bank Name:</td>
										<td></td>
										<td>Branch Name:'.$brancename->branchname.'</td>
										
									</tr>
									<tr>
										<td>A/C No.'.$brancename->name.'</td>
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
				<th style="width:35%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:15%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		
        $bname = DB::table('bankaccount')->where('id',$baccid)->pluck('name');
        $coa = DB::table('coa')->where('id',1)->first();
        $bcode = DB::table('bankaccount')->where('id',$baccid)->pluck('code');			
		$html2= '
			<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$coa->name.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$coa->code.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$bcode.'</td>
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
		
		PDF::Output('contravouchar.pdf');
		
		
		}
		else if($status==2)
		{
				
	    $date=date_create($vdate); 
		PDF::AddPage();
		
	     $bname = DB::table('bankinfo')->where('id',$baccid)->first();
         $brancename = DB::table('bankaccount')->where('id',$baccid)->first();
         		 
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
					             <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Bank Contra</u></h2>
								 <table border="0" style="width:100%">
									<tr>
										
										<td>Voucher No:'.$vnno.'</td>
										<td></td>
										<td>Manual V. No:'.$type.'</td>
										
									</tr>
									<tr>
										
										<td>Bank Name:</td>
										<td></td>
										<td>Branch Name:'.$brancename->branchname.'</td>
										
									</tr>
									<tr>
										<td>A/C No.'.$brancename->name.'</td>
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
				<th style="width:35%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:15%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		
        $bname = DB::table('bankaccount')->where('id',$baccid)->pluck('name');
        $coa = DB::table('coa')->where('id',1)->first();
        $bcode = DB::table('bankaccount')->where('id',$baccid)->pluck('code');			
		$html2= '
			<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$bcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$coa->name.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$coa->code.'</td>
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
		
		PDF::Output('contravouchar.pdf');
		}
		else if($status==3)
		{
						
	    $date=date_create($vdate); 
		PDF::AddPage();
		
	     $bname = DB::table('bankinfo')->where('id',$baccid)->first();
         $brancename = DB::table('bankaccount')->where('id',$baccid)->first();
         		 
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Bank To Bank Contra</u></h2>
								 <table border="0" style="width:100%">
									<tr>
										
										<td>Voucher No:'.$vnno.'</td>
										<td></td>
										<td>Manual V. No:'.$type.'</td>
										
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
				<th style="width:35%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Head of Accounts</th>
				<th style="width:15%">&nbsp;&nbsp;&nbsp;&nbsp;A/C Code</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;DR(BDT)</th>
				<th>&nbsp;&nbsp;&nbsp;&nbsp;CR(BDT)</th>
			  </tr>';
		
        $bname = DB::table('bankaccount')->where('id',$baccid)->pluck('name');
        $cash = DB::table('bankaccount')->where('id',$cashid)->pluck('name');
		$ccode = DB::table('bankaccount')->where('id',$cashid)->pluck('code');
        $bcode = DB::table('bankaccount')->where('id',$baccid)->pluck('code');			
		$html2= '
			<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$bcode.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cash.'</td>
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
		
		PDF::Output('contravouchar.pdf');
		} 
		

	   
	}
	
}
