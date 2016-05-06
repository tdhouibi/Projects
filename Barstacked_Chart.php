<?php
/* 
Include JpGraph in your script. Note that jpgraph.php should reside in a directory that's present in your PHP INCLUDE_PATH, otherwise specify the full path yourself. 
*/   
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_stock.php');
 require_once ('jpgraph/jpgraph_line.php');
 require_once('jpgraph/jpgraph_plotline.php');  
 require_once('jpgraph/jpgraph_scatter.php');
require_once ('jpgraph/jpgraph_bar.php');

$verbindung = mysql_connect("192.168.210.135", "bi", "mysql") or die ("Fehler im System");
$connection=mysql_select_db("kunden_daten",$verbindung);

function getsmg_unterschritten() {
    Global $verbindung;
$abfrage="SELECT rd,anzahl_smg_gr as gr

FROM zwischen_tabelle ra 

WHERE jahr=2013
GROUP BY rd;";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['gr'][]=(int)$row['gr'];
//$list['min'][]=$row['min_devk'];

}
return $list;
}
$avg_smg=getsmg_unterschritten();

$array_vorschlag_unterschritten=$avg_smg['gr'];


function getsmg_ueberschritten() {
    Global $verbindung;
$abfrage="SELECT rd,anzahl_smg_ls as ls

 FROM zwischen_tabelle ra 

WHERE jahr=2013
GROUP BY rd;";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['ls'][]=-1* abs((int)$row['ls']);
//$list['min'][]=$row['min_devk'];

}
return $list;
}
$avg_smg=getsmg_ueberschritten();

$array_vorschlag_ueberschritten=$avg_smg['ls'];
############################################################################
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
$datay1=$array_vorschlag_unterschritten;
$datay2=$array_vorschlag_ueberschritten;




// Create the graph. These two calls are always required
$graph = new Graph(1200,700);
$graph->SetScale('textlin');
 $graph->xaxis->SetTickLabels($rd_array);
 $graph->xaxis->title->Set("Standorte");
 $graph->xaxis->SetPos('min');
// Add a drop shadow
$graph->SetShadow();

#$graph->yaxis->scale->SetGrace(35);
// Adjust the margin a bit to make more room for titles
$graph->SetMargin(40,30,20,40);
 

// Create a bar pot
$bplot1 = new BarPlot($datay1);
$bplot1->SetShadow();
$bplot1->value->SetFormat('%d');
 $bplot1->value->Show();
// Adjust fill color
 $bplot1->SetValuePos('center');
# $bplot1->value->SetMargin(200);

$bplot1->SetFillColor('orange');
$bplot1->SetCenter();

$bplot2 = new BarPlot($datay2);
 
// Adjust fill color
$bplot2->SetFillColor('red');
$bplot2->value->SetFormat('%d');
$bplot2->value->Show();
$bplot2->SetValuePos('center');
#$bplot2->value->SetMargin(200);
//$graph->Add($bplot1);
//$graph->Add($bplot2);

# $gbplot = new GroupBarPlot(array($bplot1,$bplot2)); 
 $gbplot = new AccBarPlot(array($bplot1,$bplot2));
 
 $graph->Add($gbplot);
// Setup the titles
$graph->title->Set(utf8_decode('Absolute Anzahl der Berichte die außerhalb des AC SMG-Vorschlag reguliert wurden'));
#$graph->xaxis->title->Set('X-title');
$graph->yaxis->title->Set('Anz Berichte');
 
$graph->title->SetFont(FF_FONT1,FS_BOLD);
$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
 

/*
// Display the graph
@unlink("Barstacked.png");
$graph->Stroke('Barstacked.png');

*/

$graph->Stroke();