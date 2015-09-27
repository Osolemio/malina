<?php

   include ("./bd.php");
    session_start();
    $offset=$_GET['offset'];
    $result=mysql_query("SELECT value FROM settings WHERE offset = ".$offset,$db) or die(mysql_error());     
    $row = mysql_fetch_array($result);
    
    echo $row[0];
    $_SESSION['value_settings']=$row[0];

mysql_free_result($result);
mysql_close($db);

?>
