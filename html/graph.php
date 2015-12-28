<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="jschart1.css">
  <script src="js/jquery-1.11.2.min.js"></script>
  <script src="js/jquery-migrate-1.2.1.min.js"></script>
  <script src="./js/jschart-2.2.1.min.js"></script>
  <script src="jschart2.js"></script>
  
  <title>MAP Monitor. Diagrams</title>
    
</head>


<body>


<div id="legend">

    Легенда:
  
    <span class="blue"><b>Мощность с подстанции, ВА</b></span>  
    
    <span class="red"><b>Мощность АКБ-МАП по модулю, Вт</b></span>  
      
    <span class="green"><b>Мощность СП, Вт</b></span>    
  </div>
  
          
 <div id="chart"></div>

<div class="menu">
  <input type="checkbox" id="menu-collapsed" name="menu-collapsed" checked/>
  <div class="menu-content">
    <ul>
      <li><a href="menu.php">МЕНЮ</a></li>
      <li><a href="index.php">ТЕКСТ</a></li>
      <li><a href="gauges.php">ПРИБОРЫ</a></li>
      <li><a href="history.php">ИСТОРИЯ</a></li>
      <li><a href="multi_select.php">МУЛЬТИГРАФ</a></li>
      <li><a href="bms.php">BMS</a></li>
      <li><a href="settings.php">ИНФО</a></li>
      <li><a href="./setup/index.php">СИСТЕМА</a></li>
      <li><a href="./setup/settings.php">СИСТЕМА-МАП</a></li>
      <li><a href="./setup/sys.php">СИСТЕМА-СЕРВИСЫ</a></li>
    
    </ul>
  </div>
  <div class="menu-switch">
    <label class="collapse" for="menu-collapsed">«</label>
    <label class="rise" for="menu-collapsed">»</label>
  </div>
</div>
  



</body>
</html>
    