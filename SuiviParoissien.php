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
require('templateSuiviParoissien.inc');
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
	
	$debug = true;	
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pTitre=".$pTitre);
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pChampsIndividu=".$pChampsIndividu);
	pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements - pEngagement=".$pEngagement);
	
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];


	address_top();
	if (date("n") <= 7 )
	{
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
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
	echo '<SELECT name="Engagement"  >';//'.$BloquerAcces.' >';
	//echo '<option value=" " selected="selected"> </option>';
	echo '<option value="All" selected="selected">All</option>';
	pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Requete = '. $requete);
	$result = mysqli_query($eCOM_db, $requete);//, $db);
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
	}
	echo '</DIV></TR></FORM>';


	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	if ($pChampsIndividu == "Services" ) {
		echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Nom </FONT><BR>";
		echo "<FONT face=verdana color=#555555# size=0>(*) Responsable</FONT><BR>";
		echo "<FONT face=verdana color=#555555# size=0>(c) Point contact</FONT><BR></TH>";
	} else {
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Nom </FONT><FONT face=verdana color=#555555# size=0></FONT></TH>";
	}
	echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Adresse</FONT></TH>";
	echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Téléphone / e-mail</FONT></TH>";

	if ($pChampsIndividu == "Services" ) {
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>".$pChampsIndividu."</FONT></TH>";
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Ressourcements</FONT></TH>\n";
	} elseif ( $pChampsIndividu == "Ressourcements" OR $pChampsIndividu == "Souhaits" ) {
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>".$pChampsIndividu."</FONT></TH>";
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Services</FONT></TH>\n";
	}
	
	if (fCOM_Get_Autorization( 0 ) >= 40 AND $pChampsIndividu != "LangueMaternelle" ) {
		echo "<TH bgcolor=$trcolor><FONT face=verdana size=2>Denier</FONT></TH>";
	}

	$Total_pers = 0;
	$aujourdhui = date("F j, Y, g:i a");
	$File_Counter = 1;

	//debug_plus('pEngagement = '. $pEngagement );
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
	//echo "Pass1 :".$ExtraRequete.'<BR>';
	$debug = True;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass1 :'.$ExtraRequete);	
	
	if ($pChampsIndividu == "Services" ) {
		//$requete = 'SELECT * FROM `Activites` T0 where T0.`Service` = 1 '.$ExtraRequete.' ORDER BY T0.`Nom`';
		$requete = 'SELECT DISTINCT T0.`id`, T0.`Nom` As Activite, IFNULL(T2.`Lieu`, "") As Lieu, T2.`id` As Lieu_id
			FROM Activites T0
			LEFT JOIN QuiQuoi T1 ON T1.`Activite_id`=T0.`id`
			LEFT JOIN Lieux T2 ON T2.`id`= T1.`Lieu_id`
			WHERE T0.`Service`=1 AND T1.`Engagement_id`=0 '.$ExtraRequete.' '.$AddWhere.' ORDER BY T0.`Nom`, Lieu ';

		//echo "Pass2 :".$requete.'<BR>';
		pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass2 :'.$requete);
	} elseif ( $pChampsIndividu == "Ressourcements" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Formation` = 1 '.$ExtraRequete.' ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "Souhaits" ) {
		$requete = 'SELECT * FROM `Activites` T0 where T0.`Souhait` = 1 '.$ExtraRequete.' ORDER BY T0.`Nom`';
	} elseif ( $pChampsIndividu == "LangueMaternelle") {
		$requete = 'SELECT Distinct LangueMaternelle FROM `Individu` T0 where T0.`'.$pChampsIndividu.'`!="Langue Maternelle ?" and T0.`'.$pChampsIndividu.'`!="" and T0.`'.$pChampsIndividu.'`!="Autre" '.$ExtraRequete.' ORDER BY T0.`LangueMaternelle`';
	}

	if ($pChampsIndividu == "Services" AND ( $pEngagement == "" OR  $pEngagement == "All") ) {
		$temp = "load/Organigramme_service.xml";
		$handle_xml = fopen($temp, 'w');
		fwrite($handle_xml, '<?xml version="1.0" encoding="iso-8859-1"?>');
		fwrite($handle_xml, '<!-- Time-stamp: "Organigramme_service.xml   '.$aujourdhui.'" -->');
		fwrite($handle_xml, "\n\r<paroissiens>");
	}

	//debug_plus('Requete = '. $requete );
	$result = mysqli_query($eCOM_db, $requete);//, $db);
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
		}
		
		// ligne de séparation des services
		echo '<TR><TD colspan="2" bgcolor="#A1A1A1"><font face=verdana size=2>'.$TreatedName.'</font></TD><TD bgcolor="#A1A1A1"><font face=verdana size=2> Liste <A HREF="load/ListeMail_'.$File_Counter.'.php">e_mail</A></font></TD>';
		if ($pChampsIndividu == "Services" || $pChampsIndividu == "Ressourcements" || 
			$pChampsIndividu == "Souhaits" ) {
			echo '<TD bgcolor="#A1A1A1"></TD><TD bgcolor="#A1A1A1"></TD>';
		}
		if (fCOM_Get_Autorization( 0 ) >= 40 AND $pChampsIndividu != "LangueMaternelle") {
			echo '<TD bgcolor="#A1A1A1"></TD>';
		}
				
		$temp = "load/ListeMail_".$File_Counter.".php";
		$handle = fopen($temp, 'w');
		fwrite($handle, "<HTML><HEAD><title>Liste adresses mail</title></HEAD>\r\n<BODY><br>");
		fwrite($handle, "<h1><FONT face=verdana>Liste des adresses mail : ".$TreatedName."</FONT></h1>\r\n");
		fwrite($handle, "<FONT face=verdana size=2>");
		fwrite($handle, "<P>Date : ".$aujourdhui."</P>\r\n");
		fwrite($handle, "<P>===================================================</P><BR>\r\n<TABLE>");
		echo "<TR><TD><FONT face=verdana size=2>";
		fwrite($handle, "<FONT face=verdana size=2>");
		$File_Counter += 1;
		if ($pChampsIndividu == "Services" ) {
			$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, IFNULL(T2.Lieu, "") As Lieu, T0.`Lieu_id`, T0.`Responsable`, T0.`Point_de_contact`
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
		}
		
		pCOM_DebugAdd($debug, "SuiviParoissien:Lister_engagements: Requete2=".$requete2);
		
		$result2 = mysqli_query($eCOM_db, $requete2);
		while($row2 = mysqli_fetch_assoc($result2)){
			$Check_Responsable = "";
			$Check_Point_de_contact = "";
			if ($pChampsIndividu == "Services" ) {
				if ($row2['Responsable'] == 1 OR $row2['Point_de_contact'] == 1) {
					if ( $pEngagement == "" OR  $pEngagement == "All") {
						fwrite($handle_xml, "\n\r<user>\n");
						fwrite($handle_xml, "<service>".$TreatedName."</service>\n\r");
						if ($row2['Responsable'] == 1){
							fwrite($handle_xml, "<role>Responsable</role>\n\r");
							$Check_Responsable = "<FONT face=verdana color=#555555# size=0><B>(*)</B></FONT> ";
						}
						if ($row2['Point_de_contact'] == 1) {
							fwrite($handle_xml, "<Point_Contact>Oui</Point_Contact>\n\r");
							$Check_Point_de_contact = "<FONT face=verdana color=#555555# size=0><B>(C)</B></FONT> ";
						}
						fwrite($handle_xml, "<name>".$row2['Prenom']." ".$row2['Nom']."</name>\n\r");
						fwrite($handle_xml, "<telephone>".$row2['Telephone']."</telephone>\n\r");
						fwrite($handle_xml, "<email>".$row2['e_mail']."</email>\n\r");
						fwrite($handle_xml, "</user>");
					}
				}
			}
			fwrite($handle, '"'.$row2['Prenom'].' '.$row2['Nom'].'"< '.$row2['e_mail'].'>; ');
			$trcolor = usecolor();
			echo "<TR><TD width=170 bgcolor=".$trcolor."><FONT face=verdana size=2>";
			if (file_exists("Photos/Individu_".$row2['id'].".jpg")) { 
				echo $Check_Responsable.$Check_Point_de_contact.'<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row2['id'].' class="tooltip">'.$row2['Nom'].' '.$row2['Prenom'].'';
				echo "<EM><SPAN></SPAN>";
				echo "<img src='Photos/Individu_".$row2['id'].".jpg' height='100' border='1' alt='Paroissien_".$row2['id']."'>";
				echo '<BR><font face=verdana size=2>'.$row2['Prenom'].' '.$row2['Nom'].'</FONT>';
				echo "</EM></A>";
			} else {
				echo $Check_Responsable.$Check_Point_de_contact.'<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row2['id'].'>';
				echo "$row2[Nom] $row2[Prenom]";
				echo "</A>";
			}
			echo '</TD>';

			echo "<TD width=200 bgcolor=".$trcolor."><font face=verdana size=2>".Securite_html($row2['Adresse'])."</TD>";
			
			echo "<TD width=70 bgcolor=".$trcolor."><font face=verdana size=2>";
			echo "<A HREF='mailto:$row2[e_mail]?subject= Paroisse : ' TITLE='Envoyer un mail a ".$row2['Prenom']." ".$row2['Nom']."'>".$row2['e_mail']."</A><BR>";
			echo Securite_html($row2['Telephone'])."</TD>";


			// Autres Services ou ressourcements du paroissien
			//------------------------------------------------
			if ($pChampsIndividu == "Services" || $pChampsIndividu == "Ressourcements" || 
				$pChampsIndividu == "Souhaits" ) {
				echo "<TD width=150 bgcolor=".$trcolor."><font face=verdana size=1>";
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
				echo "<TD width=150 bgcolor=".$trcolor."><font face=verdana size=1>";
				if ($pChampsIndividu == "Services") {
					// les ressourcements
					$DeltaWhere='AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1 AND T1.`Formation`=1';
				} elseif ($pChampsIndividu == "Ressourcements" OR $pChampsIndividu == "Souhaits") {
					// les services
					//$DeltaWhere='AND T0.Lieu_id='.$row2['Lieu_id'].' AND T0.`QuoiQuoi_id`=2 AND T1.`Service`=1';
					$DeltaWhere='AND T0.`Session`='.$SessionActuelle.' AND T0.`QuoiQuoi_id`=2 AND T1.`Service`=1';
				}
				$requete3 = 'SELECT DISTINCT T1.`Nom`
					FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id`=T0.`Activite_id`
					WHERE T0.`Individu_id`='.$row2['id'].' AND T0.`Engagement_id`=0 AND T0.`Activite_id`<>'.$row['id'].' '.$DeltaWhere.' ORDER BY T1.`Nom`';			

				$result3 = mysqli_query($eCOM_db, $requete3);//, $db);	
				while($row3 = mysqli_fetch_assoc($result3)){
					echo '- '.$row3['Nom'].'<BR>';
				}
				pCOM_DebugAdd($debug, 'SuiviParoissien:Lister_engagements - Pass5 :'.$requete3);
				echo "</TD>";
			
			// denier
			//--------
			
				if (fCOM_Get_Autorization( 0 ) >= 40) {
					echo "<TD width=160 bgcolor=".$trcolor."><FONT face=verdana size=1>";
					$requete3 = 'SELECT T0.`Date` As Date, T0.`Montant` As Montant FROM `Denier` T0 where T0.`Paroissien_id`='.$row2['id'].' ORDER BY T0.`Date`';
					$result3 = mysqli_query($eCOM_db, $requete3);//, $db);
					while($row3 = mysqli_fetch_assoc($result3)){
						echo '['.$row3['Montant'].' € le '.date("d/m/Y", strtotime($row3['Date'])).']<BR>';
					}
					echo '</FONT></TD>';
				}
			}

			echo "</TR>";
		}
		fwrite($handle, "</TD></TR><TR><TD> </TD></TR><TR><TD> </TD></TR><TR><TD><BR><BR><BR>");
		fwrite($handle, "<FONT face=verdana size=2>");
		fwrite($handle, "(Faites un copier+coller de toute la liste ci-dessus vers la zone destinataire de votre mail)");
		fwrite($handle, "</FONT></TD></TR></TABLE>");
		fwrite($handle, "</BODY></HTML>");
		fclose($handle);
	}
			
	if ($pChampsIndividu == "Services" AND ( $pEngagement == "" OR  $pEngagement == "All") ) {
		fwrite($handle_xml, "\n\r</paroissiens>\n\r");
		fclose($handle_xml);
	}
	
	echo "</TABLE><br>";
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
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

	address_top();

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
	fCOM_address_bottom();
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
	address_top();

	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Rechercher des paroissiens par age</B><BR></TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE"><FONT face=verdana size=1>';
	$trcolor = "#EEEEEE";
	echo '<FORM action="'.$_SERVER['PHP_SELF'].'" method=POST>';
	echo '<B>Trouver des résultats avec : </B><BR> <BR>';
	echo ' <INPUT type="text" length=40 name="AgeMini" value="'.$_POST['AgeMini'].'"> ';
	echo '<= Age < <INPUT type="text" length=40 name="AgeMax" value="'.$_POST['AgeMax'].'"> ';
	echo '<INPUT type="submit" name="AfficherParoissiensParAge" value="Lancer recherche">';
	echo '</FORM></FONT>';
	fAfficherParoissiensParAge($_POST['AgeMini'], $_POST['AgeMax']);
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
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
	
	echo '<TABLE>';

	$trcolor = "#EEEEEE";
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom / Prénom</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Naissance</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Adresse</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Téléphone / <A HREF="load/ListeMail_Paroissien.php">e_mail</A></font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Services</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Ressourcements</font></TH>';
	if (fCOM_Get_Autorization( 0 ) >= 40) {
		echo "<TH bgcolor=$trcolor><font face=verdana size=2>Denier</font></TH>\n";
	}
	
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
	$requete="SELECT id, Prenom, Nom, Adresse, Naissance, e_mail, Telephone FROM Individu WHERE DATE_ADD(Naissance, INTERVAL ".$AgeMax." YEAR)>=CURDATE() and DATE_ADD(Naissance, INTERVAL ".$AgeMini." YEAR)<=CURDATE() ORDER BY Nom, Prenom";
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
	
		// définition de la couleur de la ligne
		$Accompagnateur_couleur = "919191";  // gris
		//if ($row['Services'] > 0 ) {
		//	$Accompagnateur_couleur = "000000"; // noir
		//}
		$trcolor = usecolor();
		echo '<TR>';
		echo '<TD width=150 bgcolor='.$trcolor.'><font face=verdana color=#'.$Accompagnateur_couleur.' size=2>';
		if (file_exists("Photos/Individu_".$row['id'].".jpg")) { 
			echo '<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row['id'].' class="tooltip"><font face=verdana size=2>'.Securite_html($row['Nom']).' '.Securite_html($row['Prenom']).' ';
			echo '<em><span></span>';
			echo '<img src="Photos/Individu_'.$row['id'].'.jpg" height="100" border="1" alt="Paroissien('.$row['id'].')">';
			echo '<br>'.Securite_html($row['Nom']).' '.Securite_html($row['Prenom']).' ';
			echo '</em></A></TD>';
		} else {
			echo '<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row['id'].'>';
			echo '<FONT face=verdana size=2>'.Securite_html($row['Nom']).' '.Securite_html($row['Prenom']).'</FONT>';
			echo '</A></TD>';
		}
		
		echo "<TD bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		echo Securite_html(strftime("%d/%m/%Y", fCOM_sqlDateToOut($row['Naissance']))).'</TD>';
		echo "<TD bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		echo Securite_html($row['Adresse']).'</TD>';
		echo "<TD width=70 bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		echo Securite_html($row['Telephone']).'<BR>';
		//echo "<TD width=70 bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		
		if ( $row['e_mail'] != "" ) {
			fwrite($handle, '"'.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"< '.Securite_html($row['e_mail']).'>; ');
		}
		echo '<A HREF="mailto:'.Securite_html($row['e_mail']).'?subject= Paroisse '.pCOM_Get_NomParoisse().' : " TITLE="Envoyer un mail a '.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"><FONT face=verdana size=2>'.Securite_html($row['e_mail']).'</FONT></A></TD>';

		// Services
		echo '<TD width=170 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
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
		echo '</FONT></TD>';
		
		// Ressourcement
		echo '<TD width=170 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
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
		echo "</FONT></TD>";
		
		// denier
		if (fCOM_Get_Autorization( 0) >= 40) {
			echo "<TD width=150 bgcolor=$trcolor><FONT face=verdana size=1>";
			$requete2 = 'SELECT T0.`Date` As Date, T0.`Montant` As Montant FROM `Denier` T0 where T0.`Paroissien_id`='.$row['id'].' ORDER BY T0.`Date`';
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
	
	echo '<TABLE>';

	$trcolor = "#EEEEEE";
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom / Prénom</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Adresse</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Téléphone / <A HREF="load/ListeMail_Paroissien.php">e_mail</A></font></TH>';
	//echo "<TH bgcolor=$trcolor><font face=verdana size=2>e_mail</font></TH>\n";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Services</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Ressourcements</font></TH>';
	if (fCOM_Get_Autorization( 0 ) >= 40) {
		echo "<TH bgcolor=$trcolor><font face=verdana size=2>Denier</font></TH>\n";
	}
	
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
		//if ($row['Services'] > 0 ) {
		//	$Accompagnateur_couleur = "000000"; // noir
		//}
		$trcolor = usecolor();
		echo '<TR>';
		echo '<TD width=150 bgcolor='.$trcolor.'><font face=verdana color=#'.$Accompagnateur_couleur.' size=2>';
		if ( Securite_html($row['Nom'])=="" AND Securite_html($row['Prenom'])=="") {
			$Identite = "-";
		} else {
			$Identite = Securite_html($row['Nom']).' '.Securite_html($row['Prenom']);
		}
		if (file_exists("Photos/Individu_".$row['id'].".jpg")) { 
			echo '<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row['id'].' class="tooltip"><font face=verdana size=2>'.$Identite.' ';
			echo '<em><span></span>';
			echo '<img src="Photos/Individu_'.$row['id'].'.jpg" height="100" border="1" alt="Paroissien('.$row['id'].')">';
			echo '<br>'.$Identite.' ';
			echo '</em></A></TD>';
		} else {
			echo '<A HREF=SuiviParoissien.php?action=edit_Individu&id='.$row['id'].'>';
			echo '<FONT face=verdana size=2>'.$Identite.'</FONT>';
			echo '</A></TD>';
		}
		echo "<TD bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		echo Securite_html($row['Adresse']).'</TD>';
		echo "<TD width=70 bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		echo Securite_html($row['Telephone']).'<br>';
		//echo "<TD width=70 bgcolor=$trcolor><font face=verdana color=#".$Accompagnateur_couleur." size=2>";
		
		if ( $row['e_mail'] != "" ) {
			fwrite($handle, '"'.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"< '.Securite_html($row['e_mail']).'>; ');
		}
		echo '<A HREF="mailto:'.Securite_html($row['e_mail']).'?subject= Paroisse '.pCOM_Get_NomParoisse().' : " TITLE="Envoyer un mail a '.Securite_html($row['Prenom']).' '.Securite_html($row['Nom']).'"><FONT face=verdana size=2>'.Securite_html($row['e_mail']).'</FONT></A></TD>';

		// Services
		echo '<TD width=170 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
		$requete2 = "SELECT DISTINCT CONCAT(T1.`Nom`, ' [', T0.`Session`, ']') AS list_Services
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Service`=1 AND T0.`Individu_id`=".$row['id']." AND Session > (".$SessionActuelle."-3) AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10))
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
		echo '<TD width=170 bgcolor='.$trcolor.'><FONT face=verdana size=1>';
		$requete2 = "SELECT DISTINCT CONCAT(T1.`Nom`, ' [', T0.`Session`, ']') AS list_Ressourcements
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Formation`=1 AND T0.`Individu_id`=".$row['id']." AND Session >(".$SessionActuelle."-3) AND T0.`QuoiQuoi_id`=1
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
		if (fCOM_Get_Autorization( 0 ) >= 40) {
			echo "<TD width=150 bgcolor=".$trcolor."><FONT face=verdana size=1>";
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
	address_top();
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
	echo '<FORM action="'.$_SERVER['PHP_SELF'].'" method=POST>';
	echo '<B>Trouver des résultats avec : </B><BR> <BR>';
	echo 'Un de ces mots: <INPUT type="text" length=40 name="any" value="'.$any.'"> ';
	echo 'Tous ces mots: <INPUT type="text" length=40 name="all" value="'.$all.'"> ';
	echo 'Aucun de ces mots: <INPUT type="text" length=40 name="none" value="'.$none.'"> ';
	echo '<INPUT type="submit" value="Lancer recherche">';
	echo '</FORM></FONT>';
	//echo '</table>';
	

	if ($any!="" || $all!="" || $none!="" ) {
		AfficherParoissiensRecherche($any, $all, $none);
	}
	
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	
echo '</BODY>';
echo '</HTML>';

?>
