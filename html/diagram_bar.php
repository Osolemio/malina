<?php
session_start();

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

$datay1 = $_SESSION['datay1'];
$datax1 = $_SESSION['datax1'];

// Setup the graph
$graph = new Graph($_SESSION['width'],$_SESSION['height'],'auto');
$graph->SetScale("textlin");

//$graph->img->SetAntiAliasing(false);
$graph->title->Set('Sun energy diagram'.$_SESSION['Legend']);
$graph->SetBox(true);

//$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);


$graph->xaxis->SetLabelAngle(45);
//$width=$_SESSION['width']/(count($datax1)+10);

//$graph->SetWidth($width);
$graph->xaxis->SetTickLabels($datax1);
//$graph->xgrid->SetColor('#E3E3E3');

$p1 = new BarPlot($datay1);
$graph->Add($p1);
$p1->SetColor("green");
$p1->value->Show();
//$p1->SetFillColor("lime");
$p1->SetFillGradient("darkgreen","lightskyblue",GRAD_LEFT_REFLECTION);
//$p1->SetLegend($_SESSION['Legend']);

$graph->Stroke();


?>
