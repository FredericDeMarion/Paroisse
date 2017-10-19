<?php

function sqlDateToOut($sqldate) {

  setlocale (LC_TIME, "fr");

  $year= substr($sqldate,0,4);
  $month= substr($sqldate,5,2);
  $day = substr($sqldate,8,2);
  $hour = substr($sqldate,11,2);
  $min = substr($sqldate,14,2);
  $anyyear = 2000;

  $time = mktime($hour,$min,0,$month,$day, $year); // $anyyear au lieu de $birthyear car prob. avec date de naissance < 1er Janvier 1970
  return strftime("%e",$sqldate)."/".strftime("%m",$sqldate)."/".strftime("%Y",$sqldate)."<B> à </B>".strftime("%R ",$sqldate); 
  //return $day."/".$month."/".$year." ".$hour.":".$min;

}

function sqlDateToAge($sqldate) {

  $birthyear= substr($sqldate,0,4);
  $birthmonth= substr($sqldate,5,2);
  $birthday = substr($sqldate,8,2);

  $nowyear = date("Y");
  $nowmonth = date("m");
  $nowday = date("d");

  if (($nowmonth<$birthmonth) || (($nowmonth == $birthmonth) && ($nowday<$birthday))) {
    $correction = -1;
  } else {
    $correction = 0;
  }

  $age = $nowyear - $birthyear + $correction;
  return $age;

}

function personne_affch_full($enregistrement) {
  echo "<!-- PERSONNE -->\n";
  echo "<TABLE border=\"1\" cellpadding=\"5\"><TR>\n";

  echo "<!-- PHOTO -->\n";
  echo "<TD>\n";
  if (file_exists($enregistrement['id'] . ".jpg")) {
    echo "<IMG SRC=\"" . $enregistrement['id'] . ".jpg\">" . "<BR><BR>";
  }
  echo "</TD>\n";
  echo "<!-- /PHOTO -->\n";

  echo "<!-- FICHE -->\n";
  echo "<TD>\n";
  echo "<TR><TD width=\"150\" ><FONT size=\"3\" face=\"comic sans ms\" ><B>Fiche No </B>". $enregistrement['id'] . "</FONT></TD><TD width=\"400\" ><FONT size=\"3\" face=\"comic sans ms\" ><B> Accompagnateurs </B>". $enregistrement['Accompagnateurs'] . "</FONT></TD></TR>";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Lui  </B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" >" . ucfirst($enregistrement['LUI_Prenom']) . " " . strtoupper($enregistrement['LUI_Nom'])  . "</FONT></TD></TR>";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Elle </B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" >" . ucfirst($enregistrement['ELLE_Prenom']) . " " . strtoupper($enregistrement['ELLE_Nom'])  . "</FONT></TD></TR><BR>\n";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Adresse </B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" >" . $enregistrement['Adresse'] . "</FONT></TD></TR><BR>\n";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Telephone </B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" >" . $enregistrement['Telephone'] ."</FONT></TD></TR>" ;
  //echo "<TR><TD><IMG SRC=\"logo_at.png\"></TD><TD><A HREF=\"" . $enregistrement['Email'] . "\">" . $enregistrement['Email'] . "</A><BR></TD></TR><BR>\n";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>E-Mail</B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" ><A HREF=\"" . $enregistrement['Email'] . "\">" . $enregistrement['Email'] . "</A><BR></FONT></TD></TR><BR>\n";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Enfants à charge </B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" >" . $enregistrement['Enfant'] . "</FONT></TD></TR><BR>\n";
  echo "<TR><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>Mariage</B></FONT></TD><TD><FONT size=\"3\" face=\"comic sans ms\" ><B>  </B>" . $enregistrement['Lieu mariage'] . " <B> le </B>" . sqlDateToOut($enregistrement['Date mariage']) . " <B> par </B>" . $enregistrement['Celebrant'] . "</FONT></TD></TR><BR><BR>\n";
 
  echo "</TD>\n";
  echo "<!-- /FICHE -->\n";

  echo "</TR></TABLE>\n";
  echo "<!-- /PERSONNE -->\n";
  echo "<TABLE border=\"1\" cellpadding=\"5\">\n";
  echo "<TR><TD width=\"150\" ><FONT size=\"3\" face=\"comic sans ms\" ><B>Commentaires </B></FONT></TD><TD width=\"400\" ><FONT size=\"3\" face=\"comic sans ms\" >". $enregistrement['Commentaire'] . "</FONT></TD></TABLE><BR>\n";
}

?>

<HTML><HEAD>
<TITLE>Carnet d'adresses</TITLE>
</HEAD>

<BODY>

<?

require('sqlconf.php');

// Connexion au serveur MySQL
@mysql_connect( $sqlserver , $login , $password )
or die( 'Connexion au serveur [<FONT COLOR=RED>Impossible</FONT> ]' ) ;

// Sélection de la base de données
@mysql_select_db( $sqlbase )
or die( 'Sélection de la base de donnée [<FONT COLOR=RED>Impossible</FONT> ]' ) ;

//$personne_id = 24;
$requete = 'SELECT * FROM ' . $Table .' WHERE id=' . $personne_id . ' ';
$resultat = mysql_query( $requete );

$enregistrement = mysql_fetch_array( $resultat );

personne_affch_full($enregistrement);

?>
  
</BODY>
</HTML>
