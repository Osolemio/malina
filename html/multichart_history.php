<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="multichart.css">
<?php include('./local/local.inc');?>
  <script src="./js/jquery-2.1.3.min.js"></script>
  <script src="./js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./zingchart/zingchart.min.js"></script>
  <script src="./multichart_hst.js"></script>

  <title><?php loc('multichart_hst_title');?></title>
    
    
</head>


<body>

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="index.php"><?php loc('TEXT');?></a></li>
      <li><a href="graph.php"><?php loc('POWERS');?></a></li>
      <li><a href="history.php"><?php loc('HISTORY');?></a></li>
      <li><a href="multi_select.php"><?php loc('MULTICHART');?></a></li>
      <li><a href="bms.php"><?php loc('BMS');?></a></li>
      <li><a href="settings.php"><?php loc('INFO');?></a></li>
      <li><a href="./setup/settings.php"><?php loc('SYSTEM');?>-<?php loc('MAC');?></a></li>
      <li><a href="./setup/sys.php"><?php loc('SYSTEM');?>-<?php loc('SERVICES');?></a></li>
    
    </ul>
  </div>
  <div class="menu-switch">
    <label class="collapse" for="menu-collapsed">«</label>
    <label class="rise" for="menu-collapsed">»</label>
  </div>
</div>

  <div id="chart_acc" classs="charts"></div>
  <div id="chart_unet" classs="charts"></div>

  <div id="chart_inet" classs="charts">
  <div id="button_pnet" classs="charts">
  <button onclick="pnet_switch();"><b><?php loc('p-i');?></b></button>
  </div></div>
<?php if (file_exists("/var/map/.mppt")) 
    echo '<div id="chart_ipv classs="charts""></div>';?>

  <button onclick="pbal_switch();"><b><?php loc('p-i');?></b></button>
  </div></div>
  <button onclick="sw_map();"><b><?php loc('MAP');?></b></button>
  </div></div>
  <button onclick="sw_mppt();"><b><?php loc('MPPT');?></b></button>
  </div></div>
  <div id="chart_balance" classs="charts"></div>

</body>
</html>
    