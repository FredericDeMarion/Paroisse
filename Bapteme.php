<?php
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



// Initialiser variable si elle n'existe pas
if( ! isset( $edit ) ) $edit = ""; 
if( ! isset( $delete_fiche_Bapteme ) ) $delete_fiche_Bapteme = ""; 
if( ! isset( $delete_fiche_Bapteme_confirme ) ) $delete_fiche_Bapteme_confirme = ""; 
if( ! isset( $Reunion_Present ) ) $Reunion_Present = ""; 
if( ! isset( $Reunion_Absent ) ) $Reunion_Absent = ""; 
if( ! isset( $Selectionner_Paroissien ) ) $Selectionner_Paroissien = ""; 
if( ! isset( $Selectionner_Individue ) ) $Selectionner_Individue = ""; 


function debug($ch) {
   global $debug;
   if ($debug) {
		echo $ch;
	}
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
$debug = False
;
//$IdSession = $_POST["IdSession"];
//session_readonly();

$SessionEnCours=$_SESSION["Session"];
require('templateBapteme.inc');
require('Common.php');
$debug = True;
pCOM_DebugInit($debug);

//debug("SessionEnCours=".$SessionEnCours . "<BR>\n");



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
	while($row = mysqli_fetch_assoc($result, MYSQL_ASSOC)){
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

    echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';

    address_top();
 
    echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
    echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
    echo '<TR BGCOLOR="#F7F7F7">';
    echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Liste accompagnateurs</B><BR>';
    echo '</TD></TR>';
    echo '<TR><TD BGCOLOR="#EEEEEE">';
    echo "<TABLE>";
    $trcolor = "#EEEEEE";
    echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Accompagnateurs</FONT></TH>';
    echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Adresse</FONT></TH>';
    echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Téléphone et e_mail</FONT></TH>';
    //echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>e-mail</FONT></TH>';
    echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Baptisé</FONT></TH>';
    //echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Couverts</FONT></TH>';
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
                //echo '</TD><TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$nb_personnes.'</FONT></TD></TR>';
				echo '</TR>';
            }
            $Total_pers = $Total_pers + $nb_personnes;
            $nb_personnes = substr_count($row['id_Accompagnateur'], '_')+1;
            $trcolor = usecolor();
            echo '<TR><TD width=100 bgcolor='.$trcolor.'><FONT face=verdana size=2>';
            //echo "</TD><TD bgcolor=$trcolor></TD></TR><TR><TD>";
            Display_Photo($row['Accompagnateur'], "NO LINK", $row['id_Accompagnateur'], "2");
            echo '</FONT></TD>';
            echo '<TD width=200 bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$row['Adresse'].'</FONT></TD>';
            echo '<TD width=70 bgcolor='.$trcolor.'><FONT face=verdana size=2>'.format_Telephone($row['Telephone'],"<BR>").'</FONT><BR>';
            //echo '<TD width=70 bgcolor='.$trcolor.'>
			echo '<FONT face=verdana size=2>';
            echo '<A HREF="mailto:'.str_replace("\\", "", $row['e_mail']).'?subject= Préparation Bapteme : " TITLE="Envoyer un mail à '.str_replace("<BR>", " ", $row['Accompagnateur']).'">'.str_replace("\\", "", $row['e_mail']).'</A></FONT></TD>';
            echo '<TD width=240 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
            $retour_Chariot = '';
        }
        echo "".$retour_Chariot."";
        $retour_Chariot = '<BR>';
        if ($row['Baptise_id'] > 0) {
            if (file_exists("Photos/Individu_".$row['Baptise_id'].".jpg")) {
                echo '-'.$row['SS_Session'].' <A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['Engagement_id'].' class="tooltip"> '.$row['Baptise'].'';
                echo '<EM><SPAN></SPAN>';	
                echo '<IMG src="Photos/Individu_'.$row['Baptise_id'].'.jpg" height="100" border="1" alt="Individu_'.$row['Baptise_id'].'">';
                echo '<BR>'.$row['Baptise'].'';
                echo '</EM></A>';
            } else {
                echo '-'.$row['SS_Session'].'<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['Engagement_id'].' class="tooltip"> '.$row['Baptise'].'</A>';
            }
            $nb_personnes = $nb_personnes + 2;
        }
    }
	if ($nb_personnes > 0) {
		//echo '</TD><TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$nb_personnes.'</FONT></TD></TR>';
		echo '</TR>';
	}
   
    echo '</TABLE><BR>';
    echo '<FONT face=verdana size=2>Prévoir '.$Total_pers.' couverts ( ajouter le secrétariat suivant disponibilité).</FONT>';
    fCOM_address_bottom();
    exit();
}


//======================================
// Vue accompagnateur
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_accompagnateur_sssession") {
//if ($action == "list_accompagnateur_sssession")
	Global $eCOM_db;
	$debug = false;
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';

	address_top();

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Liste accompagnateurs</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	$Total_pers = 0;
	$MemoAccompagnateur="@@@@@";
	$MemoSSession=".";
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter=1;
	
	echo "<table>";
	$trcolor = "#EEEEEE";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Session</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Enfant</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Parents</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Téléphone</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Date</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Heure</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Lieu</font></TH>\n";
	$Total_pers = 0;

	if ($_SESSION["Session"]=="All") {
			$requete = 'Select T0.`id`, T0.`Baptise_id`, count(T5.`Nom`) as Nb_Accomp, T0.`Session`, TRIM(MID(T0.`Session`,6,2)) as SS_Session, Concat(T1.`Prenom`, " ",T1.`Nom`) as Baptise, Concat(T2.`Prenom`, " ",T2.`Nom`,"<BR>",T3.`Prenom`, " ",T3.`Nom`) as Parents, IF(STRCMP(T2.`Telephone`, T3.`Telephone`),Concat(T2.`Telephone`, " ", T3.`Telephone`),T2.`Telephone`) as Parents_tel, 
			IF(STRCMP(T2.`e_mail`, T3.`e_mail`),Concat(T2.`e_mail`, "; <BR>", T3.`e_mail`),T2.`e_mail`) as Parents_e_mail, 
			Concat(GROUP_CONCAT(Concat(T5.`Prenom`) ORDER BY T5.`Sex` DESC SEPARATOR " et "), " ", T5.`Nom`) as Accompagnateur, T6.`Lieu`, T0.`Date`
						from `Bapteme` as T0
						Left join `Individu` as T1 on T1.`id`=T0.`Baptise_id`
						Left join `Individu` as T2 on T2.`id`=T1.`Mere_id`
						Left join `Individu` as T3 on T3.`id`=T1.`Pere_id`
						Left join `QuiQuoi` as T4 on T4.`Engagement_id`=T0.`id`
						Left join `Individu` as T5 on T5.`id`=T4.`Individu_id`
						Left join `Lieux` as T6 on T6.`id`= T0.`Lieu_id`
						WHERE T4.`Activite_id`='.$_SESSION["Activite_id"].' and T4.`QuoiQuoi_id`=2 group by Baptise 
						Order by Session DESC, Accompagnateur';
		} else {

			$requete = 'Select T0.`id`, T0.`Baptise_id`, count(T5.`Nom`) as Nb_Accomp, T0.`Session`, TRIM(MID(T0.`Session`,6,2)) as SS_Session, Concat(T1.`Prenom`, " ",T1.`Nom`) as Baptise, Concat(T2.`Prenom`, " ",T2.`Nom`,"<BR>",T3.`Prenom`, " ",T3.`Nom`) as Parents, IF(STRCMP(T2.`Telephone`, T3.`Telephone`),Concat(T2.`Telephone`, " ", T3.`Telephone`),T2.`Telephone`) as Parents_tel, IF(STRCMP(T2.`e_mail`, T3.`e_mail`),Concat(T2.`e_mail`, "; <BR>", T3.`e_mail`),T2.`e_mail`) as Parents_e_mail, 
			Concat(GROUP_CONCAT(Concat(T5.`Prenom`) ORDER BY T5.`Sex` DESC SEPARATOR " et "), " ", T5.`Nom`) as Accompagnateur, T6.`Lieu`, T0.`Date`
			from `Bapteme` as T0
			Left join `Individu` as T1 on T1.`id`=T0.`Baptise_id`
			Left join `Individu` as T2 on T2.`id`=T1.`Mere_id`
			Left join `Individu` as T3 on T3.`id`=T1.`Pere_id`
			Left join `QuiQuoi` as T4 on T4.`Engagement_id`=T0.`id`
			Left join `Individu` as T5 on T5.`id`=T4.`Individu_id`
			Left join `Lieux` as T6 on T6.`id`= T0.`Lieu_id`
			WHERE T4.`Activite_id`='.$_SESSION["Activite_id"].' and T4.`QuoiQuoi_id`=2 and MID(T0.`Session`,1,4)="'.$_SESSION["Session"].'" group by Baptise 
			Order by SS_Session DESC, Accompagnateur';
		}
		$result = mysqli_query($eCOM_db, $requete);//, $db);
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
				echo '<TD align="left" bgcolor="#A1A1A1" colspan=7><FONT face=verdana size=2>'.$TitreLigneSession.'&nbsp&nbsp-></FONT><FONT face=verdana size=2>';
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
				$Heure=ucwords(strftime("%R", fCOM_sqlDateToOut($row['Date'])));
			}
			echo '<tr>';
			if ($_SESSION["Session"]=="All") {
				echo '<td width=60 bgcolor="'.$trcolor.'" align="center"><font face=verdana size=2>'.$row['Session'].'</td>';
			} else {
				echo '<td width=60 bgcolor="'.$trcolor.'" align="center"><font face=verdana size=2>'.$row['SS_Session'].'</td>';
			}
			//echo '<td width=100 bgcolor="'.$trcolor.'" align="left"><font face=verdana size=2>'.ucwords($row['Baptise']).'</td>';
			
			echo '<td width=100 bgcolor="'.$trcolor.'" align="left"><font face=verdana size=2>';
			if (file_exists("Photos/Individu_".$row['Baptise_id'].".jpg")) { 
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].' class="tooltip"> '.ucwords($row['Baptise']).'';
				echo "<em><span></span>";
				echo '<img src="Photos/Individu_'.$row['Baptise_id'].'.jpg" height="100" border="1" alt="Individu_'.$row['Baptise_id'].'">';
				echo '<br>'.ucwords($row['Baptise']).'';
				echo '</em></a>';
			} else {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].' class="tooltip"> '.ucwords($row['Baptise']).'</A>';
			}
			echo '</td>';
			
			echo '<TD bgcolor="'.$trcolor.'" align="left"><font face=verdana size=2>';
			$Simplifier = array("\\", "<BR>");
			$NomParents=str_replace("<BR>", " et ", $row['Parents']);
			$AddParents=str_replace($Simplifier, "", $row['Parents_e_mail']);
            echo '<A HREF="mailto:'.$AddParents.'?subject= Préparation Baptême : " TITLE="Envoyer un mail à '.$NomParents.'">'.$row['Parents'].'</A>';
			echo '</TD>';
			fwrite($handle, '"'.$NomParents.'"< '.$AddParents.'>; ');
			
			echo '<td width=100 bgcolor="'.$trcolor.'" align="left"><font face=verdana size=2>'.format_Telephone($row['Parents_tel'], " ").'<BR>'.$AddParents.'</TD>';

			echo '<TD width=90 bgcolor="'.$trcolor.'" align="center"><font face=verdana size=2>'.$Date.'</TD>';
			echo '<TD width=60 bgcolor="'.$trcolor.'" align="center"><font face=verdana size=2>'.$Heure.'</TD>';
			echo '<TD width=120 bgcolor="'.$trcolor.'" align="left"><font face=verdana size=2>'.$row['Lieu'].'</TD>';
			echo '</TR>';

		}
		fwrite($handle, "</TD></TR><TR><TD> </TD></TR><TR><TD> </TD></TR><TR><TD>\r\n\r\n\r\n");
		fwrite($handle, "<FONT face=verdana size=2>");
		fwrite($handle, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
		fwrite($handle, "</FONT></td></tr></table>\r\n");
		fwrite($handle, "</BODY>\r\n</html>\r\n");
		fclose($handle);
		fCOM_address_bottom();
		exit();
}		
	
//======================================
// Vue Financiere
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="vue_financiere") {
//if ($action == "vue_financiere")
	Global $eCOM_db;
	$debug = false;

	address_top();
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	?>
	<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">
	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
	<TR BGCOLOR="#F7F7F7">
	<TD><FONT FACE="Verdana" SIZE="2"><B>Vue financière</B><BR>
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="#EEEEEE">
	<?php
	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Baptisés</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Session</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Accompagnateurs</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Date</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=3>€</font></TH>\n";
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT T0.`id` AS T0id, T1.`id` AS T1id, T0.`Session` AS Session, T1.`Nom`, T1.`Prenom`, T0.`Finance` AS Finance, T2.`Nom` AS Celebrant, T3.`Nom` AS Accompagnateur, T0.`date` AS Date, T4.`Lieu` As Lieu FROM `Bapteme` T0 LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` LEFT JOIN `Individu` T2 ON T0.`Celebrant_id`=T2.`id` LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id` ORDER BY T0.`date` DESC';
	} else {
		$requete = 'SELECT T0.`id` AS T0id, T1.`id` AS T1id, T0.`Session` AS Session, T1.`Nom`, T1.`Prenom`, T0.`Finance` AS Finance, T2.`Nom` AS Celebrant, T3.`Nom` AS Accompagnateur, T0.`date` AS Date, T4.`Lieu` As Lieu FROM `Bapteme` T0 LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` LEFT JOIN `Individu` T2 ON T0.`Celebrant_id`=T2.`id` LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id` WHERE MID(T0.`Session`,1,4)=' . $_SESSION["Session"] .'  ORDER BY T0.`date` DESC';
	}

	pCOM_DebugAdd($debug, 'Bapteme:vue_financiere - requete01 = '.$requete);
	$result = mysqli_query($eCOM_db, $requete);//, $db);
	$total = 0;
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		echo "<TR>";
		echo "<TD bgcolor=$trcolor><FONT face=verdana size=2>";
		//-------------
		
		if (file_exists("Photos/Individu_".$row['T0id'].".jpg")) { 
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['T0id'].' class="tooltip"><FONT SIZE="2">'.strtoupper($row['Nom'].' '.$row['Prenom']).'</FONT>';
			echo '<em><SPAN></SPAN>';
			echo "<img src='Photos/Individu_".$row['T0id'].".jpg' height='100' border='1' alt='Paroissien_".$row['T0id']."'>";
			echo '<BR><font face=verdana size=1>'.strtoupper($row['Nom'].' '.$row['Prenom']).'</FONT>';
			echo '</EM></A>';
		} else {
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['T0id'].'>';
			echo '<FONT SIZE="2">'.strtoupper($row['Nom'].' '.$row['Prenom']). '</FONT> ';
			echo '</A>';
		}
		
		//-------------
		echo "</FONT></TD>";
		echo "<TD bgcolor=$trcolor><FONT face=verdana size=2>" . substr($row['Session'],4) . "</FONT></TD>\n";
		$requete = 'SELECT T0.`id`, T1.`Nom`, T1.`Prenom`, T1.`Sex` FROM `QuiQuoi` T0 LEFT JOIN `Individu` T1 ON T1.`id` = T0.`Individu_id` WHERE T0.`Activite_id`='.$_SESSION["Activite_id"].' and T0.`Engagement_id`='.$row['T0id'].' and T0.`QuoiQuoi_id`=2 ORDER BY T1.`Nom`, T1.`Sex` DESC';
		pCOM_DebugAdd($debug, 'Bapteme:vue_financiere - requete02 = '.$requete);
		$resultat = mysqli_query($eCOM_db,  $requete );
		echo "<TD bgcolor=".$trcolor."><FONT face=verdana size=2>";
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
		echo "</FONT></TD>";
		echo "<TD bgcolor=$trcolor><FONT face=verdana size=2>";
		echo strftime("%d/%m/%y &nbsp  %H:%M", fCOM_sqlDateToOut($row['Date']));
		echo "</FONT></TD>";
		echo "<td align='right' width='35' bgcolor=$trcolor><font face=verdana size=2>".$row['Finance']."</FONT></TD>";
		$total = $total + $row['Finance'];
		echo "</TR>";
	}
	$trcolor = usecolor();
	echo "<TR><TD></TD><TD></TD><TD></TD><TD></TD><TD bgcolor=".$trcolor."><FONT face=verdana size=2><B>Total</B></FONT></TD><TD bgcolor=".$trcolor."><FONT face=verdana size=2><B>".$total." €</B></FONT></TD></FONT></TR>";
	echo "</TABLE>";
	fCOM_address_bottom();
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
		//2011 echo "<A HREF=Bapteme.php?action=delete1&id=".$row[id]." target=new><img src=\"images/delete.gif\" border=0 alt='Delete Record'></a> : ";
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



//--------------------------------------------------------------------------------------
//print one record by id
//--------------------------------------------------------------------------------------
if ( isset( $_GET['action'] ) AND $_GET['action']=="printid") {
//if ($action == "printid")
	Global $eCOM_db;
	$result = mysqli_query($eCOM_db, "SELECT * FROM ".$Table." where id = ".$id." ");//, $db);
	while($row = mysqli_fetch_assoc($result))
	{ 
		echo "<font face=verdana><h3>".$row['LUI_Nom'].", ".$row['ELLE_Nom']."</h3>";
		echo "<font face=verdana size=2>";
		echo "<B>Nom:</B>".$row['Prenom']." ".$row['Nom']."<br>";
		echo "<B>Lieu du Baptême:</B>".$row['Lieu_mariage']."<br>";
		echo "<B>Date du Baptême:</B>".$row['Date_mariage']."<br>";
		echo "<B>Célébrant:</B>".$row['Celebrant']."<br>";
		echo "<B>Accompagnateurs:</B>".$row['Accompagnateurs']."<br>";
		echo "<B>Téléphone:</B>".$row['Telephone']."<br>";
		echo "<B>Email:</B>".$row['Email']."<br>";
		echo "<B>Adresse:</B>".$row['Adresse']."<br>";
		echo "<B>Enfant:</B>".$row['Enfant']."<br>";
		echo "<B>Commentaire:</B>".$row['Commentaire']."<br>";
	//exit();
	}
}
	
//print all records
if ( isset( $_GET['action'] ) AND $_GET['action']=="printall") {
//if ($action == "printall")
	Global $eCOM_db;
	?><font face=verdana><h3><?php echo "Session ".$_SESSION["Session"]." "; ?></h3><br><?php
	if ($_SESSION["Session"]=="All") {
		$result = mysqli_query($eCOM_db, "SELECT * FROM ".$Table." ORDER BY Accompagnateurs");//, $db);
	} else {
		$result = mysqli_query($eCOM_db, "SELECT * FROM ".$Table." where Session = ".$_SESSION["Session"]." ORDER BY Accompagnateurs");//, $db);
	}
	while($row = mysqli_fetch_assoc($result))
	{ 
		echo "<FONT face=verdana size=2>";
		echo "<h3>".$row['LUI_Prenom']." ".$row['LUI_Nom'].", ".$row['ELLE_Prenom']." ".$row['ELLE_Nom']."</h3>";
		echo "<B>Lieu du Baptême:</B>".$row['Lieu_mariage']."<BR>";
		echo "<B>Date du Baptême:</B>".$row['Date_mariage']."<BR>";
		echo "<B>Célébrant:</B>".$row['Celebrant']."<BR>";
		echo "<B>Accompagnateurs:</B>".$row['Accompagnateurs']."<BR>";
		echo "<B>Téléphone:</B>".$row['Telephone']."<BR>";
		echo "<B>Email:</B>".$row['Email']."<BR>";
		echo "<B>Adresse:</B>".$row['Adresse']."<BR>";
		echo "<B>Enfant:</B>".$row['Enfant']."<BR>";
		echo "<B>Commentaire:</B>".$row['Commentaire']."<BR><BR>";
	}
	echo "<BR><BR>";
	exit();
}

//==========================================================
//edit records Bapteme
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit") {
//if ($action == "edit")
	Global $eCOM_db;
	$debug = true;
	
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
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
	$requete = 'SELECT T0.`id`, T0.`MAJ`, T0.`Baptise_id`, T1.`Nom`, T1.`Prenom`, T1.`Naissance`, T1.`Bapteme` as Date, T0.`Parrain`, T0.`Marraine`, T0.`Celebrant_id`, T2.`Nom` AS Celebrant_Nom, T2.`Prenom` AS Celebrant_Prenom, T0.`Accompagnateur_id`, T3.`Nom` AS Accompagnateur_Nom,  T3.`Prenom` AS Accompagnateur_Prenom, T0.`Session`, T0.`Reunion`, T0.`date` As Date2, T4.`Lieu` As Lieu, T0.Aspersion_Immersion, T0.`Dossier_Renseigne`, T0.`Livret_de_famille`, T0.`Extrait_Naissance`, T0.`Commentaire`, T0.`Finance` 
				FROM `Bapteme` T0 
				LEFT JOIN `Individu` T1 ON T0.`Baptise_id`=T1.`id` 
				LEFT JOIN `Individu` T2 ON T0.`Celebrant_id`=T2.`id` 
				LEFT JOIN `Individu` T3 ON T0.`Accompagnateur_id`=T3.`id` 
				LEFT JOIN `Lieux` T4 ON T0.`Lieu_id`=T4.`id`  
				WHERE T0.id='.$id.' ';

	$result = mysqli_query($eCOM_db, $requete);
	//while($row = mysqli_fetch_assoc($result))
	$row = mysqli_fetch_assoc($result);
		
	address_top();
	if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) { 
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
			echo '<TD align="right"><FONT FACE="Verdana" SIZE="1"> (Dernière modification au '.strftime("%d/%m/%Y %T", fCOM_sqlDateToOut($row['MAJ'])).')</TD>';
		}
	}
	echo '</TR>';
	
	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2"><CENTER><font face="verdana" size="2">';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<TR><TD width="140" bgcolor="#eeeeee"><FORM method=post action="'.$_SERVER['PHP_SELF'].'"><B><FONT SIZE="3"> </FONT></B></TD></TR>';
	echo '<TR><TD>';
	
	//if ( $_GET['id'] > 0 ) {
		if (( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 ) AND $row['Baptise_id'] == 0) {
			echo '<div><INPUT type="submit" name="Selectionner_Individue" value="Sélectionner le(la) Baptisé(e)"></TD>';
		} else {
			echo '<B><FONT SIZE="2">Le Baptisé :</FONT></B></TD>';
		}
		if ( $row['Baptise_id'] > 0 ) { 
			echo '<TD>';
			if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				echo '<FONT SIZE="2"><A HREF='.$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row['Baptise_id'].'>' .ucwords($row['Prenom']). ' ' .$row['Nom']. '</a></FONT>';
			} else {
				echo '<FONT SIZE="2">'.ucwords($row['Prenom']). ' ' .$row['Nom'].'</FONT>';
			}
			
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Naissance'])) != "01/01/70" ) {
	         $birthDate = explode("-", $row['Naissance']);
				$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
				echo '<FONT FACE="Verdana" SIZE="1"> ('.$Age.' ans)';
			}
			echo '</TD>';
		}
	//}
	echo "</TD>";
	
	// Photo ==================================
	
	if (file_exists("Photos/Individu_" . $row['Baptise_id'] . ".jpg")) { 
			echo '<TD rowspan="6">';
			echo '<IMG SRC="Photos/Individu_' . $row['Baptise_id'] . '.jpg" HEIGHT=150><BR><BR>';
	}
	echo '</TD></TR>';
	
	
	// Date ==================================
	echo '<TR><TD bgcolor="#eeeeee">';
	if ( $_GET['id'] > 0 ) {
		if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Date'])) != "01/01/70" ) {
			pCOM_DebugAdd($debug, "Bapteme:Edit Date(1) - ".$row['Date']." sqlDate=".fCOM_sqlDateToOut($row['Date']));
			if ( $row['Date'] = "0000-00-00") {
				$DateValue="";
			} else {
				$DateValue=strftime("%d/%m/%Y", fCOM_sqlDateToOut($row['Date']));
			}
			$hour=strftime("%H", fCOM_sqlDateToOut($row['Date2']));
			$min=strftime("%M", fCOM_sqlDateToOut($row['Date2']));
		} elseif (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['Date2'])) != "01/01/70" ) { 
			pCOM_DebugAdd($debug, "Bapteme:Edit Date(2) - ".$row['Date2']);
			if ( $row['Date2'] = "0000-00-00") {
				$DateValue="";
			} else {			
				$DateValue=strftime("%d/%m/%Y", fCOM_sqlDateToOut($row['Date2']));
			}
			$hour=strftime("%H", fCOM_sqlDateToOut($row['Date2']));
			$min=strftime("%M", fCOM_sqlDateToOut($row['Date2']));
		} else {
			pCOM_DebugAdd($debug, "Bapteme:Edit Date(3)");
			$DateValue="01/01/1970";
			$hour = "00";
			$min = "00";
		}
	//} else {
	//	$DateValue="01/01/1970";
	//	$hour = "00";
	//	$min = "00";
	//}
		
		echo '<B><FONT SIZE="2">Date du baptême:</FONT></B>';
		echo '</TD><TD width="225" bgcolor="#eeeeee">';
		if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) { 
			// Administrateur ou gestionnaire ?>
			<input type=text id="Date" name="Date" style="width:75px"<?php if ( $id > 0 ) {echo ' value ="'.$DateValue.'"';} ?> size="8" maxlength="10" >
			<a href="javascript:popupwnd('calendrier.php?idcible=Date&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
		<?php
		} else { 
			// Accompagnateur ?>
			<input type=text id="Date" name="Date" <?php if ( $id > 0 ) {echo ' value ="'.$DateValue.'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
		<?php
		}

		echo '<SELECT name="heure" '.$BloquerAcces.' >';
		for ($i=0; $i<=23; $i++) {
			if ($i == intval($hour)) {echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';} else {echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';}
		}
		echo '</SELECT>:';

		echo '<SELECT name="minute" '.$BloquerAcces.' >';
		for ($i=0; $i<=45; $i=$i+15) {
			if ($i == intval($min)) {	echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';} else {echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';}
		}
		echo '</SELECT>';
	}	
	echo '</TD></TR>';
	
	
	// Lieu ==============================================
	echo '<TR><TD bgcolor="#eeeeee">';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Lieu du baptême:</FONT></B>';
		echo '</TD><TD bgcolor="#eeeeee">';
		echo '<SELECT name="LieuBapt" '.$BloquerAcces.' >';
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
	
		echo '<SELECT name="TypeBapt" '.$BloquerAcces.' >';
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
		echo '<input type="checkbox" name="SExtrait_Naissance" id="SExtrait_Naissance" ' .$optionSelect .' /> <label for="SExtrait_Naissance"><FONT SIZE="2">Acte de Naissance</b></label>';
		if ($row['Dossier_Renseigne'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="SDossier_Renseigne" id="SDossier_Renseigne" ' .$optionSelect .' /> <label for="SDossier_Renseigne"><FONT SIZE="2">Dossier renseigné<br></b></label>';
		if ($row['Livret_de_famille'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="SLivret_de_famille" id="SLivret_de_famille" ' .$optionSelect .' /> <label for="SLivret_de_famille"><FONT SIZE="2">Livret_de_famille (si catholique)</b></label>';
		echo '</p>';
	}

	// Parents ==========================================
	echo '<TR><TD></TD></TR><TR></TR><TR><TD valign="top">';
	if ( $_GET['id'] > 0 ) {
		echo '<B><FONT SIZE="2">Parents:</FONT></B>';
		//echo '<div><input type="submit" name="Selectionner_Individue" value="Accompagnateur"></td>';
		$requete = 'SELECT T0.`id` as id_Baptise, T1.`id` as id_Pere, T1.`Nom` as Nom_Pere, T1.`Prenom` as Prenom_Pere, T1.`Souhaits` as Souhait_Pere, T2.`id` as id_Mere, T2.`Nom` as Nom_Mere, T2.`Prenom` as Prenom_Mere, T2.`Souhaits` as Souhait_Mere FROM `Individu` T0 LEFT JOIN `Individu` T1 ON T0.`Pere_id`=T1.`id` LEFT JOIN `Individu` T2 ON T0.`Mere_id`=T2.`id` WHERE T0.`id`='.$row['Baptise_id'].' ';
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result )) {
			
			if ( $row2['id_Baptise'] > 0 ) {

			// MERE
				
				if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					echo '<div style="display:inline"><input type="submit" name="Selectionner_Paroissien" value="Mère">';
					echo '<input type="hidden" name="id_Individu" value="'.$row2['id_Baptise'].'">';
					echo '<input type="hidden" name="Genre" value="F">';
					echo '<input type="hidden" name="RetourPage" value="SaisieBapteme">';
					echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
				}
				
				if ( $row2['id_Mere'] > 0 ) {
					if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
						echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendantParent&Qui=Mere&id_Individu='.$row['Baptise_id'].' TITLE="Desélectionner Mère"><img src="images/moins.gif" border=0 alt="Retirer Mère"></a>  ';
						Display_Photo($row2['Nom_Mere'], $row2['Prenom_Mere'], $row2['id_Mere'], "1");
					} else {
						echo '<FONT SIZE="2">'.$row2['Prenom_Mere'].' '.$row2['Nom_Mere'].'</FONT>';
					}
					echo '<BR>';
					//echo '<FONT SIZE="1"> <A HREF='.$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row2[id_Mere].'>' .$row2[Prenom_Mere]. ' ' .$row2[Nom_Mere]. '</a><BR>';
					$requete = 'SELECT * FROM `Souhaits` ORDER BY `Libelle` '; 
					$result = mysqli_query($eCOM_db, $requete);
					while( $row3 = mysqli_fetch_assoc( $result )) 
					{
						if (((int)$row2['Souhait_Mere'] & (int)$row3['Code']) > 0 and $row3['Code'] > 1)
						{
							//$debug = False;
							//debug("<br>Souhaits=".$row[Souhaits]. " try ".$row3[Libelle]."=" .$row3[Code]." - ".(int)$row[Souhaits] & (int)$row3[Code]." ");
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
					echo '<div style="display:inline"><input type="submit" name="Selectionner_Paroissien" value="Père">';
					echo '<input type="hidden" name="id_Individu" value="'.$row2['id_Baptise'].'">';
					echo '<input type="hidden" name="Genre" value="M">';
					echo '<input type="hidden" name="RetourPage" value="SaisieBapteme">';
					echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
				}
				
				if ( $row2['id_Pere'] > 0 ) {
					if ( $_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
						echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendantParent&Qui=Pere&id_Individu='.$row['Baptise_id'].' TITLE="Desélectionner Père"><img src="images/moins.gif" border=0 alt="Retirer Père"></a>  ';
						Display_Photo($row2['Nom_Pere'], $row2['Prenom_Pere'], $row2['id_Pere'], "1");
					} else {
						echo '<FONT SIZE="2">'.$row2['Prenom_Pere'].' '.$row2['Nom_Pere'].'</FONT>';
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
		echo '<SELECT name="Session_entered" '.$BloquerAcces.'>';
		for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
			if ($row['Session'] == "" && $i == intval($_SESSION["Session"]) or $i == intval(substr($row['Session'],0, 4))) {
				echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
			} else {
				echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
			}
		}
		echo '</SELECT>';
		echo '<B><FONT SIZE="2"> </FONT></B>';
	
		echo '<SELECT name="ss_session" '.$BloquerAcces.'>';
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
	}
	echo '</TD></TR>';
	
	// Participation Réunion ===========================================
	$debug=false;
	if (( $_SERVER['USER'] <= 3 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) AND $_GET['id'] > 0 ) { // différent que user sacristie
		echo '<TR><TD><B><FONT FACE="Verdana" SIZE="2">Participation Réunions:</FONT></B></TD><TD>';
		pCOM_DebugAdd($debug, "Bapteme:edit - Reunion = ".$row['Reunion']);
		if ( ((int)$row['Reunion'] & (int)2) > 0) {
			//echo '<span align="center"><button name="Reunion_Absent" value="2" type="submit" '.$BloquerAcces.' style="background-color:PaleGreen"> 1 </button>';
			echo '<span align="center"><button name="Reunion_Absent" value="2" type="submit" style="background-color:PaleGreen" title="Supprimer présence à la 1ère soirée">1</button>';
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Reunion_Present" value="2" type="submit" style="background-color:Gainsboro" title="Ajouter présence à la 1ère soirée"> 1 </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)4) > 0) {
			echo '<span align="center"><button name="Reunion_Absent" value="4" type="submit" style="background-color:PaleGreen" title="Supprimer présence à la 2ème soirée"> 2 </button>';
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Reunion_Present" value="4" type="submit" style="background-color:Gainsboro" title="Ajouter présence à la 2ème soirée"> 2 </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)16) > 0) {
			echo '<span align="center"><button name="Reunion_Absent" value="16" type="submit" style="background-color:PaleGreen" title="Supprimer présenté à la messe"> Messe </button>';
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Reunion_Present" value="16" type="submit" style="background-color:Gainsboro" title="Ajouter présenté à la messe"> Messe </button>';
			echo '</span>';
		}
		if ( ((int)$row['Reunion'] & (int)8) > 0) {
			echo '<span align="center"><button name="Reunion_Absent" value="8" type="submit" style="background-color:PaleGreen" title="Supprimer présence à la 3ème soirée"> 3 </button>';
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Reunion_Present" value="8" type="submit" style="background-color:Gainsboro" title="Ajouter présence à la 3ème soirée"> 3 </button>';
			echo '</span>';
		}
		echo '</TD></TR>';
	}
	
	// Accompagnateur ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		if ( fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			echo '<DIV><INPUT type="submit" name="Selectionner_Individue" value="Accompagnateur"></TD>';
		} else {
			echo '<B><FONT SIZE="2">Accompagnateur :</FONT></B></TD>';
		}
		$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom` FROM `Individu` T0 LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` WHERE T1.`Activite_id`='.$_SESSION["Activite_id"].' and T1.`QuoiQuoi_id`=2 and T1.`Engagement_id`=' . $id . ' ';
		$result = mysqli_query($eCOM_db, $requete);
		echo '<TD>';
		while( $row2 = mysqli_fetch_assoc( $result ))
		{
			if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAccompagnateur&Qui_id='.$row2['id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Accompagnateur"><img src="images/moins.gif" border=0 alt="Delete Accompagnateur"></A>  '; 
				Display_Photo($row2['Nom'], $row2['Prenom'], $row2['id'], "1");
			} else {
				echo '<FONT SIZE="2">' .$row2['Prenom']. ' ' .$row2['Nom']. '</FONT>';
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
			echo '<DIV><input type="submit" name="Selectionner_Individue" value="Célébrant Principal"></DIV></TD>';
		} else {
			echo '<B><FONT SIZE="2">Célébrant principal:</FONT></B></TD>';
		}
		if ( $row['Celebrant_id'] > 0 ) { 
			echo '<TD>';
			if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerCelebrantPrincipal&Qui_id='.$row['Celebrant_id'].'&Bapteme_id='.$id.' TITLE="Desélectionner Célébrant"><img src="images/moins.gif" border=0 alt="Delete Célébrant"></A>  '; 
				Display_Photo($row['Celebrant_Nom'], $row['Celebrant_Prenom'], $row['Celebrant_id'], "1");
			} else {
				echo '<FONT SIZE="2">' .$row['Celebrant_Prenom']. ' ' .$row['Celebrant_Nom']. '</FONT> ';
			}			
			echo '</TD>';
		}
	}
	echo '</TD></TR>';
	
	// Celebrant Autre  ==========================================
	echo '<TR><TD>';
	if ( $_GET['id'] > 0 ) {
		if ( $BloquerAcces=="") {
			echo '<DIV><input type="submit" name="Selectionner_Individue" value="Célébrants (autres)"></DIV></TD>';
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
				echo "<A HREF=Bapteme.php?action=RetirerCelebrant&Qui_id=".$row2['id']."&Bapteme_id=".$id." TITLE='Desélectionner Célébrant'><img src=\"images/moins.gif\" border=0 alt='Supprimer Célébrant'></A>  ";
			}
			echo '<FONT SIZE="1"> <A HREF=Bapteme.php?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row2['id'].'>'.$row2['Prenom'].' '.$row2['Nom'].'</a>';
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
				echo "<A HREF=Bapteme.php?action=RetirerParrain&Qui_id=".$row2['id']."&Bapteme_id=".$id." TITLE='Desélectionner Parrain Marraine'><img src=\"images/moins.gif\" border=0 alt='Delete Parrain'></a>  ";
			}
			echo '<FONT SIZE="1"> <A HREF=Bapteme.php?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row2['id'].'>' .$row2['Prenom']. ' ' .$row2['Nom']. '</a>';
			echo '</TR><TR><TD></TD><TD>';
		}
		//echo '</TD>';
		//echo '<TD bgcolor="#eeeeee">';
		echo '<input type=text name="Parrain" placeholder="Nom et Prénom du Parrain" value="'.$row['Parrain'].'" size="50" maxlength="80" '.$BloquerAcces.'></TD></TR><TR><TD></TD><TD>';
		echo '<input type=text name="Marraine" placeholder="Nom et Prénom de la Marraine" value="'.$row['Marraine'].'" size="50" maxlength="80" '.$BloquerAcces.'>';
	}
	echo '</TD></TR>';
	
	// Commentaire ==========================================
	if ((fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) AND $_GET['id'] > 0 ) {
		echo '<TR><TD colspan="2" bgcolor="#eeeeee">';
		echo '<B><FONT SIZE="2">Commentaires:</FONT></B><BR>';

		if ( $_GET['id'] == 0 ) {
			echo '<TEXTAREA cols=70 rows=5 name="Commentaire" maxlength="350"></TEXTAREA>';
		} else {
			echo '<TEXTAREA cols=70 rows=5 name="Commentaire" maxlength="350" value ="'.$row['Commentaire'].'" >'.Securite_html($row['Commentaire']).'</TEXTAREA>';
		}
		echo '</TD></TR>';
	}
	// Finance ==========================================
	echo '<TR>';
	if ((fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) AND $_GET['id'] > 0 ) { 
		echo '<TD bgcolor="#eeeeee" colspan="2">';
		echo '<B><FONT SIZE="2">Participation financière (euro):</FONT></b>';
		if ( $id > 0 ) {
			echo '<input type=text name=Finance_total value ="'.$row['Finance'].'" size="10" maxlength="5" '.$BloquerAcces.' value="">';
		} else {
			echo '<input type=text name=Finance_total size="10" maxlength="5" '.$BloquerAcces.' value="">';
		}
		echo '</TD>';
	}
	echo '</TR>';
	echo '<input type=hidden name=Paroissien_id value="'.$row['Baptise_id'].'">';
	echo '<input type=hidden name=Bapteme_id value="'.$id.'">';
	echo '<TR><TD>';

	//if ( $_GET['id'] > 0 && $_SERVER['PHP_AUTH_USER'] != "sacristie") {
	if ( $_GET['id'] > 0 && fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20 ) {
		echo '<div align="center"><input type="submit" name="edit" value="Enregistrer">';
		echo '<input type="reset" name="Reset" value="Reset">';
	}
	//if ($_SERVER['USER'] <= 2 AND $_GET['id'] > 0 ) {
	
	if ( $_GET['id'] > 0 && fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 ) {
		echo '</TD><TD><input type="submit" name="delete_fiche_Bapteme" value="Détruire la fiche">';
	}
	echo '</TD></TR>';
	echo '<TR><TD></TD></TR></TABLE>';
	echo '</FORM>';
	echo '</CENTER>';


	fCOM_address_bottom();
	exit(); 
}



function Sauvegarder_fiche_bapteme () // $Bapteme_id, $Paroissien_id, $Date, $Heure, $Min, $LieuBapt, $TypeBapt, $SExtrait_Naissance, $SDossier_Renseigne, $SLivret_de_famille, $Parrain, $Marraine, $Session_entered, $ss_session, $Finance_total, $Commentaire)
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
		
		if (isset($_POST['Date']) AND $_POST['Date'] != "" AND
			isset($_POST['heure']) AND $_POST['heure'] != "" AND
			isset($_POST['minute']) AND $_POST['minute'] != "" ) {
			$DateHeureBapteme = fCOM_getSqlDate($_POST['Date'],$_POST['heure'],$_POST['minute'],0);
			$DateBapteme = substr($DateHeureBapteme, 0, 10);
		} else {
			$DateHeureBapteme = "";
			$DateBapteme = "";
		}
		
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
			mysqli_query($eCOM_db, 'UPDATE Individu SET Bapteme="'.$DateBapteme.'" WHERE id='.$Paroissien_id.' ') or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE Bapteme SET Date="'.$DateHeureBapteme.'" WHERE id='.$Bapteme_id.' ') or die (mysqli_error($eCOM_db)); // A supprimer à terme

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
	
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit;
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
		address_top();
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
		fCOM_address_bottom();
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
		address_top();
		$requete = 'SELECT T0.`Prenom` AS Prenom, T0.`Nom` AS Nom FROM Individu T0 LEFT JOIN Bapteme T1 ON T1.`Baptise_id`=T0.`id` WHERE T1.`id`='.$Bapteme_id.' ';
		$result = mysqli_query($eCOM_db, $requete);
		$row = mysqli_fetch_assoc($result);
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		if ( $pQui == "Baptise") { 
			echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Selectionner '.$pLabel.' </B><BR></TD></TR>';
		} else {
			echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Selectionner '.$pLabel.' de '.$row['Prenom'].' '.$row['Nom'].'</B><BR></TD></TR>';
		}
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" ><BR>';

		//debug($requete . "<BR>\n");
		//$Activite_id=3;
		if ($pQui == "Celebrant" OR $pQui == "Autre_Celebrant") {
				$requete_1 = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T1.`MAJ` FROM `QuiQuoi` T0 LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` WHERE (T0.`QuoiQuoi_id`=7 or T0.`QuoiQuoi_id`=8) and T0.`Engagement_id`=0 and T1.`Dead`=0 and (T1.`Pretre`=1 or T1.`Diacre`=1) GROUP BY T1.`id` ORDER BY ';
				$requete_1 = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T1.`MAJ` FROM `Individu` T1 WHERE T1.`Dead`=0 and T1.`Actif`=1 AND (T1.`Pretre`=1 OR T1.`Diacre`=1) GROUP BY id ORDER BY ';
				$requete_2 = 'T1.`MAJ` DESC, ';
				$requete_3 = 'T1.`Nom`, T1.`Prenom` ';
			//}
		} elseif ($pQui == "Accompagnateur") {
			//$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom FROM `QuiQuoi` T0 LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` WHERE T0.`Activite_id`="'.$_SESSION["Activite_id"].'" and T0.`QuoiQuoi_id`=2 and T0.`Engagement_id`=0 ORDER BY T1.`Nom`, T1.`Prenom` GROUP BY T1.`id`, T1.`Nom`, T1.`Prenom` ';
			$requete_1 = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T1.`MAJ` FROM `QuiQuoi` T0 LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` WHERE T0.`Activite_id`="'.$_SESSION["Activite_id"].'" and T0.`QuoiQuoi_id`=2 and T0.`Engagement_id`=0 and T1.`Dead`=0 and T1.`Actif`=1 and Session = "'.$_SESSION["Session"].'" GROUP BY T1.`id` ORDER BY ';
			$requete_2 = 'T1.`MAJ` DESC, ';
			$requete_3 = 'T1.`Nom`, T1.`Prenom` ';
			//}

		} else {
			$requete_1 = 'SELECT id, Prenom, Nom, MAJ FROM Individu ORDER BY '; 
			$requete_2 = 'MAJ DESC, ';
			$requete_3 = 'Nom, Prenom'; 
			echo "<TABLE>";
			$trcolor = "#EEEEEE";
			echo "<TR><TD colspan=2><FONT face=verdana size=2>Derniers paroissiens modifiés</FONT></TD></TR>";
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Sélectionner</FONT></TH>\n";
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Prénom</FONT></TH>\n";
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Nom</FONT></TH>\n";			

			$requete=$requete_1.$requete_2.$requete_3.' LIMIT 0, 10';
			//debug("<TR><TD colspan=2><FONT face=verdana size=2>La requete est :".$requete."</FONT></TD></TR>");
			//cho "<TR><TD colspan=2><FONT face=verdana size=2>La requete est :".$requete."</FONT></TD></TR>";
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				echo '<TR><TD bgcolor='.$trcolor.'><CENTER><A HREF=Bapteme.php?action=AffecterBaseBapteme&Qui='.$pQui.'&Qui_id='.$row['id'].'&Bapteme_id='.$Bapteme_id.'&Label='.str_replace(' ', '+',$pLabel).' TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Delete Record"></A></TD>  ';
				echo '<TD><FONT face=verdana size=2>'.$row['Prenom'].'</FONT></TD><TD><FONT face=verdana size=2>'.$row['Nom'].'</FONT></TD></TR>'; 
			}
			echo '<TR><TD colspan=2><FONT face=verdana size=2><BR>Tous les paroissiens</FONT></TD></TR>';
			echo "</TABLE>";

		}
		echo "<TABLE>";
		$trcolor = "#EEEEEE";
		echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Sélectionner</FONT></TH>\n";
		echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Prénom</FONT></TH>\n";
		echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Nom</FONT></TH>\n";		
		$result = mysqli_query($eCOM_db, $requete_1.$requete_3);
		while($row = mysqli_fetch_assoc($result)){
			//if ($row[id] != $id_Bapteme ) {
				echo '<TR><TD bgcolor='.$trcolor.'><CENTER>';
				echo '<A HREF=Bapteme.php?action=AffecterBaseBapteme&Qui='.$pQui.'&Qui_id='.$row['id'].'&Bapteme_id='.$Bapteme_id.'&Label="'.str_replace(' ', '+',$pLabel).'" TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Delete Record"></A></TD>  ';
				echo '<TD><FONT face=verdana size=2>'.$row['Prenom'].'</FONT></TD><TD><FONT face=verdana size=2>'.$row['Nom'].'</FONT></TD></TR>'; 
			//}
		}
		echo "</TABLE><BR></FONT>";

		fCOM_address_bottom();
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
		echo '<h6 style="display:none;"></h6><TR id="Filtrer_'.$pCompteur.'" style="display:table-row;">';
	} else {
		echo '<TR>';
	}
	//echo "<!-- PERSONNE -->";
	echo '<TD width=20 bgcolor='.$trcolor.'><CENTER>';
	if (file_exists("Photos/Individu_".$enregistrement['Baptise_id'].".jpg")) { 
		echo ' <A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].' class="tooltip"><img src="images/edit.gif": border=0>';
		echo '<em><span></span>';
		echo "<img src='Photos/Individu_".$enregistrement['Baptise_id'].".jpg' height='100' border='1' alt='Individu_".$enregistrement['Baptise_id']."'>";
		echo '<BR><FONT face=verdana size=2>'.ucwords($enregistrement['Prenom']).' '.$enregistrement['Nom'].'</font>';
		echo '</em></A>';
	} else {
		echo ' <A HREF='.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].'><img src="images/edit.gif": border=0>';
	}
  
  if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
	//2011 echo "<a HREF=Bapteme.php?action=delete&id=$enregistrement[id]><img src=\"images/delete.gif\" border=0 alt='Delete Record'></a>  ";
  }
  echo '</CENTER></TD>';


  if ($enregistrement['Extrait_Naissance'] == '1' && $enregistrement['Dossier_Renseigne'] == '1' && $enregistrement['Reunion'] == 6) { $fgcolor = "green"; } else { $fgcolor = "black"; };
	echo '<TD bgcolor='.$trcolor.'>';
	if ( ((int)$enregistrement['Reunion'] & (int)2) > 0) {
		echo '<img src="images/Boutton_ok.gif" border=0 alt="Présent à la 1ère réunion" width=10 height=10>';
	} else {
		echo '<img src="images/Boutton_Interro.gif" border=0 alt="Absent à la 1ère réunion" width=10 height=10>';
	}
	if ( ((int)$enregistrement['Reunion'] & (int)4) > 0) {
		echo '<img src="images/Boutton_ok.gif" border=0 alt="Présent à la 2ème réunion" width=10 height=10>';
	} else {
		echo '<img src="images/Boutton_Interro.gif" border=0 alt="Absent à la 2ème réunion" width=10 height=10>';
	}
	echo '<BR>';
	if ( ((int)$enregistrement['Reunion'] & (int)16) > 0) {
		echo '<img src="images/Boutton_ok.gif" border=0 alt="Présent à la présentation à la messe" width=10 height=10>';
	} else {
		echo '<img src="images/Boutton_Interro.gif" border=0 alt="Absent à la présentation à la messe" width=10 height=10>';
	}
	if ( ((int)$enregistrement['Reunion'] & (int)8) > 0) {
		echo '<img src="images/Boutton_ok.gif" border=0 alt="Présent à la 3ème réunion" width=10 height=10>';
	} else {
		echo '<img src="images/Boutton_Interro.gif" border=0 alt="Absent à la 3ème réunion" width=10 height=10>';
	}	echo '</TD>';

	echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=1 color='.$fgcolor.'><B>'.ucwords($enregistrement['Prenom']).'</B></FONT></TD>';
	echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2 color='.$fgcolor.'><B>'.$enregistrement['Nom'].'</B></FONT></TD>';
  
	// Accompagnateur ----------------------------------------------
	echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$enregistrement['Accompagnateur'].'</FONT></TD>';

	// Sous-session --------------------------------------------------
	if ($_SESSION["Session"]=="All") {
		echo '<TD bgcolor="'.$trcolor.'" align="left"><FONT face=verdana size=2>'.$enregistrement['Session'].'</FONT></TD>';
	} else {
		if (substr($enregistrement['Session'],0,4) == $_SESSION["Session"]) {
			if (get_month_year_from_session(substr($enregistrement['Session'],4)) == "AUT") {
				$label_session= "AUTRE";
			} else {
				$label_session= get_month_year_from_session(substr($enregistrement['Session'],4));
			}
			echo '<TD bgcolor="'.$trcolor.'" align="center"><FONT face=verdana size=2>'.$label_session.'</FONT></TD>';		
		} else {
			echo '<TD bgcolor="'.$trcolor.'" align="center"><FONT face=verdana size=2>'.intval(substr($enregistrement['Session'],0,4)-1).'-'.substr($enregistrement['Session'],0,4).'</FONT></TD>';
		}
	}
  
	echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=1>'.$enregistrement['Celebrant'].'</FONT></TD>';
	if (strftime("%d/%m/%y", fCOM_sqlDateToOut($enregistrement['Date'])) == "01/01/70" ) {
		echo '<TD width=90 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
		echo '<FONT face=verdana size=1>-</FONT>';
	} else {
		echo '<TD width=90 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
		echo strftime("%d/%m/%y %H:%M", fCOM_sqlDateToOut($enregistrement['Date']));
	}
	echo '</FONT></TD>';
	echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=1>'.$enregistrement['Lieu'].'</FONT></TD>';
	echo '</TR>';
}


function personne_list ($resultat, $order) {
	Global $eCOM_db;
	global $debug;
	$debug = false;
	require("Login/sqlconf.php");
	address_top(); 

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Liste des Baptêmes</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo "<TH></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Prenom&order=".$order."\">Prénom</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Nom&order=".$order."\">Nom</A></font></TH>\n";
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Accompagnateur&order=".$order."\">Accompagnateurs</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Session&order=".$order."\">Session</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Celebrant&order=".$order."\">Célébrant</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Date&order=".$order."\">Date</A></font>&nbsp&nbsp";
	echo '<input type="checkbox" onclick="FiltrerLine()"> <label for="Filter_old_fich"><FONT SIZE="2"></b></label>';
	echo "</TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?criteria=Lieu&order=".$order."\">Lieu</A></font></TH>\n";
	$compteur = 0;
	while( $enregistrement = mysqli_fetch_assoc( $resultat ))
	{
		if (strtotime(date("Y-m-d H:i:s")) >= strtotime($enregistrement['Date'])) {
			$compteur = $compteur + 1;
		}
		personne_line($enregistrement, $compteur);
	}
	echo "</TABLE>"; 
	fCOM_address_bottom();	

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

?>

<HTML><HEAD>
<TITLE>Database Baptême</TITLE>
</HEAD>

<BODY>

<?php
Global $eCOM_db;
$debug = true;
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

$requete = "SELECT T0.`id`, T0.`Session` AS Session, T0.`Reunion`, T0.`Baptise_id` AS Baptise_id,  T1.`Nom`, T1.`Prenom`, T2.`Nom` AS Celebrant, T0.`date` AS Date, T5.`Lieu` AS Lieu, T0.`Extrait_Naissance` , T0.`Dossier_Renseigne`, T0.`Livret_de_famille`, CONCAT(GROUP_CONCAT(DISTINCT T4.`Nom` ORDER BY T4.`Sex` DESC), '<BR>', GROUP_CONCAT( T4.`Prenom` ORDER BY T4.`Sex` DESC SEPARATOR ' et ')) AS Accompagnateur 
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
?>
  
</BODY>
</HTML>
