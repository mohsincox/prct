<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Employeeinfo extends Model{
	
    protected $table = 'employeeinfo';
   public static function joining()
	{
		return DB::table('employeeinfo')
		 ->join('users', 'employeeinfo.uid', '=', 'users.id')
		 ->select('employeeinfo.id as id','employeeinfo.name as name','employeeinfo.designation as designation','employeeinfo.joindate as joindate','employeeinfo.peraddress as peraddress','employeeinfo.preaddress as preaddress','employeeinfo.salary as salary','employeeinfo.employeetype as employeetype','employeeinfo.file as file','employeeinfo.uid as uid','users.name as subname')
		 ->get();
	}
	
}	
	
