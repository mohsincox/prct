<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Factoryitem;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Itemsubgroup;
use App\Models\Measurementunit;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

class FactoryitemController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('factoryitem');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$c=Factoryitem::joining();
		return view('factoryitem')->with('factoryitem',$c);

	}
	public function addnew()
	{
		$data['item_sub_groups'] = Itemsubgroup::where('itemgroupid', 1)->get();
		return view('createfactoryitem', $data);
	}

	public function get_item_by_category(Request $request) {
		if($request->ajax()){
			$category_id = $request->input('category_id');
			$iteminfo = Item::where('itemssubgroupid', $category_id)->get();
			return response()->json($iteminfo);
		}
	}

	public function register(Request $request)
	{
		$itemsid = $request->input('itemsid');
		$q = $request->input('quantity');
		$userid = $request->input('userid');
		$items = DB::table('items')->where('id',$itemsid)->first();
		if($items==NULL){
			return redirect('factoryitem/addnew');
		}
		if($items->sstatus==1){
			    //slno
				$str=date("y-m-d");
				//echo $str;
				//die();
				 //print_r(explode("-",$str));
				 $arraystr=(explode("-",$str));
				 //echo $arraystr[0].$arraystr[1].$arraystr[2];
				 $arrayyear=$arraystr[0];
				// echo $arrayyear[0].'-'.$arrayyear[1];
				// echo '&#920';
				 //$v=array();
				 //$v[0]='&#920';
				// $yearsecond=$v[0];
				// echo  $yearsecond;
				//Year prefix
				 switch ($arrayyear[0]) {
				 case 0:
					 $yearfirst='&#920';
					 break;
				 case 1:
					 $yearfirst='A';
					 break;
				 case 2:
					 $yearfirst='B';
					 break;
				 case 3:
					 $yearfirst='C';
					 break;
				 case 4:
					 $yearfirst='D';
					 break;
				 case 5:
					 $yearfirst='E';
					 break;
				 case 6:
					 $yearfirst='F';
					 break;
				 case 7:
					 $yearfirst='G';
					 break;
				 case 8:
					 $yearfirst='H';
					 break;
				 case 9:
					 $yearfirst='J';
					 break;			 
				 default:
					 echo "No Data Found";
				}
				
				switch ($arrayyear[1]) {
				 case 0:
					 $yearsecond='&#920';
					 break;
				 case 1:
					 $yearsecond='A';
					 break;
				 case 2:
					 $yearsecond='B';
					 break;
				 case 3:
					 $yearsecond='C';
					 break;
				 case 4:
					 $yearsecond='D';
					 break;
				 case 5:
					 $yearsecond='E';
					 break;
				 case 6:
					 $yearsecond='F';
					 break;
				 case 7:
					 $yearsecond='G';
					 break;
				 case 8:
					 $yearsecond='H';
					 break;
				 case 9:
					 $yearsecond='J';
					 break;			 
				 default:
					 echo "No Data Found";
				}
				
				//Month prefix
				switch ($arraystr[1]) {
				 case 1:
					 $month='A';
					 break;
				 case 2:
					 $month='B';
					 break;
				 case 3:
					 $month='C';
					 break;
				 case 4:
					 $month='D';
					 break;
				 case 5:
					 $month='E';
					 break;
				 case 6:
					 $month='F';
					 break;
				 case 7:
					 $month='G';
					 break;
				 case 8:
					 $month='H';
					 break;
				 case 9:
					 $month='J';
					 break;
				 case 10:
					 $month='K';
					 break;
				 case 11:
					 $month='L';
					 break;	
				 case 12:
					 $month='M';
					 break;			 
				 default:
					 echo "No Data Found";
				}
				
				// Date prefix
				$date=$arraystr[2];
				if($userid==2){
					$branchprefix='B';
				}else{
					$branchprefix='T';
				}
				$prefix=$branchprefix.$yearfirst.$yearsecond.$month.$date;
				$created_at=date("Y-m-d");
				//find ID
		$factioyitems = DB::table('factioyitems')->where('userid',$userid)->where('created_at',$created_at)->orderBy('id', 'desc')->first();
				//echo $factioyitems->no;
				if($factioyitems!=NULL){
					$no=$factioyitems->no+1;
				}else{
					$no=1;
				}
				
				//echo $no;
				//die();
				$slno=$no;
				//database slno genarate
				for($i=0;$i<$q;$i++){
					$u = new Factoryitem();
					$u->itemsid = $itemsid;
					//$u->slno = 'TQ3-'.rand();
					if($slno<10){
						$u->slno=$prefix.'0000'.$slno;
					}else if($slno<100){
						$u->slno=$prefix.'000'.$slno;
					}else if($slno<1000){
						$u->slno=$prefix.'00'.$slno;
					}else if($slno<10000){
						$u->slno=$prefix.'0'.$slno;
					}else if($slno<100000){
						$u->slno=$prefix.''.$slno;
					}
					$u->no = $slno;
					$u->status = 1;
					$u->userid = $userid ;
					$u->save();
					$slno++;
				}
				//slno
			
			   /*
				for($i=0;$i<$q;$i++){
					$u = new Factoryitem();
					$u->itemsid = $itemsid;
					$u->slno = 'TQ3-'.rand();
					$u->status = 1;
					$u->userid = $userid ;
					$u->save();
				}
			    */
		} else {
			    $qnt=$q+$items->quantity;
				DB::table('items')->where('id', $itemsid)->update(['quantity' => $qnt]);   				
		} 
		
		
		return redirect('factoryitem');
	}
		
	public function delete(Request $request,$id)
	{		
			$b=Factoryitem::find($id);		
			$b->name = $request->input('name');			
			$b->delete();
			return Redirect('factoryitem');	
	}
	public function view(Request $request,$id)
	{
		$data['factoryitemview']=Factoryitem::join($id);
		$data['item_id'] = $id;
		return view('factoryitemview', $data);
	}

	public function download_csv(Request $request, $id)
	{
		if($request->ajax()){
			$search_value = $request->input('search_value');
			if($search_value == NULL){
				$download_csv_data = Factoryitem::join($id);
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			} else{
				$download_csv_data = Factoryitem::where('itemsid', $id)
										->where(function($query) use ($search_value) {
					                        $query->where('slno', 'like', '%' . $search_value . '%')
					                        ->orWhere('created_at', 'like', '%' . $search_value . '%');
					                    })
										->get();
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			}
		}
	}

    public function remain(Request $request,$id)
	{
		$c=Factoryitem::remain($id);
		return view('factoryremain')->with('value',$c);
	}

	public function download_csv_remain(Request $request, $id)
	{
		if($request->ajax()){
			$search_value = $request->input('search_value');
			if($search_value == NULL){
				$download_csv_data = Factoryitem::remain($id);
				return response()->json(array('download_csv_data'=>$download_csv_data));
			} else{
				$download_csv_data = Factoryitem::where('itemsid', $id)
								->where(function($query) use ($search_value) {
						            $query->where('slno', 'like', '%' . $search_value . '%')
			                        ->orWhere('created_at', 'like', '%' . $search_value . '%')
			                        ->orWhere('id', 'like', '%' . $search_value . '%');
			                    })
			                    ->where('sale_product',0)
								->where('status',1)
								->get();
				return response()->json(array('download_csv_data'=>$download_csv_data));
			}
		}
	}

	public function sales(Request $request,$id)
	{
			$value=Factoryitem::sales($id);
			$item_id= $id;
			return view('factorysale', compact('value', 'item_id'));
	}

	public function download_csv_sales(Request $request, $id)
	{
		if($request->ajax()){
			$search_value = $request->input('search_value');
			if($search_value == NULL){
				$download_csv_data = Factoryitem::sales($id);
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			} else{
				$download_csv_data = Factoryitem::where('itemsid', $id)
										->where(function($query) use ($search_value) {
					                        $query->where('slno', 'like', '%' . $search_value . '%')
					                        ->orWhere('created_at', 'like', '%' . $search_value . '%');
					                    })
					                    ->where('sale_product',1)
										->get();
				if($download_csv_data->count() > 0){
					foreach ($download_csv_data as $csv_data) {
						$sale = Sale::find($csv_data->salesid);
						$csv_data->invoice_no = $sale->name;
					}
				}			
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			}
		}
	}

	public function damaged(Request $request,$id)
	{
			$value=Factoryitem::damage($id);
			return view('factorydamage')->with('value',$value);
	}

	public function download_csv_damaged(Request $request, $id)
	{
		if($request->ajax()){
			$search_value = $request->input('search_value');
			if($search_value == NULL){
				$download_csv_data = Factoryitem::damage($id);
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			} else{
				$download_csv_data = Factoryitem::where('itemsid', $id)
										->where(function($query) use ($search_value) {
					                        $query->where('slno', 'like', '%' . $search_value . '%')
					                        ->orWhere('created_at', 'like', '%' . $search_value . '%');
					                    })
					                    ->where('status',0)
										->get();
				$item = Item::find($id);
				return response()->json(array('download_csv_data'=>$download_csv_data, 'item' => $item));
			}
		}
	}

	public function save_feedback(Request $request) {
		if($request->ajax()){
			$factory_item_id = $request->input('factory_item_id');
			$status = $request->input('status');
			$feedback = $request->input('feedback');

			$factory_item=Factoryitem::find($factory_item_id);
			if(!empty($factory_item)){
				$factory_item->status = $status;
				$factory_item->feedback = $feedback;
				$factory_item->save();
			}else{
				return response()->json(0);
			}
			return response()->json(1);
		}
	}

	public function remove_feedback(Request $request) {
		if($request->ajax()){
			$factory_item_id = $request->input('factory_item_id');
			$status = $request->input('status');

			$factory_item=Factoryitem::find($factory_item_id);
			if(!empty($factory_item)){
				$factory_item->status = $status;
				$factory_item->feedback = "";
				$factory_item->save();
			}else{
				return response()->json(0);
			}
			return response()->json(1);
		}
	}

}
