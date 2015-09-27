<?php

    include("bdb.php");
    if (isset($_POST['write_work'])) 
	{

        $result=mysql_query("TRUNCATE TABLE work_table",$db) or die(mysql_error());
	for ($i=0;$i<=100;$i++) 
	    {
		if ($_POST['field'.$i]>0)
	         $result=mysql_query("INSERT INTO work_table VALUES ('".$i."','".$_POST['field'.$i]."')") or die(mysql_error());

	    }
	
	}

    if (isset($_POST['write_user'])) 
	{

        $result=mysql_query("TRUNCATE TABLE user_OCV",$db) or die(mysql_error());
	for ($i=0;$i<=100;$i++) 
	    {
		if ($_POST['field'.$i]>0)
	         $result=mysql_query("INSERT INTO user_OCV VALUES ('".$i."','".$_POST['field'.$i]."')") or die(mysql_error());

	    }
	
	}


mysql_free_result($result);
mysql_close($db);

header("Refresh:0; URL=".$_SERVER['HTTP_REFERER']);

?>