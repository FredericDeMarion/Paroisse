<?php
session_start();

//==================================================================================================
//    Nom du module : Fraternite.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//--------------------------------------------------------------------------------------------------
//    V2.00 | 12/03/2018 | Intégration de Bootstrap
//--------------------------------------------------------------------------------------------------
//    V2.01 | 04/05/2018 | Donner accès aux participants d'un service même si pas gestionnaire
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
if( isset ($_GET['Service']) ) $_SESSION["Activite_id"] = $_GET['Service'];

// Inistialiser fonction accompagnateur ou pas avec ce service
if (($_SESSION["Activite_id"] == 26) OR // Aumônerie Lycée et collège
	($_SESSION["Activite_id"] == 22) OR // Emmaüs
	($_SESSION["Activite_id"] == 12) OR // Cathéchèse
	($_SESSION["Activite_id"] ==  4)) { // Alpha Classic
	$Fct_Accompagnateur_actif = true;
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		$Titre_Accompagnateur = 'Catéchiste';
	} else {
		$Titre_Accompagnateur = 'Accompagnateur';
	}
} else {
	$Fct_Accompagnateur_actif = false;
	$Titre_Accompagnateur = 'Accompagnateur';
}

// Inistialiser fonction accompagnateur ou pas avec ce service
if (($_SESSION["Activite_id"] == 26) OR // Aumônerie Lycée et collège
	($_SESSION["Activite_id"] == 4) OR // Parcours Alpha
	($_SESSION["Activite_id"] == 22) OR // Emmaüs
	($_SESSION["Activite_id"] == 12)) { // Cathéchèse
	$Fct_Frat_actif = true;
} else {
	$Fct_Frat_actif = false;
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

require('Menu.php');
//require('templateFraternite.inc');
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
	Global $Titre_Accompagnateur, $Fct_Accompagnateur_actif;
	$debug = false;

	fMENU_top();
	fMENU_Title('Liste '.$Titre_Accompagnateur.'s ...');
	
    echo '<table id="TableauTrier" class="table table-striped hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	echo '<TH> </TH>';
	echo '<TH>'.$Titre_Accompagnateur.'s</TH>';
	echo '<TH>Adresse</TH>';
	echo '<TH>Téléphone / e-mail</TH>';
	//echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>e-mail</font></TH>';
	if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		echo '<TH>Adolescent</TH>';
	} elseif ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TH>Enfant</TH>';
	} else {
		echo '<TH>Invité</TH>';
	}
	echo '</tr></thead>';
	echo '<tbody>';
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
			echo '<TR><td></td><TD>';
			fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['Individu_id'], "edit_Individu", true);
			echo '</TD>';
			echo '<TD>'.$row['Adresse'].'</TD>';
			echo '<TD>'.$row['Telephone'].'<BR>';
			echo "<A HREF='mailto:$row[e_mail]?subject= Paroisse ND Sagesse : ' TITLE='Envoyer un mail a $row[Prenom] $row[Nom]'>$row[e_mail]</A></TD>";
			echo '<TD>';
			if ($row['id'] != 0) {
				$requete3 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T2.`NoFrat`, MID(T2.`Session`,1,4) AS Session
							FROM `Individu` T0 
							LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
							LEFT JOIN `Fraternite` T2 ON T2.`id`=T1.`Engagement_id` 
							WHERE T1.`QuoiQuoi_id`=1 and T2.`id`='.$row['id'].' AND T1.`Activite_id`='.$Activite_id.'  
							ORDER BY Session, Prenom, Nom';
				pCOM_DebugAdd($debug, "Fraternite:list_accomp - requete3=".$requete3);
				$result3 = mysqli_query($eCOM_db, $requete3);
				$retour_Chariot = '';
				while($row3 = mysqli_fetch_assoc($result3)){
					echo "".$retour_Chariot."- ";
					if ( $_SESSION["Session"] == "All" ) {
						echo $row3['Session'].' ';
					}
					fCOM_Display_Photo($row3['Nom'], $row3['Prenom'], $row3['id'], "edit_Individu", true);
					$retour_Chariot = '<BR>';
				}
			}
			echo "</TD></TR>";
		}
	}
	echo '</tbody></TABLE><BR>';
	fMENU_bottom();
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
	Global $Titre_Accompagnateur, $Fct_Accompagnateur_actif;
	$debug = False;

	fMENU_top();
	fMENU_Title("Composition des fraternités :");

    echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	echo '<TH> </TH>';
	
	echo '<TH>Lieux</TH>';
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<TH>Date</TH>';
	}
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH>Jour</TH>';
	}
	echo '<TH>Fraternité</TH>';
	if ( $_SESSION["Session"] == "All" ) {
		echo '<TH>Session</TH>';
	}
	if ($Fct_Accompagnateur_actif) { // Cathéchèse
		echo '<TH>'.$Titre_Accompagnateur.'s</TH>';
	}
	if ($_SESSION["Activite_id"] == 4 ) { // Parcours Alpha
		echo '<TH>Invité</TH>';
	} elseif ( $_SESSION["Activite_id"] == 12 OR // Cathéchèse
			   $_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		echo '<TH>Enfants</TH>';
	} else {
		echo '<TH>Paroissien</TH>';
	}
	echo '<TH>Couverts</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
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
		$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].'\')"';
		
		$LieuParcours=$row['Lieu']; //fCOM_get_lieu($row['Lieu_id']);;
		if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
			$DateParcours=$row['Jour']; //fCOM_get_lieu($row['Lieu_id']);;

		} elseif ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Date'])) == "01/01/70" ) {
				$DateParcours="-";
			} else {
				setlocale(LC_TIME,"fr_FR");
				if (intval(substr($row['Date'], 11, 2))>17 ) {
					$DateParcours=ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($row['Date'])))." soir";
				} elseif (intval(substr($row['Date'], 11, 2))>13 ) {
					$DateParcours=ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($row['Date'])))." après-midi";
				} elseif (intval(substr($row['Date'], 11, 2))>11 ) {
					$DateParcours=ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($row['Date'])))." midi";
				} else {
					$DateParcours=ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($row['Date'])))." matin";
				}
			}
			if ($MemoDateParcours == "") {$MemoDateParcours = $DateParcours;};
		}
			
		// Ligne de synthèse entre chaque parcours
		if (($MemoDateParcours != $DateParcours && $MemoDateParcours != "") || 
			($MemoLieuParcours != $LieuParcours && $MemoLieuParcours != ""))   {
	
			echo '<TR bgcolor="#A1A1A1"><TD bgcolor="#A1A1A1"></TD><TD bgcolor="#A1A1A1">'.$MemoLieuParcours.'</TD>';
			if ($_SESSION["Activite_id"] != 22) { // Emmaüs
				echo '<TD bgcolor="#A1A1A1">'.$MemoDateParcours.'</TD>';
			}
			echo '<TD bgcolor="#A1A1A1">';
			echo '<FONT face=verdana size=2>Prévoir '.$Total_pers.' couverts.</FONT></TD>';
			echo '<TD bgcolor="#A1A1A1"></TD>';
			echo '<TD bgcolor="#A1A1A1"></TD>';
			echo '<TD bgcolor="#A1A1A1"></TD>';
			echo '</TR>';
			$Total_pers = 0;
			$MemoDateParcours = $DateParcours;
		}
		
		echo '<TR>';
		echo '<TD></TD>';
		echo '<TD '.$TD_Click.'>'.$row['Lieu'].'</TD>';
		$MemoLieuParcours=$LieuParcours;
		
		if ($_SESSION["Activite_id"] == 4 ) { // Parcours Alpha
			echo '<TD '.$TD_Click.'>'.$DateParcours.'</TD>';
		}
		
		if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
			echo '<TD '.$TD_Click.'>'.fCOM_Get_JourSemaine($row['Jour']).'</TD>';
		}
		
		echo '<TD '.$TD_Click.'>';
		if ($row['NoFrat'] == ""){
			$NoFrat = "-";
		} else {
			$NoFrat = $row['NoFrat'];
		}
		
		echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].'>'.$NoFrat.'</A>';
		echo '</TD>';
		
		
		if ( $_SESSION["Session"] == "All" ) {
			echo '<TD '.$TD_Click.'>'.$row['Session'].'</TD>';
		}
		
		//echo '</TD>';

		$nb_personnes = 0;
		// Liste des accompagnateurs
		//-------------------------------------
		$retour_Chariot = '';
		if ($Fct_Accompagnateur_actif) {
			echo '<TD '.$TD_Click.'>';
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
				fCOM_Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], "edit_Individu", true);
				$retour_Chariot = '<BR>';
				$nb_personnes = $nb_personnes + 1;
			}
			echo '</TD>';
		}
			
		// Liste des Participants
		//-----------------------------
		echo '<TD '.$TD_Click.'>';
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
			fCOM_Display_Photo(Securite_html($row3['Nom']), Securite_html($row3['Prenom']), $row3['id'], "edit_Individu", true);
			$retour_Chariot = '<BR>';
			$nb_personnes = $nb_personnes + 1;
		}
		echo '</TD>';
		echo '<TD '.$TD_Click.'>';
		if ($nb_personnes > 0) { echo $nb_personnes;}
		echo '</TD></TR>';
		$Total_pers = $Total_pers + $nb_personnes;
	}
	echo '<TR bgcolor="#A1A1A1" >';
	// Dernière ligne de synthèse entre chaque parcours
	echo '<td bgcolor="#A1A1A1"></td>';
	echo '<TD bgcolor="#A1A1A1">'.$LieuParcours.'</TD>';
	if ($_SESSION["Activite_id"] != 22) { // Emmaüs
		echo '<TD bgcolor="#A1A1A1">'.$DateParcours.'</TD>';
	}
	echo '<TD bgcolor="#A1A1A1">';
	echo '<FONT face=verdana size=2>Prévoir '.$Total_pers.' couverts.</FONT></TD>';
	echo '<TD bgcolor="#A1A1A1"></TD>';
	echo '<TD bgcolor="#A1A1A1"></TD>';
	echo '<TD bgcolor="#A1A1A1"></TD>';
	echo '</TR>';
	echo '</tbody></TABLE><BR>';
	fMENU_bottom();
	exit();
}

//======================================
// Vue Financiere
//======================================


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
	Global $Titre_Accompagnateur, $Fct_Accompagnateur_actif;
	
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
		
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { 
		$BloquerAcces="";
		$IsGestionnaire = true;
	} else {
		$BloquerAcces="disabled='disabled'";
		$IsGestionnaire = false;
	}	
	
	fMENU_top();

	if ($id == 0) {
		$Titre1= "Edition: Nouvelle fiche";
		$Titre2="";
	} else {
		$Titre1= 'Edition: Fiche No '.$row['id'].'</B></FONT></TD>'; 
		$Titre2= '(Dernière modification au '.substr($row['MAJ'], 0, 16).')';
	}
	fMENU_Title($Titre1, $Titre2);
	//echo '</TR>';
	
	//echo '<TABLE bgcolor="#eeeeee"><TR>';
	//echo '<TD align="center">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2">';
	echo '<CENTER>';
	
	echo '<TABLE border="0" cellpadding="2" cellspacing="0" bgcolor="#eeeeee">';
	echo '<TR bgcolor="#eeeeee"><TD>';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'"></TD></TR>';
	
	// Table ou No Frat =================================
	echo '<TR><TD><b>Nom de Fraternité</b></TD>';
	echo '<TD>';
	echo '<div class="row">';
	echo '<input class="form-control" type="text" name="NoFrat" placeholder="........" value ="'.$row['NoFrat'].'" size="10" maxlength="10" '.$BloquerAcces.'>';
	echo '</div>';
	echo '</TD></TR>';
	
	// Session ==========================================
	$debug = False;
	echo '<TR><TD>';
	echo '<B>Session</B></TD><TD>';
	echo '<div class="row">';
	echo '<SELECT class="form-control" name="PSession">';
	for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
		if (($row['Session'] != "" && $i == intval($row['Session'])) || ($row['Session'] == "" && $i == intval($_SESSION["Session"]))) {
			echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
		}
	}
	echo '</SELECT>';
	echo '</div>';
	echo '</TD></TR>';
	
	// Groupe de KT / Sous-Session pour Alpha ======================================

	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TR><TD valign="top"><B>Groupe</B></TD><TD>';
		echo '<div class="row">';
		echo '<SELECT class="form-control" name="ss_session" '.$BloquerAcces.' >';
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
		echo '</div>';
		echo '</TD></TR>';
		echo '<TR><TD></TD><TD></TD></TR>';
	} else {
		echo '<INPUT type=hidden name="ss_session" value=" ">';
	}
	
	// Date / jour et heure =====================================

	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TR><TD valign="top" bgcolor="#eeeeee"><B>Jour et heure des rencontres</B></TD><TD bgcolor="#eeeeee">';
		echo '<div class="row">';
		$Liste_Jour = fCOM_Get_Liste_JoursSemaine();
		echo '<SELECT class="form-control" name="JourSemaine" '.$BloquerAcces.' >';
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
		echo '<b>&nbsp Heure&nbsp</b>';
		$hour = substr($row['Date'],11,5);
		echo '<input class="form-control" type="time" id="heure" name="heure" value ="'.$hour.'" size="9" maxlength="10" style="width:150px" '.$BloquerAcces.'>';
		echo '</div>';
		echo '</TD></TR>';

	} elseif ($_SESSION["Activite_id"] == 4 OR // Parcours Alpha
			  $_SESSION["Activite_id"] == 26 OR // Aumônerie
			  $_SESSION["Activite_id"] == 22) { // Emmaüs
		echo '';
		$DateValue = substr($row['Date'], 0, 10);

		echo '<TR><TD valign="top" bgcolor="#eeeeee"><B>Date / heure 1ère rencontre</B></TD>';
		echo '<TD bgcolor="#eeeeee">';
		echo '<div class="row">';
		echo '<input class="form-control" type="date" id="Date" name="Date" value ="'.$DateValue.'" size="9" maxlength="10" style="width:150px" '.$BloquerAcces.'>';
		
		echo '<b>&nbsp Heure&nbsp</b>';
		$hour = substr($row['Date'],11,5);
		echo '<input class="form-control" type="time" id="heure" name="heure" value ="'.$hour.'" size="9" maxlength="10" style="width:130px" '.$BloquerAcces.'>';
		echo '</div>';
		echo '</TD></TR>';
	
	} else {
		echo '<INPUT type=hidden name="Date" value="0000/00/00">';
		echo '<INPUT type=hidden name="heure" value="00:00:00">';
	}
	

	// Lieux ==========================================
	$debug = False;
	echo '<TR><TD valign="top">';
	echo '<B>Lieu</B></TD><TD>';
	echo '<div class="row">';
	echo '<SELECT class="form-control" name="Lieux_id">';
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
	echo '</div>';
	

	// invité / Participant ==========================================
	echo '<TR><TD valign="top">';
	if ( $id > 0 ) {
		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
			if ( $IsGestionnaire ) {
				$Bouton="Ajouter un enfant";
			} else {
				$Bouton="Liste des enfants";
			}
		} else {
			if ( $IsGestionnaire ) {
				$Bouton="Ajouter un participant";
			} else {
				$Bouton="Liste des participants";
			}
		}
		echo '<div><input type="submit" name="Selectionner_Individue" value="Participant(s)" class="btn btn-outline-secondary btn-sm" ></TD>';
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
				echo '<A HREF='.$_SERVER["PHP_SELF"].'?action=RetirerParticipantDeFraternite&Qui_id='.$row2['id'].'&Invite_id='.$id.' TITLE="Retirer Participant de la fraternité"><i class="fa fa-minus-circle text-danger"></i></a>  ';
			} else {
				echo '<A HREF='.$_SERVER["PHP_SELF"].'?action=RetirerParticipant&Qui_id='.$row2['id'].'&Invite_id='.$id.'&TITLE="Retirer Participant"><i class="fa fa-minus-circle text-danger"></i></a>  ';
			}
			fCOM_Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], "edit_Individu", true);
			echo '<BR>';
		}
		echo '</TD>';

	}
	echo '</TD></TR>';
	echo '<TR><TD height="10"></TD></TR>';
	
	// Accompagnateur ==========================================
	if ($Fct_Accompagnateur_actif) {
		echo '<TR><TD valign="top">';
		$nom_accompagnateur = $Titre_Accompagnateur.'(s)';

		if ( $id > 0 ) {
			if ( $IsGestionnaire ) {
				$Bouton="Ajouter un accompagnateur";
			} else {
				$Bouton="Liste des accompagnateurs";
			}
			echo '<div><input type="submit" name="Selectionner_Individue" value="Accompagnateur(s)" class="btn btn-outline-secondary btn-sm" ></td>';
			$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` 
					FROM `Individu` T0 
					LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					WHERE T1.`Activite_id`='.$_SESSION["Activite_id"].' AND T1.`QuoiQuoi_id`=2 and T1.`Engagement_id`='.$id.'
					ORDER BY Prenom, Nom';
			$result = mysqli_query($eCOM_db, $requete);
			echo '<TD>';
			while( $row2 = mysqli_fetch_assoc( $result )) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAccompagnateur&Qui_id='.$row2['id'].'&Invite_id='.$id.' TITLE="Retirer Accompagnateur"><i class="fa fa-minus-circle text-danger"></i></a>  ';
				fCOM_Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], "edit_Individu", true);
				echo '<BR>';
			}
			echo '</TD>';
		}
		echo '</TD></TR>';
	}
	
	// Commentaire ==========================================
	echo '<TR><TD colspan="2" >';
	echo '<B>Commentaires</B>';
	echo '<br>';
	if ( $id > 0 ) {
		echo '<textarea cols=60 rows=5 name="Commentaire" maxlength="350" value ="'.Securite_html($row['Commentaire']).'">'.Securite_html($row['Commentaire']).'</textarea>';
	}

	echo '<BR></TR><TD> </TD></TR>';
	//echo '<INPUT type=hidden name="Paroissien_id" value='.$row['Invite_id'].'>';
	echo '<INPUT type=hidden name="Invite_id" value='.$id.'>';
	echo '<TR><TD>';
	if ( $id > 0 ) {
		echo '<div align="center"><INPUT type="submit" name="edit" value="Enregistrer" class="btn btn-secondary btn-sm">';
	}
	//echo '<input type="reset" name="Reset" value="Reset">';
	if (fCOM_Get_Autorization( $Activite_id ) >= 50) {
		echo '</TD><TD><INPUT type="submit" name="delete_fiche_invite_Fraternite" value="Détruire la fiche" class="btn btn-secondary btn-sm">';
	}
	
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR>';
	echo '</FORM>';
	echo '</TABLE>';

	echo '</CENTER>';
	echo '</td></tr></table>';

	fMENU_bottom();
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
	if (!isset($_POST['Date'])) { $Date = "0000-00-00"; } else { $Date = $_POST['Date'];}
	if (!isset($_POST['JourSemaine'])) { $Jour = 0; } else { $Jour = $_POST['JourSemaine'];}
	if (!isset($_POST['heure'])) { $heure = "00:00:00"; } else { $heure = $_POST['heure'].':00';}
	if (!isset($_POST['PSession'])) { $PSession = "0"; } else { $PSession = $_POST['PSession'];}
	if (!isset($_POST['ss_session'])) { $ss_session = "0"; } else { $ss_session = $_POST['ss_session'];}
	if (!isset($_POST['NoFrat'])) { $NoFrat = "0"; } else { $NoFrat = $_POST['NoFrat'];}
	if (!isset($_POST['Commentaire'])) { $Commentaire = "0"; } else { $Commentaire = $_POST['Commentaire'];}
	
	
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
		$sss_session = " ".$ss_session;
		
		pCOM_DebugAdd($debug, 'Fraternite:Sauvegarder_fiche_invite - Date='.$Date);
		$SqlDate = $Date.' '.$heure;
		
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
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche enregistrée</FONT></CENTER></B>';
	}
	mysqli_close($eCOM_db);
	echo '<META http-equiv="refresh" content="1; URL='.$_SERVER['PHP_SELF'].'?action=list_fraternite">';
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
		fMENU_top();
		
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Suppression d\'une fraternité</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir supprimer la fraternité ('.$_POST['Invite_id'].') '.$row['NoFrat'].' ?</FONT>';
		echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<INPUT type="submit" name="delete_fiche_invite_Fraternite_confirme" value="Oui">';
		echo '<INPUT type="submit" name="" value="Non">';
		echo '<INPUT type="hidden" name="id" value='.$_POST['Invite_id'].'>';
		echo '</FORM></TD></TR>';

		fMENU_bottom();
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
		fMENU_top();
		
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Retirer personne de l\'activité</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir retirer '.$row['Prenom'].' '.$row['Nom'].' de l\'activite ?</FONT>';
		echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<INPUT type="submit" name="retirer_fiche_Participant_confirme" value="Oui">';
		echo '<INPUT type="submit" name="" value="Non">';
		echo '<INPUT type="hidden" name="id" value='.$_POST['QuiQuoi_id'].'>';
		echo '</FORM></TD></TR>';

		fMENU_bottom();
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
	$debug = false;
	fMENU_top();
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
	
	echo '<table id="TableauTrier" class="table table-striped table-hover table-sm" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	
	//echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH>Sélectionner</TH>';
	echo '<TH>Prénom</TH>';
	echo '<TH>Nom</TH>';
	//if ($AfficherClasse == True) { // Aumônerie Lycée et collège
	if ( $pQui != "Accompagnateur" && 
		(($_SESSION["Activite_id"] == 12) || // Cathéchèse
		($_SESSION["Activite_id"] == 26))) { // Aumônerie Lycée et collège
		echo '<TH>Classe</TH>';
	}
	echo '</tr></thead>';
	echo '<tbody>';
	$Activite_id=$_SESSION["Activite_id"];
	if ($pQui == "Celebrant") {
		$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom 
					FROM `QuiQuoi` T0 
					LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
					WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T0.`Engagement_id`=0 and (T0.`QuoiQuoi_id`=7 or T0.`QuoiQuoi_id`=8) AND T1.Actif = 1 AND T1.Dead = 0
					ORDER BY T1.Nom, T1.Prenom';
	
	} elseif ($pQui == "Accompagnateur") {
		$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom 
					FROM `QuiQuoi` T0 
					LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
					WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T0.`Activite_id`="'.$Activite_id.'" and T0.`QuoiQuoi_id`=2 and T0.`Engagement_id`=0 AND T1.Actif = 1 AND T1.Dead = 0
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
							WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T1.id IS NULL AND T0.`Naissance` >= DATE_SUB(now(), INTERVAL 15 YEAR) AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 4 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';
			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T0.`Activite_id`="'.$Activite_id.'" AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 
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
							WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T1.id IS NULL AND T0.`Naissance` >= DATE_SUB(now(), INTERVAL 20 YEAR) AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 10 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';

			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe 
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 
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
							WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T1.id IS NULL AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 15 YEAR) AND T0.Actif = 1 AND T0.Dead = 0
							ORDER by T0.`Nom`, T0.`Prenom`';	
			} else {
				$requete = 'SELECT T1.`id` AS id, T0.`id` AS QuiQuoi_id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe 
							FROM `QuiQuoi` T0 
							LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
							WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 AND T0.`Session`='.$_SESSION["Session"].' AND T0.`Engagement_id`=0 AND T1.Actif = 1 AND T1.Dead = 0
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
						WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T0.Actif = 1 AND T0.Dead = 0 AND T0.`Naissance` <= DATE_SUB(now(), INTERVAL 10 YEAR)
						ORDER by Nom, Prenom';
						
		} else if ($_SESSION["Activite_id"] == 59) { // Parcours 40jours
			$Title = "Parcours 40jours";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T0.Actif = 1 AND T0.Dead = 0
						ORDER by Nom, Prenom'; 
						
		} else if ($_SESSION["Activite_id"] == 85) { // SophiaDeo
			$Title = "SophiaDeo";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T0.Actif = 1 AND T0.Dead = 0
						ORDER by Nom, Prenom'; 
		} else {
			$Title = "Fraternité";
			$requete = 'SELECT id, Prenom, Nom 
						FROM Individu T0
						WHERE T0.`Nom` <> "" AND T0.`Nom` <> "Annulé dupliqué" AND T0.`Prenom` <> "" AND T0.Actif = 1 AND T0.Dead = 0
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
			echo '<TD><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui='.$pQui.'&Qui_id='.$row['id'].'&Invite_id='.$Invite_id.' TITLE="Selectionner '.$pQui.'"><i class="fa fa-plus-circle"></i></a></CENTER></TD>';
			echo '<TD>'.$row['Prenom'].'</TD>';
			echo '<TD>'.$row['Nom'].'</TD>';
		} else {
			echo '<TD><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=AffecterBaseFraternite&Qui='.$pQui.'&Qui_id='.$row['id'].'&Invite_id='.$Invite_id.' TITLE="Selectionner '.$pQui.'"><i class="fa fa-plus-circle"></i></a></CENTER></td>  ';
			echo '<TD>'.$row['Prenom'].'</TD>';
			echo '<TD>'.$row['Nom'].'</TD>';
			if ($pQui != "Accompagnateur" &&
				(($_SESSION["Activite_id"] == 12) || // Cathéchèse
				($_SESSION["Activite_id"] == 26))) { // Aumônerie Lycée et collège
				echo '<TD>'.$row['Classe'].'</TD>';
			}
		}
		echo '</TR>'; 
	}
	echo '</tbody></table>'; 
	
	echo '</TD></TR></TABLE>';
	fMENU_bottom();
	exit;
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="RenseignerParticipation") {

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
		$_DateBapteme = $row['DateBapteme'];
		$_DateCommunion = $row['DateCommunion'];
		$_DateProfession = $row['DateProfession'];
		$_DateConfirmation = $row['DateConfirmation'];

		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateBapteme = '.$row['DateBapteme']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateCommunion = '.$row['DateCommunion']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateProfession = '.$row['DateProfession']);
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - DateConfirmation = '.$row['DateConfirmation']);
		}
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - QuiQuoi_id = '.$QuiQuoi_id);

	fMENU_top();
	if ( $num_total == 0) {
		fMENU_Title("Edition : Nouveau Participant");
	} else {
		fMENU_Title("Edition : Fiche No ".$row['id']);
	}	
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2">';
	echo '<CENTER>';
	
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'">';
	
	// Nom et Prénom
	echo '<TR><TD width="230"><b>Nom</b></TD>';
	echo '<TD>';
	fCOM_Display_Photo($row['Nom'], $row['Prenom'], $Qui_id, "edit_Individu", True);
	echo '</TD><TD></TD></TR>';
	
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
	    ($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TR><TD><B>Situation Familiale</B></TD>';
		echo '<TD>';
		$liste_SituationFamiliale = fCOM_Get_Liste_SituationFamiliale();
		echo '<SELECT class="form-control" name="SituationFamiliale">';
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
		echo '<TR><TD valign="top" ><B>Ecole</B></TD><TD colspan="2">';
		$liste_ecoles = fCOM_Get_liste_ecoles();
		pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - Size liste_ecoles = '.count($liste_ecoles));

		//if ( count($liste_ecoles) > 0 ) {
			echo '<SELECT class="form-control" name="EcoleSelection">';
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
		echo '<input class="form-control form-control-sm" type=text name="Autre_Ecole" placeholder="Ou nouvelle Ecole à déclarer dans la base" size="40" maxlength="40"></TD></TR>';

		
		// Classe ============================================
		echo '<TR><TD><B>Classe</B></TD><TD colspan="2">';
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
		echo '<SELECT class="form-control" style="width:160px" name="ClasseVal">';
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
	echo '<TR><TD> </TD></TR>';
	// Sacrement demandé ============================================
	echo '<TR><TD valign="top"><B>Sacrement</B></TD><TD><B>Demandé</B></TD><TD><B>ou Reçu le</B></TD></TR>';
	echo '<TR><TD></TD><TD valign="top">';
	if ($_Demande_Bapteme == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '<div class="form-check"><label class="form-check-label">';
	echo '<input class="form-check-input" type="checkbox" name="Demande_Bapteme" id="Demande_Bapteme" '.$optionSelect.' > <LABEL for="Demande_Bapteme">Baptême</b></LABEL>';
	echo '</label></div>';
	echo '</TD>';
	echo '<TD>';
	echo '<input class="form-control" type="date" id="DateBapteme" name="DateBapteme" placeholder="JJ/MM/AAAA" style="width:160px" value ="'.$_DateBapteme.'" size="8" maxlength="10">';

	if ($_CertificatBapteme == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '<div class="form-check"><label class="form-check-label">';
	echo '<input class="form-check-input" type="checkbox" name="CertificatBapteme" id="CertificatBapteme" '.$optionSelect.' > <label for="CertificatBapteme">Certificat de Baptême<BR></b></label>';
	echo '</label></div>';
	echo '</TD></TR>';
	
	echo '<TR><TD></TD><TD valign="top">';
	if ($_Demande_Communion == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
	echo '<div class="form-check"><label class="form-check-label">';
	echo '<input class="form-check-input" type="checkbox" name="Demande_Communion" id="Demande_Communion" '.$optionSelect.' > <LABEL for="Demande_Communion">1ère Communion</b></LABEL>';
	echo '</label></div>';
	echo '</TD>';
	echo '<TD>';
	echo '<input class="form-control" type="date" id="DateCommunion" name="DateCommunion" placeholder="JJ/MM/AAAA" style="width:160px" value ="'.$_DateCommunion.'" size="8" maxlength="10">';

	
	echo '</TD></TR>';
	
	if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		echo '<TR><TD></TD><TD valign="top">';
		if ($_Demande_Profession == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<div class="form-check"><label class="form-check-label">';
		echo '<input class="form-check-input" type="checkbox" name="Demande_Profession" id="Demande_Profession" '.$optionSelect.' > <LABEL for="Demande_Profession">Profession de Foi</LABEL>';
	echo '</label></div>';
		echo '<TD>';
		echo '<input class="form-control" type="date" id="DateProfession" name="DateProfession" placeholder="JJ/MM/AAAA" style="width:160px" value ="'.$_DateProfession.'" size="8" maxlength="10">';

		
		echo '<TR><TD></TD><TD valign="top">';
		if ($_Demande_Confirmation == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<div class="form-check"><label class="form-check-label">';
		echo '<input class="form-check-input" type="checkbox" name="Demande_Confirmation" id="Demande_Confirmation" '.$optionSelect.' > <LABEL for="Demande_Confirmation">Confirmation</LABEL>';
	echo '</label></div>';
		echo '<TD>';
		echo '<input class="form-control" type="date" id="DateConfirmation" name="DateConfirmation" placeholder="JJ/MM/AAAA" style="width:160px" value ="'.$_DateConfirmation.'" size="8" maxlength="10">';
	}
	
	// Participation financière ============================================
	echo '<TR><TD><B>Participation Financière</B></TD>';
	pCOM_DebugAdd($debug, 'Fraternite:RenseignerParticipation - ParticipationFinance = '.$_ParticipationF);
	echo '<TD>';
	echo '<div class="input-group">';
	echo '<span class="input-group-addon"><i class="fa fa-eur"></i></span>';
	echo '<input class="form-control" type="text" name="ParticipationF" placeholder="00" value ="'.$_ParticipationF.'" size="9" maxlength="9" '.$BloquerAcces.'>';
	echo '</div>';
	echo '</TD><TD></TD></TR>';
	
	echo '<TR><TD></TD></TR>';
	echo '<TR><TD colspan=2>';
	echo '<INPUT type=hidden name=Qui value='.$Qui.'>';
	echo '<INPUT type=hidden name=Qui_id value='.$Qui_id.'>';
	echo '<INPUT type=hidden name=QuiQuoi_id value='.$QuiQuoi_id.'>';
	echo '<INPUT type=hidden name=Invite_id value='.$Invite_id.'>';
	
	echo '<div align="center"><INPUT type="submit" class="btn btn-secondary" name="SauvegarderParticipation" value="Enregistrer"> ';
	echo '<input type="reset" class="btn btn-secondary" name="Reset" value="Reset">';
	if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
		echo ' <INPUT type="submit" class="btn btn-secondary" name="retirer_fiche_Participant" value="Retirer ce participant">';
	}
	
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR>';
	echo '</FORM>';
	echo '</TABLE>';
	
	echo '</CENTER></TD></TR></TABLE>';

	fMENU_bottom();
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
		mysqli_query($eCOM_db, 'UPDATE Individu SET Bapteme="'.$_POST['DateBapteme'].'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
	}
	
	if ( isset($_POST['DateCommunion'])) {
		pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - DateCommunion = '.$_POST['DateCommunion']);
		mysqli_query($eCOM_db, 'UPDATE Individu SET Communion="'.$_POST['DateCommunion'].'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
	}  
	
	if ( isset($_POST['DateProfession'])) {
		pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - DateProfession = '.$_POST['DateProfession']);
		mysqli_query($eCOM_db, 'UPDATE Individu SET ProfessionFoi="'.$_POST['DateProfession'].'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
	}

	if ( isset($_POST['DateConfirmation'])) {
		pCOM_DebugAdd($debug, 'Fraternite:SauvegarderParticipation - DateCommunion = '.$_POST['DateConfirmation']);
		mysqli_query($eCOM_db, 'UPDATE Individu SET Confirmation="'.$_POST['DateConfirmation'].'" WHERE id='.$_POST['Qui_id'].' ') or die (mysqli_error($eCOM_db));
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
		$Qui = fCOM_stripAccents($_GET['Qui']);
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
		}
		mysqli_query($eCOM_db, 'UPDATE Fraternite SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Invite_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?action=edit&id='.$_GET['Invite_id'].'">';
	mysqli_close($eCOM_db);
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerParticipant") {
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
	Global $eCOM_db;
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Participation`
				FROM `QuiQuoi` T0 
				LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
				LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
				LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
				WHERE T0.`Activite_id`="'.$_SESSION["Activite_id"].'" AND T0.`QuoiQuoi_id`="1" '.$ComplementRequete.'
				ORDER BY T1.`Prenom`, T1.`Nom` ';
	fMENU_top();
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		fMENU_Title("Liste des Invités ...");
	} else {
		fMENU_Title("Liste des Inscrits ...");
	}
	
	echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	$trcolor = "#EEEEEE";
	echo '<TH width="10"></TH>';
	echo '<TH width="100">Participation</TH>';
	echo '<TH>Nom</TH>';
	echo '<TH>Adresse</TH>';
	echo '<TH>No Frat</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
	$Somme_Total = 0;
	$resultat = mysqli_query($eCOM_db,  $requete );
	while( $row = mysqli_fetch_assoc( $resultat ))
	{
		$trcolor = usecolor();
		$td_click='onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui_id='.$row['Individu_id'].'\')"';
		if ($row['Participation'] > 0) { 
			$fgcolorOk = ' class="table-success"'; // "green"
		} else {
			$fgcolorOk = '';
		}
		echo '<TR>';
		echo '<TD></TD>';
		echo '<TD align="right"  '.$td_click.'>'.$row['Participation'].' €</TD>';
		if ($row['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };

		echo '<TD '.$fgcolorOk.'>';
		fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['Individu_id'], "edit_Individu", true);
		echo '</TD>';
		echo '<TD '.$td_click.'>'.$row['Telephone'].'<BR>'.$row['e_mail'].'</TD>';
		echo '<TD><A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['Frat_id'].'>'.$row['NoFrat'].'</A></TD>';
		$Somme_Total = $Somme_Total +$row['Participation'];
		echo '</TR>';
	}
	$trcolor = usecolor();
	echo "</tbody></TABLE>";
	echo '<div class="alert alert-success">Total = <strong>'.$Somme_Total.' €</strong></div>';
	fMENU_bottom();
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="trombinoscope") {
	Global $eCOM_db;
	$debug = false;
	fMENU_top();
	if ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		$Title='de l\'Aumônerie collège et lycée';
	} elseif ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		$Title='des enfants du catéchisme';
	} else {
		$Title='des paroissiens';
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Trombinoscope '.$Title.'</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	//echo '<TABLE>';
	
	//$Activite_id = 26; // Aumônerie Lycée et collège
	$criteria = "T0.`Detail`";
	$order = "DESC";
	$ComplementRequete = ' AND MID(T0.`Session`,1,4)="'.$_SESSION["Session"].'" ';

	if (($_SESSION["Activite_id"] == 26) OR // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Detail` AS Classe, T4.`e_mail` As AdressPere, Concat(T4.`e_mail`, " ", T5.`e_mail`) AS ParentAddress
		FROM `QuiQuoi` T0 
		LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
		LEFT JOIN `Individu` T4 on T4.`id`=T1.`Pere_id`
		LEFT JOIN `Individu` T5 on T5.`id`=T1.`Mere_id`
		LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
		LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 '.$ComplementRequete.'
		ORDER BY '.$criteria.' '.$order.' ';
		$Titre_Famille='Classe';
	} else {
		$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Classe, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, "" As AdressPere, "" AS ParentAddress
		FROM `QuiQuoi` T0 
		LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
		LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
		LEFT JOIN `Lieux` T3 ON T0.`Lieu_id`=T3.`id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=2 '.$ComplementRequete.'
		ORDER BY '.$criteria.' '.$order.' ';
		$Titre_Famille='Lieu';
	}
	
	$resultat = mysqli_query($eCOM_db,  $requete );
	$MemoClasse= "";
	echo '<div class="card-block">';
	echo '<div class="row">';
	while( $row = mysqli_fetch_assoc( $resultat )) {
		if ($MemoClasse != $row['Classe']) {
			echo '</div><div class="row">';
			$MemoClasse = $row['Classe'];
		}
		
		$Classe = $row['Classe'];
		$Nom = $row['Prenom']." ".$row['Nom'];
		
		//echo '<TD valign="top"><A 
		$HREF=$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$row['Individu_id'];	
		if (file_exists("Photos/Individu_".$row['Individu_id'].".jpg")) { 
			$Photo = 'Photos/Individu_'.$row['Individu_id'].'.jpg';		
		} else {
			$Photo = 'Photos/Individu_NULL.jpg';
		}

		echo '<div class="col">';
		echo '<div class="card" style="width:150px">';
		//echo '<div class="card">';
		echo '<A href='.$HREF.'><img class="card-img-top" src="'.$Photo.'" alt="Pas de photo"></A>';
		echo '<div class="card-block">';
		echo '<h6 class="card-title">'.$Nom.'</h6>';
		echo '<p class="card-text">'.$Titre_Famille.' : '.$Classe.'</p>';
		echo '</div>';
		echo '</div>';
		echo '</div>';

	}
	echo '</div>';
	echo '</div>';
	echo "</TD></TR></TABLE>";
	fMENU_bottom();
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="Inviter") { 
	// si KT
	// Tous les enfants >= 6 ans et moins de 12 qui ont déjà été baptisés et qui n'ont pas remis les pieds au catéchisme
	// si Aumônerie
	// Tous les enfants >= 12 ans et moins de 18 qui ont déjà été baptisés et qui n'ont pas remis les pieds au aumônerie
	Global $eCOM_db;
	$debug = False;
	fMENU_top();
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Invité</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		$ComplementWhere = 'AND T3.`Activite_id`!= '.$_SESSION["Activite_id"].' AND T0.`Bapteme`!= "0000-00-00" AND (YEAR(CURRENT_DATE)-YEAR(T0.`Naissance`))>=6 AND (YEAR(CURRENT_DATE)-YEAR(T0.`Naissance`))<12 ';
		
	}elseif ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège		
		$ComplementWhere = 'AND T3.`Activite_id`!= '.$_SESSION["Activite_id"].' AND (YEAR(CURRENT_DATE)-YEAR(T0.`Naissance`))>=12 AND (YEAR(CURRENT_DATE)-YEAR(T0.`Naissance`))<=18 ';
	}

	$requete = 'SELECT DISTINCT T0.`id`, T0.`Prenom` AS Prenom, T0.`Nom` as Nom, T0.`Bapteme`, (YEAR(CURRENT_DATE)-YEAR(T0.`Naissance`)) AS Age, T0.`e_mail`, T1.`e_mail` As AdressPere, T2.`e_mail`AS AdressMere 
		FROM `Individu` T0
		LEFT JOIN `Individu` T1 on T1.`id`=T0.`Pere_id`
		LEFT JOIN `Individu` T2 on T2.`id`=T0.`Mere_id`
		LEFT JOIN `QuiQuoi` T3 on T0.`id`= T3.`Individu_id` 
		WHERE T0.`Actif`=1 AND T3.`QuoiQuoi_id`=1 '.$ComplementWhere.'
		ORDER BY Age';
	
	pCOM_DebugAdd($debug, "Fraternite:Invite Requete=".$requete);
	$resultat = mysqli_query($eCOM_db,  $requete );
	
	$trcolor = "#EEEEEE";
	echo '<TABLE>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Prénom et Nom</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Age</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Date Baptême</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>e_mail (<A HREF="load/ListeMail_Parents_invite.php">Préparer e_mail</A>)</FONT></TH>';

	$temp_Parent = "load/ListeMail_Parents_invite.php";
	$handle_Parent = fopen($temp_Parent, 'w');
	fwrite($handle_Parent, "<HTML><HEAD><TITLE>Liste adresses mail des parents</TITLE></HEAD><BODY><br>");
	$TreatedName = ""; // mise à zéro pour éviter erreur
	fwrite($handle_Parent, "<h1><FONT face=verdana>Liste des adresses mail des parents : ".$TreatedName."</FONT></h1>");
	fwrite($handle_Parent, "<FONT face=verdana size=2>");
	fwrite($handle_Parent, "<p>Date : ".ucwords(strftime("%A %x %X",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))))."</p>");
	fwrite($handle_Parent, "<p>===================================================</p><br><TABLE>");
	fwrite($handle_Parent, "<FONT face=verdana size=2>");
		
	echo '</TR>';
	while( $row = mysqli_fetch_assoc( $resultat )) {
		$trcolor = usecolor();
		echo '<TR>';

		echo '<TD bgcolor='.$trcolor.'>';
		fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['id'], "edit_Individu", True);
		echo '</TD>';
		
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Age'].'</FONT></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.fCOM_PrintDate($row['Bapteme']).'</FONT></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['AdressMere'].'; '.$row['AdressPere'].'</FONT></TD>';
		fwrite($handle_Parent, '"Parents de '.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"< '.format_email_list(Securite_html($row['AdressPere']).' '.Securite_html($row['AdressMere']), ">;< ").'>; ');
		echo '</TR>';
	}
		
	fwrite($handle_Parent, "</TD></TR><TR><TD><BR><BR></TD></TR>");
	fwrite($handle_Parent, "<TR><TD><FONT face=verdana size=2>");
	fwrite($handle_Parent, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
	fwrite($handle_Parent, "</FONT></TD></TR></TABLE>");
	fwrite($handle_Parent, "</BODY></HTML>");
	fclose($handle_Parent);
		
	echo "</TABLE>";
	fMENU_bottom();
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
	Global $Titre_Accompagnateur, $Fct_Accompagnateur_actif, $Fct_Frat_actif;
	$debug = false;

	$TD_Click="";
	$SimpleFraternite = false;
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		fMENU_Title("Liste des Invités :");
	} elseif ($_SESSION["Activite_id"] == 26) { // Aumônerie Lycée et collège
		fMENU_Title("Liste des enfants de l'aumônerie :");
	} elseif ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		fMENU_Title("Liste des enfants de la cathéchèse :");
	} else {
		fMENU_Title("Liste des participants :");
		$SimpleFraternite = true;
	}

	if ($SimpleFraternite) {
		echo '<table id="TableauSansTriero" class="table table-striped table-hover table-sm" width="100%" cellspacing="0">';
	} else {
		echo '<table id="TableauTrier" class="table table-striped table-hover table-sm">';
	}
	
	$trcolor = "#EEEEEE";
	echo '<thead><tr>';
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 22) || // Emmaüs
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		//echo '<TH></TH>';
	}
	
	if ($SimpleFraternite){
		echo '<TH>Statut</TH>';  // Service ou Ressourcement
		
		$requete = 'SELECT Nom
					FROM `Activites` 
					WHERE id='.$_SESSION["Activite_id"];
		$resultat2 = mysqli_query($eCOM_db,  $requete );
		$Row_Activite = mysqli_fetch_assoc( $resultat2);
		
		$temp_AuService = "load/ListeMail_Activite_Au_Service.php";
		$handle_AuService = fopen($temp_AuService, 'w');
		fCOM_PrintFile_Init($handle_AuService, 'Liste adresses mail des paroissiens au service '.$Row_Activite['Nom']);
		
		$temp_EnRessource = "load/ListeMail_Activite_En_Ressourcement.php";
		$handle_EnRessource = fopen($temp_EnRessource, 'w');
		fCOM_PrintFile_Init($handle_EnRessource, 'Liste adresses mail des paroissiens en ressourcement '.$Row_Activite['Nom']);
	}
	
	echo '<TH> </TH>';
	if ($Fct_Frat_actif) {
		echo '<TH>No Frat</TH>';
	}
	
	echo '<TH>Nom</TH>';
	if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
		echo '<TH>Jour</TH>';
		echo '<TH>Lieu</TH>';
		echo '<TH>Ecole</TH>';
	}	
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<TH>Classe</TH>';
	}
	echo '<TH>Tel / e_Mail ';
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		echo '<A HREF="load/ListeMail_Parents.php">Parents</A>';
		setlocale(LC_TIME, "fr_FR");
		$temp_Parent = "load/ListeMail_Parents.php";
		$handle_Parent = fopen($temp_Parent, 'w');
		fCOM_PrintFile_Init($handle_Parent, 'Liste adresses mail des parents');
		$TreatedName = ""; // mise à zéro pour éviter erreur
	} else {
		echo '<A HREF="load/ListeMail_Paroissien.php">Paroissiens</A>';
		setlocale(LC_TIME, "fr_FR");
		$temp_Paroissien = "load/ListeMail_Paroissien.php";
		$handle_Paroissien = fopen($temp_Paroissien, 'w');
		fCOM_PrintFile_Init($handle_Paroissien, 'Liste adresses mail des paroissiens');
		$TreatedName = ""; // mise à zéro pour éviter erreur
	}
	echo '</TH>';
	
	echo '<TH>Adresse</TH>';
	//echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2><A HREF="'.$_SERVER['SCRIPT_NAME'].'?criteria=Accompagnateur&order='.$order.'">Accompagnateurs</A></font></TH>';
	if ($Fct_Accompagnateur_actif) { 
		echo '<TH>'.$Titre_Accompagnateur.'s</TH>';
	}
	if ($_SESSION["Session"]=="All") {
		echo '<TH>Session</TH>';
	}
	if ($_SESSION["Activite_id"] != 12) { // Cathéchèse, car déjà printer
		echo '<TH>Lieu</TH>';
	}
	if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
		echo '<TH>Date</TH>';
	}
	echo '</tr></thead>';
	echo '<tbody>';
	
	$debug = true;
	pCOM_DebugInit($debug);
	if (fCOM_Get_Autorization(  0 ) >= 30 ) { // all
		$isGestionnaire = True;
	} else {
		$isGestionnaire = False;
	}
	
	$Memo_Titre_Parcours="";
	$Memo_Classe="NULL";
	$Activite_id=$_SESSION["Activite_id"];
	while( $enregistrement = mysqli_fetch_assoc( $resultat ))
	{
		$debug = False;
  
		$trcolor = usecolor();
		echo '<TR>';

		if ($_SESSION["Activite_id"] == 26 AND 
			$Classe_order == TRUE AND 
			$Memo_Classe != $enregistrement['Classe']) { // Aumônerie Lycée et collège
			if ( $Memo_Classe != "NULL" ) {
				fclose($handle_ParentClasse);
				fclose($handle_EnfantClasse);
			}
			$Memo_Classe = $enregistrement['Classe'];
			echo '<TR>';
			echo '<TD> </TD>';
			echo '<TD align="left" bgcolor="#A1A1A1" colspan=9><font face=verdana size=2>Enfants classe de '.$Memo_Classe.' -> E_mail <A HREF="load/ListeMail_Parents'.fCOM_stripAccents($enregistrement['Classe']).'.php">Parents</A> <A HREF="load/ListeMail_Enfant'.fCOM_stripAccents($enregistrement['Classe']).'.php">Enfants</A></font>';
			echo '</TD></TR>';
				
			// Parent Classe
			$temp_ParentClasse = "load/ListeMail_Parents".fCOM_stripAccents($enregistrement['Classe']).".php";
			$handle_ParentClasse = fopen($temp_ParentClasse, 'w');
			fCOM_PrintFile_Init($handle_ParentClasse, 'Liste adresses mail des parents<BR>enfants de la classe de : '.$enregistrement['Classe'].'');
			$TreatedName = ""; // mise à zéro pour éviter erreur
				
			// Enfant Classe
			$temp_EnfantClasse = "load/ListeMail_Enfant".fCOM_stripAccents($enregistrement['Classe']).".php";
			$handle_EnfantClasse = fopen($temp_EnfantClasse, 'w');
			fCOM_PrintFile_Init($handle_EnfantClasse, 'Liste adresses mail des enfants de la classe de : '.$enregistrement['Classe'].'');
			$TreatedName = ""; // mise à zéro pour éviter erreur
			
		} else {
			// au Service ou en Ressourcement
			if ($SimpleFraternite){
				echo '<TD>';
				if ($enregistrement['Serv_ou_Ress'] == 2) {
					echo 'Au Service -> <A HREF="load/ListeMail_Activite_Au_Service.php">Liste e_mail</A>'  ;
				} else {
					echo 'En Ressourcement -> <A HREF="load/ListeMail_Activite_En_Ressourcement.php">Liste e_mail</A>';
				}
				echo '</TD>';
				echo '<TD> </TD>';
			} else {
				echo '<TD> </TD>';
			}
		}

		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 22) || // Emmaüs
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
			$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui_id='.$enregistrement['Individu_id'].'\')"';
		} elseif ($SimpleFraternite AND fCOM_Get_Autorization($enregistrement['Individu_id']) >= 20) {
			$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$enregistrement['Individu_id'].'\')"';
		} elseif ($SimpleFraternite){
			$TD_Click='';
		} else {
			$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=RenseignerParticipation&Qui_id='.$enregistrement['Individu_id'].'\')"';
		}
		

		
		// No frat
		//--------
		if ($enregistrement['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };
		
		if ($Fct_Frat_actif) {
			if ($enregistrement['NoFrat'] == "") {
				$FratNum="-";
			} else {
				$FratNum=$enregistrement['NoFrat'];
			}

			if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
				($_SESSION["Activite_id"] == 22) || // Emmaüs
				($_SESSION["Activite_id"] == 12)) { // Cathéchèse
				echo '<TD '.$TD_Click.'><A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['Frat_id'].'>'.$FratNum.'</A></TD>';
			} else {
				echo '<TD><A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].'>'.$FratNum.'</A></TD>';
			}
		}	
		
		// Nom
		//--------
		if ($enregistrement['Participation'] != 0) { $fgcolor = "green"; } else { $fgcolor = "black"; };
		echo '<TD>';
		fCOM_Display_Photo($enregistrement['Nom'], $enregistrement['Prenom'], $enregistrement['Individu_id'], "edit_Individu", $isGestionnaire);
		echo '</font></TD>';
			
		// Lieux
		//-------
		if ($_SESSION["Activite_id"] == 12) { // Cathéchèse
			echo '<TD '.$TD_Click.'>'.fCOM_Get_JourSemaine($enregistrement['Jour']).'</TD>';
			echo '<TD '.$TD_Click.'>'.$enregistrement['Lieu'].'</TD>';
			echo '<TD '.$TD_Click.'>'.$enregistrement['Ecole'].'</TD>';
		}
			
		// Classe
		//--------
		$fgcolor = "black";
		if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
			($_SESSION["Activite_id"] == 12)) { // Cathéchèse
			echo '<TD '.$TD_Click.'>'.$enregistrement['Classe'].'<BR>';
		}
			
		// Telephone / Mail
		//-----------------
		echo '<TD '.$TD_Click.'>'.$enregistrement['Telephone'].'<BR>';
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
				$debug=False;
				if ( $Memo_Classe != "NULL" ) {
					pCOM_DebugAdd($debug, "Fraternité:liste Enfant=".$enregistrement['Prenom']." ".$enregistrement['Nom']);
					fwrite($handle_EnfantClasse, '"'.$enregistrement['Prenom'].' '.$enregistrement['Nom'].'"< '.format_email_list($enregistrement['e_mail'], ">;< ").'>; ');
					//fwrite($handle_EnfantClasse, '"'.$enregistrement['Nom'].'"< '.format_email_list($enregistrement['e_mail'], ">;< ").'>; ');
				}
			}
		} else {
			
			// au Service ou en Ressourcement
			if ($SimpleFraternite){
				if ($enregistrement['Serv_ou_Ress'] == 2) {
					$Ref_Handle = $handle_AuService;
				} else {
					$Ref_Handle = $handle_EnRessource;
				}
			} else {
				$Ref_Handle = $handle_Paroissien;
			}
						
			if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
				$Titre_Parcours = 'Parcours : '.$enregistrement['Lieu'];
				if (strftime("%d/%m/%y", fCOM_sqlDateToOut($enregistrement['Date'])) == "01/01/70" ) {
					$DateParcours='-';
				} else {
					setlocale(LC_TIME,"fr_FR");
					if (intval(substr($enregistrement['Date'], 11, 2))>17 ) {
						$DateParcours= ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($enregistrement['Date'])))." soir";
					} elseif (intval(substr($enregistrement['Date'], 11, 2))>13 ) {
						$DateParcours= ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($enregistrement['Date'])))." après-midi";
					} elseif (intval(substr($enregistrement['Date'], 11, 2))>11 ) {
						$DateParcours= ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($enregistrement['Date'])))." midi";
					} else {
						$DateParcours= ucwords(strftime("(%b) %A", fCOM_sqlDateToOut($enregistrement['Date'])))." matin";
					}
				}
				$Titre_Parcours = $Titre_Parcours.' '.$DateParcours; 
				if ($Memo_Titre_Parcours != $Titre_Parcours) {
					fwrite($Ref_Handle, '<BR><BR><BR><B>'.$Titre_Parcours.'</B><BR>');
					$Memo_Titre_Parcours = $Titre_Parcours;
				}			
			}
			
			fCOM_PrintFile_Email($Ref_Handle, $enregistrement['Prenom'].' '.$enregistrement['Nom'], $enregistrement['e_mail']);
		}

		// Adresse
		//--------
		echo '<TD '.$TD_Click.'>'.$enregistrement['Adresse'].'</TD>';
	
		// Chercher les accompagnateurs
		if ($Fct_Accompagnateur_actif) {
			if ($_SESSION["Activite_id"] != 4) { // Parcours Alpha
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

			if ($enregistrement['Frat_id'] > 0) {
				$resultat2 = mysqli_query($eCOM_db,  $requete );
				echo '<TD '.$TD_Click.'>';
				$Nom = "@@@";
				while( $Row_Accomp = mysqli_fetch_assoc( $resultat2)) {
					if ($Nom == $Row_Accomp['Nom']) {
						echo " et ".$Row_Accomp['Prenom']."";
					} else {
						if ($Nom != "" && $Nom != "@@@") {echo "<br>";}
						echo "".$Row_Accomp['Nom']." ".$Row_Accomp['Prenom']."";
						$Nom = $Row_Accomp['Nom'];
					}
				}
				echo '</TD>';
			} else {
				echo '<TD></TD>';
			}
		}
		
		// Affichage de la session
		if ($_SESSION["Session"]=="All") {
			echo '<TD '.$TD_Click.'>'.$enregistrement['Session'].'</TD>';
		}
		
		if ($_SESSION["Activite_id"] != 12) { // Cathéchèse
			echo '<TD '.$TD_Click.'>'.$enregistrement['Lieu'].'</TD>';
		}

		if ($_SESSION["Activite_id"] == 4) { // Parcours Alpha
			echo '<TD align="left">';
			echo $DateParcours;
			echo '</FONT></TD>';
		}
		echo '</TR>';
	}
	echo "</tbody></TABLE>";
	
	if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
		($_SESSION["Activite_id"] == 12)) { // Cathéchèse
		fCOM_PrintFile_End($handle_Parent);
		fclose($handle_Parent);

		if ( $_SESSION["Activite_id"] == 26 AND $Classe_order == TRUE AND $Memo_Classe != "NULL" ) { // Aumônerie Lycée et collège
			fCOM_PrintFile_End($handle_ParentClasse);
			fclose($handle_ParentClasse);
		
			fCOM_PrintFile_End($handle_EnfantClasse);
			fclose($handle_EnfantClasse);
		}
	} else {
		fCOM_PrintFile_End($handle_Paroissien);
		fclose($handle_Paroissien);
	}

}

// ==================================================================
// Début --------------------------------------------------------------------------------------------------------------------------
// ==================================================================

fMENU_top();

Global $eCOM_db;
$debug = False;
$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

$Classe_order = False;
if (!isset($_GET['criteria'])) {
	$criteria='Date, Lieu, Nom';
} else {
	$criteria=$_GET['criteria'];
	if ( strpos($criteria, "`Detail`") !== False ) {
		$Classe_order = True;
	}
}
//if (!isset($_GET['order'])) {
//	$order='DESC';
//} else {
//	$order=$_GET['order'];
//}

$Activite_id=$_SESSION["Activite_id"];
if (($_SESSION["Activite_id"] == 26) || // Aumônerie Lycée et collège
	($_SESSION["Activite_id"] == 22) || // Emmaüs
	($_SESSION["Activite_id"] == 12)) { // Cathéchèse
	
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T2.`SS_Session` AS SS_Session, T2.`NoFrat`, T2.`date` AS Date, T3.`Lieu` As Lieu, T1.`id` AS Individu_id, T1.`Prenom` AS Prenom, T1.`Nom` as Nom, T0.`Participation` AS Participation, T1.`Adresse`, T1.`e_mail`, T1.`Telephone`, T0.`Detail` AS Classe, Concat(T4.`e_mail`, " ", T5.`e_mail`) AS ParentAddress, T2.`Jour`, T6.`Nom` AS Ecole
		FROM `QuiQuoi` T0 
		LEFT JOIN `Individu` T1 on T1.`id`= T0.`Individu_id` 
		LEFT JOIN `Individu` T4 on T4.`id`=T1.`Pere_id`
		LEFT JOIN `Individu` T5 on T5.`id`=T1.`Mere_id`
		LEFT JOIN `Fraternite` T2 ON T2.`id`=T0.`Engagement_id`
		LEFT JOIN `Lieux` T3 ON T2.`Lieu_id`=T3.`id`
		LEFT JOIN `Ecoles` T6 ON T6.`id`=T0.`Ecole_id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`=1 '.$ComplementRequete.'
		ORDER BY '.$criteria;
	
} elseif ($_SESSION["Activite_id"] == 4) { // Alpha
	$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T0.`SS_Session` AS SS_Session, T0.`NoFrat`, T0.`date` AS Date, T1.`Lieu` As Lieu, T3.`id` AS Individu_id, T3.`Prenom` AS Prenom, T3.`Nom` AS Nom, T2.`Participation` AS Participation, T3.`Adresse`, T3.`e_mail`, T3.`Telephone`, T2.`Detail` AS Classe
		FROM `Fraternite` T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieu_id`=T1.`id`
		LEFT JOIN `QuiQuoi` T2 ON T2.`Activite_id`='.$Activite_id.' AND T0.`id`=T2.`Engagement_id` AND T2.`QuoiQuoi_id`="1"
		LEFT JOIN `Individu` T3 on T3.`id`= T2.`Individu_id`
		WHERE T0.`Activite_id`='.$Activite_id.$ComplementRequete.' AND Nom != "" AND Prenom != ""
		ORDER BY '.$criteria;

} else {
	if ($Fct_Accompagnateur_actif) {
		$requete = 'SELECT T0.`id`, T2.`id` AS Frat_id, T0.`Session` AS Session, T0.`SS_Session` AS SS_Session, T0.`NoFrat`, T0.`date` AS Date, T1.`Lieu` As Lieu, T3.`id` AS Individu_id, T3.`Prenom` AS Prenom, T3.`Nom` AS Nom, T2.`Participation` AS Participation, T3.`Adresse`, T3.`e_mail`, T3.`Telephone`, T2.`Detail` AS Classe
		FROM `Fraternite` T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieu_id`=T1.`id`
		LEFT JOIN `QuiQuoi` T2 ON T2.`Activite_id`='.$Activite_id.' AND T2.`QuoiQuoi_id`="1"
		LEFT JOIN `Individu` T3 on T3.`id`= T2.`Individu_id`
		WHERE T0.`Activite_id`='.$Activite_id.$ComplementRequete.'
		ORDER BY '.$criteria;

	} else {
		$requete = 'SELECT T0.`id`, T0.`Session` AS Session, "" As Date, T1.`Lieu` As Lieu, T3.`id` AS Individu_id, T3.`Prenom` AS Prenom, T3.`Nom` AS Nom, T3.`Adresse`, T3.`e_mail`, T3.`Telephone`, 0 as Participation, "0" as NoFrat, T0.`QuoiQuoi_id` As Serv_ou_Ress
		FROM `QuiQuoi` T0
		LEFT JOIN `Lieux` T1 ON T0.`Lieu_id`=T1.`id`
		LEFT JOIN `Individu` T3 on T3.`id`= T0.`Individu_id`
		WHERE T0.`Activite_id`='.$Activite_id.$ComplementRequete.' AND (T0.`QuoiQuoi_id`=2 OR T0.`QuoiQuoi_id`=1) AND Nom != "" AND Prenom != ""
		ORDER BY T0.`QuoiQuoi_id` DESC, '.$criteria;
	}
}

$debug = false;
pCOM_DebugAdd($debug, 'Fraternite - requete ='.$requete);
$debug = false;

$resultat = mysqli_query($eCOM_db,  $requete );

pCOM_DebugAdd($debug, 'Fraternite - Critère de tri: ' . $criteria . "<BR>\n");

if(isset($_GET['order']) and $_GET['order']=="ASC"){
	$order="DESC";
} else {
	$order="ASC";
}

personne_list($resultat, $Classe_order, $order);

fMENU_bottom();

echo '</BODY>';
echo '</HTML>';
