<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Billspay;
use App\Models\Purchase;
use App\Http\Requests;
use Illuminate\Http\Request;
use PDF;
use Input;

class AdminController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		return view('createadmin');
	}

	
	
}
