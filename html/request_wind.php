<?php

   include ("./bd.php");

    $result_mpptd=mysql_query("SELECT time, windspeed FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());
    $row_mppt=mysql_fetch_array($result_mpptd);
    $windspeed=0; if ($row_mppt[1]<10000) $windspeed=$row_mppt[1];
echo "[{'plot0' : ".$windspeed.", 'scale-x':'".$row_mppt[0]."'}]";

mysql_free_result($result_mpptd);
mysql_close($db);

?>
