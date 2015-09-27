<?php

   session_start();


   include ("./bd.php");

    $error_status="0000000000000000000000000000000000000000000000000";
	$data=array (
		'UNET'=>0,
		'INET'=>0,
		'CH_ST'=>0,
		'UOUT'=>0,
		'UACC'=>0,
		'IACC'=>0,
		'FNET'=>0,
		'FMAP'=>0,
		'ENET'=>0,
		'EACC'=>0,
		'ECHG'=>0,
		'TEMP1'=>0,
		'TEMP2'=>0,
		'PNET'=>0,
		'PACC'=>0,
	    	'I_CH_I2C'=>-1,
		'MODE'=>0,		
		'VPV'=>0,
		'IPV'=>0,
		'EPV'=>0,	
		'TEMP_MPPT'=>0,
		'EPV'=>0,
		'PPV'=>0,
		'MODE_MPPT'=>'N/A',
		'SIGN'=>' ',
		'MPP'=>'N/A',
		'RELAY'=>0,
		'integral_dCdt'=>0,
		'C_current_Ah'=>0,
		'C_current_percent'=>0,
		'C_nominal'=>0,
		'C_measured'=>0,
		'time_to_go'=>-1,
		'windspeed'=>0,
		'user_counter'=>0,
		'estimated_SOC'=>0,
		'estimated_C'=>0,
		'timer'=>0,
		'E_alt_daily'=>0,
		'E_alt_monthly'=>0,
		'E_alt_summ'=>0,
		'E_alt_user'=>0
	    );
				
	$flag_map=0;
	$flag_mppt=0;		
	$err=array(0,0,0,0,0);	
	$mppt_present=0;
	$u_min='off';
	$u_max='off';
	$t_min='off';
	$t_max='off';

//-------------------BMS ---------------------
	
	$result_bms=mysql_query("SELECT MIN(u),MAX(u),MIN(t),MAX(t) FROM bms",$db);
   	 if ($result_bms==NULL) 
    	  {
			$u_min="off"; $u_max="off"; $t_min="off"; $t_max="off";
      		} else
      		{
			$row_bms=mysql_fetch_array($result_bms);
	      		$u_min=($row_bms[0]==NULL)?"off":$row_bms[0];
			    $u_max=($row_bms[1]==NULL)?"off":$row_bms[1];
			    $t_min=($row_bms[2]==NULL)?"off":$row_bms[2];
			    $t_max=($row_bms[3]==NULL)?"off":$row_bms[3];
      
      		}    
     if ($row_bms[2]==127) 
    	 {
        	$t_min="off";$t_max="off";
     	} else 
     	if ($row_bms[3]==127) $t_max=$t_min;
	      
    mysql_free_result($result_bms);   


//------------- Set arrays and read data MAP

if (file_exists("/var/map/.map")) {
	
	 
	 $result=mysql_query("SELECT * FROM data WHERE number = (SELECT MAX(number) FROM data)",$db) or die(mysql_error());     
   	 $row = mysql_fetch_assoc($result);


    	if (isset($_SESSION['n_gauges']) && $row['number']<=$_SESSION['n_gauges']) $flag_map=0; else $flag_map=1;
	    $_SESSION['n_gauges']=$row['number'];

	mysql_free_result($result);
	$result=mysql_query("SELECT value from settings WHERE offset=342", $db) or die(mysql_error());
	 $row1=mysql_fetch_array($result);
	$mppt_present=$row1[0];

	 if ($mppt_present==2 || $mppt_present==3) 
		$data['I_CH_I2C']=$row['_I_mppt_avg']; 
 


    $data['PNET']=round($row['_INET_16_4']*$row['_UNET']);
    $data['PACC']=round($row['_IAcc_med_A_u16']*$row['_Uacc']);
    if ($row['_MODE']==4) 
    $data['IACC']=$row['_IAcc_med_A_u16']; else $data['IACC']=0-$row['_IAcc_med_A_u16']; 
    if (($row['_MODE']==2 || $row['_MODE']==0) && $row['_UNET']==100) $row['_UNET']=0;
	$data['UNET']=$row['_UNET'];
	$data['INET']=$row['_INET_16_4'];    
    $data['UOUT']=$row['_UOUTmed'];
    $data['UACC']=$row['_Uacc'];
    $data['CH_ST']=($row['_Status_Char']&1 == 1)?3:$row['_Status_Char'];
    $data['IACC']=number_format($data['IACC'],1,".","");
    $data['FNET']=$row['_TFNET'];
    $data['FMAP']=$row['_ThFMAP'];
    $data['ENET']=$row['_E_NET'];
    $data['EACC']=$row['_E_ACC'];
    $data['ECHG']=$row['_E_ACC_CHARGE'];
    $data['TEMP1']=$row['_Temp_Grad0'];
    $data['TEMP2']=$row['_Temp_Grad2'];
    $data['MODE']=$row['_MODE'];
    
    
    if ($row['_RSErrSis']==0) {$error_status="0";} else {$error_status="1";}
    $err[0]=$row['_RSErrJobM'];
    $err[1]=$row['_RSErrJob'];
    $err[2]=$row['_RSWarning'];
    $err[3]=$row['_F_Acc_Over'];
    $err[4]=$row['_F_Net_Over'];
   
	mysql_free_result($result);   
	
}


//------------- Set arrays and read data MPPT

if (file_exists("/var/map/.mppt")) {
	
	
	 
	 $result_mppt=mysql_query("SELECT * FROM mppt WHERE number = (SELECT MAX(number) FROM mppt)",$db) or die(mysql_error());     
     $row_mppt = mysql_fetch_assoc($result_mppt);
    
     if (isset($_SESSION['n_gauges_mppt']) && $row_mppt['number']<=$_SESSION['n_gauges_mppt']) $flag_mppt=0; else $flag_mppt=1;
     $_SESSION['n_gauges_mppt']=$row_mppt['number'];
	
	 $err[5]=$row_mppt['RSErrSis'];
	 

     $data['VPV']=$row_mppt['Vc_PV'];
     $data['IPV']=$row_mppt['Ic_PV'];
     $data['EPV']=$row_mppt['Pwr_kW'];
     $data['TEMP_MPPT']=$row_mppt['Temp_Int'];
     $data['PPV']=$row_mppt['P_Out'];
     $data['MODE_MPPT']=$row_mppt['Mode'];
     $data['SIGN']=$row_mppt['Sign'];
     $data['MPP']=$row_mppt['MPP'];
     $data['RELAY']=$row_mppt['Relay_C'];
     $data['windspeed']=($row_mppt['windspeed']==65535)?0:$row_mppt['windspeed'];     

 if (!file_exists("/var/map/.map")) {
 	$data['UACC']=$row_mppt['V_Bat'];
 	$data['IACC']=$row_mppt['I_Ch'];
 	$data['TEMP1']=$row_mppt['Temp_Bat'];	
 	
 }


	 mysql_free_result($result_mppt);
     
     
}
	
//----------------- prepare error binary string    
   
    for ($i=0;$i<=5;$i++)
      {
	for ($i1=0;$i1<=7;$i1++)
	    {
	    if ((($err[$i]>>$i1)&1)==1) {$error_status.="1"; }
	    else { $error_status.="0";}
	    }
	}	
	
	mysql_close($db);

//---------------------- read battrery status
	if (file_exists("/var/map/.bmon")) {
	include ("./bd_bat.php");
	$result=mysql_query("SELECT * FROM battery_cycle WHERE number = (SELECT MAX(number) FROM battery_cycle)",$db_bat) or die(mysql_error());     
	 $row = mysql_fetch_assoc($result);
	
	$data['user_counter']=round($row['user_counter'],1);
	$data['integral_dCdt']=round($row['integral_dCdt'],1);
	$data['C_current_Ah']=round($row['C_current_Ah'],2);
	$data['C_current_percent']=round($row['C_current_percent'],1);
	$I_avg=$row['I_avg'];
	$data['estimated_SOC']=$row['estimated_SOC'];
	$data['timer']=$row['timer'];	
	

	mysql_free_result($result);
	
	$result=mysql_query("SELECT C_nominal,C_measured, n_p, alpha, t_nominal FROM battery_info WHERE id = 0",$db_bat) or die(mysql_error());     
	$row = mysql_fetch_assoc($result);
	
	$data['C_nominal']=$row['C_nominal'];
	$alpha=$row['alpha'];
	$In=$row['C_nominal']/$row['t_nominal'];

	$data['C_measured']=$row['C_measured']*(1+$alpha*($data['TEMP1']-25));
	
	$n_p=$row['n_p'];
	$C_peukert=round(pow($In,$n_p)*$row['t_nominal']*(1+$alpha*($data['TEMP1']-25)),1);
	$C20=round(pow($C_peukert/20,1/$n_p)*20,1);


	if ($I_avg<0)
	{
	$C_real=round($data['C_measured']/pow((abs($I_avg)/$In),$n_p-1),1);
	$data['C_measured']=$C_real;
	$time_to_go_min=(($C_peukert*(1+$alpha*($data['TEMP1']-25)))/(pow(abs($I_avg),$n_p))*60);
	$pc_min=100/$time_to_go_min;
	$data['time_to_go']=round(($data['C_current_percent']/$pc_min),0);
	
	}
	else
	$data['C_measured']=$C20;
	

	mysql_free_result($result);

	$result=mysql_query("SELECT estimated_C FROM estimate WHERE number = (SELECT MAX(number) FROM estimate)",$db_bat) or die(mysql_error());
	if ($result!=NULL) 
	    {
	    $row=mysql_fetch_array($result);
	    $data['estimated_C']=($row[0]>0)?$row[0]:0;
	    mysql_free_result($result);
	    }

	$result=mysql_query("SELECT * FROM battery_state WHERE number = 1",$db_bat) or die(mysql_error());
	if ($result!=NULL) 
	    {
	    $row=mysql_fetch_assoc($result);
	    $data['E_alt_daily']=$row['E_alt_daily'];
	    $data['E_alt_monthly']=$row['E_alt_monthly'];
	    $data['E_alt_summ']=$row['E_alt_summary'];
	    $data['E_alt_user']=$row['E_alt_user'];
	    
	    mysql_free_result($result);
	    }


	mysql_close($db_bat);
	}
//----------------------------------------------------


    echo $data['UNET'].",".$data['INET'].",".$data['UOUT'].",".$data['UACC'].",".$data['IACC'].",".$data['VPV'].",".$data['IPV'].",".$data['FNET'].",".$data['FMAP'].",". $data['ENET'].','.$data['EACC'].",".$data['ECHG'].",".$data['I_CH_I2C'].",".$data['EPV'].",".$data['TEMP1'].",".$data['TEMP2'].",".$data['TEMP_MPPT'].",".$data['PNET'].",".$data['PACC'].",".
$data['PPV'].",".$data['MODE_MPPT'].$data['SIGN']."MPP:".$data['MPP'].",".$data['MODE'].",".$data['RELAY'].",".$error_status.",".$u_min.",".$u_max.",".$t_min.",".$t_max.",".$flag_map.",".$flag_mppt.",".
$data['integral_dCdt'].",".$data['C_current_Ah'].",".$data['C_current_percent'].",".
$data['C_nominal'].",".$data['C_measured'].",".$data['time_to_go'].",".$data['windspeed'].",".$data['user_counter'].",".$data['estimated_SOC'].",".$data['estimated_C'].",".$data['timer'].",".$data['CH_ST'].",".
$data['E_alt_daily'].",".$data['E_alt_monthly'].",".$data['E_alt_summ'].",".$data['E_alt_user'];
     
    







?>
