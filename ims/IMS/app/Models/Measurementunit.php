<?php namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;


class Measurementunit extends Model {

	protected $table = 'measurementunit';

	public static function joining()
	{
		return DB::table('measurementunit')
		 ->join('measurementgroup', 'measurementunit.mgid', '=', 'measurementgroup.id')
		 ->select('measurementunit.id as id','measurementunit.mgid as mgid','measurementunit.name as uname','measurementgroup.name as gname')
		 ->get();
	}
}

