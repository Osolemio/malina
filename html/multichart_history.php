<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="multichart.css">
  <script src="./js/jquery-2.1.3.min.js"></script>
  <script src="./js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./zingchart/zingchart.min.js"></script>
  <script src="./multichart_hst.js"></script>

  <title>MAP Monitor. History multigraph</title>
    
    
</head>


<body>

  <div id="chart_acc"></div>
  <div id="chart_unet"></div>

  <div id="chart_inet">
  <div id="button_pnet">
  <button onclick="pnet_switch();"><b>Мощность/ток</b></button>
  </div></div>
<?php if (file_exists("/var/map/.mppt")) 
    echo '<div id="chart_ipv"></div>';?>

  <button onclick="pbal_switch();"><b>Мощность/ток</b></button>
  </div></div>
  <button onclick="sw_map();"><b>МАП</b></button>
  </div></div>
  <button onclick="sw_mppt();"><b>MPPT</b></button>
  </div></div>
  <div id="chart_balance"></div>
  <a href="./menu.php"><div id="arrow"></div></a>            

</body>
</html>
    