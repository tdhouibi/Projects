<?php

 define("CLASS_PATH", "class");
 define("FONT_PATH", "fonts");
 


include('fpdf/phpToPDF.php');
 
 include(CLASS_PATH."/Boxplot.php");
 require_once('fpdf/efpdf.php');
 

$verbindung = mysql_connect("192.168.210.135", "bi", "mysql") or die ("Fehler im System");
$connection=mysql_select_db("kunden_daten",$verbindung);



function get_Median($zahlen_array = array()) {
  $anzahl = count($zahlen_array);
  if($anzahl == 0 ){
    return false;
  }
  sort($zahlen_array);
  if($anzahl % 2 == 0){
    //gerade Anzahl => der Median ist das arithmetische Mittel der beiden mittleren Zahlen
    return ($zahlen_array[ ($anzahl/2) - 1 ] + $zahlen_array[ $anzahl/2 ]) / 2 ;
  } else {    
    //ungerade Anzahl => der mittlere Wert ist der Median    
    return $zahlen_array[$anzahl/2];
  }
} 
 
function Quartil($data,$percentile){
    if( 0 < $percentile && $percentile < 1 ) {
        $p = $percentile;
    }else if( 1 < $percentile && $percentile <= 100 ) {
        $p = $percentile * .01;
    }else {
        return "";
    }
    $count = count($data);
    $allindex = ($count-1)*$p;
    $intvalindex = intval($allindex);
    $floatval = $allindex - $intvalindex;
    sort($data);
    if(!is_float($floatval)){
        $result = $data[$intvalindex];
    }else {
        if($count > $intvalindex+1)
            $result = $floatval*($data[$intvalindex+1] - $data[$intvalindex]) + $data[$intvalindex];
        else
            $result = $data[$intvalindex];
    }
    return $result;
} 
 function get_rd() {
  
     
         Global $verbindung;

$abfrage= "select distinct(smg.k_bestandsnr) as rd from
  
(select * from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' 

and k_bemerkung like '13%'

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as smg";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    
$list['rd'][]=$row['rd'];

}
    return $list; 
 }
 $rdlist=get_rd();
 
 $alle_rd=$rdlist['rd'];
  

 
 function getSmg_kunde($rd) {
    Global $verbindung;
    
   

$abfrage= "select smg.k_smg as smg_kunde from
  
(select * from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' 

and k_bemerkung like '13%'
and k_smg != 0
and k_bestandsnr = $rd

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as smg";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    

$list['median'][]=(int)$row['smg_kunde'];



}
return $list;
  
}

 function getSmg_actineo($rd) {
    Global $verbindung;
    
   

$abfrage= "select smg.smg as smg_actineo from
  
(select * from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' 

and k_bemerkung like '13%'
and smg != 0
and k_bestandsnr = $rd

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as smg";

$q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    

$list['median'][]=(int)$row['smg_actineo'];



}
return $list;
  
}

function set_values($rd,$jahr,$anzahl,$anzahl_smg_ls,$anzahl_smg_gr,
                    $smg_k_median,$smg_k_quartil1,$smg_k_quartil3,$smg_k_max,$smg_k_min,
                    $smg_k_durchschnitt,$smg_max,$smg_min,$smg_durchschnitt,$smg_median,$smg_quartil1,$smg_quartil3){
    Global $verbindung;
    

    
   $abfrage="INSERT INTO zwischen_tabelle (rd,jahr,anzahl,anzahl_smg_ls,anzahl_smg_gr, smg_k_median,"
           . " smg_k_quartil1, smg_k_quartil3,smg_k_max, smg_k_min, smg_k_durchschnitt,"
           . "smg_max, smg_min, smg_durchschnitt, "
           ."smg_median,smg_quartil1,smg_quartil3)"
           . "VALUES ('".$rd."','".$jahr."','".$anzahl. "','".$anzahl_smg_ls."','".$anzahl_smg_gr."'"
           . ",'".$smg_k_median."','".$smg_k_quartil1."','".$smg_k_quartil3."'"
           . ",'".$smg_k_max."','".$smg_k_min."','".$smg_k_durchschnitt."'"
           . ",'".$smg_max."','".$smg_min."','".$smg_durchschnitt."'"
           . ",'".$smg_median."','".$smg_quartil1."','".$smg_quartil3."')" ;
//   var_dump($abfrage);
//    exit;
    return mysql_fetch_array(mysql_query($abfrage,$verbindung));
    
}

function get_smg_gr($sg_korridor_oben,$rd)
{ 
   
    
    Global $verbindung;
    #$gr = $avg_actineo+($avg_actineo*$sg_korridor_oben/100);
   
    
    $abfrage= "select result.smg_oben, count(result.k_smg) as smg_gr from 

(select k_bestandsnr,k_smg,smg,smg*$sg_korridor_oben as smg_oben from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' and k_bemerkung like '13%' and k_bestandsnr =$rd

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as result

where result.smg_oben < result.k_smg
";
          
            
       
    
    $q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    

$list['smg_gr'][]=(int)$row['smg_gr'];

}
  return $list['smg_gr'][0];  
}


function get_smg_ls($sg_korridor_unten,$rd)
{ 
   
    
    Global $verbindung;
   # $ls = $avg_actineo+($avg_actineo*$sg_korridor_unten/100);
   
   
      
    $abfrage= "select result.smg_unten, count(result.k_smg) as smg_ls from 

(select k_bestandsnr,k_smg,smg,smg*$sg_korridor_unten as smg_unten from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' and k_bemerkung like '13%' and k_bestandsnr =$rd

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as result

where result.smg_unten < result.k_smg
";
    
    $q=mysql_query($abfrage,$verbindung);

while ($row = mysql_fetch_array($q)){
    

$list['smg_ls'][]=(int)$row['smg_ls'];

}
  return $list['smg_ls'][0];  
}



$result = mysql_query("select distinct(smg.k_bestandsnr) from
  
(select * from admiral_smg a

inner join admiral_ac_smg ac on ac.schadennummer = a.ac_schadennummer and a.ac_gebdat = ac.gebdat

where a.k_status = 'geschlossen' and k_bemerkung like '13%'

group by ac.schadennummer,ac.name,ac.vorname

order by ac.schadennummer) as smg
");


$jahr=2013;
for ($i=0;$i<mysql_num_rows($result);$i++):
 
   ################## Kunde bezogene smg ####################
    $smgList=getSmg_kunde($alle_rd[$i]);
    $smgMedianList=$smgList['median'];
    $smg_max_kunde=max($smgMedianList);
    $smg_min_kunde=min($smgMedianList);
    $count_array = count($smgMedianList);
    $sum_array =  array_sum($smgMedianList);
    $smg_durchschnitt_kunde = (int)($sum_array / $count_array);
   ###############################################################
    
   
    
   ################## Actineo bezogene smg ########################
   
    $smg_actineo_List=getSmg_actineo($alle_rd[$i]);
    $smgMedian_actineo_List=$smg_actineo_List['median'];
    $smg_max_actineo=max($smgMedian_actineo_List);
    $smg_min_actineo=min($smgMedian_actineo_List);
    $count_actineo_array = count($smgMedian_actineo_List);
    $sum_actineo_array =  array_sum($smgMedianList);
    $smg_durchschnitt_actineo = (int)($sum_actineo_array / $count_actineo_array);
    
//    $v=get_smg_gr(25,$smg_durchschnitt_actineo,$alle_rd[$i]);
//    var_dump($v);
//    exit;
    
  ################################################################## 
//    var_dump($smg_durchschnitt_kunde);
//    exit;
    
    set_values($alle_rd[$i],$jahr,$count_array,get_smg_ls(1.25,$alle_rd[$i]),get_smg_gr(1.15,$alle_rd[$i]),
    get_Median($smgMedianList),Quartil($smgMedianList,0.25),
    Quartil($smgMedianList,0.75),$smg_max_kunde,$smg_min_kunde,$smg_durchschnitt_kunde,$smg_max_actineo,$smg_min_actineo,$smg_durchschnitt_actineo,
    get_Median($smgMedian_actineo_List),Quartil($smgMedian_actineo_List,0.25),Quartil($smgMedian_actineo_List,0.75));  
   
    #set_smg_Max_Min_Avg_kunde($smg_max_kunde,$smg_min_kunde,$smg_durchschnitt_kunde);

//$count_array=" select count(smg_devk)
//from report_devk_fake
//where rd=19 and jahr=2014
       
endfor;


#####################################################################
$median=get_Median($smgMedianList);
$quartil1= Quartil($smgMedianList,0.25);
$quartil3= Quartil($smgMedianList,0.75);

echo $median."<br>";
echo $quartil1."<br>";
echo $quartil3."<br>";


