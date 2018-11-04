<?php

//==================================================================================================
//    Nom du module : SuiviParoissien.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 14/04/2017 : Ajouter un '-' si le nom et le prénom sont vide lors des recherche de paroissien
//				Appel de la fonction pCOM_Get_NomParoisse sans '()'
// 10/05/2017 : Modification de la gestion des souhaits
// 10/05/2017 : Suppression des $_SERVER['PHP_AUTH_USER']
// 03/11/2018 : Ajout dans liste des services, ressourcements de l'information "Gestionnaire"
// 03/11/2018 : Ajout de la liste des Gestionnaires (RGPD)
//==================================================================================================
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
$debug = true;
//$IdSession = $_POST["IdSession"];
//session_readonly();

$Activite= 1; //All
$SessionEnCours=$_SESSION["Session"];
require('Common.php');
require('Menu.php');
//require('templateSuiviParoissien.inc');
$debug = false;
pCOM_DebugAdd($debug, 'SuiviParoissien - SessionEnCours='.$SessionEnCours . "<BR>\n");
require('Paroissien.php');


//======================================
// Vue liste des services / Ressourcements et Souhaits
//======================================
// Tester peut-être $ListeSouhaits n'est pas indispensable à transmettre

function Lister_engagements($pTitre, $pChampsIndividu, $pEngagement)
{
	Global $eCOM_db;
	if (!isset($pEngagement)) {$pEngagement="";}
	
	$debug = false;	
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pTitre=".$pTitre);
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pChampsIndividu=".$pChampsIndividu);
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pEngagement=".$pEngagement);
	
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];


	fMENU_top();
	if (date("n") <= 7 )
	{
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}
	
	$ActiverListe = True;
	if ( $pChampsIndividu == "Gestionnaires") {
		$ActiverListe = False;
	}

	//echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'">';

	if ($pChampsIndividu == "Services" ) {
		$AddWhere = "AND T0.`id` > 1 ";
		if (fCOM_Get_Autorization( 0 ) >= 90) {
			$AddWhere = "";
		}

		$requete = 'SELECT * FROM `Activites` T0 where T0.`Service` = 1 ORDER BY T0.`Nom`';
		$requete = 'Select DISTINCT T0.Nom As Activite, IFNULL(T2.Lieu, "") As Lieu
			FROM Activites T0
			LEFT JOIN QuiQuoi T1 ON T1.`Activite_id`=T0.`id`
			LEFT JOIN Lieux T2 ON T2.`id`= T1.`Lieu_id`
			WHERE T0.Service=1 AND T1.Engagement_id=0 '.$AddWhere.'
			ORDER BY T0.Nom, Lieu';
	} elseif ( $pChampsIndividu == "Ressourcements" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Formation` = 1 ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "Souhaits" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Souhait` = 1 ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "LangueMaternelle" ) {
		$requete = 'SELECT Distinct LangueMaternelle FROM `Individu` T0 where T0.`LangueMaternelle`<>"Langue Maternelle ?" and T0.`LangueMaternelle`<>"" and T0.`LangueMaternelle`<>"Autre" ORDER BY T0.`LangueMaternelle`';
	}
	
	echo '<TR BGCOLOR="#F7F7F7"><TD>';
	echo '<DIV align="left">';
	echo '<FONT FACE="Verdana" SIZE="2"><B>'.$pTitre.' : </B>';
	if ($ActiverListe == True) {
		echo '<SELECT name="Engagement"  >';
		echo '<option value="All" selected="selected">All</option>';
		pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Requete = '. $requete);
		$result = mysqli_query($eCOM_db, $requete);
		while($row = mysqli_fetch_assoc($result)){
			if ($pChampsIndividu == "Services" ) {
				if ($row['Lieu']=="") {
					$AfficheLibelle = $row['Activite'];
				} else {
					$AfficheLibelle = $row['Activite']." - ".$row['Lieu'];
				}
			} elseif ( $pChampsIndividu == "Ressourcements" ) {
				$AfficheLibelle = $row['Nom'];
			} elseif ( $pChampsIndividu == "Souhaits") {
				$AfficheLibelle = $row['Nom'];
			} elseif ( $pChampsIndividu == "LangueMaternelle") {
				$AfficheLibelle = $row['LangueMaternelle'];
			} elseif ( $pChampsIndividu == "Gestionnaires") {
				//$AfficheLibelle = $row['LangueMaternelle'];
			}
			if ($AfficheLibelle == $pEngagement){
				$SelectionnerChoix='selected="selected"';
			} else {
				$SelectionnerChoix='';
			}
			echo '<option value="'.$AfficheLibelle.'" '.$SelectionnerChoix.'>'.$AfficheLibelle.'</option>';
		}
		echo '</SELECT>';

		if ($pChampsIndividu == "Services" ) {
			echo '<input type="submit" name="Afficher_liste_services" value="Afficher">';
		} elseif ( $pChampsIndividu == "Ressourcements" ) {
			echo '<input type="submit" name="Afficher_liste_ressourcements" value="Afficher">';
		} elseif ( $pChampsIndividu == "Souhaits" ) {
			echo '<input type="submit" name="Afficher_liste_souhaits" value="Afficher">';
		} elseif ( $pChampsIndividu == "LangueMaternelle" ) {
			echo '<input type="submit" name="Afficher_liste_langues" value="Afficher">';
		} elseif ( $pChampsIndividu == "Gestionnaires" ) {
			//echo '<input type="submit" name="Afficher_liste_langues" value="Afficher">';
		}
	}
	if ($pChampsIndividu == "Services" ) {
		echo '<BR><FONT face=verdana color=#555555# size=0>(<i class="fa fa-star text-success"></i>) Responsable &nbsp&nbsp&nbsp(<i class="fa fa-bullseye text-success"></i>) Point contact &nbsp&nbsp&nbsp(<i class="fa fa-edit text-success"></i>) Gestionnaire</FONT>';
	}
	
	echo '</DIV>';	
	echo '</TD></TR></FORM>';
	
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo '<table id="TableauSansTriero" class="table table-striped table-hover table-sm" width="100%" cellspacing="0">';
	echo '<thead><TR>';
	
	echo "<TH>Engagement</TH>";
	echo "<TH>Nom </TH>";
	echo "<TH>Adresse</TH>";
	echo "<TH>Téléphone / e-mail</TH>";
	$NbColonne=3;
	if ($pChampsIndividu == "Services" ) {
		echo "<TH>".$pChampsIndividu."</TH>";
		echo "<TH>Ressourcements</TH>";
		$NbColonne=$NbColonne+2;
	} elseif ( $pChampsIndividu == "Ressourcements" OR $pChampsIndividu == "Souhaits" ) {
		echo "<TH>".$pChampsIndividu."</TH>";
		echo "<TH>Services</TH>";
		$NbColonne=$NbColonne+2;
	}
	
	if (fCOM_Get_Autorization( 0 ) >= 40 AND $pChampsIndividu != "LangueMaternelle" AND $pChampsIndividu != "Gestionnaires" ) { // was 40
		echo "<TH>Denier</TH>";
		$NbColonne=$NbColonne+1;
	}
	$NbColonne=$NbColonne-2;
	echo '</TR></thead>';
	echo '<tbody>';
	
	$Total_pers = 0;
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter = 1;

	if ( $pEngagement == "" ||  $pEngagement == "All") {
		$ExtraRequete = "";
	} else {
		if ($pChampsIndividu == "Services" ) {
			if (strpos($pEngagement, " - ") > 0 ){
				$Activite=substr($pEngagement, 0, strpos($pEngagement, " - "));
				$Lieu=substr($pEngagement, strpos($pEngagement, " - ")+3);
				$ExtraRequete = 'AND T0.`Nom`="'.$Activite.'" AND T2.`Lieu`="'.$Lieu.'"';
			} else {
				$ExtraRequete = 'AND T0.`Nom`="'.$pEngagement.'"';
			}
		} elseif ( $pChampsIndividu == "Ressourcements") {
			$ExtraRequete = 'AND T0.`Nom`="'.$pEngagement.'"';
		} elseif ( $pChampsIndividu == "Souhaits") {
			$ExtraRequete = 'AND T0.`Nom`="'.$pEngagement.'"';
		} elseif ( $pChampsIndividu == "LangueMaternelle") {
			$ExtraRequete = 'AND T0.`LangueMaternelle`="'.$pEngagement.'"';
		}
	}
	$debug = False;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass1 :'.$ExtraRequete);	
	
	if ($pChampsIndividu == "Services" ) {
		$requete = 'SELECT DISTINCT T0.`id`, T0.`Nom` As Activite, IFNULL(T2.`Lieu`, "") As Lieu, T2.`id` As Lieu_id
			FROM Activites T0
			LEFT JOIN QuiQuoi T1 ON T1.`Activite_id`=T0.`id`
			LEFT JOIN Lieux T2 ON T2.`id`= T1.`Lieu_id`
			WHERE T0.`Service`=1 AND T1.`Engagement_id`=0 '.$ExtraRequete.' '.$AddWhere.' ORDER BY T0.`Nom`, Lieu ';
		pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass2 :'.$requete);
	} elseif ( $pChampsIndividu == "Ressourcements" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Formation` = 1 '.$ExtraRequete.' ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "Souhaits" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Souhait` = 1 '.$ExtraRequete.' ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "LangueMaternelle") {
		$requete = 'SELECT Distinct LangueMaternelle FROM `Individu` T0 where T0.`'.$pChampsIndividu.'`!="Langue Maternelle ?" and T0.`'.$pChampsIndividu.'`!="" and T0.`'.$pChampsIndividu.'`!="Autre" '.$ExtraRequete.' ORDER BY T0.`LangueMaternelle`';
	} elseif ( $pChampsIndividu == "Gestionnaires") {
		$requete = 'SELECT "Gestionnaires" as Nom';
	}

	if ($pChampsIndividu == "Services" AND ( $pEngagement == "" OR  $pEngagement == "All") ) {
		$temp = "load/Organigramme_service.xml";
		$handle_xml = fopen($temp, 'w');
		fwrite($handle_xml, '<?xml version="1.0" encoding="iso-8859-1"?>');
		fwrite($handle_xml, '<!-- Time-stamp: "Organigramme_service.xml   '.$aujourdhui.'" -->');
		fwrite($handle_xml, "\n\r<paroissiens>");
	}

	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		if ($pChampsIndividu == "Services" ) {
			//$TreatedValue=$row[Valeur_int];
			if ($row['Lieu']=="") {
				$TreatedName = $row['Activite'];
				$TreatedLieu_id = '';
			} else {
				$TreatedName = $row['Activite']." - ".$row['Lieu'];
				$TreatedLieu_id = 'AND T0.`Lieu_id` = '.$row['Lieu_id'].' ';
			}
		} elseif ( $pChampsIndividu == "Ressourcements") {
			$TreatedName=$row['Nom'];
		} elseif ( $pChampsIndividu == "Souhaits") {
			//$TreatedValue=$row['Code'];
			$TreatedName=$row['Nom'];
		} elseif ( $pChampsIndividu == "LangueMaternelle") {
			//$TreatedValue=$row[LangueMaternelle];
			$TreatedName=$row['LangueMaternelle'];
		} elseif ( $pChampsIndividu == "Gestionnaires") {
			//$TreatedValue=$row[LangueMaternelle];
			$TreatedName="Gestionnaire";
		}
		
		$temp = "load/ListeMail_".$File_Counter.".php";
		$handle = fopen($temp, 'w');
		fCOM_PrintFile_Init($handle, "Liste des adresses mail : ".$TreatedName);
		$File_Counter += 1;
		if ($pChampsIndividu == "Services" ) {
			$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, IFNULL(T2.Lieu, "") As Lieu, T0.`Lieu_id`, T0.`Responsable`, T0.`Point_de_contact`, T0.`WEB_G` AS Gestionnaire
					FROM `QuiQuoi` T0
					LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Individu_id`
					LEFT JOIN `Lieux` T2 ON T2.`id`=T0.`Lieu_id`
					WHERE T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=2 AND T0.`Activite_id`='.$row['id'].' '.$TreatedLieu_id.' AND T0.`Session`='.$SessionActuelle.' AND T1.`Dead`=0 AND T1.`Actif`=1
					ORDER BY T1.`Nom`, T1.`Prenom`';
			//echo "Pass3 :".$requete2.'<BR>';
			pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass3 :'.$requete2);		
		} elseif ( $pChampsIndividu == "Ressourcements") {
			$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, T0.`Responsable`
					FROM `QuiQuoi` T0
					LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Individu_id`
					WHERE T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=1 AND T0.`Activite_id`='.$row['id'].' AND T0.`Session`='.$SessionActuelle.' AND T1.`Dead`=0 AND T1.`Actif`=1
					ORDER BY T1.`Nom`, T1.`Prenom`';
					
		} elseif ( $pChampsIndividu == "Souhaits") {
			$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, T0.`Responsable`
					FROM `QuiQuoi` T0
					LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Individu_id`
					WHERE T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=1 AND T0.`Activite_id`='.$row['id'].' AND T0.`Session`='.$SessionActuelle.' AND T1.`Dead`=0 AND T1.`Actif`=1
					ORDER BY T1.`Nom`, T1.`Prenom`';
					
		} elseif ( $pChampsIndividu == "LangueMaternelle") {
			$requete2 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T0.`Adresse`, T0.`Telephone`, T0.`e_mail` FROM `Individu` T0 where T0.`'.$pChampsIndividu.'`!="Langue Maternelle ?" and T0.`'.$pChampsIndividu.'`!="" and T0.`'.$pChampsIndividu.'`!="Autre" and T0.`'.$pChampsIndividu.'`="'.$TreatedName.'" AND T0.`Dead`=0 AND T0.`Actif`=1 ORDER BY T0.`Nom`, T0.`Prenom`';

		} elseif ( $pChampsIndividu == "Gestionnaires") {
			$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, T0.`WEB_G` AS Gestionnaire
					FROM `QuiQuoi` T0
					LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Individu_id`
					LEFT JOIN `Admin_membres` T2 ON T2.`Individu_id`=T0.`Individu_id`
					WHERE T0.`WEB_G`=1 AND T2.`droit_acces`=1
					ORDER BY T1.`Nom`, T1.`Prenom`';		}
		
		pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements: Requete2=".$requete2);
		
		$result2 = mysqli_query($eCOM_db, $requete2);
		while($row2 = mysqli_fetch_assoc($result2)){
			$Check_Responsable = "";
			$Check_Point_de_contact = "";
			$Check_Gestionnaire = "";
			if ($pChampsIndividu == "Services" ) {
				if ($row2['Responsable'] == 1 OR $row2['Point_de_contact'] == 1) {
					if ( $pEngagement == "" OR  $pEngagement == "All") {
						fwrite($handle_xml, "\n\r<user>\n");
						fwrite($handle_xml, "<service>".$TreatedName."</service>\n\r");
						if ($row2['Responsable'] == 1){
							fwrite($handle_xml, "<role>Responsable</role>\n\r");
						}
						if ($row2['Point_de_contact'] == 1) {
							fwrite($handle_xml, "<Point_Contact>Oui</Point_Contact>\n\r");
						}
						//if ($row2['Gestionnaire'] == 1) {
						//	fwrite($handle_xml, "<Gestionnaire>Oui</Gestionnaire>\n\r");
						//}
						fwrite($handle_xml, "<name>".$row2['Prenom']." ".$row2['Nom']."</name>\n\r");
						fwrite($handle_xml, "<telephone>".$row2['Telephone']."</telephone>\n\r");
						fwrite($handle_xml, "<email>".$row2['e_mail']."</email>\n\r");
						fwrite($handle_xml, "</user>");
					}
				}
				if ($row2['Responsable'] == 1){
					$Check_Responsable = '<i class="fa fa-star text-success"></i> ';
				}
				if ($row2['Point_de_contact'] == 1) {
					$Check_Point_de_contact = '<i class="fa fa-bullseye text-success"></i> ';
				}
				if ($row2['Gestionnaire'] == 1) {
					$Check_Gestionnaire = '<i class="fa fa-edit text-success"></i> ';
				}
			}
			fCOM_PrintFile_Email($handle, $row2['Prenom'].' '.$row2['Nom'], $row2['e_mail']);
			echo '<TR>';
			echo '<TD>'.$TreatedName.' <i class="fa fa-long-arrow-right"></i> <A HREF="load/ListeMail_'.($File_Counter - 1).'.php">e_mail</A></TD>';
			echo "<TD>";
			fCOM_Display_Photo($row2['Nom'].' '.$Check_Responsable.$Check_Point_de_contact.$Check_Gestionnaire, $row2['Prenom'], $row2['id'], "edit_Individu", true);
			echo '</TD>';

			echo "<TD>".$row2['Adresse']."</TD>";
			
			echo '<TD width=70>';
			echo "<A HREF='mailto:$row2[e_mail]?subject= Paroisse : ' TITLE='Envoyer un mail a ".$row2['Prenom']." ".$row2['Nom']."'>".$row2['e_mail']."</A><BR>";
			echo $row2['Telephone'].'</TD>';


			// Autres Services ou ressourcements du paroissien
			//------------------------------------------------
			if ($pChampsIndividu == "Services" || $pChampsIndividu == "Ressourcements" || 
				$pChampsIndividu == "Souhaits" ) {
				echo '<TD width="150" ><font size=2>';
				if ($pChampsIndividu == "Services") {
					//$DeltaWhere='AND T0.Lieu_id='.$row2['Lieu_id'].' AND T0.`QuoiQuoi_id`=2 AND T1.`Service`=1';
					$DeltaWhere=' AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=2 AND T1.`Service`=1';
				} elseif ($pChampsIndividu == "Ressourcements") {
					$DeltaWhere=' AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1 AND T1.`Formation`=1';
				} elseif ($pChampsIndividu == "Souhaits") {
					$DeltaWhere='AND T0.`QuoiQuoi_id`=1 AND T1.`Souhait`=1';
				}
				$requete3 = 'SELECT DISTINCT T1.`Nom`
					FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id`=T0.`Activite_id`
					WHERE T0.`Individu_id`='.$row2['id'].' AND T0.`Engagement_id`=0 AND T0.`Activite_id`<>'.$row['id'].' '.$DeltaWhere.' ORDER BY T1.`Nom`';
					
				$result3 = mysqli_query($eCOM_db, $requete3);//, $db);	
				while($row3 = mysqli_fetch_assoc($result3)){
					echo '- '.$row3['Nom'].'<BR>';
				}
				pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass4 :'.$requete3);
				echo "</TD>";


			// Les ressourcements ou Services du paroissien
			//------------------------------------------------
				echo '<TD width="150"><font size=2>';
				if ($pChampsIndividu == "Services") {
					// les ressourcements
					$DeltaWhere='AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1 AND T1.`Formation`=1';
				} elseif ($pChampsIndividu == "Ressourcements" OR $pChampsIndividu == "Souhaits") {
					// les services
					$DeltaWhere='AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=2 AND T1.`Service`=1';
				}
				$requete3 = 'SELECT DISTINCT T1.`Nom`
					FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id`=T0.`Activite_id`
					WHERE T0.`Individu_id`='.$row2['id'].' AND T0.`Engagement_id`=0 AND T0.`Activite_id`<>'.$row['id'].' '.$DeltaWhere.' ORDER BY T1.`Nom`';			

				$result3 = mysqli_query($eCOM_db, $requete3);
				while($row3 = mysqli_fetch_assoc($result3)){
					echo '- '.$row3['Nom'].'<BR>';
				}
				pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass5 :'.$requete3);
				echo "</TD>";
			
			// denier
			//--------
			
				if (fCOM_Get_Autorization( 0 ) >= 40) { // was 40
					echo '<TD width="160"><FONT size=2>';
					$requete3 = 'SELECT T0.`Date` As Date, T0.`Montant` As Montant FROM `Denier` T0 where T0.`Paroissien_id`='.$row2['id'].' ORDER BY T0.`Date`';
					$result3 = mysqli_query($eCOM_db, $requete3);
					while($row3 = mysqli_fetch_assoc($result3)){
						echo '['.$row3['Montant'].' € le '.date("d/m/Y", strtotime($row3['Date'])).']<BR>';
					}
					echo '</FONT></TD>';
				}
			}

			echo "</TR>";
		}
		fCOM_PrintFile_End($handle);
		fclose($handle);
	}
			
	if ($pChampsIndividu == "Services" AND ( $pEngagement == "" OR  $pEngagement == "All") ) {
		fwrite($handle_xml, "\n\r</paroissiens>\n\r");
		fclose($handle_xml);
	}
	
	echo "</tbody></TABLE>"; 
	echo "</TD></TR></TABLE>";

	fMENU_bottom();
	exit();
}

if (( isset( $_GET['action'] ) AND $_GET['action']=="list_services") OR 
	( isset( $_POST['Afficher_liste_services'] ) AND $_POST['Afficher_liste_services']=="Afficher" )) {

	if ( isset( $_GET['action'] ) AND $_GET['action']=="list_services") {
		$ListeSouhaits = "";//$_GET['ListeSouhaits'];
		$Engagement = "";//$_GET['Engagement'];
	} else {
		$Engagement = $_POST['Engagement'];
	}
	Lister_engagements("Liste des services", "Services", $Engagement);
}

if (( isset( $_GET['action'] ) AND $_GET['action']=="list_ressourcements") OR 
	( isset( $_POST['Afficher_liste_ressourcements'] ) AND $_POST['Afficher_liste_ressourcements']=="Afficher" )) {
//if ($action == "list_ressourcements" || $Afficher_liste_ressourcements) {
	
	if ( isset( $_GET['action'] ) AND $_GET['action']=="list_ressourcements") {
		$Engagement = "";//$_GET['Engagement'];
	} else {
		$Engagement = $_POST['Engagement'];
	}
	Lister_engagements("Liste des ressourcements", "Ressourcements", $Engagement);
}

if (( isset( $_GET['action'] ) AND $_GET['action']=="list_souhaits") OR 
	( isset( $_POST['Afficher_liste_souhaits'] ) AND $_POST['Afficher_liste_souhaits']=="Afficher" )) {
//if ($action == "list_souhaits" || $Afficher_liste_souhaits) {

	if ( isset( $_GET['action'] ) AND $_GET['action']=="list_souhaits") {
		$Engagement = "";//$_GET['Engagement'];
	} else {
		$Engagement = $_POST['Engagement'];
	}
	
	Lister_engagements("Liste des souhaits", "Souhaits", $Engagement);
}

if (( isset( $_GET['action'] ) AND $_GET['action']=="list_gestionnaires") OR 
	( isset( $_POST['Afficher_liste_gestionnaires'] ) AND $_POST['Afficher_liste_gestionnaires']=="Afficher" )) {
//if ($action == "list_souhaits" || $Afficher_liste_souhaits) {

	if ( isset( $_GET['action'] ) AND $_GET['action']=="list_gestionnaires") {
		$Engagement = "";//$_GET['Engagement'];
	} else {
		$Engagement = $_POST['Engagement'];
	}
	
	Lister_engagements("Liste des gestionnaires", "Gestionnaires", $Engagement);
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="list_paroissiens_RGPD") {
	
	Global $eCOM_db;
	$debug = false;	
	
	$temp = "load/ListeMail_RGPD.php";
	$handle = fopen($temp, 'w');
	fCOM_PrintFile_Init($handle, "Liste des adresses mail des paroissiens : RGPD");
	
	$temp = "load/ListeMail_RGPD_error.php";
	$handle_err = fopen($temp, 'w');
	fCOM_PrintFile_Init($handle_err, "Liste des paroissiens sans adresse mail : RGPD");

	$requete = 'SELECT DISTINCT T0.`id`, T0.`Prenom` As Prenom, T0.`Nom` As Nom, T0.`Naissance`, T0.`e_mail` as e_mail, T0.`telephone` as telephone, T1.`id` as id_Pere, T1.`Prenom` as Prenom_Pere, T1.`Nom` As Nom_Pere, T1.`e_mail` As e_mail_Pere, T2.`id` as id_Mere, T2.`Prenom` As Prenom_Mere, T2.`Nom` As Nom_Mere, T2.`e_mail` As e_mail_Mere
		FROM `Individu` T0
		LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Pere_id`
		LEFT JOIN `Individu` T2 ON T2.`id`=T0.`Mere_id`
		WHERE T0.`Prenom` != "" AND T0.`Nom` != "" AND T0.`Prenom` != "Annulé dupliqué"
		ORDER BY T0.`Nom`, T0.`Prenom`';
	$result = mysqli_query($eCOM_db, $requete);
	
	while($row = mysqli_fetch_assoc($result)){
		if (fCOM_Afficher_Age($row['Naissance']) < 18 ) {
			$ListMail="";
			if (strpos($row['e_mail'], "@") > 0) { 
				$ListMail=$ListMail.';'.$row['e_mail'];
			}
			if (strpos($row['e_mail_Pere'], "@") > 0) { 
				$ListMail=$ListMail.';'.$row['e_mail_Pere'];
			}
			if (strpos($row['e_mail_Mere'], "@") > 0) { 
				$ListMail=$ListMail.';'.$row['e_mail_Mere'];
			}
			$ListMail=fCOM_format_email_list($ListMail, ';');
			fCOM_PrintFile_Email($handle, 'Tuteur de '.$row['Prenom'].' '.$row['Nom'], $ListMail);
			//if ($row['Prenom'] == "Pauline" and $row['Nom']=="VOLPATI") {
			//	fCOM_PrintFile_End($handle);
			//	fclose($handle);
			//	echo '<META http-equiv="refresh" content="0; URL=load/ListeMail_RGPD.php">';
			//	exit();
			//}
		}
		if (strpos($row['e_mail'], "@") > 0) { 
			fCOM_PrintFile_Email($handle, $row['Nom'].' '.$row['Prenom'], $row['e_mail']);
		} else {
			//fwrite($handle_err, '<BR>(id='.$row['id'].') '.$row['Prenom'].' '.$row['Nom'].' Tel:'.$row['telephone'].' email='.$row['e_mail']);
			fwrite($handle_err, '<BR><A href="'.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$row['id'].'">'.$row['Prenom'].' '.$row['Nom'].'</A> Tel:'.$row['telephone'].' email='.$row['e_mail']);
			}
	}
	fwrite($handle, "<BR>");
	fCOM_PrintFile_End($handle);
	fwrite($handle, "<BR><A HREF=\"ListeMail_RGPD_error.php\">Liste des Paroissiens sans adresse e-mail</A><BR>");
	fclose($handle);
	fCOM_PrintFile_End($handle_err);
	fclose($handle_err);
	echo '<META http-equiv="refresh" content="0; URL=load/ListeMail_RGPD.php">';
	exit();
}


//======================================
// Vue liste des langues
//======================================
if (( isset( $_GET['action'] ) AND $_GET['action']=="list_langue") OR ( isset( $_POST['Afficher_liste_langues'] ) AND $_POST['Afficher_liste_langues']=="Afficher" )) {
//if ($action == "list_langue" || $Afficher_liste_langues) {
	
	if ( isset( $_GET['action'] ) AND $_GET['action']=="list_langue") {
		$Engagement = "";//$_GET['Engagement'];
	} else {
		$Engagement = $_POST['Engagement'];
	}
	
	Lister_engagements("Liste des langues", "LangueMaternelle", $Engagement);
}



//======================================
// Vue liste des événements futurs
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_evenements") {
//if ($action == "list_evenements"){
	Global $eCOM_db;
	$debug = false;

	fMENU_top();

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Prochains Baptêmes et Mariages</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	pCOM_DebugAdd($debug, 'SuiviParoissien:list_evenements - criteria = '.$criteria);
	pCOM_DebugAdd($debug, 'SuiviParoissien:list_evenements - order = '. $order);

	if (!isset($criteria)) $criteria="DateEve";
	if (!isset($order)) $order="DESC";
	if ($criteria=="Lieu" || $criteria=="Activité" || $criteria=="Celebrant") {
		$SecondCriteria = ", DateEve ASC";
	} else {
		$SecondCriteria = "";
	}
	if(isset($order) and $order=="ASC"){
		$order="DESC";
	}else{
		$order="ASC";
	}

	echo "<table>";
	$trcolor = "#EEEEEE";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Lieu&order=".$order."\">Lieu</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=DateEve&order=".$order."\">Date</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Activité&order=".$order."\">Type</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Celebrant&order=".$order."\">Celebrant</A></font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2><A HREF=\"" . $_SERVER['SCRIPT_NAME'] . "?Session=".$_SESSION["Session"]."&action=list_evenements&criteria=Paroissien&order=".$order."\">Paroissien</A></font></TH>\n";
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter = 1;
	
	$requete = '(SELECT T1.`Lieu`, T0.`Date` as DateEve, "Baptême" as Activité, T0.`id` as id, Concat(T3.`Prenom`, " ",T3.`Nom`) as Paroissien 
				FROM `Bapteme` as T0 LEFT JOIN `Lieux` as T1 ON T0.`Lieu_id`=T1.`id` LEFT JOIN `Individu` as T3 ON T3.`id`=T0.`Baptise_id` WHERE T0.`Date`>=now())
			Union all
				(SELECT T4.`Lieu_mariage` as Lieux, T4.`Date_mariage` as DateEve, "Mariage" as Activité, T4.`id` as id, concat(T4.`ELLE_Prenom`," ",T4.`ELLE_Nom`, " et ",T4.`LUI_Prenom`," ",T4.`LUI_Nom`) as Paroissien FROM `Fiancés` as T4 WHERE Date_mariage>=now())
			ORDER BY '.$criteria.' '.$order.' '.$SecondCriteria;
	
	$requete = '(SELECT T1.`Lieu`, T0.`Date` as DateEve, "Baptême" as Activité, T0.`id` as id, Concat(T3.`Prenom`, " ",T3.`Nom`) as Paroissien, T5.`Nom` as Celebrant 
				 FROM `Bapteme` as T0 
				 LEFT JOIN `Lieux` as T1 ON T0.`Lieu_id`=T1.`id` 
				 LEFT JOIN `Individu` as T3 ON T3.`id`=T0.`Baptise_id` 
				 LEFT JOIN `Individu` as T5 ON T5.`id`=T0.`Celebrant_id` 
				 WHERE T0.`Date`>=now())
				Union all
				 (SELECT T4.`Lieu_mariage` as Lieux, T4.`Date_mariage` as DateEve, "Mariage" as Activité, T4.`id` as id, concat(T4.`ELLE_Prenom`," ",T4.`ELLE_Nom`, " et ",T4.`LUI_Prenom`," ",T4.`LUI_Nom`) as Paroissien, T4.`Celebrant` as Celebrant 
				  FROM `Fiancés` as T4 WHERE Date_mariage>=now())
				ORDER BY '.$criteria.' '.$order.' '.$SecondCriteria;
				
	//debug('Requete = '. $requete );
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		echo "<TR>";
		$Date_Evenement=date("d/m/Y H:i", strtotime($row['DateEve']));
		echo "<TD width=150 bgcolor=$trcolor><font face=verdana size=2>".$row['Lieu']."</TD>";
		echo "<TD width=150 bgcolor=$trcolor><font face=verdana size=2>".$Date_Evenement."</TD>";
		echo "<TD width=70 bgcolor=$trcolor><font face=verdana size=2>".$row['Activité']."</TD>";
		echo "<TD width=70 bgcolor=$trcolor><font face=verdana size=2>".$row['Celebrant']."</TD>";
		echo "<TD width=300 bgcolor=$trcolor><font face=verdana size=2>";
		if ($row['Activité']=="Baptême") {
			echo '<A HREF="/Bapteme.php?action=edit&id='.$row['id'].'">'.$row['Paroissien'].'</A>';
		} else if ($row['Activité']=="Mariage") {
			echo '<A HREF="/Mariage.php?action=edit&id='.$row['id'].'">'.$row['Paroissien'].'</A>';
		}
		echo '</TD></TR>';
	}
	echo "</TABLE><BR>";
	fMENU_bottom();
	mysqli_close($eCOM_db);
	exit();
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
// Listing general des paroissiens
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//----------------------------------------------------------------------
if (( isset( $_GET['action'] ) AND $_GET['action']=="AfficherParoissiensParAge") OR (isset( $_POST['AfficherParoissiensParAge'] ) AND $_POST['AfficherParoissiensParAge']=="Lancer recherche" )) {
//if ($action == "AfficherParoissiensParAge" || $AfficherParoissiensParAge) {
	Global $eCOM_db;
	if (!isset($_POST['AgeMini'])) {$_POST['AgeMini']="";};
	if (!isset($_POST['AgeMax'])) {$_POST['AgeMax']="";};
	$debug = false;
	pCOM_DebugAdd($debug, "SuiviParoissien:AfficherParoissiensParAge - AgeMini=".$_POST['AgeMini']);
	pCOM_DebugAdd($debug, "SuiviParoissien:AfficherParoissiensParAge - AgeMax=".$_POST['AgeMax']);
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?action=AfficherParoissiensParAge&AgeMini='.$_POST['AgeMini'].'&AgeMax='.$_POST['AgeMax'];
	fMENU_top();
	fMENU_Title ("Rechercher des paroissiens par age");

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD BGCOLOR="#EEEEEE">';
	
	echo '<FORM method=POST action="'.$_SERVER['PHP_SELF'].'">';
	echo '<div class="container-fluid">';
	echo '<div class="form-row">';
	
	echo '<div class="col-form-label">';
	echo '<label for="AgeMini">Age mini</label>';
	echo ' <INPUT type="text" id="AgeMini" class="form-control" length=10 name="AgeMini" value="'.$_POST['AgeMini'].'" size="5" >';
	echo '</div>';
	
	echo '<div class="col-form-label">';
	echo '<label for="AgeMaxi">Age maxi</label>';
	echo '<INPUT type="text" id="AgeMaxi" class="form-control" length=10 name="AgeMax" value="'.$_POST['AgeMax'].'" size="5" >';
	echo '</div>';
	
	echo '<div class="col-form-label">';
	echo '<INPUT type="submit" class="btn btn-secondary mt-4" name="AfficherParoissiensParAge" value="Lancer recherche">';
	echo '</div>';
	
	echo '</div></div>';
	echo '</FORM>';
	
	fAfficherParoissiensParAge($_POST['AgeMini'], $_POST['AgeMax']);
	echo '</TD></TR></TABLE>';
	fMENU_bottom();
	exit;

}

Function fAfficherParoissiensParAge($pAgeMini, $pAgeMax) {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, "SuiviParoissien:fAfficherParoissiensParAge - pAgeMini=".$pAgeMini);
	pCOM_DebugAdd($debug, "SuiviParoissien:fAfficherParoissiensParAge - pAgeMax=".$pAgeMax);
	if (date("n") <= 7 )
	{
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}

	if ($pAgeMini == "") {
		exit;
	} elseif (!is_numeric($pAgeMini)) {
		exit;
	} else{
		$AgeMini = (int)$pAgeMini;
	}
	if ($pAgeMax == "") {
		exit;
	} elseif (!is_numeric($pAgeMax)) {
		exit;
	} else {
		$AgeMax = (int)$pAgeMax;
	}
	pCOM_DebugAdd($debug, "SuiviParoissien:fAfficherParoissiensParAge - AgeMini=".$AgeMini);
	pCOM_DebugAdd($debug, "SuiviParoissien:fAfficherParoissiensParAge - AgeMax=".$AgeMax);
	
	echo '<table id="TableauTrier" class="table table-striped table-hover table-sm" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	$trcolor = "#EEEEEE";
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo '<TH>Nom / Prénom</TH>';
	echo '<TH>Naissance</TH>';
	echo '<TH width="200">Adresse</TH>';
	echo '<TH width="180">Téléphone / <A HREF="load/ListeMail_Paroissien.php">e_mail</A>';
	if ($AgeMini < 18) {
		echo '<BR>ou <A HREF="load/ListeMailParents_Paroissien.php">e_mail Parents</A>';
	}
	echo '</TH>';
	echo '<TH>Services</font></TH>';
	echo '<TH>Ressourcements</font></TH>';
	if (fCOM_Get_Autorization( 0 ) >= 40) {  // Was 40
		echo '<TH width="180">Denier</TH>';
	}
	echo '</tr></thead>';
	echo '<tbody>';
	
	setlocale(LC_TIME, "fr_FR");
	$temp = "load/ListeMail_Paroissien.php";
	$handle = fopen($temp, 'w');
	$temp = "load/ListeMailParents_Paroissien.php";
	$handleParent = fopen($temp, 'w');
	fCOM_PrintFile_Init($handle,"Liste des adresses mail");
	fCOM_PrintFile_Init($handleParent,"Liste des adresses mail des parents");

	// Afficher les accompagnateurs noir='session courante'  grise='autre session'
	$requete="SELECT T0.`id`, T0.`Prenom`, T0.`Nom`, T0.`Adresse`, T0.`Naissance`, T0.`e_mail`, T0.`Telephone`, T1.`e_mail` as Pere_Email, T2.`e_mail` as Mere_Email
		FROM Individu T0
		LEFT JOIN  `Individu` T1 ON T0.`Pere_id` = T1.`id` 
		LEFT JOIN  `Individu` T2 ON T0.`Mere_id` = T2.`id`
		WHERE DATE_ADD(T0.`Naissance`, INTERVAL ".$AgeMax." YEAR)>=CURDATE() AND DATE_ADD(T0.`Naissance`, INTERVAL ".$AgeMini." YEAR)<=CURDATE() 
		ORDER BY Nom, Prenom";
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
	
		echo '<TR>';
		echo '<TD>';
		fCOM_Display_Photo($row['Nom'], $row['Prenom'], $row['id'], "edit_Individu", true);
		echo '</TD>';
		echo "<TD>";
		echo Securite_html(strftime("%d/%m/%Y", fCOM_sqlDateToOut($row['Naissance']))).'</TD>';
		echo "<TD>";
		echo Securite_html($row['Adresse']).'</TD>';
		echo "<TD>";
		echo Securite_html($row['Telephone']).'<BR>';

		if ( $row['e_mail'] != "" ) {
			fCOM_PrintFile_Email($handle, Securite_html($row['Prenom']).' '.Securite_html($row['Nom']), fCOM_format_email_list($row['e_mail'], ';'));
			//fwrite($handle, '"'.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"< '.Securite_html($row['e_mail']).'>; ');
		}
		if ( $row['Pere_Email'] != "" OR $row['Mere_Email'] != "" ) {
			fCOM_PrintFile_Email($handleParent, Securite_html($row['Prenom']).' '.Securite_html($row['Nom']), fCOM_format_email_list($row['Mere_Email'].' '.$row['Pere_Email'], ';'));
		}
		echo '<A HREF="mailto:'.Securite_html($row['e_mail']).'?subject= Paroisse '.pCOM_Get_NomParoisse().' : " TITLE="Envoyer un mail a '.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"><FONT face=verdana size=2>'.fCOM_format_email_list(Securite_html($row['e_mail']),';').'</FONT></A></TD>';

		// Services
		echo '<TD>';
		$requete2 = 'SELECT DISTINCT T1.`Nom`, T0.`Session`
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Service`=1 AND T0.`Individu_id`='.$row['id'].' AND Session >('.$SessionActuelle.'-3) AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10))
					ORDER BY T1.`Nom`, T0.`Session` DESC '; 
		$counter=1;
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc($result2)) {
			if ($counter > 1) {echo '<BR>';}
			echo '- '.$row2['Nom'].' ['.$row2['Session'].']';
			$counter = 2;
		}
		echo '</TD>';
		
		// Ressourcement
		echo '<TD>';
		$requete2 = 'SELECT DISTINCT CONCAT(T1.`Nom`," [",T0.`Session`,"]") AS list_Ressourcements, T1.`Nom`, T0.`Session`
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Formation`=1 AND T0.`Individu_id`='.$row['id'].' AND Session >('.$SessionActuelle.'-3) AND T0.`QuoiQuoi_id`=1
					ORDER BY T1.`Nom`, T0.`Session` DESC '; 
		$counter=1;
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2 )) 
		{
			if ($counter > 1) {echo '<BR>';}
			echo '- '.$row2['Nom'].' ['.$row2['Session'].']';
			$counter = 2;
		}
		echo "</TD>";
		
		// denier
		if (fCOM_Get_Autorization( 0) >= 40) {  // was 40
			echo "<TD>";
			$requete2 = 'SELECT T0.`Date` As Date, T0.`Montant` As Montant FROM `Denier` T0 where T0.`Paroissien_id`='.$row['id'].' ORDER BY T0.`Date`';
			$result2 = mysqli_query($eCOM_db, $requete2);
			while($row2 = mysqli_fetch_assoc($result2)){
				echo '['.$row2['Montant'].' € le '.date("d/m/Y", strtotime($row2['Date'])).'] ';
			}
			echo '</TD>';
		}
		echo "</TR>";
	}	
	echo "</tbody></TABLE>";
	fCOM_PrintFile_End($handle);
	fCOM_PrintFile_End($handleParent);
	fclose($handle);
	fclose($handleParent);

}


Function AfficherParoissiensRecherche($pAny, $pAll, $pNone) {
	Global $eCOM_db;
	$Debug = False;
	pCOM_DebugInit($Debug);
	
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	
	if (date("n") <= 7 )
	{
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}

	if ((($pNone == $pAll) || ($pNone == $pAny)) && ($pNone != "")) {
		pCOM_DebugAdd($Debug, "Suivi Paroissien:AfficherParoissiensRecherche - Tous paroissiens affichés");
		$ToutAfficher=True;
	} else {
		pCOM_DebugAdd($Debug, "Suivi Paroissien:AfficherParoissiensRecherche - Filter paroissiens affichés");		$ToutAfficher=False;
	}

	if((!$pAll) || ($pAll == "")) { $pAll = ""; } else { $pAll = "+(".$pAll.")"; }
	if((!$pAny) || ($pAny == "")) { $pAny = ""; } 
	if((!$pNone) || ($pNone == "")) { $pNone = ""; } else { $pNone = "-(".$pNone.")"; }
	
	if ($ToutAfficher == True) {
		$requete="SELECT id, Prenom, Nom, Adresse, e_mail, Telephone, Ressourcements FROM Individu order by Nom ASC";
		$requete="SELECT id, Prenom, Nom, Adresse, e_mail, Telephone FROM Individu ORDER by Nom, Prenom ASC";
	} else {
		$requete="SELECT MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('$pAll $pNone $pAny' IN BOOLEAN MODE) AS relevance, id, Prenom, Nom, Adresse, e_mail, Telephone, Services, Ressourcements FROM Individu WHERE MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('$pAll $pNone $pAny' IN BOOLEAN MODE) order by relevance DESC";
		$requete="SELECT MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('".$pAll." ".$pNone." ".$pAny."' IN BOOLEAN MODE) AS relevance, id, Prenom, Nom, Adresse, e_mail, Telephone FROM Individu WHERE MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('".$pAll." ".$pNone." ".$pAny."' IN BOOLEAN MODE) order by relevance DESC";
	}
	//$result = mysqli_query($eCOM_db, $requete);//, $db);
	//$num_rows = mysqli_num_rows($result);
	//if ($num_rows == 0) { exit;}
	pCOM_DebugAdd($Debug, 'Suivi Paroissien:AfficherParoissiensRecherche requete_01='.$requete);
	
	echo '<TABLE id="TableauTrier" class="table table-striped table-hover table-sm">';
	echo '<thead><tr>';

	$trcolor = "#EEEEEE";
	echo '<TH scope="col">Nom / Prénom</TH>';
	echo '<TH scope="col">Adresse</TH>';
	echo '<TH scope="col">Téléphone / <A HREF="load/ListeMail_Paroissien.php">e_mail</A></TH>';
	echo '<TH scope="col">Services</TH>';
	echo '<TH scope="col">Ressourcements</TH>';
	if (fCOM_Get_Autorization( 0 ) >= 40) { // Was 40
		echo '<TH scope="col">Denier</TH>';
	}
	echo '</tr></thead>';
	echo '<tbody>';
	
	setlocale(LC_TIME, "fr_FR");
	$temp = "load/ListeMail_Paroissien.php";
	$handle = fopen($temp, 'w');
	fwrite($handle, "<html><head><title>Liste adresses mail</title></head>\r\n<body><br>");
	fwrite($handle, "<h1><FONT face=verdana>Liste des adresses mail : </FONT></h1>\r\n");//".$TreatedName."</FONT></h1>\r\n");
	fwrite($handle, "<FONT face=verdana size=2>");
	fwrite($handle, "<p>Date : ".ucwords(strftime("%A %x %X",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))))."</p>\r\n");
	fwrite($handle, "<p>===================================================</p><br>\r\n<table>");
	fwrite($handle, "<FONT face=verdana size=2>");

	// Afficher les accompagnateurs noir='session courante'  grise='autre session'
	if ($ToutAfficher == True) {
		$requete="SELECT id, Prenom, Nom, Adresse, e_mail, Telephone, Ressourcements FROM Individu order by Nom ASC";
		$requete="SELECT id, Prenom, Nom, Adresse, e_mail, Telephone FROM Individu order by Nom ASC";
	} else {
		$requete="SELECT MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('$pAll $pNone $pAny' IN BOOLEAN MODE) AS relevance, id, Prenom, Nom, Adresse, e_mail, Telephone, Services, Ressourcements FROM Individu WHERE MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('$pAll $pNone $pAny' IN BOOLEAN MODE) order by relevance DESC";
		$requete="SELECT MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('".$pAll." ".$pNone." ".$pAny."' IN BOOLEAN MODE) AS relevance, id, Prenom, Nom, Adresse, e_mail, Telephone FROM Individu WHERE MATCH (Nom, Prenom, e_mail, Adresse, Commentaire) AGAINST ('".$pAll." ".$pNone." ".$pAny."' IN BOOLEAN MODE) order by relevance DESC";
	}
	pCOM_DebugAdd($Debug, 'Suivi Paroissien:AfficherParoissiensRecherche requete_02='.$requete);
	$result = mysqli_query($eCOM_db, $requete);//, $db);
	while($row = mysqli_fetch_assoc($result)){
	
		// définition de la couleur de la ligne
		$Accompagnateur_couleur = "818181";  // gris

		$trcolor = usecolor();
		echo '<TR  onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$row['id'].'\')">';

		echo '<TD>';
		if ( Securite_html($row['Nom'])=="" AND Securite_html($row['Prenom'])=="") {
			$Identite = "-";
		} else {
			$Identite = Securite_html($row['Nom']).' '.Securite_html($row['Prenom']);
		}
		fCOM_Display_Photo($Identite, "", $row['id'], "edit_Individu", false);
		echo '</TD>';

		echo "<TD>";
		echo Securite_html($row['Adresse']).'</TD>';
		echo "<TD>";
		echo Securite_html($row['Telephone']).'<br>';
		
		if ( $row['e_mail'] != "" ) {
			fwrite($handle, '"'.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"< '.Securite_html($row['e_mail']).'>; ');
		}
		echo '<A HREF="mailto:'.Securite_html($row['e_mail']).'?subject= Paroisse '.pCOM_Get_NomParoisse().' : " TITLE="Envoyer un mail a '.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"><FONT face=verdana size=2>'.Securite_html($row['e_mail']).'</FONT></A></TD>';

		// Services
		echo '<TD width=170 ><FONT face=verdana size=1>';
		$requete2 = "SELECT DISTINCT CONCAT(T1.`Nom`, ' [', T0.`Session`, ']') AS list_Services
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T0.`Individu_id`=".$row['id']." AND Session > (".$SessionActuelle."-3) AND T1.`Service`=1 AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10))
					ORDER BY T1.`Nom`, T0.`Session` DESC"; 
		pCOM_DebugAdd($Debug, 'Suivi Paroissien:AfficherParoissiensRecherche requete_03='.$requete2);
		$counter=1;
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2 )) 
		{
			if ($counter > 1) {echo '<BR>';}
			echo '- '.$row2['list_Services'];
			$counter =$counter + 1;
		}
		echo '</FONT></TD>';
		
		// Ressourcement
		echo '<TD width=170 ><FONT face=verdana size=1>';
		$requete2 = "SELECT DISTINCT CONCAT(T1.`Nom`, ' [', T0.`Session`, ']') AS list_Ressourcements
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T0.`Individu_id`=".$row['id']." AND Session >(".$SessionActuelle."-3) AND T1.`Formation`=1 AND T0.`QuoiQuoi_id`=1
					ORDER BY T1.`Nom`, T0.`Session` DESC"; 
		pCOM_DebugAdd($Debug, 'Suivi Paroissien:AfficherParoissiensRecherche requete_04='.$requete2);
		$result2 = mysqli_query($eCOM_db, $requete);
		$count_Nb_Services=mysqli_num_rows($result2);
		if ($count_Nb_Services > 0) {
			$counter=1;
			$result2 = mysqli_query($eCOM_db, $requete2);
			while( $row2 = mysqli_fetch_assoc( $result2 )) 
			{
				if ($counter > 1) {echo '<BR>';}
				echo '- '.$row2['list_Ressourcements'];
				$counter =$counter + 1;
			}
		}
		echo "</FONT></TD>";
		
		// denier
		if (fCOM_Get_Autorization( 0 ) >= 40) { // was 40
			echo "<TD width=150 ><FONT face=verdana size=1>";
			$requete2 = 'SELECT T0.`Date` As Date, T0.`Montant` As Montant FROM `Denier` T0 where T0.`Paroissien_id`='.$row['id'].' ORDER BY T0.`Date`';
			pCOM_DebugAdd($Debug, "SuiviParoissien:AfficherParoissiensRecherche - requete_05=".$requete2);
			$result2 = mysqli_query($eCOM_db, $requete2);
			while($row2 = mysqli_fetch_assoc($result2)){
				echo '['.$row2['Montant'].' € le '.date("d/m/Y", strtotime($row2['Date'])).'] ';
			}
			echo '</FONT></TD>';
		}
		echo "</TR>";
	}	
	echo '</TABLE>';
	fwrite($handle, "</TD></TR><TR><TD> </TD></TR><TR><TD> </TD></TR><TR><TD>\r\n\r\n\r\n");
	fwrite($handle, "<FONT face=verdana size=2>");
	fwrite($handle, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
	fwrite($handle, "</FONT></TD></TR></TABLE>\r\n");
	fwrite($handle, "</BODY>\r\n</HTML>\r\n");
	fclose($handle);

}



echo '<HTML><HEAD>';
echo '<TITLE>Database Paroissiens</TITLE>';
echo '</HEAD>';

echo '<BODY>';
	
	Global $eCOM_db;
	fMENU_top();
	$debug = false;

	if (isset($_POST['any'])) {
		$any=$_POST['any'];
	} elseif (isset($_GET['any'])) {
		$any=$_GET['any'];
	} else {
		$any="";
	}
	if (isset($_POST['all'])) {
		$all=$_POST['all'];
	} elseif (isset($_GET['all'])) {
		$all=$_GET['all'];
	} else {
		$all="";
	}
	if (isset($_POST['none'])) {
		$none=$_POST['none'];
	} elseif (isset($_GET['none'])) {
		$none=$_GET['none'];
	} else {
		$none="";
	}
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Rechercher des paroissiens</B><BR></TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE"><FONT face=verdana size=1>';

	$trcolor = "#EEEEEE";
	
	echo '<FORM method=post action='.$_SERVER['PHP_SELF'].'>';
	echo '<div class="form-row ml-1 mt-3">';
	echo '<div class="container-fluid"">';
	echo '<div class="form-row">';
		
	echo '<div class="col-form-label">';
	echo '<label for="any">Un de ces mots</label>';
	echo '<input type="text" id="any" name="any" class="form-control form-control-sm" maxlength="40" size="8" value="'.$any.'">';
	echo '</div>';
	
	echo '<div class="col-form-label">';
	echo '<label for="all">Tous ces mots</label>';
	echo '<input type="text" id="all" name="all" class="form-control form-control-sm" maxlength="40" size="8" value="'.$all.'">';
	echo '</div>';
	
	echo '<div class="col-form-label">';
	echo '<label for="any">Aucun de ces mots</label>';
	echo '<input type="text" id="none" name="none" class="form-control form-control-sm" maxlength="40" size="8" value="'.$none.'">';
	echo '</div>';
	
	echo '</div></div>';
	
	//echo '<FORM action="'.$_SERVER['PHP_SELF'].'" method=POST>';
	//echo '<B>Trouver des résultats avec : </B><BR> <BR>';
	//echo 'Un de ces mots: <INPUT type="text" length=40 name="any" value="'.$any.'"> ';
	//echo 'Tous ces mots: <INPUT type="text" length=40 name="all" value="'.$all.'"> ';
	//echo 'Aucun de ces mots: <INPUT type="text" length=40 name="none" value="'.$none.'"> ';
	
	echo '<INPUT type="submit" class="btn btn-secondary btn-sm ml-3" value="Lancer recherche">';
	echo '</div></FORM>';
	//echo '</FORM></FONT>';
	

	if ($any!="" || $all!="" || $none!="" ) {
		AfficherParoissiensRecherche($any, $all, $none);
	}
	
	fMENU_bottom();
	mysqli_close($eCOM_db);
	
echo '</BODY>';
echo '</HTML>';

?>
