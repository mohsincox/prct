<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Itemsgroup;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

class ItemsgroupController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('itemgroup');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{	
		$c=Itemsgroup::get();
		return view('itemgroup')->with('itemsgroup',$c);

	}
	public function addnew()
	{
		
		return view('createitemgroup');
	}

	public function register(Request $request)
	{
		
		
		$u = new Itemsgroup();
		$u->id = $request->input('id');
		$u->name = $request->input('name');
		$u->userid = $request->input('userid');
		$u->save();
		echo 'successfully Inserted.';
		return redirect('itemgroup');
	}
		public function edititemgroup(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			
			$id=$request->input('id');
			$b=Itemsgroup::find($id);		
			$b->name = $request->input('name');			
			$b->save();
			return Redirect('itemgroup');
		}
		$data['itemsgroup']=Itemsgroup::find($id);
		return view('edititemgroup',$data);
		
	}
	public function delete(Request $request,$id)
	{		
		$b=Itemsgroup::find($id);		
		$b->name = $request->input('name');			
		$b->delete();
		
		return Redirect('itemgroup');
	}

}
