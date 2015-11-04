<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class Voucherpayment extends Model {

	protected $table = 'voucherpayment';

	public static function joinvp()
	{
		return DB::table('voucherpayment')
			->join('voucher', 'voucherpayment.vid', '=', 'voucher.id')
			->select('voucherpayment.id', 'voucherpayment.baccid', 'voucherpayment.sid', 'voucher.id as vid', 'voucher.vnno', 'voucher.vdate', 'voucher.amount', 'voucher.type')
			->get();
	}
}