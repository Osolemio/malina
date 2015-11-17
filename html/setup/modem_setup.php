<?php
    
 $file=fopen("/var/map/sms_tmp.conf", "c");
 if (fwrite($file,$_POST['text'])) echo "Временный файл создан успешно<br>"; else echo "Ошибка записи<br>";
    fclose($file);    
 $out=shell_exec("sudo /usr/sbin/modem_setup.sh ");
 echo $out;
    
    echo "<br><br><a href='index.php'> <-вернуться в меню</a>";

?>