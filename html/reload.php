<?php include('./local/local_ru.inc');
?>
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

<tr bgcolor="lightblue"><td><b><?php loc('MAC');?></b></td><td>
<a href="menu.php" class="button28"><?php loc('MENU');?></a>
<a href="gauges.php" class="button28"><?php loc('GAUGES');?></a>
<a href="/setup/index.php" class="button28"><?php loc('SYSTEM');?></a>
</td></tr>



<?php    
    if (file_exists("/var/map/.map")) {        

    $shm=shmop_open(2015,"a",0,0);
    $str_json=shmop_read($shm,0,1000);
    $result=substr($str_json,0,strpos($str_json,"}")+1);
    
    shmop_close($shm);

    $row = json_decode($result,true);



    if ($row['_UNET']==100) $row['_UNET']=0;
    $P=$row['_UNET'];
    $P1=$row['_INET_16_4'];$P=round($P*$P1);
    $P2=$row['_IAcc_med_A_u16'];
    $P1=$row['_Uacc']; $Pacc=round($P2*$P1);   

    $map_error='';
    if ($row['_F_Acc_Over']!=0) $map_error=$text['map_error1'];
    if ($row['_F_Net_Over']!=0) $map_error=$map_error.$text['map_error2'];
    if ($row['_RSErrSis']!=0) $map_error=$map_error.$text['map_error3'];
    if ($row['_RSErrJobM']!=0) $map_error=$map_error.$text['map_error4'];
    if ($row['_RSErrJob']!=0) $map_error=$map_error.$text['map_error5'];
    if ($row['_RSWarning']!=0) $map_error=$map_error.$text['map_error6'];
    if ($row['_I2C_Err']!=0) $map_error=$map_error.$text['map_error7'];
?>

<tr><td><b><?php loc('TIME');?>:</b></td><td><?php echo $row['time'];?></td></tr>
<tr><td><b><?php loc('MODE');?>:</b></td><td><?php echo $mode[$row['_MODE']]; ?> </td></tr>
<tr><td><b><?php loc('UNET');?>:</b><td><b><?php echo $row['_UNET'];?><?php loc('V');?></b></td></tr>
<tr><td><b><?php loc('INET');?>:</b><td><b><?php echo $row['_INET_16_4'];?><?php loc('A');?></b> </td></tr>
<tr><td><b><?php loc('PNET');?>:</b><td><b><?php echo $row['_PNET'];?><?php loc('W');?></b>, <?php loc('calc');?>:<?php echo $P;?><?php loc('VA');?></td></tr>
<tr><td><b><?php loc('FNET');?>:</b><td><?php echo round(6250/$row['_TFNET'],1);?><?php loc('Hz');?> </td></tr>
<tr><td><b><?php loc('FMAC');?>:</b><td><?php echo round(6250/$row['_ThFMAP'],1);?><?php loc('Hz');?> </td></tr>
<tr><td><b><?php loc('UMAC');?>:</b><td><b><?php echo $row['_UOUTmed'];?><?php loc('V');?> </b></td></tr>
<tr><td><b><?php loc('UERR');?>:</b><td><text color="red"><b><?php echo $row['_UNET_Limit'];?>В<b></text> </td></tr>
<tr><td><b><?php loc('TACC');?>:</b><td><?php echo $row['_Temp_Grad0'];?>&degС </td></tr>
<tr><td><b><?php loc('TTRANS');?>:</b><td><?php echo $row['_Temp_Grad2'];?>&degC </td></tr>
<tr><td><b><?php loc('UACC');?>:</b><td><b><?php echo $row['_Uacc'];?><?php loc('V');?> </b></td></tr>
<tr><td><b><?php loc('IACC');?>:</b><td><?php echo $row['_IAcc_med_A_u16'];?><?php loc('A');?> </td></tr>
<tr><td><b><?php loc('PACC-MAC');?>:</b><td><?php echo $row['_PLoad'].$text['W'].",&nbsp".$text['calc'].":&nbsp".$Pacc.$text['W'];?></td></tr>
<tr><td><b><?php loc('ERRORS');?>:</b><td><text color="red"><b><?php echo $map_error;?></b></text></td></tr>


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
<tr bgcolor="yellow"><td><b>MPPT</b><td><tr>
<tr><td><b><?php loc('TIME');?>:</b><td><?php echo $row['time'];?> </td></tr>
<tr><td><b><?php loc('MODE');?>:</b><td><?php echo $row['Mode'].$row['Sign'].", MPP:".$row['MPP'];?> </td></tr>
<tr><td><b><?php loc('UPV');?>:</b><td><b><?php echo $row['Vc_PV'];?><?php loc('V');?> <b></td></tr>
<tr><td><b><?php loc('IPV');?>:</b><td><b><?php echo $row['Ic_PV'];?><?php loc('A');?> </b></td></tr>
<tr><td><b><?php loc('PPV');?>:</b><td><b><?php echo $row['P_PV'];?><?php loc('W');?><b></td></tr>
<tr><td><b><?php loc('POUT');?>:</b><td><?php echo $row['P_Out'].$text['W'].",&nbsp&#951=".$n."%";?> </td></tr>
<tr><td><b><?php loc('IACC');?>:</b><td><?php echo $row['I_Ch'];?><?php loc('A');?> </td></tr>
<tr><td><b><?php loc('TINT');?>:</b><td><?php echo $row['Temp_Int'];?>&degС </td></tr>
<tr><td><b><?php loc('E24');?>:</b><td><?php echo $energy;?><?php loc('kWh');?> </td></tr>

</table>

<?php

    
}   

if (!file_exists("/var/map/.mppt") && !file_exists("/var/map/.map")) loc('START_SERVICES');

?>

