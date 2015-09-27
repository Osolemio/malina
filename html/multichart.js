var pnet_flag=true;
var pbal_flag=true;
var Voltage_eeprom=0;
var range=["10:15:1","20:30:1","40:60:2","80:120:2"];
var range_low=[[11,12],[22,24],[44,48],[88,96]];
var range_norm=[[12,14.5],[24,29],[48,58],[96,116]];
var range_hi=[[14.5,15],[29,30],[58,60],[116,120]];
var range_danger=[[10,11],[20,22],[40,44],[80,88]];


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
 })



    
    var acc=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "legend": {},
    "title": {
    		"text":'Напряжение батареи, В'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_acc.php",
    "interval":1,
    "reset-timeout":240,
    "adjust-scale": false,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scaleX": {},
    "scaleY": {},
    "tooltip":{},
    "series": [
      { 
        "decimals":1,
        "text":"Uакб, %pavgВ",
        "values":[],
        "line-color":"black"
      }
         
      ]
      }
   ]
  
 }
 
 var unet=
    {
    "graphset":[
    {
    "type":'line',
    "crosshair-x":{
    },
    "legend": {},
    "title": {
    		"text":'Напряжение, В',
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_unet.php",
    "interval":1,
    "reset-timeout":240,
    "adjust-scale": false,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scaleX": {
    },
    "scaleY": {
	    "values":"150:280:10",
	    "markers" : [
		    {
			"type":"area",
			"range": [210,240],
			"backgroundColor":"green",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": [190,210],
			"backgroundColor":"orange",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": [240,280],
			"backgroundColor":"red",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": [110,190],
			"backgroundColor":"red",
			"alpha":0.2
		    }
	    
	    ]
      },
    "tooltip":{},
    "series": [
      {
        "decimals":0,
        "text":"Uвх, %pavgВ",
        "values":[],
        "line-color":"blue"
        
      },
      {
        "decimals":0,
        "text":"Uвых, %pavgВ",
        "values":[],
        "line-color":"lime"
      }
         
      ]
              
    }
    ]
 };    

var pnet=
    {
    "graphset": [
    {
    "type":'area',
    "crosshair-x":{
    },
    "crosshair-y":{},
    
    "legend": {},
    "title": {
    		"text":'Мощность, ВА'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_pnet.php",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    },
    "scale-x-1":{
    },
    "tooltip":{},
    "series": [
      {
        "decimals":0,
        "text":"Pвх, %pavgВА",
        "values":[],
        "line-color":"red",
        "background-color":"red orange"
        
      },
      {
        "decimals":0,
        "text":"Pакб-мап, %pavgВт",
        "values":[],
        "line-color":"grey",
        "background-color":"grey ivory"
        
      },
      {
        "decimals":0,
        "text":"Pmppt_out, %pavgВт",
        "values":[],
        "line-color":"green",
        "background-color":"green lime"
        
      }
         
      ]
     }          
    ]
 };    


var inet=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "crosshair-y":{},
    
    "legend": {},
    "title": {
    		"text":'Ток, А'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_inet.php",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },
    "tooltip":{},
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    "max-items":40
    },
    "scale-x-1": {
    "max-items":40
    },
    "series": [
      {
        "decimals":1,
        "text":"Iвх, %pavgА",
        "values":[],
        "line-color":"red"
      },
      {
        "decimals":1,
        "text":"Iакб-мап, %pavgА",
        "values":[],
        "line-color":"grey"
      },
      {
        "decimals":1,
        "text":"Imppt-acc, %pavgА",
        "values":[],
        "line-color":"green"
      }
         
      ]
     }          
    ]
 };    

var ipv=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "crosshair-y":{},
    
    "legend": {},
    "title": {
    		"text":'Ток MPPT (данные MPPT), А'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_ipv.php",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },
    "tooltip":{},
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    "max-items":40
    },
    "scale-x-1": {
    "max-items":40
    },
    "series": [
      {
        "decimals":1,
        "text":"Imppt-acc, %pavgА",
        "values":[],
        "line-color":"green"
      },
      {
        "decimals":1,
        "text":"ДТ1, %pavgА",
        "values":[],
        "line-color":"lime"
      },
      {
        "decimals":1,
        "text":"ДТ2, %pavgА",
        "values":[],
        "line-color":"orange"
      }
      
         
      ]
     }          
    ]
 };    




var p_balance=
    {
    "graphset": [
    {
    "type":'area',
    
    "legend": {},
    "title": {
    		"text":'Баланс АКБ по мощности, Вт. Источник - МАП (I2С)'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_pbalance.php?sw=map",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
	"rules":[
	    {
	      "rule":"%v<0",
	      "background-color":"red"
	    },
	    {
	      "rule":"%v>0",
	      "background-color":"green"
	    }
	    ],
	    
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    },
    "tooltip":{},
    "series": [
      {
        "decimals":0,
        "text":"Pбал, %pavgВт",
        "values":[],
        "line-color":"gray",
        "background-color":"red orange"
        
      }
      ]
     }          
    ]
 };    


var i_balance=
    {
    "graphset": [
    {
    "type":'area',
    "legend": {},
    "title": {
    		"text":'Баланс АКБ по току, А. Источник - МАП (I2C)'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_ibalance.php?sw=map",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },
    "tooltip":{},
    "plot":{
    "rules":[
	    {
	      "rule":"%v<0",
	      "background-color":"red"
	    },
	    {
	      "rule":"%v>0",
	      "background-color":"green"
	    }
	    ],
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    "max-items":40
    },
    "series": [
      {
        "decimals":1,
        "text":"Iсумм, %pavgА",
        "values":[],
        "line-color":"gray"
      }
      ]
     }          
    ]
 };    


var wind=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "crosshair-y":{},
    
    "legend": {},
    "title": {
    		"text":'Обороты ветрогенератора, об/мин'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_wind.php",
    "interval":1,
    "reset-timeout":240,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },
    "tooltip":{},
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scale-x": {
    "max-items":40
    },
    "series": [
      {
        "decimals":0,
        "text":"n, %pavg об/мин",
        "values":[],
        "line-color":"green"
      }
      
         
      ]
     }          
    ]
 };    

    


    window.onload=function(){

 if (document.getElementById('chart_inet')) {
    
   acc=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "legend": {},
    "title": {
    		"text":'Напряжение батареи, В'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_acc.php",
    "interval":1,
    "reset-timeout":240,
    "adjust-scale": false,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scaleX": {},
    "scaleY": {
              "values":range[Voltage_eeprom],
		"markers" : [
		    {
			"type":"area",
			"range": range_norm[Voltage_eeprom],
			"backgroundColor":"green",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": range_low[Voltage_eeprom],
			"backgroundColor":"orange",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": range_danger[Voltage_eeprom],
			"backgroundColor":"red",
			"alpha":0.2
		    },
		    {
			"type":"area",
			"range": range_hi[Voltage_eeprom],
			"backgroundColor":"yellow",
			"alpha":0.2
		    }
	    ]
      },
    "tooltip":{},
    "series": [
      { 
        "decimals":1,
        "text":"Uакб, %pavgВ",
        "values":[],
        "line-color":"black"
      }
         
      ]
      }
   ]
  
 }    

} else

{

  acc=
    {
    "graphset": [
    {
    "type":'line',
    "crosshair-x":{
    },
    "legend": {},
    "title": {
    		"text":'Напряжение батареи, В'
        	},
    "refresh":{
    "type":"feed",
    "transport":"http",
     "url":"request_acc.php",
    "interval":1,
    "reset-timeout":240,
    "adjust-scale": false,
    "max-ticks":60,
    "curtain":{
    "text": "Загрузка данных...",
    "color": "red",
    "text-size": 30,
    "bold": true
    }
    },    	
    "plot":{
    "aspect":"spline",
    "valueBox":{
        "type":'min,max,last'
	        }
            },
    "scaleX": {},
    "scaleY": {},
    "tooltip":{},
    "series": [
      { 
        "decimals":1,
        "text":"Uакб, %pavgВ",
        "values":[],
        "line-color":"black"
      }
         
      ]
      }
   ]


}
}
    
    zingchart.render({
    id:'chart_acc',
    data: acc,
    width:"99%",
    "height":"250",
    "auto-resize":true,
    output: "auto"    
    });
    
    zingchart.render({
    id:'chart_unet',
    data: unet,
    width:"99%",
    height:"300",
    "auto-resize":true,
    output: "auto"    
    });
    /*
    яingchart.render({
    id:'chart_uout',
    data: uout,
    height:300,
    output: "auto"    
    });
    */
    zingchart.render({
    id:'chart_inet',
    data: inet,
    width:"99%",
    height:"400",
    "auto-resize":true,
    output: "auto"    
    });

    zingchart.render({
    id:'chart_ipv',
    data: ipv,
    width:"99%",
    height:"400",
    "auto-resize":true,
    output: "auto"    
    });
    
    zingchart.render({
    id:'chart_wind',
    data: wind,
    width:"99%",
    height:"300",
    "auto-resize":true,
    output: "auto"    
    });

    zingchart.render({
    id:'chart_balance',
    data: i_balance,
    width:"99%",
    height:"200",
    "auto-resize":true,
    output: "auto"    
    });
    
    };
    
    
  function pnet_switch() {
  if (pnet_flag) {
  pnet_flag=false;
  zingchart.exec('chart_inet','destroy');
  zingchart.render({
    id:'chart_inet',
    data: pnet,
    width:"99%",
    height:"400",
    "auto-resize":true,
    output: "auto"    
    });
  }
   else
   {
   pnet_flag=true;zingchart.exec('chart_inet','destroy');
   zingchart.render({
    id:'chart_inet',
    data: inet,
    width:"99%",
    height:"400",
    "auto-resize":true,
    output: "auto"    
    });

  }


 };


function pbal_switch() {
  if (pbal_flag) {
  pbal_flag=false;
  zingchart.exec('chart_balance','destroy');
  zingchart.render({
    id:'chart_balance',
    data: p_balance,
    width:"99%",
    height:"200",
    "auto-resize":true,
    output: "auto"    
    });
  }
   else
   {
   pbal_flag=true;zingchart.exec('chart_balance','destroy');
   zingchart.render({
    id:'chart_balance',
    data: i_balance,
    width:"99%",
    height:"200",
    "auto-resize":true,
    output: "auto"    
    });

  }


 };


 function sw_map() {
 if (pbal_flag==true)
 {
   zingchart.exec('chart_balance', 'modify', {
  data: {
    "refresh":{
    "url":"request_ibalance.php?sw=map"
    },
    title: {
    text:"Баланс токов АКБ, А. Источник - MАП (I2C)"
   }
  }
  });
  }
  else {
  
  zingchart.exec('chart_balance', 'modify', {
  data: {
    "refresh":{
    "url":"request_pbalance.php?sw=map"
    },
    title: {
    text:"Баланс мощности АКБ, Вт. Источник - MAP (I2C)"
   }
  }
  });
 } 
 };
 
 function sw_mppt() {
 
 if (pbal_flag==true)
 {
   zingchart.exec('chart_balance', 'modify', {
  data: {
    "refresh":{
    "url":"request_ibalance.php?sw=mppt"
    },
    title: {
    text:"Баланс токов АКБ, А. Источник - MPPT (кольца)"
   }
  }
  });
  }
  else {
  
  zingchart.exec('chart_balance', 'modify', {
  data: {
    "refresh":{
    "url":"request_pbalance.php?sw=mppt"
    },
    title: {
    text:"Баланс мощности АКБ, Вт. Источник - MPPT (кольца)"
    }
   
  }
  });
 }
 };