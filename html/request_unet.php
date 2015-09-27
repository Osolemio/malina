<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $result=mysql_query("SELECT _UNET,_UOUTmed,_MODE FROM data WHERE number = ".$_SESSION['number'],$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);
    if (($row[2]==2 || $row[2]==0) && $row[0]==100) $row[0]=0;

echo "[{'plot0' : ".$row[0].", 'plot1':".$row[1].",'scale-x':'".$_SESSION['time']."'}]";

mysql_free_result($result);
mysql_close($db);

?>
