<?php
    $db=mysql_connect ('localhost','monitor','energy');
    mysql_select_db('battery',$db);
    mysql_query('SET NAMES utf8',$db);          
    mysql_query('SET CHARACTER SET utf8',$db);  
    mysql_query('SET COLLATION_CONNECTION="utf8_general_ci"',$db);
?>