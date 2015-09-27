<?php
    include("bd.php");
    $result=mysql_query("SELECT * FROM batmon WHERE number=0",$db) or die(mysql_err());
    $row=mysql_fetch_assoc($result);

    print_r(json_encode($row));
    mysql_free_result($result);
    mysql_close($db);

?>





