<?php

 $dev=str_replace("/dev/","",$_POST['port']);
 $out=shell_exec("sudo /usr/sbin/modem_setup.sh ".$dev);
 echo $out;
    
    echo "<br><br><a href='index.php'> <-вернуться в меню</a>";

?>