<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <?php include('../local/local.inc');?>
      
    <title><?php loc('email_title');?></title>
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
$cifers=array('0','1','2','3','4','5','6','7','8','9','0','.');
shell_exec('/usr/sbin/splitter.sh');


$conf=file_get_contents("/var/tmp/splitted.txt");
$ssmtp=file_get_contents("/var/tmp/ssmtp.conf");


if ($conf)

    {
	for ($i=1;$i<=4;$i++)
	    {
		$pos=strpos($conf,"min[".$i."]=");$s="";$c=7;while (in_array($conf[$pos+$c],$cifers)) $s.=$conf[$pos+$c++];$min[$i]=$s;
		$pos=strpos($conf,"max[".$i."]=");$s="";$c=7;while (in_array($conf[$pos+$c],$cifers)) $s.=$conf[$pos+$c++];$max[$i]=$s;
		$pos=strpos($conf,"alias[".$i."]=");$s="";$c=10;while ($conf[$pos+$c]!='"') $s.=$conf[$pos+$c++];$alias[$i]=$s;
	    }

    }

    else 

    { 
	loc('error'); exit(1);
    }
		$len=strlen($conf);
		$pos=strpos($conf,"mail_recipient="); $mail_recipient="";if ($pos !== false) while ($conf[$pos+16]!=='"' && ($pos+16)<$len) $mail_recipient.=$conf[($pos++)+16];

		$len=strlen($ssmtp);
		$pos=strpos($ssmtp,"root="); $root="";if ($pos !== false) while ($ssmtp[$pos+5]!==PHP_EOL && ($pos+5)<$len) $root.=$ssmtp[($pos++)+5];
		$pos=strpos($ssmtp,"mailhub="); $mailhub="";if ($pos !== false) while ($ssmtp[$pos+8]!=":" && ($pos+8)<$len) $mailhub.=$ssmtp[($pos++)+8];
		$port=""; ++$pos; while ($ssmtp[$pos+8]!==PHP_EOL && ($pos+8)<$len) $port.=$ssmtp[($pos++)+8];
		$pos=strpos($ssmtp,"hostname="); $hostname="";if ($pos !== false) while ($ssmtp[$pos+9]!==PHP_EOL && ($pos+9)<$len) $hostname.=$ssmtp[($pos++)+9];
		$pos=strpos($ssmtp,"UseTLS="); $UseTLS="";if ($pos !== false) while ($ssmtp[$pos+7]!==PHP_EOL && ($pos+7)<$len) $UseTLS.=$ssmtp[($pos++)+7]; if ($UseTLS=="") $UseTLS="NO";
		$pos=strpos($ssmtp,"UseSTARTTLS="); $UseSTARTTLS="";if ($pos !== false) while ($ssmtp[$pos+12]!==PHP_EOL && ($pos+12)<$len) $UseSTARTTLS.=$ssmtp[($pos++)+12]; if ($UseSTARTTLS=="") $UseSTARTTLS="NO";
		$pos=strpos($ssmtp,"AuthUser="); $AuthUser="";if ($pos !== false) while ($ssmtp[$pos+9]!==PHP_EOL && ($pos+9)<$len) $AuthUser.=$ssmtp[($pos++)+9];
		$pos=strpos($ssmtp,"AuthPass="); $AuthPass="";if ($pos !== false) while ($ssmtp[$pos+9]!==PHP_EOL && ($pos+9)<$len) $AuthPass.=$ssmtp[($pos++)+9];
		$pos=strpos($ssmtp,"AuthMethod="); $AuthMethod="";if ($pos !== false) while ($ssmtp[$pos+11]!==PHP_EOL && ($pos+11)<$len) $AuthMethod.=$ssmtp[($pos++)+11];if ($AuthMethod=="") $AuthMethod="LOGIN";

		



?><hr><p><center><b><?php loc('email_header');?></b></center></p><hr>
<form method="post" action="email_set.php">
<fieldset>
<b>
<p>
<?php loc('email_field1');?>: <input type="email" size=30 value="<?php echo $root;?>" name="root" id="root" autocomplete="off">
<?php loc('email_field2');?> <input type="text" value="<?php echo $mailhub;?>" name="mailhub" autocomplete="off">
<?php loc('email_field1');?>: <input type="number" value="<?php echo $port;?>" min=1 max=65535 name="port" id="port" autocomplete="off">
<?php loc('email_field2');?> <input type="text" value="<?php echo $hostname;?>" name="hostname" autocomplete="off">
<?php loc('email_field2');?> <input type="text" value="<?php echo $AuthUser;?>" name="AuthUser" autocomplete="off">
<?php loc('email_field2');?> <input type="password" value="<?php echo $AuthPass;?>" name="AuthPass" autocomplete="off">
<?php loc('email_field2'); $pass_confirm=$AuthPass;?> <input type="password" value="<?php echo $pass_confirm;?>" name="pass_confirm" autocomplete="off">
<?php loc('email_field2');?> <input type="email" value="<?php print $mail_recipient;?>" name="mail_recipient" autocomplete="off">
<p>
TLS:
<input type="radio" value="YES" name="UseTLS"><?php loc('YES');?>
<input type="radio" value="NO" name="UseTLS"><?php loc('NO');?>
</p>
<p>
StarTTLS:
<input type="radio" value="YES" name="UseStarTTLS"><?php loc('YES');?>
<input type="radio" value="NO" name="UseStarTTLS"><?php loc('NO');?>
</p>

</fieldset>
<fieldset>

</b><br>
<?php loc('email_field3');?>
<b>
	    <p><?php loc('email_field4');?>:</p>
</b>	    
<table border="0">
<tr bgcolor="whitesmoke"><td>

<input type="checkbox" <?php echo $_Uacc_checked; ?> name="email_value1" value="_Uacc"  /> <?php loc('email_field5');?>, <?php loc('V');?>  
</td>
<td>
&nbsp<?php loc('email_field6');?>:<input type="number" name="_Uacc_le" step="0.1" value=<?php echo $_Uacc_le; ?> />
</td>

<td>
&nbsp<?php loc('email_field7');?>:<input type="number" name="_Uacc_ge" step="0.1" value=<?php echo $_Uacc_ge; ?> />
</td>

<td>
&nbsp<?php loc('email_text');?>:<input type="text" name="_Uacc_email" size=100 value="<?php echo $_Uacc_email_text; ?>" />
</td>


</tr>

<tr><td>
<input type="checkbox" <?php echo $_MODE_checked; ?> name="email_value2" value="_MODE"/> <?php loc('email_field8');?>  
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbsp<?php loc('email_text');?>:<input type="text" name="_MODE_email" size=100 value="<?php echo $_MODE_email_text; ?> "/>
</td>
</tr>

<tr bgcolor="whitesmoke"><td>
<input type="checkbox" <?php echo $_tacc_checked; ?> name="email_value3" value="_Temp_Grad0" /><?php loc('TACC');?>, &degC  
</td>

<td>
&nbsp<?php loc('email_field6');?>:<input type="number" name="_tacc_le" step="1" value=<?php echo $_tacc_le; ?> />
</td>

<td>
&nbsp<?php loc('email_field7');?>:<input type="number" name="_tacc_ge" step="1" value=<?php echo $_tacc_ge; ?> />
</td>

<td>
&nbsp<?php loc('email_text');?>:<input type="text" name="_tacc_email" size=100 value="<?php echo $_tacc_email_text; ?> "/>
</td>

</tr>

<tr><td>
<input type="checkbox" <?php echo $_UOUTmed_checked; ?> name="email_value4" value="_UOUTmed"/> <?php loc('email_field9');?> 
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td>
&nbsp<?php loc('email_text');?>:<input type="text" name="_UOUTmed_email" size=100 value="<?php echo $_UOUTmed_email_text; ?> "/>
</td>
</tr>


</table>
<br><b>
<input type="checkbox" name="email_test" value="email_test"/><?php loc('email_field10');?>
<br><br></b>
<input type="submit" value="<?php loc('email_button1');?>" />  
</fieldset>
</center>
</td>			    
</tr>
</table>		

</form>
<br>
<p>
<?php

$ssmtp=fopen("/var/tmp/ssmtp.conf","rw");
$revaliases=fopen("/var/tmp/revaliases","rw");
$splitted=fopen("/var/tmp/splitted.txt","rw");
 
fclose($ssmpt);
fclose($revaliases);
fclose($splitted);

?>
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