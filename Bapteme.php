<?php
session_start();
//==================================================================================================
//    Nom du module : Bapteme.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 18/04/2017 : Suppression du bouton reset lorsque pas gestionnaire en edition d'une fiche
// 22/04/2017 : AffecterBaseBapteme correction retour vers la bonne page après sauvegarde baptisé et $Qui remplacé par $_GET['Qui']
// 19/09/2017 correction de la date sous list_accompagnateur_sssession %m/%d/%y
//==================================================================================================
// 10/05/18 : Optimisation de l'affichage de la date du baptême (sans les secondes) dans le listing
//==================================================================================================



// Initialiser variable si elle n'existe pas
if( ! isset( $edit ) ) $edit = ""; 
if( ! isset( $delete_fiche_Bapteme ) ) $delete_fiche_Bapteme = ""; 
if( ! isset( $delete_fiche_Bapteme_confirme ) ) $delete_fiche_Bapteme_confirme = ""; 
if( ! isset( $Reunion_Present ) ) $Reunion_Present = ""; 
if( ! isset( $Reunion_Absent ) ) $Reunion_Absent = ""; 
if( ! isset( $Selectionner_Paroissien ) ) $Selectionner_Paroissien = ""; 
if( ! isset( $Selectionner_Individue ) ) $Selectionner_Individue = ""; 
if( isset ($_GET['Service']) ) $_SESSION["Activite_id"] = $_GET['Service'];
if( isset ($_POST['SessionSelection']) ) $_SESSION["Session"] = $_POST['SessionSelection'];


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


$debug = False;

$SessionEnCours=$_SESSION["Session"];
require('Menu.php');
require('Common.php');
$debug = True;
pCOM_DebugInit($debug);




function get_month_year_from_session($pSession)
{
	Global $eCOM_db;
	//echo "-".$_SESSION["Session"]." ".$_SESSION["Activite_id"]." ".trim($pSession);
	if (trim($pSession)==""){
			return $pSession;
	}
	$requete = 'SELECT T0.`Date`
	FROM `Rencontres` T0
	WHERE MID(T0.`Session`,1,4)='.$_SESSION["Session"].' and T0.`Activite_id`= '.$_SESSION["Activite_id"].' and T0.`Intitule` LIKE "%'.trim($pSession).'%"
	ORDER BY T0.`Date` DESC';

	$debug=False;
	pCOM_DebugAdd($debug, 'Bapteme:get_month_year_from_session - requete = '.$requete);
	$result = mysqli_query($eCOM_db, $requete);//, $db);
	$Date_de_la_Session = trim($pSession);
	while($row = mysqli_fetch_assoc($result)){
		if ($Date_de_la_Session == trim($pSession)) {
			$Date_de_la_Session = date("M Y",strtotime($row['Date']));
			//echo " resultat=".$row[`Date`];
		}
	}
	return ($Date_de_la_Session);
}


require('Paroissien.php');


//======================================
// Vue accompagnateur
//======================================

if ( isset( $_GET['action'] ) AND $_GET['action']=="list_accomp") {
//if ($action == "list_accomp") {
	Global $eCOM_db;
	global $debug;
	pCOM_DebugAdd($debug, "Bapteme.php:action=list_accomp ... en cours");
	
	//$_SESSION["RetourPage"]=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

    fMENU_top();
	fMENU_Title("Liste accompagnateurs ...");

    echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	echo '<TH> </TH>';
    echo '<TH>Accompagnateurs</TH>';
    echo '<TH>Adresse</TH>';
    echo '<TH>Téléphone et e_mail</TH>';
    echo '<TH>Baptisé</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
    $Total_pers = 0;

    if ($_SESSION["Session"]=="All") {
        $ExtraRequete='';
    } else {
        $ExtraRequete='AND T0.`Session`='.$_SESSION["Session"].'';
    }
   
     $requete = '(SELECT GROUP_CONCAT(CONVERT(T1.`id`, CHAR(50)) ORDER BY T1.`Sex` DESC SEPARATOR "_") AS id_Accompagnateur,
	CONCAT(GROUP_CONCAT( DISTINCT T1.`Nom` ORDER BY T1.`Sex` DESC), "<BR>",GROUP_CONCAT(T1.`Prenom` ORDER BY T1.`Sex` DESC SEPARATOR " et ")) AS Accompagnateur,
	T1.`Adresse` as Adresse,
	GROUP_CONCAT(DISTINCT T1.`Telephone` ORDER BY T1.`Sex` DESC SEPARATOR " ") as Telephone,
	GROUP_CONCAT(DISTINCT T1.`e_mail` ORDER BY T1.`Sex` DESC SEPARATOR "; ") as e_mail,
	T0.`Engagement_id`, CONCAT(T3.`Prenom`, " ", T3.`Nom`) AS Baptise, T2.`Baptise_id` AS Baptise_id, MID(T2.`Session`,1,4) AS Session, TRIM(MID(T2.`Session`,6,2)) as SS_Session
	FROM QuiQuoi T0
	LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id`
	LEFT JOIN Bapteme T2 ON T2.`id`=T0.`Engagement_id`
	LEFT JOIN Individu T3 ON T3.`id`=T2.`Baptise_id`
	WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' AND T0.`QuoiQuoi_id`=2 '.$ExtraRequete.' AND T0.`Engagement_id`<>0
	GROUP BY T0.`Engagement_id`
) UNION (
	SELECT GROUP_CONCAT(DISTINCT CONVERT(T1.`id`, CHAR(50)) ORDER BY T1.`Sex` DESC SEPARATOR "_") AS id_Accompagnateur,
	CONCAT(GROUP_CONCAT( DISTINCT T1.`Nom` ORDER BY T1.`Sex` DESC), "<BR>",GROUP_CONCAT(DISTINCT T1.`Prenom` ORDER BY T1.`Sex` DESC SEPARATOR " et ")) AS Accompagnateur,
	T1.`Adresse` as Adresse,
	GROUP_CONCAT(DISTINCT T1.`Telephone` ORDER BY T1.`Sex` DESC SEPARATOR " ") as Telephone,
	GROUP_CONCAT(DISTINCT T1.`e_mail` ORDER BY T1.`Sex` DESC SEPARATOR "; ") as e_mail,
	T0.`Engagement_id`, "" AS Baptise, 0 AS Baptise_id, "" AS Session, "" AS SS_Session
	FROM QuiQuoi T0
	LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id`
	WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' AND T0.`QuoiQuoi_id`=2 '.$ExtraRequete.' AND T0.`Engagement_id`=0
	GROUP BY T1.`Adresse`
) ORDER BY Accompagnateur, Session, SS_Session, Baptise';

    $Nom_Accompagnateur = "";
    $nb_personnes =0;
    $result = mysqli_query($eCOM_db, $requete);//, $db);
    while($row = mysqli_fetch_assoc($result)){
        if ($Nom_Accompagnateur != $row['Accompagnateur']) {
            $Nom_Accompagnateur = $row['Accompagnateur'];
            if ($nb_personnes > 0) {
				echo '</TR>';
            }
            $Total_pers = $Total_pers + $nb_personnes;
            $nb_personnes = substr_count($row['id_Accompagnateur'], '_')+1;
            echo '<TR>';
			echo '<td width="5"></td>';
			echo '<td>';
			fCOM_Display_Photo($row['Accompagnateur'], "", $row['id_Accompagnateur'], "edit_Individu", False);
			echo '</td>';

			echo '<TD>'.$row['Adresse'].'</TD>';
			echo '<TD>'.format_Telephone($row['Telephone'],"<BR>").'<BR>';
            echo '<A HREF="mailto:'.str_replace("\\", "", $row['e_mail']).'?subject= Préparation Baptême : " TITLE="Envoyer un mail à '.str_replace("<BR>", " ", $row['Accompagnateur']).'">'.str_replace("\\", "", $row['e_mail']).'</A></TD>';
            echo '<TD>';
            $retour_Chariot = '';
        }
		
        echo $retour_Chariot;
        
        if ($row['Baptise_id'] > 0) {
			$retour_Chariot = '<BR>';
			echo "- ";
			fCOM_Display_Photo($row['Baptise'], "", $row['Engagement_id'], "edit", true);
            $nb_personnes = $nb_personnes + 2;
        }
    }
	if ($nb_personnes > 0) echo '</TR>';
    echo '</tbody></TABLE><BR>';
    fMENU_bottom();
    exit();
}


//======================================
// Vue accompagnateur
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_accompagnateur_sssession") {

	Global $eCOM_db;
	$debug = false;
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	fMENU_top();

	//echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	//echo '<TR BGCOLOR="#F7F7F7">';
	//echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Liste accompagnateurs</B><BR>';
	//echo '</TD></TR>';
	//echo '<TR><TD BGCOLOR="#EEEEEE">';
	fMENU_Title ("Liste accompagnateurs");
	
	$Total_pers = 0;
	$MemoAccompagnateur="@@@@@";
	$MemoSSession=".";
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter=1;
	
	echo '<table class="table table-bordered table-hover table-sm">';
	echo '<thead><tr>';
	$trcolor = "#EEEEEE";
	echo '<th scope="col">Session</th>';
	echo '<th scope="col">Enfant</th>';
	echo '<th scope="col">Parents</th>';
	echo '<th scope="col">Téléphone</th>';
	echo '<th scope="col">Date</th>';
	echo '<th scope="col">Heure</th>';
	echo '<th scope="col">Lieu</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	$Total_pers = 0;

	$WhereRequete = '';
	if ($_SESSION["Session"]!="All") {
		$WhereRequete = ' and MID(T0.`Session`,1,4)="'.$_SESSION["Session"].'" ';
	}
	$requete = 'Select T0.`id`, T0.`Baptise_id`, count(T5.`Nom`) as Nb_Accomp, T0.`Session`, TRIM(MID(T0.`Session`,6,2)) as SS_Session, Concat(T1.`Prenom`, " ",T1.`Nom`) as Baptise, Concat(T2.`Prenom`, " ",T2.`Nom`,"<BR>",T3.`Prenom`, " ",T3.`Nom`) as Parents, IF(STRCMP(T2.`Telephone`, T3.`Telephone`),Concat(T2.`Telephone`, " ", T3.`Telephone`),T2.`Telephone`) as Parents_tel, IF(STRCMP(T2.`e_mail`, T3.`e_mail`),Concat(T2.`e_mail`, "; <BR>", T3.`e_mail`),T2.`e_mail`) as Parents_e_mail, Concat(GROUP_CONCAT(Concat(T5.`Prenom`) ORDER BY T5.`Sex` DESC SEPARATOR " et "), " ", T5.`Nom`) as Accompagnateur, T6.`Lieu`, T0.`Date`
		from `Bapteme` as T0
		Left join `Individu` as T1 on T1.`id`=T0.`Baptise_id`
		Left join `Individu` as T2 on T2.`id`=T1.`Mere_id`
		Left join `Individu` as T3 on T3.`id`=T1.`Pere_id`
		Left join `QuiQuoi` as T4 on T4.`Engagement_id`=T0.`id`
		Left join `Individu` as T5 on T5.`id`=T4.`Individu_id`
		Left join `Lieux` as T6 on T6.`id`= T0.`Lieu_id`
		WHERE T4.`Activite_id`='.$_SESSION["Activite_id"].' and T4.`QuoiQuoi_id`=2 '.$WhereRequete.' 
		group by Baptise 
		Order by SS_Session DESC, Accompagnateur';
		
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
			
		if ((strpos($MemoAccompagnateur, $row['Accompagnateur'])===false && strpos($row['Accompagnateur'], $MemoAccompagnateur)===false) || ($MemoSSession != $row['SS_Session'])){
			if ($MemoSSession != $row['SS_Session'] && ($MemoSSession != "" || $row['SS_Session'] != "") ){
				echo '<TR>';
				echo '<TD align="left" bgcolor="#0000CD" colspan=7><font face=verdana color="CCCCCC" size=3>Sous session (No '.str_replace(" ", " - ",$row['Session']).') '.get_month_year_from_session(substr($row['Session'],4)).'</font></TD>';
				echo "</TR>";
			}
			echo '<TR>';
			if ($_SESSION["Session"]=="All") {
				$TitreLigneSession=$row['Accompagnateur'].' ('.$row['Session'].')';
			} else {
				$TitreLigneSession=$row['Accompagnateur'].' ('.$row['SS_Session'].')';
			}
			echo '<TD align="left" bgcolor="#A1A1A1" colspan=7><FONT face=verdana size=2>'.$TitreLigneSession.'&nbsp&nbsp <i class="fa fa-long-arrow-right"></i> </FONT><FONT face=verdana size=2>';
			echo '<A HREF="load/ListeMail_'.$File_Counter.'.php">Récupérer liste e_mail</A></FONT></TD>';
				
			if ($File_Counter==2) {
					// fermeture du fichier avant d'ouvrir le suivant
				fwrite($handle, "</TD></TR><TR><TD> </TD></TR><TR><TD> </TD></TR><TR><TD>\r\n\r\n\r\n");
				fwrite($handle, "<FONT face=verdana size=2>");
				fwrite($handle, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
				fwrite($handle, "</FONT></td></tr></table>\r\n");
				fwrite($handle, "</body>\r\n</html>\r\n");
				fclose($handle);
			}
			$temp = "load/ListeMail_".$File_Counter.".php";
			$handle = fopen($temp, 'w');
			fwrite($handle, "<html><head><title>Liste adresses mail</title></head>\r\n<body><br>");
			fwrite($handle, "<h1><FONT face=verdana>Liste des adresses mail : ".$TitreLigneSession."</FONT></h1>\r\n");
			fwrite($handle, "<FONT face=verdana size=2>");
			fwrite($handle, "<p>Date : ".$aujourdhui."</p>\r\n");
			fwrite($handle, "<p>===================================================</p><br>\r\n<table>");
			echo "<tr><td><font face=verdana size=2>";
			fwrite($handle, "<FONT face=verdana size=2>");
			$File_Counter += 1;

			//echo '<td align="center" bgcolor="#A1A1A1" colspan=4>';
			//echo "<font face=verdana size=2>Prévoir ".$Total_pers." couverts.</font></td>";
			echo "</TR>";
			$MemoAccompagnateur = $row['Accompagnateur'];
			$MemoSSession = $row['SS_Session'];
			$Total_pers = 0;
		}

		$nb_personnes = 1;
		$trcolor = usecolor();
		if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Date'])) == "01/01/70" ) {
			$Date="-";
			$Heure="-";
		} else {
			setlocale(LC_TIME,"fr_FR");
			$Date=ucwords(strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Date'])));
			$Heure=ucwords(strftime("%H:%M", fCOM_sqlDateToOut($row['Date'])));
		}
		echo '<tr role="button" data-href="'.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].'">';
		if ($_SESSION["Session"]=="All") {
			echo '<td>'.$row['Session'].'</td>';
		} else {
			echo '<td>'.$row['SS_Session'].'</td>';
		}
			
		echo '<td>';
		fCOM_Display_Photo($row['Baptise'], "", $row['Baptise_id'], "edit_Individu", False);
		echo '</td>';
			
		echo '<TD>';
		$Simplifier = array("\\", "<BR>");
		$NomParents=str_replace("<BR>", " et ", $row['Parents']);
		$AddParents=str_replace($Simplifier, "", $row['Parents_e_mail']);
        echo '<A HREF="mailto:'.$AddParents.'?subject= Préparation Baptême : " TITLE="Envoyer un mail à '.$NomParents.'">'.$row['Parents'].'</A>';
		echo '</TD>';
		fwrite($handle, '"'.$NomParents.'"< '.$AddParents.'>; ');
			
		echo '<td>'.format_Telephone($row['Parents_tel'], " ").'<BR>'.$AddParents.'</TD>';

		echo '<TD>'.$Date.'</TD>';
		echo '<TD>'.$Heure.'</TD>';
		echo '<TD>'.$row['Lieu'].'</TD>';
		echo '</TR>';

	}
	echo '</tbody>';
	echo '</table>';
	fwrite($handle, "</TD></TR><TR><TD> </TD></TR><TR><TD> </TD></TR><TR><TD>\r\n\r\n\r\n");
	fwrite($handle, "<FONT face=verdana size=2>");
	fwrite($handle, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
	fwrite($handle, "</FONT></td></tr></table>\r\n");
	fwrite($handle, "</BODY>\r\n</html>\r\n");
	fclose($handle);
	fMENU_bottom();
	exit();
}		
	
//======================================
// Vue Financiere
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="vue_financiere") {
//if ($action == "vue_financiere")
	Global $eCOM_db;
	$debug = false;

	fMENU_top();
	fMENU_Title("Vue financière");
	
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	$trcolor = "#EEEEEE";
	echo '<TH>Participation</TH>';
	echo '<TH>Baptisés</TH>';
	echo '<TH>Date</TH>';
	echo '<TH>Session</TH>';
	echo '<TH>Accompagnateurs</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
	$WhereRequete = '';
	if ($_SESSION["Session"]!="All") {
		$WhereRequete = 'WHERE MID(T0.`Session`,1,4)='.$_SESSION["Session"].' ';
	}
	$requete = 'SELECT T0.`id` AS T0id, T1.`id` AS T1id, T0.`Session` AS Session, T1.`Nom`, T1.`Prenom`, T0.`Finance` AS Finance, T2.`Nom` AS Celebrant, T3.`Nom` AS Accompagnateur, T0.`date` AS Date, T4.`Lieu` As Lieu 
		FROM `Bapteme` T0 
		LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` 
		LEFT JOIN `Individu` T2 ON T0.`Celebrant_id`=T2.`id` 
		LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` 
		LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id` 
		'.$WhereRequete.'
		ORDER BY T0.`date` DESC';

	pCOM_DebugAdd($debug, 'Bapteme:vue_financiere - requete01 = '.$requete);
	$result = mysqli_query($eCOM_db, $requete);
	$total = 0;
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['T0id'].'\')"';
		if ($row['Finance'] > 0) { 
			$fgcolorOk = ' class="table-success"'; // "green"
		} else {
			$fgcolorOk = '';
		}
		
		echo "<TR>";
		
		// participation financière
		echo '<td align="right" width="35" '.$TD_Click.'>'.$row['Finance'].' €</TD>';
		$total = $total + $row['Finance'];
		
		// Baptisé
		echo '<TD '.$TD_Click.$fgcolorOk.'>';
		fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['T0id'], "edit_Individu", False);
		echo "</TD>";
		
		// Date du baptême
		echo '<TD '.$TD_Click.'>';
		echo substr($row['Date'], 0, 16);
		echo '</TD>';
		
		// Accompagnateur
		echo '<TD '.$TD_Click.'>' . substr($row['Session'],4) . '</FONT></TD>';
		$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom`, T1.`Sex` 
			FROM `QuiQuoi` T0 
			LEFT JOIN `Individu` T1 ON T1.`id` = T0.`Individu_id` 
			WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' and T0.`Engagement_id`='.$row['T0id'].' and T0.`QuoiQuoi_id`=2 
			ORDER BY T1.`Nom`, T1.`Sex` DESC';
		pCOM_DebugAdd($debug, 'Bapteme:vue_financiere - requete02 = '.$requete);
		$resultat = mysqli_query($eCOM_db,  $requete );
		echo '<TD '.$TD_Click.'>';
		$Nom = "";
		while( $Row_Accomp = mysqli_fetch_assoc( $resultat ))
		{
			if ($Nom == $Row_Accomp['Nom']) {
				echo " et ".$Row_Accomp['Prenom']."";
			} else {
				if ($Nom != "") {echo "<BR>";}
				echo "".$Row_Accomp['Nom']." ".$Row_Accomp['Prenom']."";
				$Nom = $Row_Accomp['Nom'];
			}
		}
		echo "</TD>";
		

		echo "</TR>";
	}
	$trcolor = usecolor();
	echo '</tbody></table>';
	echo '<table bgcolor=#EEEEEE><TR><TD></TD><TD></TD><TD></TD><TD></TD><TD><FONT face=verdana size=2><B>Total</B></FONT></TD><TD><FONT face=verdana size=2><B>'.$total.' €</B></FONT></TD></FONT></TR>';
	echo '</TABLE>';
	fMENU_bottom();
	exit();
}



//view profiles
if ( isset( $_GET['action'] ) AND $_GET['action']=="profile") {
//if ($action == "profile")
	Global $eCOM_db;
	$result = mysqli_query($eCOM_db, "SELECT * FROM ".$Table." WHERE id = ".$id." ");//, $db);
	while($row = mysqli_fetch_assoc($result))
	{ 
	?>
	<html>
	<head>
	</head>
	<body bgcolor="#FFFFFF" link=blue vlink=blue alink=blue>
	<font face="verdana"><center>
	<?php
	if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire" || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
		echo "<TABLE border=0 cellpadding=1 cellspacing=1 bgcolor=00000>";
		echo "<TR><td bgcolor=#eeeeee> : <img src=\"images/profile.gif\" border=0 alt='View Profile'> : <A HREF=Bapteme.php?action=edit&id=".$row['id']." target=new><img src=\"images/edit.gif\" border=0 alt='Edit Record'></A> : ";
		echo "<A HREF=Bapteme.php?action=printid&id=".$row['id']." target=_blank><img src=\"images/print.gif\" border=0 alt='Print Record'></a> :";
		echo "</td></TR></table> ";
	}
	else {
		echo "<table border=0 cellpadding=1 cellspacing=1 bgcolor=00000>";
		echo "<TR><td bgcolor=#eeeeee> : <img src=\"images/profile.gif\" border=0 alt='View Profile'> : <A HREF=Bapteme.php?action=edit&id=".$row['id']." target=new><img src=\"images/edit.gif\" border=0 alt='Edit Record'></A> : ";
		echo "<A HREF=Bapteme.php?action=printid&id=".$row['id']." target=_blank><img src=\"images/print.gif\" border=0 alt='Print Record'></a> :";
		echo "</TD></TR></TABLE> ";
	} 
	
	echo "<h3>Fiche de renseignement de ".$row['LUI_Prenom']." ".$row['LUI_Nom'].", ".$row['ELLE_Prenom']." ".$row['ELLE_Nom'];
	echo "<TABLE border=1 cellpadding=2 cellspacing=0 bordercolor=#000000 width='95%' bgcolor=eeeeee>";
	echo "<TR><TD><FONT face=verdana size=2>Lui Prenom:</TD><TD><FONT face=verdana size=2>".$row['LUI_Prenom']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Lui Nom:</TD><TD><FONT face=verdana size=2>".$row['LUI_Nom']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Elle Prenom:</TD><TD><FONT face=verdana size=2>".$row['ELLE_Prenom']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Elle Nom:</TD><TD><FONT face=verdana size=2>".$row['ELLE_Nom']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Lieu du Baptême:</TD><TD><FONT face=verdana size=2>".$row['Lieu_mariage']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Date du Baptême:</TD><TD><FONT face=verdana size=2>".$row['Date_Mariage']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Celebrant:</TD><TD><FONT face=verdana size=2>".$row['Celebrant']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Accompagnateur:</TD><TD><FONT face=verdana size=2>".$row['Accompagnateurs']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Téléphone:</TD><TD><FONT face=verdana size=2>".$row['Telephone']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Email:</TD><TD><FONT face=verdana size=2>".$row['Email']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Adresse:</TD><TD><FONT face=verdana size=2>".$row['Adresse']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Enfant:</TD><TD><FONT face=verdana size=2>".$row['Enfant']."</TD></TR>";
	echo "<TR><TD><FONT face=verdana size=2>Commentaire:</TD><TD><FONT face=verdana size=2>".$row['Commentaire']."</TD></TR>";
	echo "</TABLE><br>";
	echo "<FONT size='2'>";
	?>
	<A HREF="javascript:window.close()">Close Window</A> | 
	<a href="javascript:location.reload()" target="_self">Refresh/Reload</A>
	<?php
	exit();	
	}
}



//==========================================================
//edit records Bapteme
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit") {

	Global $eCOM_db;
	$debug = False;
	
	$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	if ($_SESSION["RetourPage"] == "") {
		$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	if ( $_GET['id'] == 0 ) {
		// creation d'une nouvelle fiche impossible si pas gestionnaire ou administrateur
		if ($_SERVER['USER'] > 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			
			// avant de créer une nouvelle fiche on essaie d'en trouver une déjà vide
			$id = 0;
			$Requete = 'SELECT id FROM Bapteme WHERE Baptise_id=0 AND SESSION="" ORDER BY id DESC';
			$result = mysqli_query($eCOM_db, $Requete);
			while( $row = mysqli_fetch_assoc( $result ))	{
				$id = $row['id'];
			}
			if ($id == 0){
				mysqli_query($eCOM_db, 'INSERT INTO Bapteme (id, Baptise_id, Date, Lieu_id, Celebrant_id, Accompagnateur_id, Activite_id, Commentaire, Finance) VALUES (0,0,0,0,0,0,'.$_SESSION["Activite_id"].',"","")') or die (mysqli_error($eCOM_db));
				$id=mysql_insert_id();
			}
			$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit&id='.$id;
		} else {
		   echo '<META http-equiv="refresh" content="0; URL=/Bapteme.php">';
		}
	} else {
		$id= $_GET['id'];
		$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	$requete = 'SELECT T0.`id`, T0.`MAJ`, T0.`Baptise_id`, T1.`Nom`, T1.`Prenom`, T1.`Naissance`, T1.`Bapteme` as Date, T1.`Sex` AS Sex, T0.`Parrain`, T0.`Marraine`, T0.`Celebrant_id`, T2.`Nom` AS Celebrant_Nom, T2.`Prenom` AS Celebrant_Prenom, T0.`Accompagnateur_id`, T3.`Nom` AS Accompagnateur_Nom,  T3.`Prenom` AS Accompagnateur_Prenom, T0.`Session`, T0.`Reunion`, T0.`date` As Date2, T4.`Lieu` As Lieu, T0.Aspersion_Immersion, T0.`Dossier_Renseigne`, T0.`Livret_de_famille`, T0.`Extrait_Naissance`, T0.`Commentaire`, T0.`Finance` 
				FROM `Bapteme` T0 
				LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` 
				LEFT JOIN `Individu` T2 ON T0.`Celebrant_id`=T2.`id` 
				LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` 
				LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id`  
				WHERE T0.id='.$id.' ';

	$result = mysqli_query($eCOM_db, $requete);
	$row = mysqli_fetch_assoc($result);
		
	fMENU_top();
	if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Edition: ';
	if ($_GET['id'] == 0) {
		echo 'Nouvelle fiche baptême No '.$id.'</B></FONT></TD>';
	} else {
		echo 'Fiche baptême No '.$row['id'].'</B></FONT></TD>'; 
		if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['MAJ'])) != "01/01/70" ) {
			echo '<TD align="right"><FONT FACE="Verdana" SIZE="1"> (Dernière modification au '.$row['MAJ'].')</TD>';
		}
	}
	echo '</TR>';
	
	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2"><CENTER><font face="verdana" size="2">';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<TR><TD width="140" bgcolor="#eeeeee"><FORM method=post action="'.$_SERVER['PHP_SELF'].'"><B><FONT SIZE="3"> </FONT></B></TD></TR>';
	echo '<TR><TD>';
	
	//if ( $_GET['id'] > 0 ) {
		if (( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 ) AND $row['Baptise_id'] == 0) {
			echo '<div><INPUT type="submit" name="Selectionner_Individue" value="Sélectionner le(la) Baptisé(e)"></TD>';
		} else {
			if ($row['Sex']=='F'){
				echo '<B><FONT SIZE="2">La Baptisée :</FONT></B></TD>';
			} else {
				echo '<B><FONT SIZE="2">Le Baptisé :</FONT></B></TD>';
			}
		}
		if ( $row['Baptise_id'] > 0 ) { 
			echo '<TD width="400">';
			if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['Baptise_id'], "edit_Individu", True);
			} else {
				fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['Baptise_id'], "edit_Individu", False);
			}
			
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Naissance'])) != "01/01/70" ) {
				echo '<FONT FACE="Verdana" SIZE="1"> ('.fCOM_Afficher_Age($row['Naissance']).' ans)';
			}
			echo '</TD>';
		}
	//}
	echo "</TD></TR>";
	
	// Photo ==================================
	
	if (file_exists("Photos/Individu_".$row['Baptise_id'].".jpg") AND
		$row['Baptise_id'] > 0) { 
		echo '<TR><TD></TD><TD>';
		echo '<IMG SRC="Photos/Individu_'.$row['Baptise_id'].'.jpg" HEIGHT=150><BR><BR>';
		echo '</TD></TR>';
	}
	
	
	// Date ==================================
	echo '<TR><TD bgcolor="#eeeeee">';
	if ( $_GET['id'] > 0 ) {

		pCOM_DebugAdd($debug, "Bapteme:Edit Date(2) - ".$row['Date2']);
		
		echo '<B><FONT SIZE="2">Date du baptême:</FONT></B>';
		echo '</TD><TD width="225" bgcolor="#eeeeee">';
		echo '<div class="form-row">';
		?>
		<input type="date" id="Date" class="form-control form-control-sm" name="Date" style="width:140px"<?php if ( $id > 0 ) {echo ' value ="'.substr($row['Date2'],0,10).'"';} ?> size="9" maxlength="10"  <?php echo $BloquerAcces;?>>
		
		<?php
		echo '<b><FONT SIZE="2">&nbsp Heure &nbsp</FONT></b>';
		?>
		
		<input type="time" id="Heure" class="form-control form-control-sm" style="width:90px" name="heure" <?php if ( $id > 0 ) {echo ' value ="'.substr($row['Date2'],11,5).'"';} ?> size="9" maxlength="5" <?php echo $BloquerAcces;?>>
		<?php
		echo '</div>';
	}	
	echo '</TD></TR>';
	
	// Lieu ==============================================
	echo '<TR><TD bgcolor="#eeeeee">';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Lieu du baptême:</FONT></B>';
		echo '</TD><TD bgcolor="#eeeeee">';
		echo '<SELECT name="LieuBapt" class="form-control form-control-sm" '.$BloquerAcces.' >';
		$Liste_Lieu_Celebration = pCOM_Get_liste_lieu_celebration(1000);
		$Lieu_Celebration_trouve = false;
		foreach ($Liste_Lieu_Celebration as $Lieu_Celebration){
			//$Lieu_name=substr($Lieu_Celebration, strpos($Lieu_Celebration, ' ')+1); // retourne la 2eme partie de la chaine après " " (Nom Prenom)
			list($Lieu_id, $Lieu_name) = $Lieu_Celebration;
			if ( $_GET['id'] > 0 ) {
				if ($row['Lieu'] == $Lieu_name){
					echo '<option value="'.$Lieu_name.'" selected="selected">'.$Lieu_name.'</option>';
					$Lieu_Celebration_trouve = true;
				} else {
					echo '<option value="'.$Lieu_name.'">'.$Lieu_name.'</option>';
				}
			} else {
				echo '<option value="'.$Lieu_name.'">'.$Lieu_name.'</option>';
			}
		}
		echo '</SELECT>';
	}
	echo'</TD></TR>';

	// Type de bâpteme par aspersion ou immersion =====================
	echo '<TR><TD bgcolor="#eeeeee">';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Type de baptême:</FONT></B>';
		echo '</TD><TD bgcolor="#eeeeee">';
	
		echo '<SELECT name="TypeBapt" class="form-control form-control-sm" '.$BloquerAcces.' >';
		$Liste_Type_Bapteme = array("Pas défini", "Baptême par aspersion", "Baptême par immersion");
		$NumTypBapt=0;
		foreach ($Liste_Type_Bapteme as $Type_Bapteme){
			if ( $_GET['id'] > 0 ) {
				if ($row['Aspersion_Immersion'] == $NumTypBapt){
					echo '<option value="'.$NumTypBapt.'" selected="selected">'.$Type_Bapteme.'</option>';
				} else {
					echo '<option value="'.$NumTypBapt.'">'.$Type_Bapteme.'</option>';
				}
			} else {
				echo '<option value="'.$NumTypBapt.'">'.$Type_Bapteme.'</option>';
			}
			$NumTypBapt = $NumTypBapt + 1;
		}
		echo '</SELECT>';
	}
	echo '</TD></TR>';
	
	// Dossier d'inscription ==========================================
	echo '</TD></TR>';
	if ( $_GET['id'] > 0 ) {
		echo '<TR><TD bgcolor="#eeeeee">';
		echo '<b><FONT SIZE="2"> </FONT></b>';
		echo '</TD><TD bgcolor="#eeeeee" valign="top">';
		echo '<p>';
		if ($row['Extrait_Naissance'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="SExtrait_Naissance" '.$BloquerAcces.' id="SExtrait_Naissance" ' .$optionSelect .' /> <label for="SExtrait_Naissance"><FONT SIZE="2">Acte de Naissance</b></label>';
		if ($row['Dossier_Renseigne'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '&nbsp&nbsp<input type="checkbox" name="SDossier_Renseigne" '.$BloquerAcces.' id="SDossier_Renseigne" ' .$optionSelect .' /> <label for="SDossier_Renseigne"><FONT SIZE="2">Dossier renseigné<br></b></label>';
		if ($row['Livret_de_famille'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<BR><input type="checkbox" name="SLivret_de_famille" '.$BloquerAcces.' id="SLivret_de_famille" ' .$optionSelect .' /> <label for="SLivret_de_famille"><FONT SIZE="2">Livret_de_famille (si catholique)</b></label>';
		echo '</p>';
	}

	// Parents ==========================================
	echo '<TR><TD></TD></TR><TR></TR><TR><TD valign="top">';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Parents:</FONT></B>';
		
		$requete = 'SELECT T0.`id` as id_Baptise, T1.`id` as id_Pere, T1.`Nom` as Nom_Pere, T1.`Prenom` as Prenom_Pere, T1.`Souhaits` as Souhait_Pere, T2.`id` as id_Mere, T2.`Nom` as Nom_Mere, T2.`Prenom` as Prenom_Mere, T2.`Souhaits` as Souhait_Mere 
		FROM `Individu` T0 
		LEFT JOIN `Individu` T1 ON T0.`Pere_id`=T1.`id` 
		LEFT JOIN `Individu` T2 ON T0.`Mere_id`=T2.`id` 
		WHERE T0.`id`='.$row['Baptise_id'];
		
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result )) {
			
			if ( $row2['id_Baptise'] > 0 ) {

			// MERE
				
				if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					echo '<div style="display:inline"><input type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Paroissien" value="Mère">';
					echo '<input type="hidden" name="id_Individu" value="'.$row2['id_Baptise'].'">';
					echo '<input type="hidden" name="Genre" value="F">';
					echo '<input type="hidden" name="RetourPage" value="SaisieBapteme">';
					echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
				}
				
				if ( $row2['id_Mere'] > 0 ) {
					if ( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
						echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendantParent&Qui=Mere&id_Individu='.$row['Baptise_id'].' TITLE="Desélectionner Mère"> <i class="fa fa-minus-circle text-danger"></i></a>  ';
						fCOM_Display_Photo($row2['Nom_Mere'], $row2['Prenom_Mere'], $row2['id_Mere'], "edit_Individu", True);
					} else {
						//echo '<FONT SIZE="2">'.$row2['Prenom_Mere'].' '.$row2['Nom_Mere'].'</FONT>';
						fCOM_Display_Photo($row2['Nom_Mere'], $row2['Prenom_Mere'], $row2['id_Mere'], "edit_Individu", False);
					}
					echo '<BR>';
					$requete = 'SELECT * FROM `Souhaits` ORDER BY `Libelle` '; 
					$result = mysqli_query($eCOM_db, $requete);
					while( $row3 = mysqli_fetch_assoc( $result )) 
					{
						if (((int)$row2['Souhait_Mere'] & (int)$row3['Code']) > 0 and $row3['Code'] > 1)
						{
							echo '<span align="center"><button name="delete_souhait" value="'.$row3['Libelle'].'" type="submit" disabled="disabled" style="background-color:PaleGreen">'.$row3['Libelle'].'</button>';
							echo '</span>';
						}
					}
				}
				if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					echo '</TD></TR><TD></TD><TD>';
				}

				// PERE
				
				if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					echo '<div style="display:inline"><input type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Paroissien" value="Père">';
					echo '<input type="hidden" name="id_Individu" value="'.$row2['id_Baptise'].'">';
					echo '<input type="hidden" name="Genre" value="M">';
					echo '<input type="hidden" name="RetourPage" value="SaisieBapteme">';
					echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
				}
				
				if ( $row2['id_Pere'] > 0 ) {
					if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
						echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendantParent&Qui=Pere&id_Individu='.$row['Baptise_id'].' TITLE="Desélectionner Père"> <i class="fa fa-minus-circle text-danger"></i></a>  ';
						fCOM_Display_Photo($row2['Nom_Pere'], $row2['Prenom_Pere'], $row2['id_Pere'], "edit_Individu", True);
					} else {
						//echo '<FONT SIZE="2">'.$row2['Prenom_Pere'].' '.$row2['Nom_Pere'].'</FONT>';
						fCOM_Display_Photo($row2['Nom_Pere'], $row2['Prenom_Pere'], $row2['id_Pere'], "edit_Individu", False);
					}
					echo '</TR><TR><TD></TD><TD>';
					$requete = 'SELECT * FROM `Souhaits` ORDER BY `Libelle` '; 
					$result = mysqli_query($eCOM_db, $requete);
					while( $row3 = mysqli_fetch_assoc( $result )) 
					{
						if (((int)$row2['Souhait_Pere'] & (int)$row3['Code']) > 0 and $row3['Code'] > 1)
						{
							echo '<span align="center"><button name="delete_souhait" value="'.$row3['Libelle'].'" type="submit" disabled="disabled" style="background-color:PaleGreen">'.$row3['Libelle'].'</button>';
							echo '</span>';
						}
					}
				}
			}
		}
		echo '</TD>';

	}
	echo '</TD></TR><TR><TD> </TD></TR>';
	
	// Session et sous session ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT FACE="Verdana" SIZE="2">Session:</FONT></B></TD><TD>';
		echo '<div class="form-row">';
		echo '<SELECT name="Session_entered" class="form-control form-control-sm" style="width:135px" '.$BloquerAcces.'>';
		for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
			if ($row['Session'] == "" && $i == intval($_SESSION["Session"]) or $i == intval(substr($row['Session'],0, 4))) {
				echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
			} else {
				echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
			}
		}
		echo '</SELECT>';
		echo '<B><FONT SIZE="2"> </FONT></B>';
	
		echo '<SELECT name="ss_session" class="form-control form-control-sm" style="width:160px" '.$BloquerAcces.'>';
		echo '<option value=" "> </option>';
		$Liste_SsSession= array ("EXTERIEUR", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "AUTRE PREPARATION");
		foreach ($Liste_SsSession as $i){
			$i_formated=sprintf('%02d',$i);
			//if ($i_formated == intval(substr($row[Session],5))) {
			if (substr($i, 0, 3) == substr($row['Session'],5)) {
				$OptionSelect = 'selected="selected"';
			} else {
				$OptionSelect = '';
			}
			echo '<option value="'.substr($i, 0, 3).'" '.$OptionSelect.'>'.$i;
			if ( $i != get_month_year_from_session($i)) {
				echo ' - '.get_month_year_from_session($i);
			}
			echo '</option>';
		}
		echo '</SELECT>';
		echo '</div>';
	}
	echo '</TD></TR>';
	
	// Participation Réunion ===========================================
	$debug=false;
	if (( $_SERVER['USER'] <= 3 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) AND $_GET['id'] > 0 ) { // différent que user sacristie
		echo '<TR><TD><B><FONT FACE="Verdana" SIZE="2">Participation Réunions:</FONT></B></TD><TD>';
		pCOM_DebugAdd($debug, "Bapteme:edit - Reunion = ".$row['Reunion']);
		if ( ((int)$row['Reunion'] & (int)2) > 0) {
			//echo '<span align="center"><button name="Reunion_Absent" value="2" type="submit" '.$BloquerAcces.' style="background-color:PaleGreen"> 1 </button>';
			echo '<span align="center"><button name="Reunion_Absent" value="2" type="submit" class="btn btn-success btn-sm" class="btn btn-outline-success btn-sm" title="Supprimer présence à la 1ère soirée">1</button>';
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Reunion_Present" value="2" type="submit" class="btn btn-secondary btn-sm" class="btn btn-outline-secondary btn-sm" title="Ajouter présence à la 1ère soirée"> 1 </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)4) > 0) {
			echo ' <span align="center"><button name="Reunion_Absent" value="4" type="submit" class="btn btn-success btn-sm" class="btn btn-outline-success btn-sm" title="Supprimer présence à la 2ème soirée"> 2 </button>';
			echo '</span>';
		} else {
			echo ' <span align="center"><button name="Reunion_Present" value="4" type="submit" class="btn btn-secondary btn-sm" class="btn btn-outline-secondary btn-sm" title="Ajouter présence à la 2ème soirée"> 2 </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)16) > 0) {
			echo ' <span align="center"><button name="Reunion_Absent" value="16" type="submit" class="btn btn-success btn-sm" class="btn btn-outline-success btn-sm" title="Supprimer présenté à la messe"> Messe </button>';
			echo '</span>';
		} else {
			echo ' <span align="center"><button name="Reunion_Present" value="16" type="submit" class="btn btn-secondary btn-sm" class="btn btn-outline-secondary btn-sm" title="Ajouter présenté à la messe"> Messe </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)8) > 0) {
			echo ' <span align="center"><button name="Reunion_Absent" value="8" type="submit" class="btn btn-success btn-sm" class="btn btn-outline-success btn-sm" title="Supprimer présence à la 3ème soirée"> 3 </button>';
			echo '</span>';
		} else {
			echo ' <span align="center"><button name="Reunion_Present" value="8" type="submit" class="btn btn-secondary btn-sm" class="btn btn-outline-secondary btn-sm" title="Ajouter présence à la 3ème soirée"> 3 </button>';
			echo '</span>';
		}
		echo '</TD></TR>';
	}
	
	// Accompagnateur ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		if ( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			echo '<DIV><INPUT type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Individue" value="Accompagnateur"></TD>';
		} else {
			echo '<B><FONT SIZE="2">Accompagnateur :</FONT></B></TD>';
		}
		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` FROM `Individu` T0 LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` WHERE T1.`Activite_id`='.$_SESSION["Activite_id"].' and T1.`QuoiQuoi_id`=2 and T1.`Engagement_id`=' . $id . ' ';
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAccompagnateur&Qui_id='.$row2['id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Accompagnateur"> <i class="fa fa-minus-circle text-danger"></i></A>  '; 
				fCOM_Display_Photo($row2['Nom'], $row2['Prenom'], $row2['id'], "edit_Individu", True);
			} else {
				fCOM_Display_Photo($row2['Nom'], $row2['Prenom'], $row2['id'], "edit_Individu", False);
				//echo '<FONT SIZE="2">' .$row2['Prenom']. ' ' .$row2['Nom']. '</FONT>';
			}
			echo '</TR><TR><TD></TD><TD>';
		}
		echo '</TD>';

	}
	echo '</TD></TR>';
	
	
	// Celebrant Principal ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		if ( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			echo '<DIV><input type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Individue" value="Célébrant Principal"></DIV></TD>';
		} else {
			echo '<B><FONT SIZE="2">Célébrant principal:</FONT></B></TD>';
		}
		if ( $row['Celebrant_id'] > 0 ) { 
			echo '<TD>';
			if ( $BloquerAcces=="") {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerCelebrantPrincipal&Qui_id='.$row['Celebrant_id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Célébrant"> <i class="fa fa-minus-circle text-danger"></i></A>  '; 
				
				fCOM_Display_Photo($row['Celebrant_Nom'], $row['Celebrant_Prenom'], $row['Celebrant_id'], "edit_Individu", True);
			} else {
				//echo '<FONT SIZE="2">' .$row['Celebrant_Prenom']. ' ' .$row['Celebrant_Nom']. '</FONT> ';
				fCOM_Display_Photo($row['Celebrant_Nom'], $row['Celebrant_Prenom'], $row['Celebrant_id'], "edit_Individu", False);
			}			
			echo '</TD>';
		}
	}
	echo '</TD></TR>';
	
	// Celebrant Autre  ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		if ( $BloquerAcces=="") {
			echo '<DIV><input type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Individue" value="Célébrants (autres)"></DIV></TD>';
		} else {
			echo '<B><FONT SIZE="2">Autre(s) Célébrant(s) :</FONT></B></TD>';
		}

		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` 
					FROM `Individu` T0 
					LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
					WHERE T1.`Activite_id`="'.$_SESSION["Activite_id"].'" AND T1.`QuoiQuoi_id`=5 AND T1.`Engagement_id`='.$id.' ';
		$result = mysqli_query($eCOM_db, $requete);
		
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			if ( $BloquerAcces=="") {
				echo '<A HREF=Bapteme.php?action=RetirerCelebrant&Qui_id='.$row2['id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Célébrant"> <i class="fa fa-minus-circle text-danger"></i></A>  ';
				fCOM_Display_Photo($row2['Nom'], $row2['Prenom'], $row['Celebrant_id'], "edit_Individu", True);
			} else {
				fCOM_Display_Photo($row2['Nom'], $row2['Prenom'], $row['Celebrant_id'], "edit_Individu", False);
			}
			echo '</TR><TR><TD></TD><TD>';
		}
	}
	echo '</TD></TR>';	

	
	// Parrain Marraine ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Parrain Marraine :</FONT></B></TD>';
		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` FROM `Individu` T0 LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` WHERE T1.`Activite_id`="'.$_SESSION["Activite_id"].'" and T1.`QuoiQuoi_id`=3 and T1.`Engagement_id`=' . $id . ' ';
		$result = mysqli_query($eCOM_db, $requete);
		
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			if ( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				echo '<A HREF=Bapteme.php?action=RetirerParrain&Qui_id='.$row2['id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Parrain Marraine"><i class="fa fa-minus-circle text-danger"></i></a>  ';
			}
			echo '<FONT SIZE="1"> <A HREF=Bapteme.php?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row2['id'].'>' .$row2['Prenom']. ' ' .$row2['Nom']. '</a>';
			echo '</TR><TR><TD></TD><TD>';
		}
		//echo '</TD>';
		//echo '<TD bgcolor="#eeeeee">';
		echo '<input type=text name="Parrain" class="form-control form-control-sm" placeholder="Nom et Prénom du Parrain" value="'.$row['Parrain'].'" size="40" maxlength="80" '.$BloquerAcces.'></TD></TR><TR><TD></TD><TD>';
		echo '<input type=text name="Marraine" class="form-control form-control-sm" placeholder="Nom et Prénom de la Marraine" value="'.$row['Marraine'].'" size="40" maxlength="80" '.$BloquerAcces.'>';
	}
	echo '</TD></TR>';
	
	// Commentaire ==========================================
	if ((fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) AND $_GET['id'] > 0 ) {
		echo '<TR><TD colspan="2" bgcolor="#eeeeee">';
		echo '<B><FONT SIZE="2">Commentaires:</FONT></B><BR>';

		if ( $_GET['id'] == 0 ) {
			echo '<TEXTAREA cols=60 rows=5 class="form-control" name="Commentaire" maxlength="350"></TEXTAREA>';
		} else {
			echo '<TEXTAREA cols=60 rows=5 class="form-control" name="Commentaire" maxlength="350" value ="'.$row['Commentaire'].'" >'.Securite_html($row['Commentaire']).'</TEXTAREA>';
		}
		echo '</TD></TR>';
	}
	// Finance ==========================================
	echo '<TR>';
	if ((fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) AND $_GET['id'] > 0 ) { 
		echo '<TD bgcolor="#eeeeee" colspan="2">';
		echo '<div class="form-row">';
		echo '<B><FONT SIZE="2">Participation financière :</FONT></b>';
		
		echo '<div class="input-group">';
		echo '<span class="input-group-addon"><i class="fa fa-eur"></i></span>';
		
		if ( $id > 0 ) {
			echo '<input class="form-control form-control-sm" type=text style="width:80px" name=Finance_total value ="'.$row['Finance'].'" size="10" maxlength="5" '.$BloquerAcces.' value="">';
		} else {
			echo '<input class="form-control form-control-sm" type=text style="width:80px" name=Finance_total size="10" maxlength="5" '.$BloquerAcces.' value="">';
		}
		echo '</div></div>';
		echo '</TD>';
	}
	echo '</TR>';
	echo '<input type=hidden name=Paroissien_id value="'.$row['Baptise_id'].'">';
	echo '<input type=hidden name=Bapteme_id value="'.$id.'">';
	echo '<TR><TD colspan="2">';

	//if ( $_GET['id'] > 0 && $_SERVER['PHP_AUTH_USER'] != "sacristie") {
	echo '<div class="form-row">';
	if ( $_GET['id'] > 0 && fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 ) {
		
		echo '<div align="center"><input type="submit" name="edit" class="btn btn-secondary" value="Enregistrer">';
		echo ' <input type="reset" name="Reset" class="btn btn-secondary" value="Reset">';
	}
	//if ($_SERVER['USER'] <= 2 AND $_GET['id'] > 0 ) {
	
	if ( $_GET['id'] > 0 && fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 ) {
		echo ' <input type="submit" name="delete_fiche_Bapteme" class="btn btn-secondary" value="Détruire la fiche">';
	}
	echo '</div>';
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR></TABLE>';
	echo '</FORM>';
	echo '</CENTER>';


	fMENU_bottom();
	exit(); 
}



function Sauvegarder_fiche_bapteme () 
{
	Global $eCOM_db;
	$debug = False;

	$Resultat = -1;
	
	if ( isset($_POST['Bapteme_id']) AND $_POST['Bapteme_id'] > 0 ) {
		$Bapteme_id = $_POST['Bapteme_id'];	} else { $Bapteme_id = 0;}
	if ( isset($_POST['Paroissien_id']) AND $_POST['Paroissien_id'] > 0 ) {
		$Paroissien_id = $_POST['Paroissien_id']; } else {$Paroissien_id = 0;}
	
	if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 AND 
		$Bapteme_id > 0 AND $Paroissien_id > 0 ) {

		$DateHeureBapteme = $_POST['Date'].' '.$_POST['heure'].':00';
		
		if(isset( $_POST['LieuBapt'])) {
			$requete = 'SELECT id AS id_lieux FROM Lieux WHERE Lieu="'.$_POST['LieuBapt'].'" ';
			$result = mysqli_query($eCOM_db, $requete);
			$row2 = mysqli_fetch_assoc($result);
			mysqli_query($eCOM_db, 'UPDATE Bapteme SET Lieu_id='.$row2['id_lieux'].' WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		}
		
		if(isset($_POST['SExtrait_Naissance']) AND $_POST['SExtrait_Naissance']=="on")
		{$Extrait_Naissance = 1;} else {$Extrait_Naissance = 0;}

		if(isset($_POST['SLivret_de_famille']) AND $_POST['SLivret_de_famille']=="on")
		{$Livret_de_famille = 1;} else {$Livret_de_famille = 0;}

		if(isset($_POST['SDossier_Renseigne']) AND $_POST['SDossier_Renseigne']=="on")
		{$Dossier_Renseigne = 1;} else {$Dossier_Renseigne = 0;}

		if(isset($_POST['TypeBapt'])) {
			mysqli_query($eCOM_db, 'UPDATE Bapteme SET Aspersion_Immersion='.$_POST['TypeBapt'].' WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		}
	
		if(isset($_POST['Parrain'])) {$Parrain = $_POST['Parrain'];} else {$Parrain = "";}
		if(isset($_POST['Marraine'])) {$Marraine = $_POST['Marraine'];} else {$Marraine = "";}
	
		if(isset($_POST['Session_entered'])) {$Session_entered = $_POST['Session_entered'];} else {$Session_entered = "";}
		if(isset($_POST['ss_session'])) {$ss_session = $_POST['ss_session'];} else {$ss_session = "";}
		$Session_Bapteme=$Session_entered." ".$ss_session;
		
		if(isset($_POST['Finance_total'])) {$Finance_total = $_POST['Finance_total'];} else {$Finance_total = 0;}

		if(isset($_POST['Commentaire'])) {$Commentaire = $_POST['Commentaire'];} else {$Commentaire = "";}
			
		pCOM_DebugAdd($debug, "Bapteme:Sauvegarder_fiche_bapteme - Bapteme_id = ".$Bapteme_id);
		pCOM_DebugAdd($debug, "Bapteme:Sauvegarder_fiche_bapteme - Paroissien_id = ".$Paroissien_id);
		pCOM_DebugAdd($debug, "Bapteme:Sauvegarder_fiche_bapteme - Session = ".$Session_entered);
		pCOM_DebugAdd($debug, "Bapteme:Sauvegarder_fiche_bapteme - Session_Bapteme = ".$Session_Bapteme);
		pCOM_DebugAdd($debug, "Bapteme:Sauvegarder_fiche_bapteme - Finance_total = ".$Finance_total);
		
		if ($Paroissien_id > 0) {
			mysqli_query($eCOM_db, 'UPDATE Individu SET Bapteme="'.$_POST['Date'].'" WHERE id='.$Paroissien_id) or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Date="'.$DateHeureBapteme.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));

		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Extrait_Naissance="'.$Extrait_Naissance.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Dossier_Renseigne="'.$Dossier_Renseigne.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Livret_de_famille="'.$Livret_de_famille.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Parrain="'.$Parrain.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Marraine="'.$Marraine.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Session="'.$Session_entered.' '.$ss_session.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Activite_id='.$_SESSION["Activite_id"].' WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		
		
		if ( $Finance_total > 0) {
			mysqli_query($eCOM_db, 'UPDATE Bapteme SET Finance='.$Finance_total.' WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		} else {
			mysqli_query($eCOM_db, 'UPDATE Bapteme SET Finance=0 WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Session="'.$Session_entered.'" WHERE Activite_id='.$_SESSION["Activite_id"].' AND Engagement_id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));

	} else {
		$Resultat=0;
	}
	
	if(isset($_POST['Commentaire'])) {$Commentaire = $_POST['Commentaire'];} else {$Commentaire = "";}
	if ( $Bapteme_id > 0 ) {
		mysqli_query($eCOM_db, "UPDATE Bapteme SET Commentaire='".Securite_bdd($Commentaire)."' WHERE id=".$Bapteme_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db));
	}
	
	return($Resultat);

}


//updates table in DB
if ( isset( $_POST['edit'] ) AND $_POST['edit']=="Enregistrer") {
//if ($edit) {
	
	$debug = False;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Bapteme:Enregistrer - Bapteme_id = ".$_POST['Bapteme_id']);
	pCOM_DebugAdd($debug, "Bapteme:Enregistrer - Paroissien_id = ".$_POST['Paroissien_id']);
	pCOM_DebugAdd($debug, "Bapteme:Enregistrer - Session = ".$_POST['Session_entered']);
	
	$retour = Sauvegarder_fiche_bapteme ();

	if ($retour == 0) {
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Record Updated</FONT></CENTER></B>';
		$debug = False;
		pCOM_DebugAdd($debug, "Bapteme:Enregistrer - Bapteme_id = ".$_POST['Bapteme_id'] . "<BR>\n");
		pCOM_DebugAdd($debug, "Bapteme:Enregistrer - Paroissien_id = ".$_POST['Paroissien_id'] . "<BR>\n");
	}
	
	//echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	//exit;
}


//delete part 1
if ( isset( $_POST['delete_fiche_Bapteme'] ) AND $_POST['delete_fiche_Bapteme']=="Détruire la fiche") {
//if ($delete_fiche_Bapteme) {
	Global $eCOM_db;
	$debug = false;
	$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom` 
				FROM Bapteme T0 
				LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` 
				WHERE T0.`id`=' . $_POST['Bapteme_id'] . ' '; 
	pCOM_DebugAdd($debug, "Bapteme:delete_fiche_Bapteme - requete =".$requete);
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, 'Bapteme:delete_fiche_Bapteme - Enreg dans la table '.mysqli_num_rows( $result ));

	while($row = mysqli_fetch_assoc($result))
	{
		fMENU_top();
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Destruction d\'une fiche Baptême</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir détruire la fiche baptême : <BR><BR>';
		echo 'Numéro '.$row['id'].' - concernant '.$row['Prenom'].' '.$row['Nom'].' ?';
		echo '</FONT>';
		echo '<P><form method=post action="'.$_SERVER['PHP_SELF'].'">';
		echo '<input type="submit" name="delete_fiche_Bapteme_confirme" value="Oui">';
		echo '<input type="submit" name="" value="Non">';
		echo '<input type="hidden" name="id" value="'.$_POST['Bapteme_id'].'">';
		echo '</form></TD></TR>';
		fMENU_bottom();
		exit();	
	}
}

//delete part 2
if ( isset( $_POST['delete_fiche_Bapteme_confirme'] ) AND $_POST['delete_fiche_Bapteme_confirme']=="Oui") {
//if ($delete_fiche_Bapteme_confirme) {
	Global $eCOM_db;
	$debug = true;
	pCOM_DebugAdd($debug, 'Bapteme:delete_fiche_Bapteme_confirme - id='.$_POST['id']);
	$requete = 'SELECT * FROM Bapteme WHERE id=' . $_POST['id'] . ' '; 
	pCOM_DebugAdd($debug, "Bapteme:delete_fiche_Bapteme_confirme - requete01 =".$requete);
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, 'Bapteme:delete_fiche_Bapteme_confirme - Enreg dans la table '.mysqli_num_rows( $result ));


	if (mysqli_num_rows( $result )==1)
	{ 
        $requete = 'Delete FROM Bapteme WHERE id='.$_POST['id'].' '; 
		pCOM_DebugAdd($debug, "Bapteme:delete_fiche_Bapteme_confirme - requete02 =".$requete);

        $result = mysqli_query($eCOM_db, $requete); 
		if (!$result) {
			echo 'Impossible de détruire la fiche baptême '.$_POST['id'].' : ' . mysqli_error($eCOM_db);
			exit;
        } else {
	        $requete = 'Delete FROM QuiQuoi WHERE Activite_id="'.$_SESSION["Activite_id"].'" and Engagement_id='.$_POST['id'].' '; 
			pCOM_DebugAdd($debug, "Bapteme:delete_fiche_Bapteme_confirme - requete03 =".$requete);
			$result = mysqli_query($eCOM_db, $requete); 
			if (!$result) {
				echo 'Impossible de supprimer les enregistrements de la table QuiQuoi pour cette fiche Baptême '.$_POST['id'].' : ' . mysqli_error($eCOM_db);
				exit;
			}
		}
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Fiche baptême N° '.$_POST['id'].' détruite avec succès</FONT></CENTER></B>';
	}
}



if ( isset( $_POST['Reunion_Present'] ) AND ($_POST['Reunion_Present']=="2" OR $_POST['Reunion_Present']=="4" OR $_POST['Reunion_Present']=="8" OR $_POST['Reunion_Present']=="16")) {
//if ($Reunion_Present) {
	Global $eCOM_db;
	$debug=False;
	$retour = Sauvegarder_fiche_bapteme ();

	$requete = 'SELECT Reunion FROM `Bapteme` WHERE `id`='.$_POST['Bapteme_id'].' '; 
	$result = mysqli_query($eCOM_db, $requete);
	while($resultat = mysqli_fetch_assoc($result)){ 
		$CodeReunion = $resultat['Reunion'];
	}
	pCOM_DebugAdd($debug, "Bapteme:Reunion_Present - Reunion= ".$CodeReunion. " Ajouter Reunion " .$_POST['Reunion_Present']);
	$CodeReunion = (int)$CodeReunion | (int)$_POST['Reunion_Present'];
	mysqli_query($eCOM_db, 'UPDATE Bapteme SET Reunion='.$CodeReunion.' WHERE id='.$_POST['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;

}

if ( isset( $_POST['Reunion_Absent'] ) AND ($_POST['Reunion_Absent']=="2" OR $_POST['Reunion_Absent']=="4" OR $_POST['Reunion_Absent']=="8" OR $_POST['Reunion_Absent']=="16")) {
//if ($Reunion_Absent) {
	Global $eCOM_db;
	$debug=False;

	$retour = Sauvegarder_fiche_bapteme ();

	$requete = 'SELECT Reunion FROM `Bapteme` WHERE `id`='.$_POST['Bapteme_id'].' '; 
	$result = mysqli_query($eCOM_db, $requete);
	while($resultat = mysqli_fetch_assoc($result)){ 
		$CodeReunion = $resultat['Reunion'];
	}
	pCOM_DebugAdd($debug, "Bapteme:Reunion_Absent - Réunion = ".$CodeReunion. " Retirer Reunion " .$_POST['Reunion_Absent']);
	$CodeReunion = ((int)$CodeReunion & (~(int)$_POST['Reunion_Absent']));
	pCOM_DebugAdd($debug, "Bapteme:Reunion_Absent - Réunion devient = ".$CodeReunion." ");
	mysqli_query($eCOM_db, 'UPDATE Bapteme SET Reunion='.$CodeReunion.' WHERE id='.$_POST['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));

	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


if ( isset( $_POST['Selectionner_Paroissien'] ) AND ($_POST['Selectionner_Paroissien']=="Père" OR $_POST['Selectionner_Paroissien']=="Mère" OR $_POST['Selectionner_Paroissien']=="Conjoint")) {
	
	$retour = Sauvegarder_fiche_bapteme ();
	
	if ($_POST['Selectionner_Paroissien'] == "Père" ) {
		$Genre = "M";
	} elseif ($_POST['Selectionner_Paroissien'] == "Mère" ) {
		$Genre = "F";
	} elseif ($_POST['Selectionner_Paroissien'] == "Conjoint" ) {
		if ( $_POST['Sex'] == "F") {
			$Genre = "M";
		} elseif ( $_POST['Sex'] == "M") {
			$Genre = "F";
		} else {
			$Genre = "?";
		}
	}
	Selectionner_individu($_POST['Selectionner_Paroissien'], $Genre, $_POST['id_Individu'], $_POST['RetourPage'], $_POST['Fiche_id']);
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerAscendantParent") {
//if ($action == "RetirerAscendantParent") {
	Global $eCOM_db;
	$debug=False;
	//$retour = Sauvegarder_fiche_bapteme ();
	if (strcmp($_GET['Qui'], 'Mere')==0) {
		$requete='UPDATE Individu SET Mere_id=0 WHERE id='.$_GET['id_Individu'].' ';
	} elseif (strcmp($_GET['Qui'], 'Pere')==0) {
		$requete='UPDATE Individu SET Pere_id=0 WHERE id='.$_GET['id_Individu'].' ';
	} else {
		pCOM_DebugAdd($debug, "Bapteme:RetirerAscendantParent - Condition Else Qui=".$_GET['Qui']);
	}
	//Debug_plus("requete=".$requete." ");
	mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

// =================================================
// Ajouter individu à QuiQuoi (accompagnateur, Parrain ou Celebrant)
// =================================================
function Selectionner_individu_BaseBapteme ( $pQui, $pLabel, $Bapteme_id)
{
	Global $eCOM_db;
	$debug = False;
	
	fMENU_top();
	$requete = 'SELECT T0.`Prenom` AS Prenom, T0.`Nom` AS Nom FROM Individu T0 LEFT JOIN Bapteme T1 ON T1.`Baptise_id`=T0.`id` WHERE T1.`id`='.$Bapteme_id.' ';
	$result = mysqli_query($eCOM_db, $requete);
	$row = mysqli_fetch_assoc($result);
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	if ( $pQui == "Baptise") { 
		$Titre = 'Selectionner '.str_replace('_', ' ', $pLabel);
	} else {
		$Titre = 'Selectionner '.$pLabel.' de '.$row['Prenom'].' '.$row['Nom'];
	}

	$Action_Clique = '<A HREF=Bapteme.php?action=AffecterBaseBapteme&Qui='.$pQui.'&Qui_id=<Paroissien_id>&Bapteme_id='.$Bapteme_id.'&Label='.str_replace(' ', '+',$pLabel).' TITLE="Selectionner '.$pQui.'"><Icone_id></A>';
		
	$Requete_1 = '';
	$Requete_2 = '';

	if ($pQui == "Celebrant" OR $pQui == "Autre_Celebrant") {
		$Requete_2 = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T1.`MAJ` 
				FROM `Individu` T1 
				WHERE T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> "" AND T1.`Dead`=0 and T1.`Actif`=1 AND (T1.`Pretre`=1 OR T1.`Diacre`=1) GROUP BY id 
				ORDER BY T1.`Nom`, T1.`Prenom`';

	} elseif ($pQui == "Accompagnateur") {
		$Requete_2 = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T1.`MAJ` 	
				FROM `QuiQuoi` T0 
				LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
				WHERE T0.`Activite_id`="'.$_SESSION["Activite_id"].'" and T0.`QuoiQuoi_id`=2 and T0.`Engagement_id`=0 and T1.`Dead`=0 and T1.`Actif`=1 and Session = "'.$_SESSION["Session"].'" AND T1.`Nom` <> "" AND T1.`Nom` <> "Annulé dupliqué" AND T1.`Prenom` <> ""
				GROUP BY T1.`id` ORDER BY T1.`Nom`, T1.`Prenom`';

	} else {
		$Requete_1 = 'SELECT id, Prenom, Nom, MAJ 
				FROM Individu 
				WHERE Nom <> "" AND Nom <> "Annulé dupliqué" AND Prenom <> "" 
				ORDER BY MAJ DESC, Nom, Prenom'; 
		$Requete_2 = 'SELECT id, Prenom, Nom, MAJ 
				FROM Individu 
				WHERE Nom <> "" AND Nom <> "Annulé dupliqué" AND Prenom <> "" 
				ORDER BY Nom, Prenom';

	}
	fCOM_Selectionner_Paroissien ( $Titre, $Requete_1, $Requete_2, $Action_Clique);
	fMENU_bottom();
	exit;
}


if ( isset( $_POST['Selectionner_Individue'] ) AND ($_POST['Selectionner_Individue']=="Sélectionner le(la) Baptisé(e)" OR $_POST['Selectionner_Individue']=="Célébrants (autres)" OR $_POST['Selectionner_Individue']=="Célébrant Principal" OR $_POST['Selectionner_Individue']=="Parrain Marraine" OR $_POST['Selectionner_Individue']=="Accompagnateur" )) {
	
	$retour = Sauvegarder_fiche_bapteme ();
		
	$Label=$_POST['Selectionner_Individue'];
	if ($_POST['Selectionner_Individue'] == "Sélectionner le(la) Baptisé(e)" ) {
		$Selectionner_Individue = "Baptise";
		$Label = "le_Baptisé";
		
	} elseif ($_POST['Selectionner_Individue'] == "Célébrant Principal" ) {
		$Selectionner_Individue = "Celebrant";
		$Label = "le principal célébrant du baptême";
		
	} elseif ($_POST['Selectionner_Individue'] == "Célébrants (autres)" ) {
		$Selectionner_Individue = "Autre_Celebrant";
		$Label = "autre(s) célébrant(s)";
		
	} elseif ($_POST['Selectionner_Individue'] == "Parrain Marraine" ) {
		$Selectionner_Individue = "Parrain";
		$Label = "le parrain / la marraine";
		
	} elseif ($_POST['Selectionner_Individue'] == "Accompagnateur" ) {
		$Selectionner_Individue = "Accompagnateur";
		$Label = "l'accompagnateur des parents";

	}

	Selectionner_individu_BaseBapteme($Selectionner_Individue, $Label, $_POST['Bapteme_id']);
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="AffecterBaseBapteme") {
//if ($action == "AffecterBaseBapteme")
	Global $eCOM_db;
	$Debug = True;
	pCOM_DebugInit($Debug);
	echo "teste";
	if ($_GET['Qui_id'] > 0 & $_GET['Bapteme_id'] > 0) {
		if ($_GET['Qui'] == "Parrain") {
			pCOM_DebugAdd($Debug, "Bapteme:AffecterBaseBapteme-Parrain");
			mysqli_query($eCOM_db, "INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES (".$_GET['Qui_id'].",".$_SESSION["Activite_id"].", ".$_GET['Bapteme_id'].", 3, '".$_SESSION["Session"]."')") or die (mysqli_error($eCOM_db));
		} elseif ($_GET['Qui'] == "Accompagnateur") {
			pCOM_DebugAdd($Debug, "Bapteme:AffecterBaseBapteme-Accompagnateur");
			mysqli_query($eCOM_db, "INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES (".$_GET['Qui_id'].", ".$_SESSION["Activite_id"].", ".$_GET['Bapteme_id'].", 2, '".$_SESSION["Session"]."')") or die (mysqli_error($eCOM_db));
		} elseif ($_GET['Qui'] == "Autre_Celebrant") {
			pCOM_DebugAdd($Debug, "Bapteme:AffecterBaseBapteme - Autre_Celebrant");
			pCOM_DebugAdd($Debug, "Bapteme:AffecterBaseBapteme - requete=INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES (".$_GET['Qui_id'].",".$_SESSION["Activite_id"].", ".$_GET['Bapteme_id'].", 5, '".$_SESSION["Session"]."')");
			mysqli_query($eCOM_db, "INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES (".$_GET['Qui_id'].",".$_SESSION["Activite_id"].", ".$_GET['Bapteme_id'].", 5, '".$_SESSION["Session"]."')") or die (mysqli_error($eCOM_db));
		} else {
			pCOM_DebugAdd($Debug, "Bapteme:AffecterBaseBapteme-".$_GET['Qui']." Requete=UPDATE Bapteme SET ".$_GET['Qui']."_id=".$_GET['Qui_id']." WHERE id=".$_GET['Bapteme_id']);
			$Qui = fCOM_stripAccents($_GET['Qui']);
			mysqli_query($eCOM_db, "UPDATE Bapteme SET ".$_GET['Qui']."_id=".$_GET['Qui_id']." WHERE id=".$_GET['Bapteme_id']." ") or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	}

	if ($_GET['Qui'] == "Baptise"){
		echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'?'.$_SESSION["Session"].'&action=edit&id='.$_GET['Bapteme_id'].'">';
	} else {
		echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	}
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerParrain") {
//if ($action == "RetirerParrain")
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 & $_GET['Bapteme_id'] > 0) {
		mysqli_query($eCOM_db, "Delete FROM QuiQuoi WHERE Individu_id=".$_GET['Qui_id']." and Activite_id=".$_SESSION["Activite_id"]." AND Engagement_id=".$_GET['Bapteme_id']." AND QuoiQuoi_id=3 ")or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerAccompagnateur") {
//if ($action == "RetirerAccompagnateur")
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 AND $_GET['Bapteme_id'] > 0) {
		mysqli_query($eCOM_db, "Delete FROM QuiQuoi WHERE Individu_id=".$_GET['Qui_id']." and Activite_id=".$_SESSION["Activite_id"]." and Engagement_id=".$_GET['Bapteme_id']." and QuoiQuoi_id=2 ")or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerCelebrantPrincipal") {
//if ($action == "RetirerCelebrant")
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 AND $_GET['Bapteme_id'] > 0) {
		mysqli_query($eCOM_db, "UPDATE Bapteme SET Celebrant_id='0' WHERE id=".$_GET['Bapteme_id']." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerCelebrant") {
//if ($action == "RetirerCelebrant")
	Global $eCOM_db;
	if ($_GET['Qui_id'] > 0 AND $_GET['Bapteme_id'] > 0) {
		mysqli_query($eCOM_db, "Delete FROM QuiQuoi WHERE Individu_id=".$_GET['Qui_id']." AND Activite_id=".$_SESSION["Activite_id"]." AND Engagement_id=".$_GET['Bapteme_id']." AND QuoiQuoi_id=5 ")or die (mysqli_error($eCOM_db));; 
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_GET['Bapteme_id'].' ') or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

//----------------------------------------------------------------------
// Listing general de la session
//----------------------------------------------------------------------
function personne_line($enregistrement, $pCompteur) {
	$debug = True;

	$trcolor = usecolor();
	if (strtotime(date("Y-m-d H:i:s")) >= strtotime($enregistrement['Date'])) {
		echo '<h6 style="display:none;"></h6><tr id="Filtrer_'.$pCompteur.'"  style="display:table-row; ">';
	} else {
		echo '<tr>';
	}
	$TD_Click=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].'\')"';

  if ($enregistrement['Extrait_Naissance'] == '1' && $enregistrement['Dossier_Renseigne'] == '1' ) { $fgcolor = "green"; } else { $fgcolor = "black"; }; // && $enregistrement['Reunion'] == 30
	echo '<TD>';
	$Prepa_Bapt_Status = 0;
	if ( ((int)$enregistrement['Reunion'] & (int)2) > 0) {
		$Prepa_Bapt_Status = $Prepa_Bapt_Status + 1;
	}
	if ( ((int)$enregistrement['Reunion'] & (int)4) > 0) {
		$Prepa_Bapt_Status = $Prepa_Bapt_Status + 1;
	}
	if ( ((int)$enregistrement['Reunion'] & (int)16) > 0) {
		$Prepa_Bapt_Status = $Prepa_Bapt_Status + 1;
	}
	if ( ((int)$enregistrement['Reunion'] & (int)8) > 0) {
		$Prepa_Bapt_Status = $Prepa_Bapt_Status + 1;
	}
	switch ($Prepa_Bapt_Status) {
    case 0:
        echo '<i class="fa fa-battery-empty text-secondary"></i>';
        break;
    case 1:
        echo '<i class="fa fa-battery-quarter text-success"></i>';
        break;
    case 2:
        echo '<i class="fa fa-battery-half text-success"></i>';
        break;
    case 3:
        echo '<i class="fa fa-battery-three-quarters text-success"></i>';
        break;
    case 4:
        echo '<i class="fa fa-thumbs-up text-success"></i>';
        break;
	}
	echo '</TD>';

	// Prénom et nom -------------------------------------------------
	echo '<td'.$TD_Click.'>'.$enregistrement['Prenom'];
	//fCOM_Display_Photo("", $enregistrement['Prenom'], $enregistrement['Baptise_id'], "edit_Individu", False);
	echo '</td>';
	echo '<td'.$TD_Click.'>';
	fCOM_Display_Photo($enregistrement['Nom'], "", $enregistrement['Baptise_id'], "edit_Individu", False);
	echo '</td>';
	 
	// Accompagnateur ------------------------------------------------
	echo '<TD'.$TD_Click.'>'.$enregistrement['Accompagnateur'].'</TD>';

	// Sous-session --------------------------------------------------
	if ($_SESSION["Session"]=="All") {
		echo '<TD'.$TD_Click.'>'.$enregistrement['Session'].'</TD>';
	} else {
		if (substr($enregistrement['Session'],0,4) == $_SESSION["Session"]) {
			if (get_month_year_from_session(substr($enregistrement['Session'],4)) == "AUT") {
				$label_session= "AUTRE";
			} else {
				$label_session= substr($enregistrement['Session'],4).' - '.get_month_year_from_session(substr($enregistrement['Session'],4));
			}
			echo '<TD'.$TD_Click.'>'.$label_session.'</TD>';		
		} else {
			echo '<TD'.$TD_Click.'>'.intval(substr($enregistrement['Session'],0,4)-1).'-'.substr($enregistrement['Session'],0,4).'</TD>';
		}
	}
  
	echo '<TD'.$TD_Click.'>'.$enregistrement['Celebrant'].'</TD>';
	if (strftime("%d/%m/%y", fCOM_sqlDateToOut($enregistrement['Date'])) == "01/01/70" ) {
		echo '<TD>-';
	} else {
		echo '<TD'.$TD_Click.'>'.substr($enregistrement['Date'], 0, 16);
		//echo strftime("%Y/%m/%d %H:%M", fCOM_sqlDateToOut($enregistrement['Date']));
	}
	echo '</TD>';
	echo '<TD'.$TD_Click.'>'.$enregistrement['Lieu'].'</TD>';
	echo '</TR>';
}


function personne_list ($resultat, $order) {
	Global $eCOM_db;

	$debug = false;
	
	fMENU_Title("Liste des Baptêmes ...");
	echo '<table id="TableauTrier" class="table table-striped table-hover table-sm">';
	echo '<thead><tr>';
	echo '<th></th>';
	echo '<th>Prénom</th>';
	echo '<th>Nom</th>';
	echo '<th>Accompagnateurs</th>';
	echo '<th>Session</th>';
	echo '<th>Célébrant</th>';
	echo '<th>Date&nbsp&nbsp';
	echo '<input type="checkbox" onclick="FiltrerLine()"> <label for="Filter_old_fich"></label></th>';
	echo '<th>Lieu</th>';
	echo '</tr></thead>';
	echo '<tbody>';


	$compteur = 0;
	while( $enregistrement = mysqli_fetch_assoc( $resultat ))
	{
		if (strtotime(date("Y-m-d H:i:s")) >= strtotime($enregistrement['Date'])) {
			$compteur = $compteur + 1;
		}
		personne_line($enregistrement, $compteur);
	}
	echo "</tbody></TABLE>"; 

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

}


fMENU_top();


Global $eCOM_db;
$debug = False;
$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

if (isset($_GET['criteria'])) $criteria=$_GET['criteria'];
if (isset($_GET['order'])) $order=$_GET['order'];

if ($_SESSION["Session"]=="All") {
	if (!isset($_GET['criteria'])) $criteria='Session DESC, Nom, Prenom';
	if (!isset($_GET['order'])) $order='';
} else {
	if (!isset($_GET['criteria'])) $criteria='Accompagnateur';
	if (!isset($_GET['order'])) $order='DESC';
}

if ($criteria == "Lieu" or $criteria == "Celebrant") {
	$extendOrder = $criteria.' '.$order.', Date ASC, ';
} else {
	$extendOrder = $criteria.' '.$order.', ';
}

if ($_SESSION["Session"]=="All") {
	$extendWhere = 'WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].'';
} else {
	$extendWhere = 'WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' AND MID(T0.`Session`,1,4)='.$_SESSION["Session"].' OR ( MID(T0.`Session`,1,4) < '.$_SESSION["Session"].' AND Date >= CURDATE()) ';
}

$requete = "SELECT T0.`id`, T0.`Session` AS Session, T0.`Reunion`, T0.`Baptise_id` AS Baptise_id,  T1.`Nom`, T1.`Prenom`, CONCAT(T2.`Nom`, ' ',T2.`Prenom`) AS Celebrant, T0.`date` AS Date, T5.`Lieu` AS Lieu, T0.`Extrait_Naissance` , T0.`Dossier_Renseigne`, T0.`Livret_de_famille`, CONCAT(GROUP_CONCAT(DISTINCT T4.`Nom` ORDER BY T4.`Sex` DESC), ' ', GROUP_CONCAT( T4.`Prenom` ORDER BY T4.`Sex` DESC SEPARATOR ' et ')) AS Accompagnateur 
	FROM  `Bapteme` T0
	LEFT JOIN  `Individu` T1 ON T0.`Baptise_id` = T1.`id` 
	LEFT JOIN  `Individu` T2 ON T0.`Celebrant_id` = T2.`id` 
	LEFT JOIN `QuiQuoi` T3 ON T3.`Engagement_id` = T0.`id` AND T3.`Activite_id`=".$_SESSION["Activite_id"]." AND T3.`QuoiQuoi_id`=2
	LEFT JOIN  `Individu` T4 ON T3.`Individu_id` = T4.`id` 
	LEFT JOIN  `Lieux` T5 ON T0.`Lieu_id` = T5.`id`
	".$extendWhere."
	GROUP BY T0.`Baptise_id`
	ORDER BY ".$extendOrder." T0.`Session` DESC, T0.`Baptise_id`";

//error_log("Requete is :".$requete);
pCOM_DebugInit($debug);
pCOM_DebugAdd($debug, 'Bapteme - requete='.$requete);

$resultat = mysqli_query($eCOM_db,  $requete );
pCOM_DebugAdd($debug, 'Bapteme - Enreg dans la table '.mysqli_num_rows( $resultat ));

pCOM_DebugAdd($debug, 'Bapteme - Critère de tri: '.$criteria);
pCOM_DebugAdd($debug, 'Bapteme - Critère d\'ordre: '.$order);

if(isset($order) and $order=="ASC"){
$order="DESC";
}else{$order="ASC";}

personne_list($resultat, $order);

fMENU_bottom();

?>
  
</BODY>
</HTML>
