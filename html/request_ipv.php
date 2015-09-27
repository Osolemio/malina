<?php

   include ("./bd.php");

    $result_mpptd=mysql_query("SELECT time, I_Ch, Sign_C0, Sign_C1, I_EXTS0, I_EXTS1 FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());
    $row_mppt=mysql_fetch_array($result_mpptd);
    $ipv=0; if ($row_mppt[1]!=NULL) $ipv=$row_mppt[1];
    if ($row_mppt[2]==1) $i_ext0=0-$row_mppt[4]; else $i_ext0=$row_mppt[4];
    if ($row_mppt[3]==1) $i_ext1=0-$row_mppt[5]; else $i_ext1=$row_mppt[5];
    $i_ext0=$i_ext0/100;
    $i_ext1=$i_ext1/100;
    
echo "[{'plot0' : ".$ipv.", 'plot1':".$i_ext0.", 'plot2':".$i_ext1.", 'scale-x':'".$row_mppt[0]."'}]";

mysql_free_result($result_mpptd);
mysql_close($db);

?>
