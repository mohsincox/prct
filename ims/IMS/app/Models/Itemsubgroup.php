<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Itemsubgroup extends Model {

	protected $table = 'itemssubgroup';
	
	public static function joining()
	{
		return DB::table('itemssubgroup')
		 ->join('itemsgroup', 'itemssubgroup.itemgroupid', '=', 'itemsgroup.id')
		 ->select('itemssubgroup.id as id','itemssubgroup.itemgroupid as itemgroupid','itemssubgroup.name as subname','itemsgroup.name as name')
		 ->get();
	}

}