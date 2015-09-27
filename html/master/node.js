var load_map=[],load_mppt=[], load_battery, C_bat, t_bat, n_p, C20;

$(function() {

    var str_ip=ip_batmon.split(':');
	var bat_ip=str_ip[0];
	var bat_port=(str_ip[1]==null)?80:str_ip[1];


    if (active_batmon==1) {
	
	$.ajax({
	data: {
	    url: bat_ip,
	    port: bat_port,
	    dev: 'net_battery.php?table=info' 
	},
	method:'post',
	url: 'request.php',
	async: false,
	success: function(response) {
	if (response!=""){
	load_battery=$.parseJSON(response);
	C_bat=Number(load_battery['C_nominal']);
	t_bat=Number(load_battery['t_nominal']);
	n_p=Number(load_battery['n_p']);
	var In=C_bat/t_bat;
	var Cp=Math.pow(In,n_p)*t_bat;
	C20=(Math.pow(Cp/20,1/n_p)*20).toFixed(0);
	}
	},
	error: function() {
	}
	});
	
	
	

	}

    
    setInterval(function() {
        tick();
       
         }, 2000);


});

function tick() {

// battery update;
	var str_ip=ip_batmon.split(':');
	var bat_ip=str_ip[0];
	var bat_port=(str_ip[1]==null)?80:str_ip[1];


    if (active_batmon==1) {
	
	$.ajax({
	data: {
	    url: bat_ip,
	    port: bat_port,
	    dev: 'net_battery.php?table=cycle' 
	},
	method:'post',
	url: 'request.php',
	async: false,
	success: function(response) {
	if (response!=""){
	load_battery=$.parseJSON(response)
	}
	},
	error: function() {
	}
	});
	var cur_p=Number(load_battery['C_current_percent']);
	var cons=Number(load_battery['integral_dCdt']);
	$('#text_battery_ah').html(C20+'Ач');
	$('#text_battery_percent').html(cur_p.toFixed(0)+'%');
	$('#text_battery_cons').html(cons.toFixed(1)+'Ач');
	var cur_p_g=100-cur_p;
	var cur_p_b=(cons/C20)*100; if (cur_p_b>100) cur_p_b=100;
	document.getElementById('battery_percent').style.width=cur_p_g+'%';
	document.getElementById('battery_ah').style.width=cur_p_b+'%';
	}

//------------- nodes cycle ------------------

    for (var key in nodes) {
	    str_ip=nodes[key]['ip'].split(':');
	var ip=str_ip[0];
	var port=(str_ip[1]==null)?80:str_ip[1];


	if ((nodes[key]['dev']&1)==0) {

	$.ajax({
	data: {
	    url: ip,
	    port: port,
	    dev: 'net_map.php' 
	}, 
	method:'post',
	url: 'request.php',
	async: false,
	success: function(response) {
	if (response!=""){
	$('#map'+key).removeClass('pulse');
	load_map[key]=$.parseJSON(response);
	} else {
	$('#map'+key).addClass('pulse');
	document.getElementById('arrow_map_up'+key).style.visibility='hidden';
	document.getElementById('arrow_map_down'+key).style.visibility='hidden';
	document.getElementById('arrow_map_i2c'+key).style.visibility='hidden';
	}
	},
	error: function() {
	$('#map'+key).addClass('pulse');

	document.getElementById('arrow_map_up'+key).style.visibility='hidden';
	document.getElementById('arrow_map_down'+key).style.visibility='hidden';
	document.getElementById('arrow_map_i2c'+key).style.visibility='hidden';

	}
	});
//---------------MAP SECTION ------------------------------------
	if (load_map[key]!=null) {

	$('#text_map_vin'+key).html((load_map[key]['_UNET']>100)?load_map[key]['_UNET']:0);
	$('#text_map_vout'+key).html(load_map[key]['_UOUTmed']);
	$('#text_map_inet'+key).html(load_map[key]['_INET_16_4']);
	$('#text_map_fin'+key).html((6250/load_map[key]['_TFNET']).toFixed(1));
	$('#text_map_fout'+key).html((6250/load_map[key]['_ThFMAP']).toFixed(1));

	var pnet=(load_map[key]['_UNET']*load_map[key]['_INET_16_4']).toFixed(0);    
	$('#text_map_pnet'+key).html(pnet);
	var pacc=(load_map[key]['_Uacc']*load_map[key]['_IAcc_med_A_u16']).toFixed(0);
	$('#text_map_iacc'+key).html(load_map[key]['_IAcc_med_A_u16']);
	$('#text_map_pacc'+key).html(pacc);
	var psumm=0;
	if (load_map[key]['_MODE']==4) psumm=Number(pnet)-Number(pacc); else 
	psumm=Number(pnet)+Number(pacc); 
	$('#text_map_psumm'+key).html(psumm);
	$('#text_map_temp'+key).html(load_map[key]['_Temp_Grad2']+'&degC');
	var pi2c=(load_map[key]['_Uacc']*load_map[key]['_I_mppt_avg']).toFixed(0);
	$('#text_map_pi2c'+key).html(pi2c);
	$('#text_map_ii2c'+key).html(load_map[key]['_I_mppt_avg']);
	if (pi2c>0) document.getElementById('arrow_map_i2c'+key).style.visibility='visible';
	else document.getElementById('arrow_map_i2c'+key).style.visibility='hidden';
	var map_error=Number(load_map[key]['_RSErrSis'])+Number(load_map[key]['_RSErrJobM'])+
			Number(load_map[key]['_RSErrJob'])+Number(load_map[key]['_RSWarning'])
			Number(load_map[key]['_I2C_Err']);
	if (map_error) document.getElementById('led_map_error'+key).style.visibility='visible'; else
			document.getElementById('led_map_error'+key).style.visibility='hidden';
	$('#map_acc_temp'+key).html(load_map[key]['_Temp_Grad0']+'&degC');
	$('#map_vacc'+key).html(load_map[key]['_Uacc']+'В');

	var map_mode=load_map[key]['_MODE'];
	    
	    if (map_mode==0) {
		document.getElementById('led_map_net'+key).style.visibility='visible';
		document.getElementById('led_map_charge'+key).style.visibility='hidden';
		document.getElementById('led_map_relay'+key).style.visibility='hidden';
		document.getElementById('map_stop_acc'+key).style.visibility='visible';
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';
	    }
	    
	    if (map_mode==1) {
		document.getElementById('led_map_net'+key).style.visibility='hidden';
		document.getElementById('led_map_charge'+key).style.visibility='hidden';
		document.getElementById('led_map_relay'+key).style.visibility='hidden';
		document.getElementById('map_stop_acc'+key).style.visibility='visible';
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';

	    }


	    if (map_mode==2) {
		document.getElementById('led_map_net+key').style.visibility='visible';
		document.getElementById('led_map_relay'+key).style.background='red';
		document.getElementById('led_map_charge'+key).style.visibility='hidden';
		document.getElementById('map_stop_acc'+key).style.visibility='visible';
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';
		
	    }
	    
	    if (map_mode==3) {
		document.getElementById('led_map_net'+key).style.visibility='hidden';
		document.getElementById('led_map_relay'+key).style.background='lime';
		document.getElementById('led_map_charge'+key).style.visibility='hidden';
		document.getElementById('map_stop_acc'+key).style.visibility='visible';
		if (load_map[key]['_IAcc_med_A_u16']>0) {
		document.getElementById('arrow_map_up'+key).style.visibility='visible';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';
		document.getElementById('map_stop_add'+key).style.visibility='hidden';
		}
		else
		{
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';
		document.getElementById('map_stop_add'+key).style.visibility='visible';
		}


	    }

	    if (map_mode==4) {
		document.getElementById('led_map_net'+key).style.visibility='hidden';
		document.getElementById('led_map_relay'+key).style.background='lime';
		document.getElementById('led_map_charge'+key).style.visibility='visible';
		if (load_map[key]['_IAcc_med_A_u16']>0) {
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='visible';
		document.getElementById('map_stop_acc'+key).style.visibility='hidden';
		}
		else
		{
		document.getElementById('arrow_map_up'+key).style.visibility='hidden';
		document.getElementById('arrow_map_down'+key).style.visibility='hidden';
		document.getElementById('map_stop_acc'+key).style.visibility='hidden';
		}
		
	    }

	

	} //if load map


    } //if nodes

	if (nodes[key]['dev']==1 || nodes[key]['dev']==2 ) {

	$.ajax({
	data: {
	    url: ip,
	    port: port,
	    dev: 'net_mppt.php' 
	},
	method:'post',
	url: 'request.php',
	async: false,
	success: function(response) {
	if (response!=""){
	$('#mppt'+key).removeClass('pulse');
	load_mppt[key]=$.parseJSON(response)
	} else 
	{
	$('#mppt'+key).addClass('pulse');
	document.getElementById('arrow_mppt'+key).style.visibility='hidden';
	}
	},
	error: function() {
	$('#mppt'+key).addClass('pulse');
	document.getElementById('arrow_mppt'+key).style.visibility='hidden';
	}
	});

    if (load_mppt[key]!=null) {
//-------------MPPT SECTION ---------------------------------------
	$('#text_mppt_vpv'+key).html(load_mppt[key]['Vc_PV']);
	$('#text_mppt_ipv'+key).html(load_mppt[key]['Ic_PV']);
	$('#text_mppt_wind'+key).html((load_mppt[key]['windspeed']==65535)?'н/д':load_mppt[key]['windspeed']);
	$('#text_mppt_ppv'+key).html(load_mppt[key]['P_PV']);
	$('#text_mppt_temp'+key).html(load_mppt[key]['Temp_Int']+'&degC');
	$('#text_mppt_counter'+key).html(load_mppt[key]['Pwr_kW']);
	$('#text_mppt_mode'+key).html(load_mppt[key]['Mode']+load_mppt[key]['Sign']+'&nbspMPP:'+load_mppt[key]['MPP']);
	var pch=(Number(load_mppt[key]['I_Ch'])*Number(load_mppt[key]['V_Bat'])).toFixed(0);
	$('#text_mppt_pacc'+key).html(pch);
	if (pch>0) 
	document.getElementById('arrow_mppt'+key).style.visibility='visible'; else
	document.getElementById('arrow_mppt'+key).style.visibility='hidden';

	$('#mppt_acc_temp'+key).html(load_mppt[key]['Temp_Bat']+'&degC');
	$('#mppt_vacc'+key).html(load_mppt[key]['V_Bat']+'В');

	if (load_mppt[key]['RSErrSis']!=0)
	document.getElementById('led_mppt_error'+key).style.visibility='visible'; else
	document.getElementById('led_mppt_error'+key).style.visibility='hidden';


	var relay=load_mppt[key]['Relay_C'];
	document.getElementById('led_mppt_relay1'+key).style.visibility=((relay&1)==1)?'visible':'hidden';
	document.getElementById('led_mppt_relay2'+key).style.visibility=((relay==2) || (relay==3) || (relay==6) || (relay==7))?'visible':'hidden';
	document.getElementById('led_mppt_relay3'+key).style.visibility=((relay>=4) && (relay<=7))?'visible':'hidden';

	} //if load_mppt    

    } //if nodes

  } //for

}

function setup(index) {

  window.open('http://'+nodes[index]['ip']+'/setup/index.php');

}


function gauges(index) {

  window.open('http://'+nodes[index]['ip']+'/gauges.php');

}