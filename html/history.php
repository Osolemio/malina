<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <script src="./js/jquery-2.1.3.min.js"></script>
   <script src="./js/jquery-migrate-1.2.1.min.js"></script>
      
    <title>Данные по истории</title>
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
<hr><p><center><b>Построение графиков и таблиц по сохраненным данным</b></center></p><hr>
</div>
<form method="post" action="history_hdl.php">
<table border="1" color="black" width="100%">
<tr>    
<td>
<center>
<b>
<p>Дата начала: <input type="date" value=<?php echo $yesterday; ?> name="date_start" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
Дата окончания: <input type="date" value=<?php echo $today; ?> name="date_end"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
Время начала: <input type="time" value=<?php echo $time; ?> name="time_start" pattern="[0-9]{2}:[0-9]{2}">
Время окончания: <input type="time" value=<?php echo $time; ?> name="time_end" pattern="[0-9]{2}:[0-9]{2}"></p>
</b>
</center>
</td>
</tr>
<tr>
<td>
<b>
	<p>Размер графика, pix</p>
<b>
<p>Размер по горизонтали: <input type="number" value="1200" name="width">
  Размер по вертикали: <input type="number" value="800" name="height"></p>

</td>
</tr>

<tr>
<td>	
<b>
	    <p>Строим график по параметру МАП:</p>
</b>	    
<p><input type="radio" name="field" value="_Uacc" /> Напряжение АКБ, В  
<input type="radio" name="field" value="_IAcc_med_A_u16" /> Ток АКБ, А  
<input type="radio" name="field" value="_PLoad" />Мощность по АКБ, Вт 
<input type="radio" name="field" value="_UNET"/> Напряжение сети, В  
<input type="radio" name="field" value="_INET_16_4" />Ток сети, А  
<input type="radio" name="field" value="_PNET" checked/>Мощность по сети, Вт  
<input type="radio" name="field" value="_UOUTmed" />Напряжение МАП, В  
<input type="radio" name="field" value="_Temp_Grad0" />Температура АКБ, C  
</p>
</td>
</tr>	

<tr>
<td>	
	
<b>	    <p>Строим график по параметру MPPT контроллера:</p></b>
<p><input type="radio" name="field" value="Vc_PV" />Напряжение панелей, В   
<input type="radio" name="field" value="Ic_PV" />Ток панелей, А  
<input type="radio" name="field" value="V_Bat" />Напряжение АКБ, В  
<input type="radio" name="field" value="P_PV" />Мощность панелей, Вт  
<input type="radio" name="field" value="P_curr" />Мощность заряда, Вт
<input type="radio" name="field" value="windspeed" />Частота оборотов ВГ, мин<sup>-1</sup>

</p>
<p>

<input type="radio" name="field" value="Energy" />Статистика выработки по дням, кВт*ч (время не учитывается)</p>
</p>

</td>
</tr>

<tr>
<td>

<input type="radio" name="field" value="map_errors" /><b>Ошибки МАП
<input type="radio" name="field" value="mppt_errors" />Ошибки MPPT</b>
<br>


</td>
</tr>


<tr>
<td>
<center>
<input type="submit" style="background-color:lightgreen" value="Поехали!" />
<input type="submit" style="background-color:lightgreen" name="multichart" value="Мультиграф" />  
</center>
</td>			    
</tr>
</table>		

</form>
<br><i>
* Построение графиков занимает много времени и оперирует большими объемами данных. Не рекомендуется задавать большие интервалы времени, особенно на маломощных платформах (Raspberry Pi и т.п.)
<br><br>
* Для повторного просмотра "мультиграф" учитывается только время/дата начала проигрывания данных
<br><br>
</i>

<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" СИСТЕМА " ONCLICK="SystemButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" ТЕКСТ " ONCLICK="TextButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" ПРИБОРЫ " ONCLICK="GaugesButton()"> 

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
<hr><p><center><b>Данные статистики по АКБ</b></center>
<div align="right">
    <input TYPE='button' style='font-weight:bolder; background-color:orange;' VALUE=' Сбросить счетчик пользователя ' ONCLICK='Reset_Counter(1)'> 
&nbsp&nbsp
    <input TYPE='button' style='font-weight:bolder; background-color:orange;' VALUE=' Обнулить все поля ' ONCLICK='Reset_Counter(2)'> 
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
	    <td width='5%'>Самый глубокий разряд %DOD</td>
	    <td width='5%'>Минимальное напряжение, В</td><td width='5%'>Максимальное напряжение, В</td>
	    <td width='5%'>Дата последнего заряда</td><td width='5%'>Кол-во автосинхронизаций</td>
	    <td width='5%'>Суммарно от АКБ, кВтч</td><td width='5%'>Суммарно на АКБ, кВтч</td>
	    <td width='5%'>Альт. за день, кВтч</td><td width='5%'>Альт. за месяц, кВтч</td>
	    <td width='5%'>Альт. всего, кВтч</td><td width='5%'>Альт. пользователь, кВтч</td>
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
* После обнуления новые данные появляются по истечении цикла интегрирования (по умолчанию 1 мин)
	</b>
    <br>
    <input TYPE='button' style='font-weight:bolder; background-color:darkkhaki;' VALUE=' МЕНЮ ' ONCLICK='HomeButton()'> 
    ";
    mysql_free($result);
    mysql_close($db_bat);


?>







<?php } ?>
</body>


</html>