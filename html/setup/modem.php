<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  
  <title>Настройки модема</title>
    
</head>


<body>

В общем случае редактированию подлежит последняя часть файла настроек /etc/smsd.conf - секция [GSM1]<br><br>
<fieldset>
<p>
[GSM1]<br>
<b>device = /dev/ttyUSB2</b><i>- порт модема. см. ниже в перечне портов. обычно младший из нескольких портов модема</i><br>
<b>incoming = yes</b><i> - разрешение входящей связи (СМС)</i><br>
<b>#pin = </b><i> - ПИН код, если не отменен на СИМ. При установке # убрать</i><br>
<b>baudrate = 115200</b><i> - скорость подключения (см. ниже) </i><br>
<b>rtscts = no</b><i> - не трогать, если не знаете, что это </i> <br>
<b>init = AT+CPMS="SM","SM",""</b><i> - строка инициализации. Если есть ошибки, попробовать AT+CPMS="SM"</i><br>
<b>incoming = high</b> <br>
</p>
</fieldset>
<br>

<?php



$dir='/dev/serial/by-id';
$devices=glob("/dev/ttyUSB*");
echo "<b>Последовательные порты системы:<br><br></b>";

for ($i=0;$i<sizeof($devices);$i++) 
    {
       
	$out=shell_exec("find -L ".$dir." -samefile ".$devices[$i]);
	$out1=shell_exec("sudo stty -F ".$devices[$i]);
	$pos1=strpos($out1,"speed")+6;
	$pos2=strpos($out1,"baud");
	$speed=substr($out1,$pos1,$pos2-$pos1);
        echo "<b>Порт:&nbsp</b>".$devices[$i]."&nbsp <b>Устройство:</b>&nbsp".str_replace("/dev/serial/by-id/","",$out).". <b>Cкорость порта: ".$speed."бод</b><br>";
	

    }

    echo "<br><br>";

    $out3=shell_exec("cat /etc/smsd.conf");      

	echo "<form action='modem_setup.php' method='post'>
    <p><b>Редактирование файла настроек /etc/smsd.conf:</b></p>
    <p><textarea rows='20' cols='80' name='text'>".$out3."</textarea></p>
    <p><input type='submit' value='Сохранить'></p>
  </form>";

 ?>
<br><b>
После изменения конфигурации, убедитесь, что все верно, и затем <a href="sys.php">перезапустите сервис СМС</a>
<br></b><br>
Рекомендации:
<br><br>

Модем должен быть переведен в режим "только модем".<br>
В терминале обычно это делается командой AT^U2DIAG=0<br>
Для модемов ZyXel или Huawei существует программа 3gsw.exe для Windows<br>
на СИМ-карте должен быть отключен ввод PIN-кода. <br>
Перед тем как установить модем, убедитесь, что мощность питания Raspberry Pi не менее 1500мА, иначе возможна порча SD карты.<br>
Также, модем не должен быть в режиме HiLink.
<br>
Описание возможностей смс-сервера и все, все, все: http://smstools3.kekekasvi.com/<br>

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