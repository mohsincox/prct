<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Coa extends Model{
	
    protected $table = 'coa';	
	
	public static function joining()
	{
		return DB::table('coa')
			->leftJoin('coatype', 'coa.coatypeid', '=', 'coatype.id')
			->leftJoin('increasetype', 'coa.increasetypeid', '=', 'increasetype.id')
			->leftJoin('taxrate', 'coa.taxrateid', '=', 'taxrate.id')
			->select('coa.id', 'coa.code', 'coa.name', 'coa.description','coa.openbalance', 'coatype.id as coatypeid', 'coatype.name as coatypename', 'increasetype.id as increasetypeid', 'increasetype.name as increasetypename', 'taxrate.id as taxrateid', 'taxrate.name as taxratename', 'coa.userid')
			->get();
	}
	public static function contrajoining()
	{
		return DB::table('coa')
			->leftJoin('coatype', 'coa.coatypeid', '=', 'coatype.id')
			->leftJoin('increasetype', 'coa.increasetypeid', '=', 'increasetype.id')
			->leftJoin('taxrate', 'coa.taxrateid', '=', 'taxrate.id')
			->select('coa.id', 'coa.code', 'coa.name', 'coa.description','coa.openbalance', 'coatype.id as coatypeid', 'coatype.name as coatypename', 'increasetype.id as increasetypeid', 'increasetype.name as increasetypename', 'taxrate.id as taxrateid', 'taxrate.name as taxratename', 'coa.userid')
			->get();
	}
	
}