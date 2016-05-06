<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_radar.php');

$verbindung = mysql_connect("192.168.210.135", "bi", "mysql") or die ("Fehler im System");
$connection=mysql_select_db("kunden_daten",$verbindung);

function get_AC_VR_durchschnitt_2013() {
    Global $verbindung;
//$abfrage="SELECT rd,count(*) as anz_Berichte,AVG(smg) as avg_smg_actineo ,AVG(smg_devk) as avg_smg_devk
//
// FROM report_devk_fake ra 
// WHERE jahr=2014
//
//GROUP BY rd;";
 
$abfrage="SELECT rd,smg_durchschnitt as ac_vorschlag , smg_k_durchschnitt as vr_reguliert

 FROM zwischen_tabelle ra 
 WHERE jahr=2013

GROUP BY rd;";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
//$list['rd'][]=$row['rd'];
$list['ac_vorschlag'][]=(int)$row['ac_vorschlag'];
$list['vr_reguliert'][]=(int)$row['vr_reguliert'];

}
return $list;
}
$avg_smg=get_AC_VR_durchschnitt_2013();
$array_ac_vorschlag_2013=$avg_smg['ac_vorschlag'];
$array_vr_reguliert_2013=$avg_smg['vr_reguliert'];
 


$data1=$array_ac_vorschlag_2013;
$data2=$array_vr_reguliert_2013;
 

###########################################################################
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




$titles=$rd_array;




$graph = new RadarGraph (1000,700);
 
$graph->title->Set('ACTINEO-Regulierung');
$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
 
$graph->SetTitles($titles);
$graph->SetCenter(0.5,0.53);
$graph->HideTickMarks();
$graph->SetColor('lightgreen@0.7');
$graph->axis->SetColor('darkgray');
$graph->grid->SetColor('darkgray');
$graph->grid->Show();
 $graph->SetShadow();
#$graph->SetMargin(30, 30, 50, 50);

$graph->axis->title->SetFont(FF_ARIAL,FS_NORMAL,12);
$graph->axis->title->SetMargin(10);
$graph->SetGridDepth(DEPTH_BACK);
$graph->SetSize(0.8);
 
$plot1 = new RadarPlot($data1);
$plot1->SetColor('red@0.2');
$plot1->SetLineWeight(1);
#$plot->SetFillColor('red@0.7');
 $plot1->SetLegend("AC Vorschlag 2013");
$plot1->mark->SetType(MARK_IMG_SBALL,'red');
 
$graph->Add($plot1);



 
$plot2 = new RadarPlot($data2);
$plot2->SetColor('green@0.2');
$plot2->SetLineWeight(1);
#$plot->SetFillColor('red@0.7');
$plot2->SetLegend("VR Reguliert 2013");
$plot2->mark->SetType(MARK_IMG_SBALL,'green');
 
$graph->Add($plot2);

/*
@unlink("Radar.png");
$graph->Stroke('Radar.png');
*/
$graph->Stroke();
?>
