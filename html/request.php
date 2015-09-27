<?php

   include ("./bd.php");
    $map_table="data";
    $mppt_table="mppt";


    $result=mysql_query("SELECT * FROM data WHERE number = (SELECT MAX(number) FROM data)",$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);
    $result_mpptd=mysql_query("SELECT * FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());     
    $power=round($row['_INET_16_4']*$row['_UNET'],0);
    $power_acc=round($row['_IAcc_med_A_u16']*$row['_Uacc'],0);
    $power_pv=0;

    if ($result_mpptd!=NULL && file_exists("/var/map/.mppt")) {
    
    $row_mpptd = mysql_fetch_assoc($result_mpptd);
    $power_pv=$row_mpptd['P_Out']; 
    }

    mysql_free_result($result);
    $result=mysql_query("SELECT value from settings WHERE offset=342", $db) or die(mysql_error());
    $row1=mysql_fetch_array($result);
    $mppt_present=$row1[0];

   if ($mppt_present==2 || $mppt_present==3) 
		$power_pv=round($row['_I_mppt_avg']*$row['_Uacc'],0);
    

    echo $power.",".$power_acc.",".$power_pv;


mysql_free_result($result);
mysql_free_result($result_mpptd);
mysql_close($db);

?>
