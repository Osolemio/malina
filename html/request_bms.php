<?php

   include ("./bd.php");

    $result=mysql_query("SELECT * FROM bms",$db) or die(mysql_error());     
    $i=0;
    
    do {
    $row[] = mysql_fetch_assoc($result);
    
        }
    while ($row[$i++]!=NULL);
    $i--;
      
    echo $i.",";
    for ($i1=0;$i1<$i;$i1++) echo $row[$i1]['U'].",".$row[$i1]['I'].",".$row[$i1]['t'].",";

mysql_free_result($result);

    $result=mysql_query("SELECT MAX(u), MIN(u) FROM bms",$db) or die(mysql_error());
    $row=mysql_fetch_array($result);
    echo $row[0].",".$row[1];

mysql_close($db);

?>
