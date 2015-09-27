<?php

   include ("./bd.php");
    $map_table="data";
    session_start();
    $bal=0;
    if ($_GET['sw']=='map') {
    $result=mysql_query("SELECT _MODE, _I_acc_avg,_I_mppt_avg  FROM data WHERE number = ".$_SESSION['number'],$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);
    if ($row[0]==4) $bal=$row[1]+$row[2]; else $bal=$row[2]-$row[1];
    echo "[{'plot0' : ".$bal.", 'scale-x':'".$_SESSION['time']."'}]";

    } else
    {
    $result_mpptd=mysql_query("SELECT time, I_Ch, Sign_C0, Sign_C1, I_EXTS0, I_EXTS1 FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());
    $row_mppt=mysql_fetch_array($result_mpptd);
    if ($row[2]==1) $bal+=($row[4]/100); else $bal-=($row[4]/100);
    if ($row[3]==1) $bal+=($row[5]/100); else $bal-=($row[5]/100);
    $bal+=$row[1];
    
    echo "[{'plot0' : ".$bal.", 'scale-x':'".$row_mppt[0]."'}]";

    }

mysql_free_result($result);
mysql_close($db);

?>
