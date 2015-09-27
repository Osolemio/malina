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


<!--<a href="index.php"><div id="arrow"></div></a> !-->
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>


<div id='menu_div'>
<div id='menu_in'>
<form action="store.php" method="post">

<center>
 <select size=6 id="chapter" name="Menu">
    <option value=0>Генерация МАП</option>
    <option value=1>Сеть/Генератор, BMS/MPPT</option>
    <option value=2>Сеть/ЭнергЭконом</option>
    <option value=3>Параметры АКБ при заряде</option>
    <option value=4>Доп. реле (только DOMINATOR)</option>
    <option value=5>Другие опции</option>
    </select>
</center>
 <br><br>    
  <select id="menu"></select>
  <br><br>
  <select id="submenu"></select>

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
<br><br>
<input type="submit" value="Записать">
</div>
</b>
</form>
</div>
</div>
<b>
<div id=min></div>
<div id=max></div>
</b>
<div id=commands>
<div id=commands_in>
<center><b>
КОМАНДЫ МАП:
<form action="commands.php" method="post">

<p><input type="submit" name="on" value="включить МАП" ></p>
<p><input type="submit" name="off" value="выключить МАП" ></p>
<p><input type="submit" name="reset" value="сброс МАП" ></p>
<p><input type="submit" name="charge_start" value="включить заряд" ></p>
<p><input type="submit" name="charge_stop" value="выключить заряд" ></p>

</form></b>
</center>
</div>
</div>

</body>
</html>
    