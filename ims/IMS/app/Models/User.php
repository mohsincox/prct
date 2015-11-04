<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model {

	
	protected $table = 'users';
	
	public static function joining(){
		return DB::table('users')
            ->join('usergroups', 'users.usergroupid', '=', 'usergroups.id')
            ->select('users.id', 'users.name as username', 'users.email', 'usergroups.name as usergroupname')
            ->get();
	}
	

}

