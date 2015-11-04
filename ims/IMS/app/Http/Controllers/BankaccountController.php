<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Bankaccount;
use App\Models\Bankinfo;
use App\Models\Banktitle;
use App\Http\Requests;
use Illuminate\Http\Request;

class BankaccountController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('bankaccount');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$b=Bankaccount::joining();
		//print_r($b);
		return view('bankaccount')->with('bankaccount',$b);
	}
	public function addnew()
	{
		$ba=Bankinfo::get();
		return view('createbankaccount')->with('bankinfo',$ba);
	}

	public function register(Request $request)
	{
		$b = new Bankaccount();
		$b->id = $request->input('id');
		$b->code = $request->input('code');
		$b->name = $request->input('name');
		$b->accotitle = $request->input('accotitle');
		$b->bankid = $request->input('bankid');
		$b->branchname = $request->input('branchname');
		$b->opendate = CommonController::date_format($request->input('opendate'));
		$b->exdate = CommonController::date_format($request->input('exdate'));		
		$b->rate = $request->input('rate');
		$b->openbalance = $request->input('openbalance');
		$b->userid = $request->input('userid');

		$b->save();
		return redirect('bankaccount');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$b=Bankaccount::find($id);	
			$b->code = $request->input('code');			
			$b->name = $request->input('name');	
			$b->accotitle = $request->input('accotitle');
			$b->bankid = $request->input('bankid');	
			$b->branchname = $request->input('branchname');	
			$b->opendate = CommonController::date_format($request->input('opendate'));
			$b->exdate = CommonController::date_format($request->input('exdate'));
			$b->rate = $request->input('rate');
			$b->openbalance = $request->input('openbalance');
			$b->save();
			return Redirect('bankaccount');
		}
		$data['bankaccount']=Bankaccount::find($id);
		$ba=Bankinfo::get();
		return view('editbankaccount',$data)->with('bankinfo',$ba);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Bankaccount::find($id);				
		$b->delete();
		return Redirect('bankaccount');
	}

}
