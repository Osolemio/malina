<?php
 include('../local/local.inc');    
 $file=fopen("/var/map/sms_tmp.conf", "c");
 if (fwrite($file,$_POST['text'])) echo $text['ms_note11']."<br>"; else echo $text['ms_note12']."<br>";
    fclose($file);    
 $out=shell_exec("sudo /usr/sbin/modem_setup.sh ");
 echo $out;
    
    echo "<br><br><a href='index.php'> <-".$text['MENU']."</a>";

?>