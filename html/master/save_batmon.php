<?php
    include("bd.php");
    
	    $resut=mysql_query("TRUNCATE TABLE batmon",$db) or die(mysql_err());
	    mysql_free_result($result);


    $result=mysql_query("INSERT INTO batmon VALUES ('0','".$_POST['ip']."','".$_POST['active']."')",$db) or die(mysql_err());
 mysql_free_result($result);
 mysql_close($db);
?>