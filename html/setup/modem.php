<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <?php include('../local/local.inc');?>  
  <title><?php loc('modem_title');?></title>
    
</head>


<body>

<?php loc('ms_note1');?><br><br>
<fieldset>
<p>
[GSM1]<br>
<b>device = /dev/ttyUSB2</b><i>- <?php loc('ms_note2');?></i><br>
<b>incoming = yes</b><i> -<?php loc('ms_note3');?> </i><br>
<b>#pin = </b><i> - <?php loc('ms_note4');?></i><br>
<b>baudrate = 9600</b><i> - <?php loc('ms_note5');?> </i><br>
<b>rtscts = no</b><i> - <?php loc('ms_note6');?> </i> <br>
<b>init = AT+CPMS="SM","SM",""</b><i> - <?php loc('ms_note7');?></i><br>
<b>incoming = high</b> <br>
</p>
</fieldset>
<br>

<?php



$dir='/dev/serial/by-id';
$devices=glob("/dev/ttyUSB*");
echo "<b>".$text['ms_note8'].":<br><br></b>";

for ($i=0;$i<sizeof($devices);$i++) 
    {
       
	$out=shell_exec("find -L ".$dir." -samefile ".$devices[$i]);
	$out1=shell_exec("sudo stty -F ".$devices[$i]);
	$pos1=strpos($out1,"speed")+6;
	$pos2=strpos($out1,"baud");
	$speed=substr($out1,$pos1,$pos2-$pos1);
        echo "<b>".$text['ms_port'].":&nbsp</b>".$devices[$i]."&nbsp <b>".$text['ms_dev'].":</b>&nbsp".str_replace("/dev/serial/by-id/","",$out).". <b>".$text['ms_speed'].":&nbsp".$speed.$text['ms_baud']."</b><br>";
	

    }

    echo "<br><br>";

    $out3=shell_exec("cat /etc/smsd.conf");      

	echo "<form action='modem_setup.php' method='post'>
    <p><b>".$text['ms_edit']." /etc/smsd.conf:</b></p>
    <p><textarea rows='20' cols='80' name='text'>".$out3."</textarea></p>
    <p><input type='submit' value='".$text['MAC_menu_store']."'></p>
  </form>";

 ?>
<br><b>
<?php loc('ms_note8');?> <a href="sys.php"><?php loc('ms_note9');?></a>
<br></b><br>
<?php loc('ms_note10');?>

<br>
<br>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>
</html>