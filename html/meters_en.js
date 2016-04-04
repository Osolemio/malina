var d = document;
var offsetfromcursorY=15 // y offset of tooltip
var ie=d.all && !window.opera;
var ns6=d.getElementById && !d.all;
var tipobj,op,consumed=0;

var meter_v, meter_i, meter_i_i2c, meter_vout, meter_vacc, meter_iacc, meter_vpv,meter_ipv, v = 0, f, arr=[],
display_1, display_2, onload, counter_net, counter_acc,counter_charge, counter_pv,
display_3, display_4,display_5, display_6, i=1,angle=0,
    map_mode0="&nbspВЫКЛЮЧЕН. НЕТ СЕТИ",
    map_mode1="&nbspВЫКЛЮЧЕН. ЕСТЬ СЕТЬ",
    map_mode2="&nbspВКЛЮЧЕН. НЕТ СЕТИ. ГЕНЕРАЦИЯ",
    map_mode3="&nbspВКЛЮЧЕН. ТРАНСЛИРУЕТ СЕТЬ",
    map_mode4="&nbspВКЛЮЧЕН. ТРАНСЛИРУЕТ СЕТЬ. ЗАРЯД АКБ";
    map_mode=["МАП выключени и нет сети на входе","МАП выключен. Сеть на входе","МАП включен. Генерация от АКБ. Нет сети.","МАП включен и транслирует сеть","МАП включен. Трансляция + заряд","","","","","",
    "Принудительная генерация","Тарифная сеть. максимальный тариф. принудительная генерация", "Тарифная сеть. минимальный тариф",
    "ECO-mixing", "Трансляция + продажа в сеть", "Ожидание внешнего полного заряда", "Тарифная сеть. трансляция+эко-подкачка",
    "Тарифная сеть. трансляция+продажа в сеть"];
    charge_mode=["","","1 ступень","Буферный заряд","","","2 ступень","","","","Дозаряд","","","","Дозаряд"],
    error_status=[
    "Критическая ошибка!",
    "АКБ полностью разряжен, напряжение критически низкое для работы МАП.",
    "Превышение напряжения на АКБ, генерация или заряд временно приостановлен.",
    "Ток КЗ по АКБ при заряде, заряд временно приостановлен.",
    "Ток КЗ по АКБ, генерация или заряд временно приостановлен.",
    "Возможно залипание основного реле, необходим ремонт",
    "Ток КЗ по сети 220В, произойдет переход на генерацию с возможным дальнейшим отключением по КЗ АКБ.",
    "На выходе 220В постороннее напряжение. Генерация будет отключена!",
    "Произошел сброс программы",
    "АКБ полностью разряжен, МАП будет работать еще 1 минуту",
    "Перегрузка по АКБ, генерация продолжится в течении 10сек",
    "Нагрузка выше номинальной мощности. Работа ограниченное время",
    "Превышение температуры, приостановка генерации или заряда.",
    "Ошибка вентилятора",
    "Перегрузка по сети. Переход на генерацию",
    "Сбой режима работы (гроза или помеха)",
    "Выключение по многократному КЗ по заряду. Ошибка снимается через 2 ч.",
    "",
    "Сетевое напряжение вне диапазона",
    "Постороннее напряжение на выходе 220В, или большие выбросы от нагрузки",
    "Выбросы напряжения в сети 220В по входу",
    "Залипла кнопка СТАРТ или ЗАРЯД",
    "Переход на заряд невозможен - нет сети на входе",
    "Мощность нагрузки > установленной максимальной",
    "Сеть нестабильна",
    "Отключение по перегрузке по току АКБ, 10 попыток перезапуска.",
    "Отключение по перегрузке по току АКБ во время заряда, 10 попыток перезапуска.",
    "Отключение по перегрузке. Критически низкое напряжение АКБ.",
    "Неисправность вентилятора. 10 попыток перезапуска.",
    "Отключение по мощности > номинала в течение 30 минут.",
    "Отключение по полному разряду АКБ",
    "Отключение. Температура вне диапазона",
    "Полное отключение по многократным перегрузкам АКБ",
    "Отключение генерации. На выходе постороннее напряжение или реле неисправно",
    "Переход на генерацию. Перегрузка по сети",
    "","","","","","Выключение по многократным перегрузкам по сети",
    "MPPT: Перенапряжение СП",
    "MPPT: Перенапряжение АКБ",
    "MPPT: Перезаряд АКБ",
    "MPPT: Короткое замыкание по АКБ",
    "MPPT: Перегрев АКБ",
    "MPPT: Ошибка связи I2C",""
    ], err_to_screen=[], voltage=[10,15,20,30,40,60], Voltage_eeprom=0, Power_eeprom=0, power=[1.3,1.5,2,3,4.5,6,9,12,15,18,24,36], v=[12,24,48,96];

function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

$(function() {
  
  var xmlhttp=getXmlHttp();
  xmlhttp.open('GET','./request_eeprom.php?offset=6',false);
    xmlhttp.send(null);
    if (xmlhttp.status==200) {
    Voltage_eeprom=xmlhttp.responseText;
   }
  
  xmlhttp=getXmlHttp();
  xmlhttp.open('GET','./request_eeprom.php?offset=5',false);
    xmlhttp.send(null);
    if (xmlhttp.status==200) {
    Power_eeprom=xmlhttp.responseText;
   }

  var limit_acc=Math.round(power[Power_eeprom]*10/v[Voltage_eeprom])*100;        

  meter_v = new JSGadget.Meter($("#meter_v"), {
    title: "Vin",
    gap: 10,
    min: 160,
    max: 280,
    scale: { w: 1, lm: {s:20, w: 1, l: 2, f: 4.5 },
      sm: { s: 2, w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
    
meter_i = new JSGadget.Meter($("#meter_i"), {
    title: "Iin,A",
    gap: 10,
    min: 0,
    max: 50,
    scale: { w: 1, lm: {s: 10, w: 1, l: 2, f: 4.5 },
      sm: { s: 2, w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
    
meter_vout = new JSGadget.Meter($("#meter_vout"), {
    title: "Vout",
    gap: 10,
    min: 160,
    max: 280,
    scale: { w: 1, lm: {s: 20, w: 1, l: 2, f: 4.5 },
      sm: { s: 2, w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});

meter_i_i2c=new JSGadget.Meter($("#meter_i_i2c"), {
    title: "Isum,A",
    gap: 10,
    min: 0,
    max: 250,
    scale: { w: 1, lm: {s: 50, w: 1, l: 2, f: 4.5 },
      sm: { s: 10, w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }}); 

if (document.getElementById('map')) {
meter_vacc = new JSGadget.Meter($("#meter_vacc"), {
    title: "Vacc",
    gap: 10,
    min: voltage[Voltage_eeprom*2],
    max: voltage[Voltage_eeprom*2+1],
    scale: { w: 1, lm: {s: 2, w: 1, l: 2, f: 4.5 },
      sm: { s: 0.5, w: 0.5, l: 1.5 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
}
else
{

   meter_vacc = new JSGadget.Meter($("#meter_vacc"), {
    title: "Vacc",
    gap: 10,
    min: 10, 
    max: 60,
    scale: { w: 1, lm: {s: 5, w: 1, l: 2, f: 4.5 },
      sm: { s: 0.5, w: 0.5, l: 1.5 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
}

if (document.getElementById('map')) {
meter_iacc = new JSGadget.Meter($("#meter_iacc"), {
    title: "Iacc-mac,A",
    gap: 10,
    min: -limit_acc,
    max: limit_acc,
    scale: { w: 1, lm: {s: Math.round(limit_acc/3), w: 1, l: 2, f: 4.5 },
      sm: { s: Math.round(limit_acc/20), w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
}
else
{
limit_acc=100;
meter_iacc = new JSGadget.Meter($("#meter_iacc"), {
    title: "Iacc,A",
    gap: 10,
    min: -limit_acc,
    max: limit_acc,
    scale: { w: 1, lm: {s: Math.round(limit_acc/3), w: 1, l: 2, f: 4.5 },
      sm: { s: Math.round(limit_acc/20), w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
}



meter_vpv = new JSGadget.Meter($("#meter_vpv"), {
    title: "V",
    gap: 10,
    min: 40,
    max: 250,
    scale: { w: 1, lm: {s: 40, w: 1, l: 2, f: 4.5 },
      sm: { s: 10, w: 0.5, l: 1.5 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});

    meter_ipv = new JSGadget.Meter($("#meter_ipv"), {
    title: "I,A",
    gap: 10,
    min: 0,
    max: 150,
    scale: { w: 1, lm: {s: 20, w: 1, l: 2, f: 4.5 },
      sm: { s: 2, w: 0.5, l: 1 }
           },
    hand: { l: 38 },
    font: {
      size: "bold 20"
    }});
    
    display_1 = new JSGadget.Display("#display_1",
		{digits:3,
		color:"lime"});
    display_2 = new JSGadget.Display("#display_2",
		{digits:3,
		color:"lime"});
    display_3 = new JSGadget.Display("#display_3",
		{digits:2,
		color:"red"});
    display_4 = new JSGadget.Display("#display_4",
		{digits:3,
		color:"red"});
    display_5 = new JSGadget.Display("#display_5",
		{digits:2,
		color:"red"});
    display_6 = new JSGadget.Display("#display_6",
		{digits:2,
		color:"red"});
    

    counter_net = new JSGadget.Counter("#counter1",
		{digits:9,
		color: "white",
		aniMs: 600
		});    
    counter_acc = new JSGadget.Counter("#counter2",
		{digits:9,
		color: "white",
		aniMs: 600
		});    
    counter_charge = new JSGadget.Counter("#counter3",
		{digits:9,
		color: "white",
		aniMs: 600
		});    
    counter_pv = new JSGadget.Counter("#counter4",
		{digits:9,
		color: "white",
		aniMs: 600
		}); 

//------------ MAP Live chart
    if (document.getElementById('map')) {
    var chart_map = new SmoothieChart({millisPerPixel:100,grid:{fillStyle:'#ffffff'},labels:{fillStyle:'#000000'}});
    series_map = new TimeSeries();    
    chart_map.addTimeSeries(series_map, {lineWidth:3,strokeStyle:'#0000ff'});

    var canvas_map = document.getElementById('smoothie-chart-map');
    canvas_map.width=document.getElementById('smoothie-chart-map-div').clientWidth-1;
    canvas_map.height=document.getElementById('smoothie-chart-map-div').clientHeight-1;

    chart_map.streamTo(canvas_map);	
    series_map.append(new Date().getTime(),0)
}
//-----------MPPT Live chart
    if (document.getElementById('mppt')) {
    var chart_mppt = new SmoothieChart({millisPerPixel:100,grid:{fillStyle:'#ffffff'},labels:{fillStyle:'#000000'}});
    series_mppt = new TimeSeries();    
    chart_mppt.addTimeSeries(series_mppt, {lineWidth:3,strokeStyle:'green'});
    
    var canvas_mppt = document.getElementById('smoothie-chart-mppt');
    canvas_mppt.width=document.getElementById('smoothie-chart-mppt-div').clientWidth-1;
    canvas_mppt.height=document.getElementById('smoothie-chart-mppt-div').clientHeight-1;

    chart_mppt.streamTo(canvas_mppt);	
    series_mppt.append(new Date().getTime(),0)    

    var chart_wind = new SmoothieChart({millisPerPixel:100,grid:{fillStyle:'#ffffff'},labels:{fillStyle:'#000000'}});
    series_wind = new TimeSeries();    
    chart_wind.addTimeSeries(series_wind, {lineWidth:3,strokeStyle:'deeppink'});
    
    var canvas_wind = document.getElementById('smoothie-chart-wind');
    canvas_wind.width=document.getElementById('smoothie-chart-wind-div').clientWidth-1;
//    canvas_wind.height=document.getElementById('smoothie-chart-wind-div').clientHeight-1;

    chart_wind.streamTo(canvas_wind);	
    series_wind.append(new Date().getTime(),0)    

}
//------------ACC Live chart-------------

    var chart_acc = new SmoothieChart({millisPerPixel:100,grid:{fillStyle:'#ffffff'},labels:{fillStyle:'#000000'}});
    series_acc = new TimeSeries();    
    chart_acc.addTimeSeries(series_acc, {lineWidth:3,strokeStyle:'black'});

    var canvas_acc = document.getElementById('smoothie-chart-acc');
    canvas_acc.width=document.getElementById('smoothie-chart-acc-div').clientWidth-1;
    canvas_acc.height=document.getElementById('smoothie-chart-acc-div').clientHeight-1;
    chart_acc.streamTo(canvas_acc);	
    series_acc.append(new Date().getTime(),0)

//----------- add event listener ----------------
    document.getElementById("user_counter").addEventListener("click", counter_reset, false);


    setInterval(function() {
        tick();
       
         }, 1000);
     tick();
  });


 function counter_reset() {
    $.ajax({
	url: 'breq.php?action=1',
	type: "GET"
	
    });

    alert("Счетчик обнулен");

    } 


 function dataRequest () {
    HTR = ('v' == '\v') ? new ActiveXObject ('Microsoft.XMLHTTP') 
			: new XMLHttpRequest ();
    HTR.open ('get', './request_g.php');

    HTR.onreadystatechange = function ()
           {
          if (HTR.readyState == 4)
                {
              var Z = HTR.responseText;
              arr = Z.split(',');
                }
             }
     HTR.send (null);
       }
   
  function move(elem, elem1, value, limit) {
	if (elem) {
	elem.style.width=((value/limit)*0.9)*100+1+'%';
	elem1.style.left=((value/limit)*0.9)*100+4+'%';
	}
	}
  
  function mppt_relay(value) {
	var index=[0,1,2,4];
	if (document.getElementById('mppt')) {
	for (var i=1;i<=3;i++)
	    {
		if ((value&index[i])>0) 
		    {
		    document.getElementById('mppt_relay'+i).style.background="blue";
		    document.getElementById('mppt_relay'+i).style.top="2%";
		    } else
		    {
		    document.getElementById('mppt_relay'+i).style.background="gray";
		    document.getElementById('mppt_relay'+i).style.top="60%";
		    }
	    }
   }
  }
    

  function err_warn(code) {
          var i,dd, result="";
          time=new Date();
          
      for (i=0;i<code.length;i++)
       {
	if (code.charAt(i)==1) 
	    {
	    result=time.toLocaleTimeString()+":"+error_status[i]+"<br>";
	    err_to_screen.push(result); result="";
	    }
	if (err_to_screen.length==50) { delete err_to_screen.shift();}       
	}
       result="";    
       for (i=err_to_screen.length-1;i>=0;i--) result=result+err_to_screen[i];
       return result;
  }
  
	function ietruebody()  {
	return (d.compatMode && d.compatMode!="BackCompat")? d.documentElement : d.body
	}

	function positiontip(e) {

	var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
	var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
	var winwidth=ie? ietruebody().clientWidth : window.innerWidth-20;
	var winheight=ie? ietruebody().clientHeight : window.innerHeight-20;
	var rightedge=ie? winwidth-event.clientX : winwidth-e.clientX;
	var bottomedge=ie? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY;
	if (rightedge < tipobj.offsetWidth)  tipobj.style.left=curX-tipobj.offsetWidth+"px";
	    else tipobj.style.left=curX+"px";
	    if (bottomedge < tipobj.offsetHeight) tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px";
	    else tipobj.style.top=curY+offsetfromcursorY+"px";
	}
	
    function tooltip(el,txt) {
	    tipobj=document.getElementById('mess');
	    tipobj.innerHTML = txt;
	    tipobj.style.visibility="visible";
	    el.onmousemove=positiontip;
	    }


    function hide_info(el) {
	document.getElementById('mess').style.visibility='hidden';
	}



  function tick() {
    onload=dataRequest();

// $(document).ready(function () {

//---------MAP SECTION --------------------

if (document.getElementById('map')) {
//----meters
    
var mv=document.getElementById('meter_v');
var mi=document.getElementById('meter_i');
var mvout=document.getElementById('meter_vout');
var iacc=document.getElementById('meter_iacc');
var vacc=document.getElementById('meter_vacc');
var ii2c=document.getElementById('meter_i_i2c');
var alt_d=$('#alt_daily');
var alt_m=$('#alt_monthly');
var alt_s=$('#alt_summary');
var alt_u=$('#alt_user');

    if (Number(arr[28])==0)
    {     
	mv.style.background="lightslategray";
	mi.style.background="lightslategray";
	 ii2c.style.background="lightslategray"
	mvout.style.background="lightslategray";    
	if (iacc) iacc.style.background="lightslategray";
	if (vacc) vacc.style.background="lightslategray";    
    }    
    else 
    {    
    mv.style.background="white";
    mvout.style.background="white";    
    mi.style.background="white";
    ii2c.style.background="white"
    if (vacc) vacc.style.background="white";    
    if (iacc) iacc.style.background="white";
    }    

    if (arr[12]==-1) document.getElementById('meter_o7').style.background="gray" ; else 
	document.getElementById('meter_o7').style.background="green"

    if (alt_d) alt_d.html(arr[42]);
    if (alt_m) alt_m.html(arr[43]);
    if (alt_s) alt_s.html(arr[44]);
    if (alt_u) alt_u.html(arr[45]);

    if (arr[49]&1) { $('#input2').css('background','lime');$('#input1').css('background','orange');} else
		    { $('#input2').css('background','orange');$('#input1').css('background','lime');} 
    if (arr[49]&4) $('#input2').css('background','lightskyblue');

    meter_v.setVal(arr[0]);
    if (Number(arr[0])<150) 
    document.getElementById('meter_o').style.background="red"
    else 
    document.getElementById('meter_o').style.background="blue";
    
    meter_i.setVal(arr[1]);
    meter_vout.setVal(arr[2]);
    meter_i_i2c.setVal((arr[12]<0)?0:arr[12]);
    $('#text_v').html(arr[0]);
    $('#text_i').html(arr[1]);
    $('#text_vout').html(arr[2]);
    $('#text_i2c').html((arr[12]<0)?'ОТКЛ':arr[12]);
    display_1.setVal((6250/arr[7]).toFixed(1));
    display_2.setVal((6250/arr[8]).toFixed(1));
    display_3.setVal(arr[15]);
    display_6.setVal(arr[46]);
    var relay_container1=document.getElementById("relay_container1");
    var relay_container2=document.getElementById("relay_container2");
    var relay_slider1=document.getElementById("map_slider1");
    var relay_slider2=document.getElementById("map_slider2");
    if (arr[47]>0)
	{
	    relay_container1.className="relay_container slider_on";
	    relay_slider1.className="map_relay_slider checked";

	} else
	{
	    relay_container1.className="relay_container";
	    relay_slider1.className="map_relay_slider";
	}

    if (arr[48]>0)
	{
	    relay_container2.className="relay_container slider_on";
	    relay_slider2.className="map_relay_slider checked";

	} else
	{
	    relay_container2.className="relay_container";
	    relay_slider2.className="map_relay_slider";
	}



    if (i==1) {i=0; $('#text1').html(">&nbspHz&nbsp>");}
    else {i=1;$('#text1').html("<b>></b>&nbspHz&nbsp<b>><b>");}


      counter_net.setVal(arr[9]/100);
      counter_acc.setVal(arr[10]/100);
      counter_charge.setVal(arr[11]/100);
	
	$('#text_mode').html("MAC mode");

          switch (arr[21]) {
      
        case "0":
    		$('#map_mode_in').html(map_mode0);
    		document.getElementById("map_mode_in").style.background="aqua";
    		break
        case "1":	
    		$('#map_mode_in').html(map_mode1);
    		document.getElementById("map_mode_in").style.background="limegreen";
    		break
        case "2":
    		$('#map_mode_in').html(map_mode2);
    		document.getElementById("map_mode_in").style.background="lightcoral";
    		break
        case "3":
    		$('#map_mode_in').html(map_mode3);
    		document.getElementById("map_mode_in").style.background="springgreen";
    		break
        case "4":
    		$('#map_mode_in').html(map_mode4+".<br>"+charge_mode[arr[41]]);
    		document.getElementById("map_mode_in").style.background="lightskyblue";
    		break
    	default:
		if (arr[21]>=10 && arr[21]<=17)
		    {
		      $('#map_mode_in').html(map_mode[arr[21]].toUpperCase());
    		document.getElementById("map_mode_in").style.background="springgreen";

		    } else 
    		$('#map_mode_in').html("UDEFINED");
    	
	  
      }
    
    series_map.append(new Date().getTime(),Number(arr[17]));    
    

} //----------MAP SECTION-------------------




//----------------MPPT SECTION


if (document.getElementById('mppt')) {

var mvpv=document.getElementById('meter_vpv');
var mipv=document.getElementById('meter_ipv')


    if (Number(arr[29])==0)
    {     
	if (mvpv) mvpv.style.background="lightslategray";
	if (mipv) mipv.style.background="lightslategray";
    }    
    else 
    {    
    if (mvpv) mvpv.style.background="white";
    if (mipv) mipv.style.background="white";    
    }    
    
    
    meter_vpv.setVal(arr[5]);
    meter_ipv.setVal(arr[6]);

    
    display_5.setVal(arr[16]);
    $('#text_vpv').html(arr[5]);
    $('#text_ipv').html(arr[6]);
    $('#text_kw_pv').html("kWh per day");
    $('#mppt_text').html("&nbsp"+arr[20]);
    
    counter_pv.setVal(arr[13]);

    fd=mppt_relay(Number(arr[22]));// set relay status

    if (arr[36]>0 && arr[36]<1000) 
	    {
		angle+=20; if (angle>=360) angle=0;
		$('#ventilator').rotate(angle);

	    }
    
    $('#speed').html(arr[36]+"&nbspmin<sup>-1</sup>");
    
    series_mppt.append(new Date().getTime(),Number(arr[19]));
    series_wind.append(new Date().getTime(),Number(arr[36]));

} //-----------MPPT SECTION


//--------------------- ACC SECTION -----------------------

$('#bms_tmin').html(arr[26]);if (arr[26]=='off') document.getElementById("bms_tmin").style.color="gray"; else document.getElementById("bms_tmin").style.color="white";
$('#bms_tmax').html(arr[27]);if (arr[27]=='off') document.getElementById("bms_tmax").style.color="gray"; else document.getElementById("bms_tmax").style.color="white";
$('#bms_umin').html(arr[24]);if (arr[24]=='off') document.getElementById("bms_umin").style.color="gray"; else document.getElementById("bms_umin").style.color="white";
$('#bms_umax').html(arr[25]);if (arr[25]=='off') document.getElementById("bms_umax").style.color="gray"; else document.getElementById("bms_umax").style.color="white";

//-----displays
    
    display_4.setVal(arr[14]);
    

//----- text values

        
	$('#text_bmon').html(arr[32]+"%");
	consumed=arr[30];
	var real_p=((Number(arr[34])+Number(arr[30]))/Number(arr[34]))*100;
	if (real_p>100) real_p=100;
	$('#text_bmon_real').html(consumed.concat("/",arr[34],"Ah"));
	var acc_hrs=Math.floor(arr[35]/60);
	var acc_min=arr[35]-acc_hrs*60;
	if (arr[35]<2880 && arr[35]>=0) $('#est_time').html(acc_hrs+"ч"+"&nbsp"+acc_min+"min");
	else 
	if (arr[35]>2880) $('#est_time').html(">48ч");
	else
	if (arr[35]==-1) $('#est_time').html("n/a");

	$('#timer').html((
arr[40]<36000)?Math.floor(arr[40]/60)+'&nbspmin':Math.floor(arr[40]/3600)+'&nbspч');
	$('#user_counter').html(arr[37]+"Ah");
	$('#soc').html((arr[38]==-1)?"n/a":arr[38]+'%');
	$('#est_c').html((arr[39]==0)?"n/a":arr[39]+'Ah');

//	$('#text_c_measured').html(arr[34]+"Ah");
	document.getElementById('battery_100').style.width=arr[32]+"%"
	document.getElementById('battery_real').style.width=real_p+"%";
	document.getElementById('text_bmon').style.color="white";
        if (arr[32]<=100) document.getElementById('battery_100').style.background="green";
        if (arr[32]<=70) 
	{
	    document.getElementById('battery_100').style.background="yellow";
	    document.getElementById('text_bmon').style.color="black";
	}
        if (arr[32]<=50) document.getElementById('battery_100').style.background="orange";
        if (arr[32]<=30) document.getElementById('battery_100').style.background="red";

//        $('#text6').html("temperature,&degС");
        

	$('#text_power').html("");

	$('#text_pnet').html(arr[17]);
	$('#text_pacc').html(arr[18]);
	
	$('#text_vacc').html(arr[3]);
	$('#text_iacc').html(arr[4]);

	meter_vacc.setVal(arr[3]);
	meter_iacc.setVal(arr[4]);
    
    
//---------- powers------------------


	  if (arr[12]!=-1) arr[19]=Math.round(arr[12]*arr[3]); // thre is I2C mppt    
	  $('#text_ppv').html(arr[19]);
          var fd=move(document.getElementById("power_net"),
	  document.getElementById("text_pnet"),arr[17],power[Power_eeprom]*1000);
          fd=move(document.getElementById("power_acc"),
          document.getElementById("text_pacc"),arr[18],power[Power_eeprom]*1000);
          fd=move(document.getElementById("power_pv"),
          document.getElementById("text_ppv"),arr[19],power[Power_eeprom]*1000);
	
	series_acc.append(new Date().getTime(),Number(arr[18]));
      
    
       $('#map_err_in').html(err_warn(String(arr[23])));
       
       
//    });
   };
