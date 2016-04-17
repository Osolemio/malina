<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <?php include('../local/local.inc');?>
      
    <title><?php loc('sms_title');?></title>
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

<hr><p><center><b><?php loc('sms_header');?></b></center></p><hr>
<form method="post" action="sms_set.php">
<fieldset>
<b>
<p>
<?php loc('sms_field1');?>: <input type="text" size=30 value="<?php echo $a_n;?>" name="allowed_numbers" id="allowed_numbers">
<?php loc('sms_field2');?> <input type="text" value="<?php echo $s_n;?>" name="sms_number">
</b><br>
<?php loc('sms_field3');?>
<b>
	    <p><?php loc('sms_field4');?>:</p>
</b>	    
<table border="0">
<tr bgcolor="whitesmoke"><td>

<input type="checkbox" <?php echo $_Uacc_checked; ?> name="sms_value1" value="_Uacc"  /> <?php loc('sms_field5');?>, <?php loc('V');?>  
</td>
<td>
&nbsp<?php loc('sms_field6');?>:<input type="number" name="_Uacc_le" step="0.1" value=<?php echo $_Uacc_le; ?> />
</td>

<td>
&nbsp<?php loc('sms_field7');?>:<input type="number" name="_Uacc_ge" step="0.1" value=<?php echo $_Uacc_ge; ?> />
</td>

<td>
&nbsp<?php loc('sms_text');?>:<input type="text" name="_Uacc_sms" size=100 value="<?php echo $_Uacc_sms_text; ?>" />
</td>


</tr>

<tr><td>
<input type="checkbox" <?php echo $_MODE_checked; ?> name="sms_value2" value="_MODE"/> <?php loc('sms_field8');?>  
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbsp<?php loc('sms_text');?>:<input type="text" name="_MODE_sms" size=100 value="<?php echo $_MODE_sms_text; ?> "/>
</td>
</tr>

<tr bgcolor="whitesmoke"><td>
<input type="checkbox" <?php echo $_tacc_checked; ?> name="sms_value3" value="_Temp_Grad0" /><?php loc('TACC');?>, &degC  
</td>

<td>
&nbsp<?php loc('sms_field6');?>:<input type="number" name="_tacc_le" step="1" value=<?php echo $_tacc_le; ?> />
</td>

<td>
&nbsp<?php loc('sms_field7');?>:<input type="number" name="_tacc_ge" step="1" value=<?php echo $_tacc_ge; ?> />
</td>

<td>
&nbsp<?php loc('sms_text');?>:<input type="text" name="_tacc_sms" size=100 value="<?php echo $_tacc_sms_text; ?> "/>
</td>

</tr>

<tr><td>
<input type="checkbox" <?php echo $_UOUTmed_checked; ?> name="sms_value4" value="_UOUTmed"/> <?php loc('sms_field9');?> 
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbsp<?php loc('sms_text');?>:<input type="text" name="_UOUTmed_sms" size=100 value="<?php echo $_UOUTmed_sms_text; ?> "/>
</td>
</tr>


</table>
<br><b>
<input type="checkbox" name="sms_test" value="sms_test"/><?php loc('sms_field10');?>
<br><br></b>
<input type="submit" value="<?php loc('sms_button1');?>" />  
</fieldset>
</center>
</td>			    
</tr>
</table>		

</form>
<br>
<p>
<?php loc('sms_commands');?>
</p>
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