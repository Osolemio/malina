<?php
include('../local/local.inc');
    session_start();

    if ($_POST['email_test']==$text['email_field10'] && isset($_POST['mail_recipient'])) 
	{
	    shell_exec("sudo /usr/sbin/email.sh ".$_POST['mail_recipient']);
	    $_SESSION['test_email']="OK";
	    header("Refresh:0; URL=".$_SERVER['HTTP_REFERER']);
	}

if (isset($_POST['email_set']))
    {
	if ($_POST['AuthPass']!==$_POST['pass_confirm'])
	    {
	    $_SESSION['password']='ERROR';
	    header("Refresh:0; URL=".$_SERVER['HTTP_REFERER']);
	    }

	$ssmtp="#################Auto-generated configuration file#################".PHP_EOL.
	"###################You may edit it manually####################".PHP_EOL."FromLineOverride=NO".PHP_EOL."root=".$_POST['root'].PHP_EOL.
	"mailhub=".$_POST['mailhub'].":".$_POST['port'].PHP_EOL.
	"hostname=".$_POST['hostname'].PHP_EOL.
	"UseTLS=".$_POST['UseTLS'].PHP_EOL.
	"UseSTARTTLS=".$_POST['UseSTARTTLS'].PHP_EOL.
	"AuthUser=".$_POST['AuthUser'].PHP_EOL.
	"AuthPass=".$_POST['AuthPass'].PHP_EOL.
	"AuthMethod=".$_POST['AuthMethod'].PHP_EOL.
	"##############END of auto-generated part####################";



	file_put_contents("/var/tmp/ssmtp.conf",$ssmtp);
	
	$revaliases="root:".$_POST['AuthUser'].":".$_POST['mailhub'].":".$_POST['port'].PHP_EOL;

	file_put_contents("/var/tmp/revaliases",$revaliases);

	$splitted="#=============  Aliases or text============================".PHP_EOL.
	"alias[1]=\"".preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '',$_POST['_Uacc_email'])."\"".PHP_EOL.
	"alias[2]=\"".preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '',$_POST['_Uout_email'])."\"".PHP_EOL.
	"alias[3]=\"".preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '',$_POST['_MODE_email'])."\"".PHP_EOL.
	"alias[4]=\"".preg_replace('/[^a-zA-Zа-яА-Я0-9 ]/ui', '',$_POST['_tacc_email'])."\"".PHP_EOL.
	"#=============== min & max values ========================".PHP_EOL.
	"min[1]=".filter_var(str_replace(",",".",$_POST['_Uacc_le']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION).PHP_EOL.
	"min[2]=".filter_var($_POST['_Uout_le'], FILTER_SANITIZE_NUMBER_INT).PHP_EOL.
	"min[3]=2".PHP_EOL.
	"min[4]=".filter_var($_POST['_tacc_le'], FILTER_SANITIZE_NUMBER_INT).PHP_EOL.
	"max[1]=".filter_var(str_replace(",",".",$_POST['_Uacc_ge']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION).PHP_EOL.
	"max[2]=".filter_var($_POST['_Uout_ge'], FILTER_SANITIZE_NUMBER_INT).PHP_EOL.
	"max[3]=2".PHP_EOL.
	"max[4]=".filter_var($_POST['_tacc_ge'], FILTER_SANITIZE_NUMBER_INT).PHP_EOL.
	"# MODE=2, NO INPUT AC".PHP_EOL.
	"#if min=max, alert will be on equal. Otherwise >=max or <=min".PHP_EOL.
	"# you may use decimals because we use perl to compare".PHP_EOL.
	"#====================== ".PHP_EOL.
	"# Number of items".PHP_EOL.
	"index=4".PHP_EOL.
	"#====================== Where we want to send email to =========================".PHP_EOL.
	"mail_recipient=\"".filter_var($_POST['mail_recipient'], FILTER_SANITIZE_EMAIL)."\"".PHP_EOL.PHP_EOL;


	file_put_contents("/var/tmp/splitted.txt",$splitted);
	
	shell_exec('sudo /usr/sbin/compose_set.sh');

 
	    $_SESSION['set_email']="OK";
	    header("Refresh:0; URL=".$_SERVER['HTTP_REFERER']);
    }
	    $_SESSION['set_email']="ERROR";
	    header("Refresh:0; URL=".$_SERVER['HTTP_REFERER']);


?>



