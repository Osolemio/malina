<?php
// Password to be encrypted for a .htpasswd file
if ($_POST['password']!=$_POST['password1'])
    {
    echo "Введенные пароли не совпадают<br>";
    echo "<a href='index.php'>Вернуться в меню</a>";
    exit;
     }

// Encrypt password
$password = crypt($_POST['password'], base64_encode($_POST['password']));

// Print encrypted password
$f=fopen("/var/map/.tmp_p","w");
fwrite($f,$_POST['user'].":".$password);
fclose($f);
echo "Пароль и имя пользователя изменены<br>";
echo "<a href='index.php'>Вернуться в меню</a>";

?>