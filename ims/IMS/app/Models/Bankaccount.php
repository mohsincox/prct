<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Bankaccount extends Model{
	
    protected $table = 'bankaccount';	
	
	public static function joining()
	{
		return DB::table('bankaccount')
			->join('bankinfo', 'bankaccount.bankid', '=', 'bankinfo.id')

			->select('bankaccount.id', 'bankaccount.code', 'bankaccount.name', 'bankinfo.id as bankid', 'bankinfo.name as bankname', 'bankaccount.branchname','bankaccount.openbalance','bankaccount.coastatus')
			->get();
	}
}
	
//DB::table('users')
            //->join('contacts', 'users.id', '=', 'contacts.user_id')
            //->join('orders', 'users.id', '=', 'orders.user_id')
            //->select('users.id', 'contacts.phone', 'orders.price')
            //->get();