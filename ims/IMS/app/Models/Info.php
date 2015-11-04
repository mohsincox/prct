<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class Info extends Model {
	public static function callinfo($value,$spname){
		$cn=count($value);
		$str="call  ".$spname."(";
		for($i=0;$i<$cn;$i++)
		{
			$strarray="'$value[$i]',";
			$str=$str.$strarray;
		}
		$len=strlen($str);
		$strtext= substr($str,0,$len-1);
        $strfinal=');'; 		
		$strlast=$strtext.$strfinal;
		$result= DB::select(DB::raw("$strlast"));	
		return $result;
	}
        
}