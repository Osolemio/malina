<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
<?php include('./local/local_ru.inc');?>
  <title><?php loc('graph_chose');?></title>
    
    
</head>


<body>
<hr>
<center><?php loc('multichart_header');?></center>
<hr>

<form method="post" action="multichart.php">

<?php 

if (file_exists("/var/map/.map")) $map_exists=true; else $map_exists=false;


if (file_exists("/var/map/.mppt")) $mppt_exists=true; else $mppt_exists=false;

?>
<b>
<p>
<fieldset>
    <input type="checkbox" name="acc" checked disabled/> <?php loc('acc_graph');?>
    <input type="checkbox" name="umap" checked <?php if (!$map_exists) echo "disabled" ?>/> <?php loc('u_inout_mac');?>
    <input type="checkbox" name="imap" checked <?php if (!$map_exists) echo "disabled" ?>/> <?php loc('i_p_mac_mppt');?>
    <input type="checkbox" name="imppt" <?php if (!$mppt_exists) echo "disabled" ?>/> <?php loc('i_mppt');?>
    <input type="checkbox" name="iacc" checked <?php if (!$mppt_exists) echo "disabled" ?>/> <?php loc('acc_balance');?>
    <input type="checkbox" name="wind"  <?php if (!$mppt_exists) echo "disabled" ?>/> <?php loc('wind_rpm');?>
</fieldset>
<p>
<input type="submit" value="<?php loc('show');?>">
</form>

<input TYPE="button" style="font-weight:bolder; background-color:darkkhaki;" VALUE=" <?php loc('MENU');?> " ONCLICK="HomeButton()"> 
<script>
function HomeButton()
{
location.href="menu.php";
}
</script>

</body>
</html>
    