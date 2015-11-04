<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Pettycash;
use App\Models\Companyprofile;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\Combo;
use DB;
use PDF;
class PettycashController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		
		
	}
	
	public function index()
	{
		$c=Pettycash::joining();
		return view('pettycash')->with('pettycash',$c);
	}
	public function addnew()
	{
		
		return view('createpettycash');
	}

	public function register(Request $request)
	{
		$b = new Pettycash();
		$b->id = $request->input('id');
		$b->particular = $request->input('particular');
		$b->amount = $request->input('amount');
		$b->description = $request->input('description');
		$b->edate = $request->input('edate');
		if($request->input('instatus')!=NULL){
		$b->instatus = $request->input('instatus');
		}
		
		$b->userid = $request->input('userid');
		$b->save();
		return redirect('ledgerentry');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$b=Pettycash::find($id);	
			$b->particular = $request->input('particular');
			$b->amount = $request->input('amount');
			$b->description = $request->input('description');
			$b->save();
			return Redirect('ledgerentry');
		}
		$data['pettycash']=Pettycash::find($id);
		
		return view('editpettycash',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Pettycash::find($id);				
		$b->delete();
		return Redirect('ledgerentry');
	}
	
	public function printpdf(Request $request)
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
        $date=$request->input('submit');
	    $id=$request->input('id');   	
		//return '44444';
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
            $var = array($id,$fromdate,$todate);
			if($id==2)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',4)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;	
         		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Cash Collection A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			 ';
			  $html2='';
			  $total=0.00;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==3)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',2)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Cash Payment A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  ';
			  $html2='';
			  $total=0.00;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==4)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',6)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bkash A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==5)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',7)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									SAP A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==6)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',8)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									KCS A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==7)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',9)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									MBank A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==8)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',3)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bank Collection A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==9)
			{
				
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',1)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bank Payment A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==11)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('sales')->where('status',1)->where('created_at','<',$previousdate)->sum('gamount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Sales A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Invoice No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->gamount;
				$salesname=$valu->salesname;
				$name=$valu->cname;
				$dc=$valu->dc;
				$vdate=$valu->salesdate;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->id.'"   target="_blank">'.$salesname.'</a></td>

					
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==12)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('purchase')->where('status',1)->where('created_at','<',$previousdate)->sum('gross_total');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Purchase A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Purchase No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$purchasename=$valu->purchasename;
				$purchasedate=$valu->purchasedate;
				$gross_total=$valu->gross_total;
				$dc=$valu->dc;
				$sname=$valu->sname;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$purchasedate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$sname.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/purchase/pdf/'.$valu->id.'"   target="_blank">'.$purchasename.'</a></td>
					
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$gross_total.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$gross_total;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==13)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
			//	print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('employeesal')->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;			
		$fdate=date_create($curdate);
		$tdate=date_create($curdate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Salary A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Employee Name</th>
				<th>Description</th>
				
				<th >&nbsp;Particulars</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th>
 				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$employeename=$valu->employeename;
				$amount=$valu->amount;
				$particularsname=$valu->particularsname;
				$vdate=$valu->vdate;
				$description=$valu->description;
				$dc=$valu->dc;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$employeename.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$description.'</td>
					<td style="background-color:#ffffff;">'.$particularsname.'</td>
					
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else{
			//print_r($value);
			$spname="generalledger";
            $value=Info::callinfo($var,$spname);
			//print_r($value);die();
			if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$description=$valu->description;
				$name=$valu->name;
				$dc=$valu->dc;
				$created_at=$valu->created_at;
				$openbalance=$valu->openbalance;
				
			 }		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
								<div>

								 <table border="1" style="width:100%; padding:20px;">
									
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$name.'</td>
										
									</tr>
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.$openbalance.'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$description=$valu->description;
				$name=$valu->name;
				$dc=$valu->dc;
				$created_at=$valu->created_at;
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$created_at.'</td>
					<td style="background-color:#ffffff;">'.$description.'</td>

					<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/ledgerentry/pdf/'.$valu->id.'"   target="_blank">'.$id.'</a></td>
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table></div>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
			}
		}
		 else if($date=='fromdate'){
			$fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
            $var = array($id,$fromdate,$todate);
			if($id==2)
			{
				$spname="sub_ledger_voucher";
				
				$value=Info::callinfo($var,$spname);
				//print_r($value); die();
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',4)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Cash Collection A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
					
				
			}
			else if($id==3)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',2)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;	
		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Cash Payment A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==4)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',6)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bkash A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==5)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',7)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									SAP A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==6)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',8)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									KCS A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==7)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',9)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									MBank A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==8)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
								if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
		foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',3)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bank Collection A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==9)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('voucher')->where('type',1)->where('vstatus',1)->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Bank Payment A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->amount;
				$vnno=$valu->vnno;
				$name=$valu->name;
				$dc=$valu->dc;
				$vdate=$valu->vdate;
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/voucher/pdf/'.$valu->id.'/'.$valu->type.'"   target="_blank">'.$vnno.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
				
			}
			else if($id==11)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('sales')->where('status',1)->where('created_at','<',$previousdate)->sum('gamount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Sales A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Invoice No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$amount=$valu->gamount;
				$salesname=$valu->salesname;
				$name=$valu->cname;
				$dc=$valu->dc;
				$vdate=$valu->salesdate;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$name.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/physicalsales/print/'.$valu->id.'"   target="_blank">'.$salesname.'</a></td>
					
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==12)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
				//print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('purchase')->where('status',1)->where('created_at','<',$previousdate)->sum('gross_total');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Purchase A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Purchase No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$purchasename=$valu->purchasename;
				$purchasedate=$valu->purchasedate;
				$gross_total=$valu->gross_total;
				$dc=$valu->dc;
				$sname=$valu->sname;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$purchasedate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$sname.'</td>
					<td style="background-color:#ffffff;"><a href="http://192.168.1.8/IMS/purchase/pdf/'.$valu->id.'"   target="_blank">'.$purchasename.'</a></td>

					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$gross_total.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$gross_total;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			else if($id==13)
			{
				$spname="sub_ledger_voucher";
				$value=Info::callinfo($var,$spname);
			//	print_r($value);
				if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			 foreach($value as $valu){ 
			$opbalance=$valu->openbalance;
		}
        $previousdate=date('Y-m-d', strtotime($fromdate . " - 1 day"));   		
        $previousbalance= DB::table('employeesal')->where('created_at','<',$previousdate)->sum('amount');
       // echo $previousbalance; die();		
        $openbalance=$opbalance+$previousbalance;			
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Salary A/C</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							

								 <table border="1" style="width:100%; padding:20px;">
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Employee Name</th>
				<th>Description</th>
				
				<th >&nbsp;Particulars</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th>
 				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				
				$employeename=$valu->employeename;
				$amount=$valu->amount;
				$particularsname=$valu->particularsname;
				$vdate=$valu->vdate;
				$description=$valu->description;
				$dc=$valu->dc;
				
			
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$vdate.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$employeename.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$description.'</td>
					<td style="background-color:#ffffff;">'.$particularsname.'</td>
					
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="5" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}
			//die();
			
			else{
			//print_r($var);
			$spname="generalledger";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//die();
			if($value==NULL){
				echo '<h1>Data not found</h1>';
				die();
			}
			//print_r($value);die();
			 foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$description=$valu->description;
				$name=$valu->name;
				$dc=$valu->dc;
				$created_at=$valu->created_at;
				$openbalance=$valu->openbalance;
				
			 }		
		$fdate=date_create($fromdate);
		$tdate=date_create($todate);
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
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Sub Ledger</h2>
						 <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
									Ledger Account</h4>	
                         <h4>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
								From&nbsp;'.date_format($fdate,"d-M-Y").'&nbsp;To&nbsp;'.date_format($tdate,"d-M-Y").'</h4>
								 
							</div>
							
								<div>

								 <table border="1" style="width:100%; padding:20px;">
									
									<tr>
										
										<td  style="width:20%">Accounts of:</td>
										
										<td  style="width:80%">'.$name.'</td>
										
									</tr>
									
									
								</table>
					
		
						
			 <table border="1" style="background-color:lightblue; width:100%; padding:20px;">	
			  <tr>
				<th >&nbsp;Month & Date</th>
				<th>Particulars</th>

				<th >&nbsp;Voucher No.</th>
				<th>&nbsp;Debit/Credit</th>
			
				<th>&nbsp;&nbsp;Amount</th>
			  </tr>
			  <tr>
				<th style="background-color:#ffffff;">&nbsp;'.date('Y-m-d', strtotime($fromdate . " - 1 day")).'</th>
				<th style="background-color:#ffffff;">Opening Balance</th>
                <th style="background-color:#ffffff;"></th> 				
				<th style="background-color:#ffffff;"></th>
				<th style="background-color:#ffffff;">'.number_format($openbalance, 2, '.', '').'</th>
				<th></th>
			  </tr>';
			  $html2='';
			  $total=$openbalance;
			  foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$description=$valu->description;
				$name=$valu->name;
				$dc=$valu->dc;
				$created_at=$valu->created_at;
                $ht='<tr>
					<td style="background-color:#ffffff;">&nbsp;'.$created_at.'</td>
					<td style="background-color:#ffffff;">'.$description.'</td>

					<td style="background-color:#ffffff;">&nbsp;<a href="http://192.168.1.8/IMS/ledgerentry/pdf/'.$valu->id.'"   target="_blank">'.$id.'</a></td>
					<td style="background-color:#ffffff;">&nbsp;'.$dc.'</td>
					<td style="background-color:#ffffff;">&nbsp;'.$amount.'</td>
				</tr>'; 
				$html2=$html2.$ht;
				$total=$total+$amount;
		    } 
 			
		
		$html3='<tr><td colspan="4" align="right" style="background-color:#ffffff;">Total:</td><td>&nbsp;'.number_format($total, 2, '.', '').'</td></tr>';
		
        
 		$html5='';
		$html6='</table></div>';
	
        $html=$html1.$html2.$html3.$html5.$html6;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('subledgerentry.pdf');
			}			
		 }
	}
	
	public function pdf(Request $request,$id)
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
		$var = array($id);
	 	$spname="ledgerentry";
	 	$value=Info::callinfo($var,$spname);
		//print_r($value);
		foreach($value as $valu){ 
				$id=$valu->id;
				$amount=$valu->amount;
				$description=$valu->description;
				$name=$valu->name;
				$code=$valu->code;
				$created_at=$valu->created_at;

				
		} 
         $date=date_create($created_at);		
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
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Ledger Voucher</u></h2>
								 <table border="0" style="width:100%">
									<tr>
										
										<td>Accounts Name:'.$name.'</td>
										<td></td>
										<td>Manual V. No:7</td>
										
									</tr>
									

								
									<tr>
								   
									<td>Month:'.date_format($date," F,Y").'</td>
										<td></td>
										<td>Date:'.$created_at.'</td>
									</tr>
									<tr>
								   
									<td>Description:'.$description.'</td>
										<td></td>
										<td></td>
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
		
    
        $coa = DB::table('coa')->where('id',1)->first();
       		
		$html2= '
			<tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$name.'</td>
				<td style="background-color:#ffffff;">'.$code.'</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;'.$amount.'</td>
				<td style="background-color:#ffffff;"></td>
			  </tr>
			  <tr>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</td>
				<td style="background-color:#ffffff;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$coa->name.'</td>
				<td style="background-color:#ffffff;">'.$coa->code.'</td>
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
		
        $html=$html1.$html2.$html3;
		
        			
		PDF::writeHTML($html, true, false, true, false, '');
		
		PDF::Output('ledgerentry.pdf');
	}
	
	


}
