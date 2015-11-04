<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Pettycash extends Model{
	
    protected $table = 'pettycash';	
	public static function joining()
	{
		return DB::table('pettycash')
			->join('coa', 'pettycash.particular', '=', 'coa.id')

			->select('pettycash.id', 'pettycash.particular', 'pettycash.amount', 'coa.name as particular', 'pettycash.description')
			->get();
	}
	
}