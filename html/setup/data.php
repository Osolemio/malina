<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  
  <title>Результат выполнения</title>
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/jquery-migrate-1.2.1.min.js"></script>

  <script src="progress.js"></script>    
</head>


<body>

<?php


if (isset($_POST['clean']))
    {
    include('../bd.php');
    touch("/var/map/.lock_mysql");
    $result=mysql_query("TRUNCATE TABLE data",$db) or die(mysql_error());    
    $result=mysql_query("TRUNCATE TABLE bms",$db) or die(mysql_error());
    $result=mysql_query("TRUNCATE TABLE settings",$db) or die(mysql_error());    
    $result=mysql_query("TRUNCATE TABLE bms_alert",$db) or die(mysql_error());
    $result=mysql_query("TRUNCATE TABLE mppt",$db) or die(mysql_error());
    unlink('/var/map/.lock_mysql'); 
    mysql_close($db);
    
    echo "Таблицы ПО мониторинга успешно очищены";
   }

if (isset($_POST['group1']))
    {
    echo "Команда импорта в БД отправлена. Будет импортирован файл: ".$_POST['group1']."<br><br>";
    exec("sudo /usr/sbin/db_restore.sh ".$_POST['group1']." > /dev/null &");    
    }

if (isset($_POST['backup']))
    {
    echo "Команда экспорта из БД отправлена. Будет создан файл:".$_POST['device']."/".$_POST['filename']."<br><br>";
    exec("sudo /usr/sbin/db_dump.sh ".$_POST['device']."/".$_POST['filename']." > /dev/null &");    
    }

if (isset($_POST['remove']))
    {
    $out=shell_exec("sudo udevadm trigger --action=remove");
    echo "Сменные носители были безопасно отключены. Их можно извлечь<br>".$out;
    }

?>
<br><br>
Выполняем:
<div id=progress>*------</div>
<br><br>
<a href="index.php"><-в меню</a>
<br><br>
<a href="disk.php"><-назад</a>
</body>
</html>