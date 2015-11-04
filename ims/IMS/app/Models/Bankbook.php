<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Bankbook extends Model{
	
    protected $table = 'bankbook';	
	
	public static function joining()
	{
		return DB::table('bankbook')
            ->join('voucher', 'bankbook.vid', '=', 'voucher.id')
         
            ->select('bankbook.id', 'voucher.vnno', 'bankbook.baccid', 'bankbook.sid', 'bankbook.cid', 'bankbook.checkno')
            ->get();
	}
	
}