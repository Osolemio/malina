<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">

<?php include('./local/local.inc');?>  
  <title><?php loc('txt_title');?></title>

    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    
</head>


<body>

<center><b>

<?php


   include ("./bd.php");
    $map_table="data";
    $mppt_table="mppt";
?>

<?php

    
    header("refresh: 1");
    
    include("./reload.php");

    mysql_close($db);
?>

</center>
<br>
</body>
</html>
    