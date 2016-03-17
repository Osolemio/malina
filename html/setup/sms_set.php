<?php
include('../local/local.inc');
include("../bd.php");
unlink("/var/map/allowed_numbers.dat");
unlink("/var/map/report_number.dat");



$allowed=fopen("/var/map/allowed_numbers.dat","w");
$number=fopen("/var/map/report_number.dat","w");
fwrite($allowed,$_POST['allowed_numbers']);
fwrite($number,$_POST['sms_number']);

$result=mysql_query("TRUNCATE TABLE sms_alert",$db) or die(mysql_error());     
mysql_free_result($result);

if (isset($_POST['sms_test']) && isset($_POST['sms_number']))
    {
    $out=shell_exec("sudo sendsms ".$_POST['sms_number']." 'Congratulations! Your MAP has sent you first SMS'");
    echo $text['sms_result'].": <br>".$out; 
    }


if (isset($_POST['sms_value1']))
 mysql_query("INSERT INTO sms_alert VALUES (NULL, '_Uacc','".$_POST['_Uacc_le']."','".$_POST['_Uacc_ge']."','".$_POST['_Uacc_sms']."')",$db) or die(mysql_error());     

if (isset($_POST['sms_value2']))
 mysql_query("INSERT INTO sms_alert VALUES (NULL, '_MODE',2,2,'".$_POST['_MODE_sms']."')",$db) or die(mysql_error());     


if (isset($_POST['sms_value3']))
 mysql_query("INSERT INTO sms_alert VALUES (NULL, '_Temp_Grad0','".$_POST['_tacc_le']."','".$_POST['_tacc_ge']."','".$_POST['_tacc_sms']."')",$db) or die(mysql_error());     


if (isset($_POST['sms_value4']))
 mysql_query("INSERT INTO sms_alert VALUES (NULL, '_UOUTmed','100','100','".$_POST['_UOUTmed_sms']."')",$db) or die(mysql_error());     



echo "Данные переданы на запись...<br>";
fclose($allowed);
fclose($number);

mysql_close($db);




echo $text['saved_succes']." <br>";
header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
loc('2sec_return');
exit;
?>








