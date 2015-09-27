<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $result=mysql_query("SELECT _UOUTmed FROM data WHERE number = ".$_SESSION['number'],$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);
echo "[{'plot0' : ".$row[0].", 'scale-x':'".$_SESSION['time']."'}]";

mysql_free_result($result);
mysql_close($db);

?>
