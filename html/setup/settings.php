<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="./settings.css">
  <script src="../js/jquery-1.11.2.min.js"></script>
  <script src="../js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./menu.json"></script>
  <script src="./submenu.json"></script>
  <script src="./settings.js"></script>

  
  <title>Настройки МАП</title>
    
</head>


<body>

<?php

	    $shm=shmop_open(2015,"a",0,0);
	    $str_json=shmop_read($shm,0,1000);
	    $str=substr($str_json,0,strpos($str_json,"}")+1);
    
	    shmop_close($shm);
 
   	 $row = json_decode($str,true);


?>

<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;cursor:pointer;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 

<script>
function HomeButton()
{
location.href="index.php";
}
</script>


<div id='menu_div'>НАСТРОЙКИ МАП
<div id='menu_in'>
<form action="store.php" method="post">

<center>
 <select size=6 id="chapter" name="Menu" style="width:80%; font-weight:bolder; background:aqua;cursor:pointer;">
    <option value=0>Генерация МАП</option>
    <option value=1>Сеть/Генератор, BMS/MPPT</option>
    <option value=2>Сеть/ЭнергЭконом</option>
    <option value=3>Параметры АКБ при заряде</option>
    <option value=4>Доп. реле (только DOMINATOR)</option>
    <option value=5>Другие опции</option>
    </select>
</center>
 <br><br>    
  <select id="menu" style="width:80%; background: ivory;cursor:pointer;"></select>
  <br><br>
  <select id="submenu" style="width:80%; background: ivory;cursor:pointer;"></select>

<input type="hidden" name="mem_offset" id="mem_offset">
<input type="hidden" name="mem_value" id="mem_value">
<input type="hidden" name="mem_class" id="mem_class">
<input type="hidden" name="max_val" id="max_val">
<input type="hidden" name="min_val" id="min_val">
<input type="hidden" name="dop" id="dop">
<input type="hidden" name="eacc" id="eacc">


<div id=field><b>
Текущее значение:
<input type="number" name="field" id="input_field" value="">
<span id="field_units"></span>
<br><br>
<input type="submit" value="Записать" style="padding:1px; font-weight:bolder; font-size:100%; background:green; color:ivory; cursor:pointer;">


</div>

</b>
</form>
<div id=min></div>
<div id=max></div>

</div>
</div>
<b>
</b>
<div id=commands>
<div id=commands_in>
<center><b>

<form action="commands.php" method="post">

<p><input type="submit" name="on" value="включить МАП" style="height: 50px; width: 60%; font-weight:bolder; font-size:130%; background:green; color:ivory; cursor:pointer; border:5px outset gray;" ></p>
<p><input type="submit" name="off" value="выключить МАП" style="height: 50px; width: 60%; font-weight:bolder; font-size:130%; background:red; color:ivory;cursor:pointer; border:5px outset gray;"></p>
<p><input type="submit" name="reset" value="сброс МАП" style="height: 50px; width: 60%; font-weight:bolder; font-size:130%; background:red; color:ivory;cursor:pointer; border:5px outset gray;"></p>
<p><input type="submit" name="charge_start" value="включить заряд" style="height: 50px; width: 60%; font-weight:bolder; font-size:130%; background:darkblue; color:ivory;cursor:pointer; border:5px outset gray;" ></p>
<p><input type="submit" name="charge_stop" value="выключить заряд" style="height: 50px; width: 60%; font-weight:bolder; font-size:130%; background:darkblue; color:ivory;cursor:pointer; border:5px outset gray;"></p>
<p><input type="submit" name="relay_1" value=<?php if ($row['_Relay1']) echo "'Выключить реле 1'"; else echo "'Включить реле 1'";?> style="height: 50px; width: 60%; font-weight:bolder; font-size:90%; background:gray; color:ivory;cursor:pointer; border:5px outset gray;" disabled></p>
<p><input type="submit" name="relay_2" value=<?php if ($row['_Relay2']) echo "'Выключить реле 2'"; else echo "'Включить реле 2'";?> style="height: 50px; width: 60%; font-weight:bolder; font-size:90%; background:gray; color:ivory;cursor:pointer; border:5px outset gray;" disabled></p>
<p><input type="checkbox" name="confirm" value="подтверждение команды" style="left:10px; bottom:10px; height: 15px; width:15px; color:black;font-weight:bolder;" unchecked>Отметьте перед отправкой команды</p>
</form></b>
</center>
</div>
</div>

</body>
</html>
    