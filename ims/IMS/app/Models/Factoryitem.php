<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Factoryitem extends Model {

	protected $table = 'factioyitems';
	public static function joining()
	{
		return DB::table('factioyitems')
		 ->join('items', 'factioyitems.itemsid', '=', 'items.id')
		 ->join('itemssubgroup', 'items.itemssubgroupid', '=', 'itemssubgroup.id')
		 ->join('measurementunit', 'items.mesid', '=', 'measurementunit.id')
		 ->select(DB::raw('factioyitems.itemsid as itemid,itemssubgroup.name as cname,items.name as subname, count(factioyitems.itemsid) as quantity, measurementunit.name as nname'))
		 ->groupBy('factioyitems.itemsid')
		 ->get();
	}
	public static function join($id)
	{
		return DB::table('factioyitems')
            ->join('items', 'factioyitems.itemsid', '=', 'items.id')
			->where('factioyitems.itemsid',$id)
            //->join('orders', 'users.id', '=', 'orders.user_id')
            ->select('factioyitems.id', 'factioyitems.itemsid', 'items.name as itemsname', 'factioyitems.slno','factioyitems.created_at', 'factioyitems.status', 'factioyitems.feedback')
            ->get();
	}
    
	public static function countsales($id)
	{
		 return DB::table('factioyitems')
				->select(DB::raw('count(factioyitems.itemsid) as count'))
				->where('itemsid',$id)
				->where('sale_product',0)
				->where('status',1)
				->first(); 
	}
	
	public static function sales($id)
	{
		return DB::table('factioyitems')
		    ->join('items', 'factioyitems.itemsid', '=', 'items.id') 
			->join('sales', 'factioyitems.salesid', '=', 'sales.id') 
		    ->select('factioyitems.id','items.name','factioyitems.slno','factioyitems.created_at','sales.name as ivnno','factioyitems.status','factioyitems.feedback')
			->where('factioyitems.itemsid',$id)
			->where('factioyitems.sale_product',1)
			->get();
	}
	public static function innosales($inno)
	{
		return DB::table('factioyitems')
		    ->join('items', 'factioyitems.itemsid', '=', 'items.id') 
			->join('sales', 'factioyitems.salesid', '=', 'sales.id') 
		    ->select('factioyitems.id','items.name','factioyitems.slno','factioyitems.created_at','sales.name as ivnno','factioyitems.status','factioyitems.feedback')
			->where('sales.name',$inno)
			->where('sales.status',1)
			->where('factioyitems.sale_product',1)
			->get();
	}
	
	public static function remain($id)
	{
		return DB::table('factioyitems')
		    ->select('id','slno','created_at')
			->where('itemsid',$id)
			->where('sale_product',0)
			->where('status',1)
			->get();
	}
	public static function damage($id)
	{
		return DB::table('factioyitems')
		    ->join('items', 'factioyitems.itemsid', '=', 'items.id') 
		    ->select('items.name','factioyitems.slno','factioyitems.created_at','factioyitems.updated_at','factioyitems.feedback')
			->where('factioyitems.itemsid',$id)
			->where('status',0)
			->get();
	}
}