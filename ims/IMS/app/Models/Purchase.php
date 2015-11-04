<?php namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

	protected $table = 'purchase';
	
	public static function joining(){
		
		return DB::table('purchase')
            ->join('suppliers', 'purchase.suppliersid', '=', 'suppliers.id')
			->select('purchase.id','purchase.name as pname', 'suppliers.name as sname', 'purchase.suppliersbilldate','purchase.status')
			->where('purchase.cstatus',0)
            ->orderBy('id', 'desc')->take(10)->get();
		//$voucher = Voucher::orderBy('id', 'desc')->take(10)->get();
		//DB::table('users')
            //->join('contacts', 'users.id', '=', 'contacts.user_id')
           // ->join('orders', 'users.id', '=', 'orders.user_id')
           // ->select('users.id', 'contacts.phone', 'orders.price')
           // ->get();
	}

}