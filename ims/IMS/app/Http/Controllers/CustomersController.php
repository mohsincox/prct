<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests;
use Illuminate\Http\Request;

class CustomersController  extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('customers');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function index()
	{
		$c=Customer::get();
		return view('customers')->with('customers',$c);

	}
	public function addnew()
	{
		
		return view('createcustomers');
	}

	public function register(Request $request)
	{
		
		
		$c = new Customer();
		$c->id = $request->input('id');
		$c->code = $request->input('code');
		$c->openbalance = $request->input('openbalance');
		$c->creditlimit = $request->input('creditlimit');
		$c->name = $request->input('name');
		$c->preaddress = $request->input('preaddress');
		$c->peraddress = $request->input('peraddress');
		$c->phone = $request->input('phone');
		$c->email = $request->input('email');
		$c->fax = $request->input('fax');
		$c->url = $request->input('url');
		$c->userid = $request->input('userid');
		$c->save();
		return redirect('customers');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$c=Customer::find($id);	
			$c->code = $request->input('code');
			$c->openbalance = $request->input('openbalance');
			$c->creditlimit = $request->input('creditlimit');			
			$c->name = $request->input('name');	
			$c->preaddress = $request->input('preaddress');
			$c->peraddress = $request->input('peraddress');
			$c->phone = $request->input('phone');
			$c->email = $request->input('email');
			$c->fax = $request->input('fax');
			$c->url = $request->input('url');
			$c->save();
			return Redirect('customers');
			
		}
		$data['customers']=Customer::find($id);
		return view('editcustomers',$data);
		
	}
	public function delete(Request $request,$id)
	{		
			$c=Customer::find($id);					
			$c->delete();
			return Redirect('customers');
	}

}
