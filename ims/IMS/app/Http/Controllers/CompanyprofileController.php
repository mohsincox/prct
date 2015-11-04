<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Companyprofile;

use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use Input;

class CompanyprofileController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		$c=Companyprofile::get();
		//print_r($c);
		return view('createcompanyprofile')->with('profile',$c);
	}

	public function register(Request $request)
	{
		$m = new Companyprofile();
		$m->id = $request->input('id');
		$m->name = $request->input('name');
		$m->address = $request->input('address');
		$m->telephone = $request->input('telephone');
		$m->mobile = $request->input('mobile');
		$m->email = $request->input('email');
		$m->url = $request->input('url');
		if (Input::file('file')->isValid()) {
		$d = 'uploads';
		$e = Input::file('file')->getClientOriginalExtension(); 
		$f = rand(11111,99999).'.'.$e;
		Input::file('file')->move($d, $f);
		}
		$m->file = $f;
		$m->userid = $request->input('userid');
		$m->save();
		//$pid=$request->input('purchaseid');
		//$p = new Purchase();
		//$p = Purchase::find($pid);
        //$p->status = 1;
        //$p->save();
		return redirect('companyprofile');
	}
	
	
	
}
