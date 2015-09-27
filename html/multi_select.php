<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">

  <title>Выбор графиков</title>
    
    
</head>


<body>
<hr>
<center>Выберите графики реального времени для отображения</center>
<hr>

<form method="post" action="multichart.php">

<?php 

if (file_exists("/var/map/.map")) $map_exists=true; else $map_exists=false;


if (file_exists("/var/map/.mppt")) $mppt_exists=true; else $mppt_exists=false;

?>
<b>
<p>
<fieldset>
    <input type="checkbox" name="acc" checked disabled/> График АКБ
    <input type="checkbox" name="umap" checked <?php if (!$map_exists) echo "disabled" ?>/> Напряжение вход/выход МАП    
    <input type="checkbox" name="imap" checked <?php if (!$map_exists) echo "disabled" ?>/> Токи/мощности МАП/MPPT(I2C)
    <input type="checkbox" name="imppt" <?php if (!$mppt_exists) echo "disabled" ?>/> Токи MPPT (данные MPPT)
    <input type="checkbox" name="iacc" checked <?php if (!$mppt_exists) echo "disabled" ?>/> Баланс АКБ по токам
    <input type="checkbox" name="wind"  <?php if (!$mppt_exists) echo "disabled" ?>/> Обороты ветрогенератора
</fieldset>
<p>
<input type="submit" value="Отобразить">
</form>

<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="menu.php";
}
</script>

</body>
</html>
    