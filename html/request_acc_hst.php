<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $result=mysql_query("SELECT time,_Uacc FROM data WHERE number =".$_SESSION['number_start'],$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);
echo "[{'plot0' : ".$row['_Uacc'].", 'scale-x':'".$row['time']."'}]";
$_SESSION['number']=$_SESSION['number_start']++;
$_SESSION['time']=$row['time'];
mysql_free_result($result);
mysql_close($db);

?>
