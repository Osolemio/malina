<?php

   include ("./bd.php");


    $result=mysql_query("SELECT * FROM settings",$db) or die(mysql_error());
    $i=0;     
    do {
    
    $row[$i] = mysql_fetch_array($result);
    } while ($row[$i++]!=NULL);

mysql_free_result($result);
mysql_close($db);

?>
