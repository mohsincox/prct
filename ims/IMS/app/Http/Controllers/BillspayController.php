<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Billspay;
use App\Models\Purchase;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use Input;

class BillspayController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('billspay');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$m=Billspay::joining();
		return view('billspay')->with('billspay',$m);
	}
	public function addnew()
	{
		return view('createbillspay');
	}

	public function register(Request $request)
	{
		$m = new Billspay();
		$m->id = $request->input('id');
		$m->purchaseid = $request->input('purchaseid');
		$m->purchasedate = $request->input('purchasedate');
		$m->amount = $request->input('amount');
		if (Input::file('file')->isValid()) {
		$d = 'uploads';
		$e = Input::file('file')->getClientOriginalExtension(); 
		$f = rand(11111,99999).'.'.$e;
		Input::file('file')->move($d, $f);
		}
		$m->file = $f;
		$m->userid = $request->input('userid');
		$m->save();
		$pid=$request->input('purchaseid');
		$p = new Purchase();
		$p = Purchase::find($pid);
        $p->status = 1;
        $p->save();
		return redirect('billspay');
	}
	public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$m=Billspay::find($id);		
			$m->purchaseid = $request->input('purchaseid');
			$m->purchasedate = $request->input('purchasedate');
			$m->amount = $request->input('amount');
			
			$m->save();
			return Redirect('billspay');
		}
		$data['billspay']=Billspay::find($id);
		$p=Purchase::get();
		return view('editbillspay',$data)->with('p',$p);
		
	}
	public function delete(Request $request,$id)
	{		
		$m=Billspay::find($id);				
		$m->delete();
		return Redirect('billspay');
	}
	
	
}
