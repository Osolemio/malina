var chart, v = [], i = 60, c = 5, p_net, p_acc, p_sun, arr = [], max_y=2000;

$(function() {
  chart = new JSGadget.Chart($("#chart"), { //create chart
  trends: [ //create trends
    new JSGadget.ATrend({data: v, xFld: 0, yFld: 1, color: "blue"}),
    new JSGadget.ATrend({data: v, xFld: 0, yFld: 2, color: "red"}),
    new JSGadget.ATrend({data: v, xFld: 0, yFld: 3, color: "green"})
          ] 
        });
    chart.lAxis.setMinMax(0, 10000); //set y limits
    setInterval(function() {
        tick();
       
         }, 1000);
        tick();
        });
                                    
 function dataRequest () {
    HTR = ('v' == '\v') ? new ActiveXObject ('Microsoft.XMLHTTP') 
			: new XMLHttpRequest ();
    HTR.open ('get', './request.php');
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
                                                               
    
    function tick() {
      var t2 = new Date(), tz = tz = t2.getTimezoneOffset() * 60;
      t2 = t2.getTime() / 1000 - tz;
       var t1 = t2 - i, t = (t2 / c) % (Math.PI * 2);
       onload=dataRequest();
       p_net=arr[0]; p_acc=arr[1]; p_sun=arr[2];
       v.push([t2, p_net, p_acc, p_sun]);
         max_y_new=Math.max(p_net, p_acc, p_sun)*1.1;
         if(max_y<max_y_new) max_y=max_y_new;
       this.chart.lAxis.setMinMax(0, max_y);
          if (v.length > 500)
          delete v.shift();
        if (!chart.lAxis.zoom && !chart.bAxis.zoom) {
           chart.bAxis.setMinMax(t1, t2);
           chart.draw();
   }
 };
                                                              