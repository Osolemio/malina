<?php
    include("bd.php");
    
	$resut=mysql_query("TRUNCATE TABLE nodes",$db) or die(mysql_error());
	mysql_free_result($result);

	mysql_close($db);
?>