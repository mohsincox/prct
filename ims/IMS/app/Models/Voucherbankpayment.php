<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class Voucherbankpayment extends Model {

	protected $table = 'voucherbankpayment';
	
	public static function joinvbp()
	{
		return DB::table('voucherbankpayment')
			->join('voucher', 'voucherbankpayment.vid', '=', 'voucher.id')
			->select('voucherbankpayment.id', 'voucherbankpayment.baccid', 'voucherbankpayment.sid', 'voucherbankpayment.checkno', 'voucher.id as vid', 'voucher.vnno', 'voucher.vdate', 'voucher.amount', 'voucher.type')
			->get();
	}

}