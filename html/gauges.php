<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8"/>
  <link rel="stylesheet" type="text/css" href="gauges.css"/>
 <?php include('./local/local.inc');?>
  
  <script src="./js/jquery-2.1.3.min.js"></script>
  <script src="./js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./js/jsmeter-1.1.2.min.js"></script>
  <script src="./js/jsdisplay-1.1.1.min.js"></script>
  <script src="./js/jscounter-1.1.1.min.js"></script>
  <script src="./js/smoothie.js"></script>
  <script src="./js/jqueryrotate.2.1.js"></script>
  <script src="./local/local_js.js"></script>
  <script src="./meters.js"></script>
 
  <title><?php loc('gauges_title'); ?></title>

    
</head>


<body>

  <div id="header"></div>
  


<div class="wrapper">

<!----------------MAP SECTION------------>

  <?php 
    if (file_exists("/var/map/.map")) {?>

<div id="map">

    <div id="meter_o"><div id="text_v">0</div><div id="input1" onmouseover="tooltip(this,'<?php loc('tooltip8');?>')" onmouseout="hide_info(this)">1
    </div><div id="input2" onmouseover="tooltip(this,'<?php loc('tooltip9');?>')" onmouseout="hide_info(this)">2</div>
    <div id="meter_v"></div></div>
    
    <div id="meter_o1"><div id="text_i">0</div>
    <div id="meter_i"></div></div>
    
    <div id="meter_o2"><div id="text_vout">0</div>
    <div id="meter_vout"></div></div>

    <div id="meter_o7" onmouseover="tooltip(this,'<?php loc('tooltip25');?>')" onmouseout="hide_info(this)">
    <div id="text_i2c">0</div>
    <div id="meter_i_i2c"></div></div>
    
    <div class="col1 row1 bor1 black">
    <div id="display_1" onmouseover="tooltip(this,'<?php loc('tooltip1');?>')" onmouseout="hide_info(this)"></div>
    <div id="display_2" onmouseover="tooltip(this,'<?php loc('tooltip2');?>')" onmouseout="hide_info(this)"></div>
    <div id="text1"></div>
    </div>
    
    <div class="col1 row1 bor10 black">
    <div id="display_6" onmouseover="tooltip(this,'<?php loc('tooltip3');?>, &degC')" onmouseout="hide_info(this)"></div></div>
    
    <div id="text3"><?php loc('MAC');?></div>
    
    <div class="col1 row1 bor3 black">
    <div id="display_3" onmouseover="tooltip(this,'<?php loc('tooltip26');?>, &degC')" onmouseout="hide_info(this)"></div></div>
    
    
    <div id="map_mode"><div id="map_mode_in">
    </div></div>

    <div id="counter1" class="out_1">
    <div class="in"></div></div>
    <div id="counter2" class="out_1">
    <div class="in"></div></div>
    <div id="counter3" class="out_1">
    <div class="in"></div></div>
  
    <div id="text_kw_net"><?php loc('ENET');?></div>    
    <div id="text_kw_acc"><?php loc('EACC');?></div>
    <div id="text_kw_charge"><?php loc('ECHG');?></div>
    <div id="smoothie-chart-map-div"> 
    <canvas id="smoothie-chart-map"></canvas></div>
    <div class="relay_map_form1" onmouseover="tooltip(this,'<?php loc('tooltip16');?>')" onmouseout="hide_info(this)"><div id="relay_container1" class="relay_container"><div id="map_slider1" class="map_relay_slider"></div></div></div>
    <div class="relay_map_form2" onmouseover="tooltip(this,'<?php loc('tooltip17');?>')" onmouseout="hide_info(this)"><div id="relay_container2" class="relay_container"><div id="map_slider2" class="map_relay_slider"></div></div></div>


    <?php if (file_exists("/var/map/.bmon")) {?>
    <div id="alt_text"><?php loc('ALT_SRC');?></div>
    <div id="alt_energy">
      <table border="1" align="center" width=100%>
	<tr><td><?php loc('E_day');?>: </td><td id="alt_daily"></td><td><?php loc('kWh');?></td></tr>
	<tr><td><?php loc('E_month');?>: </td><td id="alt_monthly"></td><td><?php loc('kWh');?></td></tr>
	<tr><td><?php loc('E_total');?>: </td><td id="alt_summary"></td><td><?php loc('kWh');?></td></tr>
	<tr><td><?php loc('U_counter');?>: </td><td id="alt_user"></td><td><?php loc('kWh');?></td></tr>
     </table>
    </div>
    <?php } ?>

</div>


  <?php }?>

<!---------------ACC SECTION------------->
    <div id="acc">
    
    <div id="meter_o3"><div id="text_vacc"></div>
    <div id="meter_vacc"></div></div>
    
    <div id="meter_o4"><div id="text_iacc"></div>
    <div id="meter_iacc"></div></div>
    
    <div class="col1 row1 bor4 black">
    <div id="display_4" onmouseover="tooltip(this,'<?php loc('tooltip6');?>, &degC')" onmouseout="hide_info(this)"></div></div>

    <div id="timer" onmouseover="tooltip(this,'<?php loc('tooltip15');?>')" onmouseout="hide_info(this)"></div>

    <div class="col1 row1 pin bor8 silver"></div>
    <div class="col1 row1 pin bor9 silver"></div>
    <div class="col1 row1 bat bor6 black">
    <div id="battery_100" onmouseover="tooltip(this,'<?php loc('tooltip4');?>')" onmouseout="hide_info(this)"><div id="text_bmon"></div></div></div>

    <div class="col1 row1 bat bor7 black">
    <div id="battery_real" onmouseover="tooltip(this,'<?php loc('tooltip5');?>')" onmouseout="hide_info(this)"><div id="text_bmon_real"></div></div></div>

    

    <div id="text4"><?php loc('ACC');?></div>
<!--    <div id="text_timer"></div> -->
    <div id="text_c_measured"></div>

    <div id="bms"><div id="bms_in" onmouseover="tooltip(this,'<?php loc('tooltip12');?>')" onmouseout="hide_info(this)">
    <div id="bms_tmin" class="bms_in_container"></div>
    <div id="bms_tmax" class="bms_in_container"></div>
    <div id="bms_umin" class="bms_in_container"></div>
    <div id="bms_umax" class="bms_in_container"></div>
    
    <div class="bms_in_text">t<sub>min</sub>&degC</div>
    <div class="bms_in_text">t<sub>max</sub>&degC</div>
    <div class="bms_in_text"><?php loc('U');?><sub>min</sub>,<?php loc('V');?></div>
    <div class="bms_in_text"><?php loc('U');?><sub>max</sub>,<?php loc('V');?></div>
    </div></div>
    
    <div id="text_common"><?php loc('Common_data');?>:</div>

	<div id="powers_back">
	<div id="text_power"></div></div>    

    <div id="powers">
  
        <div id="text_pacc"></div>
	<div id="text_ppv"></div>
	<div id="power_net" onmouseover="tooltip(this,'<?php loc('tooltip22');?>')" onmouseout="hide_info(this)"></div>
	<div id="text_pnet"></div>
	<div id="power_acc" onmouseover="tooltip(this,'<?php loc('tooltip23');?>')" onmouseout="hide_info(this)"></div>
	<div id="power_pv" onmouseover="tooltip(this,'<?php loc('tooltip24');?>')" onmouseout="hide_info(this)"></div></div>

	<div id="map_err"><div id="map_err_in"></div>
        <div id="text_err"><?php loc('Err_Alerts');?></div>
	</div>    
	<div id="smoothie-chart-acc-div"> 
	<canvas id="smoothie-chart-acc"></canvas></div>

	<div id="estimated_data">
	
	<div id="soc" onmouseover="tooltip(this,'<?php loc('tooltip18');?>')" onmouseout="hide_info(this)"></div>
	<div id="est_time" onmouseover="tooltip(this,'<?php loc('tooltip19');?>')" onmouseout="hide_info(this)"></div>
	<div id="est_c" onmouseover="tooltip(this,'<?php loc('tooltip20');?>')" onmouseout="hide_info(this)"></div>
	<div id="user_counter" onmouseover="tooltip(this,'<?php loc('tooltip21');?>')" onmouseout="hide_info(this)"></div>
	<div class="under index1">SOC</div>
	<div class="under index2">TIME</div>
	<div class="under index3">C</div>
	<div class="under index4">COUNTER</div>
	</div>

	
    </div>

    
<!----------------MPPT SECTION------------>

  <?php 
    if (file_exists("/var/map/.mppt")) {?>

    <div id="mppt">

    <div id="meter_o5" onmouseover="tooltip(this,'<?php loc('tooltip10');?>')" onmouseout="hide_info(this)"><div id="text_vpv"></div>
    <div id="meter_vpv"></div></div>
    
    <div id="meter_o6" onmouseover="tooltip(this,'<?php loc('tooltip11');?>')" onmouseout="hide_info(this)"><div id="text_ipv"></div>
    <div id="meter_ipv"></div></div>
    
    <div class="col1 row1 bor5 black">
    <div id="display_5" onmouseover="tooltip(this,'<?php loc('tooltip7');?>, &degC')" onmouseout="hide_info(this)"></div></div>
    
    <div id="text5"><?php loc('MPPT');?></div>

    

    <div id="counter4" class="out_2">
    <div class="in"></div></div>
    <div id="text_kw_pv"></div>
    <div id="mppt_mode">
    <div id="mppt_o" onmouseover="tooltip(this,'<?php loc('tooltip13');?>')" onmouseout="hide_info(this)"><div id="mppt_text"></div></div>
    <div id="mppt_relay" onmouseover="tooltip(this,'<?php loc('tooltip14');?>')" onmouseout="hide_info(this)">
    
    <div id="mppt_relay1" class="mppt_relay_slider slider1">1</div>
    <div id="mppt_relay2" class="mppt_relay_slider slider2">2</div>
    <div id="mppt_relay3" class="mppt_relay_slider slider3">3</div>
    <div id="on"><?php loc('ON');?></div><div id="off"><?php loc('OFF');?></div>
    <div class="mppt_relay_guide guide1"></div><div class="mppt_relay_guide guide2"></div><div class="mppt_relay_guide guide3"></div>
    </div>
    </div>
    <div id="smoothie-chart-mppt-div"> 
    <canvas id="smoothie-chart-mppt"></canvas></div>

    <div id="cs1_text" class="cs1"><?php loc('cs1');?></div>
    <div id="cs1" class="cs1">
    <canvas id="cs1_ring"></canvas></div>
    <div id="cs2_text" class="cs2"><?php loc('cs2');?></div>
    <div id="cs2" class="cs2">
    <canvas id="cs2_ring"></canvas></div>
    <div id="cs1_val" class="cs_val">0A</div>
    <div id="cs2_val" class="cs_val">0A</div>

    <div id="wind">
    
    <div id="smoothie-chart-wind-div"> 
    <canvas id="smoothie-chart-wind" height="100"></canvas></div>
    
    <div id="propeller"><img src="./img/wind.gif" id="ventilator" height="140%" /></div>
    <div id="speed">0 <?php loc('min');?><sup>-1</sup></div>

    </div>





    </div>

  <?php }?>



</div>

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="menu.php"><?php loc('MENU');?></a></li>
      <li><a href="index.php"><?php loc('TEXT');?></a></li>
      <li><a href="graph.php"><?php loc('POWERS');?></a></li>
      <li><a href="history.php"><?php loc('HISTORY');?></a></li>
      <li><a href="multi_select.php"><?php loc('MULTICHART');?></a></li>
      <li><a href="bms.php"><?php loc('BMS');?></a></li>
      <li><a href="settings.php"><?php loc('INFO');?></a></li>
      <li><a href="./setup/index.php"><?php loc('SYSTEM');?></a></li>
      <li><a href="./setup/settings.php"><?php loc('SYSTEM');?>-<?php loc('MAC');?></a></li>
      <li><a href="./setup/sys.php"><?php loc('SYSTEM');?>-<?php loc('SERVICES');?></a></li>
    
    </ul>
  </div>
  <div class="menu-switch">
    <label class="collapse" for="menu-collapsed">«</label>
    <label class="rise" for="menu-collapsed">»</label>
  </div>
</div>

<div id="mess" position="absolute" style="z-index:10"></div>
</body>
</html>
    