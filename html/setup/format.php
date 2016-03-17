<?php
include('../local/local.inc');
foreach (range('a','z') as $letter)
{
$device="/dev/sd".$letter;
if (isset($_POST[$device])) echo $device;

}

if (isset($_POST['clean']))
    {
    include('../bd.php');
   $result=mysql_query("TRUNCATE TABLE data",$db) or die(mysql_error());    
    $result=mysql_query("TRUNCATE TABLE bms",$db) or die(mysql_error());
    $result=mysql_query("TRUNCATE TABLE settings",$db) or die(mysql_error());    
    $result=mysql_query("TRUNCATE TABLE bms_alert",$db) or die(mysql_error());
    $result=mysql_query("TRUNCATE TABLE mppt",$db) or die(mysql_error());

    mysql_close($db);
    
    loc('tables_cleaned');
   }

?>
<br><br>
<a href="index.php"><-<?php loc('MENU');?></a>
<br><br>
<a href="disk.php"><-<?php loc('backward');?></a>
