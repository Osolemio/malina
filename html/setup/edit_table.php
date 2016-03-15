<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <?php include('../local/local.inc');?>      
    <title><?php loc('oc_title');?></title>

    <link rel="stylesheet" type="text/css" href="./edit_table.css">
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../js/excanvas.min.js"></script><![endif]-->

    <script src="../js/jquery.js"></script>
    <script src="../js/jquery.flot.js"></script>
    <script src="edit_table.js"></script>
    <script>
	$(function() { var t=make_graph(); });
    </script>
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
 include("bdb.php");

 $today=date("Y-m-d");
 $time=date("H:i");

 $result=mysql_query("SELECT * FROM work_table") or die(mysql_error());
 $i=0;
 $work_table=array_fill(0,100,0);
 while ($row=mysql_fetch_array($result))
    {
    $work_table[$row[0]]=$row[1];

    };
 mysql_free_result($result);

?>

<hr><p>
<a href="./index.php"><b><?php loc('MENU');?></b></a>
<center><b><?php loc('oc_field1');?></b></center></p><hr>
<form method="post" action="table_store.php">
<table border="1" color="black" width="20%">
<b>
<tr bgcolor="blanchedalmond"><td width="50%">&nbsp<?php loc('oc_col1');?>&nbsp</td><td width="50%"><?php loc('oc_col2');?>&nbsp</td></tr> 

<?php

 for ($i=0;$i<=100;$i++)
    {
    echo "<tr><td>".$i."%</td><td><input type=number name='field".$i."' id='fval".$i."' value=".$work_table[$i]." min=0.000 max=100.000 step=0.001 /></td></tr>";
    }



?>


<td>&nbsp</td><td>&nbsp</td><tr>

<center>
<td bgcolor="blue"><input type="submit" name="write_work" value="<?php loc('oc_button1');?>" /></td>
<td bgcolor="green"><input type="submit" name="write_user" value="<?php loc('oc_button2');?>" /></td>


</center>
</table>		


<?php

mysql_close($db);


?>


<br><br>
<a href="./index.php"><b><?php loc('MENU');?></b></a>

<div class="chart_wrapper">
<div id="hoverdata"></div>
<div id="button_chart"><input type="button" value="<?php loc('oc_button3');?>" onclick="make_graph()"></div>
<div id="chart"></div></div>



</body>


</html>