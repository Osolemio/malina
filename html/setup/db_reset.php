
<?php
    $out=shell_exec("sudo /usr/sbin/pbase_restore.sh");
    echo str_replace("\n","<br>",$out);
    echo "Работа скрипта завершена. Сервисы были остановлены. Запустите необходимые вам через меню системы";


    
?>