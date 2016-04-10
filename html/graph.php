<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="jschart1.css">
    <?php include('./local/local.inc');?>

  <script src="js/jquery-1.11.2.min.js"></script>
  <script src="js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./js/jschart-2.2.1.min.js"></script>
  <script src="jschart2.js"></script>
  
  <title><?php loc('diagrams_title');?></title>

    
</head>


<body>


<div id="legend">

    <?php loc('Legend');?>:
  
    <span class="blue"><b><?php loc('P_MAINS');?>, <?php loc('VA');?></b></span>  
    
    <span class="red"><b><?php loc('P_ACC_MAC_MOD');?>, <?php loc('W');?></b></span>  
      
    <span class="green"><b><?php loc('PPV');?>, <?php loc('W');?></b></span>    
  </div>
  
          
 <div id="chart"></div>

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="menu.php"><?php loc('MENU');?></a></li>
      <li><a href="index.php"><?php loc('TEXT');?></a></li>
      <li><a href="gauges.php"><?php loc('GAUGES');?></a></li>
      <li><a href="history.php"><?php loc('HISTORY');?></a></li>
      <li><a href="multi_select.php"><?php loc('MULTICHART');?></a></li>
      <li><a href="bms.php"><?php loc('BMS');?></a></li>
      <li><a href="settings.php"><?php loc('INFO');?></a></li>
      <li><a href="./setup/index.php"><?php loc('SYSTEM');?></a></li>
      <li><a href="./setup/settings.php"><?php loc('SYSTEM');?>-<?php loc('MAC');?></a></li>
      <li><a href="./setup/sys.php"><?php loc('SYSTEM');?>-<?php loc('SERVICES');?></a></li>
    
    </ul>
  </div>
  <div class="menu-switch">
    <label class="collapse" for="menu-collapsed">«</label>
    <label class="rise" for="menu-collapsed">»</label>
  </div>
</div>
  



</body>
</html>
    