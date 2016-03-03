<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
<?php


   include ("./request_settings.php");
   include ("./local/local_ru.inc");
?>
  
  <title><?php loc('info_page');?></title>
    
</head>


<body>




<table border="2" align="left">
<tr bgcolor="lightskyblue"><td><b><?php loc('info_header');?></b></td><td><a href="./menu.php"><b><?php loc('MENU');?></b></a></td></tr>
<tr><td><?php loc('version_hw');?>:</td><td>&nbsp<?php echo $row[0x01][1]^0xC0; if ($row[0x01][1]&0x80) echo "&nbsp".$text['HYBRID'];?></td></tr>
<tr><td><?php loc('version_fw');?>:</td><td>&nbsp<?php $i=$row[0x02][1]&0x1F;$i1=$row[0x02][1]>>5;echo $i.".".$i1;?></td></tr>
<tr><td><?php loc('device_type');?>:</td><td>&nbsp<?php echo $dominator[$row[0x24][1]]; ?></td></tr>
<tr><td><?php loc('device_power');?>:</td><td>&nbsp<?php echo $power[$row[0x05][1]].$text['kW']; ?></td></tr>
<tr><td><?php loc('work_voltage');?>:</td><td>&nbsp<?php echo $voltage[$row[0x06][1]].$text['V']; ?></td></tr>
<tr><td><?php loc('motherboard_version');?>:</td><td>&nbsp<?php echo $row[0x0C][1]; ?></td></tr>
<tr><td><?php loc('t_sensors');?>:</td><td>&nbsp<?php if ($row[0x51][1]&1) echo $text['env'].",";if ($row[0x51][1]&2) echo "&nbsp".$text['tor'].","; if ($row[0x51][1]&1) echo "&nbsp".$text['transistors'].".";?></td></tr>
<tr><td><?php loc('vent_no');?>:</td><td>&nbsp<?php echo $row[0x52][1]; ?></td></tr>
<tr><td><?php loc('min_curr_absorption');?>:</td><td>&nbsp<?php $i=$row[0x65][1]/100; echo $i."C"; ?></td></tr>
<tr><td><?php loc('chg_time_BMS');?>:</td><td>&nbsp<?php echo $row[0x66][1]."&nbsp".$text['min']; ?></td></tr>
<tr><td><?php loc('nominal_power');?>:</td><td>&nbsp<?php echo $row[0x68][1]."% ".$text['from_max']; ?></td></tr>
<tr><td><?php loc('restart_attempts');?>:</td><td>&nbsp<?php echo $row[0x73][1]; ?></td></tr>
<tr><td><?php loc('chg_delay');?>:</td><td>&nbsp<?php echo $row[0x78][1]."&nbsp".$text['sec']; ?></td></tr>
<tr><td><?php loc('stab_time');?>:</td><td>&nbsp<?php echo $row[0x7B][1]."&nbsp".$text['sec']; ?></td></tr>
<tr><td><?php loc('tmax_chg');?>:</td><td>&nbsp<?php echo $row[0x7E][1]."&nbsp".$text['min']; ?></td></tr>
<tr><td><?php loc('NET_delay');?>:</td><td>&nbsp<?php echo $row[0x7F][1]."&nbsp".$text['min']; ?></td></tr>
<tr><td><?php loc('discharged_acc_time');?>:</td><td>&nbsp<?php echo $row[0x80][1]."&nbsp".$text['sec']; ?></td></tr>
<tr><td><?php loc('Vdd');?>:</td><td>&nbsp<?php if ($row[0xFE][1]&1) echo $text['always_on'].","; else echo $text['depends_on_mode']; ?></td></tr>
<tr><td><?php loc('sine_accuracy');?>:</td><td>&nbsp<?php echo $sin[$row[0x138][1]]; ?></td></tr>
<tr><td><?php loc('3ph');?>:</td><td>&nbsp<?php if ($row[0x139][1]==255) loc('1ph'); else echo $phase[$row[0x139][1]]; ?></td></tr>
<tr><td><?php loc('Uout_gen');?>:</td><td>&nbsp<?php $i=$row[0x13A][1]+100; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('Pmax_add');?>:</td><td>&nbsp<?php if ($row[0x13B][1]==0) loc('off_full');if ($row[0x13B][1]==1) loc('on_full');?></td></tr>
<tr><td><?php loc('Eco_mode');?>:</td><td>&nbsp<?php if ($row[0x13C][1]==255) loc('no_in_settings'); else echo $eco[$row[0x13C][1]];?></td></tr>
<tr><td><?php loc('Uacc_min');?>:</td><td>&nbsp<?php $i=(($row[0x13D][1]<<$row[0x06][1]) + $row[0x102][1])/10; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('Load_gen');?>:</td><td>&nbsp<?php $i=$row[0x13E][1]; if ($i==0) loc('off_full'); else echo $i.$text['W']; ?></td></tr>
<tr><td><?php loc('parallel_macs');?>:</td><td>&nbsp<?php if ($row[0x155][1]==255) loc('not_available'); else echo $row[0x155][1]; ?></td></tr>
<tr><td><?php loc('input_source');?>:</td><td>&nbsp<?php echo $net[$row[0x150][1]]; ?></td></tr>
<tr><td><?php loc('external_devices');?>:</td><td>&nbsp<?php echo $ext[$row[0x156][1]]; ?></td></tr>
<tr><td><?php loc('mppt_no');?>:</td><td>&nbsp<?php if ($row[0x157][1]==255) loc('off_full'); else echo $row[0x157][1]; ?></td></tr>
<tr><td><?php loc('input_max');?>:</td><td>&nbsp<?php $i=($row[0x168][1]<<$row[0x107][1])*100; echo $i.$text['W']; ?></td></tr>
<tr><td><?php loc('hi_threshold');?>:</td><td>&nbsp<?php $i=$row[0x169][1]+100; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('lo_threshold');?>:</td><td>&nbsp<?php $i=$row[0x16A][1]+100; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('net_alg');?>:</td><td>&nbsp<?php echo $net_mode[$row[0x16B][1]]; ?></td></tr>
<tr><td><?php loc('Ueco_net');?>:</td><td>&nbsp<?php $i=(($row[0x16C][1]<<$row[0x06][1])+$row[0x103][1])/10; echo $i.$text['V']; ?></td></tr>
<?php
 if ($row[0x16B][1]==3) {
?>
<tr><td><?php loc('tzone_start');?>:</td><td>&nbsp<?php if ($row[0x16D][1]==0) loc('off_full'); else { $hh=$row[0x16D][1]>>3; $mm=$row[0x16D][1]&0x7; printf("%02d:%02d",$hh,$mm);} ?></td></tr>
<tr><td><?php loc('tzone_end');?>:</td><td>&nbsp<?php if ($row[0x16E][1]==0) loc('off_full'); else { $hh=$row[0x16E][1]>>3; $mm=$row[0x16E][1]&0x7; printf("%02d:%02d",$hh,$mm);} ?></td></tr>
<?php
}
?>

<tr><td><?php loc('Min_u_pumping');?>:</td><td>&nbsp<?php if ($row[0x16F][1]==255) loc('off_full'); else echo $row[0x16F][1]."%"; ?></td></tr>
<tr><td><?php loc('acc_type');?>:</td><td>&nbsp<?php echo $acc_type[$row[0x180][1]]; ?></td></tr>
<tr><td><?php loc('c_acc_array');?>:</td><td>&nbsp<?php $i=($row[0x181][1]*25)>>$row[0x06][1]; echo $i.$text['Ah']; ?></td></tr>
<tr><td><?php loc('1st_step_i');?>:</td><td>&nbsp<?php $i=($row[0x182][1]/100); echo $i."С"; ?></td></tr>
<tr><td><?php loc('2nd_step_i');?>:</td><td>&nbsp<?php $i=($row[0x183][1]/100); echo $i."С"; ?></td></tr>
<tr><td><?php loc('acc_chg_alg');?>:</td><td>&nbsp<?php echo $charge[$row[0x184][1]]; ?></td></tr>
<tr><td><?php loc('u_acc_stop');?>:</td><td>&nbsp<?php $i=(($row[0x185][1]<<$row[0x06][1])+$row[0x104][1])/10; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('u_acc_buf');?>:</td><td>&nbsp<?php $i=(($row[0x186][1]<<$row[0x06][1])+$row[0x105][1])/10; echo $i.$text['V']; ?></td></tr>
<tr><td><?php loc('u_acc_start');?>:</td><td>&nbsp<?php $i=(($row[0x187][1]<<$row[0x06][1])+$row[0x106][1])/10; echo $i.$text['V']; ?></td></tr>


</table>
<br>
</body>
</html>
    