  var dev=['МАП','MPPT','МАП+MPPT'];
  var load='';
    var batmon; 

$(function () {
    
    $.ajax({
	
	method:'post',
	url: 'load.php',
	async: false,
	success: function(response) {
	if (response!=']') load=$.parseJSON(response);
	}
	});

    console.log(load);
    if (load=='') {
    $('#nodes').jstree(
    { 'core' : {
	 'check_callback':true,
	 'multiple':false,
	'data' : [
	{
	 'id' : '1',
	 'parent' : '#',
         'text' : 'Блок АКБ 1',
	 'icon' : '../img/bat.png',
         'state' : {
           'opened' : true,
           'selected' : false
         }
         },
	
	 {
	 'id' : 'Гараж',
	 'parent' : '1',
         'text' : 'Гараж',
	 'icon' : '../img/pc.png',
         'state' : {
           'opened' : true,
           'selected' : false
         }
	 },
	{
	 'id' : 'IP',
	 'parent' : 'Гараж',
         'text' : '127.0.0.1',
	 'icon' : '../img/note.png',
         'state' : {
           'opened' : true,
           'selected' : false
         }
	 },
	 {
	 'id' : 'dev',
	 'parent' : 'Гараж',
         'text' : 'МАП',
	 'icon' : '../img/note.png',
         'state' : {
           'opened' : true,
           'selected' : false
         }
         }
	]
      }
//   'plugins' : ["unique"]
    }
    
 );
 }
 else

    {

    $('#nodes').jstree(
    { 'core' : {
	 'check_callback':true,
	 'multiple':false,
	'data' : load  

	}});
    }

$('#nodes').on("changed.jstree", function (e, data) {
  
    if (data.instance.is_leaf(data.selected[0]) && !(data.instance.get_parent(data.selected[0])=='#')) 
   {
    data.instance.deselect_node(data.selected[0],true);
    return;
   }

});

});







function create_node() {
    var device=dev[$('#devices').find(':selected').val()];
    
    if ($('#nodes').jstree(true).create_node($('#acc_number').val(),{'id':$('#name').val(), 'text': $('#name').val(), 'icon' : '../img/pc.png'})==false)
	{
	$('#nodes').jstree(true).create_node('#',{'id': $('#acc_number').val(), 'text': 'Блок АКБ '+$('#acc_number').val(), 'icon' : '../img/bat.png'});
	$('#nodes').jstree(true).create_node($('#acc_number').val(),{'id':$('#name').val(), 'text': $('#name').val(), 'icon' : '../img/pc.png'});
	}
    $('#nodes').jstree(true).create_node($('#name').val(),{'id':'ip'+$('#name').val(), 'text': $('#ip').val(),  'icon' : '../img/note.png'});
    $('#nodes').jstree(true).create_node($('#name').val(),{'id':'dev'+$('#name').val(), 'text': device,  'icon' : '../img/note.png'});
}


function delete_node() {
 var  instance=$('#nodes').jstree(true);
 var obj=instance.get_selected(true)[0];
 if (!obj) {alert("Ничего не выбрано");return;}
  instance.delete_node(obj);

}

function save() {
var instance=$('#nodes').jstree(true).get_json('#',{'flat':false, 'no_state':true});
; var trunc=true;
 for (var key_acc in instance)
    for (var key_pi in instance[key_acc].children)
 {
		var node=instance[key_acc].id+','+instance[key_acc].children[key_pi].text+','+
		instance[key_acc].children[key_pi].children[0].text+','+instance[key_acc].children[key_pi].children[1].text;
    $.ajax({
	data:{
		'node': node,
		'truncate': trunc
		},
	url: 'save.php',
	async: false,
        method: 'POST',
	success: function(response) {
	trunc=false;
	},
	error: function (response) {
    	    var r = jQuery.parseJSON(response.responseText);
    	    alert("Message: " + r.Message);
    	    alert("StackTrace: " + r.StackTrace);
    	    alert("ExceptionType: " + r.ExceptionType);
	}});
    }
 if (!trunc) alert("Данные сохранены");
}


 function open_all() {
	$('#nodes').jstree(true).open_all('#');
    }

 function close_all() {
	$('#nodes').jstree(true).close_all('#');
    }


 $(document).ready(function() {

	$.ajax({
	url: 'load_batmon.php',
	async: false,
        method: 'post',
	success: function(response) {
	batmon=$.parseJSON(response);
	},
	error: function (response) {
    	    var r = jQuery.parseJSON(response.responseText);
    	    alert("Message: " + r.Message);
    	    alert("StackTrace: " + r.StackTrace);
    	    alert("ExceptionType: " + r.ExceptionType);
	}});
	$('#batmon_active').val(batmon['active']);
	$('#batmon_ip').val(batmon['ip']);

    });

 function save_batmon() {
	var ip=$('#batmon_ip').val();
	var active=$('#batmon_active').find(':selected').val();

	$.ajax({
	data:{
		'ip': ip,
		'active': active
		},
	url: 'save_batmon.php',
	async: false,
        method: 'POST',
	success: function(response) {
	alert("Сохранено");
	},
	error: function (response) {
    	    var r = jQuery.parseJSON(response.responseText);
    	    alert("Message: " + r.Message);
    	    alert("StackTrace: " + r.StackTrace);
    	    alert("ExceptionType: " + r.ExceptionType);
	}});    

    }