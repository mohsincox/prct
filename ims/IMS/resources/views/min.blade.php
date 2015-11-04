<?php
$t=array(10,5,15);
	function max()
	{
		$in=0;
		for($i=0;$i<$t.length;$i++)
		{
			if($t>$in)
			{
				$in=$t;
			}
			
		}
		echo $in;
	}
	
?>