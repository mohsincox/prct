<?php namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Employeeinfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Controllers\Common\CommonController;
use DB;

use Input;

class EmployeeinfoController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('employee');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	public function index()
	{
		$c= Employeeinfo::get();
		//print_r ($c);
		return view('employee')->with('employee', $c);
	
	
	}
	public function addnew()
	{
		$c= Employeeinfo::get();
		return view('createemployee')->with('employee', $c);
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
		$c= Employeeinfo::get();
		$m = new Employeeinfo();
		$m->id = $request->input('id');
		$m->name = $request->input('name');
		$m->designation = $request->input('designation');
		$m->joindate = CommonController::date_format($request->input('joindate'));
		$m->preaddress = $request->input('preaddress');
		$m->peraddress = $request->input('peraddress');
		$m->basic_salary = $request->input('basic_salary');
		$m->house_rent = $request->input('house_rent');
		$m->medical_expense = $request->input('medical_expense');
		$m->food_expense = $request->input('food_expense');
		$m->conveyance = $request->input('conveyance');
		$m->entertain_allowance = $request->input('entertain_allowance');
		$m->total_salary = $request->input('total_salary');
		$m->employeetype = $request->input('employeetype');
		$m->uid = $request->input('uid');
		if (Input::file('file')->isValid()) {
		$d = 'uploads';
		$e = Input::file('file')->getClientOriginalExtension(); 
		$f = rand(11111,99999).'.'.$e;
		Input::file('file')->move($d, $f);
		}
		$m->file = $f;
		$m->userid = $request->input('userid');
		$m->save();

		return redirect('employee');
	}
	
	public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$m=Employeeinfo::find($id);
			$m->name = $request->input('name');
			$m->designation = $request->input('designation');
			$m->joindate = CommonController::date_format($request->input('joindate'));
			
			$m->preaddress = $request->input('preaddress');
			$m->peraddress = $request->input('peraddress');
			$m->salary = $request->input('salary');
			$m->employeetype = $request->input('employeetype');
			$m->uid = $request->input('uid');
			if (Input::file('file')->isValid()) {
			$d = 'uploads';
			$e = Input::file('file')->getClientOriginalExtension(); 
			$f = rand(11111,99999).'.'.$e;
			Input::file('file')->move($d, $f);
			}
			$m->file = $f;
			$m->userid = $request->input('userid');
			$m->save();
			return Redirect('employee');
			
		}
		$data['employee']=Employeeinfo::find($id);
		return view('editemployee',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Employeeinfo::find($id);				
		$b->delete();
		return Redirect('employee');
	}

	
}	
