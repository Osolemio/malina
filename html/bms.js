var  arr=[]; c=1;
//---- thresholds --------------
var t_max=40; t_min=0; u_min=2.7; u_max=3.7;


$(function() {
  
    setInterval(function() {
        tick();
       
         }, 1000);
     tick();
  });


 


 function dataRequest () {
    HTR = ('v' == '\v') ? new ActiveXObject ('Microsoft.XMLHTTP') 
			: new XMLHttpRequest ();
    HTR.open ('get', './request_bms.php');
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
  
	elem.style.width=(350*value)/limit+10+'px';
	}
  
  function led(value, color, flag) {

	    if (flag=="on") 
	    
   document.getElementById("led".concat(color.charAt(0),value)).style.background=color;
else 
   document.getElementById("led".concat(color.charAt(0),value)).style.background="dark".concat(color);
  
  }
  
  
  
  function tick() {
    onload=dataRequest();

    
//----- text values
    $(document).ready(function () {
    var index=1; c=(c==0)?1:0;
    var Vmp=0,Vbat=0;
    for (i=1;i<=16;i++) {
    if (i<=arr[0])  
    {
    var ld=led(i,"red","off");
    if (arr[index]>=u_max || arr[index]<=u_min || (arr[index+2]!=127 && arr[index+2]>=t_max) || arr[index+2]<=t_min) 
      ld=led(i,"red","on");
    if (i<=(arr[0]/2)) Vmp+=parseFloat(arr[index]); else Vmp-=parseFloat(arr[index]);
    Vbat+=parseFloat(arr[index]);
    document.getElementById("bms".concat(i)).style.background="ivory";
    if (c==0) ld=led(i,"green","on"); else ld=led(i,"green","off");

    if (arr[index+2]==127) var t="off"; else t=arr[index+2];
    if (arr[index]==arr[arr[0]*3+1]) document.getElementById("bms".concat(i)).style.background="orange";
    if (arr[index]==arr[arr[0]*3+2]) document.getElementById("bms".concat(i)).style.background="deepskyblue";
    document.getElementById("scale".concat(i)).style.width=100*arr[index+1]/arr[index]*2.1+'px';
    $('#vmp').html("Напряжение средней точки: ".concat(Vmp.toFixed(2),"В или ",((Math.abs(Vmp)/Vbat)*100).toFixed(2),"%"));
    $('#display'.concat(i)).html("&nbspU=".concat(arr[index],"В&nbspI=",arr[index+1],"A&nbspt=",t,(t=="off")?"":"&degC"));
    index+=3;
    }
    else 
    {
    document.getElementById("bms".concat(i)).style.background="gray";
    $('#display'.concat(i)).html("НЕ ПОДКЛЮЧЕН");
    var ld=led(i,"red","off");ld=led(i,"red","off");
    }
    }	
	
    
    });
   };
