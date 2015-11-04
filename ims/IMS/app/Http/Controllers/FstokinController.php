<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Common\CommonController;
use App\Models\Info;
use App\Models\Combo;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;


class FstokinController   extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('fstokin');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	
	public function index()
	{
		
		return view('factoryinout');
		

	}
	public function addnew()
	{
		
		//return view('createphysicalsale');
	}

     public function today(Request $request)
	{
	       $date=Combo::callcombo('currentdate');
			//print_r($date);
			foreach($date as $d){
				$curdate=$d->curdate;
			}
			$fromdate=CommonController::date_format($curdate);
            $todate=CommonController::date_format($curdate);
			//$fromdate=date("Y-m-d");
           // $todate=date("Y-m-d");
            $var = array($fromdate,$todate);
            $spname="rptfactorypurchase";
            $value=Info::callinfo($var,$spname);
			//print_r($value);
			//echo '<br>';
			$spname="rptfactioyitems";
            $value1=Info::callinfo($var,$spname);
			//print_r($value1);
			//echo '<br>';
			
			return view('fstockview', compact('value','value1','fromdate','todate'));
			
            
	}
	
	 public function fromtoday(Request $request)
	{
		  
		    $date=$request->input('submit');
	        $branchid=$request->input('branchid');
	        $fromdate=CommonController::date_format($request->input('fromdate'));
			$todate=CommonController::date_format($request->input('todate'));
			 if($date=='today'){
				$date=Combo::callcombo('currentdate');
				//print_r($date);
				foreach($date as $d){
					$curdate=$d->curdate;
				}
				$fromdate=CommonController::date_format($curdate);
				$todate=CommonController::date_format($curdate);
				$fromdate=date("Y-m-d");
				$todate=date("Y-m-d"); 
				$factioyitems=DB::table('factioyitems')
				              ->join('users', 'users.id', '=', 'factioyitems.userid')
							  ->join('items', 'items.id', '=', 'factioyitems.itemsid') 
			                  ->whereBetween('factioyitems.created_at', array($fromdate, $todate))
							  ->where('factioyitems.userid', $branchid)
							  ->select('users.name as branchname', 'items.name as itemsname','factioyitems.slno')
			                  ->get();		  
				return view('factorystockview', compact('value','value1','fromdate','todate','factioyitems'));
				}else if($date=='fromdate'){
					 $fromdate=CommonController::date_format($request->input('fromdate'));
					 $todate=CommonController::date_format($request->input('todate'));
					 $factioyitems=DB::table('factioyitems')
				              ->join('users', 'users.id', '=', 'factioyitems.userid')
							  ->join('items', 'items.id', '=', 'factioyitems.itemsid') 
			                  ->whereBetween('factioyitems.created_at', array($fromdate, $todate))
							  ->where('factioyitems.userid', $branchid)
							  ->select('users.name as branchname', 'items.name as itemsname','factioyitems.slno')
			                  ->get();
				return view('factorystockview', compact('value','value1','fromdate','todate','factioyitems'));
				 }            
	}

}
