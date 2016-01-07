<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
      
    <title>Настройка СМС</title>
    <style>    
    hr {
	border: none;
	background-color:red;
	color: red;
	height: 2px;
	}
    </style>

    
    </head>
    <body>

<?php

include("../bd.php");

$allowed=fopen("/var/map/allowed_numbers.dat","r");
$number=fopen("/var/map/report_number.dat","r");

$a_n=fread($allowed,100);
$s_n=fread($number,100);

fclose($allowed);
fclose($number);

$result=mysql_query("SELECT * FROM sms_alert",$db) or die(mysql_error());     

$_Uacc_checked='';$_Uacc_ge=58.2;$_Uacc_le=47;
$_MODE_checked='';
$_tacc_checked='';$_tacc_le=-10;$_tacc_ge=40;
$_UOUTmed_checked='';

$i=0;
do {
$row[$i] = mysql_fetch_assoc($result);

if ($row[$i]['field']=='_Uacc')
{ 

$_Uacc_checked='checked';
$_Uacc_ge=$row[$i]['ge'];
$_Uacc_le=$row[$i]['le'];
$_Uacc_sms_text=$row[$i]['sms'];
}

if ($row[$i]['field']=='_MODE') 
{
$_MODE_checked='checked';
$_MODE_ge=$row[$i]['ge'];
$_MODE_le=$row[$i]['le'];
$_MODE_sms_text=$row[$i]['sms'];

} 



if ($row[$i]['field']=='_Temp_Grad0') 
{
$_tacc_checked='checked'; 
$_tacc_ge=$row[$i]['ge'];
$_tacc_le=$row[$i]['le'];
$_tacc_sms_text=$row[$i]['sms'];

}

if ($row[$i]['field']=='_UOUTmed') 
{
$_UOUTmed_checked='checked'; 
$_UOUTmed_ge=$row[$i]['ge'];
$_UOUTmed_le=$row[$i]['le'];
$_UOUTmed_sms_text=$row[$i]['sms'];

}


} while ($row[$i++]!=NULL);


mysql_free_result($result);
mysql_close($db);
?>

<hr><p><center><b>Настройка СМС уведомлений и команд</b></center></p><hr>
<form method="post" action="sms_set.php">
<fieldset>
<b>
<p>
Доверенные номера для команд, через пробел: <input type="text" size=30 value="<?php echo $a_n;?>" name="allowed_numbers" id="allowed_numbers">
Номер для СМС уведомлений <input type="text" value="<?php echo $s_n;?>" name="sms_number">
</b><br>
Формат номера в международном формате (без +)
<b>
	    <p>СМС по событию МАП:</p>
</b>	    
<table border="0">
<tr bgcolor="whitesmoke"><td>

<input type="checkbox" <?php echo $_Uacc_checked; ?> name="sms_value1" value="_Uacc"  /> Диапазон напряжения АКБ, В  
</td>
<td>
&nbspМеньше или равно:<input type="number" name="_Uacc_le" step="0.1" value=<?php echo $_Uacc_le; ?> />
</td>

<td>
&nbspБольше или равно:<input type="number" name="_Uacc_ge" step="0.1" value=<?php echo $_Uacc_ge; ?> />
</td>

<td>
&nbspТекст СМС (латинскими):<input type="text" name="_Uacc_sms" size=100 value="<?php echo $_Uacc_sms_text; ?>" />
</td>


</tr>

<tr><td>
<input type="checkbox" <?php echo $_MODE_checked; ?> name="sms_value2" value="_MODE"/> Отсутствие сети  
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbspТекст СМС (латинскими):<input type="text" name="_MODE_sms" size=100 value="<?php echo $_MODE_sms_text; ?> "/>
</td>
</tr>

<tr bgcolor="whitesmoke"><td>
<input type="checkbox" <?php echo $_tacc_checked; ?> name="sms_value3" value="_Temp_Grad0" />Температура АКБ, &degC  
</td>

<td>
&nbspМеньше или равно:<input type="number" name="_tacc_le" step="1" value=<?php echo $_tacc_le; ?> />
</td>

<td>
&nbspБольше или равно:<input type="number" name="_tacc_ge" step="1" value=<?php echo $_tacc_ge; ?> />
</td>

<td>
&nbspТекст СМС (латинскими):<input type="text" name="_tacc_sms" size=100 value="<?php echo $_tacc_sms_text; ?> "/>
</td>

</tr>

<tr><td>
<input type="checkbox" <?php echo $_UOUTmed_checked; ?> name="sms_value4" value="_UOUTmed"/> Отсутствие сети на выходе 
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbspТекст СМС (латинскими):<input type="text" name="_UOUTmed_sms" size=100 value="<?php echo $_UOUTmed_sms_text; ?> "/>
</td>
</tr>


</table>
<br><b>
<input type="checkbox" name="sms_test" value="sms_test"/>Отправить тестовую СМС
<br><br></b>
<input type="submit" value="Применить" />  
</fieldset>
</center>
</td>			    
</tr>
</table>		

</form>
<br>
<p>
Доступные команды (только с доверенных номеров):
<ul>
<li>#report - запрос состояния МАП </li>
<li>#reportmppt - зпрос состояния MPPT </li>
<li>#stop - прекратить генерацию </li>
<li>#start - включить генерацию </li>
<li>#charge_on - включить заряд АКБ </li>
<li>#charge_off - выключить заряд АКБ </li>
</ul>
</p>
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