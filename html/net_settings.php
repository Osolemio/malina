<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
<?php


   include ("./request_settings.php");
   include ("./local/local.inc");
?>
  
    
</head>


<body>


<?php 
echo $row[0x01][1]^0xC0; if ($row[0x01][1]&0x80) echo "&nbsp".$text['HYBRID']; echo "|";
$i=$row[0x02][1]&0x1F;$i1=$row[0x02][1]>>5;echo $i.".".$i1; echo "|";
echo $dominator[$row[0x24][1]]; echo "|";
echo $power[$row[0x05][1]]; echo "|";
echo $voltage[$row[0x06][1]]; echo "|";
echo $row[0x0C][1]; echo "|";
if ($row[0x51][1]&1) echo $text['env'].",";if ($row[0x51][1]&2) echo "&nbsp".$text['tor'].","; if ($row[0x51][1]&1) echo "&nbsp".$text['transistors']."."; echo "|";
echo $row[0x52][1]; echo "|";
$i=$row[0x65][1]/100; echo $i."C"; echo "|";
echo $row[0x66][1]."&nbsp".$text['min']; echo "|";
echo $row[0x68][1]."% ".$text['from_max']; echo "|";
echo $row[0x73][1]; echo "|";
echo $row[0x78][1]."&nbsp".$text['sec']; echo "|";
echo $row[0x7B][1]."&nbsp".$text['sec']; echo "|";
echo $row[0x7E][1]."&nbsp".$text['min']; echo "|";
echo $row[0x7F][1]."&nbsp".$text['min']; echo "|";
echo $row[0x80][1]."&nbsp".$text['sec']; echo "|";
if ($row[0xFE][1]&1) echo $text['always_on'].","; else echo $text['depends_on_mode']; echo "|";
echo $sin[$row[0x138][1]]; echo "|";
if ($row[0x139][1]==255) loc('1ph'); else echo $phase[$row[0x139][1]]; echo "|";
$i=$row[0x13A][1]+100; echo $i.$text['V']; echo "|";
if ($row[0x13B][1]==0) loc('off_full');if ($row[0x13B][1]==1) loc('on_full'); echo "|";
if ($row[0x13C][1]==255) loc('no_in_settings'); else echo $eco[$row[0x13C][1]]; echo "|";
$i=(($row[0x13D][1]<<$row[0x06][1]) + $row[0x102][1])/10; echo $i.$text['V']; echo "|";
$i=$row[0x13E][1]; if ($i==0) loc('off_full'); else echo $i.$text['W']; echo "|";
if ($row[0x155][1]==255) loc('not_available'); else echo $row[0x155][1]; echo "|";
echo $net[$row[0x150][1]]; echo "|";
echo $ext[$row[0x156][1]]; echo "|";
if ($row[0x157][1]==255) loc('off_full'); else echo $row[0x157][1]; echo "|";
$i=($row[0x168][1]<<$row[0x107][1])*100; echo $i; echo "|";
$i=$row[0x169][1]+100; echo $i.$text['V'];  echo "|";
$i=$row[0x16A][1]+100; echo $i.$text['V'];  echo "|";
echo $net_mode[$row[0x16B][1]];  echo "|";
$i=(($row[0x16C][1]<<$row[0x06][1])+$row[0x103][1])/10; echo $i.$text['V'];  echo "|";
if ($row[0x16F][1]==255) loc('off_full'); else echo $row[0x16F][1]."%";  echo "|";
echo $acc_type[$row[0x180][1]];  echo "|";
$i=($row[0x181][1]*25)>>$row[0x06][1]; echo $i.$text['Ah'];  echo "|";
$i=($row[0x182][1]/100); echo $i."С";  echo "|";
$i=($row[0x183][1]/100); echo $i."С";  echo "|";
echo $charge[$row[0x184][1]];  echo "|";
$i=(($row[0x185][1]<<$row[0x06][1])+$row[0x104][1])/10; echo $i.$text['V'];  echo "|";
$i=(($row[0x186][1]<<$row[0x06][1])+$row[0x105][1])/10; echo $i.$text['V'];  echo "|";
$i=(($row[0x187][1]<<$row[0x06][1])+$row[0x106][1])/10; echo $i.$text['V'];  echo "|";
?>

<br>
</body>
</html>
    