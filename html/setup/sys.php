<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <?php include('../local/local.inc');?>
  <title><?php loc('services_title');?></title>
  <script src="../js/jquery-2.1.3.min.js"></script>
  <script src="../js/jquery-migrate-1.2.1.min.js"></script>

  <script src="proc.js"></script>    


    
</head>

<style>

a.button {
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

<body>

<center><b>
<table bgcolor="ghostwhite" border=5 align="left" style="border-spacing: 2px 15px;">
<tr bgcolor="white"><td><?php loc('Service_state');?>  <?php loc('MAC');?>: </td><td><div id="map_div">N/A</div> </td> <td>

<?php


if (isset($_POST['map']['sendRequest'])) {
    
   if (file_exists("/var/map/.map")) unlink("/var/map/.map"); else touch("/var/map/.map");
   header("refresh");
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="map">
    <input type="submit" name="map[sendRequest]" value="<?php if (file_exists("/var/map/.map")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>
<?php
} else

{
?>
  
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="map">
    <input type="submit" name="map[sendRequest]" value="<?php if (file_exists("/var/map/.map")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>


<?php
}    
?>

</td></tr>
<tr bgcolor="white"><td><?php loc('Service_state');?>  <?php loc('MPPT');?>: </td><td><div id="mppt_div">N/A</div> </td> <td>

<?php


if (isset($_POST['mppt']['sendRequest'])) {
    
   if (file_exists("/var/map/.mppt")) unlink("/var/map/.mppt"); else touch("/var/map/.mppt");
   header("refresh");
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="mppt">
    <input type="submit" name="mppt[sendRequest]" value="<?php if (file_exists("/var/map/.mppt")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>
<?php
} else

{
?>
  
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="mppt">
    <input type="submit" name="mppt[sendRequest]" value="<?php if (file_exists("/var/map/.mppt")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>
<?php
}    

?>
</td></tr>
<tr bgcolor="white"><td> <?php loc('Service_state');?>  <?php loc('batmon');?>: </td><td><div id="batmon_div">N/A</div> </td> <td>

<?php

if (isset($_POST['batmon']['sendRequest'])) {
    
   if (file_exists("/var/map/.bmon")) unlink("/var/map/.bmon"); else touch("/var/map/.bmon");
   header("refresh");
?>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="batmon">
    <input type="submit" name="batmon[sendRequest]" value="<?php if (file_exists("/var/map/.bmon")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>
<?php
} else

{
?>
  
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="batmon">
    <input type="submit" name="batmon[sendRequest]" value="<?php if (file_exists("/var/map/.bmon")) echo $text['Stop_service']; else echo $text['Start_service'];?>"/>
</form>


<?php
}    
?>


</td></tr>
<tr bgcolor="white"><td> <?php loc('Service_state');?>  <?php loc('SMS');?>: </td><td><div id="sms_div">N/A</div> </td> <td>

<?php    


if (isset($_POST['sms']['sendRequest'])) {
    
   if (file_exists("/var/map/.sms")) unlink("/var/map/.sms"); else touch("/var/map/.sms");
   header("refresh");
?>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="sms">
    <input type="submit" name="sms[sendRequest]" value="<?php if (file_exists("/var/map/.sms")) echo $text['Stop_service']; else echo $text['Start_service'];;?>"/>
</form>
<?php
} else

{
?>
  
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="sms">
    <input type="submit" name="sms[sendRequest]" value="<?php if (file_exists("/var/map/.sms")) echo $text['Stop_service']; else echo $text['Start_service'];;?>"/>
</form>


<?php
}    
?>


</td></tr>
<tr bgcolor="white"><td> <?php loc('Service_state');?>  mysql: </td><td><div id="mysql">N/A</div> </td> <td>
</td></tr>

<tr><td> <?php loc('Reboot');?> RasPi: </td><td><div id="reboot"></div> </td> <td>


<?php


if (isset($_POST['reboot']['sendRequest'])) touch("/var/map/.reboot");
?>




<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="reboot">
    <input type="submit" name="reboot[sendRequest]" value="<?php loc('Reboot');?> RasPi"/>
<br>

</td></tr>
<tr><td> <?php loc('Turn_off');?>  RasPi: </td><td><div id="shutdown"></div> </td> <td>


<?php
if (isset($_POST['shutdown']['sendRequest'])) touch("/var/map/.shutdown");
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="shutdown">
    <input type="submit" name="shutdown[sendRequest]" value="<?php loc('Turn_off');?> RasPi"/>
<br>

</td></tr>

<tr bgcolor="white"><td>
<br><br>
<?php loc('Delay_info');?>
<br><br>
<td></td>
<td align="center">
<a href="index.php" class="button"><?php loc('MENU');?></a>
</td>
</td></tr>

</table>
</center>
<br>
</body>
</html>
    