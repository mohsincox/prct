<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Illuminate\Http\Request;


class ReportsaleController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		
		return view('reportsale');
		

	}
	public function addnew()
	{
		
		//return view('createphysicalsale');
	}

 

}
