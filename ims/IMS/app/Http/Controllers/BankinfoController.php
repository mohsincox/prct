<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Bankinfo;
use App\Http\Requests;
use Illuminate\Http\Request;


class BankinfoController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('bankinfo');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$b=Bankinfo::get();
		return view('bankinfo')->with('bankinfo',$b);
	}
	public function addnew()
	{
		return view('createbankinfo');
	}

	public function register(Request $request)
	{
		
		$v = Validator::make($request->all(),['name' => 'unique:bankinfo',] );

    if ($v->fails())
    {
		$b = new Bankinfo();
		$p=$b->name= $request->input('name');
		return view('createbankinfo')->withErrors($v->errors());
       
    }
	else{
		$b = new Bankinfo();
		$b->id = $request->input('id');
		$b->name = $request->input('name');
		$b->userid = $request->input('userid');
		$b->save();
		return redirect('bankinfo');
	}
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$v = Validator::make($request->all(),['name' => 'unique:bankinfo',] );

    if ($v->fails())
    {
		$b = new Bankinfo();
		$p=$b->name= $request->input('name');
		return view('createbankinfo')->withErrors($v->errors());
       
    }
	else{
			$id=$request->input('id');
			$b=Bankinfo::find($id);		
			$b->name = $request->input('name');	
			$b->save();
			return Redirect('bankinfo');
		}}
		$data['bankinfo']=Bankinfo::find($id);
		return view('editbankinfo',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Bankinfo::find($id);				
		$b->delete();
		return Redirect('bankinfo');
	}

}
