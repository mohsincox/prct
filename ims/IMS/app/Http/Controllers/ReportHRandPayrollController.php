<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Companyprofile;
use App\Models\Employeesalary;
use App\Models\Employeeinfo;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
class ReportHRandPayrollController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		
		
	}
	
	public function index()
	{
		$data['employees'] = Employeeinfo::all();
		return view('reportHRandPayroll', $data);
	}

	public function datewise(Request $request) {
		
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
		
		$check_data = $request->input('submit');
		$employee_id = $request->input('employee_id');

		if($check_data == 'today'){
			$current_date = date("Y-m-d");
			if($employee_id == 0){
				$employee_salary = Employeesalary::get_today_all_employee_salary($current_date);
			} else if(($employee_id != -1) && ($employee_id != 0)){
				$employee_salary = Employeesalary::get_today_employee_salary($current_date, $employee_id);
			}
			
		} else if($check_data == 'fromdate'){
			$fromdate = $request->input('fromdate');
			$todate = $request->input('todate');
			$fromdate = date('Y-m-d', strtotime($fromdate));
			$todate_view = date('Y-m-d', strtotime($todate));
			$todate = date('Y-m-d', strtotime($todate. " + 1 day"));
			if($employee_id == 0){
				$employee_salary = Employeesalary::get_from_to_all_employee_salary($fromdate, $todate);
			} else if(($employee_id != -1) && ($employee_id != 0)){
				$employee_salary = Employeesalary::get_from_to_employee_salary($fromdate, $todate, $employee_id);
			}
		}
		//print_r($employee_salary);
		if(count($employee_salary) > 0){
			
			//$fdate=date_create($vdate);
			//$tdate=date_create($vdate);
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
							<u>Employee Report</u></h2>
							<h4>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							if($check_data == 'today'){
								$html1 =$html1.$current_date.'&nbsp;To&nbsp;'.$current_date.'</h4>';
							} else if($check_data == 'fromdate'){
								$html1 = $html1.$fromdate.'&nbsp;To&nbsp;'.$todate_view.'</h4>';
							}
							$html1 = $html1.'</div>
								<div>
							
							
						<table border="1" style="background-color:lightblue; width:100%; padding:20px;">
						  <tr>
							<th style="width:15%">Serial No.</th>
							<th>Employee Name</th>
							<th>Particulars</th>
							<th>Description</th>
							<th>Amount</th>
							
						  </tr>';
						  
			$html2= '';
			$i=1;
			foreach($employee_salary as $valu){ 
					$html='<tr><td style="background-color:#ffffff;width:15%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$i.'</td>
					<td style="background-color:#ffffff;">'.$valu->ename.'</td>
					<td style="background-color:#ffffff;">'.$valu->pname.'</td>
					<td style="background-color:#ffffff;">'.$valu->description.'</td>
					<td style="background-color:#ffffff;">'.$valu->amount.'</td></tr>';
					$html2=$html2.$html;
					$i++;
					
			} 
			
			
			$html3='</table></div>
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
			
			$html=$html1.$html2.$html3;
			
						
			PDF::writeHTML($html, true, false, true, false, '');
			
			PDF::Output('employeesalary.pdf');
		} else {
			echo 'No Information are Found.';
		}
		
	}
}	