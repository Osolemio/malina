
/*
data.forEach(function(d) {
    d.date=parseDate(d.date);
    d.value=d.value;
});
*/
$(function() {
var chart_main=dc.lineChart("#dc_chart");
var chart_range=dc.lineChart("#dc_slider_chart");
var ndx=crossfilter(data);
var all=ndx.groupAll();
var dateDim=ndx.dimension(function(d) {return new Date(d.date);});
var yDim=ndx.dimension(function(d) {return d.value;});
var gr = dateDim.group().reduceSum(function(d) {return d.value;});
var minDate = dateDim.bottom(1)[0].date;
var maxDate = dateDim.top(1)[0].date;

dc.chart_main = function (chart, group) {
    dc.chart_main.register(chart, group);
};
dc.registerChart(chart_main,"groupA");

    chart_main
    .width(graph_width).height(graph_height)
    .dimension(dateDim)
    .transitionDuration(500)
    .mouseZoomable(true)
    .rangeChart(chart_range)
    .group(gr)
    .x(d3.time.scale().domain([minDate, maxDate]))
    .y(d3.scale.linear().domain([yDim.bottom(1)[0].value,yDim.top(1)[0].value]))
    .brushOn(false)
    .elasticX(true)
    .elasticY(false)
    .yAxisLabel(legend)
    .renderHorizontalGridLines(true)
    .zoomOutRestrict(true);

    chart_range
    .renderArea(true)
    .width(graph_width).height(80)
    .dimension(dateDim)
    .group(gr)
    .brushOn(true)
    .x(d3.time.scale().domain([minDate, maxDate]))
    .y(d3.scale.linear().domain([yDim.bottom(1)[0].value,yDim.top(1)[0].value]))
    .elasticY(false)
    .elasticX(true)
    .yAxis().tickValues([0,yDim.top(1)[0].value]);
dc.renderAll();

});