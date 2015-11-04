<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;

class CoatypeController  extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('customers');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}

	public function addnew()
	{
		return view('createcoatype');
		//"BalancesheetController";
	}
	

}
