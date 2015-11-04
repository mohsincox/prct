<?php

foreach($t as $b)
{
	echo $b->name;
	$i=$b->id;
	
	foreach($tt as $d)
	{
		echo $d->name; //&nbsp;
		echo "<br/>";
	}
}
?>