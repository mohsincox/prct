<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Item extends Model {

	protected $table = 'items';

	public static function joining()
	{
		return DB::table('items')
		 ->join('itemssubgroup', 'items.itemssubgroupid', '=', 'itemssubgroup.id')
		 ->join('measurementunit', 'items.mesid', '=', 'measurementunit.id')
		 ->select('items.id as id','itemssubgroup.name as subname','items.name as name')
		 ->get();
	}
	public static function get_measurement_unit($item_id)
	{
		return DB::table('items')->where('items.id', $item_id)
		 ->join('measurementunit', 'items.mesid', '=', 'measurementunit.id')
		 ->select('items.*','measurementunit.name as measurement_unit_name')
		 ->first();
	}

	public static function get_product_slno($item_id)
	{
		return DB::table('items')->where('items.id', $item_id)
		 ->join('factioyitems', 'items.id', '=', 'factioyitems.itemsid')
		 ->whereNull('factioyitems.salesid')
		 ->where('factioyitems.sale_product', 0)
		 ->select('items.*','factioyitems.id as f_id', 'factioyitems.slno')
		 ->get();
	}
}