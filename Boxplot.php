<?php

// Example of a stock chart
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_stock.php');
 require_once ('jpgraph/jpgraph_line.php');
 require_once('jpgraph/jpgraph_plotline.php');
 require_once('jpgraph/jpgraph_plotmark.inc.php');
 require_once('jpgraph/jpgraph_plotline.php');
 require_once('jpgraph/jpgraph_scatter.php');
 
 
  

 
 
 
 
$verbindung = mysql_connect("192.168.210.135", "bi", "mysql") or die ("Fehler im System");
$connection=mysql_select_db("kunden_daten",$verbindung);

function get_Boxplot_values() {
    Global $verbindung;
$abfrage="SELECT smg_k_quartil1 as open,smg_k_quartil3 as close ,smg_k_min as min, smg_k_max as max, smg_k_median as median

FROM zwischen_tabelle  
WHERE jahr=2013


";

$q=mysql_query($abfrage,$verbindung);
$temp = array(); 

while ($row = mysql_fetch_array($q)){
    
     
   $temp[] = $row[0];
   $temp[] = $row[1];
   $temp[] = $row[2];
   $temp[] = $row[3];
   $temp[] = $row[4];
   

}

return $temp;
}
$array=get_Boxplot_values();

function data_row($sqlabfrage){
    Global $verbindung;
return mysql_fetch_array(mysql_query($sqlabfrage,$verbindung));
}

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
$array_smg_k_durchschnitt_2014=$smg_k_durchschnitt['smg_k_durchschnitt'];

// Include the module for creating line graph plots.  
$datay1 =$array_smg_k_durchschnitt_2014;  
###########################################################################
function get_smg_kunde() {
    Global $verbindung;
$abfrage="select AVG(smg_k_durchschnitt)  as smg_k_durchschnitt
From zwischen_tabelle
where jahr=2013

";


return mysql_fetch_array(mysql_query($abfrage,$verbindung));
}
$avg_smg_kunde=get_smg_kunde();
###########################################################################
function get_rd(){
    /**
     *  @return rd array
     */
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

//var_dump($rd_array);
//exit;
###########################################################################


// Data must be in the format : open(Q1),close(Q3),min,max,median
/*
$datay = array(
    34,42,27,45,36,
    55,25,14,59,40,
    15,40,12,47,23,
    62,38,25,65,57,
    38,49,32,64,45);
 */
$datay=$array;
// Setup a simple graph
$graph = new Graph(1210,400);

$graph->SetScale('textlin');
$graph->SetMarginColor('lightblue');

$graph->title->Set('Regulierungsverhalten (Euro)');
$graph->xaxis->SetTickLabels($rd_array);
$graph->xaxis->title->Set("Standorte");
$graph->xaxis->SetTickSide(SIDE_LEFT);
$graph->SetShadow();
#$graph->xaxis->SetTickPositions(array(0,10,20,30,40,50,60,70,80,90,100), array(5,15,25,35,45,55,65,75,85,95));
$graph->SetMargin(40, 70, 50, 30);
# $graph->xaxis->scale->SetAutoMin(3);
// Create a new stock plot
$p1 = new BoxPlot($datay);


$graph->Add($p1);

// Width of the bars (in pixels)
$p1->SetWidth(55);

#$p1->SetCenter($aCenter=true);
$p1->SetCenter();

 #$p1->SetLegend('Jahr 2014'); 
 $p1->SetWeight(1);
 

// the Color
# $p1->SetMedianColor($aColor);
$p1->SetColor($aFrame='black', $aFill=	'peachpuff2',$aFrameNeg='darkred', $aFillNeg='darkred');
$p1->SetMedianColor('red','white');
// Uncomment the following line to hide the horizontal end lines
$p1->HideEndLines($aHide=false);
############################################
$line=new PlotLine(HORIZONTAL,(int)$avg_smg_kunde[0],"chartreuse4",1);
#$line->SetLegend('500');
#$line->value->SetFormat('%d');
#$line->value->Show();
#$graph->legend->SetPos(0.97,0.7,'center','bottom');
$graph->AddLine($line);
#$graph->AddLine(new ScatterPlot(array(100,200,300,400,500,600,700,800,9,10,11,12),array(1,2,3,4,5,6,7,8,9,10,11,12)));
#$caption=new Text('900',50,200);
#$caption=new Text('500',1070,420);
#$caption->SetColor('chartreuse4');
#$caption->SetFont(FONT1_BOLD);
#$graph->AddText($caption);
#$graph->AddText($caption);
#$graph->AddLine(new PlotLine(VERTICAL,5,"",0));
#$graph->AddBand(new PlotBand(HORIZONTAL,BAND_RDIAG,0, "max", "red", 2));


#$p4->SetColor('blue');
#$graph->AddLine(new LinePlot($datay));
#$graph->ynaxis[2]->SetColor('blue'); 

############################################
//
//$sp1 = new LinePlot($datay1);
//$sp1->SetColor("0");
//$sp1->SetCenter();
//// Specify marks for the line plots
//$sp1->mark->SetType(MARK_FILLEDCIRCLE);
//#$sp1->mark->SetFillColor("red");
//$sp1->mark->SetWidth(3);
//$sp1->value->Show();


$sp1 = new ScatterPlot($datay1);
$sp1->mark->SetType(MARK_FILLEDCIRCLE);
$sp1->SetCenter();
#$sp1->mark->SetLinkPoints($aFlag=true,$aColor="red",$aWeight=1,$aStyle='solid');
$sp1->mark->SetFillColor("blue");
$sp1->mark->SetWidth(3);
$sp1->value->SetFormat('%d');
$sp1->value->Show();
#$sp1->mark->SetWeight(10);
#$sp1->mark-> SetLabelPos (SIDE_UP);

$graph->AddLine($sp1);






for($i=0;$i<count($datay1);$i++):
$datay3[$i]='';
endfor;
$datay3[count($datay1)]= (int)$avg_smg_kunde[0];

//var_dump($datay3);
//exit;
$sp2 = new ScatterPlot($datay3);

$sp2->value->Show();
$sp2->mark->SetWidth(0);
$sp2->value->SetColor('chartreuse4');
$sp2->value->SetMargin(0);
$sp2->value->SetAlign('right','right');
$sp2->value->SetFormat('%d');
$graph->AddLine($sp2);

/*
@unlink("Boxplot.png");
$graph->Stroke('Boxplot.png');

*/
$graph->Stroke();