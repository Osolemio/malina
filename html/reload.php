<style>

a.button28 {
position: relative;
display: inline-block;
 font-size: 90%;
font-weight: 700;
 color: rgb(209,209,217);
text-decoration: none;
 text-shadow: 0 -1px 2px rgba(0,0,0,.2);
padding: .5em 1em;
  outline: none;
border-radius: 3px;
 background: linear-gradient(rgb(110,112,120), rgb(81,81,86)) rgb(110,112,120);
box-shadow:
  0 1px rgba(255,255,255,.2) inset,
  0 3px 5px rgba(0,1,6,.5),
 0 0 1px 1px rgba(0,1,6,.2);
  transition: .2s ease-in-out;
   }
  a.button28:hover:not(:active) {
 background: linear-gradient(rgb(126,126,134), rgb(70,71,76)) rgb(126,126,134);
 }
 a.button28:active {
   top: 1px;
 background: linear-gradient(rgb(76,77,82), rgb(56,57,62)) rgb(76,77,82)
 box-shadow: 0 0 1px rgba(0,0,0,.5) inset,
 0 2px 3px rgba(0,0,0,.5) inset,
 1px 1px rgba(255,255,255,.1);
}                    
</style>



<table border="2" align="left" >

<tr bgcolor="lightblue"><td><b>МАП. МОНИТОРИНГ</b></td><td>
<a href="menu.php" class="button28">МЕНЮ</a>
<a href="gauges.php" class="button28">ПРИБОРЫ</a>
<a href="/setup/index.php" class="button28">СИСТЕМА</a>
</td></tr>



<?php    
    if (file_exists("/var/map/.map")) {        

    $shm=shmop_open(2015,"a",0,0);
    $str_json=shmop_read($shm,0,1000);
    $result=substr($str_json,0,strpos($str_json,"}")+1);
    
    shmop_close($shm);

    $row = json_decode($result,true);
    $mode=array("МАП выключени и нет сети на входе","МАП выключен.Сеть на входе","МАП включен. Генерация от АКБ. Нет сети.","МАП включен и транслирует сеть","МАП включен. Трансляция + заряд","","","","","",
    "Принудительная генерация","Тарифная сеть. максимальный тариф. принудительная генерация", "Тарифная сеть. минимальный тариф",
    "Трансляция + эко-подкачка", "Трансляция + продажа в сеть", "Ожидание внешнего полного заряда", "Тарифная сеть. трансляция+эко-подкачка",
    "Тарифная сеть. трансляция+продажа в сеть");



    if ($row['_UNET']==100) $row['_UNET']=0;
    $P=$row['_UNET'];
    $P1=$row['_INET_16_4'];$P=round($P*$P1);
    $P2=$row['_IAcc_med_A_u16'];
    $P1=$row['_Uacc']; $Pacc=round($P2*$P1);   

    $map_error='';
    if ($row['_F_Acc_Over']!=0) $map_error="Ошибка по АКБ/температуре";
    if ($row['_F_Net_Over']!=0) $map_error=$map_error." Ошибка по перегрузке от сети.";
    if ($row['_RSErrSis']!=0) $map_error=$map_error." Критическая ошибка!";
    if ($row['_RSErrJobM']!=0) $map_error=$map_error." Ошибка (неисправность) после перегрузки.";
    if ($row['_RSErrJob']!=0) $map_error=$map_error." Ошибка после перегрузки";
    if ($row['_RSWarning']!=0) $map_error=$map_error." Ошибка напряжения по диапазону. Нестабильность сети";
    if ($row['_I2C_Err']!=0) $map_error=$map_error." Ошибка I2C";
?>

<tr><td><b>Время:</b></td><td><?php echo $row['time'];?></td></tr>
<tr><td><b>Режим:</b></td><td><?php echo $mode[$row['_MODE']]; ?> </td></tr>
<tr><td><b>Напряжение с подстанции</b><td><b><?php echo $row['_UNET'];?>В</b></td></tr>
<tr><td><b>Ток по входу:</b><td><b><?php echo $row['_INET_16_4'];?>А</b> </td></tr>
<tr><td><b>Мощность с подстанции</b><td><b><?php echo $row['_PNET'];?>Вт</b>, расчет:<?php echo $P;?>ВА</td></tr>
<tr><td><b>Частота с подстанции:</b><td><?php echo round(6250/$row['_TFNET'],1);?>Гц </td></tr>
<tr><td><b>Частота с МАП:</b><td><?php echo round(6250/$row['_ThFMAP'],1);?>Гц </td></tr>
<tr><td><b>Напряжение с МАП:</b><td><b><?php echo $row['_UOUTmed'];?>В </b></td></tr>
<tr><td><b>Последнее напряжение вне диапазона:</b><td><text color="red"><b><?php echo $row['_UNET_Limit'];?>В<b></text> </td></tr>
<tr><td><b>Температура АКБ:</b><td><?php echo $row['_Temp_Grad0'];?>&degС </td></tr>
<tr><td><b>Температура транзисторов:</b><td><?php echo $row['_Temp_Grad2'];?>&degC </td></tr>
<tr><td><b>Напряжение АКБ:</b><td><b><?php echo $row['_Uacc'];?>В </b></td></tr>
<tr><td><b>Ток по АКБ:</b><td><?php echo $row['_IAcc_med_A_u16'];?>А </td></tr>
<tr><td><b>Мощность по линии АКБ-МАП:</b><td><?php echo $row['_PLoad']."Вт,&nbspрасчет:&nbsp".$Pacc."Вт";?></td></tr>
<tr><td><b>ОШИБКИ:</b><td><text color="red"><b><?php echo $map_error;?></b></text></td></tr>


<?php
}


    
 if (file_exists("/var/map/.mppt")) {    

    $shm=shmop_open(2016,"a",0,0);
    $str_json=shmop_read($shm,0,1000);
    $result=substr($str_json,0,strpos($str_json,"}")+1);
    
    shmop_close($shm);
    
    
    $row = json_decode($result,true);
    $energy=$row['Pwr_kW'];
    $pout=intval($row['P_Out']);
    $ppv=intval($row['P_PV']);
    if ($ppv>0) $n=round($pout/$ppv,4)*100; else $n=0;
?>

<tr><td></td></tr>
<tr bgcolor="yellow"><td><b>СОЛНЕЧНЫЕ ПАНЕЛИ. MPPT</b><td><tr>
<tr><td><b>Время:</b><td><?php echo $row['time'];?> </td></tr>
<tr><td><b>Режим работы:</b><td><?php echo $row['Mode'].$row['Sign'].", MPP:".$row['MPP'];?> </td></tr>
<tr><td><b>Напряжение панелей:</b><td><b><?php echo $row['Vc_PV'];?>В <b></td></tr>
<tr><td><b>Ток панелей:</b><td><b><?php echo $row['Ic_PV'];?>А </b></td></tr>
<tr><td><b>Мощность панелей:</b><td><b><?php echo $row['P_PV'];?>Вт<b></td></tr>
<tr><td><b>Мощность на выходе:</b><td><?php echo $row['P_Out']."Вт,&nbsp&#951=".$n."%";?> </td></tr>
<tr><td><b>Ток на АКБ:</b><td><?php echo $row['I_Ch'];?>А </td></tr>
<tr><td><b>Внутренняя температура:</b><td><?php echo $row['Temp_Int'];?>&degС </td></tr>
<tr><td><b>Энергия за сутки:</b><td><?php echo $energy;?>кВтч </td></tr>

</table>

<?php

    
}   

if (!file_exists("/var/map/.mppt") && !file_exists("/var/map/.map")) echo "Запустите необходимые сервисы";

?>

