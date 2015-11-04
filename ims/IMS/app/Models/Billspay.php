<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class Billspay extends Model {

	protected $table = 'billspay';

	public static function joining()
	{
		return DB::table('billspay')
		 ->join('purchase', 'billspay.purchaseid', '=', 'purchase.id')
		 ->select('billspay.id as id','purchase.name as pname','billspay.purchasedate as purchasedate','billspay.amount as amount','billspay.file as file')
		 ->get();
	}
	/*DB::table('users')
            ->join('contacts', 'users.id', '=', 'contacts.user_id')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('users.id', 'contacts.phone', 'orders.price')
            ->get();*/
}

