<!DOCTYPE html>
<html>
 <meta charset="utf-8">
 <?php include('./local/local_ru.inc');?>
<head>
 <title><?php loc('update_title');?></title>
 </head>
  <body>
<h2><p><b><?php loc('update_text1');?></b></p></h2>
<fieldset>
 <form action="update_start.php" method="post" enctype="multipart/form-data">
<input type="file" name="update"><br> 
 <input type="submit" value="<?php loc('update_load');?>"><br>
</form>
</fieldset>
<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU'); ?> " ONCLICK="HomeButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('SYSTEM'); ?> " ONCLICK="SystemButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('TEXT'); ?> " ONCLICK="TextButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('GAUGES'); ?> " ONCLICK="GaugesButton()"> 

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