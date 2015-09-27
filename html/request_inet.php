<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $result=mysql_query("SELECT _INET_16_4, _IAcc_med_A_u16, _I_mppt_avg FROM data WHERE number = ".$_SESSION['number'],$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);

echo "[{'plot0' : ".$row[0].", 'plot1':".$row[1].", 'plot2':".$row[2].", 'scale-x':'".$_SESSION['time']."'}]";

mysql_free_result($result);
mysql_close($db);

?>
