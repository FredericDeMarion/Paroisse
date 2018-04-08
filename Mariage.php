<?php
session_start();

//==================================================================================================
//    Nom du module : Mariage.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 17/05/2017 : Correction, le gestionnaire mariage ne pouvait pas créer de nouvelle fiche fiancée
// 17/05/2017 : Correction, proposer des fiches déjà crées et vierges lors d'une nouvelle création
// 17/07/2017 : Accompagnateur, Modification Impossible check Acte de Naissance, Baptême et Lettre intention
//==================================================================================================

// Initialiser variable si elle n'existe pas
if( ! isset( $edit ) ) $edit = ""; 
if( ! isset( $delete_fiche_fiance ) ) $delete_fiche_fiance = ""; 
if( ! isset( $delete_fiche_fiance_confirme ) ) $delete_fiche_fiance_confirme = ""; 
if( ! isset( $Selectionner_Paroissien ) ) $Selectionner_Paroissien = ""; 
if( ! isset( $upload_Photo_couple ) ) $upload_Photo_couple = "";
if( isset ($_GET['Service']) ) $_SESSION["Activite_id"] = $_GET['Service'];

function debug($ch) {
   global $debug;
   if ($debug)
      echo $ch;
}

//row color

function usecolor( )
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

Global $eCOM_db;
$debug = false;
//$IdSession = $_POST["IdSession"];
//session_readonly();

$Activite= 2; //Preparation mariage
$Activite_id= 2; //Preparation mariage
$SessionEnCours=$_SESSION["Session"];
//require('templateMariage.inc');
require('Menu.php');
require('Common.php');
$debug = false;
pCOM_DebugAdd($debug, "Mariage - SessionEnCours=".$SessionEnCours);

require('Paroissien.php');

//edit records
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit") {
//if ($action == "edit") { 
	
	$debug = false;
	
	if ( $_GET['id'] == 0 ) {
		// creation d'une nouvelle fiche impossible si pas gestionnaire ou administrateur
		if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) 
		{
			$id = 0;
			$requete = 'SELECT id FROM Fiancés WHERE MAJ="0000-00-00 00:00:00" AND Lieu_mariage="" AND Status="" ORDER BY id DESC';
			$result = mysqli_query($eCOM_db, $requete);
			//$row = mysqli_fetch_assoc($result);
			while( $row = mysqli_fetch_assoc( $result)) {
				$id = $row['id'];
			}
			if ( $id == 0 ) {
				$requete = 'INSERT INTO Fiancés (id, Commentaire) VALUES (0,"")'; 
				pCOM_DebugAdd($debug, 'Mariage:edit - requete01='.$requete);
				mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
				$id = mysqli_insert_id($eCOM_db);
				mysqli_query($eCOM_db, 'UPDATE Fiancés SET Session="'.$SessionEnCours.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));

			}
			$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit&id='.$id;
		} else {
			echo '<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'">';
			mysqli_close($eCOM_db);
			exit;
		}
	} else {
		$id= $_GET['id'];
		$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	

	$requete = 'SELECT * FROM Fiancés WHERE id='.$id.' '; 
	pCOM_DebugAdd($debug, 'Mariage:edit - requete02='.$requete);
	$result = mysqli_query($eCOM_db, $requete);
	$row = mysqli_fetch_assoc($result);
	
	fMENU_top();
		
	if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Edition: ';
	echo 'Fiche No '.$row['id'].' </TD>'; 
	if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['MAJ'])) != "01/01/70" ) {
		echo '<TD align="right"><FONT FACE="Verdana" SIZE="1"> (Dernière modification au '.$row['MAJ'].')</TD>';
	}
	echo '</TR>';
	

	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2"><CENTER><font face="verdana" size="2">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'">';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	
	// Recherche de LUI_id
	$requete2 = 'SELECT T1.`id` 
				FROM QuiQuoi T0 
				LEFT JOIN Individu T1 ON T1.`id`=T0.`Individu_id`
				WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=1 AND T0.`Engagement_id`='.$id.' AND T1.`Sex`="M"';
	$result2 = mysqli_query($eCOM_db, $requete2);
	$row2 = mysqli_fetch_assoc($result2);
	$LUI_id = $row2['id'];
	
	if ( $LUI_id > 0) {
		$requete2 = 'SELECT * FROM Individu WHERE id='.$LUI_id.''; 
		pCOM_DebugAdd($debug, 'Mariage:edit - requete03='.$requete2);
		$result2 = mysqli_query($eCOM_db, $requete2);
		$row1 = mysqli_fetch_assoc($result2);
	}
	
	// Recherche de ELLE_id
	$requete2 = 'SELECT T1.`id` 
				FROM QuiQuoi T0 
				LEFT JOIN Individu T1 ON T1.`id`=T0.`Individu_id`
				WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=1 AND T0.`Engagement_id`='.$id.' AND T1.`Sex`="F"';
	$result2 = mysqli_query($eCOM_db, $requete2);
	$row2 = mysqli_fetch_assoc($result2);
	$ELLE_id = $row2['id'];
	
	if ( $ELLE_id > 0) {
		$requete2 = 'SELECT * FROM Individu WHERE id='.$ELLE_id.''; 
		pCOM_DebugAdd($debug, 'Mariage:edit - requete04='.$requete2);
		$result2 = mysqli_query($eCOM_db, $requete2);
		$row2 = mysqli_fetch_assoc($result2);
	}
	
	
	// Photo	
	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		echo '<TR><TD></TD><TD align="center" valign="middle" >';
		if (file_exists("Photos/" . $row['id'] . ".jpg")) { 
			echo '<IMG SRC="Photos/' . $row['id'] . '.jpg" HEIGHT=150><BR><BR>';
			if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
			echo '<div align=center><input type="submit" class="btn btn-outline-secondary btn-sm" name=upload_Photo_couple value="Charger une autre photo...">'; }		
		} else {
			if (fCOM_Get_Autorization( $Activite_id ) >= 30) {
			echo '<div align=center><input type="submit" class="btn btn-outline-secondary btn-sm" name=upload_Photo_couple value="Charger une photo...">'; }
		}
		echo '</TD></TR>';
	}
	
	
	//------
	// LUI
	//------
	
	echo '<TR><TD width="140" bgcolor="#eeeeee" valign="top">';
	if ( $BloquerAcces=="" )
	{
		pCOM_DebugAdd($debug, 'Mariage:edit - LUI_id='.$LUI_id);
		if ( $LUI_id > 0 ) {
			echo '<DIV style="display:inline"><input class="btn btn-secondary" type="submit" name="Selectionner_Paroissien" value="Le fiancé">';
		} else {
			echo '<DIV style="display:inline"><input class="btn btn-secondary" type="submit" name="Selectionner_Paroissien" value="Sélectionner le fiancé">';
		}
		echo '<INPUT type="hidden" name="Fiche_id" value="'.$id.'">';
		echo '<INPUT type="hidden" name="ButtomName" value="LUI">';
		echo '</DIV>';
	} else {
		echo '<B><FONT SIZE="3">LUI</FONT></B>';
	}
	echo '</TD>';
	
	echo '<TD bgcolor="#eeeeee" colspan="2">';
	if ( $LUI_id > 0 ) {
		if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			fCOM_Display_Photo($row1['Nom'], $row1['Prenom'], $LUI_id, "edit_Individu", True);
		} else {
			echo '<FONT SIZE="2">'.ucwords($row1['Prenom']). ' ' .$row1['Nom'].'</FONT>';
		}
		// Téléphone
		echo '<BR><FONT SIZE="1"><B>Téléphone: </B>'.Securite_html($row1['Telephone']).'</FONT>';
		// email
		echo '<BR><FONT SIZE="1"><B>Email: </B>'.Securite_html($row1['e_mail']).'</FONT>';
		// adresse
		echo '<BR><FONT SIZE="1"><B>Adresse: </B>'.Securite_html($row1['Adresse']).'</FONT>';

		// Date de Naissance
		echo '<B><FONT SIZE="2"><BR>Né le :</FONT></B>';
		if ( $row1['Naissance'] != "0000-00-00" ) {
			echo '<FONT FACE="Verdana" SIZE="1"> '.date("d/m/Y", strtotime($row1['Naissance']));
			$Age = fCOM_Afficher_Age($row1['Naissance']);
			if ( $Age > -1 ) {
				echo '<FONT FACE="Verdana" SIZE="1"> ('.$Age.' ans) </FONT>';
			}
		} else {
			echo '<FONT FACE="Verdana" SIZE="1">---</FONT>';
		}
		echo '</TD></TR>';

		// Enfants
		if ( $ELLE_id > 0 ) {
			$ConditionWhere='AND T0.`Mere_id`!='.$ELLE_id.'';
		} else {
			$ConditionWhere='';
		}
		$requeteEnfants = 'SELECT T0.id, T0.`Nom`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` 
							FROM `Individu` T0 
							WHERE T0.`Pere_id`='.$LUI_id.' '.$ConditionWhere.' 
							ORDER BY Naissance';
		$debug = false;
		pCOM_DebugAdd($debug, 'Mariage:edit - requeteEnfants01='.$requeteEnfants);
		$TitreLigne ='<TR><TD></TD><TD><FONT SIZE="2">Enfant(s) : </FONT>';
		$resultListEnfants = mysqli_query($eCOM_db, $requeteEnfants);
		while( $ListEnfants = mysqli_fetch_assoc( $resultListEnfants ))
		{
			echo $TitreLigne;
			$TitreLigne = "";
			fCOM_Display_Photo("", $ListEnfants['Prenom'], $ListEnfants['id'], "edit_Individu", true);
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($ListEnfants['Naissance'])) != "01/01/70" ) {
				$birthDate = explode("-", $ListEnfants['Naissance']);
				$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
				//$Prenom= $Prenom." ($Age ans)";
				
				echo '<FONT SIZE="1">('.$Age.' ans) </FONT>';
			} else {
				echo '</A><FONT SIZE="1"> - </FONT>';
			}
		}
		if ($TitreLigne == "")
		{
			echo '</TD></TR>';
		}
		echo '</TD></TR>';
		
		// Confession

		echo '<TR><TD></TD><TD><div class="col-small">';
		echo '<B><FONT SIZE="2"> Confession :</FONT></B>';
		echo '<select class="form-control form-control-sm" style="width:160px" name="LConfession" '.$BloquerAcces.' >';
		foreach ($Liste_Confessions as $LConfession){
			if ($row1['Confession'] == $LConfession){
				echo '<option value="'.$LConfession.'" selected="selected">'.$LConfession.'</option>';
			} else {
				echo '<option value="'.$LConfession.'">'.$LConfession.'</option>';
			}
		}		
		echo '</select>';
		echo '</div>';
	} else {
		echo '<input type=hidden name="LConfession" value="Confession ?">';	
	}

	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		pCOM_DebugAdd($debug, 'Mariage:edit - ELLE_id='.$ELLE_id);
		// Selection de la declaration intention LUI
		$Declaration="";
		if ($row1['Confession'] == "Catholique") {
			if ($row2['Confession'] == "Catholique"){ $Declaration="1a";}
			else {
				if ($row2['Confession'] == "Sans"){ $Declaration="4a";}
				else {
					if ($row2['Confession'] == "Catéchumène"){ $Declaration="3a";}
					else { $Declaration="2a";}
				}
			}
		} else {
			if ($row1['Confession'] == "Sans") {
				if ($row2['Confession'] == "Catholique"){ 
					$Declaration="4b";
				} else { 
					$Declaration="D. Intention Impossible";
				}
			} else {
				if ($row1['Confession'] == "Catéchumène") {
					if ($row2['Confession'] == "Catholique"){ 
						$Declaration="3b";
					} else { 
						echo "D. Intention Impossible";
					}
				} elseif ($row1['Confession'] == "Musulman") { 
					if ($row2['Confession'] == "Catholique"){ 
						$Declaration="5b";
					} else { 
						echo "D. Intention Impossible";
					}
				} else { 
					if ($row1['Confession'] == "Confession ?" or $row1['Confession'] == "") { 
						$Declaration="";
					} else {
						$Declaration="2b";
					}
				}
			}
		}
		echo "Formulaire : <A href=\"Formulaires/".$Declaration.".pdf\" target=\"_blank\"><FONT SIZE=\"2\">$Declaration</FONT></A>";
	}
	echo "</TD></TR>";

	if ( $LUI_id > 0 ) {

		echo '<TR><TD bgcolor="#eeeeee" valign="top"></TD>';
		echo '<TD colspan="2" valign="top"><P>';
		if ($row['LUI_Extrait_Naissance'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="LUI_Acte_Naissance" '.$BloquerAcces.' id="LUI_Acte_Naissance" '.$optionSelect.' /> <label for="LUI_Acte_Naissance"><FONT SIZE="2">Acte de Naissance</b></label>&nbsp&nbsp';
		if ($row['LUI_Extrait_Bapteme'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="LUI_Acte_Bapteme" '.$BloquerAcces.' id="LUI_Acte_Bapteme" '.$optionSelect.' /> <label for="LUI_Acte_Bapteme"><FONT SIZE="2">Acte de Baptême<br></b></label>&nbsp&nbsp';
		if ($row['LUI_Lettre_Intention'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="LUI_Lettre_Intention" '.$BloquerAcces.' id="LUI_Lettre_Intention" '.$optionSelect.' /> <label for="LUI_Lettre_Intention"><FONT SIZE="2">Lettre d\'intention</b></label>';
		echo '</P></TD></TR><TR><TD height="10"></TD></TR>';
	
	} else {
		echo '<input type=hidden name="LUI_Acte_Naissance" value="0">';
		echo '<input type=hidden name="LUI_Acte_Bapteme" value="0">';
		echo '<input type=hidden name="LUI_Lettre_Intention" value="0">';
	}
	
	//------
	// ELLE
	//------
	
	echo '<TR><TD width="140" bgcolor="#eeeeee" valign="top">';
	if ( $BloquerAcces=="" )
	{
		if ( $ELLE_id > 0 ) {
			echo '<DIV style="display:inline"><input type="submit" class="btn btn-secondary" name="Selectionner_Paroissien" value="La fiancée">';
		} else {
			echo '<DIV style="display:inline"><input type="submit" class="btn btn-secondary" name="Selectionner_Paroissien" value="Sélectionner la fiancée">';
		}
		echo '<INPUT type="hidden" name="Fiche_id" value="'.$id.'">';
		echo '<INPUT type="hidden" name="ButtomName" value="ELLE">';
		echo '</DIV>';
	} else {
		echo '<B><FONT SIZE="3">ELLE</FONT></B>';
	}
	echo '</TD>';
	
	echo '<TD bgcolor="#eeeeee" colspan="2">';
	if ( $ELLE_id > 0 ) {
		if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			fCOM_Display_Photo($row2['Nom'], $row2['Prenom'], $ELLE_id, "edit_Individu", True);
		} else {
			echo '<FONT SIZE="2">'.ucwords($row2['Prenom']). ' ' .$row2['Nom'].'</FONT>';
		}
		
		// Téléphone
		echo '<BR><FONT SIZE="1"><B>Téléphone: </B>'.Securite_html($row2['Telephone']).'</FONT>';
		// email
		echo '<BR><FONT SIZE="1"><B>Email: </B>'.Securite_html($row2['e_mail']).'</FONT>';
		// adresse
		echo '<BR><FONT SIZE="1"><B>Adresse: </B>'.Securite_html($row2['Adresse']).'</FONT>';

		// Date de Naissance
		echo '<B><FONT SIZE="2"><BR>Né le :</FONT></B>';
		if ( $row2['Naissance'] != "0000-00-00" ) {
			echo '<FONT FACE="Verdana" SIZE="1"> '.date("d/m/Y", strtotime($row2['Naissance']));
			$Age = fCOM_Afficher_Age($row2['Naissance']);
			if ( $Age > -1 ) 
			{
				echo '<FONT FACE="Verdana" SIZE="1"> ('.$Age.' ans) </FONT>';
			}
		} else {
			echo '<FONT FACE="Verdana" SIZE="1">---</FONT>';
		}
		echo '</TD></TR>';
		
		if ( $LUI_id > 0 ) {
			$ConditionWhere='AND T0.`Pere_id`!='.$LUI_id.'';
		} else {
			$ConditionWhere='';
		}
		$requeteEnfants = 'SELECT T0.id, T0.`Nom`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` 
							FROM `Individu` T0 
							WHERE T0.`Mere_id`='.$ELLE_id.' '.$ConditionWhere.' 
							ORDER BY Naissance';
		$debug = false;
		pCOM_DebugAdd($debug, 'Mariage:edit - requeteEnfants02='.$requeteEnfants);
		$TitreLigne ='<TR><TD></TD><TD><FONT SIZE="2">Enfant(s) : </FONT>';
		$resultListEnfants = mysqli_query($eCOM_db, $requeteEnfants);
		while( $ListEnfants = mysqli_fetch_assoc( $resultListEnfants ))
		{
			echo $TitreLigne;
			$TitreLigne = "";
			fCOM_Display_Photo("", $ListEnfants['Prenom'], $ListEnfants['id'], "edit_Individu", true);
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($ListEnfants['Naissance'])) != "01/01/70" ) {
				$birthDate = explode("-", $ListEnfants['Naissance']);
				$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
				//$Prenom= $Prenom." ($Age ans)";
				
				echo '<FONT SIZE="1">('.$Age.' ans) </FONT>';
			} else {
				echo '</A><FONT SIZE="1"> - </FONT>';
			}
		}
		if ($TitreLigne == "")
		{
			echo '</TD></TR>';
		}
	
		echo '</TD></TR>';
		
		// Confession
		echo '<TR><TD></TD><TD><div class="col-small">';
		echo '<B><FONT SIZE="2"> Confession :</FONT></B>';
		echo '<select class="form-control form-control-sm" style="width:160px" name="EConfession" '.$BloquerAcces.' >';
		foreach ($Liste_Confessions as $EConfession){
			if ($row2['Confession'] == $EConfession){
				echo '<option value="'.$EConfession.'" selected="selected">'.$EConfession.'</option>';
			} else {
				echo '<option value="'.$EConfession.'">'.$EConfession.'</option>';
			}
		}		
		echo '</select>';
	} else {
		echo '<input type=hidden name="EConfession" value="Confession ?">';
	}
	
	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		// Selection de la declaration intention ELLE
		$Declaration="";
		if ($row2['Confession'] == "Catholique") {
			if ($row1['Confession'] == "Catholique"){
				$Declaration="1a";
			} else {
				if ($row1['Confession'] == "Sans"){ 
					$Declaration="4a";
				} elseif ($row1['Confession'] == "Catéchumène"){ 
					$Declaration="3a";
				} elseif ($row1['Confession'] == "Musulman"){ 
					$Declaration="5a";
				} else {
					$Declaration="2a";
				}
			}
		} else {
			if ($row2['Confession'] == "Sans") {
				if ($row1['Confession'] == "Catholique"){ 
					$Declaration="4b";
				} else {
					$Declaration="D. Intention Impossible";
				}
			} else {
				if ($row2['Confession'] == "Catéchumène") {
					if ($row1['Confession'] == "Catholique"){ 
						$Declaration="3b";
					} else { 
						echo "D. Intention Impossible";
					}
				} else { 
					if ($row2['Confession'] == "Confession ?" or $row2['Confession'] == "") { 
						$Declaration="";
					} else {
						$Declaration="2b";
					}
				}
			}
		}
		echo "Formulaire : <a href=\"Formulaires/".$Declaration.".pdf\" target=\"_blank\"><FONT SIZE=\"2\">$Declaration</FONT></a>";
	}
	echo '</TD></TR>';
	
	// Enfants de ELLE
	if ( $ELLE_id > 0 ) {

		echo '<TR><TD bgcolor="#eeeeee" valign="top"></TD>';
		echo '<TD colspan="2" valign="top"><P>';
		if ($row['ELLE_Extrait_Naissance'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="ELLE_Acte_Naissance" '.$BloquerAcces.' id="ELLE_Acte_Naissance" '.$optionSelect.' /> <label for="ELLE_Acte_Naissance"><FONT SIZE="2">Acte de Naissance</b></label>&nbsp&nbsp';
		if ($row['ELLE_Extrait_Bapteme'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="ELLE_Acte_Bapteme" '.$BloquerAcces.' id="ELLE_Acte_Bapteme" '.$optionSelect.' /> <label	for="ELLE_Acte_Bapteme"><FONT SIZE="2">Acte de Baptême<br></b></label>&nbsp&nbsp';
		if ($row['ELLE_Lettre_Intention'] == '1') { $optionSelect = "checked"; } else { $optionSelect = ""; };
		echo '<input type="checkbox" name="ELLE_Lettre_Intention" '.$BloquerAcces.' id="ELLE_Lettre_Intention" '.$optionSelect.' /> <label for="ELLE_Lettre_Intention"><FONT SIZE="2">Lettre d\'intention</b></label>';
		echo '</P></TD></TR><TR><TD height="10"></TD></TR>';
	
	} else {
		echo '<input type=hidden name="ELLE_Acte_Naissance" value="0">';
		echo '<input type=hidden name="ELLE_Acte_Bapteme" value="0">';
		echo '<input type=hidden name="ELLE_Lettre_Intention" value="0">';
	}
	
		// Enfants en commun
		echo '<TR><TD bgcolor="#eeeeee" valign="top"><B><FONT SIZE="2">Enfant:</FONT></B></TD>';
		echo '<TD bgcolor="#eeeeee" colspan="2" valign="bottom">';
		if ( $LUI_id > 0 && $ELLE_id > 0 ) {
			$requeteEnfants = 'SELECT T0.id, T0.`Nom`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` FROM `Individu` T0 WHERE T0.`Mere_id`='.$ELLE_id.' AND T0.`Pere_id`='.$LUI_id.' ORDER BY Naissance';
			$debug = False;
			pCOM_DebugAdd($debug, 'Mariage:edit - requeteEnfants03='.$requeteEnfants);
			$TitreLigne ='<FONT SIZE="2">Enfant(s) : </FONT>';
			$resultListEnfants = mysqli_query($eCOM_db, $requeteEnfants);
			while( $ListEnfants = mysqli_fetch_assoc( $resultListEnfants )) {
				echo $TitreLigne;
				$TitreLigne = "";
				fCOM_Display_Photo("", $ListEnfants['Prenom'], $ListEnfants['id'], "edit_Individu", True);
				if (strftime("%d/%m/%y", fCOM_sqlDateToOut($ListEnfants['Naissance'])) != "01/01/70" ) {
					$birthDate = explode("-", $ListEnfants['Naissance']);
					$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
					//$Prenom= $Prenom." ($Age ans)";
				
					echo '<FONT SIZE="1">('.$Age.' ans) </FONT>';
				} else {
					echo '</A><FONT SIZE="1"> - </FONT>';
				}
			}
			if ($TitreLigne == "") {
				echo '<BR>';
			}
		}
		echo '<input type="text" class="form-control form-control-sm" name="NEnfant" placeholder="Ex: 0 ou G(2001) F(2002)" value ="'.$row['Enfant'].'" size="40" maxlength="40" '.$BloquerAcces.'>';
		echo '<BR></TD></TR>';
	
	
	if ( $ELLE_id > 0 AND $LUI_id > 0 ) {
		// Premier ministre à avoir accueilli les fiancés
		
		echo '<TR><TD bgcolor="#eeeeee"><b><FONT SIZE="2">1er contact:</FONT></b></TD><TD>';
		echo '<SELECT class="form-control form-control-sm" name="Prem_Accueil_id" '.$BloquerAcces.' >';
		$Liste_Celebrants = fCOM_Get_liste_celebrants($row['Prem_Accueil_id']);
		foreach ($Liste_Celebrants as $Celebrant_array){
			list($celebrant_id, $celebrant_prenom, $celebrant_nom)=$Celebrant_array;
			if ($row['Prem_Accueil_id'] == $celebrant_id ){
				echo '<option value='.$celebrant_id.' selected="selected">'.$celebrant_prenom.' '.$celebrant_nom.'</option>';
			} else {
				echo '<option value='.$celebrant_id.'>'.$celebrant_prenom.' '.$celebrant_nom.'</option>';
			}
		}
		echo '</SELECT></TD></TR>';
	}
	
	// Accompagnateur
	echo '<TR><TD valign= "top" bgcolor="#eeeeee">';
	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		echo '<DIV><INPUT type="submit" class="btn btn-outline-secondary btn-sm" name="Selectionner_Paroissien" value="Accompagnateur(s)"></TD>';
		echo '<TD>';
		$requete2 = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T0.`Sex` 
				FROM `Individu` T0 
				LEFT JOIN `QuiQuoi` T1 ON T0.`id`=T1.`Individu_id` 
				WHERE T1.`Activite_id`=2 AND T1.`QuoiQuoi_id`=2 and T1.`Engagement_id`='.$id.'
				ORDER BY Sex, Prenom, Nom';
		$debug = false;
		$NbFiches=0;
		pCOM_DebugAdd($debug, 'Mariage:edit - requete accompagnateur='.$requete2);
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2 ))
		{
			if ( fCOM_Get_Autorization($_SESSION["Activite_id"]) >= 30 ) {
				echo '<A HREF=".$_SERVER["PHP_SELF"]."?action=RetirerAccompagnateur&Qui_id='.$row2['id'].'&Invite_id=".$id." TITLE="Retirer Accompagnateur"><i class="fa fa-minus-circle text-danger"></i></a>  ';
				fCOM_Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], "edit_Individu", True);
			} else {
				fCOM_Display_Photo(Securite_html($row2['Nom']), Securite_html($row2['Prenom']), $row2['id'], "edit_Individu", False);
			}
			echo '<BR>';
			$NbFiches = $NbFiches +1;
		}
		if ($NbFiches == 0) {
			echo '<FONT FACE="Verdana" SIZE="1">Pas d\'accompagnateurs encore sélectionnés</FONT>';
		}
	}
	echo '</TD>';


	// Session
	echo '<TR><TD align=left" valign="top">';
	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		echo '<div class="container-fluid">';
		echo '<div class="form-row">';
		echo '<div class="col-form-label">';		
		if ($row['Session']=="0" ) {
			$TestSession = $SessionEnCours;
		} else {
			$TestSession = $row['Session'];
		}
		echo '<B><FONT FACE="Verdana" SIZE="2">Session:</FONT></B></TD><TD>';
		echo '<SELECT class="form-control form-control-sm" style="width:100px" name="AnneeSession" '.$BloquerAcces.'>';
		for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
			if ($i == intval($TestSession)) {echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';} else {echo '<option value="'.$i.'">'.$i.'</option>';}
		}
		echo "</SELECT>";
		echo '</div>';
		// Status 
		//echo "<BR>";
		$Liste_Status = array("A affecter");
		$Item = 1;
		$Liste_Status[$Item]="Parcours normal";
		$Item =$Item + 1;
		$Liste_Status[$Item]="Prépa. Ext.";
		$Item =$Item + 1;
		$Liste_Status[$Item]="Prépa. Ext. + WE";
		$Item =$Item + 1;
		$Liste_Status[$Item]="Annulé/Reporté";
		$Item =$Item + 1;
		$Liste_Status[$Item]="WE";
		$Item =$Item + 1;
		$Liste_Status[$Item]="WE Annulé";
		$Item =$Item + 1;
		$Liste_Status[$Item]="CANA WE";
		$Item =$Item + 1;
		$Liste_Status[$Item]="CANA WE Annulé";
		$Item =$Item + 1;
		$Liste_Status[$Item]="Autre";
		echo '<div class="col-form-label">';
		echo '<SELECT class="form-control form-control-sm" name="Status" '.$BloquerAcces.' >';
		foreach ($Liste_Status as $Status){
			if ($row['Status'] == $Status){
				echo '<option value="'.$Status.'" selected="selected">'.$Status.'</option>';
			} else {
				echo '<option value="'.$Status.'">'.$Status.'</option>';
			}
		}		
		echo '</SELECT>';
		echo '</div>';
		echo '</div></div>';
		echo '</TD></TR>';
	


		// Lieu du mariage
		
		// Il n'y a qu'un champs Lieu_mariage, soit le lieu fait partie de la préliste, et on le pré-sélectionne dans cette liste, soit on remplit le champs libre à droite.
		echo '<TR><TD bgcolor="#eeeeee" valign="top"><B><FONT SIZE="2">Lieu du mariage:</FONT></B></TD>';
		echo '<TD bgcolor="#eeeeee" colspan="2">';
		echo '<select class="form-control form-control-sm" name="LMariage" '.$BloquerAcces.' >';
		$Liste_Lieu_Celebration = pCOM_Get_liste_lieu_celebration(1000);
		$Lieu_Celebration_trouve = false;
		foreach ($Liste_Lieu_Celebration as $Lieu_Celebration_array){
			list($Lieu_id, $Lieu_name) = $Lieu_Celebration_array;
			if ($row['Lieu_mariage'] == $Lieu_name){
				$Lieu_Celebration_trouve = TRUE;
			}
		}		
		foreach ($Liste_Lieu_Celebration as $Lieu_Celebration_array){
			list($Lieu_id, $Lieu_name) = $Lieu_Celebration_array;
			if ($row['Lieu_mariage'] == $Lieu_name OR ($Lieu_name == "Hors Paroisse" AND $Lieu_Celebration_trouve == False AND $row['Lieu_mariage'] != "" )){
				echo '<option value="'.$Lieu_name.'" selected="selected">'.$Lieu_name.'</option>';
			} else {
				echo '<option value="'.$Lieu_name.'">'.$Lieu_name.'</option>';
			}
		}
		echo '</SELECT>';
		echo ' ';
		echo '<INPUT type="text" class="form-control form-control-sm" name=AutreLMariage placeholder="Autre lieu de mariage : <Ville> (<dept>)" ';
		if ( $Lieu_Celebration_trouve == false) {
			echo ' value ="'.$row['Lieu_mariage'].'"';
		}
		echo ' size="40" maxlength="30" '.$BloquerAcces.'>';
		echo '</TD></TR>';
	
		// Date du mariage
		//echo '';
		//if (! empty($row['Date_mariage'])) {
		//	$DateYear=substr($row['Date_mariage'],0,4);
		//	$DateMonth=substr($row['Date_mariage'],5,2);
		//	$DateDay=substr($row['Date_mariage'],8,2);
		//	$DateValue = $DateDay."/".$DateMonth."/".$DateYear;
		//}

		echo '<TR><TD bgcolor="#eeeeee" valign="top"><B><FONT SIZE="2">Date du mariage:</FONT></B></TD>';
		echo '<TD width="225" bgcolor="#eeeeee" colspan="2">';
		echo '<div class="form-row">';
		echo '<input type="date" class="form-control form-control-sm" style="width:140px" id="DateMariage" name="DateMariage" value ="'.substr($row['Date_mariage'],0,10).'" size="9" maxlength="10" '.$BloquerAcces.'>';
		//echo '</SELECT>';
		
		echo '<b><FONT SIZE="2">&nbsp Heure &nbsp</FONT></b>';
		$hour = substr($row['Date_mariage'],11,5);
		echo '<input type="time" class="form-control form-control-sm" style="width:140px" id="HeureMariage" name="heure" value ="'.substr($row['Date_mariage'],11,5).'" size="9" maxlength="10" '.$BloquerAcces.'>';
		echo '</div>';
		echo '</TD></TR>';
	
	
	
		// Celebrant
		
		echo '<TR><TD bgcolor="#eeeeee" valign="top"><b><FONT SIZE="2">Célébrant:</FONT></b></td>';
		echo '<TD bgcolor="#eeeeee" colspan="2">';
		echo '<SELECT class="form-control form-control-sm" name="Celebrant" '.$BloquerAcces.' >';
		//$Liste_Celebrants = get_liste_celebrants_Mariage();
		$Liste_Celebrants = fCOM_Get_liste_celebrants($row['Celebrant_id']);
		//debug_plus('Mariage.php celebrants are = "'.$Liste_Celebrants[1].'"');
		$Celebrant_trouve = ' value ="'.$row['Celebrant'].'"';
		$Item = 1;
		foreach ($Liste_Celebrants as $Celebrant_array){
			list($celebrant_id, $celebrant_prenom, $celebrant_nom)=$Celebrant_array;
			if ($row['Celebrant_id'] == $celebrant_id AND $celebrant_id != 0 ){
				$Celebrant_trouve = '';
			}
			$Item = $Item + 1;
		}
		
		foreach ($Liste_Celebrants as $Celebrant_array){
			list($celebrant_id, $celebrant_prenom, $celebrant_nom)=$Celebrant_array;
			if ($row['Celebrant_id'] == $celebrant_id ){
				echo '<option value='.$celebrant_id.' selected="selected">'.$celebrant_prenom.' '.$celebrant_nom.'</option>';
			} else {
				echo '<option value='.$celebrant_id.'>'.$celebrant_prenom.' '.$celebrant_nom.'</option>';
			}
		}
		if ($row['Celebrant_id'] == -1 ) {
			echo '<option value=-1 selected="selected">Célébrant Extérieur</option>';
		} else {
			echo '<option value=-1 ">Célébrant Extérieur</option>';
		}
		echo '</SELECT>';
		echo ' ';
		echo '<INPUT type="text" class="form-control form-control-sm" name=Autre_Celebrant placeholder="Autre célébrant de la liste" '.$Celebrant_trouve .' size="40" maxlength="40" '.$BloquerAcces.'>';
	
	
		echo '</TD></TR>';
	
	
		// Commentaire ==================
		echo '<TR>';
		if ($_SERVER['PHP_AUTH_USER'] != "sacristie" || fCOM_Get_Autorization( $Activite_id ) >= 20) {
			echo '<TD colspan="2" bgcolor="#eeeeee" VALIGN=TOP><B><FONT SIZE="2">Commentaires:</FONT></B><BR>';
			echo '<textarea cols=65 rows=6 class="form-control form-control-sm" name="Commentaire" maxlength="350" value ="'.$row['Commentaire'].'">'.$row['Commentaire'].'</textarea></TD>';
		}
	
		// Participation financière =============================
		if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) { 
			echo '<TD bgcolor="#eeeeee"><b><FONT SIZE="2">Participation financière:</FONT></b><br>';
			
			echo '<div class="input-group">';
			echo '<span class="input-group-addon"><i class="fa fa-eur"></i></span>';
			echo '<input class="form-control" type="text" name=Finance_total value ="'.$row['Finance_total'].'" placeholder="00" size="5" maxlength="9" '.$BloquerAcces.'>';
			echo '</div>';
			
			echo '<B><FONT SIZE="1">Finance commentaires:</FONT></B><br>';
			echo '<textarea cols=25 rows=3 class="form-control form-control-sm" name="Finance_commentaire" maxlength="100" value ="'.$row['Finance_commentaire'].'">'.$row['Finance_commentaire'].'</textarea>';
			echo '</TD>';
		}
	}
	echo '</TR>';
	
	echo '<TR><TD></TD><TD>';
	echo '<input type=hidden name=id value="'.$id.'">';
	
	if ( $LUI_id > 0 && $ELLE_id > 0 ) {
		if ($_SERVER['USER'] <= 3 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) {
			echo '<br><div align="center"><input type="submit" class="btn btn-secondary" name="edit" value="Enregistrer"> ';
			echo '<input type="reset" class="btn btn-secondary" name="Reset" value="Reset">';
		}
		if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
			echo ' <input type="submit" class="btn btn-secondary" name="delete_fiche_fiance" value="Détruire la fiche">';
		}
	}
	echo '</TD>';
	echo '</TR></TABLE>';
	echo '</FORM>';
	echo '</CENTER>';

	fMENU_bottom();
	mysqli_close($eCOM_db);
	exit(); 
}



function Sauvegarder_fiche_fiance ()
{
	Global $eCOM_db;
	$debug = False;
	
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Mariage:Sauvegarder_fiche_fiance - id = ".$_POST['id']);
	pCOM_DebugAdd($debug, "Mariage:Sauvegarder_fiche_fiance - Session = ".$_POST['AnneeSession']);
	pCOM_DebugAdd($debug, "Mariage:Sauvegarder_fiche_fiance - Prem_Accueil_id = ".$_POST['Prem_Accueil_id']);
	pCOM_DebugAdd($debug, "Mariage:Sauvegarder_fiche_fiance - Autre_Celebrant = ".$_POST['Autre_Celebrant']);

	if ( isset($_POST['id']) AND $_POST['id'] > 0 ) {$id = $_POST['id'];} else { $id = 0;}

	if (fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30 AND $id > 0 ) {

		$DateTimeValue = $_POST['DateMariage'].' '.$_POST['heure'].':00';
		pCOM_DebugAdd($debug, "Mariage:Sauvegarder_fiche_fiance - DateTimeValue=".$DateTimeValue);			
		if ( isset($_POST['LMariage']) AND $_POST['LMariage'] != "" ) 
			{ $LMariage = $_POST['LMariage']; } else { $LMariage = ""; }
		if ( isset($_POST['AutreLMariage']) AND $_POST['AutreLMariage'] != "" ) 
			{ $AutreLMariage = $_POST['AutreLMariage']; } else { $AutreLMariage = ""; }
		if ($AutreLMariage != "") { $LMariage = $AutreLMariage; }
		
		if ( isset($_POST['LUI_Acte_Naissance']) AND $_POST['LUI_Acte_Naissance'] == "on") 
			{ $LActeNaissance = 1; } else { $LActeNaissance = 0; }
		if ( isset($_POST['LUI_Acte_Bapteme']) AND $_POST['LUI_Acte_Bapteme'] == "on") 
			{ $LActeBapteme = 1;	} else { $LActeBapteme = 0;	}
		if ( isset($_POST['LUI_Lettre_Intention']) AND $_POST['LUI_Lettre_Intention'] == "on") 
			{ $LLettreIntention = 1;	} else { $LLettreIntention = 0;	}			
			
		if ( isset($_POST['LConfession']) AND $_POST['LConfession'] != "" ) 
			{ $LConfession = $_POST['LConfession']; } else { $LConfession = ""; }

		if ( isset($_POST['ELLE_Acte_Naissance']) AND $_POST['ELLE_Acte_Naissance'] == "on") 
			{$EActeNaissance = 1; } else { $EActeNaissance = 0; }
		if ( isset($_POST['ELLE_Acte_Bapteme']) AND $_POST['ELLE_Acte_Bapteme'] == "on") 
			{ $EActeBapteme = 1; } else { $EActeBapteme = 0; }
		if ( isset($_POST['ELLE_Lettre_Intention']) AND $_POST['ELLE_Lettre_Intention'] == "on") 
			{ $ELettreIntention = 1; } else { $ELettreIntention = 0; }			
			
		if ( isset($_POST['AnneeSession']) AND $_POST['AnneeSession'] != "" ) 
			{ $AnneeSession = $_POST['AnneeSession']; } else { $AnneeSession = ""; }

		if ( isset($_POST['EConfession']) AND $_POST['EConfession'] != "" ) 
			{ $EConfession = $_POST['EConfession']; } else { $EConfession = ""; }

		if ( isset($_POST['NEnfant']) AND $_POST['NEnfant'] != "" ) 
			{ $NEnfant = $_POST['NEnfant']; } else { $NEnfant = ""; }

		if ( isset($_POST['Status']) ) { $Status = $_POST['Status']; } else { $Status = ""; }
		if ( isset($_POST['Prem_Accueil_id']) AND $_POST['Prem_Accueil_id'] > 0 ) 
			{ $Prem_Accueil_id = $_POST['Prem_Accueil_id']; } else { $Prem_Accueil_id = 0; }
		if ( isset($_POST['Celebrant']) AND $_POST['Celebrant'] > 0 ) 
			{ $Celebrant = $_POST['Celebrant']; } else { $Celebrant = 0; }
		if ( isset($_POST['Autre_Celebrant']) AND $_POST['Autre_Celebrant'] != "" ) 
			{ $Autre_Celebrant = $_POST['Autre_Celebrant']; } else { $Autre_Celebrant = ""; }
		
		if ( isset($_POST['Finance_total']) AND $_POST['Finance_total'] > 0 ) 
			{ $Finance_total = $_POST['Finance_total']; } else { $Finance_total = 0; }
		if ( isset($_POST['Finance_commentaire']) AND $_POST['Finance_commentaire'] != "" ) 
			{ $Finance_commentaire = $_POST['Finance_commentaire']; } else { $Finance_commentaire = ""; }

		if ( isset($_POST['AnneeSession']) AND $_POST['AnneeSession'] != "" ) 
			{ $AnneeSession = $_POST['AnneeSession']; } else { $AnneeSession = ""; }
		if ( isset($_POST['Commentaire']) AND $_POST['Commentaire'] != "" ) 
			{ $Commentaire = $_POST['Commentaire']; } else { $Commentaire = ""; }


		if ($Celebrant > 0) {
			$Autre_Celebrant = "";
		}
			
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Lieu_mariage="'.$LMariage.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		if ($DateTimeValue != NULL) {
			mysqli_query($eCOM_db, 'UPDATE Fiancés SET Date_mariage = "'.$DateTimeValue.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Status="'.$Status.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Prem_Accueil_id='.$Prem_Accueil_id.' WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Celebrant_id='.$Celebrant.' WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Celebrant="'.$Autre_Celebrant.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET LUI_Extrait_Naissance="'.$LActeNaissance.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET LUI_Extrait_Bapteme="'.$LActeBapteme.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET LUI_Lettre_Intention="'.$LLettreIntention.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));

		mysqli_query($eCOM_db, 'UPDATE Fiancés SET ELLE_Extrait_Naissance="'.$EActeNaissance.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET ELLE_Extrait_Bapteme="'.$EActeBapteme.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET ELLE_Lettre_Intention="'.$ELettreIntention.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));

		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Finance_total="'.$Finance_total.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Finance_commentaire="'.$Finance_commentaire.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Session="'.$AnneeSession.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Session="'.$AnneeSession.'" WHERE Activite_id=2 AND Engagement_id='.$id.' ') or die (mysqli_error($eCOM_db));		
		//}
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Enfant="'.$NEnfant.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET Commentaire="'.$Commentaire.'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$id.' ') or die (mysqli_error($eCOM_db));
		
		// Sauvegarde de la confession - LUI
		$requete2 = 'SELECT T1.`id` 
				FROM QuiQuoi T0 
				LEFT JOIN Individu T1 ON T1.`id`=T0.`Individu_id`
				WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=1 AND T0.`Engagement_id`='.$id.' AND T1.`Sex`="M"';
		$result2 = mysqli_query($eCOM_db, $requete2);
		if (mysqli_num_rows($result2) > 0) {
			$row2 = mysqli_fetch_assoc($result2);
			if ($row2['id'] > 0) {
				mysqli_query($eCOM_db, 'UPDATE Individu SET Confession="'.$LConfession.'" WHERE id='.$row2['id'].' ') or die (mysqli_error($eCOM_db));		
			}
		}
		
		// Sauvegarde de la confession - ELLE
		$requete2 = 'SELECT T1.`id` 
				FROM QuiQuoi T0 
				LEFT JOIN Individu T1 ON T1.`id`=T0.`Individu_id`
				WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=1 AND T0.`Engagement_id`='.$id.' AND T1.`Sex`="F"';
		$result2 = mysqli_query($eCOM_db, $requete2);
		if (mysqli_num_rows($result2) > 0) {
			$row2 = mysqli_fetch_assoc($result2);
			if ($row2['id'] > 0) {
				mysqli_query($eCOM_db, 'UPDATE Individu SET Confession="'.$EConfession.'" WHERE id='.$row2['id'].' ') or die (mysqli_error($eCOM_db));
			}
		}
		
		return (0);
		
	} else {
		return (-1);
	}
}


if ( isset( $_POST['edit'] ) AND $_POST['edit']=="Enregistrer") {
//if ($edit) {
	
	$debug = false;
	
	$check_LUI_Acte_Naissance = isset($_POST['LUI_Acte_Naissance']) ? $_POST['LUI_Acte_Naissance'] : "off" ;
	$check_LUI_Acte_Bapteme = isset($_POST['LUI_Acte_Bapteme']) ? $_POST['LUI_Acte_Bapteme'] : "off" ;	
	$check_LUI_Lettre_Intention = isset($_POST['LUI_Lettre_Intention']) ? $_POST['LUI_Lettre_Intention'] : "off" ;	
	$check_ELLE_Acte_Naissance = isset($_POST['ELLE_Acte_Naissance']) ? $_POST['ELLE_Acte_Naissance'] : "off" ;	
	$check_ELLE_Acte_Bapteme = isset($_POST['ELLE_Acte_Bapteme']) ? $_POST['ELLE_Acte_Bapteme'] : "off" ;	
	$check_ELLE_Lettre_Intention = isset($_POST['ELLE_Lettre_Intention']) ? $_POST['ELLE_Lettre_Intention'] : "off" ;	
	
	$retour = Sauvegarder_fiche_fiance ();
	if ($retour == 0) {
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche enregistrée avec succès</FONT></CENTER></B>';
	} else {
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Impossible d\'enregistrer la fiche</FONT></CENTER></B>';
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit();
}

//delete part 1
if ( isset( $_POST['delete_fiche_fiance'] ) AND $_POST['delete_fiche_fiance']=="Détruire la fiche") {
//if ($delete_fiche_fiance) {
	Global $eCOM_db;
	$debug = false;

	$requete = 'SELECT T0.`id`,
				(SELECT Concat(T1.`Prenom`, " ",T1.`Nom`) FROM QuiQuoi T2 
					LEFT JOIN Individu T1 ON T2.`Individu_id`=T1.`id` WHERE T2.`Activite_id`=2 AND T2.`QuoiQuoi_id`=1 AND T1.`Sex`="M" AND T2.`Engagement_id`='.$_POST['id'].') AS LUI_Name, 
				(SELECT Concat(T1.`Prenom`, " ",T1.`Nom`) FROM QuiQuoi T2 
					LEFT JOIN Individu T1 ON T2.`Individu_id`=T1.`id` WHERE T2.`Activite_id`=2 AND T2.`QuoiQuoi_id`=1 AND T1.`Sex`="F" AND T2.`Engagement_id`='.$_POST['id'].') AS ELLE_Name
				FROM Fiancés T0 WHERE T0.`id`='.$_POST['id'].' '; 
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance - id = ".$_POST['id']);
	pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance - Requete = ".$requete);

	$result = mysqli_query($eCOM_db, $requete);
	//debug('Enregistrements dans la table <i>personne</i> [ <FONT COLOR=GREEN>' . mysqli_num_rows( $result ) . '</FONT> ]<BR><BR>');

	while($row = mysqli_fetch_assoc($result))
	{
		fMENU_top();
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Destruction d\'une fiche fiancée</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir détruire cette fiche : <BR><BR>';
		echo $row['ELLE_Name'].' et '.$row['LUI_Name'].' ?</FONT>';
		echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<input type="submit" name="delete_fiche_fiance_confirme" value="Oui">';
		echo '<input type="submit" name="" value="Non">';
		echo '<input type="hidden" name="id" value='.$_POST['id'].'>';
		echo '</FORM></TD></TR>';
		fMENU_bottom();
		mysqli_close($eCOM_db);
		exit();	
	}
}

//delete part 2
if ( isset( $_POST['delete_fiche_fiance_confirme'] ) AND $_POST['delete_fiche_fiance_confirme']=="Oui") {
//if ($delete_fiche_fiance_confirme) {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance_confirme - id=".$id);
	$requete = 'SELECT * FROM Fiancés WHERE id='.$_POST['id'].' '; 
	pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance_confirme - requete01=".$requete);
	$result = mysqli_query($eCOM_db, $requete);
    pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance_confirme - Enreg dans la table ".mysqli_num_rows( $result ));

	//while($row = mysql_fetch_row($result))
	if (mysqli_num_rows( $result ) == 1)
	{ 
        $requete = 'UPDATE Fiancés SET Actif=0 WHERE id='.$_POST['id'].' '; 
		pCOM_DebugAdd($debug, "Mariage:delete_fiche_fiance_confirme - requete02=".$requete);
        $result = mysqli_query($eCOM_db, $requete); 
		if (!$result) {
			echo 'Impossible d\'exécuter la requête : ' . mysqli_error($eCOM_db);
			mysqli_close($eCOM_db);
			exit;
        }
		mysqli_query($eCOM_db, 'UPDATE Fiancés SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_POST['id'].' ') or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche détruite avec succès</FONT></CENTER></B>';
	}
}


///////////////////////////////////////////////////////////////////
// A RETRAVAILLER
////////////////////////////////////////////////////////////////////

if ( isset( $_POST['Selectionner_Paroissien'] ) AND ( 
	$_POST['Selectionner_Paroissien']=="Sélectionner le fiancé" OR 
	$_POST['Selectionner_Paroissien']=="Le fiancé" OR 
	$_POST['Selectionner_Paroissien']=="Sélectionner la fiancée" OR 
	$_POST['Selectionner_Paroissien']=="La fiancée" OR 
	$_POST['Selectionner_Paroissien']=="Accompagnateur(s)" )) {
	
	if  ( $_POST['Selectionner_Paroissien'] == "Accompagnateur(s)" ) {
		$retour = Sauvegarder_fiche_fiance ();
		
		if ($retour == 0) {
			echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche provisoirement enregistrée avec succès</FONT></CENTER></B>';
		} else {
			echo '<B><CENTER><FONT face="verdana" size="2" color=red>Impossible d\'enregistrer provisoirement la fiche</FONT></CENTER></B>';
		}
	}
	$debug = false;
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien -> ".$_POST['Selectionner_Paroissien']);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien id=".$_POST['Fiche_id']);
	
	if ( $_POST['Selectionner_Paroissien']=="Sélectionner le fiancé" OR 
		 $_POST['Selectionner_Paroissien']=="Le fiancé" ) {
		$_SESSION["Action"] = sprintf("%d\n%d\n%s\n%s", 1, $_POST['Fiche_id'], "T1.`Sex`='M'", "Fiancés");
		$return = Selectionner_Paroissien_Afficher("Sélectionner le fiancé", 1, $_POST['id'], "T1.`Sex`='M'", False, "QuiQuoi", $_POST['id'] );
		
	} elseif  ( $_POST['Selectionner_Paroissien']=="Sélectionner la fiancée" OR 
				$_POST['Selectionner_Paroissien']=="La fiancée" ) {
		$_SESSION["Action"] = sprintf("%d\n%d\n%s\n%s", 1, $_POST['Fiche_id'], "T1.`Sex`='F'", "Fiancés");
		$return = Selectionner_Paroissien_Afficher("Sélectionner la fiancée", 1, $_POST['id'], "T1.`Sex`='F'", False, "QuiQuoi", $_POST['id'] );
		
	} elseif  ( $_POST['Selectionner_Paroissien'] == "Accompagnateur(s)" ) {
		$_SESSION["Action"] = sprintf("%d\n%d\n%s\n%s", 2, $_POST['Fiche_id'], "", "Fiancés");
		$return = Selectionner_Paroissien_Afficher("Sélectionner l'accompagnateur", 2, $_POST['id'], "T0.`Activite_id`=2 AND T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=2 AND T0.`Session`=".$SessionEnCours." ", True, "QuiQuoi", $_POST['id'] );
	}

}


Function Selectionner_Paroissien_Afficher($Title, $pQuoiQuoi_id, $Engagement_id, $SqlWhere, $Inscription, $Database, $Champs ) 
{
	Global $eCOM_db;
	fMENU_top();
	
	$debug = False;
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - Title=".$Title);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - pQuoiQuoi_id =".$pQuoiQuoi_id);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - Engagement_id =".$Engagement_id);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - SqlWhere =".$SqlWhere);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - Inscription =".$Inscription);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - Database =".$Database);
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - Champs =".$Champs);

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - pQuoiQuoi_id =".$pQuoiQuoi_id);
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>'.$Title.'</B></FONT><BR></TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	//echo '<FONT FACE="Verdana" size="2" ><BR>';

	$Activite_id=$_SESSION["Activite_id"];

	if ( $Inscription == True ) { // Inscription déjà réalisée, ou liste déjà définie
		$requete = 'SELECT T1.`id` AS id, T1.`Prenom` AS Prenom, T1.`Nom` AS Nom, T0.`Detail` AS Classe, T1.`Actif` 
					FROM `QuiQuoi` T0 
					LEFT JOIN `Individu` T1 ON T0.`Individu_id`=T1.`id` 
					WHERE T1.`Nom`<>"" AND T1.`Nom`<>"Annulé dupliqué" AND T1.`Prenom`<>"" AND T1.`Actif`=1 AND '.$SqlWhere.'
					GROUP BY T1.`id` 
					ORDER BY T1.`Nom`, T1.`Prenom`';
	} else {
		$requete = 'SELECT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Naissance`, T1.`MAJ` 
					FROM Individu T1
					WHERE T1.`Nom`<>"" AND T1.`Nom`<>"Annulé dupliqué" AND T1.`Prenom`<>"" AND T1.`Actif`=1 AND '.$SqlWhere.'
					ORDER BY MAJ DESC, T1.`Nom`, T1.`Prenom` 
					LIMIT 0, 10';
					
		pCOM_DebugAdd($debug, "Mariage:Selectionner_Paroissien_Afficher - requete =".$requete);
		
		echo "<TR><TD colspan=2><FONT face=verdana size=2>Derniers paroissiens modifiés</FONT></TD></TR>";
		echo '<TABLE>';
		$trcolor = "#EEEEEE";
		
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Sélectionner</font></TH>';
		echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom</font></TH>';

		$result = mysqli_query($eCOM_db, $requete);
		while($row = mysqli_fetch_assoc($result)) {
			$trcolor = usecolor();
			echo '<TR>'; 
			echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=DeclarerBaseQuiQuoi&Qui_id='.$row['id'].' TITLE="'.$Title.'"><i class="fa fa-plus-circle"></i></a></TD>  ';
			echo '<TD bgcolor='.$trcolor.'>';
			fCOM_Display_Photo($row['Prenom'], $row['Nom'], $row['id'], "edit_Individu", False);
			echo '</TD></TR>'; 
		}
		echo '<TR><TD colspan=2><FONT face=verdana size=2><BR>Tous les paroissiens</FONT></TD></TR>';
		echo '</TABLE></FONT>';
		$requete = 'SELECT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Naissance` 
					FROM Individu T1
					WHERE T1.`Nom`<>"" AND T1.`Nom`<>"Annulé dupliqué" AND T1.`Prenom`<>"" AND T1.`Actif`=1 AND '.$SqlWhere.'
					ORDER by T1.`Nom`, T1.`Prenom` ';
	}
	
	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Sélectionner</font></TH>';
	echo '<TH bgcolor='.$trcolor.'><font face=verdana size=2>Nom</font></TH>';

	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)) {
		$trcolor = usecolor();
		echo '<TR>'; 
		echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=DeclarerBaseQuiQuoi&Qui_id='.$row['id'].' TITLE="'.$Title.'"><i class="fa fa-plus-circle"></i></a></TD>  ';
		echo '<TD bgcolor='.$trcolor.'>';
		fCOM_Display_Photo($row['Prenom'], $row['Nom'], $row['id'], "edit_Individu", False);
		echo '</TD></TR>';
	}
	echo '</TABLE><BR></FONT>';
	fMENU_bottom();
	exit;
}



if ( isset( $_GET['action'] ) AND $_GET['action']=="DeclarerBaseQuiQuoi") {
//if ($action == "DeclarerBaseQuiQuoi") { 
	Global $eCOM_db;
	list($QuoiQuoi_id, $Engagement_id, $SqlWhere, $Database) = sscanf($_SESSION["Action"], "%d\n%d\n%s\n%s");
	$debug=False;
	$Activite_id = $_SESSION["Activite_id"];
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Action= ".$_SESSION["Action"]);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Activite_id =".$Activite_id);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> QuoiQuoi_id =".$QuoiQuoi_id);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Engagement_id =".$Engagement_id);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> SqlWhere =".$SqlWhere);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Qui_id =".$_GET['Qui_id']);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Database =".$Database);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Session =".$_SESSION["Session"]);
	pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> RetourPageCourante =".$_SESSION["RetourPageCourante"]);
	
	if ($_GET['Qui_id'] > 0 & $Engagement_id > 0) {

		// verifier si la fiche existe déjà
		$requete = 'SELECT T0.`id` 
		FROM QuiQuoi T0
		LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id`
		WHERE T0.`Activite_id`='.$Activite_id.' AND T0.`QuoiQuoi_id`='.$QuoiQuoi_id.' AND T0.`Engagement_id`='.$Engagement_id.' AND '.$SqlWhere.' ';
		pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> requete=".$requete);
		
		$result = mysqli_query($eCOM_db, $requete);
		$num_total = mysqli_num_rows($result);
		if ( $num_total > 0 ) {
			pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Num_Total=".$num_total);
			$row = mysqli_fetch_assoc($result);
			mysqli_query($eCOM_db, 'UPDATE QuiQuoi SET Individu_id='.$_GET['Qui_id'].' WHERE id='.$row['id'].' ') or die (mysqli_error($eCOM_db));
			
		} else {
			pCOM_DebugAdd($debug, "Mariage:Action=DeclarerBaseQuiQuoi -> Num_Total=0 INSERT");
			mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES ('.$_GET['Qui_id'].','.$Activite_id.','.$Engagement_id.','.$QuoiQuoi_id.', "'.$_SESSION["Session"].'")') or die (mysqli_error($eCOM_db));
		}

		mysqli_query($eCOM_db, 'UPDATE Fiancés SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$Engagement_id.' ') or die (mysqli_error($eCOM_db));
	}
	$_SESSION["Action"]="";
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	mysqli_close($eCOM_db);
	exit;
}


/////////////////////////////////////////////////
// FIN DE A RETRAVAILLER
////////////////////////////////////////////////


if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerAccompagnateur") {
//if ($action == "RetirerAccompagnateur") {
	Global $eCOM_db;
	$debug = False;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Mariage:RetirerAccompagnateur - id = ".$_GET['Qui_id']);

	if ($_GET['Qui_id'] > 0 & $_GET['Invite_id'] > 0) {
		$Activite_id=$_SESSION["Activite_id"];
		$requete='DELETE FROM QuiQuoi WHERE Individu_id='.$_GET['Qui_id'].' AND Activite_id='.$Activite_id.' AND Engagement_id='.$_GET['Invite_id'].' AND QuoiQuoi_id=2';
		pCOM_DebugAdd($debug, "Mariage:RetirerAccompagnateur - requete_1 =".$requete);
		mysqli_query($eCOM_db, $requete)or die (mysqli_error($eCOM_db));
		$requete="UPDATE Fiancés SET MAJ='".date("Y-m-d H:i:s")."' WHERE id=".$_GET['Invite_id'];
		pCOM_DebugAdd($debug, "Mariage:RetirerAccompagnateur - requete_2 =".$requete);
		mysqli_query($eCOM_db, $requete)or die (mysqli_error($eCOM_db));
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_POST['upload_Photo_couple'] ) AND ( $_POST['upload_Photo_couple']=="Charger une autre photo..." OR $_POST['upload_Photo_couple']=="Charger une photo...")) {
//if ($upload_Photo_couple) {
	
	Global $eCOM_db;
	echo '<form method="POST" action="upload.php" enctype="multipart/form-data">';
	echo '<FONT color=green><h4>La taille maximum du fichier ne doit pas dépasser 50Ko<BR>';
	echo 'Veuillez ne pas mettre d\'accents ni d\'espace dans le nom de l\'image</font><BR>';
	echo 'Fichier (id='.$_POST['id'].') :  <BR></h4>';
	//<!-- On limite le fichier à 100Ko -->
	echo '<input type="hidden" name="MAX_FILE_SIZE" value="50000">';
	echo '<input type="file" name="avatar">';
	echo '<input type=hidden name=id value='.$_POST['id'].'>';
	echo '<input type=hidden name=fichier_target value='.$_POST['id'].'.jpg>';
	echo '<input type=hidden name=Activite value='.$_SESSION["Activite_id"].'>';
	echo '<input type="submit" name="envoyer" value="Envoyer le fichier"></form>';
	mysqli_close($eCOM_db);
	exit();
}
	
//======================================
// Vue accompagnateur
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="list_accomp") {
//if ($action == "list_accomp") {
	Global $eCOM_db;
	$debug = false;

	fMENU_top();

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Liste Accompagnateurs</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	$trcolor = "#EEEEEE";
	echo '<thead><tr>';
	echo '<TH>Accompagnateurs</TH>';
	echo '<TH>Adresse</TH>';
	echo '<TH>Téléphone</TH>';
	echo '<TH>e-mail</TH>';
	echo '<TH>Fiancés</TH>';
	echo '<TH>Couverts</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
	
	$Total_pers = 0;
	if ($_SESSION["Session"]=="All") {
		$ExtraRequete='';
	} else {
		$ExtraRequete='AND T0.`Session`='.$_SESSION["Session"].'';
	}


	$requete = '(SELECT GROUP_CONCAT(CONVERT(T1.`id`, CHAR(50)) ORDER BY T1.`Sex` DESC SEPARATOR "_") AS id_Accompagnateur, 
CONCAT(GROUP_CONCAT( DISTINCT T1.`Nom`), "<BR>",GROUP_CONCAT(T1.`Prenom` ORDER BY T1.`Sex` DESC SEPARATOR " et ")) AS Accompagnateur, 
T1.`Adresse` as Adresse, 
GROUP_CONCAT(DISTINCT T1.`Telephone` ORDER BY T1.`Sex` DESC SEPARATOR " ") as Telephone, 
GROUP_CONCAT(DISTINCT T1.`e_mail` ORDER BY T1.`Sex` DESC SEPARATOR "; ") as e_mail, 
T0.`Engagement_id`, 
(SELECT CONCAT(GROUP_CONCAT(DISTINCT T3.`Nom` ORDER BY T3.`Sex` DESC SEPARATOR " / " )) FROM QuiQuoi T2 LEFT JOIN Individu T3 ON T2.`Individu_id`=T3.`id` WHERE T2.`Activite_id`=2 AND T2.`QuoiQuoi_id`=1 AND T0.`Engagement_id`=T2.`Engagement_id` ) AS NomFiances
FROM QuiQuoi T0 
LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id` 
WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=2 AND T0.`Engagement_id`<>0 '.$ExtraRequete.'
GROUP BY T0.`Engagement_id`)
UNION
(SELECT GROUP_CONCAT(DISTINCT CONVERT(T1.`id`, CHAR(50)) ORDER BY T1.`Sex` DESC SEPARATOR "_") AS id_Accompagnateur, 
CONCAT(GROUP_CONCAT( DISTINCT T1.`Nom`), "<BR>", GROUP_CONCAT(DISTINCT T1.`Prenom` ORDER BY T1.`Sex` DESC SEPARATOR " et ")) AS Accompagnateur, 
T1.`Adresse` as Adresse, 
GROUP_CONCAT(DISTINCT T1.`Telephone` ORDER BY T1.`Sex` DESC SEPARATOR " ") as Telephone, 
GROUP_CONCAT(DISTINCT T1.`e_mail` ORDER BY T1.`Sex` DESC SEPARATOR "; ") as e_mail, 
T0.`Engagement_id`, 
" " AS NomFiances
FROM QuiQuoi T0 
LEFT JOIN Individu T1 ON T0.`Individu_id`=T1.`id` 
WHERE T0.`Activite_id`=2 AND T0.`QuoiQuoi_id`=2 AND T0.`Engagement_id`=0 '.$ExtraRequete.' AND T1.`Pretre`=0 AND T1.`Diacre`=0
GROUP BY T1.`Nom`)
ORDER BY Accompagnateur';

	
	$nb_personnes=2;
	$Memo_Accompagnateur="";
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){

		if ($Memo_Accompagnateur != $row['Accompagnateur']) {
			if ($Memo_Accompagnateur != "") {
				echo '</TD><TD>';
				if ($nb_personnes > 0) { echo '<FONT face=verdana size=2>'.$nb_personnes.'</FONT>';}
				echo '</TD></TR>';
				$Total_pers = $Total_pers + $nb_personnes;
				$nb_personnes=2;
				$retour_Chariot="";
			}
			$Memo_Accompagnateur = $row['Accompagnateur'];
			echo '<TR><TD>';
			fCOM_Display_Photo($row['Accompagnateur'], "", $row['id_Accompagnateur'], "edit_Individu", False);
			echo '</TD>';
			echo '<TD>'.$row['Adresse'].'</TD>';
			echo '<TD width="70">'.$row['Telephone'].'</TD>';
			echo '<TD width="70">';
			//echo "<A HREF="mailto:.$row[e_mail].?subject= Preparation Mariage" TITLE='Envoyer un mail a $Accompagnateur'>$row[e_mail]</A></td>";
			echo '<A HREF="mailto:'.$row['e_mail'].'?subject= Préparation Mariage : " TITLE="Envoyer un mail a '.$row['Accompagnateur'].'">'.fCOM_format_email_list($row['e_mail'], ';').'</A></TD>';
			echo '<TD width=170><FONT face=verdana size=1>';
		}
		if (!isset ($retour_Chariot)) {$retour_Chariot="";};
		echo "".$retour_Chariot."";
		fCOM_Display_Photo($row['NomFiances'], "", $row['Engagement_id'], "edit", True);
		$retour_Chariot = '<BR>';
		if ($row['Engagement_id'] <> 0) { $nb_personnes = $nb_personnes + 2;}

	}	
	if ($Memo_Accompagnateur != "") {
		echo '</TD><TD>';
		if ($nb_personnes > 0) { echo '<FONT face=verdana size=2>'.$nb_personnes.'</FONT>';}
		echo '</TD></TR>';
		$Total_pers = $Total_pers + $nb_personnes;
		$nb_personnes=2;
	}
	echo '</tbody></table><BR>';
	
	echo '</TD></TR></TABLE><br>';
	echo "<font face=verdana size=2>Prévoir ".$Total_pers." couverts ( ajouter le secrétariat suivant disponibilité).</font>";
	fMENU_bottom();
	exit();
}

//======================================
// Vue Financiere
//======================================
if ( isset( $_GET['action'] ) AND $_GET['action']=="vue_financiere") {
//if ($action == "vue_financiere") {
	Global $eCOM_db;
	$debug = false;

	fMENU_top();
	fMENU_Title("Vue financière ...");
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	echo '<table id="TableauTrier" class="table table-striped table-bordered hover ml-1 mr-1" width="100%" cellspacing="0">';
	echo '<thead><tr>';
	echo '<TH width="10"> </TH>';
	echo '<TH width="100">Participation</TH>';
	echo '<TH>Fiancés</TH>';
	echo '<TH>Date</TH>';
	echo '<TH>Accompagnateurs</TH>';
	echo '</tr></thead>';
	echo '<tbody>';
	if ($_SESSION["Session"]=="All") {
		$ExtraRequete='';
	} else {
		$ExtraRequete='AND T0.`Session`='.$_SESSION["Session"].'';
	}
				
	$requete = 'SELECT T0.`id`, T0.`Date_mariage`, T0.`Finance_total`, T0.`Status`,
				(SELECT CONCAT(GROUP_CONCAT( DISTINCT T2.`Nom`), " ") FROM QuiQuoi T1 LEFT JOIN Individu T2 ON T1.`Individu_id`=T2.`id` WHERE T1.`Activite_id`=2 AND T1.`QuoiQuoi_id`=2 AND T1.`Engagement_id`=T0.`id`) AS Accompagnateurs, 
				(SELECT CONCAT(GROUP_CONCAT(DISTINCT T4.`Nom` ORDER BY T4.`Sex` DESC SEPARATOR " / " )) FROM QuiQuoi T3 LEFT JOIN Individu T4 ON T3.`Individu_id`=T4.`id` WHERE T3.`Activite_id`=2 AND T3.`QuoiQuoi_id`=1 AND T3.`Engagement_id`=T0.`id` ) AS NomFiances
				FROM Fiancés T0
				WHERE T0.`Actif`=1 and T0.`Status` <> "Annulé/Reporté" AND T0.`Status` <> "CANA WE" AND T0.`Status` <> "CANA WE Annulé" '.$ExtraRequete.' 
				ORDER BY Accompagnateurs, T0.`Date_mariage`';
				
			
	pCOM_DebugAdd($debug, "Mariage:RetirerAccompagnateur - requete=".$requete);
	$result = mysqli_query($eCOM_db, $requete);
	$total = 0;
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();

		echo '<TR>';
		$td_click='onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit&id='.$row['id'].'\')"';
		echo '<td></td>';

		if ($row['Finance_total'] > 0) { 
			$fgcolorOk = ' class="table-success"'; // "green"
		} else {
			$fgcolorOk = '';
		}
		echo '<TD align="right" '.$td_click.'>'.$row['Finance_total'].' €</TD>';
		echo '<TD  '.$td_click.$fgcolorOk.'>';
		fCOM_Display_Photo($row['NomFiances'], "", $row['id'], "edit", False);
		echo '</TD>';

		echo '<td '.$td_click.'>';
		echo substr($row['Date_mariage'],0,16);
		echo '</TD>';
		if ( $row['Accompagnateurs'] <> "" ) {
			echo '<TD '.$td_click.'>'.$row['Accompagnateurs'].'</TD>';
		} else {
			echo '<TD '.$td_click.'><I>'.$row['Status'].'<I></TD>';
		}

		$total = $total + $row['Finance_total'];
		echo '</TR>';
	}
	$trcolor = usecolor();
	echo "</tbody></TABLE>";
	
	echo '<div class="alert alert-success">Total = <strong>'.$total.' €</strong></div>';

	fMENU_bottom();
	exit();
}



//view profiles
if ( isset( $_GET['action'] ) AND $_GET['action']=="profile") {
 
	header( 'content-type: text/html; charset=UTF-8' );
	?>
	<html>
	<head>
	</head>
	<body bgcolor="#FFFFFF" link=blue vlink=blue alink=blue>
	<font face="verdana"><center>
	<h3><?php echo $_GET['nom_fiance']."<BR><br>"; ?>
	<table border=1 cellpadding=2 cellspacing=0 bordercolor=#000000 width="95%" bgcolor=eeeeee>
	<tr>
	<td><font face=verdana size=2>email:</td><td><font face=verdana size=2><?php echo $_GET['email']; ?></td></tr>

	</table><br>										
	<font size="2">
	<A HREF="javascript:window.close()"><button type="button" class="btn btn-secondary">Fermer</button></A>
	<?php
	exit();	
	//}
}




//--------------------------------------------------------------------------------------
//print one record by id
//--------------------------------------------------------------------------------------
if ( isset( $_GET['action'] ) AND $_GET['action']=="printid") {
//if ($action == "printid") {
	Global $eCOM_db;
	$result = mysqli_query($eCOM_db, "SELECT * FROM ".$Table." where id = ".$_GET['id']." ");
	while($row = mysqli_fetch_assoc($result))
	{ 
		echo "<FONT face=verdana><h3>".$row['LUI_Nom'].", ".$row['ELLE_Nom']."</h3>";
		echo "<FONT face=verdana size=2><B>Nom:</B>".$row['LUI_Prenom']." ".$row['LUI_Nom'].", ".$row['ELLE_Prenom']." ".$row['ELLE_Nom']."<br>";
		echo "<B>Lieu de Mariage:</B>".$row['Lieu_mariage']."<br>";
		echo "<B>Date de Mariage:</B>".$row['Date_mariage']."<br>";
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
//if ($action == "printall") {
	Global $eCOM_db;
	?><font face=verdana><h3><?php echo "Session ".$_SESSION["Session"]." "; ?></h3><br><?php
	if ($_SESSION["Session"]=="All") {
		$result = mysqli_query($eCOM_db, "SELECT * FROM Fiancés ORDER BY Accompagnateurs");
	} else {
		$result = mysqli_query($eCOM_db, "SELECT * FROM Fiancés where Session = ".$_SESSION["Session"]." ORDER BY Accompagnateurs");
	}
	while($row = mysqli_fetch_assoc($result))
	{ 
	
		echo "<FONT face=verdana size=2>";
		echo "<h3>".$row['LUI_Prenom']." ".$row['LUI_Nom'].", ".$row['ELLE_Prenom']." ".$row['ELLE_Nom']."</h3>";
		echo "<B>Lieu de Mariage:</B>".$row['Lieu_mariage']."<br>";
		echo "<B>Date de Mariage:</B>".$row['Date_mariage']."<br>";
		echo "<B>Célébrant:</B>".$row['Celebrant']."<br>";
		echo "<B>Accompagnateurs:".$row['Accompagnateurs']."</B><br>";
		echo "<B>Téléphone:</B>".$row['Telephone']."<br>";
		echo "<B>Email:</B>".$row['Email']."<br>";
		echo "<B>Adresse:</B>".$row['Adresse']."<br>";
		echo "<B>Enfant:</B>".$row['Enfant']."<br>";
		echo "<B>Commentaire:</B>".$row['Commentaire']."<br><br>";
	}
	echo "<br><br>";
	mysqli_close($eCOM_db);
	exit();
}



if ( isset( $_GET['action'] ) AND $_GET['action']=="trombinoscope") {
	Global $eCOM_db;
	$debug = false;
	fMENU_top();
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Trombinoscope des fiancés de la session '.$_SESSION["Session"].'</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	
	$Activite_id = 2; // Préparation mariage
	$criteria = "T0.`Detail`";
	$ComplementRequete = ' AND MID(T0.`Session`,1,4)="'.$_SESSION["Session"].'" ';
	$criteria='Accompagnateur';
	$order='DESC';
	$extentionWhere='AND (T0.`Session` = '.$_SESSION["Session"].' OR ( T0.`Session` < '.$_SESSION["Session"].' AND Date_mariage >= CURDATE() AND Accompagnateurs <> "Annulé/Reporté" AND Accompagnateurs <> "CANA WE" )) ';
	$SelectAccompagnateur='IF(T0.`Session`<'.$_SESSION["Session"].',Concat("Session ", T0.`Session`),IFNULL((SELECT Concat(GROUP_CONCAT( DISTINCT T6.`Nom`), " ",GROUP_CONCAT(T6.`Prenom` ORDER BY T6.`Sex` DESC SEPARATOR " et ")) FROM QuiQuoi T5 LEFT JOIN Individu T6 ON T5.`Individu_id`=T6.`id` WHERE T5.`Activite_id`=2 AND T5.`QuoiQuoi_id`=2 AND T0.`id`=T5.`Engagement_id` ), T0.`Status`))';
	$SelectAccompagnateur='IF(T0.`Session`<'.$_SESSION["Session"].',Concat("Session ", T0.`Session`),IFNULL((SELECT Concat(GROUP_CONCAT(T6.`Prenom` ORDER BY T6.`Sex` DESC SEPARATOR " et "), " ",GROUP_CONCAT( DISTINCT T6.`Nom`)) FROM QuiQuoi T5 LEFT JOIN Individu T6 ON T5.`Individu_id`=T6.`id` WHERE T5.`Activite_id`=2 AND T5.`QuoiQuoi_id`=2 AND T0.`id`=T5.`Engagement_id` ), T0.`Status`))';

	$requete = 'SELECT T0.`id` AS T0_id, T0.`LUI_Extrait_Naissance`, T0.`LUI_Extrait_Bapteme`, T0.`LUI_Lettre_Intention`, T0.`ELLE_Extrait_Naissance`, T0.`ELLE_Extrait_Bapteme`, T0.`ELLE_Lettre_Intention`, T0.`Lieu_mariage`, T0.`Date_mariage`, T0.`Session` AS Session, IFNULL((SELECT Concat(T4.`Nom`) FROM Individu T4 WHERE T4.`id`= T0.`Celebrant_id`), T0.`Celebrant`) as Celebrant, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Telephone, 
(SELECT T6.`e_mail` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_email, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Confession, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Telephone, 
(SELECT T6.`e_mail` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_email, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Confession, 
'.$SelectAccompagnateur.' As Accompagnateur
FROM Fiancés T0
LEFT JOIN Individu T1 ON T1.`id`=T0.`LUI_id` 
LEFT JOIN Individu T2 ON T2.`id`=T0.`ELLE_id`
WHERE T0.`Actif`=1 '.$extentionWhere.'
ORDER BY '.$criteria.' '.$order.''; 
	echo '<div class="card-block">';
	echo '<div class="row">';
	$resultat = mysqli_query($eCOM_db,  $requete );
	$MemoAccompagnateur= "";
	while( $row = mysqli_fetch_assoc( $resultat )) {
		if ($row['Accompagnateur'] <> "Prépa. Ext. + W" and
			$row['Accompagnateur'] <> "Prépa. Ext." and 
			$row['Accompagnateur'] <> "Autre" and
			$row['Accompagnateur'] <> "Annulé/Reporté" and
			strpos($row['Accompagnateur'], "Session", 0) === False ) {
			if ($MemoAccompagnateur != $row['Accompagnateur']) {
				if ($row['Accompagnateur'] == "" ) {
					$Accompagnateur='Pas d\'accompagnateur';
				} else {
					$Accompagnateur='Couples accompagnés par '.$row['Accompagnateur'];
				}	
				$MemoAccompagnateur = $row['Accompagnateur'];
				echo '</TD></TR><TR><TD>'.$Accompagnateur.'</TD></TR><TR><TD>';
				echo '</div><div class="row">';
			}
			$Nom = $row['ELLE_Prenom']." et ".$row['LUI_Prenom'];
			$HREF= $_SERVER['PHP_SELF'].'?action=edit&id='.$row['T0_id'];
			
			if (file_exists("Photos/".$row['T0_id'].".jpg")) { 
				$Photo = 'Photos/'.$row['T0_id'].'.jpg';		
			} else {
				$Photo = 'Photos/Individu_NULL.jpg';
			}

			echo '<div class="col">';
			echo '<div class="card" style="width:150px">';
			echo '<A href='.$HREF.'><img class="card-img-top" src="'.$Photo.'" alt="Pas de photo"></A>';
			echo '<div class="card-block">';
			echo '<h6 class="card-title">'.$Nom.'</h6>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		
		}
	}
	echo '</div>';
	echo '</div>';
	echo "</TD></TR></TABLE>";
	fMENU_bottom();
	exit;
}






//----------------------------------------------------------------------
// Listing general de la session
//----------------------------------------------------------------------


function personne_line($enregistrement, $pCompteur) {
	$trcolor = usecolor();

	//echo '<!-- PERSONNE -->';

	$OnClick_TD=' onclick="window.location.assign(\''.$_SERVER['PHP_SELF'].'?action=edit&id='.$enregistrement['id'].'\')"';
	
	if (strtotime(date('Y-m-d H:i:s')) >= strtotime($enregistrement['Date_mariage'])) {
		echo '<h6 style="display:none;"></h6><TR id="Filtrer_'.$pCompteur.'" style="display:table-row;">';
	} else {
		echo '<tr>';
	}

	
	$NomFiance = $enregistrement['LUI_Prenom'].' '.$enregistrement['LUI_Nom'].' et<BR>'.$enregistrement['ELLE_Prenom'].' '.$enregistrement['ELLE_Nom'];
	$email=$enregistrement['LUI_email']."<BR>".$enregistrement['ELLE_email'];
	
	//echo "<A HREF=\"javascript:showProfile('$NomFiance','$email')\"><img src=\"images/profile.gif\" border=0 alt='View Profile'></A>";
  
	echo "<TD><A HREF=\"javascript:showProfile('$NomFiance','$email')\"><i class=\"fa fa-envelope-o\"></i></A></TD>";

	if ($_SESSION["Session"]=="All") {
		echo '<TD '.$OnClick_TD.'>'.$enregistrement['Session'].'</TD>';
	} else {
		echo '<TD '.$OnClick_TD.'>'.$enregistrement['Accompagnateur'].'</TD>';
	}

	if ($enregistrement['LUI_Extrait_Naissance'] == '1' && ($enregistrement['LUI_Extrait_Bapteme'] == '1' || $enregistrement['LUI_Confession'] == 'Sans') && $enregistrement['LUI_Lettre_Intention']) 
	{ 
		//$fgcolor = "green"; 
		$fgcolorL = ' class="table-success"';
	} else {
		$fgcolorL = '';
	}
	if ($enregistrement['ELLE_Extrait_Naissance'] == '1' && ($enregistrement['ELLE_Extrait_Bapteme'] == '1' || $enregistrement['ELLE_Confession'] == 'Sans')  && $enregistrement['ELLE_Lettre_Intention']) 
	{ 
		//$fgcolor = "green"; 
		$fgcolorE = ' class="table-success"';
	} else {
		$fgcolorE = '';
	}	
	echo '<td '.$fgcolorL.' '.$OnClick_TD.'>';
	fCOM_Display_Photo($enregistrement['LUI_Nom'], $enregistrement['LUI_Prenom'], $enregistrement['id'], "edit", True);
	echo '</td>';
	echo '<td '.$fgcolorE.' '.$OnClick_TD.'>';
	fCOM_Display_Photo($enregistrement['ELLE_Nom'], $enregistrement['ELLE_Prenom'], $enregistrement['id'], "edit", True);
	echo '</td>';
	
	echo '<TD '.$OnClick_TD.'>'.$enregistrement['LUI_Telephone']." ".$enregistrement['ELLE_Telephone'].'</TD>';

	$type_confession = "-";
	if ($enregistrement['ELLE_Confession'] == "Orthodoxe" || $enregistrement['LUI_Confession'] == "Orthodoxe" || $enregistrement['ELLE_Confession'] == "Protestant" || $enregistrement['LUI_Confession'] == "Protestant") 
	{
		$type_confession = "M";
	} else {
		if ($enregistrement['ELLE_Confession'] == "Musulman" || $enregistrement['LUI_Confession'] == "Musulman" || $enregistrement['ELLE_Confession'] == "Juif" || $enregistrement['LUI_Confession'] == "Juif" || $enregistrement['ELLE_Confession'] == "Bouddhiste" || $enregistrement['LUI_Confession'] == "Bouddhiste" || $enregistrement['ELLE_Confession'] == "Autre" || $enregistrement['LUI_Confession'] == "Autre" || $enregistrement['ELLE_Confession'] == "Sans" || $enregistrement['LUI_Confession'] == "Sans" )
		{
			$type_confession = "D";
		}
	}
	echo '<TD '.$OnClick_TD.'>'.$type_confession.'</TD>';
	echo '<TD '.$OnClick_TD.'>'.$enregistrement['Celebrant'].'</TD>';
	echo '<TD '.$OnClick_TD.'>'.substr($enregistrement['Date_mariage'], 0, 16).'</TD>';
	echo '<TD '.$OnClick_TD.'>'.$enregistrement['Lieu_mariage'].'</TD>';
	echo '</TR>';

	//echo '<!-- /PERSONNE -->';
}


function personne_list ($resultat, $order) {
	global $debug;
	$debug = false;
	fMENU_Title("Liste des couples de fiancés ...");
	require("Login/sqlconf.php");
 
	echo '<table id="TableauTrier" class="table table-striped table-hover table-sm">';
	echo '<thead><tr>';
	echo '<th scope="col"></th>';
	if ($_SESSION["Session"]=="All") {
		echo '<th scope="col">Session</th>';
	} else {
		echo '<th scope="col">Accompagnateur</th>';
	}
	echo '<th scope="col">LUI</th>';
	echo '<th scope="col">ELLE</th>';
	echo '<th width="70">Téléphone</th>';
	echo '<th scope="col">Mixte</th>';
	echo '<th scope="col">Célébrant</th>';
	echo '<th width="120">Date&nbsp&nbsp';
	echo '<input type="checkbox" onclick="FiltrerLine()"> <label for="Filter_old_fich"><FONT SIZE="2"></b></label></th>';
	echo '<th scope="col">Lieu</th>';
	echo '</tr></thead>';
	echo '<tbody>';
	
	global $debug;
	$debug=False;
	pCOM_DebugInit($debug);
	
	$compteur = 0;
	while( $enregistrement = mysqli_fetch_assoc( $resultat ))
	{
		if (strtotime(date('Y-m-d H:i:s')) >= strtotime($enregistrement['Date_mariage'])) {
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

<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin
	function showProfile(nom_fiance, email) {
		var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=400,height=350";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=profile&nom_fiance=" + nom_fiance +"&email=" + email, "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php

}


fMENU_top();

Global $eCOM_db;
$debug = false;
$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

if (isset($_GET['criteria'])) $criteria=$_GET['criteria'];
if (isset($_GET['order'])) $order=$_GET['order'];

if ($_SESSION["Session"]=="All") {
	if (!isset($_GET['criteria'])) $criteria='Session';
	if (!isset($_GET['order'])) $order='DESC';
} else {
	if (!isset($_GET['criteria'])) $criteria='Accompagnateur';
	if (!isset($_GET['order'])) $order='DESC';
}

if ($_SESSION["Session"]=="All")
{
	$extentionWhere='AND Accompagnateurs <> "Annulé/Reporté" AND Accompagnateurs <> "CANA WE"';
	$SelectAccompagnateur='T0.`Session`';
} else {
	$extentionWhere='AND (T0.`Session` = '.$_SESSION["Session"].' OR ( T0.`Session` < '.$_SESSION["Session"].' AND Date_mariage >= CURDATE() AND Accompagnateurs <> "Annulé/Reporté" AND Accompagnateurs <> "CANA WE" )) ';
	$SelectAccompagnateur='IF(T0.`Session`<'.$_SESSION["Session"].',Concat("Session ", T0.`Session`),IFNULL((SELECT Concat(GROUP_CONCAT( DISTINCT T6.`Nom`), " ",GROUP_CONCAT(T6.`Prenom` ORDER BY T6.`Sex` DESC SEPARATOR " et ")) FROM QuiQuoi T5 LEFT JOIN Individu T6 ON T5.`Individu_id`=T6.`id` WHERE T5.`Activite_id`=2 AND T5.`QuoiQuoi_id`=2 AND T0.`id`=T5.`Engagement_id` ), T0.`Status`))';
}
if ($criteria == "Celebrant") {
	$extentionOrder=', T0.`Date_mariage` ASC ';
	
} elseif ($criteria == "Lieu_mariage" ) {
	$extentionOrder=', T0.`Date_mariage` ASC ';
	
} elseif ($criteria == "Lieu_nom" ) {
	$criteria = "Lieu_mariage";
	$extentionOrder=', LUI_Nom ASC ';
	
} else {
	$extentionOrder='';
}

$requete = 'SELECT T0.`id`, T1.`id` As LUI_id, T2.`id` As ELLE_id, T0.`LUI_Extrait_Naissance`, T0.`LUI_Extrait_Bapteme`, T0.`LUI_Lettre_Intention`, T0.`ELLE_Extrait_Naissance`, T0.`ELLE_Extrait_Bapteme`, T0.`ELLE_Lettre_Intention`, T0.`Lieu_mariage`, T0.`Date_mariage`, T0.`Session` AS Session, IFNULL((SELECT Concat(T4.`Prenom`, " ",T4.`Nom`) FROM QuiQuoi T3 LEFT JOIN Individu T4 ON T3.`Individu_id`=T4.`id` WHERE T3.`Activite_id`=2 AND T3.`QuoiQuoi_id`=5 AND T0.`id`=T3.`Engagement_id`), T0.`Celebrant`) as Celebrant, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Telephone, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Confession, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Telephone, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Confession, 
'.$SelectAccompagnateur.' As Accompagnateur
FROM Fiancés T0
LEFT JOIN Individu T1 ON T1.`id`=T0.`LUI_id` 
LEFT JOIN Individu T2 ON T2.`id`=T0.`ELLE_id`
WHERE T0.`Actif`=1 '.$extentionWhere.'
ORDER BY '.$criteria.' '.$order.$extentionOrder.''; 

$requete = 'SELECT T0.`id`, T1.`id` As LUI_idd, T2.`id` As ELLE_idd, T0.`LUI_Extrait_Naissance`, T0.`LUI_Extrait_Bapteme`, T0.`LUI_Lettre_Intention`, T0.`ELLE_Extrait_Naissance`, T0.`ELLE_Extrait_Bapteme`, T0.`ELLE_Lettre_Intention`, T0.`Lieu_mariage`, T0.`Date_mariage`, T0.`Session` AS Session, IFNULL((SELECT Concat(T4.`Prenom`, " ", T4.`Nom`) FROM Individu T4 WHERE T4.`id`= T0.`Celebrant_id`), IF (T0.`Celebrant_id`=-1, "Célébrant Extérieur", T0.`Celebrant`)) as Celebrant, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Telephone, 
(SELECT T6.`e_mail` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_email, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="M" AND T0.`id`=T7.`Engagement_id`) AS LUI_Confession, 
(SELECT T6.`Prenom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Prenom, 
(SELECT T6.`Nom` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Nom, 
(SELECT T6.`Telephone` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Telephone, 
(SELECT T6.`e_mail` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_email, 
(SELECT T6.`Confession` FROM QuiQuoi T7 LEFT JOIN Individu T6 ON T7.`Individu_id`=T6.`id` WHERE T7.`Activite_id`=2 AND T7.`QuoiQuoi_id`=1 AND T6.`Sex`="F" AND T0.`id`=T7.`Engagement_id`) AS ELLE_Confession, 
'.$SelectAccompagnateur.' As Accompagnateur
FROM Fiancés T0
LEFT JOIN Individu T1 ON T1.`id`=T0.`LUI_id` 
LEFT JOIN Individu T2 ON T2.`id`=T0.`ELLE_id`
WHERE T0.`Actif`=1 '.$extentionWhere.'
ORDER BY '.$criteria.' '.$order.$extentionOrder.''; 


$debug=false;
//debug_plus($requete . "<BR>\n");

$resultat = mysqli_query($eCOM_db, $requete);
$NbEnregistrement = mysqli_num_rows($resultat);
pCOM_DebugAdd($debug, "Mariage - Enreg dans la table " .$NbEnregistrement);

pCOM_DebugAdd($debug, 'Mariage - Critère de tri: '.$criteria);
pCOM_DebugAdd($debug, 'Mariage - Critère d\'ordre: '.$order);

if(isset($order) and $order=="ASC"){
$order="DESC";
}else{$order="ASC";}

personne_list($resultat, $order);
fMENU_bottom();
mysqli_close($eCOM_db);
?>

