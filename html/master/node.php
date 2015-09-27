<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8"/>
  <link rel="stylesheet" type="text/css" href="node.css"/>
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/jquery-migrate-1.2.1.min.js"></script>
  
 
  <title>Монитор узла АКБ <?php echo $_GET['node']; ?> </title>

    
</head>


<body>

<?php 

    include("bd.php");
    $i=0; $all=0;
    $result=mysql_query("SELECT * FROM nodes WHERE acc_number=".$_GET['node'],$db) or die(mysql_error());
	while ($row=mysql_fetch_assoc($result)) {
	    $node[$i]['name']=$row['name'];
	    $node[$i]['ip']=$row['ip'];
	    $node[$i]['dev']=$row['devices'];
	    $i++;$all++; if ($row['devices']==2) ++$all;
	}


    mysql_free_result($result);

    $result=mysql_query("SELECT * FROM batmon WHERE number=0",$db) or die(mysql_error());
    $row=mysql_fetch_assoc($result);

    $ip_batmon=$row['ip'];
    $active_batmon=$row['active'];
    
    mysql_close($db);
?>

<script>
 var nodes,active_batmon=0,ip_batmon='127.0.0.1';

 $(function() {    
   nodes=$.parseJSON(<?php echo("'".json_encode($node)."'"); ?>);
   ip_batmon=<?php echo("'".$ip_batmon)."';"; ?>
   active_batmon=<?php echo($active_batmon).";"; ?>
    });

</script>
<script src="./node.js"></script>

<div class="wrapper" style='width:<?php echo 208*($all)."px;"; ?>'>
<div id="accumulator" class="acc">
<?php if ($active_batmon==1) { echo "

<div class='battery_green'><div class='battery_in'><div id='battery_percent' class='battery_black'></div></div></div>
<div class='battery_blue'><div class='battery_in'><div id='battery_ah' class='battery_black'></div></div></div>
<div id='text_battery_ah' style='color:white; top:140px; left:10px; font-size:120%; font-weight:700;'>Н/Д</div>
<div id='text_battery_percent' style='color:white; top:100px; left:10px; font-size:120%; font-weight:700;'>Н/Д</div>
<div id='text_battery_cons' style='color:white; top:140px; left:150px; font-size:120%; font-weight:700;'>Н/Д</div>


";
}

?>

<div style="bottom:1px; left:2px;">
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>
</div>
</div>

<?php
    
    $n=0;

    for ($index=0;$index<$i;$index++) {
	if (($node[$index]['dev']&1)==0) 
	    {
	    $n++;
	    echo "<div id='map".$index."' class='map'>
	<div class='name'>".$node[$index]['name']."</div><div class='ip'>".$node[$index]['ip']."</div>
	<div class='setup_but but' onclick='setup(".$index.")'><img src='../img/setup.png'></div>
	<div class='gauges_but but' onclick='gauges(".$index.")'><img src='../img/gauges.png'></div>
	<div id='text_map_vin".$index."' class='text_map_vin'>н/д</div>
	<div id='text_map_vout".$index."' class='text_map_vout'>н/д</div>
	<div id='text_map_inet".$index."' class='text_map_inet'>н/д</div>
	<div id='text_map_fin".$index."' class='text_map_fin'>н/д</div>
	<div id='text_map_fout".$index."' class='text_map_fout'>н/д</div>
	<div id='text_map_pnet".$index."' class='text_map_pnet'>н/д</div>
	<div id='text_map_pacc".$index."' class='text_map_pacc'>н/д</div>
	<div id='text_map_iacc".$index."' class='text_map_iacc'>н/д</div>
	<div id='text_map_psumm".$index."' class='text_map_psumm'>н/д</div>
	<div id='text_map_pi2c".$index."' class='text_map_pi2c'>н/д</div>
	<div id='text_map_ii2c".$index."' class='text_map_ii2c'>н/д</div>
	<div id='text_map_temp".$index."' class='text_map_temp'>н/д</div>
	<div id='led_map_error".$index."' class='led map_error'></div>	
	<div id='led_map_net".$index."' class='led map_net'></div>
	<div id='led_map_relay".$index."' class='led map_relay'></div>	
	<div id='led_map_charge".$index."' class='led map_charge'></div>
	<div id='map_stop_acc".$index."' class='stop map_acc'></div>
	<div id='map_stop_add".$index."' class='stop map_acc_add'></div>

</div>

	<div id='arrow_map_up".$index."' class='arrow_up floating_up' style='left:".(($n-1)*208+40)."px;'></div>
	<div id='arrow_map_down".$index."' class='arrow_down floating' style='left:".(($n-1)*208+40)."px;'></div>
	<div id='arrow_map_i2c".$index."' class='arrow_down floating' style='left:".(($n-1)*208+88)."px;'></div>
	<div id='map_vacc".$index."' style='color:white; font-size:120%; font-weight:700; text-align:center; background-color:#000013; width:60px; height:30px; top:500px; left:".(($n-1)*208+140)."px;'>н/д</div>
	<div id='map_acc_temp".$index."' style='color:white; font-size:120%; font-weight:700; text-align:center; background-color:#000013; width:50px; height:30px; top:500px; left:".(($n-1)*208+20)."px;'>н/д</div>";
	}

	
	if ($node[$index]['dev']==1 || $node[$index]['dev']==2) {
	    $n++;
	    echo "<div id='mppt".$index."' class='mppt'><div class='name'>".$node[$index]['name']."</div><div class
	='ip'>".$node[$index]['ip']."</div><div class='gauges_but but' onclick='gauges(".$index.")'><img src='../img/gauges.png'></div>
	<div id='text_mppt_mode".$index."' class='text_mppt_mode'>н/д</div>
	<div id='text_mppt_vpv".$index."' class='text_mppt_vpv'>н/д</div>
	<div id='text_mppt_wind".$index."' class='text_mppt_wind'>н/д</div>
	<div id='text_mppt_ipv".$index."' class='text_mppt_ipv'>н/д</div>
	<div id='text_mppt_ppv".$index."' class='text_mppt_ppv'>н/д</div>
	<div id='text_mppt_temp".$index."' class='text_mppt_temp'>н/д</div>
	<div id='text_mppt_counter".$index."' class='text_mppt_counter'>н/д</div>
	<div id='text_mppt_pacc".$index."' class='text_mppt_pacc'>н/д</div>
	<div id='led_mppt_error".$index."' class='led mppt_error'></div>
	<div id='led_mppt_relay1".$index."' class='led mppt_relay1'></div>
	<div id='led_mppt_relay2".$index."' class='led mppt_relay2'></div>
	<div id='led_mppt_relay3".$index."' class='led mppt_relay3'></div>

</div>
	

	<div id='arrow_mppt".$index."' class='arrow_down floating' style='left:".(($n-1)*208+98)."px;'></div>
	<div id='mppt_vacc".$index."' style='color:white; font-size:120%; font-weight:700; text-align:center; background-color:#000013; width:60px; height:30px; top:500px; left:".(($n-1)*208+140)."px;'>н/д</div>
	<div id='mppt_acc_temp".$index."' style='color:white; font-size:120%; font-weight:700; text-align:center; background-color:#000013; width:50px; height:30px; top:500px; left:".(($n-1)*208+20)."px;'>н/д</div>";
    }


    }

    
?>





</div>


<div id="mess"></div>
</body>
</html>
