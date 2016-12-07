<?php

   include ("/var/www/html/bd_bat.php");
    if (!isset($_GET['table'])) $_GET['table']='cycle';

    if ($_GET['table']=='cycle')
    $result=mysql_query("SELECT * FROM battery_cycle WHERE number = (SELECT MAX(number) FROM battery_cycle)",$db_bat) or die(mysql_error());     

    if ($_GET['table']=='state')
    $result=mysql_query("SELECT * FROM battery_state WHERE number = 1",$db_bat) or die(mysql_error());     

    if ($_GET['table']=='info')
    $result=mysql_query("SELECT * FROM battery_info WHERE id=0",$db_bat) or die(mysql_error());     

    $row = mysql_fetch_assoc($result);
    print_r(json_encode($row));

    mysql_free_result($result);
    mysql_close($db_bat);

?>
