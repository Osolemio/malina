<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <script src="./js/jquery-2.1.3.min.js"></script>
   <script src="./js/jquery-migrate-1.2.1.min.js"></script>
   <?php include('./local/local.inc'); ?>
      
    <title><?php loc('history_page'); ?></title>
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
 $today=date("Y-m-d");
 $yesterday=date("Y-m-d",mktime(0,0,0,date("m"), date("d")-1, date("Y")));
 $time=date("H:i");

?>
<div style="background-color:#F0F0F0">
<hr><p><center><b><?php loc('history_header'); ?></b></center></p><hr>
</div>
<form method="post" action="history_hdl.php">
<table border="1" color="black" width="100%">
<tr>    
<td>
<center>
<b>
<p><?php loc('start_date'); ?>: <input type="date" value=<?php echo $yesterday; ?> name="date_start" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
<?php loc('end_date'); ?>: <input type="date" value=<?php echo $today; ?> name="date_end"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
<?php loc('start_time'); ?>: <input type="time" value=<?php echo $time; ?> name="time_start" pattern="[0-9]{2}:[0-9]{2}">
<?php loc('end_time'); ?>: <input type="time" value=<?php echo $time; ?> name="time_end" pattern="[0-9]{2}:[0-9]{2}"></p>
</b>
</center>
</td>
</tr>
<tr>
<td>
<b>
	<p><?php loc('Graph_size'); ?>, pix</p>
<b>
<p><?php loc('h_size'); ?>: <input type="number" value="1200" name="width">
  <?php loc('v_size'); ?>: <input type="number" value="800" name="height"></p>

</td>
</tr>

<tr>
<td>	
<b>
	    <p><?php loc('mac_graphs'); ?>:</p>
</b>	    
<p><input type="radio" name="field" value="_Uacc" /> <?php loc('UACC'); ?>, <?php loc('V'); ?>  
<input type="radio" name="field" value="_IAcc_med_A_u16" /> <?php loc('IACC'); ?>, <?php loc('A'); ?>  
<input type="radio" name="field" value="_PLoad" /><?php loc('PACC'); ?>, <?php loc('W'); ?> 
<input type="radio" name="field" value="_UNET"/> <?php loc('UNET'); ?>, <?php loc('V'); ?>  
<input type="radio" name="field" value="_INET_16_4" /><?php loc('INET'); ?>, <?php loc('A'); ?> 
<input type="radio" name="field" value="_PNET" checked/><?php loc('PNET'); ?>, <?php loc('W'); ?>  
<input type="radio" name="field" value="_UOUTmed" /><?php loc('UMAC'); ?>, <?php loc('V'); ?>  
<input type="radio" name="field" value="_Temp_Grad0" /><?php loc('TACC'); ?>, &degC  
</p>
</td>
</tr>	

<tr>
<td>	
	
<b>	    <p><?php loc('mppt_graphs'); ?>:</p></b>
<p><input type="radio" name="field" value="Vc_PV" /><?php loc('UPV'); ?>, <?php loc('V'); ?>   
<input type="radio" name="field" value="Ic_PV" /><?php loc('IPV'); ?>, <?php loc('A'); ?>  
<input type="radio" name="field" value="V_Bat" /><?php loc('UACC'); ?>, <?php loc('V'); ?>  
<input type="radio" name="field" value="P_PV" /><?php loc('PPV'); ?>, <?php loc('W'); ?>  
<input type="radio" name="field" value="P_curr" /><?php loc('PCHG'); ?>, <?php loc('W'); ?>
<input type="radio" name="field" value="cs1" /><?php loc('cs1'); ?>, <?php loc('A'); ?>
<input type="radio" name="field" value="cs2" /><?php loc('cs2'); ?>, <?php loc('A'); ?>
<input type="radio" name="field" value="windspeed" /><?php loc('wind_rpm'); ?>, <?php loc('min'); ?><sup>-1</sup>

</p>
<p>

<input type="radio" name="field" value="Energy" /><?php loc('kwh_stat'); ?></p>
</p>

</td>
</tr>

<tr>
<td>

<input type="radio" name="field" value="map_errors" /><b><?php loc('mac_errors'); ?>
<input type="radio" name="field" value="mppt_errors" /><?php loc('mppt_errors'); ?></b>
<br>


</td>
</tr>


<tr>
<td>
<center>
<input type="submit" style="background-color:lightgreen" value="<?php loc('go!_button'); ?>" />
<input type="submit" style="background-color:lightgreen" name="multichart" value="<?php loc('multichart_button'); ?>" />  
</center>
</td>			    
</tr>
</table>		

</form>
<br><i>
*<?php loc('note1'); ?>
<br><br>
*<?php loc('note2'); ?>
<br><br>
</i>

<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU'); ?> " ONCLICK="HomeButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('SYSTEM'); ?> " ONCLICK="SystemButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('TEXT'); ?> " ONCLICK="TextButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('GAUGES'); ?> " ONCLICK="GaugesButton()"> 

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







<script>
function Reset_Counter(sw) {
    $.ajax({
	data:{
		'sw': sw
		},
	url: 'makedrop.php',
	async: false,
        method: 'POST',
	success: function(response) {
	alert("выполнено");
	},
	error: function (response) {
    	    var r = jQuery.parseJSON(response.responseText);
    	    alert("Message: " + r.Message);
    	    alert("StackTrace: " + r.StackTrace);
    	    alert("ExceptionType: " + r.ExceptionType);
	}});



}


</script>
<?php

    if (file_exists('/var/map/.bmon')) {
?>
<div style="background-color:#F0F0F0">
<hr><p><center><b><?php loc('header_history_2'); ?></b></center>
<div align="right">
    <input TYPE='button' style='font-weight:bolder; background-color:orange;' VALUE=' <?php loc('reset_button1'); ?> ' ONCLICK='Reset_Counter(1)'> 
&nbsp&nbsp
    <input TYPE='button' style='font-weight:bolder; background-color:orange;' VALUE=' <?php loc('reset_button2'); ?> ' ONCLICK='Reset_Counter(2)'> 
</div>
</p><hr>
</div>
<?php
    include('bd_bat.php');
      
     $result=mysql_query("SELECT * FROM battery_state WHERE number=1",$db_bat) or die(mysql_error());
     $row=mysql_fetch_assoc($result);
     echo "
	<b>
	<table border=1>
	    <tr>
	    <td width='5%'>".$text['deepest_discharge']."</td>
	    <td width='5%'>".$text['umin']."</td><td width='5%'>".$text['umax']."</td>
	    <td width='5%'>".$text['latest_chg']."</td><td width='5%'>".$text['autosync_number']."</td>
	    <td width='5%'>".$text['summ_from_acc']."</td><td width='5%'>".$text['summ_to_acc']."</td>
	    <td width='5%'>".$text['alt_day']."</td><td width='5%'>".$text['alt_month']."</td>
	    <td width='5%'>".$text['alt_total']."</td><td width='5%'>".$text['alt_user']."</td>
	    </tr>
	    <tr>
	    <td>".$row['deepest_discharge']."</td>
	    <td>".$row['lowest_voltage']."</td>
	    <td>".$row['highest_voltage']."</td>
	    <td>".$row['last_charge_date']."</td>
	    <td>".$row['number_autosync']."</td>
	    <td>".round($row['E_summary_from_battery']/1000,3)."</td>
	    <td>".round($row['E_summary_to_battery']/1000,3)."</td>
	    <td>".$row['E_alt_daily']."</td>
	    <td>".$row['E_alt_monthly']."</td>
	    <td>".$row['E_alt_summary']."</td>
	    <td>".$row['E_alt_user']."</td>


	    </tr>
	</table>
*".$text['note3']."
	</b>
    <br>
    <input TYPE='button' style='font-weight:bolder; background-color:darkkhaki;' VALUE=' ".$text['MENU']." ' ONCLICK='HomeButton()'> 
    ";
    mysql_free_result($result);
    mysql_close($db_bat);


?>







<?php } ?>
</body>


</html>