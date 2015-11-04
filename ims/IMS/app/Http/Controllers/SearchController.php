<?php namespace App\Http\Controllers;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Voucher;
use App\Models\Companyprofile;
use App\Models\Physicalsale;
use App\Models\Combo;
use App\Models\Info;
use App\Models\Sale;
use App\Models\Bankaccount;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Factoryitem;
use PDF;
use DB;
class SearchController  extends Controller {	
	public function index()
	{
		 $profile=Companyprofile::get();
		return view('searchreturn')->with('profile',$profile);
	}
    
	public function searchreturn(Request $request)
	{
		$profile=Companyprofile::get();
		if($request->input('slno')!=NUll){
		    $factioyitems = DB::table('factioyitems')->where('slno',$request->input('slno'))->first();
			$factioyitems->salesid;
			$fiall = DB::table('factioyitems')			            
						 ->join('sales', 'sales.id', '=', 'factioyitems.salesid')
						 ->join('customers', 'customers.id', '=', 'sales.customerid')
						 ->join('items', 'items.id', '=', 'factioyitems.itemsid')
						 ->select('customers.name as cname', 'factioyitems.slno', 'customers.preaddress', 'customers.phone', 'sales.name as salesname', 'sales.created_at as salesdate', 'items.name as itemsname')
						 ->where('salesid',$factioyitems->salesid)
						 ->get();
	    //$var = array($pslno);
		//$spname="searchreturn";
		//$value=Info::callinfo($var,$spname);
		// print_r($fiall);
		
		//$profile=Companyprofile::get();
		return view('searchreturn2', compact('profile', 'fiall'));
		}
		else{
		$profile=Companyprofile::get();
		return view('searchreturn')->with('profile',$profile);
		}
    }
	
}
