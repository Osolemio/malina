<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <title>НАСТРОЙКИ</title>
    
</head>


<body>

<b>


<style>

body {
    background: url(../img/uART_logo.png);
    background-repeat: repeat-xy;
    text-align:center;
}

div {

position:absolute;

}

div.wrapper {
    width:800px;
    height:800px;
    left:50%;
    margin-left:-400px;
}

a {
 position:absolute;
 width:30%;
 height:10%;
  border-radius:10px;
    vertical-align: middle;
    text-align: center;
    text-decoration: none;
      text-shadow: 0 -1px 1px #777;
      font-size: 190%;
      font-weight: 700;
  -moz-border-radius:10px;
  -o-border-radius:10px;
  -webkit-border-radius:10px;
  box-shadow:inset rgba(255,255,255,0.7) 2px 2px 2px, inset rgba(0,0,0,0.3) -2px -2px 2px,
      4px 4px 6px 2px rgba(0,0,0,0.7);
  -moz-box-shadow:inset rgba(255,255,255,0.7) 2px 2px 2px, inset rgba(0,0,0,0.3) -2px -2px 2px,
      4px 4px 6px 2px rgba(0,0,0,0.7);
  -webkit-box-shadow:inset rgba(255,255,255,0.7) 2px 2px 2px, inset rgba(0,0,0,0.3) -2px -2px 2px,
      4px 4px 6px 2px rgba(0,0,0,0.7);


  background: blanchedalmond;

}

a.menu1 {
 top:30px;
 left:10%;
 background: lightgray;
}


a.menu1:hover { 
background: linear-gradient(ivory, lightskyblue) yellow; 
-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

}
a.menu1:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }


a.menu2 {
 top:140px;
 left:10%;
}


a.menu2:hover { 
-moz-transform: scale(2,2);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);
background: linear-gradient(ivory, orange) yellow;
 }


a.menu2:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }


a.menu3 {
 top:250px;
 left:10%;
}


a.menu3:hover { 
-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow; }


a.menu3:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }


a.menu4 {
 top:360px;
 left:10%;
}


a.menu4:hover { 
-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);
background: linear-gradient(ivory, orange) yellow;

 }

a.menu4:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }

a.menu5 {
 top:30px;
 left:45%;
}


a.menu5:hover { 

-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow;
}

a.menu5:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }


a.menu6 {
 top:140px;
 left:45%;
}


a.menu6:hover { 

-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow;
}

a.menu6:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }

a.menu7 {
 top:250px;
 left:45%;
}


a.menu7:hover { 

-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow;
}

a.menu7:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }

a.menu8 {
 top:360px;
 left:45%;
}

a.menu8:hover { 

-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow;
}

a.menu8:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }


a.menu9 {
 top:470px;
 left:10%;
}


a.menu9:hover { 

-moz-transform: scale(1.1,1.1);
 -webkit-transform: scale(1.1,1.1);
  -o-transform: scale(1.1,1.1);
   transform: scale(1.1,1.1);

background: linear-gradient(ivory, orange) yellow;
}

a.menu9:active { background: linear-gradient(#f59500, #f5ae00) #f59500; }





div.div_t {
 position:relative;
 top:20%;

}


</style>

<div class="wrapper">
<a href="../menu.php" class="menu1"><div class="div_t"> ГЛАВНОЕ </div></a>
<br>
<a href=<?php if (file_exists("/var/map/.map")) echo "settings.php"; else echo "#";?> class="menu2"><div class="div_t"> МАП </div></a>
<br>
<a href="sys.php" class="menu3"><div class="div_t">СЕРВИСЫ</div></a>
<br>
<a href="modem.php" class="menu4"><div class="div_t">МОДЕМ</div></a>
<br>
<a href="sms.php" class="menu5"><div class="div_t">СМС</div></a>
<br>
<a href="psys.php" class="menu6"><div class="div_t">ПАРОЛЬ</div></a>
<br>
<a href="syslog.php" class="menu7"><div class="div_t">#syslog</div></a>
<br>
<a href="disk.php" class="menu8"><div class="div_t">Работа с БД</div></a>
<br>
<a href="battery.php" class="menu9"><div class="div_t">БАТАРЕЯ</div></a>
<br>
</div>

</b>
</body>
</html>