<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Measurementunit;
use App\Models\Measurementgroup;
use App\Http\Requests;
use Illuminate\Http\Request;

class MeasurementunitController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('measurementunit');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$m=Measurementunit::joining();
		return view('measurementunit')->with('measurementunit',$m);
	}
	public function addnew()
	{
		return view('createmeasurementunit');
	}

	public function register(Request $request)
	{
		$m = new Measurementunit();
		$m->id = $request->input('id');
		$m->mgid = $request->input('mgid');
		$m->name = $request->input('name');
		$m->userid = $request->input('userid');
		$m->save();
		return redirect('measurementunit');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$m=Measurementunit::find($id);		
			$m->name = $request->input('name');	
			$m->mgid = $request->input('mgid');	
			$m->save();
			
			return Redirect('measurementunit');
		}
		$data['measurementunit']=Measurementunit::find($id);
		$mg=Measurementgroup::get();
		return view('editmeasurementunit',$data)->with('mg',$mg);;
		
	}
	public function delete(Request $request,$id)
	{		
		$m=Measurementunit::find($id);				
		$m->delete();
		return Redirect('measurementunit');
	}

}
