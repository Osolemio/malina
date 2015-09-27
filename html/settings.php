<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  
  <title>ИНФОРМАЦИЯ О МОДЕЛИ</title>
    
</head>


<body>



<?php


   include ("./request_settings.php");
    $power=array("1,3","1,5","2","3","4,5","6","9","12","15","18","24","36");
    $voltage=array(12,24,48,96);
    $sin=array("Чистый синус","Оптимальный синус","Pmax","Прецизионный синус");
    $phase=array("Однофазная работа","Ведущий/фаза1","Фаза 2","Фаза 3", "Ведомый");
    $eco=array("Нет подкачки. При ЭКО - генерация от АКБ","Включен","Продажа в сеть");
    $onoff=array("Выключено", "Включено");
    $phase_mode=array("Независимая работа трех фаз","Синхронная (зависимая) работа трех фаз", "Отключено");
    $net=array("Промышленная сеть", "Прочие электростанции (генератор)");
    $ext=array("Нет внешних устройств","BMS &microART&copy", "MPPT &microART&copy", "BMS+MPPT &microART&copy");
    $net_mode=array("Трансляция+заряд","Только трансляция","Принудительная генерация ЭКО","Тарифная сеть");
    $acc_type=array("Кислотный","Гелевый/AGM","AGM Shoto","Кислотный Trojan","Литий-ионный LYP 3.9В","Литий-ионный LYP 3.7В");
    $charge=array("Двуступенчатый. С постоянным током","Трехступенчатый: 2 ступени постоянным током + дозаряд","Трехступенчатый: 2 постоянным током + буферный", "Четырехступенчатый: Две ступени постоянным током + дозаряд + буферный");
?>

<table border="2" align="left">
<tr bgcolor="lightskyblue"><td><b>ИНФОРМАЦИЯ О НАСТРОЙКАХ ИНВЕРТОРА МАП</b></td><td><a href="./menu.php"><b>МЕНЮ</b></a></td></tr>
<tr><td>ВЕРСИЯ МОДЕЛИ СИЛОВОЙ ПЛАТЫ:</td><td>&nbsp<?php echo $row[0x01][1]^0xC0; if ($row[0x01][1]&0x80) echo "&nbspГИБРИД";?></td></tr>
<tr><td>ВЕРСИЯ ПО:</td><td>&nbsp<?php $i=$row[0x02][1]&0x1F;$i1=$row[0x02][1]>>5;echo $i.".".$i1;?></td></tr>
<tr><td>Мощность устройства:</td><td>&nbsp<?php echo $power[$row[0x05][1]]."кВт"; ?></td></tr>
<tr><td>Рабочее напряжение:</td><td>&nbsp<?php echo $voltage[$row[0x06][1]]."В"; ?></td></tr>
<tr><td>Версия платы с процессором:</td><td>&nbsp<?php echo $row[0x0C][1]; ?></td></tr>
<tr><td>Датчики температуры:</td><td>&nbsp<?php if ($row[0x51][1]&1) echo "Окружающей среды,";if ($row[0x51][1]&2) echo "&nbspТора,"; if ($row[0x51][1]&1) echo "&nbspТранзисторов.";?></td></tr>
<tr><td>Количество вентиляторов:</td><td>&nbsp<?php echo $row[0x52][1]; ?></td></tr>
<tr><td>Минимальный ток дозаряда:</td><td>&nbsp<?php $i=$row[0x65][1]/100; echo $i."C"; ?></td></tr>
<tr><td>Время работы в дозаряде при активировании BMS:</td><td>&nbsp<?php echo $row[0x66][1]."&nbspмин"; ?></td></tr>
<tr><td>Номинальная мощность:</td><td>&nbsp<?php echo $row[0x68][1]."% от максимальной"; ?></td></tr>
<tr><td>Количество попыток старта при перегрузке:</td><td>&nbsp<?php echo $row[0x73][1]; ?></td></tr>
<tr><td>Задержка перехода на заряд после появлении сети и разряженной АКБ:</td><td>&nbsp<?php echo $row[0x78][1]."&nbspс"; ?></td></tr>
<tr><td>Время на стабилизацию при появлении сети с подстанции:</td><td>&nbsp<?php echo $row[0x7B][1]."&nbspс"; ?></td></tr>
<tr><td>Максимальное время работы в режиме дозаряда:</td><td>&nbsp<?php echo $row[0x7E][1]."&nbspмин"; ?></td></tr>
<tr><td>Задержка перехода на сеть в ЭКО режиме:</td><td>&nbsp<?php echo $row[0x7F][1]."&nbspмин"; ?></td></tr>
<tr><td>Время работы на разряженной АКБ:</td><td>&nbsp<?php echo $row[0x80][1]."&nbspс"; ?></td></tr>
<tr><td>Питание +12В на разъеме модема. Vdd:</td><td>&nbsp<?php if ($row[0xFE][1]&1) echo "Включено всегда,"; else echo "В зависимости от режима"; ?></td></tr>
<tr><td>Точность синуса:</td><td>&nbsp<?php echo $sin[$row[0x138][1]]; ?></td></tr>
<tr><td>Трехфазная работа:</td><td>&nbsp<?php if ($row[0x139][1]==255) echo "Нет платы. 1 фаза."; else echo $phase[$row[0x139][1]]; ?></td></tr>
<tr><td>Выходное напряжение генерации:</td><td>&nbsp<?php $i=$row[0x13A][1]+100; echo $i."В"; ?></td></tr>
<tr><td>Подкачка сети Pmax:</td><td>&nbsp<?php if ($row[0x13B][1]==0) echo "Выключена";if ($row[0x13B][1]==1) echo "Включена";?></td></tr>
<tr><td>ЭКО режим:</td><td>&nbsp<?php if ($row[0x13C][1]==255) echo "Отсуствует в настройках"; else echo $eco[$row[0x13C][1]];?></td></tr>
<tr><td>Минимальное напряжение для рабоы от АКБ:</td><td>&nbsp<?php $i=(($row[0x13D][1]<<$row[0x06][1]) + $row[0x102][1])/10; echo $i."В"; ?></td></tr>
<tr><td>Включение генерации по нагрузке:</td><td>&nbsp<?php $i=$row[0x13E][1]; if ($i==0) echo "Выключено"; else echo $i."Вт"; ?></td></tr>
<tr><td>Совместная работа в трехфазной системе:</td><td>&nbsp<?php if ($row[0x13F][1]==255) echo $phase_mode[2]; else echo $phase_mode[$row[0x13F][1]]; ?></td></tr>
<tr><td>Источник сети на входе:</td><td>&nbsp<?php echo $net[$row[0x150][1]]; ?></td></tr>
<tr><td>Внешние устройства:</td><td>&nbsp<?php echo $ext[$row[0x156][1]]; ?></td></tr>
<tr><td>Количество подключенных MPPT &microART&copy:</td><td>&nbsp<?php if ($row[0x157][1]==255) echo "Отключено"; else echo $row[0x157][1]; ?></td></tr>
<tr><td>Максимальная мощность сетевого входа:</td><td>&nbsp<?php $i=($row[0x168][1]<<$row[0x107][1])*100; echo $i."Вт"; ?></td></tr>
<tr><td>Верхний порог напряжения сети по входу:</td><td>&nbsp<?php $i=$row[0x169][1]+100; echo $i."В"; ?></td></tr>
<tr><td>Нижний порог напряжения сети по входу:</td><td>&nbsp<?php $i=$row[0x16A][1]+100; echo $i."В"; ?></td></tr>
<tr><td>Алгоритм работы с сетью:</td><td>&nbsp<?php echo $net_mode[$row[0x16B][1]]; ?></td></tr>
<tr><td>Uэко. Напряжение перехода на сеть:</td><td>&nbsp<?php $i=(($row[0x16C][1]<<$row[0x06][1])+$row[0x103][1])/10; echo $i."В"; ?></td></tr>
<?php
 if ($row[0x16B][1]==3) {
?>
<tr><td>Время начала минимального тарифа:</td><td>&nbsp<?php if ($row[0x16D][1]==0) echo "Отключено"; else { $hh=$row[0x16D][1]>>3; $mm=$row[0x16D][1]&0x7; printf("%02d:%02d",$hh,$mm);} ?></td></tr>
<tr><td>Время окончания минимального тарифа:</td><td>&nbsp<?php if ($row[0x16E][1]==0) echo "Отключено"; else { $hh=$row[0x16E][1]>>3; $mm=$row[0x16E][1]&0x7; printf("%02d:%02d",$hh,$mm);} ?></td></tr>
<?php
}
?>

<tr><td>Минимальная мощность подкачки (DOMINATOR или Гибрид):</td><td>&nbsp<?php if ($row[0x16F][1]==255) echo "Отключено"; else echo $row[0x16F][1]."%"; ?></td></tr>
<tr><td>Тип АКБ:</td><td>&nbsp<?php echo $acc_type[$row[0x180][1]]; ?></td></tr>
<tr><td>Емкость массива АКБ:</td><td>&nbsp<?php $i=($row[0x181][1]*25)>>$row[0x06][1]; echo $i."Ач"; ?></td></tr>
<tr><td>Ток первой ступени заряда АКБ:</td><td>&nbsp<?php $i=($row[0x182][1]/100); echo $i."С"; ?></td></tr>
<tr><td>Ток второй ступени заряда АКБ:</td><td>&nbsp<?php $i=($row[0x183][1]/100); echo $i."С"; ?></td></tr>
<tr><td>Алгоритм заряда АКБ:</td><td>&nbsp<?php echo $charge[$row[0x184][1]]; ?></td></tr>
<tr><td>Напряжение окончания заряда АКБ:</td><td>&nbsp<?php $i=(($row[0x185][1]<<$row[0x06][1])+$row[0x104][1])/10; echo $i."В"; ?></td></tr>
<tr><td>Напряжение буферного заряда АКБ:</td><td>&nbsp<?php $i=(($row[0x186][1]<<$row[0x06][1])+$row[0x105][1])/10; echo $i."В"; ?></td></tr>
<tr><td>Напряжение старта заряда АКБ:</td><td>&nbsp<?php $i=(($row[0x187][1]<<$row[0x06][1])+$row[0x106][1])/10; echo $i."В"; ?></td></tr>


</table>
<br>
</body>
</html>
    