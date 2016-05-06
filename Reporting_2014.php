<?php

#require_once('fpdf/efpdf.php');
include('fpdf/phpToPDF.php');
$verbindung = mysql_connect("localhost", "root", "") or die ("Fehler im System");
$connection=mysql_select_db("test",$verbindung);

######################################## For the table Nr.2##########################################
function getAvg_smg1() {
    Global $verbindung;
$abfrage="SELECT rd,anzahl as anz_Berichte,smg_durchschnitt as avg_smg_actineo ,smg_k_durchschnitt as avg_smg_devk

 FROM report_devk_fake_values ra 
WHERE jahr=2014
GROUP BY rd;";

$q=mysql_query($abfrage,$verbindung);
$temp = array(); 

while ($row = mysql_fetch_array($q)){
    
   // $temp .= '"' . $row['rd'] . '", ' . $row['anz_Berichte'] . ', ' . $row['avg_smg_actineo'] . ', ' . $row['avg_smg_devk'];
   $temp[] = $row[0];
   $temp[] = $row[1];
   $temp[] = (int)$row[2];
   $temp[] = (int)$row[3];
//$list['rd'][]=$row['rd'];
//$list['smg_table'][]=(int)$row['anz_Berichte']['avg_smg_actineo'][' avg_smg_devk']*1.5;

//$list['min'][]=$row['min_devk'];

}

return $temp;
}
$avg_smg=getAvg_smg1();
$array=$avg_smg['smg_table'];

#########################################################################################

################################ Table NR.1 Parameter####################################

// Définition des propriétés du tableau.
$proprietesTableau = array(
	'BRD_COLOR' => array(255,255,255),
	'BRD_SIZE' => '0.3',
	'TB_ALIGN' => 'L',
	'L_MARGIN' => 00,
	);


// Définition des propriétés du header du tableau.	
$proprieteHeader = array(
	'T_COLOR' => array(255,255,255),
	'T_SIZE' => 10,
	'T_FONT' => 'Arial',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'C',
	'V_ALIGN' => 'T',
	'T_TYPE' => 'B',
	'LN_SIZE' => 7,
	'BG_COLOR_COL0' => array(200, 87, 0),
	'BG_COLOR' => array(200, 87, 0),
	'BRD_COLOR' => array(213,222,219),
	'BRD_SIZE' => 0.3,
	'BRD_TYPE' => 1,
	'BRD_TYPE_NEW_PAGE' => '',
	);



// Contenu du header du tableau.	
$contenuHeader = array(
	30, 30, 30,30,
	utf8_decode("Geschäftsjahr"), "ACTINEO", "VR geliefert*","DS verarbeitet"
	);

// Définition des propriétés du reste du contenu du tableau.	
$proprieteContenu = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 7,
	'T_FONT' => 'Arial',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'R',
	'V_ALIGN' => 'M',
	'T_TYPE' => '',
	'LN_SIZE' => 5,
	'BG_COLOR_COL0' => array(208, 208, 208),
	'BG_COLOR' => array(208,208,208),
	'BRD_COLOR' => array(213,222,219),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
	);	

// Contenu du tableau.	
$contenuTableau = array(
	"GJ 2013", 729, 248,233,
	"GJ 2014", 716,254,205,
        
         
	);	
#########################################################################################

################################ Table NR.2 Parameter####################################
// Définition des propriétés du tableau.
$proprietesTableau2 = array(
	'BRD_COLOR' => array(255,255,255),
	'BRD_SIZE' => '0.3',
	'TB_ALIGN' => 'L',
	'L_MARGIN' => 30,
	);


// Définition des propriétés du header du tableau.	
$proprieteHeader2 = array(
	'T_COLOR' => array(255,255,255),
	'T_SIZE' => 9,
	'T_FONT' => 'Arial',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'C',
	'V_ALIGN' => 'T',
	'T_TYPE' => 'B',
	'LN_SIZE' => 5,
	'BG_COLOR_COL0' => array(0, 156, 196),
	'BG_COLOR' => array(0,156, 196),
	'BRD_COLOR' => array(213,222,219),
	'BRD_SIZE' => 0.3,
	'BRD_TYPE' => 1,
	'BRD_TYPE_NEW_PAGE' => '',
	);



// Contenu du header du tableau.	
$contenuHeader2 = array(
	30, 30, 30,30,      # Breite der Spalten
	"Standort", "Berichte[Anz]", "AC ".chr(248). "-Vorschlag [".chr(128)."]","VR ".chr(248). "-Reguliert [".chr(128)."]"
	);

// Définition des propriétés du reste du contenu du tableau.	
$proprieteContenu2 = array(
	'T_COLOR' => array(0,0,0),
	'T_SIZE' => 7,
	'T_FONT' => 'Arial',
	'T_ALIGN_COL0' => 'L',
	'T_ALIGN' => 'R',
	'V_ALIGN' => 'M',
	'T_TYPE' => '',
	'LN_SIZE' => 4,
	'BG_COLOR_COL0' => array(255, 255, 255),
	'BG_COLOR' => array(208,208,208),
	'BRD_COLOR' => array(213,222,219),
	'BRD_SIZE' => 0.1,
	'BRD_TYPE' => '1',
	'BRD_TYPE_NEW_PAGE' => '',
	);	


$contenuTableau2 = $avg_smg;

##########################################################################################

$pdf=new phpToPDF();


$pdf->AddPage();
$pdf->SetFont("Arial","B","20");
$pdf->SetY(70);
$pdf->MultiCell(120, 10, utf8_decode('Reporting-Standort Vergleich DEVK'), '', 'L', False);
$pdf->SetY(105);
$pdf->SetFont("Arial","","14");
#$fpdf->AddFont('Raleway-Light','','Raleway-Light.php');
#$fpdf->SetFont('Raleway-Light','B',11);
$pdf->Cell(50, 7, "Auswertung", '', 0, 'L');
$pdf->Cell(20, 7,utf8_decode( "Geschäftsjahr 2014"), '', 1, 'L');
$pdf->Cell(50, 7, "Erstellt", '', 0, 'L');
$pdf->Cell(20, 7,utf8_decode( "September 2015"), '', 1, 'L');
$pdf->SetFont("Arial","","12");
$pdf->Cell(20, 30,utf8_decode( "Anzahl der abgeschlossenen Vorgänge"), '', 0, 'L');
$pdf->SetY(140);
$pdf->drawTableau($pdf, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
$pdf->Cell(0,5,utf8_decode(' *Abgeschlossene und regulierte Vorgänge mit einem AST'),'',1,'L');
$pdf->SetFont("Arial","B","14");
$pdf->Cell(30, 40, "Ansprechpartner", '', 1, 'L');
$pdf->SetFont("Arial","","11");
$pdf->Cell(70, 7, "Olav Skowronnek", '', 0, 'L');
$pdf->Cell(20, 7,utf8_decode( "Verena Klumb"), '', 1, 'L');
$pdf->SetFont("Arial","","7");
$pdf->Cell(70, 7, utf8_decode("Geschäftsführer"), '', 0, 'L');
$pdf->Cell(20, 7,utf8_decode( "Geschäftsführerin"), '', 1, 'L');
$pdf->SetFont("Arial","","9");
$pdf->Cell(70, 5, utf8_decode("Tel 2236.48003100"), '', 0, 'L');
$pdf->Cell(20, 5,utf8_decode( "Tel 2336.48003123"), '', 1, 'L');
$pdf->SetFont("Arial","","9");
$pdf->Cell(70, 5, utf8_decode("olav.skowronnek@actineo.de"), '', 0, 'L');
$pdf->Cell(20, 5,utf8_decode( "verena.klumb@actineo.de"), '', 1, 'L');

#$pdf->Cell(20, 5,$data['rd'], '', 1, 'L');
#$pdf->Cell(20, 5,$data['avg(smg_devk)'], '', 1, 'L');
#$pdf->Cell(20, 5,$data['rd'], '', 1, 'L');

/*
$pdf->Cell(20, 5,$Max, '', 1, 'L');
$pdf->Cell(20, 5,$Min, '', 1, 'L');
$pdf->Cell(20, 5,$Open, '', 1, 'L');
$pdf->Cell(20, 5,$Close, '', 1, 'L');
$pdf->Cell(20, 5,round($Median, 2), '', 1, 'L');
$pdf->Cell(20, 5,$AnzahlB, '', 1, 'L');
*/
$pdf->AddPage();
$pdf->image('Radar.png',20,50,170,130);
//$pdf->SetY(160);
//$pdf->image('example7.png',20,150,100,100);
$pdf->AddPage();
$pdf->SetY(50);
$pdf->drawTableau($pdf, $proprietesTableau2, $proprieteHeader2, $contenuHeader2, $proprieteContenu2, $contenuTableau2);
$pdf->image('BarChart.png',10,150,190,110);

$pdf->AddPage();
$pdf->image('Boxplot.png',10,40,190,100);
#$pdf->SetTextColor(0, 0, 204);
#$pdf->SetFont('Arial', 'B', 7);
#$pdf->SetXY(198,95);
#$pdf->Cell(50, 7, (int)$avg_smg_devk[0], '', 0, 'L');
#$pdf->SetTextColor(0, 0, 0);
$pdf->image('Barstacked.png',10,150,190,110);
$pdf->Output();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

