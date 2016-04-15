<?php
session_start();

require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_line.php');

$datay1 = $_SESSION['datay1'];
$datax1 = $_SESSION['datax1'];

// Setup the graph
$graph = new Graph($_SESSION['width'],$_SESSION['height'],'auto');
$graph->SetScale("textlin");

//$theme_class=new UniversalTheme;

//$graph->SetTheme($theme_class);
//$graph->img->SetAntiAliasing(false);
$graph->title->Set('User\'s diagram');
$graph->SetBox(true);

//$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);


$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetLabelAngle(90);
$interval=count($datax1)/$_SESSION['width']*20; if ($interval<1) $interval=1;
$graph->xaxis->SetTextLabelInterval($interval);
$graph->xaxis->SetTickLabels($datax1);
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($datay1);
$graph->Add($p1);
$p1->SetColor("#FF1493");
$p1->SetLegend($_SESSION['Legend']);

/*
// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Line 2');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#FF1493");
$p3->SetLegend('Line 3');
*/

$graph->legend->SetFrameWeight(1);

// Output line
$graph->Stroke();


?>
