<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class Voucherbankreceive extends Model {

	protected $table = 'voucherbankreceive';

	public static function joinvbr()
	{
		return DB::table('voucherbankreceive')
			->join('voucher', 'voucherbankreceive.vid', '=', 'voucher.id')
			->select('voucherbankreceive.id', 'voucherbankreceive.baccid', 'voucherbankreceive.cid', 'voucherbankreceive.checkno', 'voucher.id as vid', 'voucher.vnno', 'voucher.vdate', 'voucher.amount', 'voucher.type')
			->get();
	}
}