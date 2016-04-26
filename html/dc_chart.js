
/*
data.forEach(function(d) {
    d.date=parseDate(d.date);
    d.value=d.value;
});
*/
var avg=0;sum=0;
$(function() {
data.forEach(function(d) {
    d.d=new Date(d.d);
    sum+=d.v;++avg;
});
avg=sum/avg;
var chart_main=dc.lineChart("#dc_chart");
var ndx=crossfilter(data);
var dateDim=ndx.dimension(function(d) {return d.d;});
var yDim=ndx.dimension(function(d) {return d.v;});
var gr = dateDim.group().reduceSum(function(d) {return d.v;});
var minDate = dateDim.bottom(1)[0].d;
var maxDate = dateDim.top(1)[0].d;
var maxY=yDim.top(1)[0].v;
var minY=yDim.bottom(1)[0].v;



    chart_main
    .width(graph_width).height(graph_height)
    .dimension(dateDim)
    .transitionDuration(200)
    .group(gr)
    .x(d3.time.scale().domain([minDate, maxDate]))
    .y(d3.scale.linear().domain([minY,maxY]))
    .brushOn(false)
    .elasticX(false)
    .elasticY(false)
    .yAxisLabel(legend)
    .renderHorizontalGridLines(true);


dc.renderAll();


$('input[name=slider]').nativeMultiple({
    stylesheet: "slider",
    onCreate: function() {

    },
    onChange: function(first_value, second_value) {
    --second_value;
    chart_main.x(d3.time.scale().domain([data[first_value].d, data[second_value].d]));
    minY=data[first_value].v;
    maxY=0;
    var i=first_value;
    while (i<=second_value) 
	{
	    avg+=data[i].v;
	    if (maxY<data[i].v) maxY=data[i].v;
	    if (minY>data[i].v) minY=data[i].v;
	    ++i;
	}
    avg=avg/(second_value-first_value+1);
    chart_main.y(d3.scale.linear().domain([minY,maxY])); 
    $('#values').html("<b>MAX:</b> "+maxY+",<b> MIN:</b> "+minY+" <b>AVG:</b> "+avg.toFixed(2));
    chart_main.rescale();
    chart_main.redraw();

    },
    onSlide: function(first_value, second_value) {
    
    $('#date').html("<b>FROM:</b> "+data[first_value].d+' <b>TO:</b> '+data[second_value-1].d);
    }
});

    $('#values').html("<b>MAX:</b> "+maxY+",<b> MIN:</b> "+minY+" <b>AVG:</b> "+avg.toFixed(2));
    $('#date').html("<b>FROM:</b> "+minDate+' <b>TO:</b> '+maxDate);
});



