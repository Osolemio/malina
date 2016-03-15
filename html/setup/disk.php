<!DOCTYPE HTML>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <?php include('../local/local.inc');?>
  <title><?php loc('db_title');?></title>
    
</head>


<body>


<?php
$out=shell_exec("sudo fdisk -l | grep '^/dev/sd[a-z]' | cut -c 1-8 | sort | uniq");
$dev_block=explode("\n",rtrim($out));
?>
<form action="data.php" method="post">
<table border="2">
<tr bgcolor="lightskyblue"><td><b><center><?php loc('db_field1');?>:</center></b><br></td></tr>
<?php

$color='ivory';
foreach ($dev_block as $value)
    {
    $color=($color=='ivory')?$color='blanchedalmond':$color='ivory';
    echo "<tr bgcolor=$color><td>".$text['db_field2']." <b>".$value."</b>"; ; if ($value=="/dev/sda") echo "&nbsp<b>-".$text['db_field3'].".<b>";
    echo "</td></tr>";
    if ($value!="") $out=shell_exec("sudo fdisk -l ".$value); else $out="USB media not found";
    echo "<tr bgcolor=$color><td>".str_replace("\n","<br>",$out)."<br><br></td></tr>";

    }
?>
</table>
<br>
<input type="submit" name="remove" value="<?php loc('db_button1');?>">&nbsp<?php loc('db_field4');?>.
<br>
<br><b><i><?php loc('db_field5');?></b></i></u><br> <br>


<br><b><i><?php loc('db_field6');?></b></i></u><br> <br>
<hr>
<center><i><?php loc('db_header1');?></i></center>
<hr>


<br><br><?php loc('db_field7');?>:<br>


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
<?php loc('db_field8');?>:<input type="text" value="map_backup.sql" name="filename">
</p></p><p><input type='submit' name="backup" value='<?php loc('save');?>'></p></form>

<hr>
<center><i><?php loc('db_field12');?></i></center>
<hr>


</form>

    <form name="myform" action="data.php" method="POST">

    <br><?php loc('db_field13');?><br><br><br>

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
<input type="submit" name="recovery" value="<?php loc('db_button4');?>">
</form>

<form name="form2" action="data.php" method="POST">
<hr>
<center><i><?php loc('db_field14');?><i></center>
<hr>
<br>
<p>
</p></p><p><input type='submit' name="clean" value='<?php loc('db_button2');?>'></p>
</p></p><p><input type='submit' name="clean_err" value='<?php loc('db_button3');?>'></p>
</form>
<?php loc('db_field9');?>:<br>

<?php
$out=shell_exec("sudo mysql -u root -pmicroart -e 'SELECT table_schema `database_name`, sum( data_length + index_length )/1024/1024 `Data Base Size in MB` FROM information_schema.TABLES GROUP BY table_schema;'");
echo str_replace("\n","<br>",$out);
?>

<hr><hr>


<br><br> <b><?php loc('db_field10');?>:</b>
<?php
$out=shell_exec('sudo mysqladmin -pmicroart variables | grep datadir | cut -d "|" -f 3');

?>

<br><br><?php loc('db_field11');?>:<b><?php echo $out;?></b><br>
<?php 
$out=shell_exec("sudo service mysql status");
echo str_replace("\n","<br>",$out);
?>


<br><br>

<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>
</html>