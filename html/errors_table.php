<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8"/>
 
  <title>Errors history table</title>

    
</head>
<style>
table {table-layout:fixed}

</style>
<body>


<?php
    session_start();
    include("./bd.php");
    $query = "SELECT * FROM ".$_SESSION['table']." WHERE number BETWEEN'".$_SESSION['number_low']."' AND '".$_SESSION['number_high']."'\n";
        $result=mysql_query($query) or die("Query failed date/time start:".mysql_error());
	$data_map=array(
	    '_RSErrJobM'=>array(
			'АКБ полностью разряжен, напряжение критически низкое для работы МАП.',
			'Превышение напряжения на АКБ, генерация или заряд временно приостановлен.',
			 'Ток КЗ по АКБ при заряде, заряд временно приостановлен.',
			 'Ток КЗ по АКБ, генерация или заряд временно приостановлен.',
			 'Возможно залипло основное реле, необходим ремонт.',
			 'Ток КЗ по сети 220В, произойдет переход на генерацию с возможным дальнейшим отключением по КЗ АКБ.',
			 'На выходе 220В (но не на входе сети 220В) постороннее напряжение, генерация будет отключена.',
			 'Произошел сброс программы, возможно сильная помеха от грозы и т.д.'
			    ),
	    '_RSErrJob'=>array(
			'АКБ полностью разряжен, МАП будет работать еще 1 минуту и отключит генерацию, пока напряжение на АКБ не станет выше EE_LCD_UAccMinNetGen =12,5в (заводское) или не перейдет на заряд.',
			'Перегрузка по АКБ, генерация продолжится в течении 10сек (отсчет времени в ячейке _T_Overload). После чего произойдет отключение на 10сек. (_TOff_Overload) и так не более 10 раз в течении 10мин.',
			'Нагрузка выше номинальной мощности – будем работать ограниченное время (30 мин для Pro модели). Отсчет времени работы в ячейке _T_Nominal.',
			'Превышение температуры, приостановка генерации или заряда.',
			'Вентилятор не крутится, отрабатывает аналогично перегрузке ErrJ_PowAccMax.',
			'Мощность перегрузки по сети, переход на генерацию.',
			'Был сбой в режиме работы (например по сбросу питания или сильной помехи).',
			'Выключились по многократным КЗ по заряду, через 2ч снимаем ошибку.'
			    ),
	    '_RSWarning'=>array(
			'Нет действия для комбинации кнопка (“СТАРТ” и “ЗАРЯД”)',
			'Сетевое напряжение вышло за пределы.',
			'Есть постороннее напряжение на выходной розетке или большие выбросы напряжения от нагрузки.',
			'Выбросы напряжения в сети 220В по входу.',
			'Залипла кнопка (“СТАРТ” или “ЗАРЯД”).',
			'Переход на заряд не возможен - нет сети на входе.',
			'В режиме трансляции сети и возможно заряда, мощность нагрузки за пределами выставленной максимальной мощности (EE_LCD_NetMaxPow), но меньше мощности МАР. Произойдет переход на генерацию (кроме режима подкачки).',
			'Сеть нестабильна (по напряжению или частоте), не будет перехода на трансляцию сети или выход из заряда если насчитали много нестабильностей'
			    ),
	    '_I2C_Err'=>array(
			'I2C Err Ack',
			'I2C Err Sum',
			'I2C Err Data Size',
			'I2C Err Protocol',
			'н/о',
			'н/о',
			'н/о',
			'н/о'
			),
	    '_RSErrDop'=>array(
			'Неправильные данные от предыдущей фазы или нет связи вообще.',
			'Вышли за окно синхронизации сдвига между фазами в 60 град.',
			'н/о',
			'н/о',
			'н/о',
			'н/о',
			'н/о',
			'н/о'
			    ),
	    '_F_AccOver'=>array(
			'Отключение по перегрузке по току АКБ, 10 попыток перезапуска.',
			'Отключение по перегрузке по току АКБ во время заряда, 10 попыток перезапуска.',
			'Отключение по перегрузке по S_UAccLow - критически низкое напряжение АКБ, снимается при восcтановлении напряжения на АКБ',
			'Отключение по неисправности вентилятора, 10 попыток перезапуска.',
			'Отключение по мощности выше номинальной более 30мин - снимается через 30мин',
			'Отключение по полному разряду АКБ - снимаем по достижению напряжения на АКБ выше EE_LCD_UAccMinNetGen =12,5в (заводское) или при переходе на заряд',
			'Отключение по выходу за пределы датчика температуры, снимается при остывании',
			'Полное отключение по многократным перегрузкам по АКБ, снимается персоналом кнопкой “СТАРТ”'
			    ),
	'_F_NETOver'=>array(
			'Отключение генерации - на выходе постороннее напряжение или неисправное реле. Либо не правильное подключение проводов 220В либо ремонт.',
			'Переход на генерацию - перегрузка по сети (превышена максимальная мощность EE_LCD_NetMaxPow порядка 1,5 максимальной мощности МАП) , снимается после исчезновения сети со входа но не более 10 раз в течении 10 мин.',
			'н/о',
			'Выключение по многократным перегрузкам по сети, снимается персоналом кнопкой “СТАРТ”',
			'н/о',
			'н/о',
			'н/о',
			'н/о'
			    )

    
	);

	    $data_mppt=array(
	     '_RSErrSis'=>array(
			'Перенапряжение панелей',
			'Перенапряжение АКБ',
			'Перезаряд АКБ',
			'Короткое замыкание АКБ',
			'Перегрев АКБ',
			'Ошибка связи I2C',
			'н/о',
			'н/о'
			    )

	);

	   $legend=array('map_errors'=>'МАП', 'mppt_errors'=>'MPPT');
?>
<b>
<hr>
<center>История ошибок <?php echo $legend[$_SESSION['table']]; ?></center>
<hr>

<table align="center" width="100%" bgcolor="ivory" border="2px" cellpadding="2px">
<tr bgcolor="blanchedalmond"><td>Дата</td><td>Время</td><td>_RSErrSis</td>
<?php
    if ($_SESSION['table']=='map_errors')

	 echo "<td>_RSErrJobM</td><td>_RSWarning</td><td>_I2CErr</td><td>_RSErrDop</td><td>Состояние по перегрузке АКБ</td><td>Состояние по перегрузке по сети 220</td><td>Частота вне диапазона, Гц</td><td>Напряжение вне диапазона, В</td></tr></b>";
else echo "</tr></b>";


	

	while ($row=mysql_fetch_assoc($result))
	    {
	echo "<tr><td>".$row['date']."</td><td>".$row['time']."</td><td>";
	
          if ($_SESSION['table']=='mppt_errors')
	    {
	      echo "Value=".$row['_RSErrSis']."&nbsp";    
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrSis']>>$i)&1)>0) echo $data_mppt['_RSErrSis'][$i].",<br>";
	      echo "</td>";
	    }
	
	  if ($_SESSION['table']=='map_errors')	     
	    {
	     echo "Value=".$row['_RSErrSis']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrSis']>>$i)&1)>0) echo $data_map['_RSErrSis'][$i].",<br>";
	     echo "</td><td>";
	    
	    echo "Value=".$row['_RSErrJobM']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrJobM']>>$i)&1)>0) echo $data_map['_RSErrJobM'][$i].",<br>";
	     echo "</td><td>";

	    echo "Value=".$row['_RSWarning']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSWarning']>>$i)&1)>0) echo $data_map['_RSWarning'][$i].",<br>";
	     echo "</td><td>";

	    echo "Value=".$row['_I2C_Err']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_I2C_Err']>>$i)&1)>0) echo $data_map['_I2C_Err'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_RSErrDop']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_RSErrDop']>>$i)&1)>0) echo $data_map['_RSErrDop'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_F_AccOver']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_F_AccOver']>>$i)&1)>0) echo $data_map['_F_AccOver'][$i].",<br>";
	     echo "</td><td>";

	echo "Value=".$row['_F_NETOver']."&nbsp";
	     for ($i=0;$i<8;$i++)  if ((($row['_F_NETOver']>>$i)&1)>0) echo $data_map['_F_NETOver'][$i].",<br>";
	     echo "</td><td>";


	echo "Value=".round(2500/$row['_TFNET_Limit'],1)."&nbsp</td><td>";
	echo "Value=".$row['_UNET_Limit']."&nbsp</td>";



	    }
	
	echo "</tr>";
	    }


    mysql_free_result($result);
    mysql_close($db);
?>
</table>
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


</body>
</html>