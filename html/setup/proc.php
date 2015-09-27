<?php

    $map=exec("/bin/pidof mapd");
    $mppt=exec("/bin/pidof mpptd");
    $mysql=exec("/bin/pidof mysqld");
    $batmon=exec("/bin/pidof batmon");
    $smsd=exec("/bin/pidof smsd");
    echo ($map!=NULL)?1:0;echo ",";
    echo ($mppt!=NULL)?1:0;echo ",";
    echo ($batmon!=NULL)?1:0;echo ",";
    echo ($smsd!=NULL)?1:0;echo ",";
    echo ($mysql!=NULL)?1:0;echo ",";
    echo (file_exists("/var/map/.map"))?1:0;echo ",";
    echo (file_exists("/var/map/.mppt"))?1:0;echo ",";
    echo (file_exists("/var/map/.bmon"))?1:0;


?>