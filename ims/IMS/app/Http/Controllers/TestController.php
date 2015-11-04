<?php namespace App\Http\Controllers;
use App\Models\Test;
use App\Models\Ttest;
class TestController extends Controller {

	
	
	public function index()
	{
		//return view('test');
		$t=Test::get();
		$tt=Ttest::get();
		//return view('test')->with('a', $t);
		return view('test', compact('t', 'tt'));
		//remove raw code  
		
		//return view('t'); //proc
		//return view('min');
	}

}
