<?php
    include("bd.php");
    
    if ($_POST['truncate']=='true')
	{
	    $resut=mysql_query("TRUNCATE TABLE nodes",$db) or die(mysql_error());
	    mysql_free_result($result);

	}

    $query=explode(',',$_POST['node']);
    $dev=0;
    if ($query[3]=='МАП') $dev=0;
    if ($query[3]=='MPPT') $dev=1;
    if ($query[3]=='МАП+MPPT') $dev=2;
    $result=mysql_query("INSERT INTO nodes VALUES (NULL,'".$query[2]."','".$query[0]."','".$dev."','".$query[1]."')",$db) or die(mysql_error());
 mysql_free_result($result);
 mysql_close($db);
?>