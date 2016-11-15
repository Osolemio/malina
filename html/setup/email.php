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
session_start();


if (isset($_SESSION['password']))
    {
	unset($_SESSION['password']);
	loc('psys_different');echo "!!!!<br>";

    }


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
		$pos=strpos($conf,"exit 1");if ($pos!==false) $script_is_active=false; else $script_is_active=true;
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
<p>
<input type="checkbox" value="active" name="script_is_active" <?php if ($script_is_active) echo 'checked';?>/><?php loc('email_field3');?>
</p>
<b>
<p>
<?php loc('email_root');?>: <input type="email" size=30 value="<?php echo $root;?>" name="root" id="root" autocomplete="off">&nbsp
<?php loc('email_smtp');?>: <input type="text" value="<?php echo $mailhub;?>" name="mailhub" autocomplete="off">&nbsp
<?php loc('email_port');?>: <input type="number" value="<?php echo $port;?>" min=1 max=65535 name="port" id="port" autocomplete="off"><br><br>
<?php loc('email_hostname');?>: <input type="text" value="<?php echo $hostname;?>" name="hostname" autocomplete="off">&nbsp
<?php loc('email_login');?>: <input type="text" value="<?php echo $AuthUser;?>" name="AuthUser" autocomplete="off"><br><br>
<?php loc('email_password');?>: <input type="password" value="<?php echo $AuthPass;?>" name="AuthPass" autocomplete="off">&nbsp
<?php loc('email_password1'); $pass_confirm=$AuthPass;?>: <input type="password" value="<?php echo $pass_confirm;?>" name="pass_confirm" autocomplete="off"><br><br>
<?php loc('email_email');?>: <input type="email" value="<?php print $mail_recipient;?>" name="mail_recipient" autocomplete="off">&nbsp
<table valign="baseline"><tr><td  valign="baseline">
<p>
<?php loc('email_TLS');?>:</td><td  valign="baseline">
<input type="radio" value="YES" name="UseTLS" <?php if ($UseTLS=="YES") echo "checked";?> autocomplete="off"><?php loc('YES');?>
<input type="radio" value="NO" name="UseTLS" <?php if ($UseTLS=="NO") echo "checked";?> autocomplete="off"><?php loc('NO');?>
</p></td></tr><tr><td  valign="baseline">
<p>
<?php loc('email_STARTTLS');?>:</td><td  valign="baseline">
<input type="radio" value="YES" name="UseSTARTTLS" <?php if ($UseSTARTTLS=="YES") echo "checked";?> autocomplete="off"><?php loc('YES');?>
<input type="radio" value="NO" name="UseSTARTTLS" <?php if ($UseSTARTTLS=="NO") echo "checked";?> autocomplete="off"><?php loc('NO');?>
</p></td></tr>
<tr><td  valign="baseline">
<p>
<?php loc('email_auth');?>:</td><td  valign="baseline">
<input type="radio" value="LOGIN" name="AuthMethod" <?php if ($AuthMethod=="LOGIN") echo "checked";?> autocomplete="off">LOGIN (PLAIN TEXT)
<input type="radio" value="cram-md5" name="AuthMethod" <?php if ($AuthMethod=="cram-md5") echo "checked";?> autocomplete="off">CRAM-MD5
</p></td></tr>
</table>


</fieldset>
<fieldset>

</b><br>
<?php loc('email_field3');?>
<b>
	    <p><?php loc('email_field4');?>:</p>
</b>	    
<table border="0">
<tr bgcolor="whitesmoke"><td valign="baseline">

<?php loc('email_field5');?>, <?php loc('V');?>  
</td>
<td valign="baseline">
&nbsp<?php loc('email_field6');?>:<input type="number" name="_Uacc_le" step="0.1" value=<?php echo $min[1]; ?> />
</td>

<td valign="baseline">
&nbsp<?php loc('email_field7');?>:<input type="number" name="_Uacc_ge" step="0.1" value=<?php echo $max[1]; ?> />
</td>

<td valign="baseline">
&nbsp<?php loc('email_text');?>:<input type="text" name="_Uacc_email" size=100 value="<?php echo $alias[1]; ?>" />
</td>


</tr>

<tr><td valign="baseline">
<?php loc('email_field8');?>  
</td>
<td>&nbsp</td>
<td>&nbsp</td>
<td valign="baseline">
&nbsp<?php loc('email_text');?>:<input type="text" name="_MODE_email" size=100 value="<?php echo $alias[3]; ?> "/>
</td>
</tr>

<tr bgcolor="whitesmoke"><td valign="baseline">
<?php loc('TACC');?>, &degC  
</td>

<td valign="baseline">
&nbsp<?php loc('email_field6');?>:<input type="number" name="_tacc_le" step="1" value=<?php echo $min[4]; ?> />
</td>

<td valign="baseline">
&nbsp<?php loc('email_field7');?>:<input type="number" name="_tacc_ge" step="1" value=<?php echo $max[4]; ?> />
</td>

<td valign="baseline">
&nbsp<?php loc('email_text');?>:<input type="text" name="_tacc_email" size=100 value="<?php echo $alias[4]; ?> "/>
</td>

</tr>

<tr><td valign="baseline">
<?php loc('email_field9');?> 
</td>
<td valign="baseline">&nbsp<?php loc('email_field6');?>:<input type="number" name="_Uout_le" step="1" value=<?php echo $min[2]; ?> /></td>
<td valign="baseline">&nbsp<?php loc('email_field7');?>:<input type="number" name="_Uout_ge" step="1" value=<?php echo $max[2]; ?> /></td>
<td valign="baseline">
&nbsp<?php loc('email_text');?>:<input type="text" name="_Uout_email" size=100 value="<?php echo $alias[2]; ?> "/>
</td>
</tr>


</table>
<br><b>
<input type="submit" name="email_test" value="<?php loc('email_field10');?>"/>
<br><br></b>
<input type="submit" name="email_set" value="<?php loc('email_button1');?>" />  
</fieldset>
</center>
</td>			    
</tr>
</table>
<br>
<?php

 if (isset($_SESSION['test_email'])) 
    {
	unset($_SESSION['test_email']);
    	loc('email_ok');echo "<br>";
	echo str_replace("\n","<br>",shell_exec('tail /var/log/mail.log'));
    }

if (isset($_SESSION['set_email'])) 
    {
	unset($_SESSION['set_email']);
    	loc('saved_succes');echo "<br>";
    }

?>		

</form>
<br>
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