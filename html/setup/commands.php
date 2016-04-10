<?php

include('../local/local.inc');

if (isset($_POST['on'])) $value=chr(2);;
if (isset($_POST['off'])) $value=chr(1);
if (isset($_POST['charge_start'])) $value=chr(5);
if (isset($_POST['charge_stop'])) $value=chr(4);
if (isset($_POST['reset'])) $value=chr(6);
if (isset($_POST['relay_1']) || isset($_POST['relay_2']))
	{

	    $shm=shmop_open(2015,"a",0,0);
	    $str_json=shmop_read($shm,0,1000);
	    $str=substr($str_json,0,strpos($str_json,"}")+1);
    
	    shmop_close($shm);
 
   	    $row = json_decode($str,true);
	    if (isset($_POST['relay_1'])) $row['_Relay1']=($row['_Relay1']^1);
	    if (isset($_POST['relay_2'])) $row['_Relay2']=($row['_Relay2']^2);
	    $value=$row['_Relay1']+$row['_Relay2'];


	}

if (!isset($_POST['confirm'])) {
    loc('checkbox_required');
    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
    loc('2sec_return');

    exit(-1);



}

include("../bd.php");

$offset=$_POST['mem_offset'];

$to_map=fopen("/var/map/to_map","w");


if (file_exists("/var/map/.restricted") && (isset($_POST['off']) || isset($_POST['reset'])))
    {
    loc('restricted_mode');
    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
    loc('2sec_return');
    exit(-1);
    }



$post=chr(0xFF).chr(0x00).chr(0x00).$value;

if (isset($_POST['relay_1']) || isset($_POST['relay_2']))
	{
 
	$offset=0x586;
	$post=chr(0xFF).chr($offset&0xFF).chr($offset>>8).chr($value);

	}


fwrite($to_map, $post);
loc('command_succes');
echo "<br>";
fclose($to_map);

 do {
    $result=mysql_query("SELECT result FROM eeprom_result WHERE offset =0",$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);

    } while ($row[0]!=0);

mysql_free_result($result);
$result=mysql_query("TRUNCATE TABLE eeprom_result",$db) or die(mysql_error());     
mysql_free_result($result);
mysql_close($db);
loc('command_stored');
echo "<br>";
header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
loc('2sec_return');
exit;

?>