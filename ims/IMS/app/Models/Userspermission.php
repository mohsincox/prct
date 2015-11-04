<?php namespace App\Models;


use Auth;
use Illuminate\Database\Eloquent\Model;
use DB;


class Userspermission extends Model {

	
	protected $table = 'userspermission';

	public static function get_users_permission($routes) {
		return DB::table('submenus')
			->where('link', $routes)
			->join('userspermission', 'submenus.id', '=', 'userspermission.submenuid')
			->where('userspermission.userid', Auth::id())
			->select('userspermission.status')
			->first();
	}

}

