<?php

   include ("./bd.php");


    $result=mysql_query("SELECT * FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);
    print_r(json_encode($row));

    mysql_free_result($result);
    mysql_close($db);

?>
