<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="multichart.css">
  <script src="./js/jquery-2.1.3.min.js"></script>
  <script src="./js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./zingchart/zingchart.min.js"></script>
  <script src="./multichart.js"></script>

  <title>MAP Monitor. Diagrams</title>
    
    
</head>


<body>

  <div id="chart_acc"></div>

<?php if (file_exists("/var/map/.map")) {

  if (isset($_POST['umap'])) echo "<div id='chart_unet'></div>";

  if (isset($_POST['imap'])) { echo "<div id='chart_inet'>
  <div id='button_pnet'>
  <button onclick='pnet_switch();'><b>Мощность/ток</b></button>
  </div></div>";}
}


if (file_exists("/var/map/.mppt") && isset($_POST['imppt'])) 
  echo '<div id="chart_ipv"></div>';

if (file_exists("/var/map/.map") && isset($_POST['iacc'])) {?>
  <button onclick="pbal_switch();"><b>Мощность/ток</b></button>
  </div></div>
  <button onclick="sw_map();"><b>МАП</b></button>
  </div></div>
  <button onclick="sw_mppt();"><b>MPPT</b></button>
  </div></div>
  <div id="chart_balance"></div>
<?php }
if (isset($_POST['wind'])) echo "<div id='chart_wind'></div>";



?>

<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" СИСТЕМА " ONCLICK="SystemButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" ТЕКСТ " ONCLICK="TextButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" ПРИБОРЫ " ONCLICK="GaugesButton()"> 
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" ИСТОРИЯ " ONCLICK="HistoryButton()"> 

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

function HistoryButton()
{
location.href="history.php";
}



</script>



</div>
            

</body>
</html>
    