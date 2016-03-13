<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8"/>
<?php include('./local/local_ru.inc');?> 

  <title><?php loc('errors_title');?></title>

    
<body>


<?php
    session_start();
    include("./bd.php");
    $query = "SELECT * FROM ".$_SESSION['table']." WHERE number BETWEEN'".$_SESSION['number_low']."' AND '".$_SESSION['number_high']."'\n";
        $result=mysql_query($query) or die("Query failed date/time start:".mysql_error());
?>
<b>
<hr>
<center> <?php loc('errors_hdr'); echo $legend[$_SESSION['table']]; ?></center>
<hr>

<table align="center" width="100%" bgcolor="ivory" border="2px" cellpadding="2px">
<tr bgcolor="blanchedalmond"><td><?php loc('DATE');?></td><td><?php loc('TIME');?></td><td>_RSErrSis</td>
<?php
    if ($_SESSION['table']=='map_errors')

	 echo "<td>_RSErrJobM</td><td>_RSWarning</td><td>_I2CErr</td><td>_RSErrDop</td><td>".$text['err_ab_overload']."</td><td>".$text['err_net_overload']."</td><td>".$text['err_fout']."</td><td>".$text['err_uout']."</td></tr></b>";
else echo "</tr></b>";


	

	while ($row=mysql_fetch_assoc($result))
	    {
	echo "<tr><td>".$row['date']."</td><td>".$row['time']."</td><td>";
	
          if ($_SESSION['table']=='mppt_errors')
	    {
	      echo "Value=".$row['_RSErrSis']."&nbsp";    
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrSis']>>$i)&1)>0) echo $data_mppt['_RSErrSis'][$i].",<br>";
	      echo "</td>";
	    }
	
	  if ($_SESSION['table']=='map_errors')	     
	    {
	     echo "Value=".$row['_RSErrSis']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrSis']>>$i)&1)>0) echo $data_map['_RSErrSis'][$i].",<br>";
	     echo "</td><td>";
	    
	    echo "Value=".$row['_RSErrJobM']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrJobM']>>$i)&1)>0) echo $data_map['_RSErrJobM'][$i].",<br>";
	     echo "</td><td>";

	    echo "Value=".$row['_RSWarning']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSWarning']>>$i)&1)>0) echo $data_map['_RSWarning'][$i].",<br>";
	     echo "</td><td>";

	    echo "Value=".$row['_I2C_Err']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_I2C_Err']>>$i)&1)>0) echo $data_map['_I2C_Err'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_RSErrDop']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrDop']>>$i)&1)>0) echo $data_map['_RSErrDop'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_F_AccOver']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_F_AccOver']>>$i)&1)>0) echo $data_map['_F_AccOver'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_F_NETOver']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_F_NETOver']>>$i)&1)>0) echo $data_map['_F_NETOver'][$i].",<br>";
	     echo "</td><td>";


	echo "Value=".round(2500/$row['_TFNET_Limit'],1)."&nbsp</td><td>";
	echo "Value=".$row['_UNET_Limit']."&nbsp</td>";



	    }
	
	echo "</tr>";
	    }


    mysql_free_result($result);
    mysql_close($db);
?>
</table>
<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('SYSTEM');?> " ONCLICK="SystemButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('TEXT');?> " ONCLICK="TextButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('GAUGES');?> " ONCLICK="GaugesButton()"> 

<script>

function HomeButton()
{
location.href="menu.php";
}

function SystemButton()
{
location.href="/setup/index.php";
}

function TextButton()
{
location.href="index.php";
}

function GaugesButton()
{
location.href="gauges.php";
}



</script>



</div>


</body>
</html>