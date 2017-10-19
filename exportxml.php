<?php

session_start();
$debug = true;
//$IdSession = $_POST["IdSession"];
//session_readonly();

function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}
//$Activite= 1; //All
//$SessionEnCours=$_SESSION["Session"];
require('Common.php');
require('templateSuiviParoissien.inc');
$debug = false;
require('Paroissien.php');
#On liste les activitees
$xml = new SimpleXMLElement('<people></people>');
$dieu=$xml->addChild('person', '');
$dieu->addAttribute('name','Dieu');

//on liste les activitees ici (groupes derriere Dieu)
$select_activite="select id, Nom from Activites where id !=1 and Service=1 ";
$result = mysql_query($select_activite);//, $db);
while($row = mysql_fetch_array($result)){
    //FIXME ajouter la personalisation ode la case 
    $myservice=$dieu->addChild('person', '');
    $myservice->addAttribute('name',utf8_encode($row['Nom']));
    $myservice->addAttribute('class','categorie');
//    $myact->addAttribute('mycode',htmlentities($row['Code']));
    $last_responsable=$myservice;
    //on va chercher dans la base quel est la personne qui est responsable de ce service
    $select2="select Individu.id, Nom, Prenom, e_mail, Telephone, Individu_id from QuiQuoi LEFT JOIN Individu on Individu.id=Individu_id where Activite_id=".$row['id']." and Session=2017 and Responsable=1 ";
    $result2 =mysql_query($select2);//, $db);
    while($row2 = mysql_fetch_array($result2)){
         $responsable=$myservice->addChild('person','');
         $responsable->addAttribute('name',utf8_encode($row2['Prenom']." ".$row2['Nom']));
         $responsable->addAttribute('email',utf8_encode($row2['e_mail']));
         $responsable->addAttribute('telephone',utf8_encode($row2['Telephone']));
         if( file_exists( "Photos/Individu_".$row2['id'].".jpg" ))
         {
             $responsable->addAttribute('image',"../Photos/Individu_".$row2['id'].".jpg");
         }
         $responsable->addAttribute('class','Responsable');
         $last_responsable=$responsable;
    }
    //maintennant on rajoute au service tous les membres de l equipe

  /*  $select3="select Nom, Prenom, e_mail, Telephone, Individu_id from QuiQuoi LEFT JOIN Individu on Individu.id=Individu_id where Activite_id=".$row['id']." and Session=2017 and Responsable!=1 and dead=0 ";
    $result3 =mysql_query($select3);//, $db);
    while($row3 = mysql_fetch_array($result3)){

    $equipe=$last_responsable->addChild('person','');
    $equipe->addAttribute('name',utf8_encode($row3['Prenom']." ".$row3['Nom']));
  if( file_exists( "Photos/Individu_".$row2['id'].".jpg" ))
         {
             $responsable->addAttribute('image',"../Photos/Individu_".$row2['id'].".jpg");
         }

    $equipe->addAttribute('email',utf8_encode($row3['e_mail']));
    $equipe->addAttribute('telephone',utf8_encode($row3['Telephone']));
    $equipe->addAttribute('class','people');
    }
*/
}

//header('Content-Type: application/xml');
$res=$xml->saveXML();
echo ($res);
file_put_contents('orga/organigramme.xml',$res);
echo("ok");
exit(0);

/*
$xml = new SimpleXMLElement('<employees></employees>');
foreach($employees as $employ):
$xml->addChild('name',$employ['name']);
$xml->addChild('age',$employ['age']);
endforeach;
file_put_contents('file.xml',$xml->saveXML());

*/
