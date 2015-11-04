<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class Combo extends Model {
    public static function callcombo($spname){
		$str="call  ".$spname."(";
        $strfinal=');'; 		
		$strlast=$str.$strfinal;
		$result= DB::select(DB::raw("$strlast"));
		return $result;
	}	
}
