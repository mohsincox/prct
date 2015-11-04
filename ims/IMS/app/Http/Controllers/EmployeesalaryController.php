<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Employeesalary;
use App\Models\Employeeinfo;
use App\Http\Requests;
use Illuminate\Http\Request;

class EmployeesalaryController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		
		
	}
	
	public function index()
	{
		$c=Employeesalary::joining();
		//print_r ($c);
		return view('employeesal')->with('employeesal',$c);
	}
	public function addnew()
	{
		
		return view('createemployeesal');
	}

	public function get_employee_salary(Request $request)
	{
		if($request->ajax()){
			$employee = Employeeinfo::find($request->input('employee_id'));
			if(!empty($employee)){
				return response()->json($employee);
			} else {
				return response()->json(0);
			}
		}
	}

	public function register(Request $request)
	{
		$b = new Employeesalary();
		$b->id = $request->input('id');
		$b->eid = $request->input('ename');
		$b->pid = $request->input('pname');
		$b->amount = $request->input('amount');
		$b->vdate = CommonController::date_format($request->input('vdate'));
		$b->description = $request->input('description');
		$b->userid = $request->input('userid');

		$b->save();
		return redirect('employeesal');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$b=Employeesalary::find($id);	
			$b->eid = $request->input('ename');
			$b->pid = $request->input('pname');
			$b->amount = $request->input('amount');
			$b->vdate = CommonController::date_format($request->input('vdate'));
			$b->description = $request->input('description');
			$b->save();
			return Redirect('employeesal');
		}
		$data['employeesal']=Employeesalary::find($id);
		
		return view('editemployeesal',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Employeesalary::find($id);				
		$b->delete();
		return Redirect('employeesal');
	}

}
