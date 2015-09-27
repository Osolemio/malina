<?php
    $db_bat=mysql_connect ('localhost','monitor','energy');
    mysql_select_db('battery',$db_bat);
    mysql_query('SET NAMES utf8',$db_bat);          
    mysql_query('SET CHARACTER SET utf8',$db_bat);  
    mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"',$db_bat);
?>