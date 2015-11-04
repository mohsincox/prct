<?php
$spname="viewpurchase";
$value=array(14);
$cn=count($value);
//echo $cn;
		$str="call  ".$spname."(";
		for($i=0;$i<$cn;$i++)
		{
			$strarray="'$value[$i]',";
			$str=$str.$strarray;
			//echo $str;echo "&nbsp &nbsp";
		}
		$len=strlen($str);
		echo $len;
		//die();
		$strtext= substr($str,0,$len-1);
        $strfinal=');'; 		
		$strlast=$strtext.$strfinal;
		//echo "$strlast";die();
		$result= DB::select(DB::raw("$strlast"));
print_r($result);		
		//return $result;
		
?>