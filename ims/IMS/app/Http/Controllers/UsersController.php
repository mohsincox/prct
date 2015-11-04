<?php namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Hash;

class UsersController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('users');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	
	public function index()
	{
		$users = User::joining();
		return view('users')->with('users', $users);
		//return "mohsin";
	}
    public function addnew(Request $request)
	{
		if($request->method() == 'POST'){
			$rules = [
						'name' => 'required',
						'usergroupid' => 'required',
			    		'email' => 'required|email|unique:users',
			    		'password' => 'required|min:6',
			    		'confirm_password' => 'required|same:password'
					];

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
       			return redirect('users/addnew')->withErrors($validator)->withInput();
    		} else {
				$user = new User();
				$user->name = $request->input('name');
				$user->email = $request->input('email');
				$user->password = bcrypt($request->input('password'));
				$user->usergroupid = $request->input('usergroupid');
				$user->save();
				return redirect('users');
			}
		}	
		return view('createusers');
	}
	 public function changepass(Request $request)
	{
		if($request->method() == "POST"){
			Validator::extend('passcheck', function($attribute, $value, $parameters) {
			    return Hash::check($value, Auth::user()->password); 
			});

			$messages = array(
			    'passcheck' => 'Your old password was incorrect',
			);

			$rules = [
						'old_password'  => 'passcheck',
			    		'new_password' => 'required|min:6',
			    		'confirm_password' => 'required|same:new_password'
					];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
       			return redirect('users/changepass')->withErrors($validator);
    		} else {
				$user_id = Auth::id();

				$user = User::find($user_id);
				$user->password = bcrypt($request->input('new_password'));
				$user->save();
				return redirect('users/changepass');
			}
		}
		return view('changepass');
	}
}
