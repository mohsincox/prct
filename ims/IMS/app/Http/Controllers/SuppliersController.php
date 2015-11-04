<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Http\Requests;
use Illuminate\Http\Request;

class SuppliersController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('suppliers');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$s=Supplier::orderBy('id','desc')->get();
		//print_r($s);
		//foreach($s as $v){
		//	echo $v->id.'<br>';
		//}
		return view('suppliers')->with('suppliers',$s);

	}
	public function addnew()
	{
		
		return view('createsuppliers');
	}

	public function register(Request $request)
	{
		
		
		$s = new Supplier();
		$s->id = $request->input('id');
		$s->code = $request->input('code');
		$s->openbalance = $request->input('openbalance');
		$s->name = $request->input('name');
		$s->preaddress = $request->input('preaddress');
		$s->peraddress = $request->input('peraddress');
		$s->phone = $request->input('phone');
		$s->email = $request->input('email');
		$s->fax = $request->input('fax');
		$s->url = $request->input('url');
		$s->userid = $request->input('userid');
		$s->save();
		return redirect('suppliers');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$s=Supplier::find($id);		
			$s->name = $request->input('name');
			$s->openbalance = $request->input('openbalance');
			$s->preaddress = $request->input('preaddress');
			$s->peraddress = $request->input('peraddress');
			$s->phone = $request->input('phone');
			$s->email = $request->input('email');
			$s->fax = $request->input('fax');
			$s->url = $request->input('url');
			$s->save();
			return Redirect('suppliers');
		}
		$data['suppliers']=Supplier::find($id);
		return view('editsuppliers',$data);
		
	}
	public function delete(Request $request,$id)
	{		
			$s=Supplier::find($id);					
			$s->delete();
			return Redirect('suppliers');
	}

}
