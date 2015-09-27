<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" href="dist/themes/default/style.min.css" />
  <script src="dist/libs/jquery.js"></script>
  <script src="dist/jstree.min.js"></script>  
  <script src="nodes.js"></script>
<title>НАСТРОЙКИ УЗЛОВ</title>
    
</head>


<body>
<fieldset>
<div id="nodes"></div>
</fieldset>
<br>
<fieldset>
<div id="fields">
    <b>Имя узла:</b>&nbsp<input type="text" value="Имя узла" id="name"/>
    <b>IP адрес [:порт]. По умолчанию:80</b>&nbsp<input type="text" value="IP адрес узла" id="ip"/>
    <b>Номер АКБ:</b>&nbsp<input type="text" value="Номер АКБ" id="acc_number"/>
    <b>Подключено:</b>&nbsp<select size=1 id="devices" name="оборудование">
	<option value="0">МАП</option>
	<option value="1">MPPT</option>
	<option value="2">МАП+MPPT</option>
    </select> 
</div>

</fieldset>
<br>
<div><input type="button" style="background-color:lightgreen;" value="Создать узел" onclick=create_node() />

&nbsp&nbsp<input type="button" style="background-color:orange;" value="Удалить узел" onclick=delete_node() />

&nbsp&nbsp<input type="button" style="background-color:lightblue;" value="Сохранить" onclick=save() /></div>
<br>
<div><input type="button" style="background-color:blanchedalmond;" value="Свернуть все" onclick=close_all() />
&nbsp&nbsp
<input type="button" style="background-color:blanchedalmond;" value="Раскрыть все" onclick=open_all() />

</div>
<br><br>
<div>
<fieldset>
    <b>IP адрес [:порт] узла батарейного монитора:</b>&nbsp<input type="text" value="127.0.0.1" id="batmon_ip"/>
    <b>Активен:</b>&nbsp<select size=1 id="batmon_active" name="Активен">
	<option value="0">НЕТ</option>
	<option value="1">ДА</option>
    </select>

</fieldset>
<br>
<input type="button" style="background-color:lightblue;" value="Сохранить" onclick=save_batmon() />
</div>
<br><br>
<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>
</div>



</body>
</html>