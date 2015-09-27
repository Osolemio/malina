<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
      
    <title>Имя пользователя и пароль</title>
    <style>    
    hr {
	border: none;
	background-color:red;
	color: red;
	height: 2px;
	}
    </style>

    
    </head>
    <body>


<hr><p><center><b>Изменение имени пользователя и пароля</b></center></p><hr>
<form method="post" action="up_set.php">
<fieldset>
<b>
<p>
Новое имя пользователя: <input type="text" name="user"><br>
Новый пароль: <input type="password" name="password"><br>
Повторно пароль: <input type="password" name="password1"><br>
</b><br>
<input type="submit" value="Подтвердить">
</p>
<br><br>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" МЕНЮ " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>


</html>