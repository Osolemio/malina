<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8"/>
  <link rel="stylesheet" type="text/css" href="gauges.css"/>
  <script src="./js/jquery-2.1.3.min.js"></script>
  <script src="./js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./js/jsmeter-1.1.2.min.js"></script>
  <script src="./js/jsdisplay-1.1.1.min.js"></script>
  <script src="./js/jscounter-1.1.1.min.js"></script>
  <script src="./js/smoothie.js"></script>
  <script src="./js/jqueryrotate.2.1.js"></script>
  <script src="./meters.js"></script>
 
  <title>MAP Monitor. Gauges</title>

    
</head>


<body>

  <div id="header"></div>
  


<div class="wrapper">

<!----------------MAP SECTION------------>

  <?php 
    if (file_exists("/var/map/.map")) {?>

<div id="map">

    <div id="meter_o"><div id="text_v">0</div>
    <div id="meter_v"></div></div>
    
    <div id="meter_o1"><div id="text_i">0</div>
    <div id="meter_i"></div></div>
    
    <div id="meter_o2"><div id="text_vout">0</div>
    <div id="meter_vout"></div></div>

    <div id="meter_o7" onmouseover="tooltip(this,'Суммарный ток на АКБ всех контроллеров, подключенных по шине I2C')" onmouseout="hide_info(this)">
    <div id="text_i2c">0</div>
    <div id="meter_i_i2c"></div></div>
    
    <div class="col1 row1 bor1 black">
    <div id="display_1" onmouseover="tooltip(this,'Частота по входу')" onmouseout="hide_info(this)"></div>
    <div id="display_2" onmouseover="tooltip(this,'Частота по выходу')" onmouseout="hide_info(this)"></div>
    <div id="text1"></div>
    </div>
    
    <div class="col1 row1 bor10 black">
    <div id="display_6" onmouseover="tooltip(this,'Температура тора (ДОМИНАТОР), &degC')" onmouseout="hide_info(this)"></div></div>
    
    <div id="text3"></div>
    
    <div class="col1 row1 bor3 black">
    <div id="display_3" onmouseover="tooltip(this,'Температура транзисторов инвертора, &degC')" onmouseout="hide_info(this)"></div></div>
    
    
    <div id="map_mode"><div id="map_mode_in">
    </div></div>

    <div id="counter1" class="out_1">
    <div class="in"></div></div>
    <div id="counter2" class="out_1">
    <div class="in"></div></div>
    <div id="counter3" class="out_1">
    <div class="in"></div></div>
  
    <div id="text_kw_net"></div>    
    <div id="text_kw_acc"></div>
    <div id="text_kw_charge"></div>
    <div id="smoothie-chart-map-div"> 
    <canvas id="smoothie-chart-map"></canvas></div>
    <div class="relay_map_form1"><div class="relay_container"><div id="map_slider1" class="map_relay_slider"></div></div></div>
    <div class="relay_map_form2"><div class="relay_container"><div id="map_slider2" class="map_relay_slider"></div></div></div>


    <?php if (file_exists("/var/map/.bmon")) {?>
    <div id="alt_text">АЛЬТЕРНАТИВНЫЕ ИСТОЧНИКИ (I2C)</div>
    <div id="alt_energy">
      <table border="1" align="center" width=100%>
	<tr><td>Выработка за день: </td><td id="alt_daily"></td><td>кВтч</td></tr>
	<tr><td>Выработка за месяц: </td><td id="alt_monthly"></td><td>кВтч</td></tr>
	<tr><td>Общая выработка: </td><td id="alt_summary"></td><td>кВтч</td></tr>
	<tr><td>Счетчик пользователя: </td><td id="alt_user"></td><td>кВтч</td></tr>
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
    <div id="display_4" onmouseover="tooltip(this,'Температура батареи, &degC')" onmouseout="hide_info(this)"></div></div>

    <div id="timer" onmouseover="tooltip(this,'Таймер отдыха батареи. После установленного времени будет измерен % заряда')" onmouseout="hide_info(this)"></div>

    <div class="col1 row1 bor8 silver"></div>
    <div class="col1 row1 bor9 silver"></div>
    <div class="col1 row1 bor6 black">
    <div id="battery_100" onmouseover="tooltip(this,'Емкость батареи в %. Расcчитывается с учетом интенсивности разряда и экспоненты Пейкерта.')" onmouseout="hide_info(this)"><div id="text_bmon"></div></div></div>

    <div class="col1 row1 bor7 black">
    <div id="battery_real" onmouseover="tooltip(this,'Реальный расход емкости в Ач. Емкость C<sub>20</sub> c учетом температуры. При разряде - приведенная по среднему току разряда')" onmouseout="hide_info(this)"><div id="text_bmon_real"></div></div></div>

    

    <div id="text4"></div>
<!--    <div id="text_timer"></div> -->
    <div id="text_c_measured"></div>

    <div id="bms"><div id="bms_in" onmouseover="tooltip(this,'Пороговые значения BMS')" onmouseout="hide_info(this)">
    <div id="bms_tmin" class="bms_in_container"></div>
    <div id="bms_tmax" class="bms_in_container"></div>
    <div id="bms_umin" class="bms_in_container"></div>
    <div id="bms_umax" class="bms_in_container"></div>
    
    <div class="bms_in_text">t<sub>min</sub>&degC</div>
    <div class="bms_in_text">t<sub>max</sub>&degC</div>
    <div class="bms_in_text">U<sub>min</sub>,В</div>
    <div class="bms_in_text">U<sub>max</sub>,В</div>
    </div></div>
    
    <div id="text_common">Общие данные:</div>

	<div id="powers_back">
	<div id="text_power"></div></div>    

    <div id="powers">
  
        <div id="text_pacc"></div>
	<div id="text_ppv"></div>
	<div id="power_net" onmouseover="tooltip(this,'Мощность МАП AC')" onmouseout="hide_info(this)"></div>
	<div id="text_pnet"></div>
	<div id="power_acc" onmouseover="tooltip(this,'Мощность по АКБ')" onmouseout="hide_info(this)"></div>
	<div id="power_pv" onmouseover="tooltip(this,'Мощность по контроллерам')" onmouseout="hide_info(this)"></div></div>

	<div id="map_err"><div id="map_err_in"></div>
        <div id="text_err"></div>
	</div>    
	<div id="smoothie-chart-acc-div"> 
	<canvas id="smoothie-chart-acc"></canvas></div>

	<div id="estimated_data">
	
	<div id="soc" onmouseover="tooltip(this,'Оценка уровня заряда батареи по НРЦ')" onmouseout="hide_info(this)"></div>
	<div id="est_time" onmouseover="tooltip(this,'Прогнозное время работы')" onmouseout="hide_info(this)"></div>
	<div id="est_c" onmouseover="tooltip(this,'Последняя расчетная емкость батареи')" onmouseout="hide_info(this)"></div>
	<div id="user_counter" onmouseover="tooltip(this,'Счетчик пользователя. Клик для сброса')" onmouseout="hide_info(this)"></div>
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

    <div id="meter_o5" onmouseover="tooltip(this,'Напряжение панелей')" onmouseout="hide_info(this)"><div id="text_vpv"></div>
    <div id="meter_vpv"></div></div>
    
    <div id="meter_o6" onmouseover="tooltip(this,'Ток панелей')" onmouseout="hide_info(this)"><div id="text_ipv"></div>
    <div id="meter_ipv"></div></div>
    
    <div class="col1 row1 bor5 black">
    <div id="display_5" onmouseover="tooltip(this,'Температура контроллера, &degC')" onmouseout="hide_info(this)"></div></div>
    
    <div id="text5"></div>

    

    <div id="counter4" class="out_2">
    <div class="in"></div></div>
    <div id="text_kw_pv"></div>
    <div id="mppt_mode">
    <div id="mppt_o" onmouseover="tooltip(this,'Режим работы MPPT. s-сканирование, b,B-буферный, i-заряд, v-дозаряд.<br> + нехватка энергии, - избыток энергии')" onmouseout="hide_info(this)"><div id="mppt_text"></div></div>
    <div id="mppt_relay" onmouseover="tooltip(this,'Реле контроллера.')" onmouseout="hide_info(this)">
    
    <div id="mppt_relay1" class="mppt_relay_slider slider1"></div>
    <div id="mppt_relay2" class="mppt_relay_slider slider2"></div>
    <div id="mppt_relay3" class="mppt_relay_slider slider3"></div>
    <div id="on">ВКЛ</div><div id="off">ВЫКЛ</div>
    <div class="mppt_relay_guide guide1"></div><div class="mppt_relay_guide guide2"></div><div class="mppt_relay_guide guide3"></div>
    </div>
    </div>
    <div id="smoothie-chart-mppt-div"> 
    <canvas id="smoothie-chart-mppt"></canvas></div>

    <div id="wind">
    
    <div id="smoothie-chart-wind-div"> 
    <canvas id="smoothie-chart-wind" height="100"></canvas></div>
    
    <div id="propeller"><img src="./img/wind.gif" id="ventilator" height="140%" /></div>
    <div id="speed">0 мин<sup>-1</sup></div>

    </div>





    </div>

  <?php }?>



</div>

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="menu.php">МЕНЮ</a></li>
      <li><a href="index.php">ТЕКСТ</a></li>
      <li><a href="graph.php">МОЩНОСТИ</a></li>
      <li><a href="history.php">ИСТОРИЯ</a></li>
      <li><a href="multi_select.php">МУЛЬТИГРАФ</a></li>
      <li><a href="bms.php">BMS</a></li>
      <li><a href="settings.php">ИНФО</a></li>
      <li><a href="./setup/index.php">СИСТЕМА</a></li>
      <li><a href="./setup/settings.php">СИСТЕМА-МАП</a></li>
      <li><a href="./setup/sys.php">СИСТЕМА-СЕРВИСЫ</a></li>
    
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
    