<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  
  <title>Настройки модема</title>
    
</head>


<body>


<?php


echo "Выберите порт к которому подключен USB модем. Если устройство имеет более одного порта, обычно, младший по номеру.<br> <br>";


$dir='/dev/serial/by-id';
$devices=glob("/dev/ttyUSB*");
echo "<form action='modem_setup.php' method='post'> <p> <select name='port'>";
for ($i=0;$i<sizeof($devices);$i++) 
    {
       
	$out=shell_exec("find -L ".$dir." -samefile ".$devices[$i]);

        echo "<option value='".$devices[$i]."'> <b>Порт:&nbsp</b>".str_replace("/dev/tty","",$devices[$i])."&nbsp <b>Устройство:</b>&nbsp".str_replace("/dev/serial/by-id/","",$out)."</option>";
	

    }

echo "</select></p><p><input type='submit' value='Записать'></p></form>"

 ?>

Модем должен быть переведен в режим "только модем".<br>
В терминале обычно это делается командой AT^U2DIAG=0<br>
Для модемов ZyXel или Huawei существует программа 3gsw.exe для Windows<br>
на СИМ-карте должен быть отключен ввод PIN-кода. <br>
Перед тем как установить модем, убедитесь, что мощность питания Raspberry Pi не менее 1500мА, иначе возможна порча SD карты.<br>
<br>
Для использования ПИН-кода и изменения прочих настроек модема и смс-сервиса, возможно ручное редактирование файла /etc/smsd.conf<br>
Описание возможностей смс-сервера и все, все, все: http://smstools3.kekekasvi.com/<br>
* Перечень поддерживаемых моделей GSM модемов определяется текущей версией ядра linux
<br>
<br>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>
</html>