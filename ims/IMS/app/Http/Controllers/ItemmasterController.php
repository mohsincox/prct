<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
class ItemmasterController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('itemmaster');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$c=Item::joining();
		return view('items')->with('items',$c);

	}
	public function addnew()
	{
		
		return view('createitemmaster');
	}

	public function register(Request $request)
	{
		
		
		$u = new Item();
		$u->itemssubgroupid = $request->input('itemssubgroupid');
		$u->code = $request->input('code');
		$u->name = $request->input('name');
		$u->quantity = $request->input('quantity');
		$u->mesid = $request->input('mesid');
		$u->price = $request->input('price');
		if($request->input('sstatus')==NULL)
		$u->sstatus =0;
		else
		$u->sstatus = $request->input('sstatus');
		$u->userid = $request->input('userid');
		$u->save();
		//echo 'successfully Inserted.';
		return redirect('itemmaster');
	}
		public function edititemmaster(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			
			$id=$request->input('id');
			$u=Item::find($id);	
			$u->itemssubgroupid = $request->input('itemssubgroupid');			
			$u->code = $request->input('code');
			$u->name = $request->input('name');
			$u->quantity = $request->input('quantity');
			$u->mesid = $request->input('mesid');
			$u->price = $request->input('price');
			if($request->input('sstatus')==NULL)
			$u->sstatus =0;
			else
			$u->sstatus = $request->input('sstatus');
			$u->userid = $request->input('userid');		
			$u->save();
			return Redirect('itemmaster');
		}
		$data['Item']=Item::find($id);
		//print_r($data['Item']);
		return view('edititemmaster',$data);
		
	}
	public function delete(Request $request,$id)
	{		
			$b=Item::find($id);		
			$b->name = $request->input('name');			
			$b->delete();
			return Redirect('itemmaster');
	}

}
