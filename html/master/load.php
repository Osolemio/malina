<?php
    include("bd.php");
    $dev=array('МАП','MPPT','МАП+MPPT');
    $result=mysql_query("SELECT acc_number FROM nodes GROUP BY acc_number",$db) or die(mysql_error());
     $out='[';
	while ($row=mysql_fetch_array($result)) {

	$out=$out."{\"id\":\"".$row[0]."\",\"parent\":\"#\",\"text\":\"Блок АКБ ".$row[0]."\",\"icon\":\"../img/bat.png\"},";
	
	}
    mysql_free_result($result);
    $result=mysql_query("SELECT * FROM nodes",$db) or die(mysql_error());
	while ($row=mysql_fetch_assoc($result)) {

	    $out=$out."{\"id\":\"node".$row['number']."\",\"parent\":\"".$row['acc_number']."\",\"text\":\"".$row['name']."\",\"icon\":\"../img/pc.png\"},";
	    $out=$out."{\"id\":\"ip".$row['number']."\",\"parent\":\"node".$row['number']."\",\"text\":\"".$row['ip']."\",\"icon\":\"../img/note.png\"},";
	    $out=$out."{\"id\":\"dev".$row['number']."\",\"parent\":\"node".$row['number']."\",\"text\":\"".$dev[$row['devices']]."\",\"icon\":\"../img/note.png\"},";
}
    $out=substr($out,0,strlen($out)-1);
    $out=$out.']';
    echo $out;
    mysql_free_result($result);     
    mysql_close($db);


?>