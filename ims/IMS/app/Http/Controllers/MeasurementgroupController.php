<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Measurementgroup;
use App\Http\Requests;
use Illuminate\Http\Request;

class MeasurementgroupController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('measurementgroup');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$m=Measurementgroup::get();
		return view('measurementgroup')->with('measurementgroup',$m);
	}
	public function addnew()
	{
		return view('createmeasurementgroup');
	}

	public function register(Request $request)
	{
		$m = new Measurementgroup();
		$m->id = $request->input('id');
		$m->name = $request->input('name');
		$m->userid = $request->input('userid');
		$m->save();
		return redirect('measurementgroup');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$m=Measurementgroup::find($id);		
			$m->name = $request->input('name');	
			$m->save();
			return Redirect('measurementgroup');
		}
		$data['measurementgroup']=Measurementgroup::find($id);
		return view('editmeasurementgroup',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$m=Measurementgroup::find($id);				
		$m->delete();
		return Redirect('measurementgroup');
	}

}
