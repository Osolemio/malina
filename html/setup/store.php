<?php
$restricted=array(0x138, 0x139, 0x180, 0x181, 0x182, 0x183, 0x185);
$offset=$_POST['mem_offset'];

include("../bd.php");

$to_map=fopen("/var/map/to_map","w");



if ($offset<0x102 || $offset>0x1B7)
    {
    echo "Вы не можете менять эту настройку"."<br>";
    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
    echo "Через 2 секунды вы будете возвращены обратно";

    exit(-1);
    }

if (in_array($offset, $restricted) && file_exists("/var/map/.restricted"))
    {
    echo "Включен режим ограничения. Вы не можете менять эту настройку <br>";
    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
    echo "Через 2 секунды вы будете возвращены обратно";

    exit(-1);
    }



      switch ($_POST['mem_class']) {

    case 'number': 
	    $value=$_POST['field'];
	    if ($value<$_POST['min'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;
    case 'list':
	    $value=$_POST['mem_value'];
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;
    case 'ac':
	    $value=$_POST['field']-100;    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;
    case 'dc_dop':
	    $value=($_POST['field']*10)>>$_POST['eacc'];
	    $dop=$_POST['field']*10-($value<<$_POST['eacc']);
	    
    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;
    
    case 'power_dop':
	    $value=($_POST['field']/100)>>$_POST['dop'];    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;

    case 'current_c':
	    $value=($_POST['field']*100);    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;
	
    case 'capacity':
	    $value=($_POST['field']>>$_POST['eacc'])/25;    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;

    case 'time':
	    $time=explode(":",$_POST['field']);
	    $time[0]=intval($time[0]);
	    $time[1]=intval($time[1]);
	    $value=($time[0]<<3)+(round($time[1]/10));    
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;

    case 'charge_time':
	    $value=$_POST['field']*60/16;
	    if ($value<$_POST['min_val'] || $value>$_POST['max_val'])
		{
		    echo "Значение вне допустимого диапазона"."<br>";
		    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
		    echo "Через 2 секунды вы будете возвращены обратно";

		    exit(-1);
		}
	    break;


    default:
	    echo "Нет данных для записи"."<br>";
	    header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
	    echo "Через 2 секунды вы будете возвращены обратно"."<br>";

	    exit(-1);
	    break;
	}

$value=intval($value);
$offset=intval($offset);



$post=chr(0xFF).chr($offset&0xFF).chr($offset>>8).chr($value);

fwrite($to_map, $post);
echo "Данные ячейки переданы на запись...<br>";


 do {
    $result=mysql_query("SELECT result FROM eeprom_result WHERE offset =".$offset,$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);

    } while ($row[0]!=0);

mysql_free_result($result);
$result=mysql_query("TRUNCATE TABLE eeprom_result",$db) or die(mysql_error());     
mysql_free_result($result);

if ($_POST['mem_class']=='dc_dop')
	{
	 if ($offset==0x13D) $offset_dop=0x102;
	 if ($offset==0x16C) $offset_dop=0x103;
	 if ($offset==0x185) $offset_dop=0x104;
	 if ($offset==0x186) $offset_dop=0x105;
	 if ($offset==0x187) $offset_dop=0x106;
	$post=chr(0xFF).chr($offset_dop&0xFF).chr($offset_dop>>8).chr($dop);
	fwrite($to_map, $post);

echo "Данные ячейки переданы на запись...<br>";
fclose($to_map);

 do {
    $result=mysql_query("SELECT result FROM eeprom_result WHERE offset =".$offset_dop,$db) or die(mysql_error());     
    $row = mysql_fetch_assoc($result);

    } while ($row[0]!=0);

mysql_free_result($result);
$result=mysql_query("TRUNCATE TABLE eeprom_result",$db) or die(mysql_error());     
mysql_free_result($result);

sleep(2);

	}

fclose($to_map);
mysql_close($db);

echo "Данные успешно записаны <br>";
header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
echo "Через 2 секунды вы будете возвращены обратно";
exit;
?>








