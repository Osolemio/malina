<?php
 if ($_POST['sw']==1)
 touch("/var/map/.eureset");
 if ($_POST['sw']==2)
 touch("/var/map/.allreset");
 exit(0);

?>