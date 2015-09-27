<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $result=mysql_query("SELECT _UNET, _INET_16_4, _IAcc_med_A_u16, _Uacc, _I_mppt_avg FROM data WHERE number = ".$_SESSION['number'],$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);
    $ppv=round($row[3]*$row[4],0);
    $power=round($row[0]*$row[1],0);
    $power_acc=round($row[2]*$row[3],0);
echo "[{'plot0' : ".$power.", 'plot1':".$power_acc.", 'plot2':".$ppv.", 'scale-x':'".$_SESSION['time']."'}]";

mysql_free_result($result);

mysql_close($db);

?>
