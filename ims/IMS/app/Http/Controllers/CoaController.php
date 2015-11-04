<?php namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Coa;
use App\Models\Coatype;
use App\Models\Increasetype;
use App\Models\Taxrate;
use App\Models\Bankaccount;
use App\Models\Customer;
use App\Models\Supplier;
use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use Input;


class CoaController  extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');
		$permission = \App\Http\Controllers\Common\CommonController::check_permission('coa');
		if($permission == 0){
			echo 'This url is not found.';die();
			return redirect('/home');
		}
	}
	
	public function index()
	{
		$c=Coa::joining();
		//print_r($c);
		return view('coa')->with('coa',$c);
	}
	public function addnew()
	{
		$coatype=Coatype::get();
		$increasetype=Increasetype::get();
		$taxrate=Taxrate::get();
		return view('createcoa', compact('coatype', 'increasetype', 'taxrate'));
	}

	public function register(Request $request)
	{
		$v = Validator::make($request->all(), [
        'name' => 'required|unique:coa',
        
    ]);

    if ($v->fails())
    {
		
        $coatype=Coatype::get();
		$increasetype=Increasetype::get();
		$taxrate=Taxrate::get();
		return view('createcoa', compact('coatype', 'increasetype', 'taxrate'))->withErrors($v->errors());
    }
else{
		
		$c = new Coa();
		$c->id = $request->input('id');
		$c->code = $request->input('code');
		$c->name = $request->input('name');
		$c->description = $request->input('description');
		$c->openbalance = $request->input('openbalance');
		$c->increasetypeid = $request->input('increasetypeid');
		$c->taxrateid = $request->input('taxrateid');
		$c->coatypeid = $request->input('coatypeid');
		$c->userid = $request->input('userid');
		$c->save();
		return redirect('coa');
	}}
	public function registerbank(Request $request)
	{
		$c = new Coa();
		$c->id = $request->input('id');
		$c->code = $request->input('code');
		$name = explode("+", $request->input('name'));
		$c->name = $name[1];
		$c->description = $request->input('description');
		
		$c->increasetypeid = $request->input('increasetypeid');
		$c->taxrateid = $request->input('taxrateid');
		$c->coatypeid = $request->input('coatypeid');
		$c->userid = $request->input('userid');
		$c->save();
		$pid=$request->input('code');
	    Bankaccount::where('code',$pid)->update(['coastatus' => 1]);
		return redirect('coa');
	}
		public function edit(Request $request,$id)
	{
		if($request->method()=='POST')
		{
			$id=$request->input('id');
			$c=Coa::find($id);		
			$c->code = $request->input('code');
			$c->name = $request->input('name');
			$c->description = $request->input('description');
			$c->openbalance = $request->input('openbalance');
			$c->increasetypeid = $request->input('increasetypeid');
			$c->taxrateid = $request->input('taxrateid');
			$c->coatypeid = $request->input('coatypeid');
			$c->save();
			return Redirect('coa');
		}
		$data['coa']=Coa::find($id);
		$ct=Coatype::get();
		$it=Increasetype::get();
		$tr=Taxrate::get();
		return view('editcoa',$data)->with('coatype',$ct)->with('increasetype',$it)->with('taxrate',$tr);
		
	}
	public function delete(Request $request,$id)
	{		
		$c=Coa::find($id);				
		$c->delete();
		return Redirect('coa');
	}
	public function addnewbac()
	{
	    $bankaccount=Bankaccount::joining();
		$coatype=Coatype::get();
		$increasetype=Increasetype::get();
		$taxrate=Taxrate::get();
		return view('createbankaccountcoa', compact('bankaccount','coatype','increasetype','taxrate'));
		
	}

	public function get_bankaccount_code(Request $request) {
		if($request->ajax()){
			$bankaccount_id = $request->input('bankaccount_id');
			$bankaccount_info = Bankaccount::find($bankaccount_id);
			return response()->json($bankaccount_info);
		}
	}

	public function addnewcc()
	{
		$customers=Customer::get();
		$coatype=Coatype::get();
		$increasetype=Increasetype::get();
		$taxrate=Taxrate::get();
		return view('createcustomerscoa', compact('customers','coatype','increasetype','taxrate'));
		
	}

	public function get_customer_code(Request $request) {
		if($request->ajax()){
			$customer_id = $request->input('customer_id');
			$customer_info = Customer::find($customer_id);
			return response()->json($customer_info);
		}
	}

	public function addnewcs()
	{
		$suppliers=Supplier::get();
		$coatype=Coatype::get();
		$increasetype=Increasetype::get();
		$taxrate=Taxrate::get();
		return view('createsupplierscoa', compact('suppliers','coatype','increasetype','taxrate'));
		//return view('createsupplierscoa');
	}

	public function get_supplier_code(Request $request) {
		if($request->ajax()){
			$supplier_id = $request->input('supplier_id');
			$supplier_info = Supplier::find($supplier_id);
			return response()->json($supplier_info);
		}
	}

	public function registercc(Request $request)
	{
		$c = new Coa();
		$c->id = $request->input('id');
		$c->code = $request->input('code');
		$name = explode("+", $request->input('name'));
		$c->name = $name[1];
		$c->description = $request->input('description');
		
		$c->increasetypeid = $request->input('increasetypeid');
		$c->taxrateid = $request->input('taxrateid');
		$c->coatypeid = $request->input('coatypeid');
		$c->userid = $request->input('userid');
		$c->save();
		$pid=$request->input('code');
	    Customer::where('code',$pid)->update(['coastatus' => 1]);
		
		
		//$cn=$request->input('name');
	    //Customer::where('name',$cn)->update(['customerstatus' => 1]);
		return redirect('coa');
	}
	public function registercs(Request $request)
	{
		$c = new Coa();
		$c->id = $request->input('id');
		$c->code = $request->input('code');
		$name = explode("+", $request->input('name'));
		$c->name = $name[1];
		$c->description = $request->input('description');
		$c->increasetypeid = $request->input('increasetypeid');
		$c->taxrateid = $request->input('taxrateid');
		$c->coatypeid = $request->input('coatypeid');
		$c->userid = $request->input('userid');
		$c->save();
		$pid=$request->input('code');
	    Supplier::where('code',$pid)->update(['coastatus' => 1]);
		//$sn=$request->input('name');
	    //Supplier::where('name',$sn)->update(['coastatus' => 1]);
		return redirect('coa');
	}
	public function addnewcoatype()
	{
		return view('createcoatype');
		//"BalancesheetController";
	}
	public function registercoatype(Request $request)
	{
		$rules = ['name' => 'required|unique:coatype',];

    $validator = Validator::make(Input::all(), $rules);

    if ($validator->fails())
    {
        return redirect('coa/coatype/addnew')->withErrors($validator);
    }
	else{	
		$u = new Coatype();
		$u->id = $request->input('id');
		$u->name = $request->input('name');
		$u->userid = $request->input('userid');
		$u->save();
		//echo 'successfully Inserted.';
		return redirect('coa/addnew');
		}
	}
}
