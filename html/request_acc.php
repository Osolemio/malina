<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
if (file_exists("/var/map/.map")) {
    $result=mysql_query("SELECT number, time, _Uacc FROM data WHERE number = (SELECT MAX(number) FROM data)",$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);
echo "[{'plot0' : ".$row['_Uacc'].", 'scale-x':'".$row['time']."'}]";
$_SESSION['number']=$row['number'];
$_SESSION['time']=$row['time'];
mysql_free_result($result);

} else if (file_exists("/var/map/.mppt")) {
    $result=mysql_query("SELECT number, time, V_Bat FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);
echo "[{'plot0' : ".$row['V_Bat'].", 'scale-x':'".$row['time']."'}]";
$_SESSION['number']=$row['number'];
$_SESSION['time']=$row['time'];

mysql_free_result($result);
}


mysql_close($db);

?>
