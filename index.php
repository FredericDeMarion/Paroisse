<?php

//==================================================================================================
//    Nom du module : index.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 10/05/2017 : suppression des $_SERVER['PHP_AUTH_USER']
// 28/05/2017 : Correction de la session sélectionnée différente que le courant
// 17/07/2017 : Ajout des célébrations automatiques
// 17/07/2017 : Ajout de la fonction Gerer_Equipe_Technique_Messe lorsqu'il n'y a pas de fichier XML
// 05/11/2017 : utilisation de fCOM_sqlDateToOut et suppression de sqlDateToOut de Template.inc
//==================================================================================================

require('Common.php');
require('template.inc');
require('Paroissien.php');
$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

Global $eCOM_db;
// Initialisation des variables
if( ! isset( $action ) ) $action = ""; // l'initialiser si elle n'existe pas
if( ! isset( $Messe_Acteurs ) ) $Messe_Acteurs = ""; 
if( ! isset( $Session_SP ) ) $Session_SP = ""; 
if( ! isset( $Chants_Mariage ) ) $Chants_Mariage = ""; 


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

if ( isset( $_POST['Lancer_Activite'] ) ) {
	// Préparation Mariage
	$debug = true;
	$_SESSION["Session"] = $_POST['SessionSelection'];
	$requete = 'SELECT id, Nom, Menu_Ordre, Menu_PHP_File FROM `Activites` WHERE Nom = "'.$_POST['Lancer_Activite'].'" AND Menu_Ordre > 0';
	$result = mysqli_query($eCOM_db, $requete);
	$Lancer_Activite = "Location:index.php";
	while ( $row = mysqli_fetch_assoc( $result)) {
		$_SESSION["Activite_id"] = $row['id'];
		$Lancer_Activite=$row['Menu_PHP_File'];
		pCOM_DebugAdd($debug, "Index:Lancer_Activite - ".$row['id']." ".$row['Nom']." - ".$row['Menu_PHP_File']);
	}
	pCOM_DebugAdd($debug, "Index:Lancer_Activite - requete=".$requete);
	pCOM_DebugAdd($debug, "Index:Lancer_Activite - ".$row['id']." ".$_POST['Lancer_Activite']." - ".$Lancer_Activite);
	header($Lancer_Activite);
	exit();
}


if ( isset( $_POST['Messe_Acteurs'] ) AND $_POST['Messe_Acteurs']="Messes"){
	//$_SESSION["Session"] = date("Y");
	$_SESSION["Session"] = $_POST['SessionSelection'];
	$_SESSION["Activite_id"] = 86;
	header('Location:Evenements.php');
	exit();
}

if ( isset( $_POST['Session_SP'] ) AND $_POST['Session_SP']="Go") {
	// Suivi Paroissien
	$debug = false;
	pCOM_DebugAdd($debug, "Index:Session_SP - Session=".$_POST['SessionSelection']);
	//$_SESSION["Session"] = date("Y");
	header('Location:SuiviParoissien.php');
	//echo '<META http-equiv="refresh" content="1; URL=https://'.$_SERVER['SERVER_NAME'].'/SuiviParoissien.php">';
	exit();
}

if ($Chants_Mariage) {
	$debug = false;
	echo '<META http-equiv="refresh" content="1; URL=/Chants_Mariage/index.htm">';
	exit();
}


//======================================
// Vue liste des événements futurs
//======================================
if (fCOM_Get_Autorization( 16) == 200) { // Impossible 200
//if (( isset( $_GET['action']) AND $_GET['action']=="list_evenements") || fCOM_Get_Autorization( 16) == 20) {
	Global $eCOM_db;
	
	if (fCOM_Get_Autorization( 16) == 20 && empty($_SESSION["Session"])) {
		$_SESSION["Session"] = date("Y");
	}
	
	$debug = False;
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	address_top();
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '</TABLE>';
	echo '</ENTETE>';
	//echo '<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>';
	echo '<PIED>';

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Prochaines célébrations : Messes, Baptêmes et Mariages</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	

	if (!isset($_GET['criteria'])) {
		$criteria="DateEve";
	} else {
		$criteria=$_GET['criteria'];	}
	if (!isset($_GET['order'])) {
		$order="DESC";
	} else {
		$order=$_GET['order'];
	}
	
	pCOM_DebugAdd($debug, "Index:list_evenements - criteria = ". $criteria );
	pCOM_DebugAdd($debug, "Index:list_evenements - order = ". $order );

	if ($criteria=="Lieu" || $criteria=="Activité" || $criteria=="Celebrant") {
		$SecondCriteria = ", DateEve ASC";
	} else {
		$SecondCriteria = "";
	}
	if($order=="ASC"){
		$order="DESC";
	}else{
		$order="ASC";
	}

	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Lieu&order=".$order."\">Lieu</A></font></TH>\n";
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=DateEve&order=".$order."\">Date</A></font></TH>\n";
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Activité&order=".$order."\">Type</A></font></TH>\n";
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Celebrant&order=".$order."\">Celebrant</A></font></TH>\n";
	echo "<TH bgcolor=".$trcolor."><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Paroissien&order=".$order."\">Paroissien</A></font></TH>\n";
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter = 1;

	$requete = '(SELECT T1.`Lieu`, T0.`Date` AS DateEve, T0.`Aspersion_Immersion` AS Activité, T0.`id` AS id, Concat(T3.`Prenom`, " ",T3.`Nom`) AS Paroissien, "Ok" AS Status, T5.`Nom` AS Celebrant 
				 FROM `Bapteme` as T0 
				 LEFT JOIN `Lieux` as T1 ON T0.`Lieu_id`=T1.`id` 
				 LEFT JOIN `Individu` as T3 ON T3.`id`=T0.`Baptise_id` 
				 LEFT JOIN `Individu` as T5 ON T5.`id`=T0.`Celebrant_id` 
				 WHERE T0.`Date`>=now())
				Union all
				 (SELECT T4.`Lieu_mariage` AS Lieux, T4.`Date_mariage` AS DateEve, "Mariage" AS Activité, T4.`id` AS id, 
				CONCAT(
				(SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
				FROM Individu T6
				LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
				WHERE T6.`Sex` = "F" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`), " et ",
				(SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
				FROM Individu T6
				LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
				WHERE T6.`Sex` = "M" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`)) As Paroissien, T4.`Status` AS Status, T4.`Celebrant` AS Celebrant
				FROM `Fiancés` AS T4
				WHERE T4.`Date_mariage` >= now())
				Union all
				(SELECT T11.`Lieu` AS Lieux, T10.`Date` AS DateEve, "Messe" AS Activité, "0" AS id, "-" AS Paroissien, "Ok" AS Status, CONCAT(T13.`Prenom`, " ", T13.`Nom`) as Celebrant
				FROM Rencontres T10
				LEFT JOIN `Lieux` T11 ON T10.`Lieux_id` = T11.`id`
				LEFT JOIN `QuiQuoi` T12 ON T10.`id` = T12.`Engagement_id` AND T12.`Activite_id`= 86 AND T12.`QuoiQuoi_id`=5
				LEFT JOIN `Individu` T13 ON T12.`Individu_id` = T13.`id`
				WHERE T10.`Activite_id`=86 AND T10.`Date` >= now())
				ORDER BY '.$criteria.' '.$order.' '.$SecondCriteria;

	pCOM_DebugAdd($debug, "Index:list_evenements requete=".$requete);
				//debug('Requete = '. $requete );
	$Liste_Type_Bapteme = array("Baptême", "Baptême aspersion", "Baptême immersion");
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		if ($row['Status'] != "Annulé/Reporté") {
			$trcolor = usecolor();
			echo "<TR>";
			$Date_Evenement=date("d/m/Y H:i", strtotime($row['DateEve']));
			echo "<TD width=150 bgcolor=".$trcolor."><font face=verdana size=2>".$row['Lieu']."</TD>";
			echo "<TD width=150 bgcolor=".$trcolor."><font face=verdana size=2>".$Date_Evenement."</TD>";
			if ($row['Activité']=="Mariage" || $row['Activité']=="Messe") {
				echo "<TD bgcolor=".$trcolor."><font face=verdana size=2>".$row['Activité']."</TD>";
			} else {
				echo "<TD bgcolor=".$trcolor."><font face=verdana size=2>".$Liste_Type_Bapteme[$row['Activité']]."</TD>";
			}
			echo "<TD bgcolor=".$trcolor."><font face=verdana size=2>".$row['Celebrant']."</td>";
			echo "<TD width=300 bgcolor=".$trcolor."><font face=verdana size=2>";

			if ($row['Activité']=="Mariage") {
				if (file_exists("Photos/".$row['id'].".jpg"))
				{ 
					echo ' <A HREF=/Mariage.php?action=edit&id='.$row['id'].' class="tooltip">'.$row['Paroissien'];
					echo '<EM><SPAN></SPAN>';
					echo "<img src='Photos/".$row['id'].".jpg' height='100' border='1' alt='couple_".$row['id']."'>";
					//echo '<BR><FONT face=verdana size=2>'.$row['Paroissien'].'</FONT>';
					echo '</EM></A>';
				} else {
					echo '<A HREF="/Mariage.php?action=edit&id='.$row['id'].'">'.$row['Paroissien'].'</A>';
				}			
	
			} elseif ( $row['Activité']!="Messe" ) { // il s'agit d'un baptème
				echo '<A HREF="/Bapteme.php?action=edit&id='.$row['id'].'">'.$row['Paroissien'].'</A>';
			}
			echo '</TD></TR>';
		}
	}
	echo "</TABLE><br>";
	echo '</TABLE>';
	address_bottom();
	echo '</pied>';
	echo '</BODY>';
	echo '</HTML>';
	exit();
}

//if ($_SERVER['PHP_AUTH_USER'] == "sacristie" ) { 
//	echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?action=list_evenements&criteria=DateEve&order=DESC">';
//	exit;
//}


	//---------------------------------------------
	// Menu pour redirection Mariage, Bapteme, ...
	//---------------------------------------------

	address_top();
	Global $eCOM_db;
	$Debug=False;
	pCOM_DebugAdd($Debug, "Index:Menu");
	
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '</TABLE>';
	echo '</entete>';
	//echo '<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>';
	echo '<pied>';
	
	// section sommaire
	echo '<NAV>';
	//$requete = 'SELECT * FROM `Activites` T0 where T0.`id` = 2';
	//$result = mysqli_query($eCOM_db, $requete);
	//$row = mysqli_fetch_assoc($result);
	
	if (intval(date("n")) >= 8) {
		$_SESSION["Session"]=strval(intval(date("Y"))+1);
	} else {
		$_SESSION["Session"]=date("Y");
	}
	//$_SESSION["Session"] = $row[ActualSession];

	echo '<form method=post action="'.$_SERVER['PHP_SELF']. '">';
	echo "<table>";
	$trcolor = "#EEEEEE";
	echo '<TH width="40%" bgcolor='.$trcolor.'><font face=verdana size=2>Session</font></TH>';
	echo '<TH width="60%" bgcolor='.$trcolor.'><font face=verdana size=2>Actvités </font></TH>';


	echo '<TR>';
	echo '<TD ROWSPAN=10 align="center" bgcolor="#eeeeee">';
	if (intval(date("n")) >= 8 && intval(date("n")) <= 9) {
		echo '<font face=verdana size=2 color=green>Attention nous sommes passés en '.$_SESSION["Session"].' !</FONT><BR><BR>';
	} 
	
	echo '<SELECT name="SessionSelection">';
	echo '<option value="All">All</option>';
	for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
		if ($i == intval($_SESSION["Session"])) {
			echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
		} else {
			echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
		}
	}
	echo '</SELECT></font></TD>';
	
	$requete = 'SELECT id, Nom, Menu_Ordre, Menu_PHP_File FROM `Activites` WHERE Menu_Ordre > 0 ORDER BY Menu_Ordre ASC';
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result)) {
		if (fCOM_Get_Autorization($row['id']) >= 20) {
			pCOM_DebugAdd($Debug, "Index:Menu - ".$row['Nom']);
			echo '<TD bgcolor="#eeeeee"><input type="submit" name="Lancer_Activite" value="'.$row['Nom'].'"></TD></TR>';
		}
	}
	

	pCOM_DebugAdd($Debug, "Index : Autorization level=".fCOM_Get_Autorization( 0));
	
	if (fCOM_Get_Autorization( 0) >= 20 OR
		fCOM_Get_Autorization(87) >= 20 OR  // Accueil Messe
		fCOM_Get_Autorization(20) >= 20 OR  // Broadcast
		fCOM_Get_Autorization(47) >= 20 OR  // Projection
		fCOM_Get_Autorization(16) >= 20 OR  // Sacristie
		fCOM_Get_Autorization(51) >= 20 OR  // Animateur
		fCOM_Get_Autorization(19) >= 20 OR  // Sono
		fCOM_Get_Autorization(14) >= 20 OR  // Eveil à la Foi
		fCOM_Get_Autorization(90) >= 20	) { // Garderie
		echo '<TR><TD bgcolor="#eeeeee"><input type="submit" name="Messe_Acteurs" value="Messes et célébrations"></TD>';
		echo '<TD bgcolor="#eeeeee"> </TD></TR>';
	}
	echo '</TABLE>';
	echo '<TABLE>';
	if (fCOM_Get_Autorization( 0) >= 30 AND fCOM_Get_Autorization( 16) != 30) { 
		pCOM_DebugAdd($Debug, "Index : SuiviParoissien autorisé");

		echo '<TR><TD width="40%" bgcolor="#eeeeee"><FONT face=verdana size=2>Suivi Paroissiens : </FONT></TD><TD width="60%" bgcolor="#eeeeee"><input type="submit" name="Session_SP" value="Go"></TD>';
		echo '<TD bgcolor="#eeeeee">';
		//echo '<input type="submit" name="Session_SP" value="Go">';
		echo '</TD></TR>';
	}
	
	echo '</TR><TR>';
	echo '</FORM>';
	
	echo "</TABLE>";
	echo '</NAV>';
	
	//----------------------
	// section Anniversaire
	//----------------------
	echo '<anniv>';
	$trcolor = "#EEEEEE";

	$joursem = array('dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam');
	
	echo '<TABLE>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Quand</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Anniversaire</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Date</font></TH>';
	setlocale(LC_TIME, "fr_FR");
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';

	$requete = '(SELECT id, Nom, Prenom, "Naissance" as Type, Naissance as DateEv, (YEAR(CURRENT_DATE)-YEAR(Naissance)) AS Age, MOD((DATEDIFF(ADDDATE(Naissance, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Naissance)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours 
	FROM Individu 
	WHERE Naissance != "0000-00-00" AND Actif=1 AND Dead=0 )
	UNION ALL 
	(SELECT id, LUI_Nom As Nom, Concat(`ELLE_Prenom`, " et ",`LUI_Prenom`) as Prenom, "Mariage" as Type, Date_mariage as DateEv, (YEAR(CURRENT_DATE)-YEAR(Date_mariage)) AS Age, MOD((DATEDIFF(ADDDATE(Date_mariage, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Date_mariage)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours 
	FROM Fiancés 
	WHERE Date_mariage != "0000-00-00" and Date_mariage < CURRENT_DATE) 
	ORDER by NbJours, Prenom, Nom';
	
	$requete = '(SELECT id, Concat(Prenom, " ", Nom) AS Paroissien,  "Naissance" as Type, Naissance as DateEv, (YEAR(CURRENT_DATE)-YEAR(Naissance)) AS Age, MOD((DATEDIFF(ADDDATE(Naissance, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Naissance)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours 
	FROM Individu 
	WHERE Naissance != "0000-00-00" AND Actif=1 AND Dead=0)
	UNION ALL 
	(SELECT T4.`id` AS id, 
CONCAT((SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
FROM Individu T6
LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
WHERE T6.`Sex` = "F" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`), " et ",
(SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
FROM Individu T6
LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
WHERE T6.`Sex` = "M" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`)) As Paroissien,
"Mariage" AS Activité, T4.`Date_mariage` AS DateEve,
(YEAR(CURRENT_DATE)-YEAR(T4.`Date_mariage`)) AS Age, MOD((DATEDIFF(ADDDATE(T4.`Date_mariage`, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Date_mariage)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours
FROM `Fiancés` AS T4
WHERE T4.`Date_mariage` < now() and T4.`Date_mariage` != "0000-00-00 00:00:00") 
	ORDER by NbJours, Paroissien LIMIT 0,20';
	
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result ))
	{
		$trcolor = usecolor();
		if ($row['NbJours'] < 7) {
			$BoldText = '';
			if ($row['Paroissien'] != "" or ($row['Paroissien'] == "" and ($_SERVER['PHP_AUTH_USER'] == "administrateur" || fCOM_Get_Autorization( 0) >= 90))) {
				echo '<TR>';
				if ($row['NbJours'] == 0) {
					echo '<TR>';
					echo '<TD bgcolor='.$trcolor.'><B><font face=verdana size=1>aujourd\'hui</font></B></TD>';
					$BoldText = '<B>';
				} else if ($row['NbJours'] == 1) {
					echo '<TR>';
					echo '<TD bgcolor='.$trcolor.'><font face=verdana size=1>demain</font></TD>';
				} else {
					setlocale(LC_TIME, "fr_FR");
					echo '<TR>';
					echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=1>';
					$timestamp=mktime(0, 0, 0, date("m"), date("d")+$row['NbJours'], date("Y"));
					echo $joursem[date("w",$timestamp)];
					echo '</FONT></TD>';
				}
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=1>'.$row['Type'].'</font></TD>';
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=1>'.$BoldText;
				if ($row['Paroissien'] == "") {
					$Paroissien ="--";
				} else {
					$Paroissien = $row['Paroissien'];
				}
				if ($row['Type'] == "Naissance") {
					Display_Photo($Paroissien,"", $row['id'], "1");
				} else {
					if (file_exists("Photos/".$row['id'].".jpg")) { 
						echo '<A HREF=Mariage.php?action=edit&id='.$row['id'].' class="tooltip"><FONT SIZE="1">'.$Paroissien.'</FONT>';
						echo '<em><span></span>';
						echo '<img src="Photos/'.$row['id'].'.jpg" height="100" border="1" alt="couple_'.$row['id'].'">';
						echo '<br><font face=verdana size=2>'.$Paroissien.'</font>';
						echo '</em></a>';
					} else {
						echo '<A HREF=Mariage.php?action=edit&id='.$row['id'].'>';
						echo '<FONT SIZE="1">'.$Paroissien.'</FONT></A> ';
						echo '</A>';
					}
				}
				echo '</B></font></TD>';
				echo '<TD bgcolor='.$trcolor.'><font face=verdana size=1>'.strftime("%d/%m/%Y",fCOM_sqlDateToOut($row['DateEv'])).' ('.$row['Age'].' ans)</font></TD>';
				echo '</B></TR>';
			}
		}
	}
	//echo '<TD height="10" bgcolor="#F7F7F7">';
	echo '</TABLE>';
	echo '</anniv>';

	// Section pied de page
	address_bottom();
	echo '</pied>';
	echo '</BODY>';
	echo '</HTML>';

	
?>
