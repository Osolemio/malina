var settings=[];
const max_items=45;

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
  xmlhttp.open('GET','load.php',false);
    xmlhttp.send(null);
    if (xmlhttp.status==200) {
    var result=JSON.parse(xmlhttp.responseText);}
   
    else alert('Ошибка получения данных');
    
     var eacc=result[max_items][0];
     $('#input_field').prop('disabled',true);
     $('#chapter').change(function() {
	$('#menu, #submenu').find('option')
	    .remove()
	    .end()
	    .prop('disabled',true);
     chapter_id=$(this).val();
	 for (i=0;i<max_items;i++) {
	    if (result[i][0]==chapter_id) 
	     $('#menu').append('<option value="'+result[i][1]+'">'+menu[i]+'</option>');

	    }
          $('#menu').change();
	  $('#menu').prop('disabled',false);

	});
	
	$('#chapter').change();

//------------------SUBMENU------------------------------
     $('#menu').change(function() {
	$('#submenu').find('option')
	    .remove()
	    .end()
	    .prop('disabled',true);
     var menu_id=$(this).val();
     var chapter_id=$('#chapter').val();
     $('#input_field').prop('disabled',true);
     $('#input_field').prop('type',"text");
     $('#input_field').val("выберите из списка");
     $('#field_units').html("");

	    for (i=0;i<max_items;i++) {
//------------------------list------------------------------------------ 
		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='list' && result[i][8]=='on')
	      {
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("");

             for (i1=result[i][5];i1<=result[i][6];i1++)
		{
	        $('#submenu').append('<option value="'+i1+'">'+submenu[i][i1]+'</option>');
                
		
		}
		var item=document.getElementById("submenu");
		item.options[result[i][7]].selected=true;;
		$('#submenu').prop('disabled',false);
		$('#min').html("");
		$('#max').html("");
		
		}
	     
//------------------------number----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && (result[i][4]=='number' || result[i][4]=='sec') && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		if (result[i][4]=='sec') $('#field_units').html("сек"); else $('#field_units').html("");

		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: "+result[i][5]);
		$('#max').html("Максимальное значение: "+result[i][6]);
		$('#input_field').prop('type',"number");
		$('#input_field').prop('min',result[i][5]);
		$('#input_field').prop('max',result[i][6]);
		$('#input_field').prop('step',1);
		$('#input_field').val(result[i][7]);
		$('#input_field').prop('disabled',false);
		}    

//------------------------temperature----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='temp' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("&degC");

		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: "+(result[i][5]-50));
		$('#max').html("Максимальное значение: "+(result[i][6]-50));
		$('#input_field').prop('type',"number");
		$('#input_field').prop('min',result[i][5]-50);
		$('#input_field').prop('max',result[i][6]-50);
		$('#input_field').prop('step',1);
		$('#input_field').val(result[i][7]-50);
		$('#input_field').prop('disabled',false);
		}    


//------------------------voltage ac----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='ac' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#input_field').prop('disabled',true);
		$('#field_units').html("В");
		$('#min').html("Минимальное значение: ".concat(result[i][5]+100));
		$('#max').html("Максимальное значение: ".concat(result[i][6]+100));
		$('#input_field').prop('type',"number");
		$('#input_field').prop('min',result[i][5]+100);
		$('#input_field').prop('max',result[i][6]+100);
		$('#input_field').prop('step',1);
		
		$('#input_field').val(result[i][7]+100);
		$('#input_field').prop('disabled',false);
		}    

//------------------------dc_dop----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='dc_dop' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#dop').val(result[i][3]);
		$('#eacc').val(eacc);
		$("#field_units").html("В");
		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: ".concat(((result[i][5]<<eacc)+result[i][3])/10));
		$('#max').html("Максимальное значение: ".concat(((result[i][6]<<eacc)+result[i][3])/10));
		$('#input_field').prop('type',"number");
		$('#input_field').prop('min',(((result[i][5]<<eacc)+result[i][3])/10));
		$('#input_field').prop('max',(((result[i][6]<<eacc)+result[i][3])/10));
		$('#input_field').prop('step',0.1);
		$('#input_field').val(((result[i][7]<<eacc)+result[i][3])/10);
		$('#input_field').prop('disabled',false);
		}    


//------------------------dc----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='dc' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#eacc').val(eacc);
		$("#field_units").html("В");
		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: ".concat(((result[i][5]<<eacc))/10));
		$('#max').html("Максимальное значение: ".concat(((result[i][6]<<eacc))/10));
		$('#input_field').prop('type',"number");
		$('#input_field').prop('min',(((result[i][5]<<eacc))/10));
		$('#input_field').prop('max',(((result[i][6]<<eacc))/10));
		$('#input_field').prop('step',1);
		$('#input_field').val(((result[i][7]<<eacc))/10);
		$('#input_field').prop('disabled',false);
		}    


//------------------------power_dop----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='power_dop' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("Вт");
		$('#dop').val(result[i][3]);
		$('#eacc').val(eacc);
		$('#input_field').prop('type',"number");
		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: ".concat((result[i][5]<<result[i][3])*100));
		$('#max').html("Максимальное значение: ".concat((result[i][6]<<result[i][3])*100));
		$('#input_field').prop('min',((result[i][5]<<result[i][3])*100));
		$('#input_field').prop('max',((result[i][6]<<result[i][3])*100));
		$('#input_field').prop('step',100);
		
		$('#input_field').val((result[i][7]<<result[i][3])*100);
		$('#input_field').prop('disabled',false);
		}    

//------------------------current_c----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='current_c' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("С");
		$('#input_field').prop('type',"number");
		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: ".concat(result[i][5]/100));
		$('#max').html("Максимальное значение: ".concat(result[i][6]/100));
		$('#input_field').prop('min',result[i][5]/100);
		$('#input_field').prop('max',result[i][6]/100);
		$('#input_field').prop('step',0.01);
		

		$('#input_field').val(result[i][7]/100);
		$('#input_field').prop('disabled',false);
		}    

//------------------------capacity----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='capacity' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("Ач");
		$('#eacc').val(eacc);
		$('#input_field').prop('type',"number");
		$('#input_field').prop('disabled',true);
		$('#min').html("Минимальное значение: ".concat((result[i][5]*25)>>eacc));
		$('#max').html("Максимальное значение: ".concat((result[i][6]*25)>>eacc));
		$('#input_field').prop('min',(result[i][5]*25)>>eacc);
		$('#input_field').prop('max',(result[i][6]*25)>>eacc);
		$('#input_field').prop('step',1);
		
		$('#input_field').val((result[i][7]*25)>>eacc);
		$('#input_field').prop('disabled',false);
		}    

//------------------------time----------------

		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='time' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("");
		$('#input_field').prop('type',"time");
		$('#input_field').prop('disabled',true);
		$('#input_field').prop('step',"any");
		$('#min').html("Время задается с точностью до 10 мин, например 23:50");
		$('#max').html("");
		var hh=(result[i][7]>>3).toString(); hh=(hh.length==1)?"0"+hh:hh;
		var mm=((result[i][7]&7)*10).toString();mm=(mm.length==1)?"0"+mm:mm;
		$('#input_field').val(hh.concat(":",mm));
		$('#input_field').prop('disabled',false);
		}    
//-------------------------charge_time---------------
		if (result[i][0]==chapter_id && result[i][1]==menu_id && result[i][4]=='charge_time' && result[i][8]=='on')
		{
		$('#mem_offset').val(result[i][2]);
		$('#mem_class').val(result[i][4]);
		$('#min_val').val(result[i][5]);
		$('#max_val').val(result[i][6]);
		$('#field_units').html("");
		$('#input_field').prop('type',"number");
		$('#input_field').prop('disabled',true);
		$('#input_field').prop('step',"1");
		$('#input_field').prop('min',(result[i][5]*16/60).toFixed(0));
		$('#input_field').prop('max',(result[i][6]*16/60).toFixed(0));

		$('#min').html("Время задается в часах");
		$('#max').html("Максимальное значение: ".concat((result[i][6]*16/60).toFixed(0)));
		var hh=(result[i][7]*16/60).toFixed(0).toString();
		
		$('#input_field').val(hh);
		$('#input_field').prop('disabled',false);
		}



////


	}
	    $('#submenu').change();

	});

	$('#submenu').change(function() {
	    
	    $('#mem_value').val($("#submenu").find(":selected").val());

	
	});

     $('#menu').change();
     $('#submenu').change();
         

   
 });

