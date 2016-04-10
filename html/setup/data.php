<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <?php include('../local/local.inc');?>  
  <title><?php loc('result');?></title>
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/jquery-migrate-1.2.1.min.js"></script>
  <script src="../local/local_js.js"></script>
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
    
    loc('tables_cleaned');
   }

if (isset($_POST['clean_err']))
    {
    include('../bd.php');
    touch("/var/map/.lock_mysql");
    $result=mysql_query("TRUNCATE TABLE map_errors",$db) or die(mysql_error());    
    $result=mysql_query("TRUNCATE TABLE mppt_errors",$db) or die(mysql_error());
    unlink('/var/map/.lock_mysql'); 
    mysql_close($db);
    
    loc('tables_cleaned');
   }


if (isset($_POST['group1']))
    {
    echo $text['command_import_sent'].$_POST['group1']."<br><br>";
    exec("sudo /usr/sbin/db_restore.sh ".$_POST['group1']." > /dev/null &");    
    }

if (isset($_POST['backup']))
    {
    echo $text['command_export_sent'].$_POST['device']."/".$_POST['filename']."<br><br>";
    exec("sudo /usr/sbin/db_dump.sh ".$_POST['device']."/".$_POST['filename']." > /dev/null &");    
    }

if (isset($_POST['remove']))
    {
    $out=shell_exec("sudo udevadm trigger --action=remove");
    echo $text['media_disconnected'].$out;
    }

?>
<br><br>
<?php loc('executing');?>:
<div id=progress>*------</div>
<br><br>
<a href="index.php"><- <?php loc('MENU');?></a>
<br><br>
<a href="disk.php"><-<?php loc('backward');?></a>
</body>
</html>