<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="UTF-8">
  <link rel="stylesheet" href="dist/themes/default/style.min.css" />
  <script src="dist/libs/jquery.js"></script>
  <script src="dist/jstree.min.js"></script>  
  <script src="nodes.js"></script>
<?php include('../local/local.inc');?>
<title><?php loc('ms_set_title');?></title>
    
</head>


<body>
<div style="background-color:#F0F0F0; border: 4px double gray;">
<fieldset>
<div id="nodes"></div>
</fieldset>
<br>
<fieldset>
<div id="fields">
    <b><?php loc('m_set_name');?>:</b>&nbsp<input type="text" value="" id="name"/>
    <b>IP&nbsp<?php loc('m_set_address');?></b>&nbsp<input type="text" value="" id="ip"/>
    <b><?php loc('m_set_acc_no');?>:</b>&nbsp<input type="text" value="" id="acc_number"/>
    <b><?php loc('m_set_connected');?>:</b>&nbsp<select size=1 id="devices">
	<option value="0"><?php loc('MAC');?></option>
	<option value="1"><?php loc('MPPT');?></option>
	<option value="2"><?php loc('MAC');?>+<?php loc('MPPT');?></option>
    </select> 
</div>

</fieldset>
<br>
<div><input type="button" style="background-color:lightgreen;" value="<?php loc('m_set_button1');?>" onclick=create_node() />

&nbsp&nbsp<input type="button" style="background-color:orange;" value="<?php loc('m_set_button2');?>" onclick=delete_node() />

&nbsp&nbsp<input type="button" style="background-color:lightblue;" value="<?php loc('m_set_button3');?>" onclick=save() />

&nbsp&nbsp<input type="button" style="color:white; background-color:red;" value="<?php loc('m_set_button4');?>" onclick=erase() /></div>
<br>
<div><input type="button" style="background-color:blanchedalmond;" value="<?php loc('m_set_button5');?>" onclick=close_all() />
&nbsp&nbsp
<input type="button" style="background-color:blanchedalmond;" value="<?php loc('m_set_button6');?>" onclick=open_all() />

</div>
</div>
<br><br>

<div style="background-color:aquamarine; border: 4px double gray;">
<fieldset>
    <b>IP <?php loc('m_set_batmon');?>:</b>&nbsp<input type="text" value="127.0.0.1" id="batmon_ip"/>
    <b><?php loc('m_set_batmon_act');?>:</b>&nbsp<select size=1 id="batmon_active" name="<?php loc('m_set_batmon_act');?>">
	<option value="0"><?php loc('NO');?></option>
	<option value="1"><?php loc('YES');?></option>
    </select>

</fieldset>
<br>
<input type="button" style="background-color:lightblue;" value="<?php loc('save');?>" onclick=save_batmon() />
</div>
<br><br>
<div>
<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="index.php";
}
</script>
</div>



</body>
</html>