<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Usersgroup;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
class UsersgroupController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('usersgroup');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$c=Usersgroup::get();
		return view('usersgroup')->with('usersgroup',$c);

	}
	public function addnew()
	{
		
		return view('createusersgroup');
	}

	public function register(Request $request)
	{
		
		
		$u = new Usersgroup();
		$u->id = $request->input('id');
		$u->name = $request->input('name');
		$u->userid = $request->input('userid');
		$u->save();
		echo 'successfully Inserted.';
		return redirect('usersgroup');
	}
		public function editusersgroup(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			
			$id=$request->input('id');
			$b=Usersgroup::find($id);		
			$b->name = $request->input('name');			
			$b->save();
			return Redirect('usersgroup');
		}
		$data['usersgroup']=Usersgroup::find($id);
		return view('editusersgroup',$data);
		
	}
	public function delete(Request $request,$id)
	{		
			$b=Usersgroup::find($id);		
			$b->name = $request->input('name');			
			$b->delete();
			return Redirect('usersgroup');
		
		
		
	}

}
