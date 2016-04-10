var  arr=[]; c=1;


$(function() {
  
    setInterval(function() {
        tick();
       
         }, 1000);
     tick();
  });


 


 function dataRequest () {
    HTR = ('v' == '\v') ? new ActiveXObject ('Microsoft.XMLHTTP') 
			: new XMLHttpRequest ();
    HTR.open ('get', './progress.php');
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
    onload=dataRequest();

    
//----- text values
    $(document).ready(function () {
    var cont=$('#progress').html(); 
    if (arr[0]=='#') 
     {
    if (cont== '*------')$('#progress').html('-*-----');
    if (cont== '-*-----')$('#progress').html('--*----');    
    if (cont== '--*----')$('#progress').html('---*---');
    if (cont== '---*---')$('#progress').html('----*--');
    if (cont== '----*--')$('#progress').html('-----*-');
    if (cont== '-----*-')$('#progress').html('------*');
    if (cont== '------*')$('#progress').html('*------');
    }
    if (arr[0]=='done')  $('#progress').html(loc['finished']);    

       });
   };
