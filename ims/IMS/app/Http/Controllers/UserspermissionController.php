<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userspermission;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

class UserspermissionController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('userspermission');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function index()
	{
		$users = User::get();
		return view('userspermission')->with('users', $users);
		
	}
	public function privilege(Request $request,$id)
	{
		return view('privilege')->with('id', $id);
		
	}
	public function register(Request $request)
	{	
		$id=$request->input('id');
		$stat=$request->input('stat');
		$submunuid=$request->input('submunuid');
		//$status=$request->input('status');
		$users = DB::table('userspermission')->where('userid',$id)->select('id')->get();
		if($users==NULL){
			 foreach( $submunuid as $key => $n ) {	
			    $l = new Userspermission();		
				$l->userid = $id;
				$l->submenuid = $n;
				$l->status = $request->input($n);
				$l->save(); 
			 }
		}else{
			    foreach( $submunuid as $key => $n ) {	
				Userspermission::where('userid',$id)->where('submenuid',$n)->update(['status' => $request->input($n)]);
				
				$new_permission = Userspermission::where('userid', $id)
								->where('submenuid',$n)
								->first();
					if(empty($new_permission)) {
						$l = new Userspermission();		
						$l->userid = $id;
						$l->submenuid = $n;
						$l->status = $request->input($n);//$status[$key];
						$l->save(); 
					}
				}
		}
		return Redirect('userspermission');
	}
}
