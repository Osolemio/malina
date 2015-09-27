<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
      
    <title>Настройки АКБ</title>
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

<hr><p><center><b>Настройки блока аккумуляторных батарей</b></center></p><hr>
<form method="post" action="battery_set.php">
<table border="1" color="black" width="100%">
<b>
<tr bgcolor="blanchedalmond">
<td>Дата ввода в эксплуатацию: <input type="date" value="<?php if ($row['start_date']=='0000-00-00') echo $today; else echo $row['start_date']; ?>" name="start_date"></td>
<td>&nbspНомер батареи: <input type="number" value="<?php echo $row['Battery_Number']; ?>" name="battery_number" min=1 max=20 disabled></td>
<td>&nbspIP адрес центрального сервера: <input type="text" value="<?php echo $row['SERVER_IP']; ?>" name="server_ip" disabled></td>
</tr>

<tr bgcolor="ivory">
<p>
<td>Номинальная емкость батарейного блока: <input type="number" value="<?php echo $row['C_nominal']; ?>" name="C_nominal" min=10 max=10000>Ач<br> за <input type="number" value="<?php echo $row['t_nominal']; ?>" name="t_nominal" min=1 max=30>часов</td>
<td>&nbspВремя отдыха: <input type="number" value="<?php echo $row['rest_time_min']; ?>" name="rest_time" min=30 max=6000>мин</td>
<td>&nbspТемпературный коэффициент емкости: <input type="number"  value="<?php echo $row['alpha']; ?>" step="0.001" name="battery_alpha" min=0.001 max=0.50></td>
</p>
</tr>
<tr bgcolor="blanchedalmond">
<p>
<td>Число Пейкерта: <input type="number" value="<?php echo $row['n_p']; ?>" step="0.01" name="peukert" min=1.00 max=1.50></td>
<td>&nbspКоэффициент отдачи по емкости: <input type="number" value="<?php echo $row['coulombic_eff']; ?>" step="0.001" name="coulombic_eff" min=0.500 max=1.000></td>
<td>&nbspНоминальное напряжение блока: <input type="number"  value="<?php echo $row['voltage']; ?>" name="voltage">В</td>
</p>
</tr>
<tr>
<tr bgcolor="ivory">
<p>
<td>Напряжение заряда (на блок): <input type="number" value="<?php echo $row['charged_voltage']; ?>" step="0.1" name="charged_voltage">В</td>
<td>&nbspМинимальный ток 100% заряда в течение 2 минут: <input type="number" value="<?php echo $row['min_charged_current']; ?>" step="0.01" name="min_charged_current" min=0.01 max=0.10>xС</td>
<td>&nbspКоличество ячеек (элементов, батарей): <input type="number"  value="<?php echo $row['cells_number']; ?>" name="cells_number" min=1 max=48></td>
</p>
</tr>


<tr>
Разделяемый блок АКБ<input type="checkbox" name="shared" <?php if ($row['SHARED']==1) echo "checked"; ?> >
<br><br>
</tr>
<tr bgcolor="blanchedalmond"><td>
    <select name="battery_type">
        <option disabled selected>Тип батареи</option>

	<option value="current">Текущая (без изменений)</option>
	<option value="USER">Пользовательская</option>
	<option value="LA">Свинцово-кислотная АКБ с жидким электролитом 2В/эл</option>
	<option value="LA6">Свинцово-кислотная АКБ с жидким электролитом 12В/бат</option>
	<option value="AGM">Свинцово-кислотная AGM</option>
	<option value="GEL">Свинцово-кислотная GEL</option>
	<option value="LI37">LiFePo4 Литий-железофосфатная 3.7В</option>
	<option value="LI39">LiFePo4 Литий-железофосфатная 3.9В</option>
    </select>


</td><td>&nbspНе измерять SOC в диапазоне от<input type="number" step=0.001 value="<?php echo $row['U_ocv_invalid_min']; ?>" name="Umin"/>В до <input type="number" step=0.001 value="<?php echo $row['U_ocv_invalid_max']; ?>" name="Umax"/>В</td>
<td>
	&nbsp Источник данных по току MPPT
	<select name="i_source">
	    <option value=0 <?php if ($row['I_source']==0) echo "selected";?>>МАП по шине I2C</option>
	    <option value=1 <?php if ($row['I_source']==1) echo "selected";?>>MPPT RS232</option>
	</select>

</td>
</tr>

<tr bgcolor="ivory">
<p>
<td>Прекращать генерацию при глубине разряда (DOD): <input type="number" value="<?php echo $row['dod_off']; ?>" step="1" min="0" max="100" name="dod_off">%</td>
<td>&nbspПрекращать генерацию при потреблении более: <input type="number" value="<?php echo $row['ah_off']; ?>" step="1" name="ah_off">Ач</td>
<td>&nbspДля предыдущих пунктов - 0 (ноль) - генерация не будет прекращена</td>
</p>
</tr>





<tr>

<center>
<td bgcolor="blue"><input type="submit" name="write" value="Записать" /></td>
<td bgcolor="red"><input type="submit" name="reset_battery" value="Полный сброс (новая АКБ)" />
<input type="submit" name="reset_stat" value="Сбросить статистику АКБ" />
</td>  
<td bgcolor="green"><input type="submit" name="sync_battery" value="Синхронизировать 100%" /></td>  


</center>
</tr>
</table>		

<input type="number" name="C_measured" value="<? echo $row['C_measured']; ?>" hidden />

</form>
<br><br><center>
<hr>Калькулятор числа Пейкерта<hr>
</center>

<p>
Номинальная емкость при <input type="number" value=10 id="t1"> часовом разряде:&nbsp<input type="number" value=100 id="c1">Ач&nbsp
</p>

<p>
Номинальная емкость при <input type="number" value=20 id="t2"> часовом разряде:&nbsp<input type="number" value=110 id="c2">Ач&nbsp
</p>
<p>
<input type="button" value="Рассчитать" onclick="calculate()">
</p>

экспонента Пейкерта:<div id="result">1.00</div>
<hr>

<center>
Текущая рабочая таблица НРЦ (напряжения разомкнутой цепи). В/элемент
</center>
<form action="edit_table.php"><input type="submit" value="Редактировать рабочую таблицу"></form> 
<hr>
<br>


<br>

<table border="1" color="black" width="20%">
<tr bgcolor="lightgreen"><td>% заряда</td><td>В/элемент</td></tr>

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
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>


</body>


</html>