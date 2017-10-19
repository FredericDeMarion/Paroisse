<?php
session_start();

//==================================================================================================
//    Nom du module : Fraternite.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================

// Initialiser variable si elle n'existe pas
if( ! isset( $action ) ) $action = ""; 
if( ! isset( $edit ) ) $edit = ""; 
if( ! isset( $Selectionner_Individue ) ) $Selectionner_Individue = ""; 
if( ! isset( $SauvegarderParticipation ) ) $SauvegarderParticipation = ""; 
if( ! isset( $retirer_fiche_Participant ) ) $retirer_fiche_Participant = ""; 
if( ! isset( $retirer_fiche_Participant_confirme ) ) $retirer_fiche_Participant_confirme = ""; 
if( ! isset( $delete_fiche_invite_Fraternite ) ) $delete_fiche_invite_Fraternite = ""; 
if( ! isset( $delete_fiche_invite_Fraternite_confirme ) ) $delete_fiche_invite_Fraternite_confirme = ""; 

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

//session_start();
$debug = False;

$Activite_id=$_SESSION["Activite_id"];

if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
	$Title = "Parcours_Alpha";
	$AfficherClasse = False;
} elseif ($_SESSION["Activite_id"] == 12) { // Cathéchèse
	$Title = "Cathéchèse";
	$AfficherClasse = True;
} elseif ($_SESSION["Activite_id"] == 22) { // Emmaüs
	$Title = "Emmaüs";
	$AfficherClasse = False;
} elseif ($_SESSION["Activite_id"] == 26) { // Aumonerie
	$Title = "Aumônerie";
	$AfficherClasse = True;
} elseif ($_SESSION["Activite_id"] == 59) { // Parcours 40jours
	$Title = "40 jours - découvrir l'essentiel";
	$AfficherClasse = True;
} elseif ($_SESSION["Activite_id"] == 85) { // SophiaDeo
	$Title = "40 jours - découvrir l'essentiel";
	$AfficherClasse = True;
} else {
	$Title = "Fraternité";
	$AfficherClasse = False;
}

$SessionEnCours=$_SESSION["Session"];
if ($_SESSION["Session"]=="All") {
	$ComplementRequete = '';
} else {
	$ComplementRequete = ' AND MID(T0.`Session`,1,4)="'.$SessionEnCours.'" ';
}

require('templateFraternite.inc');
require('Common.php');

$debug = false;
pCOM_DebugAdd($debug, "Fraternite - SessionEnCours=".$SessionEnCours);

if ($action == "Logout")
{
	session_unregister("myusername");
	session_cache_expire(0);
	//session_destroy();
	echo '<META http-equiv="refresh" content="1; URL='.$_SERVER['PHP_SELF'].'">';
    exit();
}

require('Paroissien.php');
	
//======================================
// Vue accompagnateur
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_accomp") {
//if ($action == "list_accomp") {
	Global $eCOM_db;
	$debug = false;

	address_top();
	
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Liste accompagnateurs</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Accompagnateurs</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Adresse</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Téléphone / e-mail</font></TH>';
	//echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>e-mail</font></TH>';
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Enfant</font></TH>';
	} else {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Invité</font></TH>';
	}
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2>Couverts</font></TH>\n";
	$Activite_id=$_SESSION["Activite_id"];
	
	if ( $_SESSION["Session"] == "All" ) {
		$requete = 'SELECT T2.`id`, T2.`Date`, T1.`Lieu`, T3.`id` AS Individu_id, T3.`Nom` AS Nom, T3.`Prenom` AS Prenom, T3.`Adresse`, T3.`Telephone`, T3.`e_mail`, T0.`Engagement_id`
				FROM `QuiQuoi` As T0 
				LEFT JOIN `Fraternite` T2 ON T0.`Engagement_id`=T2.`id`
				LEFT JOIN `Lieux` AS T1 ON T1.`id`=T2.`Lieu_id` 
				LEFT JOIN `Individu` T3 ON T3.`id`=T0.`Individu_id`
				WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=2
				ORDER BY T3.`Prenom`, T3.`Nom` ';
	} else {
		$requete = 'SELECT T2.`id`, T2.`Date`, T1.`Lieu`, T3.`id` AS Individu_id, T3.`Nom` AS Nom, T3.`Prenom` AS Prenom, T3.`Adresse`, T3.`Telephone`, T3.`e_mail`, T0.`Engagement_id`
				FROM `QuiQuoi` As T0 
				LEFT JOIN `Fraternite` T2 ON T0.`Engagement_id`=T2.`id`
				LEFT JOIN `Lieux` AS T1 ON T1.`id`=T2.`Lieu_id` 
				LEFT JOIN `Individu` T3 ON T3.`id`=T0.`Individu_id`
				WHERE MID(T0.`Session`,1,4)='.$_SESSION["Session"].' AND T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=2
				ORDER BY T3.`Prenom`, T3.`Nom` ';
	}
	pCOM_DebugAdd($debug, "Fraternite:list_accomp - requete=".$requete);
	$Memo_Individu_id = 0;
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		if ( ($row['Individu_id'] != $Memo_Individu_id) || ($row['Individu_id'] == $Memo_Individu_id && $row['Engagement_id'] != 0) ) {
			$Memo_Individu_id = $row['Individu_id'];
			$trcolor = usecolor();
			echo '<TR><TD width=100 bgcolor='.$trcolor.'><font face=verdana size=2>';
			Display_Photo($row['Nom'], $row['Prenom'], $row['Individu_id'], 2);
			echo '</TD>';
			echo '<TD width=250 bgcolor='.$trcolor.'><font face=verdana size=2>'.Securite_html($row['Adresse']).'</TD>';
			echo '<TD width=70 bgcolor='.$trcolor.'><font face=verdana size=2>'.Securite_html($row['Telephone']).'<BR>';
			//echo '<TD width=70 bgcolor='.$trcolor.'><font face=verdana size=2>';
			echo "<A HREF='mailto:$row[e_mail]?subject= Paroisse ND Sagesse : ' TITLE='Envoyer un mail a $row[Prenom] $row[Nom]'>$row[e_mail]</A></TD>";
			echo '<TD bgcolor='.$trcolor.'><font face=verdana size=1>';
			//echo ' '.$row[`id`]. '';
			if ($row['id'] != 0) {
				$requete3 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T2.`NoFrat`, MID(T2.`Session`,1,4) AS Session
							FROM `Individu` T0 
							LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
							LEFT JOIN `Fraternite` T2 ON T2.`id`=T1.`Engagement_id` 
							WHERE T1.`QuoiQuoi_id`=1 and T2.`id`='.$row['id'].' AND T1.`Activite_id`='.$Activite_id.'  
							ORDER BY Session, Prenom, Nom';
				//$debug = true;
				pCOM_DebugAdd($debug, "Fraternite:list_accomp - requete3=".$requete3);
				$result3 = mysqli_query($eCOM_db, $requete3);
				$retour_Chariot = '';
				while($row3 = mysqli_fetch_assoc($result3)){
					echo "".$retour_Chariot."- ";
					if ( $_SESSION["Session"] == "All" ) {
						echo $row3['Session'].' ';
					}
					Display_Photo(Securite_html($row3['Nom']), Securite_html($row3['Prenom']), $row3['id'], 2);
					$retour_Chariot = '<BR>';
				}
			}
			echo "</TD></TR>";
		}
	}
	echo "</TABLE><BR>";
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();
}


//======================================
// Vue e_mail
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="e_mail") {
//if ($action == "e_mail") {
	$filtre=$filtre;
	echo 'test XML<BR>';
	$debug = false;
	
	require_once('includes/simplexml/class/IsterXmlSimpleXMLImpl.php');
	 // read and write a document
	$impl = new IsterXmlSimpleXMLImpl;
	$doc  = $impl->load_file('load/Aumonerie.xml');
  
	// access children
	foreach( $doc->children() as $Enfant ) {
		echo $Enfant->CDATA();
	}
	exit;
}
	



//======================================
// Composition des Tables
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_fraternite") {
//if ($action == "list_fraternite") {
	global $ComplementRequete;
	Global $eCOM_db;
	$debug = False;

	address_top();

	echo '<LINK rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Composition des fraternités</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Lieux</font></TH>';
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 22) || // Emmaüs
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
	} else {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Date</font></TH>';
	}
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Jour</font></TH>';
	}
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Fraternité</font></TH>';
	if ( $_SESSION["Session"] == "All" ) {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Session</font></TH>';
	}
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Catéchistes</font></TH>';
	} else {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Accompagnateurs</font></TH>';
	}
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Invité</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Couverts</font></TH>';
	echo '</TR>';
	$Activite_id=$_SESSION["Activite_id"];
	$Total_pers = 0;
	$MemoDateParcours ="";
	$MemoLieuParcours="";
	$DateParcours="";
	$LieuParcours="";
	
	pCOM_DebugAdd($debug, 'Fraternite:list_fraternite - Activite_id='.$Activite_id);
	
	// On recherche d'abord les sessions de l'année
	$requete = 'SELECT T0.`id`, T0.`Date`, T1.`Lieu`, T0.`Jour`, T0.`NoFrat`, T0.`Session` 
					FROM `Fraternite` As T0 
					LEFT JOIN `Lieux` AS T1 ON T1.`id`=T0.`Lieu_id`
					WHERE T0.`Activite_id`='.$Activite_id.' '.$ComplementRequete.'
					ORDER BY T0.`Date` DESC, T1.`Lieu` ASC, T0.`NoFrat` ';

	pCOM_DebugAdd($debug, 'Fraternite:list_fraternite - requete='.$requete);

	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();

		$LieuParcours=$row['Lieu']; //fCOM_get_lieu($row['Lieu_id']);;
		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
			$DateParcours=$row['Jour']; //fCOM_get_lieu($row['Lieu_id']);;

			} else {
			if (strftime("%d/%m/%y", sqlDateToOut($row['Date'])) == "01/01/70" ) {
				$DateParcours="-";
			} else {
				setlocale(LC_TIME,"fr_FR");
				if (intval(strftime("%k", sqlDateToOut($row['Date'])))>17 ) {
					$DateParcours=ucwords(strftime("%B", sqlDateToOut($row['Date'])))." Soirée";
				} elseif (intval(strftime("%k", sqlDateToOut($row['Date'])))>13 ) {
					$DateParcours=ucwords(strftime("%B", sqlDateToOut($row['Date'])))." Après-midi";
				} elseif (intval(strftime("%k", sqlDateToOut($row['Date'])))>11 ) {
					$DateParcours=ucwords(strftime("%B", sqlDateToOut($row['Date'])))." Midi";
				} else {
					$DateParcours=ucwords(strftime("%B", sqlDateToOut($row['Date'])))." Matin";
				}
			}
		}
			
		// Ligne de synthèse entre chaque parcours
		if (($MemoDateParcours != $DateParcours && $MemoDateParcours != "") || 
			($MemoLieuParcours != $LieuParcours && $MemoLieuParcours != ""))   {
	
			echo '<TR><TD align="center" bgcolor="#A1A1A1"><FONT face=verdana size=2>'.$MemoLieuParcours.'</FONT></TD>';
			if ($_SESSION["Activite_id"] != 22) { // Emmaüs
				echo '<TD align="center" bgcolor="#A1A1A1"><FONT face=verdana size=2>'.$MemoDateParcours.'</FONT></TD>';
			}
			echo '<TD align="center" bgcolor="#A1A1A1" colspan=4>';
			echo '<FONT face=verdana size=2>Prévoir '.$Total_pers.' couverts ( ajouter prêtres et secrétariat suivant disponibilité).</FONT></TD></TR>';
			$Total_pers = 0;
			$MemoDateParcours = $DateParcours;
		}
		
		echo '<TR>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Lieu'].'</FONT></TD>';
		$MemoLieuParcours=$LieuParcours;
		
		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 22) || // Emmaüs
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		} else {
			echo '<TD width=90 align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$DateParcours.'</FONT></TD>';
		}
		
		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.fCOM_Get_JourSemaine($row['Jour']).'</FONT></TD>';
		}
		
		echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>';
		if ($row['NoFrat'] == ""){
			$NoFrat = "-";
		} else {
			$NoFrat = $row['NoFrat'];
		}
		
		echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].'>'.$NoFrat.'</A>';
		echo '</FONT></TD>';
		
		
		if ( $_SESSION["Session"] == "All" ) {
			echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Session'].'</FONT></TD>';
		}
		
		echo '</FONT></TD>';

		$nb_personnes = 0;
		// Liste des accompagnateurs
		//-------------------------------------
		$retour_Chariot = '';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>';
		$requete2 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T0.`Adresse`, T0.`Telephone`, T0.`e_mail`, T2.`NoFrat` FROM `Individu` T0 
					LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					LEFT JOIN `Fraternite` As T2 ON T2.`id`=T1.`Engagement_id` 
					WHERE T1.`Activite_id`='.$Activite_id.' and T1.`QuoiQuoi_id`=2 and T2.`id`='.$row['id'].' 
					ORDER BY T0.`Prenom`, T0.`Nom`';
		$debug = False;
		pCOM_DebugAdd($debug, "Fraternite:list_fraternite - requete2=".$requete2);

		$result2 = mysqli_query($eCOM_db, $requete2);
		while($row2 = mysqli_fetch_assoc($result2)){
			echo "".$retour_Chariot."- ";
			Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], 2);
			$retour_Chariot = '<BR>';
			$nb_personnes = $nb_personnes + 1;
		}
		echo '</TD>';
			
		// Liste des Participants
		//-----------------------------
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=1>';
		$requete3 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T2.`NoFrat` 
					 FROM `Individu` T0 
					 LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					 LEFT JOIN `Fraternite` As T2 ON T2.`id`=T1.`Engagement_id` 
					 WHERE T1.`Activite_id`='.$Activite_id.' and T1.`QuoiQuoi_id`=1 and T2.`id`='.$row['id'].' ORDER BY Prenom, Nom';
		pCOM_DebugAdd($debug, 'Fraternite:list_fraternite - requete3='.$requete3);
		
		$result3 = mysqli_query($eCOM_db, $requete3);
		$retour_Chariot = '';
		while($row3 = mysqli_fetch_assoc($result3)){
			// Liste des participants
			echo "".$retour_Chariot."- ";
			Display_Photo(Securite_html($row3['Nom']), Securite_html($row3['Prenom']), $row3['id'], 2);
			$retour_Chariot = '<BR>';
			$nb_personnes = $nb_personnes + 1;
		}
		echo '</TD>';
		echo '<TD bgcolor='.$trcolor.'>';
		if ($nb_personnes > 0) { echo '<font face=verdana size=2>'.$nb_personnes.'</font>';}
		echo '</TD></TR>';
		$Total_pers = $Total_pers + $nb_personnes;
	}
	echo '<TR>';
	// Dernière ligne de synthèse entre chaque parcours
	echo '<TD align="center" bgcolor="#A1A1A1"><font face=verdana size=2>'.$LieuParcours.'</font></TD>';
	if ($_SESSION["Activite_id"] != 22) { // Emmaüs
		echo '<TD align="center" bgcolor="#A1A1A1"><FONT face=verdana size=2>'.$DateParcours.'</FONT></TD>';
	}
	echo '<TD align="center" bgcolor="#A1A1A1" colspan=4>';
	echo '<font face=verdana size=2>Prévoir '.$Total_pers.' couverts (ajouter prêtres et secrétariat suivant disponibilité).</font></TD></TR>';
		
	echo '</TABLE><BR>';
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();
}

//======================================
// Vue Financiere
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="vue_financiere_old") {
//if ($action == "vue_financiere_old") {
	Global $eCOM_db;
	$debug = false;

	address_top();
	
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Vue financière</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Invité</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Session</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Accompagnateurs</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Date</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=3>€</font></TH>';
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT T0.`id` AS T0id, T1.`id` AS T1id, T0.`Session` AS Session, T1.`Nom`, T1.`Prenom`, T0.`Finance` AS Finance, T3.`Nom` AS Accompagnateur, T0.`date` AS Date, T4.`Lieu` As Lieu 
				FROM `Fraternite` T0 
				LEFT JOIN `Individu` T1 ON T0.`Invite_id`=T1.`id` 
				LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` 
				LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id` 
				ORDER BY T0.`date` DESC';
	} else {
		$requete = 'SELECT T0.`id` AS T0id, T1.`id` AS T1id, T0.`Session` AS Session, T1.`Nom`, T1.`Prenom`, T0.`Finance` AS Finance, T3.`Nom` AS Accompagnateur, T0.`date` AS Date, T4.`Lieu` As Lieu 
				FROM `Fraternite` T0 
				LEFT JOIN `Individu` T1 ON T0.`Invite_id`=T1.`id` 
				LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` 
				LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id` 
				WHERE MID(T0.`Session`,1,4)=' . $_SESSION["Session"] .'  
				ORDER BY T0.`date` DESC';
	}

	pCOM_DebugAdd($debug, "Fraternite:vue_financiere_old - requete01 =".$requete);
	$result = mysqli_query($eCOM_db, $requete);
	debug($result. "<BR>\n");
	$total = 0;
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		echo "<TR>";
		echo "<TD bgcolor=$trcolor><font face=verdana size=2>";
		
		echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['T0id'].' class="tooltip">'.$row['Prenom'].' '.strtoupper($row['Nom']).'';
		echo '<EM><span></span>';
		echo '<IMG src="Individu_'.$row['id'].'.jpg" height="100" border="1" alt="Individu_'.$row['id'].'">';
		echo '<BR>'.$row['Prenom'].' '.$row['Nom'].'</EM></a>';
		echo '</FONT></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.substr($row['Session'],4).'</FONT></TD>';
		$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom`, T1.`Sex` 
				FROM `QuiQuoi` T0 
				LEFT JOIN `Individu` T1 ON T1.`id` = T0.`Individu_id` 
				WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' and T0.`Engagement_id`='.$row['T0id'].' and T0.`QuoiQuoi_id`=2 
				ORDER BY T1.`Nom`, T1.`Sex` DESC';
		pCOM_DebugAdd($debug, "Fraternite:vue_financiere_old - requete02 =".$requete);
		$resultat = mysqli_query($eCOM_db,  $requete );
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>';
		$Nom = "";
		while( $Row_Accomp = mysqli_fetch_assoc( $resultat ))
		{
			if ($Nom == $Row_Accomp['Nom']) {
				echo " et ".Securite_html($Row_Accomp['Prenom'])."";
			} else {
				if ($Nom != "") {echo "<br>";}
				echo "".Securite_html($Row_Accomp['Nom'])." ".Securite_html($Row_Accomp['Prenom'])."";
				$Nom = Securite_html($Row_Accomp['Nom']);
			}
		}
		echo '</FONT></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>';
		echo strftime("%d/%m/%y &nbsp  %H:%M", sqlDateToOut($row['Date']));
		echo '</FONT></TD>';
		echo '<TD align="right" width="35" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Finance'].'</FONT></TD>';
		$total = $total + $row['Finance'];
		echo '</TR>';
	}
	$trcolor = usecolor();
	echo '<TR><TD></TD><TD></TD><TD></TD><TD></TD><TD bgcolor='.$trcolor.'><font face=verdana size=2><B>Total</B></font></TD><TD bgcolor='.$trcolor.'><font face=verdana size=2><B>'.$total.'</B></font></TD></font></TR>';
	echo '</table>';
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();
}



//view profiles
if ( isset( $_GET['action'] ) AND $_GET['action']=="profile") {
//if ($action == "profile") {

	exit();	

}



//--------------------------------------------------------------------------------------
//print one record by id
//--------------------------------------------------------------------------------------
if ( isset( $_GET['action'] ) AND $_GET['action']=="printid") {
//if ($action == "printid") { 

}
	
//print all records
if ( isset( $_GET['action'] ) AND $_GET['action']=="printall") {
//if ($action == "printall") { 
	Global $eCOM_db;
	mysqli_close($eCOM_db);
	exit();
}

//==========================================================
//==========================================================
//edit records Fraternite
//
// id == 0 pour créé une nouvelle fraternité
//==========================================================
//==========================================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit") {
//if ($action == "edit") { 
	Global $eCOM_db;
	$debug = false;
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		$Liste_SSSessions = array("Session ?", "Biot Octobre soirée", "Sophia Janvier midi", "Sophia Avril soirée", "Sophia Octobre soirée", "Valbonne Janvier soirée");
		
	} else if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		$Liste_SSSessions = array("Liste_SSSessions ?");
		
	} else if ($_SESSION["Activite_id"] == 26) { // Aumonerie
		$requete = 'SELECT Lieu FROM Lieux WHERE IsParoisse=1 ORDER by Lieu';
		$result = mysqli_query($eCOM_db, $requete);
		$Liste_SSSessions = array("Lieux ?");
		$Item = 1;
		while($row = mysqli_fetch_assoc($result)){
			$Liste_SSSessions[$Item]=$row['Lieu'];
			$Item =$Item + 1;
		}
	} else {
		$Liste_SSSessions = array("Session ?");
	}
	
	$id = $_GET['id'];
	$Activite_id=$_SESSION["Activite_id"];
	
	if ( $id == 0 ) {
		// creation d'une nouvelle fiche impossible si pas gestionnaire ou administrateur
		if (fCOM_Get_Autorization( $Activite_id ) < 30) {
			echo '<META http-equiv="refresh" content="0; URL=/Fraternite.php">';
		} else {
			mysqli_query($eCOM_db, 'INSERT INTO Fraternite (id, Activite_id, Commentaire) VALUES (0,'.$Activite_id.', "")') or die (mysqli_error($eCOM_db));
			$id=mysqli_insert_id($eCOM_db);
		}
	}
	$requete = 'SELECT T0.`id`, T0.`NoFrat`, T0.`MAJ`, T0.`Session`, T0.`SS_Session`, 
					   T0.`date` As Date, T0.`Jour`, T1.`Lieu` As Lieu, T1.`id` As Lieu_id, T0.`Commentaire` 
				FROM `Fraternite` T0 
				LEFT JOIN `Lieux` T1 ON T0.`Lieu_id`=T1.`id` 
				WHERE T0.id='.$id.' ';

	pCOM_DebugAdd($debug, "Fraternite:edit - requete =".$requete);

	$result = mysqli_query($eCOM_db, $requete);
	//while($row = mysqli_fetch_assoc($result))
	$row = mysqli_fetch_assoc($result);
		
	address_top();
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Edition: ';
	if ($id == 0) {
		echo 'Nouvelle fiche</B></FONT></TD>';
	} else {
		echo 'Fiche No '.$row['id'].'</B></FONT></TD>'; 
		if (strftime("%d/%m/%y", sqlDateToOut($row['MAJ'])) != "01/01/70" ) {
			echo '<TD align="right"><FONT FACE="Verdana" SIZE="1"> (Dernière modification au '.strftime("%d/%m/%Y %T", sqlDateToOut($row['MAJ'])).')</TD>';
		}
	}
	echo '</TR>';
	
	echo '<TR>';
	echo '<TD BGCOLOR="#EEEEEE" Colspan="2">';
	echo '<CENTER><font face="verdana" size="2">';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<TR><TD bgcolor="#eeeeee">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'"><B><FONT SIZE="3"> </FONT></B></TD></TR>';
	
	// Table ou No Frat =================================
	echo '<TR><TD><b><FONT SIZE="2">Nom de Fraternité:</FONT></b></TD>';
	echo '<TD bgcolor="#eeeeee"><input type=text name="NoFrat" placeholder="........" value ="'.$row['NoFrat'].'" size="10" maxlength="10" '.$BloquerAcces.'></TD></TR>';
	
	// Session ==========================================
	$debug = False;
	echo '<TR><TD>';
	echo '<B><FONT FACE="Verdana" SIZE="2">Session:</FONT></B></TD><TD>';
	echo '<SELECT name="PSession">';
	for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
		if (($row['Session'] != "" && $i == intval($row['Session'])) || ($row['Session'] == "" && $i == intval($_SESSION["Session"]))) {
			echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
		}
	}
	echo '</SELECT>';
	echo '</TD></TR>';
	
	// Groupe de KT / Sous-Session pour Alpha ======================================
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<INPUT type=hidden name="ss_session" value=" ">';
	} else {
		echo '<TR><TD valign="top" bgcolor="#eeeeee"><B><FONT SIZE="2">Groupe:</FONT></B></TD><TD>';
		echo '<SELECT name="ss_session" '.$BloquerAcces.' >';
		$Liste_Groupe_KT = fCOM_Get_Liste_Groupe_KT();
		foreach ($Liste_Groupe_KT as $Groupe_KT){
			list($Groupe_id, $Groupe_nom) = $Groupe_KT;
			$SelectionDefault = '';
			if ($row['SS_Session'] == $Groupe_id) {
				$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$Groupe_id.'"'.$SelectionDefault.'>'.$Groupe_nom.'</option>';
		}
		echo '</SELECT>';
		echo '</TD></TR>';
		echo '<TD></TD><TD></TD></TR>';
	}
	
	// Date / jour et heure =====================================
	if (($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TR><TD valign="top" bgcolor="#eeeeee"><B><FONT SIZE="2">Jour et heure des rencontres :</FONT></B></TD><TD>';
		$Liste_Jour = fCOM_Get_Liste_JoursSemaine();
		echo '<SELECT name="JourSemaine" '.$BloquerAcces.' >';
		$Jour = 0;
		if ($row['Jour']>=1 AND $row['Jour']<=7) {
			$Jour = $row['Jour'];
		} else {
			$Jour = 0;
		}
		for ($i = 0; $i<=7; $i++){
			$SelectionDefault = '';
			if ($i == $Jour) {
				$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$i.'"'.$SelectionDefault.'>'.$Liste_Jour[$i].'</option>';
		}
		echo '</SELECT>';

	} else {
		echo '';
		if (! empty($row['Date'])) {
			//$DateYear=substr($row['Date'],0,4);
			//$DateMonth=substr($row['Date'],5,2);
			//$DateDay=substr($row['Date'],8,2);
			//$DateValue = $DateDay."/".$DateMonth."/".$DateYear;
			$DateValue = fCOM_PrintDate($row['Date']);
		} else {
			$DateValue ="";
		}

		echo '<TR><TD valign="top" bgcolor="#eeeeee"><B><FONT SIZE="2">Date et heure de la 1ère rencontre :</FONT></B></TD>';
		echo '<TD width="225" bgcolor="#eeeeee" colspan="2">';
		echo '<input type=text id="Date" name="Date" value ="'.$DateValue.'" size="9" maxlength="10" '.$BloquerAcces.'>';
		if ($BloquerAcces=="") { 
			?>
			<a href="javascript:popupwnd('calendrier.php?idcible=Date&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
			<?php
		}
		echo '</SELECT>';
	}
	
		echo '<b><FONT SIZE="2">  Heure </FONT></b>';
		$hour = substr($row['Date'],11,2);
		echo '<SELECT name="heure" '.$BloquerAcces.' >';
		for ($i=7; $i<=23; $i++) {
			if ($i == intval($hour)) {echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';} else {echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';}
		}
		echo '</SELECT>:';

		$min = substr($row['Date'],14,2);
		echo '<SELECT name="minute" '.$BloquerAcces.' >';
		for ($i=0; $i<=45; $i=$i+15) {
			if ($i == intval($min)) {	echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';} else {echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';}
		}
		echo '</SELECT></TD></TR>';
	

	// Lieux ==========================================
	$debug = False;
	echo '<TR><TD>';
	echo '<B><FONT FACE="Verdana" SIZE="2">Lieu:</FONT></B></TD><TD>';
	echo '<SELECT name="Lieux_id">';
	pCOM_DebugAdd($debug, 'Fraternite:Edit Lieu_name='.$row['Lieu']);
	pCOM_DebugAdd($debug, 'Fraternite:Edit Lieu_id='.$row['Lieu_id']);
	$Liste_Lieu_Celebration = pCOM_Get_liste_lieu_celebration(1);
	foreach ($Liste_Lieu_Celebration as $Lieu_Celebration_array){
		list($Lieu_id, $Lieu_name) = $Lieu_Celebration_array;
		if ($row['Lieu_id'] == $Lieu_id) {
			echo '<option value="'.$Lieu_id.'" selected="selected">'.$Lieu_name.'</option>';
		} else {
			echo '<option value="'.$Lieu_id.'" >'.$Lieu_name.'</option>';
		}
	}
	echo '</SELECT>';
	

	// invité / Participant ==========================================
	echo '<TR><TD valign="top">';
	if ( $id > 0 ) {
		echo '<div><input type="submit" name="Selectionner_Individue" value="Participant(s)"></TD>';
		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` 
					FROM `Individu` T0 
					LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					WHERE T1.`Activite_id`='.$_SESSION["Activite_id"].' AND T1.`QuoiQuoi_id`=1 and T1.`Engagement_id`='.$id.' 
					ORDER by Prenom, Nom';
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				echo "<A HREF=".$_SERVER['PHP_SELF']."?action=RetirerParticipantDeFraternite&Qui_id=$row2[id]&Invite_id=$id TITLE='Retirer Participant de la fraternité'><img src=\"images/moins.gif\" border=0 alt='Retirer Participant'></a>  ";
			} else {
				echo "<A HREF=".$_SERVER['PHP_SELF']."?action=RetirerParticipant&Qui_id=".$row2['id']."&Invite_id=".$id."&TITLE='Retirer Participant'><img src=\"images/moins.gif\" border=0 alt='Retirer Participant'></a>  ";
			}
			Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], 2);
			//echo '</TR><TR><TD></TD><TD>';
			echo '<BR>';
		}
		echo '</TD>';

	}
	echo '</TD></TR>';
	echo '<TR><TD height="10"></TD></TR>';
	
	// Accompagnateur ==========================================
	echo '<TR><TD valign="top">';
	$nom_accompagnateur = "Accompagnateur(s)";
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		$nom_accompagnateur = "Catéchiste(s)";
	}
	if ( $id > 0 ) {
		echo '<div><input type="submit" name="Selectionner_Individue" value="Accompagnateur(s)"></td>';
		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` 
					FROM `Individu` T0 
					LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					WHERE T1.`Activite_id`='.$_SESSION["Activite_id"].' AND T1.`QuoiQuoi_id`=2 and T1.`Engagement_id`='.$id.'
					ORDER BY Prenom, Nom';
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			echo "<A HREF=".$_SERVER['PHP_SELF']."?action=RetirerAccompagnateur&Qui_id=$row2[id]&Invite_id=$id TITLE='Retirer Accompagnateur'><img src=\"images/moins.gif\" border=0 alt='Delete Accompagnateur'></a>  ";
			Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], 2);
			//echo '</TR><TR><TD></TD><TD>';
			echo '<BR>';
		}
		echo '</TD>';
	}
	echo '</TD></TR>';
	
	// Commentaire ==========================================
	echo '<TR><TD colspan="2" bgcolor="#eeeeee">';
	echo '<B><FONT SIZE="2">Commentaires:</FONT></B>';
	echo '<br>';
	if ( $id > 0 ) {
		echo '<textarea cols=80 rows=5 name="Commentaire" maxlength="350" value ="'.Securite_html($row['Commentaire']).'">'.Securite_html($row['Commentaire']).'</textarea>';
	}

	echo '<BR></TR><TD> </TD></TR>';
	//echo '<INPUT type=hidden name="Paroissien_id" value='.$row['Invite_id'].'>';
	echo '<INPUT type=hidden name="Invite_id" value='.$id.'>';
	echo '<TR><TD>';
	if ( $id > 0 ) {
		echo '<div align="center"><INPUT type="submit" name="edit" value="Enregistrer">';
	}
	//echo '<input type="reset" name="Reset" value="Reset">';
	if (fCOM_Get_Autorization( $Activite_id ) >= 50) {
		echo '</TD><TD><INPUT type="submit" name="delete_fiche_invite_Fraternite" value="Détruire la fiche">';
	}
	
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR>';
	echo '</TABLE>';
	echo '</FORM>';
	echo '</CENTER>';

	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit(); 
}



function Sauvegarder_fiche_invite ( )
{
	Global $eCOM_db;
	$debug = False;
	$Activite_id=$_SESSION["Activite_id"];

	if (!isset($_POST['Invite_id'])) {
		pCOM_DebugAdd($debug, "Fraternite:Sauvegarder_fiche_invite Invite_id=0 Sauvegarde impossible");
		exit;
	} else {
		$Invite_id=$_POST['Invite_id'];
	}
	if (! isset($_POST['Lieux_id'])) {$Lieux_id = 0;} else { $Lieux_id = $_POST['Lieux_id'];}
	if (!isset($_POST['Date'])) { $Date = "01/01/1970"; } else { $Date = $_POST['Date'];}
	if (!isset($_POST['JourSemaine'])) { $Jour = 0; } else { $Jour = $_POST['JourSemaine'];}
	if (!isset($_POST['heure'])) { $heure = "0"; } else { $heure = $_POST['heure'];}
	if (!isset($_POST['minute'])) { $minute = "0"; } else { $minute = $_POST['minute'];}
	if (!isset($_POST['PSession'])) { $PSession = "0"; } else { $PSession = $_POST['PSession'];}
	if (!isset($_POST['ss_session'])) { $ss_session = "0"; } else { $ss_session = $_POST['ss_session'];}
	if (!isset($_POST['NoFrat'])) { $NoFrat = "0"; } else { $NoFrat = $_POST['NoFrat'];}
	if (!isset($_POST['Commentaire'])) { $Commentaire = "0"; } else { $Commentaire = $_POST['Commentaire'];}
	
	
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
		$sss_session = " ".$ss_session;
		
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - Date='.$Date);
		$SqlDate = fCOM_getSqlDate($Date, $heure, $minute, 0);
		
		if (strpos($sss_session, "Janvier ")>0) {			$Mois="01";
		} elseif (strpos($sss_session, "Février ")>0) {	$Mois="02";
		} elseif (strpos($sss_session, "Mars ")>0) {		$Mois="03";
		} elseif (strpos($sss_session, "Avril ")>0) {		$Mois="04";
		} elseif (strpos($sss_session, "Mai ")>0) {		$Mois="05";
		} elseif (strpos($sss_session, "Juin ")>0) {		$Mois="06";
		} elseif (strpos($sss_session, "Juillet ")>0) {	$Mois="07";
		} elseif (strpos($sss_session, "Août ")>0) {		$Mois="08";
		} elseif (strpos($sss_session, "Septembre ")>0) {$Mois="09";
		} elseif (strpos($sss_session, "Octobre ")>0) {	$Mois="10";
		} elseif (strpos($sss_session, "Novembre ")>0) {	$Mois="11";
		} elseif (strpos($sss_session, "Décembre ")>0) {	$Mois="12";
		} else {$Mois="01";}
		$Annee=$_SESSION["Session"];
		if ($Mois == "09" || $Mois == "10" || $Mois == "11" || $Mois == "12") {
			$Annee=$Annee-1;
		}
		if (strpos($sss_session, " soirée")>0) {		$heure="19";
		} elseif (strpos($sss_session, " midi")>0) {	$heure="12"; }
		$DateTimeValue = $Annee."-".$Mois."-01 ".$heure.":00:00";

		//$Nom = strtoupper($Nom);
		//debug($DateTimeValue . "<BR>\n");
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - DateTimeValue='.$DateTimeValue);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET Date="'.$SqlDate.'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - $Lieux_id='.$Lieux_id);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET Lieu_id='.$Lieux_id.' WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - PSession='.$PSession);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET Session="'.$PSession.'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - ss_session='.$ss_session);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET SS_Session="'.$ss_session.'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - NoFrat='.$NoFrat);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET NoFrat="'.$NoFrat.'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - Jour='.$Jour);
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET Jour='.$Jour.' WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));

	}
	mysqli_query($eCOM_db, 'UPDATE Fraternite SET Commentaire="'.$Commentaire.'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
	mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$Invite_id.' ') or die (mysqli_error($eCOM_db));
	$Resultat=0;

	return($Resultat);
}


//updates table in DB
if ( isset( $_POST['edit'] ) AND $_POST['edit']=="Enregistrer") {
//if ($edit) {
	Global $eCOM_db;
	$retour = Sauvegarder_fiche_invite ();
	if ($retour == 0) {
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Record Updated</FONT></CENTER></B>';
	}
	mysqli_close($eCOM_db);
	echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?action=list_fraternite">';
	exit;
}


//delete part 1
if ( isset( $_POST['delete_fiche_invite_Fraternite'] ) AND $_POST['delete_fiche_invite_Fraternite']=="Détruire la fiche") {
//if ($delete_fiche_invite_Fraternite) {
	Global $eCOM_db;
	$debug = true;
	$requete = 'SELECT T0.`NoFrat` 
				FROM Fraternite T0 
				WHERE T0.`id`='.$_POST['Invite_id'].' '; 
	pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite - requete='.$requete);
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite - Nb Enreg dans la table'. mysqli_num_rows($result));

	while($row = mysqli_fetch_assoc($result))
	{
		address_top();
		
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Suppression d\'une fraternité</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir supprimer la fraternité ('.$_POST['Invite_id'].') '.$row['NoFrat'].' ?</FONT>';
		echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<INPUT type="submit" name="delete_fiche_invite_Fraternite_confirme" value="Oui">';
		echo '<INPUT type="submit" name="" value="Non">';
		echo '<INPUT type="hidden" name="id" value='.$_POST['Invite_id'].'>';
		echo '</FORM></TD></TR>';

		fCOM_address_bottom();
		mysqli_close($eCOM_db);
		exit();	
	}
}

//delete part 2
if ( isset( $_POST['delete_fiche_invite_Fraternite_confirme'] ) AND $_POST['delete_fiche_invite_Fraternite_confirme']=="Oui") {
//if ($delete_fiche_invite_Fraternite_confirme) {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite_confirme - id='.$_POST['id']);
	$requete = 'SELECT * FROM Fraternite WHERE id='.$_POST['id'].' '; 
	pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite_confirme - requete01='.$requete);
	$result = mysqli_query($eCOM_db, $requete);
    pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite_confirme - Nb Enreg dans la table '.mysqli_num_rows($result));

	if (mysqli_num_rows( $result )==1)
	{ 
		$Activite_id=$_SESSION["Activite_id"];
		// FDM supprimer en premier QuiQuoi puis Fraternité
        $requete = 'Delete FROM Fraternite WHERE id='.$_POST['id'].' '; 
		pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite_confirme - requete02='.$requete);
        $result = mysqli_query($eCOM_db, $requete); 
		if (!$result) {
			echo 'Impossible de détruire la fraternité de la base' . mysqli_error($eCOM_db);
			exit;
        } else {
	        $requete = 'Delete FROM QuiQuoi WHERE Activite_id="'.$Activite_id.'" and Engagement_id='.$_POST['id'].' '; 
			pCOM_DebugAdd($debug, 'Fraternite:delete_fiche_invite_Fraternite_confirme - requete03='.$requete);
			$result = mysqli_query($eCOM_db, $requete); 
			if (!$result) {
				echo 'Impossible de supprimer les participants de la fraternité : ' . mysqli_error($eCOM_db);
				exit;
			}
		}
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Fraternité supprimée avec succès</FONT></CENTER></B>';
	}
	mysqli_close($eCOM_db);
	echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?action=list_fraternite">';
	exit;
}

//delete part 1
if ( isset( $_POST['retirer_fiche_Participant'] ) AND $_POST['retirer_fiche_Participant']=="Retirer ce participant") {
//if ($retirer_fiche_Participant) {
	Global $eCOM_db;
	$debug = false;
	$requete = 'SELECT T1.`Nom`, T1.`Prenom`
                FROM QuiQuoi T0
				LEFT JOIN Individu T1 ON T1.`id`=T0.`Individu_id`					   
                WHERE T0.`id`='.$_POST['QuiQuoi_id'].' '; 
	pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant - requete='.$requete);
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant - Nb Enreg dans la table '.mysqli_num_rows($result));

	while($row = mysqli_fetch_assoc($result))
	{
		Global $eCOM_db;
		address_top();
		
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Retirer personne de l\'activité</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir retirer '.$row['Prenom'].' '.$row['Nom'].' de l\'activite ?</FONT>';
		echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<INPUT type="submit" name="retirer_fiche_Participant_confirme" value="Oui">';
		echo '<INPUT type="submit" name="" value="Non">';
		echo '<INPUT type="hidden" name="id" value='.$_POST['QuiQuoi_id'].'>';
		echo '</FORM></TD></TR>';

		fCOM_address_bottom();
		mysqli_close($eCOM_db);
		exit();	
	}
}

//delete part 2
if ( isset( $_POST['retirer_fiche_Participant_confirme'] ) AND $_POST['retirer_fiche_Participant_confirme']=="Oui") {
//if ($retirer_fiche_Participant_confirme) {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant_confirme - id='.$id);
	$requete = 'SELECT * FROM QuiQuoi WHERE id='.$_POST['id'].' '; 
	pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant_confirme - requete01='.$requete);
	$result = mysqli_query($eCOM_db, $requete);
    pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant_confirme - Nb Enreg dans la table '.mysqli_num_rows($result));
	if (mysqli_num_rows( $result )==1)
	{ 
        $requete = 'Delete FROM QuiQuoi WHERE id='.$_POST['id'].' '; 
		pCOM_DebugAdd($debug, 'Fraternite:retirer_fiche_Participant_confirme - requete02='.$requete);
        $result = mysqli_query($eCOM_db, $requete); 
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Participant retiré avec succès</FONT></CENTER></B>';
	}
}



//==========================================================
//==========================================================
//edit edit_Inscription
//==========================================================
//==========================================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit_Inscription") {
//if ($action == "edit_Inscription") {
	$Select_Individue = "Participant";
	$Label = "un(e) nouveau(elle) participant(e)";
	$Inscription = true;
	Selectionner_individu_BaseFraternite($Select_Individue, $Label, $Inscription, $_GET['id']);
}


// =================================================
// Ajouter individu à QuiQuoi (accompagnateur, Parrain ou Celebrant, Participant)
// =================================================
function Selectionner_individu_BaseFraternite ( $pQui, $Label, $Inscription, $Invite_id)
{
	Global $eCOM_db;
	$debug = true;
	address_top();
	pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - pQui='.$pQui);
	pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - Label='.$Label);
	pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - Inscription='.$Inscription);
	pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - Invite_id='.$Invite_id);
	
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';

	if ( $pQui == "Baptise" || $pQui == "Participant") { 
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Selectionner '.$Label.'</B><BR></TD><TD></TD><TD></TD></TR>';
		pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - Participant ou Baptise');
	} else {
		$requete = 'SELECT T0.`Prenom` AS Prenom, T0.`Nom` AS Nom 
					FROM Individu T0 
					LEFT JOIN Fraternite T1 ON T1.`Invite_id`=T0.`id` 
					WHERE T1.`id`='.$Invite_id.' ';
		pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - requete(1)='.$requete);
		$result = mysqli_query($eCOM_db, $requete);
		$row = mysqli_fetch_assoc($result);
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Selectionner '.$Label.' de '.$row['Prenom'].' '.$row['Nom'].'</B><BR></TD><TD></TD><TD></TD></TR>';
	}
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<FONT FACE="Verdana" size="2" ><BR>';
	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Sélectionner</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Prénom</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom</font></TH>';
	//if ($AfficherClasse == True) { // Aumônerie Lycée et collège
	if ( $pQui != "Accompagnateur" && 
		(($_SESSION["Activite_id"] == 12) || // Cathéchèse
		($_SESSION["Activite_id"] == 26))) { // Aumônerie Lycée et collège
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Classe</font></TH>';
	}
	$Activite_id=$_SESSION["Activite_id"];
	if ($pQui == "Celebrant") {
		$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom 
					FROM `QuiQuoi` T0 
					LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
					WHERE T0.`Engagement_id`=0 and (T0.`QuoiQuoi_id`=7 or T0.`QuoiQuoi_id`=8) AND T1.Actif = 1 AND T1.Dead = 0
					ORDER BY T1.Nom, T1.Prenom';
	
	} elseif ($pQui == "Accompagnateur") {
		$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom 
					FROM `QuiQuoi` T0 
					LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
					WHERE T0.`Activite_id`="'.$Activite_id.'" and T0.`QuoiQuoi_id`=2 and T0.`Engagement_id`=0 AND T1.Actif = 1 AND T1.Dead = 0
					GROUP BY T1.`id` 
					ORDER BY T1.`Nom`, T1.`Prenom` ';
	
	} elseif ($pQui == "Participant") {
		// $pQui == "Participant"
		if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
			$Title = "Cathéchèse";
			if ( $Inscription == True ) { // nouvelle inscription
				$requete = 'SELECT DISTINCT T0.`id`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` 
							FROM `Individu` T0
							LEFT JOIN `QuiQuoi` T1 ON T1.`Individu_id`=T0.`id`
							AND (T1.`Activite_id`='.$Activite_id.' AND T1.`QuoiQuoi_id`=1 AND T1.`Session`='.$_SESSION["Session"].')
							WHERE T1.id IS NULL AND T0.`Naissance` >= DATE_SUB(now(), INTERVAL 15 YEAR) AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 4 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';
			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T0.`Activite_id`="'.$Activite_id.'" AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 
							GROUP BY T1.`id` 
							ORDER BY T1.`Nom`, T1.`Prenom` ';
			}
										
		} else if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
			$Title = "Aumônerie";
			if ( $Inscription == True ) { // nouvelle inscription
				$requete = 'SELECT DISTINCT T0.`id`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` 
							FROM `Individu` T0
							LEFT JOIN `QuiQuoi` T1 ON T1.`Individu_id`=T0.`id`
							AND (T1.`Activite_id`='.$Activite_id.' AND T1.`QuoiQuoi_id`=1 AND T1.`Session`='.$_SESSION["Session"].')
							WHERE  T1.id IS NULL AND T0.`Naissance` >= DATE_SUB(now(), INTERVAL 20 YEAR) AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 10 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';

			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe 
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 
							GROUP BY T1.`id` 
							ORDER BY T1.`Nom`, T1.`Prenom` ';
			}
										
			
		} else if ($_SESSION["Activite_id"] == 22) { // Emmaüs
			$Title = "Emmaüs";
			if ( $Inscription == True ) { // nouvelle inscription
				$requete = 'SELECT id, Prenom, Nom, Naissance 
							FROM Individu 
							ORDER by Nom, Prenom ';
				$requete = 'SELECT DISTINCT T0.`id`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` 
							FROM `Individu` T0
							LEFT JOIN `QuiQuoi` T1 ON T1.`Individu_id`=T0.`id`
							AND (T1.`Activite_id`='.$Activite_id.' AND T1.`QuoiQuoi_id`=1 AND T1.`Session`='.$_SESSION["Session"].')
							WHERE  T1.id IS NULL AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 15 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';	
			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe 
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 AND T1.Actif = 1 AND T1.Dead = 0
							GROUP BY T1.`id` 
							ORDER BY T1.`Nom`, T1.`Prenom` ';
			}
										
		} else if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
			$Title = "Parcours Alpha";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						LEFT JOIN `QuiQuoi` T1 ON T1.`Individu_id`=T0.`id`
						AND (T1.`Activite_id`='.$Activite_id.' AND T1.`Engagement_id`='.$Invite_id.' AND T1.`QuoiQuoi_id`=1 )
						WHERE T1.id IS NULL AND T0.Actif = 1 AND T0.Dead = 0 AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 10 YEAR)
						ORDER by Nom, Prenom';
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.Actif = 1 AND T0.Dead = 0 AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 10 YEAR)
						ORDER by Nom, Prenom';
						
		} else if ($_SESSION["Activite_id"] == 59) { // Parcours 40jours
			$Title = "Parcours 40jours";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.Actif = 1 AND T0.Dead = 0
						ORDER by Nom, Prenom'; 
						
		} else if ($_SESSION["Activite_id"] == 85) { // SophiaDeo
			$Title = "SophiaDeo";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.Actif = 1 AND T0.Dead = 0
						ORDER by Nom, Prenom'; 
		} else {
			$Title = "Fraternité";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.Actif = 1 AND T0.Dead = 0
						ORDER by Nom, Prenom'; 
		}
		pCOM_DebugAdd($debug, 'Fraternite:Selectionner_individu_BaseFraternite - requete(2) = '.$requete);

	}
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)) {
		$trcolor = usecolor();
		echo '<TR>'; 
		if ($pQui == "Participant" && $Inscription == True && ($Title == "Cathéchèse" || $Title == "Aumônerie" || $Title == "Emmaüs")) {
				// il faut saisir la participation financière de l'inscription
			echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui='.$pQui.'&Qui_id='.$row['id'].'&Invite_id='.$Invite_id.' TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Add Record"></a></td>  ';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Prenom'].'</FONT></TD>';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Nom'].'</FONT></TD>';
		} else {
			echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=AffecterBaseFraternite&Qui='.$pQui.'&Qui_id='.$row['id'].'&Invite_id='.$Invite_id.' TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Add Record"></a></td>  ';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Prenom'].'</FONT></TD>';
			echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Nom'].'</FONT></TD>';
			if ($pQui != "Accompagnateur" &&
				(($_SESSION["Activite_id"] == 12) || // Cathéchèse
				($_SESSION["Activite_id"] == 26))) { // Aumônerie Lycée et collège
				echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Classe'].' </FONT></TD>';
			}
		}
		echo '</TR>'; 
	}
	echo '</TABLE><BR></FONT>';
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="RenseignerParticipation") {
//if ($action == "RenseignerParticipation") { 
   // Participation financière
   
	// $Qui			-> "Participant"
	// $Qui_id		-> Individu[id]
	// $Invite_id	-> Engagement_id
	Global $eCOM_db;
	$debug = false;
	if (!isset($_GET['Qui'])) { $Qui = 0; } else {$Qui = $_GET['Qui'];}
	if (!isset($_GET['Qui_id'])) { $Qui_id = 0; } else {$Qui_id = $_GET['Qui_id'];}
	if (!isset($_GET['Invite_id'])) { $Invite_id = 0; } else {$Invite_id = $_GET['Invite_id'];}
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Qui = '.$Qui.'<BR>');
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Qui_id = '.$Qui_id.'<BR>');
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Invite_id = '.$Invite_id.'<BR>');

	// 2 cas de figure, soit il existe déjà une fiche d'engagement dans QuiQuoi, soit pas
	$Activite_id = $_SESSION["Activite_id"];
	$QuiQuoi_id = 0;
	$requete = 'SELECT T0.`id`, T0.`Participation` AS ParticipationF, T0.`Detail` AS Classe, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.Ecole_id,
				MID(T0.`Detail_02`,1,1) AS Demande_Bapteme, MID(T0.`Detail_02`,2,1) AS Demande_1ereCommunion, MID(T0.`Detail_02`,3,1) AS CertificatBapteme, MID(T0.`Detail_02`,4,1) AS SituationFamiliale, MID(T0.`Detail_02`,5,1) AS Demande_Profession, MID(T0.`Detail_02`,6,1) AS Demande_Confirmation, T1.`Bapteme` AS DateBapteme, T1.`Communion` AS DateCommunion, T1.`ProfessionFoi` AS DateProfession, T1.`Confirmation` AS DateConfirmation
				FROM QuiQuoi T0 
				LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id`
				WHERE T1.`id`='.$Qui_id.' AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Activite_id`='.$Activite_id;
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Requete01 = '.$requete);
	$num_total = mysqli_num_rows($result);
	if ( $num_total == 0){
		$requete = 'SELECT id, Prenom, Nom FROM Individu WHERE id='.$Qui_id;
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Requete02 = '.$requete.'<BR>');
		$result = mysqli_query($eCOM_db, $requete);
		$row = mysqli_fetch_assoc($result);
		$_Classe = "";
		$_ParticipationF = 0; // Participation financière
		$QuiQuoi_id = 0;
		$_Ecole = "";
		$_Demande_Bapteme = 0;
		$_Demande_Communion = 0;
		$_Demande_Profession = 0;
		$_Demande_Confirmation = 0;
		$_CertificatBapteme = 0;
		$_SituationFamiliale = 0;
		$_DateBapteme = "";
		$_DateCommunion = "";
		$_DateProfession = "";
		$_DateConfirmation = "";
	} else {
		$row = mysqli_fetch_assoc($result);
		$_Classe = $row['Classe'];;
		$_ParticipationF = $row['ParticipationF']; // Participation financière
		$QuiQuoi_id = $row['id'];
		$_Ecole = fCOM_Get_Ecole($row['Ecole_id']);
		$_Demande_Bapteme = $row['Demande_Bapteme'];
		$_Demande_Communion = $row['Demande_1ereCommunion'];
		$_Demande_Profession = $row['Demande_Profession'];
		$_Demande_Confirmation =$row['Demande_Confirmation'];
		$_CertificatBapteme = $row['CertificatBapteme'];
		$_SituationFamiliale = $row['SituationFamiliale'];
		if ($row['DateBapteme'] != "0000-00-00"){
			$_DateBapteme = date("d/m/Y", strtotime($row['DateBapteme']));
		} else {
			$_DateBapteme = "";
		}
		if ($row['DateCommunion'] != "0000-00-00"){
			$_DateCommunion = date("d/m/Y", strtotime($row['DateCommunion']));
		} else {
			$_DateCommunion = "";
		}
		if ($row['DateProfession'] != "0000-00-00"){
			$_DateProfession = date("d/m/Y", strtotime($row['DateProfession']));
		} else {
			$_DateProfession = "";
		}		
		if ($row['DateConfirmation'] != "0000-00-00"){
			$_DateConfirmation = date("d/m/Y", strtotime($row['DateConfirmation']));
		} else {
			$_DateConfirmation = "";
		}
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateBapteme = '.$row['DateBapteme']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateCommunion = '.$row['DateCommunion']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateProfession = '.$row['DateProfession']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateConfirmation = '.$row['DateConfirmation']);
		}
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - QuiQuoi_id = '.$QuiQuoi_id);

	address_top();
	
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Edition: ';
	if ( $num_total == 0) {
		echo 'Nouveau Participant</B></FONT></TD>';
	} else {
		echo 'Fiche No '.$row['id'].'</B></FONT></TD>'; 
	}
	echo '</TR>';
	echo '<TR>';
	echo '<TD BGCOLOR="#EEEEEE" Colspan="2">';
	echo '<CENTER><font face="verdana" size="2">';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<TR><TD width="140" bgcolor="#eeeeee">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'"><B><FONT SIZE="3"> </FONT></B></TD></TR>';
	
	// Nom et Prénom
	echo '<TR><TD width=250><b><FONT SIZE="2">Nom :</FONT></b></TD>';
	echo '<TD><FONT SIZE="2">'.$row['Nom'].' '.$row['Prenom'].'</FONT></TD><TD></TD></TR>';
	
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
	    ($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TR><TD width=90 ><B><FONT SIZE="2">Situation Familiale:</FONT></B></TD>';
		echo '<TD>';
		$liste_SituationFamiliale = fCOM_Get_Liste_SituationFamiliale();
		echo '<SELECT name="SituationFamiliale">';
		$i=0;
		foreach ($liste_SituationFamiliale as $SituationFamiliale){
			$SelectionDefault = '';
			if ($_SituationFamiliale == sprintf('%s',$i)) {
					$SelectionDefault = ' selected="selected"';
			}
			echo '<option value="'.$i.'"'.$SelectionDefault.'>'.$SituationFamiliale.'</option>';
			$i = $i + 1;
		}
		echo '</SELECT></TD></TR>';
		
		// Ecole =============================================
		echo '<TR><TD width=90 ><B><FONT SIZE="2">Ecole / Classe:</FONT></B></TD><TD colspan="2">';
		$liste_ecoles = fCOM_Get_liste_ecoles();
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Size liste_ecoles = '.count($liste_ecoles));

		//if ( count($liste_ecoles) > 0 ) {
			echo '<SELECT name="EcoleSelection">';
			foreach ($liste_ecoles as $Ecole){
				list($Ecole_id, $Ecole_nom) = $Ecole;
				$SelectionDefault = '';
				if ($_Ecole == $Ecole_nom) {
					$SelectionDefault = ' selected="selected"';
				}
				echo '<option value="'.$Ecole_id.'"'.$SelectionDefault.'>'.$Ecole_nom.'</option>';
			}
			echo '</SELECT>';
		//}
		echo '&nbsp<input type=text name="Autre_Ecole" placeholder="Nouvelle Ecole à déclarer dans la base" size="35" maxlength="40">';

		
		// Classe ============================================
		echo '<BR>';
		$Liste_Classe = array(" ");
		if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
			$Liste_Classe[1]="6ème";
			$Liste_Classe[2]="5ème";
			$Liste_Classe[3]="4ème";
			$Liste_Classe[4]="3ème";
			$Liste_Classe[5]="2nd";
			$Liste_Classe[6]="1ère";
			$Liste_Classe[7]="Term";
		} else if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
			$Liste_Classe[1]="Mat";
			$Liste_Classe[2]="CP";
			$Liste_Classe[3]="CE1";
			$Liste_Classe[4]="CE2";
			$Liste_Classe[5]="CM1";
			$Liste_Classe[6]="CM2";
			$Liste_Classe[7]="6ème";
		}
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Size Liste_Classe = '.count($Liste_Classe));
		echo '<SELECT name="ClasseVal">';
		for ($i = 0, $size = count($Liste_Classe)-1; $i <= $size; $i++) {
			pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Liste_Classe = "'.$Liste_Classe[$i].'"');
			pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - row[Classe] = "'.$_Classe.'"');
			if (strpos($_Classe, $Liste_Classe[$i]) === False) {
				echo '<option value="'.$Liste_Classe[$i].'">'.$Liste_Classe[$i].'</option>';
			} else {
				echo '<option value="'.$Liste_Classe[$i].'" selected="selected">'.$Liste_Classe[$i].'</option>';

			}
		}
		echo '</SELECT>';
		echo '</TD></TR>';
	}
	
	// Sacrement demandé ============================================
	echo '<TR><TD width=90 valign="top"><B><FONT SIZE="2">Sacrement demandé:</FONT></B></TD>';
	echo '<TD>';
	if ($_Demande_Bapteme == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '<input type="checkbox" name="Demande_Bapteme" id="Demande_Bapteme" '.$optionSelect.' > <LABEL for="Demande_Bapteme"><FONT SIZE="2">Baptême</b></LABEL></TD>';
	echo '<TD>';
	echo '<FONT SIZE="2"> reçu le ';
	echo '<input type=text id="DateBapteme" name="DateBapteme" placeholder="JJ/MM/AAAA" style="width:75px" value ="'.$_DateBapteme.'" size="8" maxlength="10"></FONT>';
	?><a href="javascript:popupwnd('calendrier.php?idcible=DateBapteme&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span><?php
	if ($_CertificatBapteme == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '&nbsp<input type="checkbox" name="CertificatBapteme" id="CertificatBapteme" '.$optionSelect.' > <label for="CertificatBapteme"><FONT SIZE="2"> Certificat de Baptême<BR></b></label>';
	echo '</TD></TR>';
	
	echo '<TR><TD></TD><TD>';
	if ($_Demande_Communion == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '<input type="checkbox" name="Demande_Communion" id="Demande_Communion" '.$optionSelect.' > <LABEL for="Demande_Communion"><FONT SIZE="2">1ère Communion</b></LABEL></TD>';
	echo '<TD>';
	echo '<FONT SIZE="2"> reçu le ';
	echo '<input type=text id="DateCommunion" name="DateCommunion" placeholder="JJ/MM/AAAA" style="width:75px" value ="'.$_DateCommunion.'" size="8" maxlength="10"></FONT>';
	?><a href="javascript:popupwnd('calendrier.php?idcible=DateCommunion&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span><?php
	
	echo '</TD></TR>';
	
	if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		echo '<TR><TD></TD><TD>';
		if ($_Demande_Profession == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="Demande_Profession" id="Demande_Profession" '.$optionSelect.' > <LABEL for="Demande_Profession"><FONT SIZE="2">Profession de Foi </b></LABEL></TD>';
		echo '<TD>';
		echo '<FONT SIZE="2"> reçu le ';
		echo '<input type=text id="DateProfession" name="DateProfession" placeholder="JJ/MM/AAAA" style="width:75px" value ="'.$_DateProfession.'" size="8" maxlength="10"></FONT>';
		?><a href="javascript:popupwnd('calendrier.php?idcible=DateProfession&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span><?php
		
		echo '<TR><TD></TD><TD>';
		if ($_Demande_Confirmation == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="Demande_Confirmation" id="Demande_Confirmation" '.$optionSelect.' > <LABEL for="Demande_Confirmation"><FONT SIZE="2">Confirmation </b></LABEL></TD>';
		echo '<TD>';
		echo '<FONT SIZE="2"> reçu le ';
		echo '<input type=text id="DateConfirmation" name="DateConfirmation" placeholder="JJ/MM/AAAA" style="width:75px" value ="'.$_DateConfirmation.'" size="8" maxlength="10"></FONT>';
		?><a href="javascript:popupwnd('calendrier.php?idcible=DateConfirmation&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span><?php
		
	}
	
	// Participation financière ============================================
	echo '<TR><TD width=90><B><FONT SIZE="2">Participation Financière (Euro):</FONT></B></TD>';
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - ParticipationFinance = '.$_ParticipationF);
	echo '<TD bgcolor="#eeeeee"><input type=text name="ParticipationF" placeholder="00" value ="'.$_ParticipationF.'" size="9" maxlength="9" '.$BloquerAcces.'></TD><TD></TD></TR>';
	
	echo '<TR><TD></TD></TR>';
	echo '<TR><TD colspan=2>';
	echo '<INPUT type=hidden name=Qui value='.$Qui.'>';
	echo '<INPUT type=hidden name=Qui_id value='.$Qui_id.'>';
	echo '<INPUT type=hidden name=QuiQuoi_id value='.$QuiQuoi_id.'>';
	echo '<INPUT type=hidden name=Invite_id value='.$Invite_id.'>';
	
	echo '<div align="center"><INPUT type="submit" name="SauvegarderParticipation" value="Enregistrer">';
	echo '<input type="reset" name="Reset" value="Reset">';
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
		echo '<INPUT type="submit" name="retirer_fiche_Participant" value="Retirer ce participant">';
	}
	
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR>';
	echo '</TABLE>';
	echo '</FORM>';
	echo '</CENTER>';

	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_POST['SauvegarderParticipation'] ) AND $_POST['SauvegarderParticipation']=="Enregistrer") {
//if ($SauvegarderParticipation) {
	Global $eCOM_db;
	$debug = False;
	
	$Ecole_Id = 0;
	if ( isset($_POST['EcoleSelection']) ) { 
		$Ecole_Id = $_POST['EcoleSelection'];
	}
	
	if ( ! isset($_POST['Autre_Ecole']) ) { $Autre_Ecole = "";} else {$Autre_Ecole = trim($_POST['Autre_Ecole']);}
	if ( ! isset($_POST['ClasseVal']) )   { $ClasseVal = "";  } else {$ClasseVal = $_POST['ClasseVal'];}
	if ( ! isset($_POST['ParticipationF'])) 
		{$ParticipationF = 0;} else {$ParticipationF = $_POST['ParticipationF'];}
	if (!isset($_POST['SituationFamiliale'])){
		$SituationFamiliale = 0;
	} else {
		$SituationFamiliale=$_POST['SituationFamiliale'];
	}
	if ( !isset($_POST['Demande_Bapteme']) ) {
		$Demande_Bapteme = '0';
	} elseif ($_POST['Demande_Bapteme'] == "on") {
		$Demande_Bapteme = '1';
	} else {
		$Demande_Bapteme = '0';
	}
	
	if ( !isset($_POST['Demande_Communion']) ) {
		$Demande_Communion = '0';
	} elseif ($_POST['Demande_Communion'] == "on") {
		$Demande_Communion = '1';
	} else {
		$Demande_Communion = '0';
	}
	
	if ( !isset($_POST['CertificatBapteme']) ) {
		$CertificatBapteme = '0';
	} elseif ($_POST['CertificatBapteme'] == "on") {
		$CertificatBapteme = '1';
	} else {
		$CertificatBapteme = '0';
	}
	$Demande_option = $Demande_Bapteme.$Demande_Communion.$CertificatBapteme.$SituationFamiliale;
	
	if ($Autre_Ecole != "" AND $Ecole_Id == 0) {
		mysqli_query($eCOM_db, 'INSERT INTO Ecoles (id, Nom) VALUES (0, "'.$Autre_Ecole.'")') or die (mysqli_error($eCOM_db));
		$Ecole_Id=mysqli_insert_id($eCOM_db);

	}

	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Demande_Bapteme = '.$Demande_Bapteme);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Demande_Communion = '.$Demande_Communion);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - CertificatBapteme = '.$CertificatBapteme);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Session = '.$_SESSION["Session"]);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Ecole_id = '.$Ecole_Id);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Demande_option = '.$Demande_option);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - ClasseVal = '.$ClasseVal);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - ParticipationF = '.$ParticipationF);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Invite_id = '.$_POST['Invite_id']);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Activite_id = '.$_SESSION["Activite_id"]);
	pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - Qui_id = '.$_POST['Qui_id']);
	
	if ($_POST['QuiQuoi_id'] == 0) {
		mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Participation, Detail, Detail_02, Ecole_id, Session) VALUES ('.$_POST['Qui_id'].','.$_SESSION["Activite_id"].','.$_POST['Invite_id'].', 1,'.$ParticipationF.',"'.$ClasseVal.'","'.$Demande_option.'",'.$Ecole_Id.',"'.$_SESSION["Session"].'")') or die (mysqli_error($eCOM_db));

	} else {
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Ecole_id="'.$Ecole_Id.'" WHERE id='.$_POST['QuiQuoi_id'].' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail="'.$ClasseVal.'" WHERE id='.$_POST['QuiQuoi_id'].' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Detail_02="'.$Demande_option.'" WHERE id='.$_POST['QuiQuoi_id'].' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Participation='.$ParticipationF.' WHERE id='.$_POST['QuiQuoi_id'].' ') or die (mysqli_error($eCOM_db));
	}
	
	if ( isset($_POST['DateBapteme'])) {
		pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - DateBapteme = '.$_POST['DateBapteme']);
		if ($_POST['DateBapteme'] != "") {
			$DateBapteme = substr(fCOM_getSqlDate($_POST['DateBapteme'],0,0,0), 0, 10);
			$requete = 'SELECT * FROM Individu WHERE id='.$_POST['Qui_id'].' AND Bapteme='.$DateBapteme.'';
			$result = mysqli_query($eCOM_db, $requete);
			if (mysqli_num_rows($result) == 0) {
				mysqli_query($eCOM_db, 'UPDATE Individu SET Bapteme="'.$DateBapteme.'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
			}
		}
	}
	
	if ( isset($_POST['DateCommunion'])) {
		pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - DateCommunion = '.$_POST['DateCommunion']);
		if ($_POST['DateCommunion'] != "") {
			$DateCommunion = substr(fCOM_getSqlDate($_POST['DateCommunion'],0,0,0), 0, 10);
			$requete = 'SELECT * FROM Individu WHERE id='.$_POST['Qui_id'].' AND Bapteme='.$DateCommunion.'';
			$result = mysqli_query($eCOM_db, $requete);
			if (mysqli_num_rows($result) == 0) {
				mysqli_query($eCOM_db, 'UPDATE Individu SET Communion="'.$DateCommunion.'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
			}
		}
	}  
	
}



if ( isset( $_POST['Selectionner_Individue'] ) AND ( $_POST['Selectionner_Individue']=="Participant(s)" OR $_POST['Selectionner_Individue']=="Accompagnateur(s)" )) {
//if ($Selectionner_Individue) {
	$retour = Sauvegarder_fiche_invite ( );

	$Label=$_POST['Selectionner_Individue'];
	$Selectionner_Individue = $_POST['Selectionner_Individue'];
	
	if ($Selectionner_Individue == "Invité" ) {
		$Selectionner_Individue = "Invite";
		$Label = "L'invité";
	} elseif ($Selectionner_Individue == "Le Baptisé" ) {
		$Selectionner_Individue = "Baptise";
		$Label = "le Baptisé";
	} elseif ($Selectionner_Individue == "Célébrant" ) {
		$Selectionner_Individue = "Celebrant";
		$Label = "le Célébrant";
	} elseif ($Selectionner_Individue == "Parrain Marraine" ) {
		$Selectionner_Individue = "Parrain";
		$Label = "le Parrain / Marraine";
	} elseif ($Selectionner_Individue == "Participant" ) {
		$Selectionner_Individue = "Participant";
		$Label = "Invité";
	} elseif ($Selectionner_Individue == "Participant(s)" ) {
		$Selectionner_Individue = "Participant";
		$Label = "Invité";
	} elseif ($Selectionner_Individue == "Accompagnateur(s)" ) {
		$Selectionner_Individue = "Accompagnateur";
		//$Label = "Invité";
	}
	$Inscription = False;
	Selectionner_individu_BaseFraternite($Selectionner_Individue, $Label, $Inscription, $_POST['Invite_id']);
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="AffecterBaseFraternite") {
//if ($action == "AffecterBaseFraternite") { 
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 & $_GET['Invite_id'] > 0) {
		$Qui = stripAccents($_GET['Qui']);
		$Activite_id=$_SESSION["Activite_id"];
		if ($Qui == "Parrain") {
			mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES ('.$_GET['Qui_id'].','.$Activite_id.','.$_GET['Invite_id'].',3, ".$_SESSION["Session"].")') or die (mysqli_error($eCOM_db));
		} elseif ($Qui == "Accompagnateur") {
			mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES ('.$_GET['Qui_id'].','.$Activite_id.','.$_GET['Invite_id'].',2, '.$_SESSION["Session"].')') or die (mysqli_error($eCOM_db));
		} elseif ($Qui == "Participant") {
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				// si on arrive ici, c'est que la fiche QuiQuoi est déjà créé, if faut en modifier les champs
				// rechercher le QuiQuoi_id
				$QuiQuoi_id = 0;
				$requete = 'SELECT T0.`id`, T0.`Participation`, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom
							FROM QuiQuoi T0 
							LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id`
							WHERE T1.`id`='.$_GET['Qui_id'].' AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Activite_id`='.$_SESSION["Activite_id"].' ';
				error_log('Fraternite:AffecterBaseFraternite - requete='.$requete);
				
				$result = mysqli_query($eCOM_db, $requete);
				$row = mysqli_fetch_assoc($result);
				$QuiQuoi_id = $row['id'];
				// renseigner l'engagement
				mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Engagement_id='.$_GET['Invite_id'].' WHERE id='.$QuiQuoi_id.' ') or die (mysqli_error($eCOM_db));
			} else{
				mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES ('.$_GET['Qui_id'].','.$Activite_id.','.$_GET['Invite_id'].',1, '.$_SESSION["Session"].')') or die (mysqli_error($eCOM_db));
			}
		} else {
			mysqli_query($eCOM_db, 'UPDATE Fraternite SET '.$Qui.'_id='.$_GET['Qui_id'].' WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
		// mysqli_query($eCOM_db, 'UPDATE Individu SET '".$Qui."'_id='".$Qui_id."' WHERE id='.$Enfant.' ') or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?action=edit&id='.$_GET['Invite_id'].'">';
	mysqli_close($eCOM_db);
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerParticipant") {
//if ($action == "RetirerParticipant") {
	Global $eCOM_db;
	$debug=False;
	if ($_GET['Qui_id'] > 0 & $_GET['Invite_id'] > 0) {
		pCOM_DebugAdd($debug, 'Fraternite:RetirerParticipant - requete = Delete FROM QuiQuoi WHERE Individu_id='.$_GET['Qui_id'].' AND Activite_id='.$_SESSION["Activite_id"].' AND Engagement_id='.$_GET['Invite_id'].' AND QuoiQuoi_id=1');
		mysqli_query($eCOM_db, 'Delete FROM QuiQuoi WHERE Individu_id='.$_GET['Qui_id'].' AND Activite_id='.$_SESSION["Activite_id"].' AND Engagement_id='.$_GET['Invite_id'].' AND QuoiQuoi_id=1 ')or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerParticipantDeFraternite") {
//if ($action == "RetirerParticipantDeFraternite") {
	Global $eCOM_db;
	// cette fonction est appelée pour les cas d'inscription avant de définir les fraternités
	if ($_GET['Qui_id'] > 0 & $_GET['Invite_id'] > 0) {
		$Activite_id=$_SESSION["Activite_id"];
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Engagement_id=0 WHERE Individu_id='.$_GET['Qui_id'].' and Activite_id='.$Activite_id.' AND Session='.$_SESSION["Session"].' AND Engagement_id='.$_GET['Invite_id'].' and QuoiQuoi_id=1 ')or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}



if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerAccompagnateur") {
//if ($action == "RetirerAccompagnateur") {
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 & $_GET['Invite_id'] > 0) {
		$Activite_id=$_SESSION["Activite_id"];
		mysqli_query($eCOM_db, 'Delete FROM QuiQuoi WHERE Individu_id='.$_GET['Qui_id'].' and Activite_id='.$Activite_id.' and Engagement_id='.$_GET['Invite_id'].' and QuoiQuoi_id=2 ')or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="vue_financiere") {
//if ($action == "vue_financiere") {
	Global $eCOM_db;
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Participation`
				FROM `QuiQuoi` T0 
				LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
				LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
				LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
				WHERE T0.`Activite_id`="'.$_SESSION["Activite_id"].'" AND T0.`QuoiQuoi_id`="1" '.$ComplementRequete.'
				ORDER BY T1.`Prenom`, T1.`Nom` ';
	address_top();

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Liste des Invités</B><BR>';
	} else {
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Liste des Inscrits</B><BR>';
	}
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>No Frat</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Nom</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Adresse</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Participation</FONT></TH>';
	$Somme_Total = 0;
	$resultat = mysqli_query($eCOM_db,  $requete );
	while( $row = mysqli_fetch_assoc( $resultat ))
	{
		$trcolor = usecolor();
		echo '<TR>';
		if ($row['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };
		echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2 color='.$fgcolor.'>'.$row['NoFrat'].'</FONT></TD>';
		echo '<TD align="left" bgcolor='.$trcolor.'><FONT face=verdana size=2 color='.$fgcolor.'>';
		Display_Photo($row['Nom'], $row['Prenom'], $row['Individu_id'], 2);
		echo '</FONT></TD>';
		echo '<TD align="left" bgcolor='.$trcolor.'><FONT face=verdana size=2 color='.$fgcolor.'>'.$row['Telephone'].'<BR>'.$row['e_mail'].'</FONT></TD>';
		echo '<TD align="right" bgcolor='.$trcolor.'><FONT face=verdana size=2 color='.$fgcolor.'>'.$row['Participation'].'</FONT></TD>';
		$Somme_Total = $Somme_Total +$row['Participation'];
		echo '</TR>';
	}
	$trcolor = usecolor();
	echo '<TR><TD bgcolor='.$trcolor.'></TD><TD  align="right" bgcolor='.$trcolor.'></TD><TD align="right" bgcolor='.$trcolor.'><FONT face=verdana size=2><B>Total is </B></FONT></TD><TD align="right" bgcolor='.$trcolor.'><FONT face=verdana size=2><B>'.sprintf("%01.2f",$Somme_Total).'</B></FONT></TD></TR>';
	echo '</TABLE>';
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="trombinoscope") {
	Global $eCOM_db;
	$debug = false;
	address_top();
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Trombinoscope de l\'Aumônerie collège et lycée</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<TABLE>';
	
	$Activite_id = 26; // Aumônerie Lycée et collège
	$criteria = "T0.`Detail`";
	$order = "DESC";
	$ComplementRequete = ' AND MID(T0.`Session`,1,4)="'.$_SESSION["Session"].'" ';

	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Detail` AS Classe, T4.`e_mail` As AdressPere, Concat(T4.`e_mail`, " ", T5.`e_mail`) AS ParentAddress
		FROM `QuiQuoi` T0 
		LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
		LEFT JOIN `Individu` T4 on T4.`id`=T1.`Pere_id`
		LEFT JOIN `Individu` T5 on T5.`id`=T1.`Mere_id`
		LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
		LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 '.$ComplementRequete.'
		ORDER BY '.$criteria.' '.$order.' ';
	$resultat = mysqli_query($eCOM_db,  $requete );
	$compteur = 0;
	$MemoClasse= "";
	while( $row = mysqli_fetch_assoc( $resultat )) {
		if ($compteur > 5 OR $MemoClasse != $row['Classe']) {
			echo "</TR><TR>";
			$compteur = 0;
			$MemoClasse = $row['Classe'];
		}
		$compteur = $compteur + 1;
					
		echo '<TD valign="top"><A HREF='.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$row['Individu_id'].'>';	
		if (file_exists("Photos/Individu_".$row['Individu_id'].".jpg")) { 
			echo '<IMG SRC="Photos/Individu_'.$row['Individu_id'].'.jpg" HEIGHT=150 border="1"></A>';		
		} else {
			echo '<IMG SRC="Photos/Individu_NULL.jpg" HEIGHT=150 border="1"></A>';
		}
		echo "<BR><FONT face=verdana size=2>".$row['Prenom']." ".$row['Nom']."</FONT><BR>";
		if ($row['Classe'] == "" ) {
			echo "<FONT face=verdana size=1>La classe n'est pas renseignée</FONT></TD>";
		} else {
			echo "<FONT face=verdana size=2>classe de ".$row['Classe']."</FONT></TD>";
		}
	}
	echo "</TR></TABLE>";
	fCOM_address_bottom();
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

function personne_list ($resultat, $Classe_order, $order) {
	Global $eCOM_db;
	global $debug;
	$debug = false;
	require("Login/sqlconf.php");
	address_top(); 

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Liste des Invités</B><BR>';
	} else {
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Liste des Inscrits</B><BR>';
	}
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 22) || // Emmaüs
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2></font></TH>';
	}
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=NoFrat&order='.$order.'">No Frat</A></font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Prenom&order='.$order.'">Prénom</A></font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Nom&order='.$order.'">Nom</A></font></TH>';
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=T0.`Detail`&order='.$order.'">Jour</A></font></TH>';
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Lieu&order='.$order.'">Lieu</A></font></TH>';
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=T0.`Detail`&order='.$order.'">Ecole</A></font></TH>';
	}	
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=T0.`Detail`&order='.$order.'">Classe</A></font></TH>';
	}
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Tel / e_Mail ';
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<A HREF="load/ListeMail_Parents.php">Parents</A>';
		setlocale(LC_TIME, "fr_FR");
		$temp_Parent = "load/ListeMail_Parents.php";
		$handle_Parent = fopen($temp_Parent, 'w');
		fwrite($handle_Parent, "<HTML><HEAD><TITLE>Liste adresses mail des parents</TITLE></HEAD><BODY><br>");
		$TreatedName = ""; // mise à zéro pour éviter erreur
		fwrite($handle_Parent, "<h1><FONT face=verdana>Liste des adresses mail des parents : ".$TreatedName."</FONT></h1>\r\n");
		fwrite($handle_Parent, "<FONT face=verdana size=2>");
		fwrite($handle_Parent, "<p>Date : ".ucwords(strftime("%A %x %X",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))))."</p>");
		fwrite($handle_Parent, "<p>===================================================</p><br><TABLE>");
		fwrite($handle_Parent, "<FONT face=verdana size=2>");
	}
	echo '</font></TH>';
	
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Adresse</font></TH>';
	//echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Accompagnateur&order='.$order.'">Accompagnateurs</A></font></TH>';
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Catéchiste</font></TH>';
	} else {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Accompagnateurs</font></TH>';
	}
	if ($_SESSION["Session"]=="All") {
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Session&order='.$order.'">Session</A></font></TH>';
	}
	if ($_SESSION["Activite_id"] != 12) { // Cathéchèse, car déjà printer
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Lieu&order='.$order.'">Lieu</A></font></TH>';
	}
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Date&order='.$order.'">Date</A></font></TH>';
	}
	
	$debug = true;
	pCOM_DebugInit($debug);
	
	$Memo_Classe="NULL";
	$Activite_id=$_SESSION["Activite_id"];
	while( $enregistrement = mysqli_fetch_assoc( $resultat ))
	{
		$debug = False;
  
			$trcolor = usecolor();
			echo '<TR>';
		
			// $Qui			-> "Participant"
			// $Qui_id		-> Individu[id]
			// $Invite_id	-> Engagement_id
			if ($_SESSION["Activite_id"] == 26 AND $Classe_order == TRUE AND $Memo_Classe != $enregistrement['Classe']) { // Aumônerie Lycée et collège
				if ( $Memo_Classe != "NULL" ) {
					fclose($handle_ParentClasse);
					fclose($handle_EnfantClasse);
				}
				$Memo_Classe = $enregistrement['Classe'];
				echo '<TR>';
				echo '<TD align="left" bgcolor="#A1A1A1" colspan=9><font face=verdana size=2>Enfants classe de '.$Memo_Classe.' -> E_mail <A HREF="load/ListeMail_Parents'.fCOM_stripAccents($enregistrement['Classe']).'.php">Parents</A> <A HREF="load/ListeMail_Enfant'.fCOM_stripAccents($enregistrement['Classe']).'.php">Enfants</A></font>';
				echo '</TD></TR>';
				
				// Parent Classe
				$temp_ParentClasse = "load/ListeMail_Parents".fCOM_stripAccents($enregistrement['Classe']).".php";
				$handle_ParentClasse = fopen($temp_ParentClasse, 'w');				
				fwrite($handle_ParentClasse, "<HTML><HEAD><TITLE>Liste adresses mail des parents</TITLE></HEAD><BODY><BR>");
				$TreatedName = ""; // mise à zéro pour éviter erreur
				fwrite($handle_ParentClasse, "<h1><FONT face=verdana>Liste des adresses mail des parents<BR>enfants de la classe de : ".$enregistrement['Classe']."</FONT></h1>");
				fwrite($handle_ParentClasse, "<FONT face=verdana size=2>");
				fwrite($handle_ParentClasse, "<p>Date : ".ucwords(strftime("%A %x %X",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))))."</p>\r\n");
				fwrite($handle_ParentClasse, "<p>===================================================</p><br><TABLE>");
				fwrite($handle_ParentClasse, "<FONT face=verdana size=2>");
				
				// Enfant Classe
				$temp_EnfantClasse = "load/ListeMail_Enfant".fCOM_stripAccents($enregistrement['Classe']).".php";
				$handle_EnfantClasse = fopen($temp_EnfantClasse, 'w');				
				fwrite($handle_EnfantClasse, "<HTML><HEAD><TITLE>Liste adresses mail des enfants</TITLE></HEAD><BODY><br>");
				$TreatedName = ""; // mise à zéro pour éviter erreur
				fwrite($handle_EnfantClasse, "<h1><FONT face=verdana>Liste des adresses mail<BR>enfants de la classe de : ".$enregistrement['Classe']."</FONT></h1>");
				fwrite($handle_EnfantClasse, "<FONT face=verdana size=2>");
				fwrite($handle_EnfantClasse, "<p>Date : ".ucwords(strftime("%A %x %X",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))))."</p>");
				fwrite($handle_EnfantClasse, "<p>===================================================</p><br><TABLE>");
				fwrite($handle_EnfantClasse, "<FONT face=verdana size=2>");
			}

			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				
				echo '<TD align="center" bgcolor='.$trcolor.'>';
				echo ' <A HREF='.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui_id='.$enregistrement['Individu_id'].'&Qui=Participant TITLE="Modifier Inscription"><img src="images/edit.gif": border=0 alt="Modifier participation financière">';
				echo '<em><span></span>';
				echo "</em></A></TD>";
			}
			
			// No frat
			//--------
			if ($enregistrement['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };

			if ($enregistrement['NoFrat'] == "") {
				$FratNum="-";
			} else {
				$FratNum=$enregistrement['NoFrat'];
			}
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				echo '<TD align="center" bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'><A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['Frat_id'].'>'.$FratNum.'</font></A></TD>';
			} else {
				echo '<TD align="center" bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'><A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].'>'.$FratNum.'</font></A></TD>';
			}
			
			// Prénom
			//--------
			if ($enregistrement['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };
			
			//echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Prenom'].'</font></TD>';
			echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>';
			Display_Photo("", $enregistrement['Prenom'], $enregistrement['Individu_id'], 2);
			echo '</font></TD>';
			
			// Nom
			//--------
			echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Nom'].'</font></TD>';
			
			// Lieux
			//-------
			if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.fCOM_Get_JourSemaine($enregistrement['Jour']).'</font></TD>';
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Lieu'].'</font></TD>';
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Ecole'].'</font></TD>';
			}
			
			// Classe
			//--------
			$fgcolor = "black";
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Classe'].'<BR>';
			}
			
			// Telephone / Mail
			//-----------------
			echo '<TD width=150 bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Telephone'].'<BR>';
			echo '<A HREF="mailto:'.$enregistrement['e_mail'].'?subject= Paroisse ND Sagesse : " TITLE="Envoyer un mail a '.$enregistrement['Prenom'].' '.$enregistrement['Nom'].'">'.$enregistrement['e_mail'].'</A></font></TD>';
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				if ( $enregistrement['ParentAddress'] != "" ) {
					fwrite($handle_Parent, '"Parents de '.Securite_html($enregistrement['Prenom']).' '.Securite_html($enregistrement['Nom']).'"< '.format_email_list(Securite_html($enregistrement['ParentAddress']), ">;< ").'>; ');
					if ( $Memo_Classe != "NULL" ) {
						fwrite($handle_ParentClasse, '"Parents de '.Securite_html($enregistrement['Prenom']).' '.Securite_html($enregistrement['Nom']).'"< '.format_email_list(Securite_html($enregistrement['ParentAddress']), ">;< ").'>; ');

					}
				}
				if ( $enregistrement['e_mail'] != "" ) {
					$debug=true;
					if ( $Memo_Classe != "NULL" ) {
						pCOM_DebugAdd($debug, "Fraternité:liste Enfant=".$enregistrement['Prenom']." ".$enregistrement['Nom']);
						fwrite($handle_EnfantClasse, '"'.$enregistrement['Prenom'].' '.$enregistrement['Nom'].'"< '.format_email_list($enregistrement['e_mail'], ">;< ").'>; ');
						//fwrite($handle_EnfantClasse, '"'.$enregistrement['Nom'].'"< '.format_email_list($enregistrement['e_mail'], ">;< ").'>; ');
					}
				}
			}
			//echo '<font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['e_mail'].'</font></TD>';

			// Adresse
			//--------
			echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2 color='.$fgcolor.'>'.$enregistrement['Adresse'].'</font></TD>';
	
			// Chercher les accompagnateurs
			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				if ( $enregistrement['Frat_id'] > 0 ) {
					$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom`, T1.`Sex`, T1.`e_mail`, T1.`Adresse`, T1.`Telephone` 
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T1.`id` = T0.`Individu_id` 
							WHERE T0.`Activite_id`='.$Activite_id.' and T0.`Engagement_id`='.$enregistrement['Frat_id'].' and T0.`QuoiQuoi_id`=2 
							ORDER BY T1.`Nom`, T1.`Sex` DESC';
				}
			} else {
				$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom`, T1.`Sex`, T1.`e_mail`, T1.`Adresse`, T1.`Telephone`
						FROM `QuiQuoi` T0 
						LEFT JOIN `Individu` T1 ON T1.`id` = T0.`Individu_id` 
						WHERE T0.`Activite_id`='.$Activite_id.' and T0.`Engagement_id`='.$enregistrement['id'].' and T0.`QuoiQuoi_id`=2 
						ORDER BY T1.`Nom`, T1.`Sex` DESC';
			}
			//debug($requete . "<BR>\n");
			if ($enregistrement['Frat_id'] > 0) {
				$resultat2 = mysqli_query($eCOM_db,  $requete );
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=2>';
				$Nom = "@@@";
				while( $Row_Accomp = mysqli_fetch_assoc( $resultat2))
				{
					if ($Nom == $Row_Accomp['Nom']) {
						echo " et ".$Row_Accomp['Prenom']."";
					} else {
						if ($Nom != "" && $Nom != "@@@") {echo "<br>";}
						echo "".$Row_Accomp['Nom']." ".$Row_Accomp['Prenom']."";
						$Nom = $Row_Accomp['Nom'];
					}
				}
				echo '</font></TD>';
			} else {
				echo '<TD bgcolor='.$trcolor.'></TD>';
			}

			// Affichage de la session
			if ($_SESSION["Session"]=="All") {
				//echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$enregistrement[Session].' '.$enregistrement[SS_Session].' </FONT></TD>';
			//} elseif ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
			//	echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$enregistrement[SS_Session].' </FONT></TD>';
			//} else {
				echo '<TD align="center" bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$enregistrement['Session'].' </FONT></TD>';
			}
		
			if ($_SESSION["Activite_id"] != 12) { // Cathéchèse
				echo '<TD width=110 bgcolor='.$trcolor.'><font face=verdana size=2>'.$enregistrement['Lieu'].'</font></TD>';
			}

			if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
			
				if (strftime("%d/%m/%y", sqlDateToOut($enregistrement['Date'])) == "01/01/70" ) {
					echo '<TD width=90 bgcolor='.$trcolor.'><FONT face=verdana size=2>';
					echo '<FONT face=verdana size=2>-</FONT></TD>';
				} else {
					echo '<TD width=100 align="left" bgcolor='.$trcolor.'><FONT face=verdana size=2>';
					setlocale(LC_TIME,"fr_FR");
					echo ucwords(strftime("%B", sqlDateToOut($enregistrement['Date'])));

					if (intval(strftime("%k", sqlDateToOut($enregistrement['Date'])))>17 ) {
						echo " Soirée";
					} elseif (intval(strftime("%k", sqlDateToOut($enregistrement['Date'])))>13 ) {
						echo " Après-midi";
					} elseif (intval(strftime("%k", sqlDateToOut($enregistrement['Date'])))>11 ) {
						echo " Midi";
					} else {
						echo " Matin";
					}
					echo '</FONT></TD>';
				}
			}
			echo '</TR>';
			//echo "<!-- /PERSONNE -->";
		//}
	}
	echo '</TABLE>'; 
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		fwrite($handle_Parent, "</TD></TR><TR><TD><BR><BR></TD></TR>");
		fwrite($handle_Parent, "<TR><TD><FONT face=verdana size=2>");
		fwrite($handle_Parent, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
		fwrite($handle_Parent, "</FONT></TD></TR></TABLE>");
		fwrite($handle_Parent, "</BODY></HTML>");
		fclose($handle_Parent);
	}		
	if ( $_SESSION["Activite_id"] == 26 AND $Classe_order == TRUE AND $Memo_Classe != "NULL" ) { // Aumônerie Lycée et collège
		fwrite($handle_ParentClasse, "</TD></TR><TR><TD><BR><BR></TD></TR>");
		fwrite($handle_ParentClasse, "<TR><TD><FONT face=verdana size=2>");
		fwrite($handle_ParentClasse, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
		fwrite($handle_ParentClasse, "</FONT></TD></TR></TABLE>");
		fwrite($handle_ParentClasse, "</BODY></HTML>");
		fclose($handle_ParentClasse);
			
		fwrite($handle_EnfantClasse, "</TD></TR><TR><TD><BR><BR></TD></TR>");
		fwrite($handle_EnfantClasse, "<TR><TD><FONT face=verdana size=2>");
		fwrite($handle_EnfantClasse, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
		fwrite($handle_EnfantClasse, "</FONT></TD></TR></TABLE>");
		fwrite($handle_EnfantClasse, "</BODY></HTML>");
		fclose($handle_EnfantClasse);
	}

	fCOM_address_bottom();	

}

// ==================================================================
// Début --------------------------------------------------------------------------------------------------------------------------
// ==================================================================

echo '<HTML><HEAD>';
echo '<TITLE>Database Fraternite</TITLE>';
echo '</HEAD>';
echo '<BODY>';

Global $eCOM_db;
$debug = False;
$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

// Connexion au serveur MySQL
//require("Login/sqlconf.php");

//mysql_connect( $sqlserver , $login , $password ) 
//or die( 'Connexion au serveur [<FONT COLOR=RED>Impossible</FONT> ]' ) ;
//debug('Connexion au serveur [ <FONT COLOR=GREEN>OK</FONT> ]<BR>');

// Sélection de la base de données

//@mysql_select_db( $sqlbase )
//mysql_select_db( $sqlbase )
//or die( 'Sélection de la base de donnée [<FONT COLOR=RED>Impossible</FONT> ]' ) ;
//debug('Connexion à la base de donnée  [ <FONT COLOR=GREEN>OK</FONT> ]<BR>');

$Classe_order = False;
if (!isset($_GET['criteria'])) {
	$criteria='Nom';
} else {
	$criteria=$_GET['criteria'];
	if ( strpos($criteria, "`Detail`") !== False ) {
		$Classe_order = True;
	}
}
if (!isset($_GET['order'])) {
	$order='DESC';
} else {
	$order=$_GET['order'];
}

if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
	($_SESSION["Activite_id"] == 22) || // Emmaüs
	($_SESSION["Activite_id"] == 12)) { // Cathéchèse
	$Activite_id=$_SESSION["Activite_id"];
	
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Detail` AS Classe, Concat(T4.`e_mail`, " ", T5.`e_mail`) AS ParentAddress, T2.`Jour`, T6.`Nom` AS Ecole
		FROM `QuiQuoi` T0 
		LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
		LEFT JOIN `Individu` T4 on T4.`id`=T1.`Pere_id`
		LEFT JOIN `Individu` T5 on T5.`id`=T1.`Mere_id`
		LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
		LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
		LEFT JOIN `Ecoles` T6 ON T6.`id`=T0.`Ecole_id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 '.$ComplementRequete.'
		ORDER BY '.$criteria.' '.$order.' ';
	
} else {
	// Alpha
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T0.`SS_Session` AS SS_Session, T0.`NoFrat`, T0.`date` AS Date, T1.`Lieu` As Lieu, T3.`id` AS Individu_id, T3.`Prenom` AS Prenom, T3.`Nom` AS Nom, T2.`Participation` AS Participation, T3.`Adresse`, T3.`e_mail`, T3.`Telephone`, T2.`Detail` AS Classe
		FROM `Fraternite` T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieu_id`=T1.`id`
		LEFT JOIN `QuiQuoi` T2 ON T2.`Activite_id`='.$Activite_id.' AND T0.`id`=T2.`Engagement_id` AND T2.`QuoiQuoi_id`="1"
		LEFT JOIN `Individu` T3 on T3.`id`= T2.`Individu_id`
		WHERE T0.`Activite_id`='.$Activite_id.$ComplementRequete.'
		ORDER BY '.$criteria.' '.$order.' ';
}

$debug = false;
pCOM_DebugAdd($debug, 'Fraternite - requete ='.$requete);

$resultat = mysqli_query($eCOM_db,  $requete );

pCOM_DebugAdd($debug, 'Fraternite - Critère de tri: ' . $criteria . "<BR>\n");
pCOM_DebugAdd($debug, 'Fraternite - Critère d\'ordre: ' . $order . "<BR><BR>\n");

if(isset($_GET['order']) and $_GET['order']=="ASC"){
	$order="DESC";
} else {
	$order="ASC";
}

personne_list($resultat, $Classe_order, $order);
mysqli_close($eCOM_db);

echo '</BODY>';
echo '</HTML>';
