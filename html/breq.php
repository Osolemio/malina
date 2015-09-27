<?php

include("bd_bat.php");

if ($_GET['action']==1)
	{
	$result=mysql_query("SELECT MAX(number) from battery_cycle",$db_bat) or die(mysql_error()); 
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	mysql_query("UPDATE battery_cycle SET user_counter=0 WHERE number=".$row[0],$db_bat) or die(mysql_error()); 
	}



mysql_close($db_bat);

?>