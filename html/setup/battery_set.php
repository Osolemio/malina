<?php

include("./bdb.php");
include('../local/local.inc');

 $today=date("Y-m-d");
 $time=date("H:i");
 touch("/var/map/.bset");

if (isset($_POST['write'])) {
    if (isset($_POST['shared'])) $shared=1; else $shared=0;
    if (isset($_POST['reset_battery']) || isset($_POST['reset_stat'])) $_POST['C_measured']=$_POST['C_nominal'];

    if ($_POST['C_measured']=='0.0') $_POST['C_measured']=$_POST['C_nominal'];
    $result=mysql_query("TRUNCATE TABLE battery_info",$db) or die(mysql_error());

    $query="INSERT INTO battery_info VALUES ('".$_POST['battery_number']."','".$_POST['cells_number']."','".$_POST['start_date']."','".$_POST['server_ip']."','".$_POST['C_nominal']."','".$_POST['C_measured']."
    ','".$_POST['t_nominal']."','".$_POST['battery_alpha']."','".$_POST['peukert']."','".$_POST['coulombic_eff']."','".$_POST['voltage']."
    ','".$_POST['charged_voltage']."','".$_POST['min_charged_current']."','".$_POST['rest_time']."','".$shared."','".$_POST['Umin']."','".$_POST['Umax']."','".$_POST['i_source']."','".$_POST['dod_off']."','".$_POST['ah_off']."','0')";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);

    }

if (isset($_POST['reset_battery']))
    {
    $result=mysql_query("TRUNCATE TABLE battery_state",$db) or die(mysql_error());
    $query="INSERT INTO battery_state VALUES ('1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0')";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    }

if (isset($_POST['sync_battery']))
    {
    $result=mysql_query("TRUNCATE TABLE battery_cycle",$db) or die(mysql_error());
    $query="INSERT INTO battery_cycle VALUES (NULL,'".$today."','".$time."','0','".$_POST['C_measured']."','100','0','0','-1','0','0')";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);

    }

if ($_POST['battery_type']=='LA')
    {
    mysql_query("DROP TABLE work_table") or die(myqsl_error());       
    $query="CREATE TABLE work_table LIKE Lead_acid_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    $query="INSERT INTO work_table SELECT * FROM Lead_acid_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    }

if ($_POST['battery_type']=='LA6')
    { 
    mysql_query("DROP TABLE work_table") or die(myqsl_error());   
    $query="CREATE TABLE work_table LIKE Lead_acid_OCV_6";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    $query="INSERT INTO work_table SELECT * FROM Lead_acid_OCV_6";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    }



if ($_POST['battery_type']=='LI37')
    {
    mysql_query("DROP TABLE work_table") or die(myqsl_error());       
    $query="CREATE TABLE work_table LIKE LiFePo4_37_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    $query="INSERT INTO work_table SELECT * FROM LiFePo4_37_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    }


if ($_POST['battery_type']=='USER')
    {    
    mysql_query("DROP TABLE work_table") or die(myqsl_error());   
    $query="CREATE TABLE work_table LIKE user_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    $query="INSERT INTO work_table SELECT * FROM user_OCV";
    $result=mysql_query($query,$db) or die(mysql_error());
    mysql_free_result($result);
    }


mysql_close($db);

loc('saved_succes');
header("Refresh:2; URL=".$_SERVER['HTTP_REFERER']);
loc('2sec_return');
exit;

?>