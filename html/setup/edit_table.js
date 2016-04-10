	function make_graph() {

		var arr = [];

		for (var i = 0; i <= 100; i++) {
		 var val=document.getElementById('fval'+i).value; 
	    	if (val>0) arr.push([i,val]);
		
		}

		var plot = $.plot("#chart", [
			{ data: arr, label: loc['title8']},
		], {
			series: {
				lines: {
					show: true
				},
				points: {
					show: true
				}
			},
			grid: {
				hoverable: true,
				clickable: true
			},
			legend: {
				show: false,
				position: "se"
			}
		});

		$("<div id='tooltip'></div>").css({
		    position: "absolute",
		    display: "none",
		    border: "1px solid #fdd",
		    padding: "2px",
		    "background-color": "#fee",
		    opacity: 0.80
		}).appendTo("body");



		$("#chart").bind("plothover", function (event, pos, item) {

				var str = "(" + pos.x.toFixed(2) + "%, " + pos.y.toFixed(2) + loc['V']+")";
				$("#hoverdata").text(str);

				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$("#tooltip").html(item.series.label + " of " + x + " = " + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} 
  else {
					$("#tooltip").hide();
				}
		});

	}
