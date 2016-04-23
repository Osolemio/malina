
/*
data.forEach(function(d) {
    d.date=parseDate(d.date);
    d.value=d.value;
});
*/
$(function() {
var chart=dc.lineChart("#dc_chart");
var ndx=crossfilter(data);
var all=ndx.groupAll();
var dateDim=ndx.dimension(function(d) {return new Date(d.date);});
var yDim=ndx.dimension(function(d) {return d.value;});
var gr = dateDim.group().reduceSum(function(d) {return d.value;});
var minDate = dateDim.bottom(1)[0].date;
var maxDate = dateDim.top(1)[0].date;


    chart
    .width(graph_width).height(graph_height)
    .dimension(dateDim)
    .group(gr)
    .x(d3.time.scale().domain([minDate, maxDate]))
    .y(d3.scale.linear().domain([yDim.bottom(1)[0].value,yDim.top(1)[0].value]))
    .brushOn(false)
    .elasticX(true)
    .elasticY(false);

dc.renderAll();
});