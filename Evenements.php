<?php

//==================================================================================================
//    Nom du module : Evenements.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 09/05/2017 : Ajouter l'équipe liturgique pour changer les animateurs et musiciens des messes
// 10/05/2017 : Ajouter un deuxième musicien
// 26/05/2017 : Programmation des futurs célébration récurrentes
// 28/05/2017 : à la suppression d'un événement, la page était redirigée vers une mauvaise page
// 28/05/2017 : La dernière ligne de la requête n'est pas affichée dans le listing général
// 17/07/2017 : Ajout de la fonction Gerer_Equipe_Technique_Messe
// 30/09/2017 : Problème d'affichage du mauvais célébrant dans la liste générale
// 26/10/2017 : Affichage du dernier événement pendant 5h
//==================================================================================================

if (isset( $_SERVER['PHP_AUTH_USER'] ) AND $_SERVER['PHP_AUTH_USER'] == "celebration"){
	echo '<META http-equiv="refresh" content="1; URL=https://'.$_SERVER['SERVER_NAME'].'/Celebration/index.php">';
}

function debug($ch) {
   global $debug;
   if ($debug)
      echo $ch;
}



//row color
function usecolor()
{
	$trcolor1 = "#EEEEEE";
	$trcolor2 = "#E1E1E1";
	static $colorvalue;
	if($colorvalue == $trcolor1)
		$colorvalue = $trcolor2;
	else
		$colorvalue = $trcolor1;
	return($colorvalue);
}

session_start();
$debug = False;
//$IdSession = $_POST["IdSession"];
//session_readonly();

$Activite_id= 86; //Messe
$SessionEnCours=$_SESSION["Session"];
//require('Login/sqlconf.php');
//mysql_connect( $sqlserver , $login , $password ) ;
//mysql_select_db( $sqlbase );
require('Common.php');
require('templateEvenement.inc');


setlocale (LC_TIME, 'fr_FR','fra'); 
$debug = false;
debug("SessionEnCours=".$SessionEnCours . "<BR>\n");

require('Paroissien.php');

// Activation des fonctions suivant les paroisses
// -----------------------------------------------
Global $eCOM_db;
$Paroisse_name = "Inconnue";
$requete_Lieux = 'SELECT * FROM Lieux WHERE IsParoisse = -1';
$result_Lieux = mysqli_query($eCOM_db, $requete_Lieux);
while($row_lieu = mysqli_fetch_array($result_Lieux)){
	$Paroisse_name = $row_lieu['Lieu'];
}
$Gerer_Equipe_Technique_Messe = True;
if ($Paroisse_name == "Notre Dame de la Sagesse") {
	$Gerer_Equipe_Technique_Messe = False;
}

//--------------------------------------------------------------------------------------
//delete one rencontre by id
//--------------------------------------------------------------------------------------
if ( isset( $_POST['Evenement_delete'] ) AND $_POST['Evenement_delete']=="Supprimer") {
//if ($Evenement_delete) {

	Global $eCOM_db;
	$debug = false;
	debug($Table . "<BR>\n");

	address_top();
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Database Rencontre: Suppression d\'un événement</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<FONT FACE="verdana" color="#313131" size="2">Etes-vous certain de vouloir supprimer cet événement ? </FONT><BR><BR>';
	
	$requete = 'SELECT T0.`id`, T0.`Date`, T0.`Intitule`, T1.`Lieu` 
				FROM `Rencontres` T0
				LEFT JOIN `Lieux` T1 ON T0.`Lieux_id` = T1.`id` 
				WHERE T0.`id`='.$_POST['id'].' ';
	error_log('Evenement:Evenement_delete -> requete='.$requete);
	$result = mysqli_query( $eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)) {
		echo '<FONT face=verdana color=#313131 size=2>';
		echo strftime("%d/%m/%y  %H:%M    ", sqlDateToOut($row['Date']));
		echo '</FONT>';
		echo '<FONT face=verdana color=#313131 size=2> - '.$row['Lieu'].'</FONT>';
		echo '<FONT face=verdana color=#313131 size=2> < '.$row['Intitule'].' ></FONT>';
	}
	echo '<P><FORM method=post action="'.$_SERVER['PHP_SELF'].'">';
	echo '<input type="submit" name="Evenement_supprimer" value="Oui">';
	echo '<input type="submit" name="" value="Non">';
	echo '<input type="hidden" name="id" value="'.$_POST['id'].'">';
	echo '</FORM>';
	fCOM_address_bottom();
	exit();	
}


if ( isset( $_POST['Evenement_supprimer'] ) AND $_POST['Evenement_supprimer']=="Oui") {
//if ($Evenement_supprimer){
	Global $eCOM_db;
	Global $Activite_id;
	error_log('Evenement:Evenement_supprimer -> Activite='.$Activite_id);
	$requete = 'DELETE FROM Rencontres WHERE id='.$_POST['id'].' '; 
	debug($requete . "<BR>\n");
    $result = mysqli_query( $eCOM_db, $requete); 
	if (!$result) {
		mysqli_query( $eCOM_db, 'DELETE FROM QuiQuoi WHERE Activite_id='.$Activite_id.' and Engagement_id='.$_POST['id'].'') or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Impossible d\'exécuter la requête : '.mysqli_error($eCOM_db).'</FONT></CENTER></B>';
		
    } else {
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Evénement supprimée avec succes</FONT></CENTER></B>';
	}
	echo '<META http-equiv="refresh" content="2; URL='.$_SERVER['PHP_SELF'].'">';
	exit;
}



if ( isset( $_POST['Evenement_sauvegarder'] ) AND $_POST['Evenement_sauvegarder']=="Enregistrer") {
//if ($Evenement_sauvegarder){
	Global $eCOM_db;
	$debug = False;
	
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder DateRencontre='.$_POST['DateRencontre']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder heure='.$_POST['heure']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder minute='.$_POST['minute']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder Lieux='.$_POST['Lieux']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder Intitule='.$_POST['Intitule']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder EveilFoi='.$_POST['EveilFoi']);
	pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder Garderie='.$_POST['Garderie']);
	
	//if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})", $_POST['DateRencontre'], $regs)) {
	$DateTimeValue=fCOM_getSqlDate($_POST['DateRencontre'], $_POST['heure'], $_POST['minute'], 0);
	if ($DateTimeValue != "") {

		$Activite_id=86;
		if (date("n") <= 7 ) { $Session= date("Y");	} else { $Session= date("Y")+1;	}
		
		if ($_POST['id'] > 0) {
			mysqli_query( $eCOM_db, 'UPDATE Rencontres SET 
				Date="'.$DateTimeValue.'", 
				Intitule="'.$_POST['Intitule'].'", 
				Lieux_id="'.$_POST['Lieux'].'", 
				Session="'.$Session.'", 
				Activite_id="'.$Activite_id.'" 
				WHERE id='.$_POST['id'].'') or die (mysqli_error($eCOM_db));
			
			$Engagement_Id=$_POST['id'];

		} else {
			$requete = 'INSERT INTO Rencontres (Activite_id, Session, Date, Classement, Intitule, Lieux_id) VALUES ('.$Activite_id.',"'.$Session.'", "'.$DateTimeValue.'", "", "'.$_POST['Intitule'].'", '.$_POST['Lieux'].')';
			
			pCOM_DebugAdd($debug, 'Evenement:Evenement_sauvegarder requete='.$requete);
			mysqli_query( $eCOM_db, $requete) or die (mysqli_error($eCOM_db));
			$Engagement_Id=mysqli_insert_id($eCOM_db);
		}
		
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 5, $_POST['Celebrant']);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 12, $_POST['Sacristin']);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 13, $_POST['Animateur']);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 13, $_POST['Animateur_02'], 2);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 21, $_POST['Musicien']);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 21, $_POST['Musicien_02'], 2);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 16, $_POST['EveilFoi']);
		Enregistrer_Intervenants($Activite_id, $Engagement_Id, 23, $_POST['Garderie']);
		if ( $Gerer_Equipe_Technique_Messe == True) {
			Enregistrer_Intervenants($Activite_id, $Engagement_Id, 24, $_POST['Sono']);
			Enregistrer_Intervenants($Activite_id, $Engagement_Id, 25, $_POST['Projection']);
			Enregistrer_Intervenants($Activite_id, $Engagement_Id, 26, $_POST['Broadcast']);
		}
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Evénement sauvegardée avec succes</FONT></CENTER></B>';
	} else {
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Format de date invalide : '.$_POST['DateRencontre'].'</FONT></CENTER></B>';
		exit;
	}
	echo '<META http-equiv="refresh" content="1; URL='.$_SERVER['PHP_SELF'].'">';
	exit;
}

function Enregistrer_Intervenants($pActivite_id, $pEngagement_Id, $pQuoiQuoi_id, $pIndividu_id, $NbOccurence=1) {
	Global $eCOM_db;
	
	if (date("n") <= 7 ) { $Session= date("Y");	} else { $Session= date("Y")+1;	}
	
	// tester l'existance de la donnée en table avant d'opérer
	$Requete='SELECT id FROM QuiQuoi WHERE Activite_id='.$pActivite_id.' AND Engagement_id='.$pEngagement_Id.' AND QuoiQuoi_id='.$pQuoiQuoi_id.' ORDER BY id';
	$result=mysqli_query( $eCOM_db, $Requete);
	$counter_line=mysqli_num_rows($result);
	if ($counter_line < $NbOccurence) {
		if ($pIndividu_id != 0) {
			mysqli_query( $eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES ('.$pIndividu_id.','.$pActivite_id.','.$pEngagement_Id.', '.$pQuoiQuoi_id.', '.$Session.')') or die (mysqli_error($eCOM_db));
		}
	} else {
		$Compteur = 1;
		$result=mysqli_query( $eCOM_db, $Requete);
		while($row = mysqli_fetch_assoc($result)){
			if ($NbOccurence == $Compteur) {
				//mysqli_query( $eCOM_db, 'UPDATE QuiQuoi SET Individu_id="'.$pIndividu_id.'" WHERE Activite_id='.$pActivite_id.' AND Engagement_id='.$pEngagement_Id.' AND QuoiQuoi_id='.$pQuoiQuoi_id.' ') or die (mysqli_error($eCOM_db));
				mysqli_query( $eCOM_db, 'UPDATE QuiQuoi SET Individu_id="'.$pIndividu_id.'" WHERE id='.$row['id'].'') or die (mysqli_error($eCOM_db));
			}
			$Compteur = $Compteur + 1;
		}
	}
}

//==========================================================
//==========================================================
// edit records Rencontres
//==========================================================
//==========================================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit") {
//if ($action == "edit") { 

	Global $eCOM_db;
	Global $Activite_id;
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';

	address_top();

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	if ($_GET['id'] == 0) {
		echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Nouvel Célébration</B></FONT></TD></TR>';
	} else {
		echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Célébration No '.$_GET['id'].'</B></FONT></TD></TR>'; 
	}

	if ($_GET['id'] > 0) {
		$Compteur = 1;
		$_Musicien_02="";
		$_Animateur_02="";
		$requete = 'SELECT T0.`id`, T0.`Date`, T0.`Intitule`, T1.`Lieu`, CONCAT(T3.`Prenom`, " ", T3.`Nom`) as Celebrant, CONCAT(T5.`Prenom`, " ", T5.`Nom`) as Sacristin, CONCAT(T7.`Prenom`, " ", T7.`Nom`) as Animateur, CONCAT(T9.`Prenom`, " ", T9.`Nom`) as Musicien, CONCAT(T11.`Prenom`, " ", T11.`Nom`) as EveilFoi, CONCAT(T13.`Prenom`, " ", T13.`Nom`) as Garderie, CONCAT(T15.`Prenom`, " ", T15.`Nom`) as Sono, CONCAT(T17.`Prenom`, " ", T17.`Nom`) as Projection, CONCAT(T19.`Prenom`, " ", T19.`Nom`) as Broadcast   
		FROM Rencontres T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieux_id` = T1.`id`
		LEFT JOIN `QuiQuoi` T2 ON T0.`id` = T2.`Engagement_id` AND T2.`QuoiQuoi_id`=5 AND T2.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T3 ON T2.`Individu_id` = T3.`id`
		LEFT JOIN `QuiQuoi` T4 ON T0.`id` = T4.`Engagement_id` AND T4.`QuoiQuoi_id`=12 AND T4.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T5 ON T4.`Individu_id` = T5.`id`
		LEFT JOIN `QuiQuoi` T6 ON T0.`id` = T6.`Engagement_id` AND T6.`QuoiQuoi_id`=13 AND T6.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T7 ON T6.`Individu_id` = T7.`id`
		LEFT JOIN `QuiQuoi` T8 ON T0.`id` = T8.`Engagement_id` AND T8.`QuoiQuoi_id`=21 AND T8.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T9 ON T8.`Individu_id` = T9.`id`
		LEFT JOIN `QuiQuoi` T10 ON T0.`id` = T10.`Engagement_id` AND T10.`QuoiQuoi_id`=16 AND T10.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T11 ON T10.`Individu_id` = T11.`id`
		LEFT JOIN `QuiQuoi` T12 ON T0.`id` = T12.`Engagement_id` AND T12.`QuoiQuoi_id`=23 AND T12.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T13 ON T12.`Individu_id` = T13.`id`
		LEFT JOIN `QuiQuoi` T14 ON T0.`id` = T14.`Engagement_id` AND T14.`QuoiQuoi_id`=24 AND T14.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T15 ON T14.`Individu_id` = T15.`id`
		LEFT JOIN `QuiQuoi` T16 ON T0.`id` = T16.`Engagement_id` AND T16.`QuoiQuoi_id`=25 AND T16.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T17 ON T16.`Individu_id` = T17.`id`
		LEFT JOIN `QuiQuoi` T18 ON T0.`id` = T18.`Engagement_id` AND T18.`QuoiQuoi_id`=26 AND T18.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T19 ON T18.`Individu_id` = T19.`id`
		WHERE T0.`Activite_id`=86 AND T0.`id`='.$_GET['id'].'
		ORDER BY Date';

		$result = mysqli_query( $eCOM_db, $requete);
		while($row = mysqli_fetch_assoc($result)){
			$DateYear=substr($row['Date'],0,4);
			$DateMonth=substr($row['Date'],5,2);
			$DateDay=substr($row['Date'],8,2);
			$_DateRencontre = $DateDay."/".$DateMonth."/".$DateYear;
			$_HeureRencontre = substr($row['Date'],11,2);
			$_MinuteRencontre = substr($row['Date'],14,2);
			$_Intitule = $row['Intitule'];
			$_Lieu = $row['Lieu'];
			$_Celebrant = $row['Celebrant'];
			$_Sacristin = $row['Sacristin'];
			if ($Compteur == 1) {
				$_Musicien = $row['Musicien'];
				$_Animateur = $row['Animateur'];
			} else {
				$_Musicien_02 = $row['Musicien'];
				$_Animateur_02 = $row['Animateur'];
			}
			$_EveilFoi = $row['EveilFoi'];
			$_Garderie = $row['Garderie'];
			$_Sono = $row['Sono'];
			$_Projection = $row['Projection'];
			$_Broadcast = $row['Broadcast'];
			if ( isset($_GET['Intitule']) ) {
				$_ComplementIntitule = unserialize(urldecode(stripslashes($_GET['Intitule'])));
				if (strlen($_Intitule) > 0 AND strlen($_ComplementIntitule) > 0){
					$_ComplementIntitule = substr($_ComplementIntitule, strlen($_Intitule), strlen($_ComplementIntitule)-strlen($_Intitule));
				}
				$pos = strpos($_ComplementIntitule, "<BR>");
				if ($pos !== FALSE AND $pos == 0) {
					$_ComplementIntitule = substr($_ComplementIntitule, 4-strlen($_ComplementIntitule));
				}
				if (strlen($_ComplementIntitule)>0) {
					$_ComplementIntitule = $_ComplementIntitule."<BR>";
				}
			} else {
				$_ComplementIntitule = "";
			}
			$Compteur = $Compteur + 1;
		}
	
	} else {
		if ( isset($_GET['Date']) ) {
			$pDate=unserialize(urldecode(stripslashes($_GET['Date'])));
			$debug=true;
			pCOM_DebugAdd($debug, "Evenement:Edit pDate=".$pDate);
			$_DateRencontre=substr($pDate,0,10);
			$_HeureRencontre = substr($pDate,12,2);
			$_MinuteRencontre = substr($pDate,15,2);
		} else {
			$_DateRencontre = "";
			$_HeureRencontre = "";
			$_MinuteRencontre = "";
		}
		if ( isset($_GET['Lieu']) ) {
			$_Lieu = $_GET['Lieu'];
		} else {
			$_Lieu = "";
		}
		if ( isset($_GET['Celebrant']) ) {
			$_Celebrant = unserialize(urldecode(stripslashes($_GET['Celebrant'])));
		} else {
			$_Celebrant = "";
		}
		if ( isset($_GET['Intitule']) ) {
			$_ComplementIntitule = unserialize(urldecode(stripslashes($_GET['Intitule'])));
		} else {
			$_ComplementIntitule = "";
		}
		if (strlen($_ComplementIntitule) > 0) {
			$_ComplementIntitule =$_ComplementIntitule."<BR>";
		}
		$_Intitule = "";
		$_Sacristin = "";
		$_Animateur = "";
		$_Animateur_02 = "";
		$_Musicien = "";
		$_Musicien_02 = "";
		$_EveilFoi = "";
		$_Garderie = "";
		$_Sono = "";
		$_Projection = "";
		$_Broadcast = "";
	}
	
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<FORM method=post action='.$_SERVER['PHP_SELF'].'>';
	echo '<P><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR>';

	// Intitulé
	echo '<TD colspan="2" bgcolor="#eeeeee" valign="middle">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Intitulé:</FONT></B><BR>';
	echo '<FONT FACE="Verdana" SIZE="2">'.$_ComplementIntitule.'</FONT>';
	echo '<input type=text name="Intitule" size="30" maxlength="100" value="'.$_Intitule.'"></TD>';

	echo '</TR><TR>';

	echo '<TD bgcolor="#eeeeee" valign="middle" height="60">'; //width="330" 
	echo '<B><FONT FACE="Verdana" SIZE="2">&nbsp Date: <FONT ACE="Verdana" SIZE="2"></B>';
	echo '<input type=text id="DateRencontre" name="DateRencontre" size="8" maxlength="10" value="'.$_DateRencontre.'">';
	?>
	<a href="javascript:popupwnd('calendrier.php?idcible=DateRencontre&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
	<?php
	echo '<B><FONT FACE="Verdana" SIZE="2">&nbsp Heure: <FONT ACE="Verdana" SIZE="2"></B>';
	echo '<b><FONT FACE="Verdana" SIZE="2"></FONT></b>';
	echo '<select name="heure">';
	for ($i=0; $i<=23; $i++) {
		if (sprintf("%02d", $i) == sprintf("%02d", $_HeureRencontre)) {
			echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
		} else {
			echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
		}
	}
	echo '</select><FONT FACE="Verdana" SIZE="2">h</FONT>';
	echo '<select name="minute">';
	for ($i=0; $i<=45; $i=$i+15) {
		if (sprintf("%02d", $i) == sprintf("%02d", $_MinuteRencontre)) {
			echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
		} else {
			echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
		}
	}
	echo '</SELECT></TD>';
	
	// Lieux
	$debug= False;
	echo '<TD bgcolor="#eeeeee" valign="middle">'; // width=100 
	echo '<B><FONT FACE="Verdana" SIZE="2">Lieux:</FONT></B><BR>';
	echo '<SELECT name="Lieux">';
	$Liste_Lieux_Evenements = pCOM_Get_liste_lieu_celebration(1);
	foreach ($Liste_Lieux_Evenements as $Lieu_Celebration_array){
		list($Lieu_id, $Lieu_name) = $Lieu_Celebration_array;
		pCOM_DebugAdd($debug, "Lieu_name= ".$Lieu_name);
		if ($_Lieu == $Lieu_name){
			$SelectionDefault = ' selected="selected"';
		} else {
			$SelectionDefault = '';
		}
		echo '<option value="'.$Lieu_id.'"'.$SelectionDefault.'>'.$Lieu_name.'</option>';
	}
	echo '</SELECT></TD>';
	
	echo '</TR><TR>';
	
	// Prêtre
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	echo '<TR><TD bgcolor="#eeeeee" valign="middle" height="60">';
	echo '<B><FONT FACE="Verdana" SIZE="2">&nbsp Célébrant:</FONT></B><BR>';
	echo '<SELECT name="Celebrant" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_celebrants();
	foreach ($liste_serviteurs as $Celebrant){
		list($Celebrant_id, $Celebrant_prenom, $Celebrant_nom) = $Celebrant;
		if ($_Celebrant == $Celebrant_prenom." ".$Celebrant_nom){
			$SelectionDefault = ' selected="selected"';
		} else {
			$SelectionDefault = '';
		}
		echo '<option value="'.$Celebrant_id.'"'.$SelectionDefault.'>'.$Celebrant_prenom." ".$Celebrant_nom.'</option>';
	}
	echo '</SELECT></TD>';	

	// Sacristins
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	if (fCOM_Get_Autorization( 16 ) >= 30) { $BloquerAcces="";} // 16 = Sacristie
	echo '<TD bgcolor="#eeeeee" valign="middle">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Sacristin:</FONT></B><BR>';
	echo '<SELECT name="Sacristin" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_serviteurs(16, False); // 16-sacristie
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Sacristin == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT></TD>';	
	
	echo '</TR><TR>';

	// Musiciens
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	if (fCOM_Get_Autorization( 51 ) >= 30) { $BloquerAcces="";} // 51 = Animateur Chants
	echo '<TR><TD bgcolor="#eeeeee" valign="middle" height="80">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Musiciens:</FONT></B><BR>';
	echo '<SELECT name="Musicien" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_serviteurs(36, False); // 36-Musique 
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Musicien == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT><BR>';
	// Musicien 02
	echo '<SELECT name="Musicien_02" '.$BloquerAcces.' >';
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Musicien_02 == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT>';
	echo '</TD>';
	
	// Animateurs
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	if (fCOM_Get_Autorization( 51 ) >= 30) { $BloquerAcces="";} // 51 = Animateur Chants
	echo '<TD bgcolor="#eeeeee" valign="middle">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Animateur:</FONT></B><BR>';
	echo '<SELECT name="Animateur" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_serviteurs(51, False); // 51-Animateur 
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Animateur == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT><BR>';
	// Animateur 2
	echo '<SELECT name="Animateur_02" '.$BloquerAcces.' >';
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Animateur_02 == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT>';
	echo '</TD>';

	echo '</TR><TR>';
		
	// Eveil à la Foi
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	if (fCOM_Get_Autorization( 14 ) >= 30) { $BloquerAcces="";} // 14-Eveil à la Foi
	echo '<TD bgcolor="#eeeeee" valign="middle" height="60">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Eveil à la Foi:</FONT></B><BR>';
	echo '<SELECT name="EveilFoi" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_serviteurs(14, False); // 14-Eveil à la Foi 
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_EveilFoi == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT></TD>';
	
	// Garderie
	$BloquerAcces="disabled='disabled'";
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
	if (fCOM_Get_Autorization( 90 ) >= 30) { $BloquerAcces="";} // 90-Garderie
	echo '<TD bgcolor="#eeeeee" valign="middle">';
	echo '<B><FONT FACE="Verdana" SIZE="2">Garderie:</FONT></B><BR>';
	echo '<SELECT name="Garderie" '.$BloquerAcces.' >';
	$liste_serviteurs = fCOM_Get_liste_serviteurs(90, False); // 90-Garderie
	foreach ($liste_serviteurs as $Serviteur){
		list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
		$SelectionDefault = '';
		if ($_Garderie == $Serviteur_prenom." ".$Serviteur_nom) {
			$SelectionDefault = ' selected="selected"';
		}
		echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
	}
	echo '</SELECT></TD>';

	echo '</TR>';
	
	if ( $Gerer_Equipe_Technique_Messe == True ) {
		echo '<TR>';
		
		// Sono
		$BloquerAcces="disabled='disabled'";
		if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
		if (fCOM_Get_Autorization( 19 ) >= 30) { $BloquerAcces="";} // 19-Sono
		echo '<TD bgcolor="#eeeeee" valign="middle" height="60">';
		echo '<B><FONT FACE="Verdana" SIZE="2">Sono:</FONT></B><BR>';
		echo '<SELECT name="Sono" '.$BloquerAcces.' >';
		$liste_serviteurs = fCOM_Get_liste_serviteurs(19, False); // 19-Sono
		foreach ($liste_serviteurs as $Serviteur){
			list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
			$SelectionDefault = '';
			if ($_Sono == $Serviteur_prenom." ".$Serviteur_nom) {
				$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
		}
		echo '</SELECT></TD>';
	
		// Projection
		$BloquerAcces="disabled='disabled'";
		if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
		if (fCOM_Get_Autorization( 47 ) >= 30) { $BloquerAcces="";} // 47-Projection
		echo '<TD bgcolor="#eeeeee" valign="middle">';
		echo '<B><FONT FACE="Verdana" SIZE="2">Projection:</FONT></B><BR>';
		echo '<SELECT name="Projection" '.$BloquerAcces.' >';
		$liste_serviteurs = fCOM_Get_liste_serviteurs(47, False); // 47-Projection
		foreach ($liste_serviteurs as $Serviteur){
			list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
			$SelectionDefault = '';
			if ($_Projection == $Serviteur_prenom." ".$Serviteur_nom) {
				$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
		}
		echo '</SELECT></TD>';

		echo '</TR><TR>';
		
		// Broadcast
		$BloquerAcces="disabled='disabled'";
		if (fCOM_Get_Autorization( $Activite_id ) >= 30) { $BloquerAcces="";}
		if (fCOM_Get_Autorization( 20 ) >= 30) { $BloquerAcces="";} // 20-Vidéo Broadcast
		echo '<TD bgcolor="#eeeeee" valign="middle" height="60">';
		echo '<B><FONT FACE="Verdana" SIZE="2">Broadcast:</FONT></B><BR>';
		echo '<SELECT name="Broadcast" '.$BloquerAcces.' >';
		$liste_serviteurs = fCOM_Get_liste_serviteurs(20, False); // 20-Vidéo Broadcast
		foreach ($liste_serviteurs as $Serviteur){
			list($Serviteur_id, $Serviteur_lieu, $Serviteur_prenom, $Serviteur_nom) = $Serviteur;
			$SelectionDefault = '';
			if ($_Broadcast == $Serviteur_prenom." ".$Serviteur_nom) {
				$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$Serviteur_id.'"'.$SelectionDefault.'>'.$Serviteur_lieu." - ".$Serviteur_prenom." ".$Serviteur_nom.'</option>';
		}
		echo '</SELECT></TD>';
		echo '<TD bgcolor="#eeeeee"> </TD>';

		echo '</TR>';
	}
	
	echo '<TR><TD> </TD></TR>';

	
	echo '<TR><TD COLSPAN="2">';
	echo '<INPUT type="submit" name="Evenement_sauvegarder" value="Enregistrer">';
	echo '<INPUT type="reset" name="Reset" value="Reset">';
	echo '<INPUT type="hidden" name="id" value='.$_GET['id'].'>';
	if ($_GET['id'] > 0 AND fCOM_Get_Autorization( $Activite_id ) >= 30) {
		echo '<INPUT type="submit" name="Evenement_delete" value="Supprimer">';
	}
	echo '</TD></TR></TABLE></P></FORM>';
	fCOM_address_bottom();
	exit();
}




//=========================================================
//=========================================================
//=========================================================
// Programmation des célébrations récurrentes
//=========================================================
//=========================================================
//=========================================================

if ( isset( $_GET['action'] ) AND $_GET['action']=="Prog_Recurrente_Celebration") {
//if ($action == "rencontres") {
	if ( ! isset($_GET['id'])) {$_GET['id']=0;};
	
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, "Evenements:".$_GET['action']." ... en cours");
	
	address_top();
	
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF']."?action=Prog_Recurrente_Celebration";

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Configuration des célébration récurrentes</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>';
	if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
			echo '<CENTER>';
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=Prog_Recurrente_Celebration&id=0><img src="images/edit.gif" border=0 alt="Mofifier Record"></A>  ';
			echo '</CENTER>';
	} else { echo ' ';}
	echo '</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Date Début</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Date Fin</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Jour</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Heure</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Lieu</FONT></TH>';
	$requete = 'SELECT T0.`id`, T0.`DateDeb`, T0.`DateFin`, T0.`Jour`, T0.`Heure`, T1.`Lieu`
				FROM Celebrations_rec T0
				LEFT JOIN Lieux T1 ON T1.`id`=T0.`Lieu_id`
				ORDER BY Jour';
		
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		$Foregroundcolor = "#000000";
		if (sqlDateToOut($row['DateFin']) < sqlDateToOut(date("Y/m/d"))) {
			$Foregroundcolor = "#919191";
		}
		pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave - Date =".sqlDateToOut($row['DateFin'])." à comparer avec=".sqlDateToOut(date("Y/m/d")));
		
		echo '<TR>';
		if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
			echo '<TD bgcolor='.$trcolor.'><CENTER>';
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=Prog_Recurrente_Celebration&id='.$row['id'].'><img src="images/edit.gif" border=0 alt="Mofifier Record"></A>  ';
			echo '</CENTER></TD>';
		} else {
			echo '<TD></TD>';
		}
		echo '<TD width=120 align="middle" bgcolor='.$trcolor.'><font face=verdana color='.$Foregroundcolor.' size=2>';
		echo strftime("%d/%m/%y", sqlDateToOut($row['DateDeb']));
		echo '</TD>';
		
		echo '<TD width=120 align="middle" bgcolor='.$trcolor.'><font face=verdana color='.$Foregroundcolor.' size=2>';
		echo strftime("%d/%m/%y", sqlDateToOut($row['DateFin']));
		echo '</TD>';
		
		echo '<TD width=120 align="middle" bgcolor='.$trcolor.'><font face=verdana color='.$Foregroundcolor.' size=2>';
		switch ($row['Jour']) {
		case 7:
			echo 'Dimanche';
			break;
		case 1:
			echo 'Lundi';
			break;
		case 2:
			echo 'Mardi';
			break;
		case 3:
			echo 'Mercredi';
			break;
		case 4:
			echo 'Jeudi';
			break;
		case 5:
			echo 'Vendredi';
			break;
		case 6:
			echo 'Samedi';
			break;
		}
		echo '</TD>';
		
		echo '<TD width=120 align="middle" bgcolor='.$trcolor.'><font face=verdana color='.$Foregroundcolor.' size=2>';
		//echo strftime("%H:%M", sqlDateToOut($row['Heure']));
		echo substr($row['Heure'], 0, 5);
		echo '</TD>';

		echo '<TD width=120 align="middle" bgcolor='.$trcolor.'><font face=verdana color='.$Foregroundcolor.' size=2>';
		echo $row['Lieu'];
		echo '</TD>';

		echo '</TR>';
	}

	
	// formulaire de saisie
	//---------------------
	if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
		echo '<FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<P><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7">';
		if ( $_GET['id'] > 0 ) {
			echo '<TD COLSPAN="5"><FONT FACE="Verdana" SIZE="2"><BR><B>Modification d\'une célébration récurrente</B></TD>';
		} else {
			echo '<TD COLSPAN="5"><FONT FACE="Verdana" SIZE="2"><BR><B>Saisie d\'une nouvelle célébration récurrente</B></TD>';
		}
		echo '</TR>';
		echo '<TR>';
		echo '<TD width="100" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Date début: </U></FONT></B></TD>';
		echo '<TD width="120" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Date fin: </U></FONT></B></TD>';
		echo '<TD width="120" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Jour: </U></FONT></B></TD>';
		echo '<TD width="120" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Heure: </U></FONT></B></TD>';
		echo '<TD bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Lieu :</U></FONT></B></TD>';
		echo '</TR>';
		


		if ( $_GET['id'] > 0 )
		{
			$requete = 'SELECT * FROM Celebrations_rec where id='.$_GET['id'].'';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result))
			{
				$DateDeb=strftime("%d/%m/%y", sqlDateToOut($row['DateDeb']));
				$DateFin=strftime("%d/%m/%y", sqlDateToOut($row['DateFin']));
				$Jour_id=$row['Jour'];
				$Heure=$row['Heure'];
				$Lieu_id=$row['Lieu_id'];
			}
		} else {
			$DateDeb = "";
			$DateFin = "";
			$Jour_id = 0;
			$Heure = "";
			$Lieu_id = 0;
		}
		
		echo '<TR>';
		echo '<TD bgcolor="#eeeeee">';
		echo '<input type=text id="DateDeb" name="DateDeb" size="8" maxlength="10" value="'.$DateDeb.'">';
		?>	
		<a href="javascript:popupwnd('calendrier.php?idcible=DateDeb&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
		<?php
		echo '</TD>';
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<input type=text id="DateFin" name="DateFin" size="8" maxlength="10" value="'.$DateFin.'">';
		?>	
		<a href="javascript:popupwnd('calendrier.php?idcible=DateFin&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
		<?php
		echo '</TD>';
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<B><FONT FACE="Verdana" SIZE="2"></FONT></B>';
		$Liste_jour = array(" ", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
		echo '<SELECT name="jour">';
		for ($i=0; $i<=7; $i++) {
			if (sprintf("%02d", $i) == sprintf("%02d", $Jour_id)) {
				echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.$Liste_jour[$i].'</option>';
			} else {
				echo '<option value="'.sprintf("%02d", $i).'">'.$Liste_jour[$i].'</option>';
			}
		}
		echo '</SELECT></TD>';
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<B><FONT FACE="Verdana" SIZE="2"></FONT></B>';
		echo '<SELECT name="heure">';
		for ($i=0; $i<=23; $i++) {
			if (sprintf("%02d", $i) == sprintf("%02d", $Heure)) {
				echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
			} else {
				echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
			}
		}
		echo '</SELECT><FONT FACE="Verdana" SIZE="2">h</FONT>';
		echo '<SELECT name="minute">';
		for ($i=0; $i<=45; $i=$i+15) {
			if (sprintf("%02d", $i) == sprintf("%02d", substr($Heure,3,2))) {
				echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
			} else {
				echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
			}
		}
		echo '</SELECT>';
		echo '</TD>';
		
		pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave - Lieux_id =".$Lieu_id." id=".$_GET['id']);
		$BloquerAcces="";
		echo '<TD bgcolor="#eeeeee">';
		echo '<SELECT name="Lieu" '.$BloquerAcces.' >';
		$Liste_Lieu_Celebration = pCOM_Get_liste_lieu_celebration(1);
		foreach ($Liste_Lieu_Celebration as $Lieu_Celebration){
			list($ListLieu_id, $Lieu_name) = $Lieu_Celebration;
			if ( $_GET['id'] > 0 ) {
				if ($Lieu_id == $ListLieu_id){
					pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave - Lieux_id =".$Lieu_name);
					echo '<option value="'.$ListLieu_id.'" selected="selected">'.$Lieu_name.'</option>';
				} else {
					echo '<option value="'.$ListLieu_id.'">'.$Lieu_name.'</option>';
				}
			} else {
				echo '<option value="'.$ListLieu_id.'">'.$Lieu_name.'</option>';
			}
		}
		echo '</SELECT></TD>';
		
		echo '</TR>';
		
		echo '<TR><TD COLSPAN="2">';
		echo '<INPUT type="submit" name="Prog_Recurrente_Celebration_sauvegarder" value="Enregistrer">';
		echo '<INPUT type="reset" name="Reset" value="Reset">';
		if ( $_GET['id'] > 0 ) {
			echo '<INPUT type="submit" name="Prog_Recurrente_Celebration_supprimer" value="Supprimer">';
		}
		echo '<INPUT type="hidden" name="id" value='.$_GET['id'].'>';
		echo '<BR></TD></TR>';
		echo '</TABLE></P></FORM>';

	} 
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();
}

//--------------------------------------------------------------------------------------
//delete one rencontre by id
//--------------------------------------------------------------------------------------

if ( isset( $_POST['Prog_Recurrente_Celebration_supprimer'] ) AND 
			$_POST['Prog_Recurrente_Celebration_supprimer']=="Supprimer") {
//if ( $rencontre_supprimer ) {
	Global $eCOM_db;
	$debug = true;


	address_top();

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Database Rencontre: Suppression d\'une célébration récurrente</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<FONT FACE="verdana" color="#313131" size="2">Etes-vous certain de vouloir supprimer cette célébration récurrente ?</FONT><BR><BR>';

	$requete = 'SELECT T0.`id`, T0.`DateDeb`, T0.`DateFin`, T0.`Jour`, T0.`Heure`, T1.`Lieu`
				FROM Celebrations_rec T0
				LEFT JOIN Lieux T1 ON T1.`id`=T0.`Lieu_id`
				WHERE T0.`id`='.$_POST['id'].' '; 
	$result = mysqli_query($eCOM_db, $requete);	
	while($row = mysqli_fetch_assoc($result))
	{
		echo '<FONT FACE="verdana" color="#313131" size="2">';
		echo 'Du '.strftime("%d/%m/%y", sqlDateToOut($row['DateDeb']));
		echo ' au '.strftime("%d/%m/%y", sqlDateToOut($row['DateFin']));
		echo '  -> tous les ';
		switch ($row['Jour']) {
		case 7:
			echo 'Dimanche';
			break;
		case 1:
			echo 'Lundi';
			break;
		case 2:
			echo 'Mardi';
			break;
		case 3:
			echo 'Mercredi';
			break;
		case 4:
			echo 'Jeudi';
			break;
		case 5:
			echo 'Vendredi';
			break;
		case 6:
			echo 'Samedi';
			break;
		}
		echo ' à '.substr($row['Heure'], 0, 5).' à '.$row['Lieu'];
		echo '<BR>&nbsp</FONT>';
	}
	pCOM_DebugAdd($debug, "Evenement:ProgRecCel_del - requete =".$requete);
	echo '</TD></TR>';
	echo '<TR><TD><P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
	echo '<INPUT type="submit" name="Prog_Recurrente_Celebration_supprimer_Conf" value="Oui">';
	echo '<INPUT type="submit" name="Prog_Recurrente_Celebration_supprimer_Conf" value="Non">';
	echo '<INPUT type="hidden" name="id" value='.$_POST['id'].'>';
	echo '</FORM>';
	echo '</TD></TR></TABLE>';
	
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();	
}


if ( isset( $_POST['Prog_Recurrente_Celebration_supprimer_Conf'] ) AND 
		  ( $_POST['Prog_Recurrente_Celebration_supprimer_Conf']=="Oui" OR
			$_POST['Prog_Recurrente_Celebration_supprimer_Conf']=="Non" )) {
//if ( $rencontre_delete ) {
	Global $eCOM_db;
	$debug = False;
	$Delay = "0";
	if ( $_POST['Prog_Recurrente_Celebration_supprimer_Conf'] == "Oui" )
	{
		if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30)
		{
			$requete = 'DELETE FROM Celebrations_rec WHERE id='.$_POST['id'].' '; 
			pCOM_DebugAdd($debug, "Evenement:ProgRecCel_delConfirm - requete =".$requete);
			$result = mysqli_query($eCOM_db, $requete); 
			if (!$result) {
				echo '<B><CENTER><FONT face="verdana" size="2" color=red>';
				echo 'Impossible de retirer ces célébrations : '.mysqli_error($eCOM_db);
				echo '</FONT></CENTER></B>';
			} else {
				echo '<B><CENTER><FONT face="verdana" size="2" color=green>Célébrations supprimées avec succès</FONT></CENTER></B>';
			}
		} else {
			echo '<B><CENTER><FONT face="verdana" size="2" color=red>';
			echo 'Impossible de retirer ces célébrations : pas de droit accordé</FONT></CENTER></B>';
		}
		$Delay = "2";
	}
	echo '<META http-equiv="refresh" content="'.$Delay.'; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_POST['Prog_Recurrente_Celebration_sauvegarder'] ) AND 
			$_POST['Prog_Recurrente_Celebration_sauvegarder']=="Enregistrer") {

	Global $eCOM_db;
	if (fCOM_Get_Autorization($_SESSION["Activite_id"]) <= 20)
	{
		echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
		mysqli_close($eCOM_db);
		exit;
	}
	
	$debug = True;
	pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave DateDeb=" .$_POST['DateDeb']);
	pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave DateFin=" .$_POST['DateFin']);
	pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave Jour=" .$_POST['jour']);
	pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave Heure=" .$_POST['heure'].":".$_POST['minute']);
	pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave Lieu=" .$_POST['Lieu']);
	
	$id=$_POST['id'];
	
	// vérifierer :DateDeb, DateFin, jour, heure, minute, Lieu
	$DateDeb=fCOM_getSqlDate($_POST['DateDeb'], 0, 0, 0);
	if ( $DateDeb == "" ) {
		fCOM_GetWindowBack(2);
		exit;
	}
	$DateFin=fCOM_getSqlDate($_POST['DateFin'], 0, 0, 0);
	if ( $DateFin == "" ) {
		fCOM_GetWindowBack(2);
		exit;
	}
	
	$Heure=$_POST['heure'].':'.$_POST['minute'].':00';
	if ( $id > 0 ) {
		// sauvegarder :DateDeb, DateFin, jour, heure, minute, Lieu
		mysqli_query($eCOM_db, 'UPDATE Celebrations_rec SET DateDeb="'.substr($DateDeb,0,10).'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Celebrations_rec SET DateFin="'.substr($DateFin,0,10).'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Celebrations_rec SET Jour='.$_POST['jour'].' WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Celebrations_rec SET Heure="'.$Heure.'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Celebrations_rec SET Lieu_id='.$_POST['Lieu'].' WHERE id='.$id.'') or die (mysqli_error($eCOM_db));		
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Modifications enregistrée avec succès</FONT></CENTER></B>';
		
	} else {
		$requete = 'INSERT INTO Celebrations_rec (id, DateDeb, DateFin, Jour, Heure, Lieu_id) VALUES (0, "'.substr($DateDeb,0,10).'", "'.substr($DateFin,0,10).'", '.$_POST['jour'].', "'.$Heure.'", '.$_POST['Lieu'].')';
		pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave - requete=".$requete);
		mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Célébration récurrente ajoutée avec succès</FONT></CENTER></B>';
		}
		
	echo '<META http-equiv="refresh" content="1; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}



//----------------------------------------------------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
// Listing general de la session
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
	address_top();
	Global $eCOM_db;
	setlocale(LC_ALL, 'fr_FR','fra');
	
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Messes et célébrations</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	$Liste_Type_Bapteme = array("Baptême", "Baptême en aspersion", "Baptême en immersion");
	
	if (!isset($_GET['criteria'])) {
		if (fCOM_Get_Autorization( 16 ) >= 20) {
			$criteria="Lieu";
		} else {
			$criteria="Date";
		}
	} else {
		$criteria=$_GET['criteria'];
	}
	
	if (!isset($_GET['order'])) {
		$order="DESC";
	} else {
		$order=$_GET['order'];
	}
	
	if ($criteria=="Lieu" ) {
		$SecondCriteria = ", Date ASC, Ordre ASC";
	} elseif ($criteria=="Celebrant") {
		$SecondCriteria = ", Date ASC, Lieu ASC, Ordre ASC";
	} else {
		$SecondCriteria = ", Lieu ASC, Ordre ASC";
	}
	
	if($order=="ASC"){
		$order="DESC";
	}else{
		$order="ASC";
	}
	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2> </font></TH>';
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"".$_SERVER['SCRIPT_NAME']."?criteria=Date&order=".$order."\">Date</A></font>";
    echo '<input type="checkbox" onclick="FiltrerLine()"> <label for="Filter_old_fich"><FONT SIZE="2"></b></label></TH>';
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"".$_SERVER['SCRIPT_NAME']."?criteria=Lieu&order=".$order."\">Lieu</A></font></TH>";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Intitulé</font></TH>';
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"".$_SERVER['SCRIPT_NAME']."?criteria=Celebrant&order=".$order."\">Célébrant</A></font></TH>";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Sacristin</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Animateur</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Musicien</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Eveil Foi</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Garderie</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Sono</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Projection</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Vidéo</font></TH>';

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	
	$debug = False;
	pCOM_DebugInit($debug);
	
	//----------------------------------------------------
	// Réinitialiser la liste des prochaines célébrations
	// Date limite = today + 60 jours
	//---------------------------------------------------
	$DateLimite=fCOM_sqlDateToOut(date("Y-m-d H:i:s")) + (24*3600*360);
	$CurrentDate=date("Y-m-d");
	pCOM_DebugAdd($debug,"Evenements:Liste DateLimite=".$DateLimite);
	pCOM_DebugAdd($debug,"Evenements:Liste CurrentDate=".$CurrentDate);
	
	$requete="Truncate Celebrations_futur";
	$result = mysqli_query( $eCOM_db, $requete);
	$requete='SELECT * FROM Celebrations_rec WHERE "'.$CurrentDate.'" <= DateFin';
	pCOM_DebugAdd($debug,"Evenements:Liste Requete=".$requete);

	$result = mysqli_query( $eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)) {
		$DateRec = fCOM_sqlDateToOut(date("Y-m-d").' '.$row['Heure']);
		while ( date('N', $DateRec) != $row['Jour'] ) {
			$DateRec = $DateRec + (24*3600);
		}
		pCOM_DebugAdd($debug,"Evenements:Liste DateDeb=".fCOM_sqlDateToOut($row['DateDeb']));
		pCOM_DebugAdd($debug,"Evenements:Liste DateFin=".fCOM_sqlDateToOut($row['DateFin']));
		pCOM_DebugAdd($debug,"Evenements:Liste DateRec=".$DateRec);
		While ( $DateRec <= fCOM_sqlDateToOut($row['DateFin']) AND
				$DateRec <= $DateLimite ) {
			if ($DateRec >= fCOM_sqlDateToOut($row['DateDeb']) AND 
				$DateRec <= fCOM_sqlDateToOut($row['DateFin']) AND
				$DateRec <= $DateLimite ) {
				$requete = 'INSERT INTO Celebrations_futur (Date, Lieu_id) VALUES ("'.date("Y-m-d H:i:s",$DateRec).'", '.$row['Lieu_id'].')';
				pCOM_DebugAdd($debug,"Evenements:Liste Requete02=".$requete);
			//pCOM_DebugAdd($debug, "Evenement:ProgRecCelSave - requete=".$requete);
				mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
			}
			$DateRec = $DateRec + (24*3600*7); // 7 jours
		}
	}
	
	if ($Gerer_Equipe_Technique_Messe == False ) {
		// lecture du fichier XML du Dimanche à Sophia
		$Fichier_xml="http://serveur.ndsagesse.com/serveur.ndsagesse.com/nds-planning.xml";
		$xml_SophiaAntipolis = simplexml_load_file($Fichier_xml) or pCOM_DebugAdd($debug, "Evenements:XML - Erreur de chargement fichier Sophia_Antipolis");
	
		// lecture du fichier XML du Samedi à Sophia
		$Fichier_xml="http://serveur.ndsagesse.com/serveur.ndsagesse.com/nds-planning-samedi.xml";
		$xml_SophiaAntipolis_samedi = simplexml_load_file($Fichier_xml) or pCOM_DebugAdd($debug, "Evenements:XML - Erreur de chargement fichier Sophia_Antipolis samedi");
	}
	
	$compteur = 0;
	
	$MemoId = 0;
	$MemoDate = "";
	$MemoIntitule = "";
	$MemoLieu = "";
	$MemoCelebtant = "";
	$MemoSacristin= "";
	$MemoAnimateur = "";
	$MemoMusicien = "";
	$MemoEveilFoi = "";
	$MemoGarderie = "";
	$MemoOrdre = "";
	$MemoSono = "";
	$MemoProjection = "";
	$MemoBroadcast = "";
	$MemoActivité = "";
	$MemoParoissien = "";
	
	$requete = '(SELECT T0.`id` as id, T0.`Date` as Date, T0.`Intitule` as Intitule, T1.`Lieu` as Lieu, CONCAT(T3.`Prenom`, " ", T3.`Nom`) as Celebrant, CONCAT(T5.`Prenom`, " ", T5.`Nom`) as Sacristin, CONCAT(T7.`Prenom`, " ", T7.`Nom`) as Animateur, CONCAT(T9.`Prenom`, " ", T9.`Nom`) as Musicien, CONCAT(T11.`Prenom`, " ", T11.`Nom`) as EveilFoi, CONCAT(T13.`Prenom`, " ", T13.`Nom`) as Garderie, "Messe" AS Activité, "-" AS Paroissien, "OK" AS Status, 0 as Ordre, CONCAT(T15.`Prenom`, " ", T15.`Nom`) as Sono, CONCAT(T17.`Prenom`, " ", T17.`Nom`) as Projection, CONCAT(T19.`Prenom`, " ", T19.`Nom`) as Broadcast   
		FROM Rencontres T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieux_id` = T1.`id`
		LEFT JOIN `QuiQuoi` T2 ON T0.`id` = T2.`Engagement_id` AND T2.`QuoiQuoi_id`=5  AND T2.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T3 ON T2.`Individu_id` = T3.`id`
		LEFT JOIN `QuiQuoi` T4 ON T0.`id` = T4.`Engagement_id` AND T4.`QuoiQuoi_id`=12 AND T4.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T5 ON T4.`Individu_id` = T5.`id`
		LEFT JOIN `QuiQuoi` T6 ON T0.`id` = T6.`Engagement_id` AND T6.`QuoiQuoi_id`=13 AND T6.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T7 ON T6.`Individu_id` = T7.`id`
		LEFT JOIN `QuiQuoi` T8 ON T0.`id` = T8.`Engagement_id` AND T8.`QuoiQuoi_id`=21 AND T8.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T9 ON T8.`Individu_id` = T9.`id`
		LEFT JOIN `QuiQuoi` T10 ON T0.`id` = T10.`Engagement_id` AND T10.`QuoiQuoi_id`=16 AND T10.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T11 ON T10.`Individu_id` = T11.`id`
		LEFT JOIN `QuiQuoi` T12 ON T0.`id` = T12.`Engagement_id` AND T12.`QuoiQuoi_id`=23 AND T12.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T13 ON T12.`Individu_id` = T13.`id`
		LEFT JOIN `QuiQuoi` T14 ON T0.`id` = T14.`Engagement_id` AND T14.`QuoiQuoi_id`=24 AND T14.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T15 ON T14.`Individu_id` = T15.`id`
		LEFT JOIN `QuiQuoi` T16 ON T0.`id` = T16.`Engagement_id` AND T16.`QuoiQuoi_id`=25 AND T16.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T17 ON T16.`Individu_id` = T17.`id`
		LEFT JOIN `QuiQuoi` T18 ON T0.`id` = T18.`Engagement_id` AND T18.`QuoiQuoi_id`=26 AND T18.`Activite_id`= T0.`Activite_id`
		LEFT JOIN `Individu` T19 ON T18.`Individu_id` = T19.`id`
		WHERE T0.`Activite_id`=86 AND T0.`Date`> (NOW() - INTERVAL 3 MONTH))
				
		UNION ALL
				
		(SELECT T0.`id` as id, T0.`Date` as Date, "Baptême" as Intitule, T1.`Lieu` as Lieu, Concat(T5.`Prenom`, " ",T5.`Nom`) AS Celebrant, "" as Sacristin, "" as Animateur, "" as Musicien, "" as EveilFoi, "" as Garderie, T0.`Aspersion_Immersion` AS Activité,  Concat(T3.`Prenom`, " ",T3.`Nom`) AS Paroissien, "OK" AS Status, 1 as Ordre, "" as Sono, "" as Projection, "" as Broadcast 
		FROM `Bapteme` as T0 
		LEFT JOIN `Lieux` as T1 ON T0.`Lieu_id`=T1.`id` 
		LEFT JOIN `Individu` as T3 ON T3.`id`=T0.`Baptise_id` 
		LEFT JOIN `Individu` as T5 ON T5.`id`=T0.`Celebrant_id` 
		WHERE T1.`IsParoisse` = 1 AND T0.`Date`>=(NOW() - INTERVAL 3 MONTH))
			
		UNION ALL
				
		(SELECT T4.`id` as id, T4.`Date_mariage` as Date, "Mariage" as Intitule, T4.`Lieu_mariage` as Lieu, IFNULL(Concat(T8.`Prenom`, " ", T8.`Nom`), IF(T4.`Celebrant_id`=0, "En Attente", IF(T4.`Celebrant_id`=-1 AND T4.`Celebrant`="", "Célébrant Extérieur", T4.`Celebrant`))) AS Celebrant, "" as Sacristin, "" as Animateur, "" as Musicien, "" as EveilFoi, "" as Garderie, "Mariage" AS Activité, 
		CONCAT((SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
			FROM Individu T6
			LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
			WHERE T6.`Sex` = "F" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`), " et ",
			(SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
		FROM Individu T6
		LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
		WHERE T6.`Sex` = "M" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`)) As Paroissien, T4.`Status` AS Status, 2 as Ordre, "" as Sono, "" as Projection, "" as Broadcast
		FROM `Fiancés` as T4
		LEFT JOIN `Individu` AS T8 ON T8.`id`=T4.`Celebrant_id`
		LEFT JOIN `Lieux` as T9 ON T9.`Lieu`=T4.`Lieu_mariage` 
		WHERE T9.`IsParoisse` = 1 AND T4.`Date_mariage` >= (NOW() - INTERVAL 3 MONTH))
				
		UNION ALL
				
		(SELECT "0" as id, T0.`date` as Date, "" as Intitule, T1.`Lieu` as Lieu, "" AS Celebrant, "" as Sacristin, "" as Animateur, "" as Musicien, "" as EveilFoi, "" as Garderie, "Messe Rec" AS Activité,  "" AS Paroissien, "OK" AS Status, -1 as Ordre, "" as Sono, "" as Projection, "" as Broadcast 
		FROM `Celebrations_futur` as T0 
		LEFT JOIN `Lieux` as T1 ON T0.`Lieu_id`=T1.`id` 
		WHERE T0.`date`>=now())
		ORDER BY '.$criteria.' '.$order.' '.$SecondCriteria; //Date, Lieu, Ordre';

	$MemoDateOrg="";
	$result = mysqli_query( $eCOM_db, $requete);
	$number = mysqli_num_rows($result);
	$PremLine = 1;
	$i = 1;
	while($row = mysqli_fetch_assoc($result) OR ($i <= $number+1 AND $number > 0)){
		
		pCOM_DebugAdd($debug, "Evenements:liste - id=".$row['id']);
		pCOM_DebugAdd($debug, "Evenements:liste - MemoDateOrg=".$MemoDateOrg." New date=".$row['Date']);
		pCOM_DebugAdd($debug, "Evenements:liste - MemoLieu=".$MemoLieu." New lieu=".$row['Lieu']);
		pCOM_DebugAdd($debug, "Evenements:liste - PremLine=".$PremLine);
		pCOM_DebugAdd($debug, "Evenements:liste - Status=".$row['Status']);
		pCOM_DebugAdd($debug, "Evenements:liste - i=".$i." number+1=".$number+1);
		
		// --------------------------------------------------------------
		// Print de la ligne si on est pas à la 1ère ligne de la table
		// --------------------------------------------------------------
		if ( ($MemoLieu != $row['Lieu'] OR $MemoDateOrg != $row['Date'] OR $i == $number+1) AND 
			$PremLine == 0 AND $row['Status'] != "Annulé/Reporté") {
			
			$trcolor = usecolor();
			
			pCOM_DebugAdd($debug, "Evenements:liste - cas 1");
			if ($MemoDateOrg < date("Y-m-d H:i:s", Time() - (5*3600))) { // on laisse en visibilité pendant 5h
				$ColorFont="#919191";
				$compteur = $compteur + 1;
				echo '<h6 style="display:none;"></h6><TR id="Filtrer_'.$compteur.'" style="display:none;">';		
			} else {
				$ColorFont="#000000";
				echo '<TR>';
			}

			if (fCOM_Get_Autorization( $Activite_id ) >= 30 || 
				fCOM_Get_Autorization( 51 ) >= 30 || 
				fCOM_Get_Autorization( 16 ) >= 30) {
				echo "<TD bgcolor=".$trcolor."><CENTER>";
				echo "<A HREF=".$_SERVER['PHP_SELF']."?action=edit&id=".$MemoId."&Date=".addslashes(urlencode(serialize($MemoDate)))."&Lieu=".$MemoLieu."&Celebrant=".addslashes(urlencode(serialize($MemoCelebrant)))."&Intitule=".addslashes(urlencode(serialize($MemoIntitule)))."><img src=\"images/edit.gif\" border=0 alt='See Record'></A> ";
				echo "</CENTER></TD>";
			} else {
				echo "<TD></TD>";
			}

			echo '<TD bgcolor='.$trcolor.' width=70><font face=verdana color='.$ColorFont.' size=2>'.ustr_replace(" ", "<BR>",$MemoDisplayDate).'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoLieu.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoIntitule.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoCelebrant.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoSacristin.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoAnimateur.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoMusicien.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoEveilFoi.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana color='.$ColorFont.' size=2>'.$MemoGarderie.'</TD>';
						
			if ($Gerer_Equipe_Technique_Messe == False ) {
				// Extraction du fichier XML
				$debug = false;
				$Team_broadcast="";
				$Team_projection="";
				$Team_sono="";
		
				$xml = "";
				$pos = strpos($MemoLieu, "Sophia-Antipolis");
				if ( $pos !== false ) {
					if ( date("w", fCOM_sqlDateToOut($MemoDateOrg)) == 6 ) {
						pCOM_DebugAdd($debug, "Evenements:XML - Samedi");
						$xml = $xml_SophiaAntipolis_samedi;
					} else {
						pCOM_DebugAdd($debug, "Evenements:XML - Dimanche");
						$xml = $xml_SophiaAntipolis;
					}
				}
		
				if ($xml != "") {
					//print_r($xml);
					pCOM_DebugAdd($debug, "Evenements:XML - start");
					foreach ($xml->year->month as $month) {
						if ( $month['year'] == strftime("%Y", sqlDateToOut($MemoDateOrg)) AND sprintf("%02d", $month['id']) == strftime("%m", sqlDateToOut($MemoDateOrg))) {
							pCOM_DebugAdd($debug, "Evenements:XML(1) - month=".sprintf("%02d", $month['id'])." année=".$month['year']);
							pCOM_DebugAdd($debug, "Evenements:ROW(2) - month=".strftime("%m", sqlDateToOut($MemoDateOrg))." année=".strftime("%Y", sqlDateToOut($MemoDateOrg)));
							foreach ($month->d as $day) {
								$numDay = explode("-", $day['num']);
								pCOM_DebugAdd($debug, "Evenements:XML - day=".$numDay[1]);
								if ( sprintf("%02d", $numDay[1]) == strftime("%d", sqlDateToOut($MemoDateOrg)) ) {
									pCOM_DebugAdd($debug, "Evenements:XML - day trouvé=".sprintf("%02d", $numDay[1]));
									foreach ($day->user as $user) {
										pCOM_DebugAdd($debug, "Evenements:XML - User=".$user['team']."/".$user['initial']);
										if ($user['team']== 'b') { // broadcast
											$Team_broadcast = $user['initial'];
										} elseif ($user['team']== 'p') { // projection
											$Team_projection = $user['initial'];
										} elseif ($user['team']== 's') { // sono
											$Team_sono = $user['initial'];
										}
									}
								}
							}
						}
					}
					$debug = false;
					mb_internal_encoding('UTF-8');
					//$Users = $xml->xpath("user");
					foreach ( $xml->users->s->user as $user) {
						pCOM_DebugAdd($debug, "Evenements:XML - User s=".$user['initial']."/".$Team_sono."->".$user['name']);
						if ((string)$user['initial'] == $Team_sono) {
							pCOM_DebugAdd($debug, "Evenements:XML - Username sono trouvé");
							$Team_sono = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $user['name']);
						}
					}
					foreach ( $xml->users->p->user as $user) {
						pCOM_DebugAdd($debug, "Evenements:XML - User p=".$user['initial']."/".$Team_projection."->".$user['name']);
						if ((string)$user['initial'] == $Team_projection) {
							pCOM_DebugAdd($debug, "Evenements:XML - Username projection trouvé");
							$Team_projection = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $user['name']);
						}
					}
					foreach ( $xml->users->b->user as $user) {
						pCOM_DebugAdd($debug, "Evenements:XML - User b=".$user['initial']."/".$Team_broadcast."->".$user['name']);
						if ((string)$user['initial'] == $Team_broadcast) {
							pCOM_DebugAdd($debug, "Evenements:XML - Username broadcast trouvé");
							$Team_broadcast = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $user['name']);
						}
					}
				}
			}
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana color='.$ColorFont.' size=2>'.$Team_sono.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana color='.$ColorFont.' size=2>'.$Team_projection.'</TD>';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana color='.$ColorFont.' size=2>'.$Team_broadcast.'</TD>';
			
			$MemoDate = strftime("%d/%m/%Y  %H:%M", sqlDateToOut($row['Date']));
			$MemoDisplayDate = strftime("%d/%m/%y  %a %H:%M", sqlDateToOut($row['Date']));
			$MemoDateOrg = $row['Date'];
			$MemoId = 0;
			$MemoLieu = $row['Lieu'];
			$MemoActivité ="";
			if ($row['Ordre']== -1 OR $row['Ordre']==0) { // Messe ou Messe Rec
				$MemoActivité = $row['Intitule'];
			} elseif ($row['Ordre']== 2) { // "Mariage"
				$MemoActivité = $row['Activité'];
			} elseif ($row['Ordre']== 1) { // "Baptême" 
				$MemoActivité = $Liste_Type_Bapteme[$row['Activité']];
			}
			$MemoIntitule = Build_Intitule("", $row['Ordre'], $MemoActivité, $row['id'], $row['Paroissien'], $row['Celebrant']);
			$MemoCelebrant = $row['Celebrant'];
			$MemoSacristin = $row['Sacristin'];
			$MemoAnimateur = $row['Animateur'];
			$MemoMusicien = $row['Musicien'];
			$MemoEveilFoi = $row['EveilFoi'];
			$MemoGarderie = $row['Garderie'];
			$MemoOrdre = $row['Ordre']; // 0-Messe, 1-Baptême, 2-Mariage
			$Team_sono = $row['Sono'];
			$Team_projection = $row['Projection'];
			$Team_broadcast = $row['Broadcast'];
			if ($MemoOrdre == 0){ // != Baptême et Mariage et Recurrence
				$MemoId = $row['id'];
			}		
			echo '</TR>';
			pCOM_DebugAdd($debug, "Evenements:liste - MemoSacristin=".$MemoSacristin." New Sacristin=".$row['Sacristin']);
			
		} elseif ( $PremLine == 1 AND $row['Status'] != "Annulé/Reporté") {
			
		// --------------------------------------------------------------
		// Il s'agit de la première ligne de la table
		// --------------------------------------------------------------
			pCOM_DebugAdd($debug, "Evenements:liste - cas 2");
			//if ($MemoActivité == "") {
				if ($row['Ordre']== -1 OR $row['Ordre'] == 0) { // Messe ou Messe Rec
					if ($MemoActivité == $MemoIntitule AND strlen($MemoIntitule) > 0 ) {
						$MemoActivité = "";
					} else {
						$MemoActivité = $row['Intitule'];
					}
				} elseif ($row['Ordre']== 2) { // "Mariage"
					$MemoActivité = $row['Activité'];
				} elseif ($row['Ordre']== 1) { // "Baptême" 
					$MemoActivité = $Liste_Type_Bapteme[$row['Activité']];
				}
			//}
			$MemoIntitule = Build_Intitule("", $row['Ordre'], $MemoActivité, $row['id'], $row['Paroissien'], $row['Celebrant']);
			$PremLine = 0;
			$MemoDate = strftime("%d/%m/%Y  %H:%M", sqlDateToOut($row['Date']));
			$MemoDisplayDate = strftime("%d/%m/%y  %a %H:%M", sqlDateToOut($row['Date']));
			$MemoDateOrg = $row['Date'];
			$MemoLieu = $row['Lieu'];
			$MemoCelebrant = $row['Celebrant'];
			$MemoSacristin = $row['Sacristin'];
			$MemoAnimateur = $row['Animateur'];
			$MemoMusicien = $row['Musicien'];
			$MemoEveilFoi = $row['EveilFoi'];
			$MemoGarderie = $row['Garderie'];
			$MemoOrdre = $row['Ordre']; // 0-Messe, 1-Baptême, 2-Mariage
			$Team_sono = $row['Sono'];
			$Team_projection = $row['Projection'];
			$Team_broadcast = $row['Broadcast'];
			if ($MemoOrdre == 0){ // != Baptême et Mariage et Recurrence
				$MemoId = $row['id'];
			}			
			
		} else {
		// --------------------------------------------------------------
		// Date et Lieu sont équivalents au précédent
		// --------------------------------------------------------------
			
			pCOM_DebugAdd($debug, "Evenements:liste - cas 3");
			if ($row['Ordre']== -1 OR $row['Ordre']==0) { // Messe ou Messe Rec
				if ($MemoActivité == $MemoIntitule AND strlen($MemoIntitule) > 0) {
					$MemoActivité = "";
				} else {
					$MemoActivité = $row['Intitule'];
				}				
			} elseif ($row['Ordre']== 2) { // "Mariage"
				$MemoActivité = $row['Activité'];
			} elseif ($row['Ordre']== 1) { // "Baptême" 
				$MemoActivité = $Liste_Type_Bapteme[$row['Activité']];
			}
			$MemoIntitule = Build_Intitule($MemoIntitule, $row['Ordre'], $MemoActivité, $row['id'], $row['Paroissien'], $row['Celebrant']);
			
			if ($row['Celebrant'] != ""){$MemoCelebrant = $row['Celebrant'];}
			if ($row['Sacristin'] != ""){$MemoSacristin = $row['Sacristin'];}
			if ($row['EveilFoi'] != ""){$MemoEveilFoi = $row['EveilFoi'];}
			if ($row['Garderie'] != ""){$MemoGarderie = $row['Garderie'];}
			if ($row['Sono'] != ""){$Team_sono = $row['Sono'];}
			if ($row['Projection'] != ""){$Team_projection = $row['Projection'];}
			if ($row['Broadcast'] != ""){$Team_broadcast = $row['Broadcast'];}
			
			if ($row['Animateur'] != "" AND $MemoAnimateur != "" AND strpos($MemoAnimateur, $row['Animateur'])===False) {
				$MemoAnimateur = $MemoAnimateur.' et<BR>'.$row['Animateur'];
			} elseif ($MemoAnimateur == "") {
				$MemoAnimateur = $row['Animateur'];
			}
			if ($row['Musicien'] != "" AND $MemoMusicien != "" AND strpos($MemoMusicien, $row['Musicien'])===False) {
				$MemoMusicien = $MemoMusicien.' et<BR>'.$row['Musicien'];
			} elseif ($MemoMusicien == "") {
				$MemoMusicien = $row['Musicien'];
			}
			$MemoOrdre = $row['Ordre']; // 0-Messe, 1-Baptême, 2-Mariage
			if ($MemoOrdre == 0){ // != Baptême et Mariage
				//$MemoId = 0;
				$MemoId = $row['id'];
			}
		}
		$i ++;
	}
	echo "</TABLE>";


function Build_Intitule ($pMemoIntitule, $pType, $pIntitule, $pId, $pParoissien, $pCelebrant) {
	Global $Activite_id;
	$Build_Intitule = $pMemoIntitule;
	if (strlen($Build_Intitule) > 0 AND strlen($pIntitule) > 0 AND $Build_Intitule!=$pIntitule) { $Build_Intitule = $Build_Intitule."<BR>"; }
	if ($pType == 0  AND $Build_Intitule!=$pIntitule) {
		$Build_Intitule = $Build_Intitule.$pIntitule;
	} elseif ($pType==1 OR $pType==2) { // Cas du Mariage et Baptême
		if ($pCelebrant != "En Attente") {
			$Build_Intitule = $Build_Intitule.$pCelebrant." célèbre le ".$pIntitule." de ";
		} else {
			$Build_Intitule = $Build_Intitule.$pIntitule." de ";
		}
	}
	if (fCOM_Get_Autorization( $Activite_id ) >= 30 || 
		fCOM_Get_Autorization( 51 ) >= 30 || 
		fCOM_Get_Autorization( 16 ) >= 30) {
		if ($pType==2) { // Mariage
			if (file_exists("Photos/".$pId.".jpg")) { 
				$Build_Intitule = $Build_Intitule.'<A HREF=/Mariage.php?action=edit&id='.$pId.' class="tooltip">'.$pParoissien.'.';
				$Build_Intitule = $Build_Intitule.'<EM><SPAN></SPAN>';
				$Build_Intitule = $Build_Intitule."<img src='Photos/".$pId.".jpg' height='100' border='1' alt='couple_".$pId."'>";
				$Build_Intitule = $Build_Intitule.'</EM></A>';
			} else {
				$Build_Intitule = $Build_Intitule.'<A HREF="/Mariage.php?action=edit&id='.$pId.'">'.$pParoissien.'.</A>';
			}
	
		} elseif ( $pType==1 ) { // Baptême
			$Build_Intitule = $Build_Intitule.'<A HREF="/Bapteme.php?action=edit&id='.$pId.'">'.$pParoissien.'.</A>';
		}
	} else {
		$Build_Intitule = $Build_Intitule.$pParoissien;
	}
	return $Build_Intitule;
}

?>
<script type="text/javascript">
var current=null;
function FiltrerLine() {
	
	var nombreh6 = document.getElementsByTagName('h6').length; //nombre de tr a cacher
	for(var i=1; i<=nombreh6; i++)
	{
		var stockacacher = 'Filtrer_'+i;
		current = document.getElementById(stockacacher);
		
		if(current.style.display=='table-row')	{
			current.style.display='none';
		} else {
			current.style.display='table-row';
		}
	}
}
</script>
<?php

