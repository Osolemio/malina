<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
  <?php include('../local/local_ru.inc');?>         
    <title><?php loc('battery_title');?></title>
    <script>
	function calculate() {
	var c1=document.getElementById("c1").value;
	var t1=document.getElementById("t1").value;	
	var c2=document.getElementById("c2").value;
	var t2=document.getElementById("t2").value;	
	var i1=Number(c1)/Number(t1);
	var i2=Number(c2)/Number(t2);
	var n_p=(Math.log(Number(t2))-Math.log(Number(t1)))/(Math.log(i1)-Math.log(i2));
		
	document.getElementById("result").innerHTML=n_p.toFixed(2);

	}
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

 $result=mysql_query("SELECT * FROM battery_info WHERE id=0") or die(mysql_error());
 $row=mysql_fetch_assoc($result);
 mysql_free_result($result);

?>

<hr><p><center><b><?php loc('battery_header');?></b></center></p><hr>
<form method="post" action="battery_set.php">
<table border="1" color="black" width="100%">
<b>
<tr bgcolor="blanchedalmond">
<td><?php loc('bat_start_date');?>: <input type="date" value="<?php if ($row['start_date']=='0000-00-00') echo $today; else echo $row['start_date']; ?>" name="start_date"></td>
<td>&nbsp<?php loc('bat_number');?>: <input type="number" value="<?php echo $row['Battery_Number']; ?>" name="battery_number" min=1 max=20 disabled></td>
<td>&nbsp<?php loc('bat_server_ip');?>: <input type="text" value="<?php echo $row['SERVER_IP']; ?>" name="server_ip" disabled></td>
</tr>

<tr bgcolor="ivory">
<p>
<td><?php loc('bat_nominal_c');?>: <input type="number" value="<?php echo $row['C_nominal']; ?>" name="C_nominal" min=10 max=10000><?php loc('Ah');?><br> <?php loc('for');?> <input type="number" value="<?php echo $row['t_nominal']; ?>" name="t_nominal" min=1 max=30><?php loc('hours');?></td>
<td>&nbsp<?php loc('bat_rest_time');?>: <input type="number" value="<?php echo $row['rest_time_min']; ?>" name="rest_time" min=30 max=6000><?php loc('min');?></td>
<td>&nbsp<?php loc('bat_alpha');?>: <input type="number"  value="<?php echo $row['alpha']; ?>" step="0.001" name="battery_alpha" min=0.001 max=0.50></td>
</p>
</tr>
<tr bgcolor="blanchedalmond">
<p>
<td><?php loc('bat_peukert1');?>: <input type="number" value="<?php echo $row['n_p']; ?>" step="0.01" name="peukert" min=1.00 max=1.50></td>
<td>&nbsp<?php loc('bat_coul_eff');?>: <input type="number" value="<?php echo $row['coulombic_eff']; ?>" step="0.001" name="coulombic_eff" min=0.500 max=1.000></td>
<td>&nbsp<?php loc('bat_nominal_voltage');?>: <input type="number"  value="<?php echo $row['voltage']; ?>" name="voltage"><?php loc('V');?></td>
</p>
</tr>
<tr>
<tr bgcolor="ivory">
<p>
<td><?php loc('bat_chg_voltage');?>: <input type="number" value="<?php echo $row['charged_voltage']; ?>" step="0.1" name="charged_voltage"><?php loc('V');?></td>
<td>&nbsp<?php loc('bat_min_current');?>: <input type="number" value="<?php echo $row['min_charged_current']; ?>" step="0.01" name="min_charged_current" min=0.01 max=0.10>xС</td>
<td>&nbsp<?php loc('bat_cells_number');?>: <input type="number"  value="<?php echo $row['cells_number']; ?>" name="cells_number" min=1 max=48></td>
</p>
</tr>


<tr>
<?php loc('shared_batt');?><input type="checkbox" name="shared" <?php if ($row['SHARED']==1) echo "checked"; ?> disabled>
<br><br>
</tr>
<tr bgcolor="blanchedalmond"><td>
    <select name="battery_type">
        <option disabled selected><?php loc('bat_type');?></option>

	<option value="current"><?php loc('bat_type_current');?></option>
	<option value="USER"><?php loc('bat_type_user_def');?></option>
	<option value="LA"><?php loc('bat_type_lead2V');?></option>
	<option value="LA6"><?php loc('bat_type_lead12V');?></option>
	<option value="AGM"><?php loc('bat_type_AGM');?></option>
	<option value="GEL"><?php loc('bat_type_GEL');?></option>
	<option value="LI37"><?php loc('bat_type_LFP37');?></option>
	<option value="LI39"><?php loc('bat_type_LFP39');?></option>
    </select>


</td><td>&nbsp<?php loc('bat_untrusted_soc');?><input type="number" step=0.001 value="<?php echo $row['U_ocv_invalid_min']; ?>" name="Umin"/><?php loc('V');?> <?php loc('to');?> <input type="number" step=0.001 value="<?php echo $row['U_ocv_invalid_max']; ?>" name="Umax"/><?php loc('V');?></td>
<td>
	&nbsp <?php loc('bat_mppt_source');?>
	<select name="i_source">
	    <option value=0 <?php if ($row['I_source']==0) echo "selected";?>><?php loc('bat_map_i2c');?></option>
	    <option value=1 <?php if ($row['I_source']==1) echo "selected";?>>MPPT RS232</option>
	</select>

</td>
</tr>

<tr bgcolor="ivory">
<p>
<td><?php loc('bat_stop_dod');?>: <input type="number" value="<?php echo $row['dod_off']; ?>" step="1" min="0" max="100" name="dod_off">%</td>
<td>&nbsp<?php loc('bat_stop_consumed');?>: <input type="number" value="<?php echo $row['ah_off']; ?>" step="1" name="ah_off"><?php loc('Ah');?></td>
<td>&nbsp<?php loc('bat_note1');?></td>
</p>
</tr>





<tr>

<center>
<td bgcolor="blue"><input type="submit" name="write" value="<?php loc('MAC_menu_store');?>" /></td>
<td bgcolor="red"><input type="submit" name="reset_battery" value="<?php loc('bat_button1');?>" />
<input type="submit" name="reset_stat" value="<?php loc('bat_button2');?>" />
</td>  
<td bgcolor="green"><input type="submit" name="sync_battery" value="<?php loc('bat_sync');?>" /></td>  


</center>
</tr>
</table>		

<input type="number" name="C_measured" value="<? echo $row['C_measured']; ?>" hidden />

</form>
<br><br><center>
<hr><?php loc('bat_header2');?><hr>
</center>

<p>
<?php loc('bat_note2');?> <input type="number" value=10 id="t1"> <?php loc('bat_note3');?>:&nbsp<input type="number" value=100 id="c1"><?php loc('Ah');?>&nbsp
</p>

<p>
<?php loc('bat_note2');?> <input type="number" value=20 id="t2"> <?php loc('bat_note3');?>:&nbsp<input type="number" value=110 id="c2"><?php loc('Ah');?>&nbsp
</p>
<p>
<input type="button" value="<?php loc('bat_button3');?>" onclick="calculate()">
</p>

<?php loc('bat_peuker2');?>:<div id="result">1.00</div>
<hr>

<center>
<?php loc('bat_header3');?>
</center>
<form action="edit_table.php"><input type="submit" value="<?php loc('bat_button4');?>"></form> 
<hr>
<br>


<br>

<table border="1" color="black" width="20%">
<tr bgcolor="lightgreen"><td><?php loc('bat_chg_percent');?></td><td><?php loc('bat_v_el');?></td></tr>

<?php

    $result=mysql_query("SELECT * FROM work_table") or die(mysq_error());

    for ($i=0; $row[$i]=mysql_fetch_array($result); $i++)
	echo "<tr><td>".$row[$i][0]."</td><td>".$row[$i][1]."</td></tr>";
//    array_pop($array);

mysql_free_result($result);
mysql_close($db);


?>


</table>
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