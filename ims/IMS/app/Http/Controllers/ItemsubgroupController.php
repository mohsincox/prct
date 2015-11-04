<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Itemsubgroup;
use App\Models\Itemsgroup;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Closure;

class ItemsubgroupController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('itemsubgroup');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$c=Itemsubgroup::joining();
		return view('itemsubgroup')->with('itemsubgroup',$c);

	}
	public function addnew()
	{
		
		return view('createitemsubgroup');
	}

	public function register(Request $request)
	{
	    $u = new Itemsubgroup();
		$u->itemgroupid = $request->input('itemgroupid');
		$u->name = $request->input('name');
		$u->userid = $request->input('userid');
		$u->save();
		return redirect('itemsubgroup');
		
	}
		public function edititemsubgroup(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$b=Itemsubgroup::find($id);
            $b->itemgroupid = $request->input('itemgroupid');			
			$b->name = $request->input('name');	
			$b->userid = $request->input('userid');			
			$b->save();
			return Redirect('itemsubgroup');
		}
		$data['itemsubgroup']=Itemsubgroup::find($id);
		return view('edititemsubgroup',$data);
		
	}
	public function delete(Request $request,$id)
	{		
			$b=Itemsubgroup::find($id);
			//print_r($b);
            $b->itemgroupid = $request->input('itemgroupid');			
			$b->name = $request->input('name');			
			$b->delete();
			return Redirect('itemsubgroup');
	}

}
