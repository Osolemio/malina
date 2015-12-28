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

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="index.php">ТЕКСТ</a></li>
      <li><a href="graph.php">МОЩНОСТИ</a></li>
      <li><a href="history.php">ИСТОРИЯ</a></li>
      <li><a href="multi_select.php">МУЛЬТИГРАФ</a></li>
      <li><a href="bms.php">BMS</a></li>
      <li><a href="settings.php">ИНФО</a></li>
      <li><a href="./setup/settings.php">СИСТЕМА-МАП</a></li>
      <li><a href="./setup/sys.php">СИСТЕМА-СЕРВИСЫ</a></li>
    
    </ul>
  </div>
  <div class="menu-switch">
    <label class="collapse" for="menu-collapsed">«</label>
    <label class="rise" for="menu-collapsed">»</label>
  </div>
</div>

  <div id="chart_acc" class='charts'></div>

<?php if (file_exists("/var/map/.map")) {

  if (isset($_POST['umap'])) echo "<div id='chart_unet' class='charts'></div>";

  if (isset($_POST['imap'])) { echo "<div id='chart_inet'  class='charts'>
  <div id='button_pnet'>
  <button onclick='pnet_switch();'><b>Мощность/ток</b></button>
  </div></div>";}
}


if (file_exists("/var/map/.mppt") && isset($_POST['imppt'])) 
  echo '<div id="chart_ipv" class="charts"></div>';

if (file_exists("/var/map/.map") && isset($_POST['iacc'])) {?>
  <button onclick="pbal_switch();"><b>Мощность/ток</b></button>
  </div></div>
  <button onclick="sw_map();"><b>МАП</b></button>
  </div></div>
  <button onclick="sw_mppt();"><b>MPPT</b></button>
  </div></div>
  <div id="chart_balance"  class='charts'></div>
<?php }
if (isset($_POST['wind'])) echo "<div id='chart_wind' class='charts'></div>";



?>

            

</body>
</html>
    