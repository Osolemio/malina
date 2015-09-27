<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  
  <title>Работа с БД mysql</title>
    
</head>


<body>


<?php
$out=shell_exec("sudo fdisk -l | grep '^/dev/sd[a-z]' | cut -c 1-8 | sort | uniq");
$dev_block=explode("\n",rtrim($out));
?>
<form action="data.php" method="post">
<table border="2">
<tr bgcolor="lightskyblue"><td><b><center>Cменные накопители, доступные для выбора, и информация о разделах:</center></b><br></td></tr>
<?php

$color='ivory';
foreach ($dev_block as $value)
    {
    $color=($color=='ivory')?$color='blanchedalmond':$color='ivory';
    echo "<tr bgcolor=$color><td>Устройство (диск, флеш-карта и т.п) <b>".$value."</b>"; ; if ($value=="/dev/sda") echo "&nbsp<b>-системный.<b>";
    echo "</td></tr>";
    if ($value!="") $out=shell_exec("sudo fdisk -l ".$value); else $out="USB media not found";
    echo "<tr bgcolor=$color><td>".str_replace("\n","<br>",$out)."<br><br></td></tr>";

    }
?>
</table>
<br>
<input type="submit" name="remove" value="Безопасно извлечь все съеные накопители">&nbspКроме системного диска.
<br>
<br><b><i>В процессе нижеуказанных действий база данных будет заблокирована и недоступна, либо остановлена на время сохранения/восстановления.</b></i></u><br> <br>


<br><b><i>*Если вы не понимаете, что и для чего вы делаете, лучше ничего не предпринимать!</b></i></u><br> <br>
<hr>
<center><i>Резервное копирование (занимает много времени)</i></center>
<hr>


<br><br>Выберите устройство и раздел для резервного сохранения БД:<br>


<?php

$dir='/mnt/';
$devices=scandir($dir);
echo "<form action='data.php' method='post'> <p> <select name='device'>";
for ($i=2;$i<sizeof($devices);$i++) 
    {
       
        echo "<option value='".$dir.$devices[$i]."'>".$devices[$i]."</option>";
	

    }
echo "</select>"
?>
<br>
<br>
<p>
Имя файла для сохраниения:<input type="text" value="map_backup.sql" name="filename">
</p></p><p><input type='submit' name="backup" value='Сохранить'></p></form>

<hr>
<center><i>Восстановление базы данных из резервной копии (занимает много времени)</i></center>
<hr>


</form>

    <form name="myform" action="data.php" method="POST">

    <br>Восстановление из файла. Выберите файл и нажмите "Восстановить". Система ищет в корневом каталоге сменных носителей файл с расширением sql (*.sql)<br><br><br>

<?php
$out=glob("/mnt/*/*.sql");

sort($out);
$c = count($out);
for($i=0; $i<$c; $i++)
{
    echo "<input type=\"radio\" name=\"group1\" value=\"" . $out[$i] . "\">" . $out[$i] . "<br />";
}
?>
</select>
<br></b>
<input type="submit" name="recovery" value="Восстановить">
</form>

<form name="form2" action="data.php" method="POST">
<hr>
<center><i>Очистка текущей базы данных. Будет выполнена очистка всех таблиц ПО мониторинга, за исключением настроек. Структура БД сохраняется.<i></center>
<hr>
<br>
<p>
</p></p><p><input type='submit' name="clean" value='Очистить все таблицы (TRUNCATE)'></p>
</form>
Текущий размер таблиц:<br>

<?php
$out=shell_exec("sudo mysql -u root -pmicroart -e 'SELECT table_schema `database_name`, sum( data_length + index_length )/1024/1024 `Data Base Size in MB` FROM information_schema.TABLES GROUP BY table_schema;'");
echo str_replace("\n","<br>",$out);
?>

<hr><hr>


<br><br> <b>Статус сервера БД:</b>
<?php
$out=shell_exec('sudo mysqladmin -pmicroart variables | grep datadir | cut -d "|" -f 3');

?>

<br><br>Текущее расположение БД:<b><?php echo $out;?></b><br>
<?php 
$out=shell_exec("sudo service mysql status");
echo str_replace("\n","<br>",$out);
?>


<br><br>

<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>
</html>