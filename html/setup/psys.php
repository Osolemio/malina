<!DOCTYPE html>
<html>
    <head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta charset="UTF-8">
   <?php include('../local/local.inc');?>      
    <title><?php loc('psys_title');?></title>
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


<hr><p><center><b><?php loc('psys_hdr');?></b></center></p><hr>
<form method="post" action="up_set.php">
<fieldset>
<b>
<p>
<?php loc('psys_field1');?>: <input type="text" name="user"><br>
<?php loc('psys_field2');?>: <input type="password" name="password"><br>
<?php loc('psys_field3');?>: <input type="password" name="password1"><br>
</b><br>
<input type="submit" value="<?php loc('confirm');?>">
</p>
<br><br>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>

</body>


</html>