<?php
include('../local/local.inc');

// Password to be encrypted for a .htpasswd file
if ($_POST['password']!=$_POST['password1'])
    {
    echo $text['psys_different']."<br>";
    echo "<a href='index.php'>".$text['return_to_menu']."</a>";
    exit;
     }

// Encrypt password
$password = crypt($_POST['password'], base64_encode($_POST['password']));

// Print encrypted password
$f=fopen("/var/map/.tmp_p","w");
fwrite($f,$_POST['user'].":".$password);
fclose($f);
echo $text['changed']."<br>";
echo "<a href='index.php'>".$text['return_to_menu']."</a>";

?>