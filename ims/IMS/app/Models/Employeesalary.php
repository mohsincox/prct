<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Employeesalary extends Model{
	
    protected $table = 'employeesal';	
	public static function joining()
	{
		return DB::table('employeesal')
			->join('employeeinfo', 'employeesal.eid', '=', 'employeeinfo.id')
			->join('particulars', 'employeesal.pid', '=', 'particulars.id')
			->select('employeesal.id','employeesal.amount','particulars.name as pname', 'employeeinfo.name as ename', 'employeesal.description', 'employeesal.vdate')
			->get();
	}

	public static function get_today_all_employee_salary($current_date){
		return DB::table('employeesal')
			->where('employeesal.created_at', '=', $current_date)
			->join('employeeinfo', 'employeesal.eid', '=', 'employeeinfo.id')
			->join('particulars', 'employeesal.pid', '=', 'particulars.id')
			->select('employeesal.id','employeesal.amount','particulars.name as pname', 'employeeinfo.name as ename', 'employeesal.description', 'employeesal.vdate')
			->get();
	}

	public static function get_today_employee_salary($current_date, $employee_id){
		return DB::table('employeesal')
			->where('employeesal.eid', $employee_id)
			->where('employeesal.created_at', '=', $current_date)
			->join('employeeinfo', 'employeesal.eid', '=', 'employeeinfo.id')
			->join('particulars', 'employeesal.pid', '=', 'particulars.id')
			->select('employeesal.id','employeesal.amount','particulars.name as pname', 'employeeinfo.name as ename', 'employeesal.description', 'employeesal.vdate')
			->get();
	}

	public static function get_from_to_all_employee_salary($fromdate, $todate){
		return DB::table('employeesal')
			->whereBetween('employeesal.created_at', array($fromdate, $todate))
			->join('employeeinfo', 'employeesal.eid', '=', 'employeeinfo.id')
			->join('particulars', 'employeesal.pid', '=', 'particulars.id')
			->select('employeesal.id','employeesal.amount','particulars.name as pname', 'employeeinfo.name as ename', 'employeesal.description', 'employeesal.vdate')
			->get();
	}

	public static function get_from_to_employee_salary($fromdate, $todate, $employee_id){
		return DB::table('employeesal')
			->where('employeesal.eid', $employee_id)
			->whereBetween('employeesal.created_at', array($fromdate, $todate))
			->join('employeeinfo', 'employeesal.eid', '=', 'employeeinfo.id')
			->join('particulars', 'employeesal.pid', '=', 'particulars.id')
			->select('employeesal.id','employeesal.amount','particulars.name as pname', 'employeeinfo.name as ename', 'employeesal.description', 'employeesal.vdate')
			->get();
	}
	
}