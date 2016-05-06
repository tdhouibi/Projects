<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_stock.php');
 require_once ('jpgraph/jpgraph_line.php');
 require_once('jpgraph/jpgraph_plotline.php');  
 require_once('jpgraph/jpgraph_scatter.php');
require_once ('jpgraph/jpgraph_bar.php');

$verbindung = mysql_connect("192.168.210.135", "bi", "mysql") or die ("Fehler im System");
$connection=mysql_select_db("kunden_daten",$verbindung);

################################################### Diagramm Nr 2############################################################
function get_smk_k_durchschnitt_2013() {
    Global $verbindung;
$abfrage="SELECT rd,smg_k_durchschnitt as smg_k_duchschnitt

 FROM zwischen_tabelle ra 
 WHERE jahr=2013

GROUP BY rd;";
 


$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['smg_k_durchschnitt'][]=(int)$row['smg_k_duchschnitt'];


}
return $list;
}
$smg_k_durchschnitt=get_smk_k_durchschnitt_2013();
$array_smg_k_durchschnitt_2013=$smg_k_durchschnitt['smg_k_durchschnitt'];

#####################################Boxplot Durchschnit Point und Diagramm Nr 2 #############################################
function get_smk_k_durchschnitt_2014() {
    Global $verbindung;
$abfrage="SELECT rd,smg_k_durchschnitt as smg_k_duchschnitt

 FROM zwischen_tabelle ra 
 WHERE jahr=2014

GROUP BY rd;";
 


$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['smg_k_durchschnitt'][]=(int)$row['smg_k_duchschnitt'];


}
return $list;
}
$smg_k_durchschnitt=get_smk_k_durchschnitt_2014();
$array_smg_k_durchschnitt_2014=$smg_k_durchschnitt['smg_k_durchschnitt'];

#################################################################################
function get_rd(){
    
    Global $verbindung;
    $abfrage='select distinct(rd) as rd
from zwischen_tabelle
order by rd asc;';
    
$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['rd'][]=(int)$row['rd'];


}
return $list;  
    
}
$rd_list=get_rd();
$rd_array=$rd_list['rd'];

###########################################################################

$data1y=$array_smg_k_durchschnitt_2013;
$data2y=$array_smg_k_durchschnitt_2014;

// Create the graph. These two calls are always required
$graph = new Graph(1200,600,"auto");    
$graph->SetScale("textlin");
 $graph->xaxis->SetTickLabels($rd_array);
$graph->SetShadow();
$graph->img->SetMargin(40,30,20,40);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b1plot->SetFillColor("orange");
$b1plot->SetLegend('2013 Reguliert');
$b2plot = new BarPlot($data2y);
$b2plot->SetLegend('2014 Reguliert');
$b2plot->SetFillColor("blue");

// Create the grouped bar plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));

// ...and add it to the graPH
$graph->Add($gbplot);

$graph->title->Set("Jahres Vergleich Regulierung je Standort");
$graph->xaxis->title->Set("Standorte");
$graph->yaxis->title->Set("Anz Berichte");

$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

/*
// Display the graph
@unlink("BarChart.png");
$graph->Stroke('BarChart.png');
*/

$graph->Stroke();