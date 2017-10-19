<?php
//==================================================================================================
//    Nom du module : Paroissien.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 18/04/2017 : Correction du bug affichage d'un paroissien à partir de index.php fenêtre anniversaire
// 26/04/2016 : Lorsque l'on donne accès à la base de données, vider le mot de passe
// 10/05/2017 : Modification de la gestion des souhaits
// 10/05/2017 : Suppression des $_SERVER['PHP_AUTH_USER']
//==================================================================================================

// Initialiser variable si elle n'existe pas
if( ! isset( $action ) ) $action = ""; 
if( ! isset( $submit_individu ) ) $submit_individu = ""; 
if( ! isset( $edit_individu ) ) $edit_individu = ""; 
if( ! isset( $ajouter_service ) ) $ajouter_service = ""; 
if( ! isset( $NewBouton_service ) ) $NewBouton_service = ""; 
if( ! isset( $delete_service ) ) $delete_service = ""; 
if( ! isset( $ajouter_ressourcement ) ) $ajouter_ressourcement = ""; 
if( ! isset( $delete_ressourcement ) ) $delete_ressourcement = ""; 
if( ! isset( $ajouter_souhait ) ) $ajouter_souhait = ""; 
if( ! isset( $delete_souhait ) ) $delete_souhait = ""; 
if( ! isset( $ajouter_Denier ) ) $ajouter_Denier = ""; 
if( ! isset( $delete_fiche_Paroissien ) ) $delete_fiche_Paroissien = ""; 
if( ! isset( $delete_fiche_Paroissien_confirme ) ) $delete_fiche_Paroissien_confirme = ""; 
if( ! isset( $Selectionner_Ascendant ) ) $Selectionner_Ascendant = ""; 
if( ! isset( $upload_Photo ) ) $upload_Photo = ""; 
if( ! isset( $Database_Acces ) ) $Database_Acces = "";


	$Liste_Confessions = array("Confession ?", "Sans", "Anglican", "Bouddhiste", "Catéchumène", "Catholique", "Juif", "Musulman", "Orthodoxe", "Protestant", "Autre");
	$Liste_LangueMaternelle = array("Langue Maternelle ?", "Allemand", "Anglais", "Arabe", "Chinois", "Espagnol", "Français", "Italien", "Neerlandais", "Polonais", "Portugais", "Russe", "Slovaque", "Autre");
	$Liste_Genre = array(" ", "F", "M");

	
	
// Afficher l'adresse sur une carte GoogleMap
function Display_Photo($Nom, $Prenom, $id, $Font_Size)
{
	if(!isset($Font_Size)) {
		$Font_Size="1";
	}
	
	if (file_exists("Photos/Individu_".$id.".jpg")) { 
		if ($Prenom == "NO LINK"){
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].' class="tooltip"><FONT SIZE="'.$Font_Size.'">'.$Nom.'</FONT>';
			echo '<em><span></span>';
			echo "<img src='Photos/Individu_".$id.".jpg' height='100' border='1' alt='Paroissien_".$id."'>";
			echo '<br><font face=verdana size=1>'.$Nom.'</font>';
			echo '</em></A>';
		} else {
			echo '<A HREF=/SuiviParoissien.php?action=edit_Individu&id='.$id.' class="tooltip"><FONT SIZE="'.$Font_Size.'">'.$Prenom.' '.$Nom.'</FONT>';
			echo '<em><span></span>';
			echo "<img src='Photos/Individu_".$id.".jpg' height='100' border='1' alt='Paroissien_".$id."'>";
			echo '<br><font face=verdana size=1>'.$Prenom.' '.$Nom.'</font>';
			echo '</em></A>';
		}
	} else {
		if ($Prenom == "NO LINK"){
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'>';
			echo '<FONT SIZE="'.$Font_Size.'">' .$Nom. '</FONT> ';
			echo '</A>';
		} else {
			echo '<A HREF=/SuiviParoissien.php?action=edit_Individu&id='.$id.'>';
			echo '<FONT SIZE="'.$Font_Size.'">' .$Prenom. ' ' .$Nom. '</FONT> ';
			echo '</A>';
		}
	}
}

//view profiles
if ( isset( $_GET['action'] ) AND $_GET['action']=="AjouterCeService") {
//if ($action == "AjouterCeService") {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Paroissien:AjouterCeService");
	
	echo '<html>';
	echo '<head></head>';
	echo '<body bgcolor="#FFFFFF" link=blue vlink=blue alink=blue>';
	echo '<font face="verdana"><center>';
	$requete = 'SELECT Concat(Prenom, " ", Nom) as SonNom FROM Individu where id = '.$_GET['Individu_id'].' '; 
	pCOM_DebugAdd($debug, "Paroissien:AjouterCeService- requete=".$requete);
	$result = mysqli_query($eCOM_db, $requete);
	$row = mysqli_fetch_assoc( $result);
	//while( $row3 = mysqli_fetch_assoc( $result )) 
	//{
	//	echo '<option value="'.$row3[id].'">'.$row3[Nom].'</option>';
	//}
	if ($_GET['QuiQuoi_id'] == 0) {
		echo '<FONT SIZE="3">Ajouter un service<BR></FONT>';
	} else {
		echo '<FONT SIZE="3">Modifier le service<BR></FONT>';
	}
	echo '<BR>';
	echo '<FORM method=post id="NewService" action="'.$_SERVER['PHP_SELF'].'">';
	echo '<table border=1 cellpadding=2 cellspacing=0 bordercolor=#000000 width="95%" bgcolor=eeeeee>';
	echo '<TR><TD><FONT SIZE="2"><U>Nom</U> : '.$row['SonNom'].'</FONT></TD></TR>';
	echo '<TR><TD>';
	pCOM_DebugAdd($debug, "Paroissien:AjouterCeService- Individu_id=".$_GET['Individu_id']);
	pCOM_DebugAdd($debug, "Paroissien:AjouterCeService- QuiQuoi_id=".$_GET['QuiQuoi_id']);
	if ($_GET['QuiQuoi_id'] == 0) {
		echo '<FONT SIZE="2"><U>Selectionner un service</U> : ';
		echo '<select name="Activite_id" >';
		echo '<option value=" "> </option>';
		if (fCOM_Get_Autorization(0) >= 50) {
			$requete = 'SELECT * FROM `Activites` WHERE Service=1 ORDER BY `Nom` '; 
		} else {
			$requete = 'SELECT * FROM `Activites` WHERE Service=1 AND id > 1 ORDER BY `Nom` '; 
		}
		$result = mysqli_query($eCOM_db, $requete);
		while( $row3 = mysqli_fetch_assoc( $result )) 
		{
			echo '<option value="'.$row3['id'].'">'.$row3['Nom'].'</option>';
		}
		echo '</select></FONT>';
		$Lieu_id = 0;
		$Responsable = 0;
		$Point_de_contact = 0;
		$WEB_gestionnaire = 0;
		$Essentiel_Fraternite = 0;
		$Essentiel_Adoration = 0;
		$Essentiel_Service = 0;
		$Essentiel_Formation = 0;
		$Essentiel_Mission = 0;

	} else {
		$requete = 'SELECT T1.`Nom`, T0.`Lieu_id`, T0.`Activite_id`, T0.`Responsable`, T0.`Essentiel_Fraternite`, T0.`Essentiel_Adoration`, T0.`Essentiel_Service`, T0.`Essentiel_Formation`, T0.`Essentiel_Mission`, T0.`Point_de_contact`, T0.`WEB_G` 
		FROM `QuiQuoi` T0 
		LEFT JOIN `Activites` T1 ON T0.`Activite_id` = T1.`id`
		WHERE T0.`id` = '.$_GET['QuiQuoi_id'].' '; 
		pCOM_DebugAdd($debug, "Paroissien:AjouterCeService- requete=".$requete);
		$result = mysqli_query($eCOM_db, $requete);
		$row3 = mysqli_fetch_assoc( $result );
		echo '<FONT SIZE="2"><U>Service</U> : '.$row3['Nom'].'</FONT>';
		$Lieu_id = $row3['Lieu_id'];
		$Responsable = $row3['Responsable'];
		$Point_de_contact = $row3['Point_de_contact'];
		$WEB_gestionnaire = $row3['WEB_G'];
		$Essentiel_Fraternite = $row3['Essentiel_Fraternite'];
		$Essentiel_Adoration = $row3['Essentiel_Adoration'];
		$Essentiel_Service = $row3['Essentiel_Service'];
		$Essentiel_Formation = $row3['Essentiel_Formation'];
		$Essentiel_Mission = $row3['Essentiel_Mission'];
		echo '<input type=hidden name=Activite_id value='.$row3['Activite_id'].' >';
	}
	
	if ($Responsable==1) {$Checked_Responsable="checked";} else {$Checked_Responsable="";};
	if ($Point_de_contact==1) {$Checked_Point_de_contact="checked";} else {$Checked_Point_de_contact="";};
	if ($WEB_gestionnaire==1) {$Checked_WEB_gestionnaire="checked";} else {$Checked_WEB_gestionnaire="";};
	if ($Essentiel_Fraternite==1) {$Checked_Fraternite="checked";} else {$Checked_Fraternite="";};
	if ($Essentiel_Adoration==1) {$Checked_Adoration="checked";} else {$Checked_Adoration="";};
	if ($Essentiel_Service==1) {$Checked_Service="checked";} else {$Checked_Service="";};
	if ($Essentiel_Formation==1) {$Checked_Formation="checked";} else {$Checked_Formation="";};
	if ($Essentiel_Mission==1) {$Checked_Mission="checked";} else {$Checked_Mission="";};
	
	echo '</TD></TR>';
	echo '<TR><TD>';
	echo '<FONT SIZE="2">Selectionner un clocher : ';
	echo '<select name="Lieu_id" >';
	echo '<option value="0">Tous les clochers</option>';
	$requete = 'SELECT id, Lieu FROM Lieux where isParoisse = 1 ORDER BY Lieu'; 
	$result = mysqli_query($eCOM_db, $requete);
	while( $row3 = mysqli_fetch_assoc( $result )) 
	{
		$OptionSelect = '';
		if ($row3['id'] == $Lieu_id) {
			$OptionSelect = ' selected="selected"';
		}
		echo '<option value="'.$row3['id'].'"'.$OptionSelect.'>'.$row3['Lieu'].'</option>';
	}
	echo '</select></FONT>';
	echo '</TD></TR>';
	
	
	echo '<TR><TD>';
	echo '<FONT SIZE="2"><U>Rôle</U> :';
	echo '<input type="checkbox" name="Responsable" id="Responsable" value="on" '.$Checked_Responsable.'/> <label for="Responsable"><FONT SIZE="2">Responsable</b></label></FONT> ';
	echo '<input type="checkbox" name="Point_de_contact" id="Point_de_contact" value="on" '.$Checked_Point_de_contact.'/> <label for="Point_de_contact"><FONT SIZE="2">Point de contact</b></label></FONT>';
	if (fCOM_Get_Autorization(0)>= 50) {
		echo '<input type="checkbox" name="WEB_G" id="WEB_Gestionnaire" value="on" '.$Checked_WEB_gestionnaire.'/> <label for="WEB_Gestionnaire"><FONT SIZE="2">Gestionnaire WEB</b></label></FONT>';
	} else {
		if ( $WEB_gestionnaire == 1 ){
			echo '<INPUT TYPE=hidden name="WEB_G" value="on" >';
		} else {
			echo '<INPUT TYPE=hidden name="WEB_G" value="off" >';
		}
	}
	echo '<BR><FONT SIZE="2"><U>Essentiels</U> :<BR>';
	echo '<input type="checkbox" name="Essentiel_Fraternite" id="Essentiel_Fraternite" value="on" '.$Checked_Fraternite.'/> <label for="Essentiel_Fraternite"><FONT SIZE="2">Fraternite</b></label>';
	echo '<input type="checkbox" name="Essentiel_Adoration" id="Essentiel_Adoration" value="on" '.$Checked_Adoration.'/> <label for="Essentiel_Adoration"><FONT SIZE="2">Adoration</b></label>';
	echo '<input type="checkbox" name="Essentiel_Service" id="Essentiel_Service" value="on" '.$Checked_Service.'/> <label for="Essentiel_Service"><FONT SIZE="2">Service</b></label>';
	echo '<input type="checkbox" name="Essentiel_Formation" id="Essentiel_Formation" value="on" '.$Checked_Formation.'/> <label for="Essentiel_Formation"><FONT SIZE="2">Formation</b></label>';
	echo '<input type="checkbox" name="Essentiel_Mission" id="Essentiel_Mission" value="on" '.$Checked_Mission.'/> <label for="Essentiel_Mission"><FONT SIZE="2">Mission</b></label>';
	echo '</TD></TR>';
	
	//echo '<TR><TD>';
	echo '</table><br>';
	
	if ($_GET['QuiQuoi_id'] == 0) {
		$ActionIs="Ajouter ce service";
	}else{
		$ActionIs="Enregistrer";
	}
	echo '<span align="center">';
	echo '<INPUT type="submit" formnovalidate="formnovalidate" name="ajouter_service" value="'.$ActionIs.'">';
	echo '<INPUT TYPE="submit" formnovalidate="formnovalidate" name="delete_service" value="Supprimer ce service">';
	echo '<INPUT TYPE="button" value="Fermer" onClick="parent.close()">';
	echo '</span>';
	echo '<INPUT TYPE=hidden name=Individu_id value='.$_GET['Individu_id'].' >';
	echo '<INPUT TYPE=hidden name=QuiQuoi_id value='.$_GET['QuiQuoi_id'].' >';
	echo '</BR>';
	
	echo '</FORM>';

	mysqli_close($eCOM_db);
	exit();	
}



//edit records
if ( isset( $_GET['action'] ) AND $_GET['action']=="edit_Individu") {
//if ($action == "edit_Individu") {
	Global $eCOM_db;
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	if ($_SESSION["RetourPage"] == "") {
		$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	
	if (date("n") <= 7 )
	{
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}
	
	$debug = False;
	pCOM_DebugInit($debug);
	
	if ( $_GET['id'] == 0 ) {
		// creation d'une nouvelle fiche impossible si pas gestionnaire ou administrateur
		if (fCOM_Get_Autorization( 0 ) >= 30) {
			// avant de créer une nouvelle fiche on essaie d'en trouver une déjà vide
			$id = 0;
			$Requete = 'SELECT id FROM Individu WHERE Nom="" AND Prenom="" AND Telephone="" AND Adresse="" AND Commentaire="" ORDER BY id DESC';
			$result = mysqli_query($eCOM_db, $Requete);
			while( $row = mysqli_fetch_assoc( $result ))	{
				$id = $row['id'];
			}
			if ($id == 0){
				mysqli_query($eCOM_db, 'INSERT INTO Individu (id) VALUES (0)') or die (mysqli_error($eCOM_db));
				$id=mysql_insert_id();
			}
			$_SESSION["RetourPageCourante"]=$_SERVER['PHP_SELF'].'?action=edit_individu&id='.$id;
		} else {
			echo'<META http-equiv="refresh" content="0; URL='.$_SERVER['PHP_SELF'].'">';
			exit;
		}
	} else {
		$id= $_GET['id'];
	}
	
	if ( $id > 0 ) {
		// $requete = 'SELECT * FROM Individu WHERE id=' . $id . ' '; 
		$requete = 'SELECT T1.*, T2.`Nom` AS Nom_Pere, T2.`Prenom` AS Prenom_Pere, T3.`Nom` AS Nom_Mere, T3.`Prenom` AS Prenom_Mere, T4.`Nom` AS Nom_Conjoint, T4.`Prenom` AS Prenom_Conjoint FROM `Individu` T1 LEFT JOIN `Individu` T2 ON T1.`Pere_id`=T2.`id` LEFT JOIN `Individu` T3 ON T1.`Mere_id`=T3.`id` LEFT JOIN `Individu` T4 ON T1.`Conjoint_id`=T4.`id` WHERE T1.`id`='.$id.'';

		pCOM_DebugAdd($debug, "Paroissien:edit_Individu - requete=".$requete);
		$result = mysqli_query($eCOM_db, $requete);
		//while($row = mysqli_fetch_assoc($result))
		$row = mysqli_fetch_assoc($result);
	} 
	
	address_top();
	if (fCOM_Get_Autorization( 0)>= 30) { 
		$BloquerAcces="";
	} else {
		$BloquerAcces="disabled='disabled'";
	}
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Edition: ';
	if ($_GET['id'] == 0) {
		echo 'Nouvelle fiche No '.$row['id'].'</B></FONT></td>';
		$Pere_id = 0;
		$Mere_id = 0;
		$Conjoint_id = 0;
		$Services = 0;
		$Ressourcement = 0;
	} else {
		echo 'Fiche paroissien No '.$row['id'].'</B></FONT></td>'; 
		if (strftime("%d/%m/%y", fCOM_sqlDateToOut($row['MAJ'])) != "01/01/70" ) {
			echo '<TD align="right"><FONT FACE="Verdana" SIZE="1"> (Dernière modification au '.strftime("%d/%m/%Y %T", fCOM_sqlDateToOut($row['MAJ'])).')</td>';
		}
	}
	echo '</TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE" Colspan="2">';
	echo '<CENTER><font face="verdana" size="2">';
	echo '<FORM method=post action="'.$_SERVER['PHP_SELF'].'" >';
	echo '<TABLE border="0" cellpadding="2" cellspacing="0">';
	echo '<TR><TD width="140" bgcolor="#eeeeee"><B><FONT SIZE="3"> </FONT></B></TD></TR>';
	echo '<TR><TD bgcolor="#eeeeee" valign="top"><B><FONT SIZE="2">Nom:</FONT></B></TD><TD bgcolor="#eeeeee">';
	echo '<INPUT type=text name="Prenom" placeholder="Prénom" value ="'.$row['Prenom'].'" size="18" maxlength="40" '.$BloquerAcces.'> ';
	echo '<INPUT type=text name="Nom" placeholder="NOM" value ="'.$row['Nom'].'" size="18" maxlength="40" '.$BloquerAcces.'>';

	// Genre
	
	echo '<BR><FONT SIZE="2">Genre:</FONT>';
	echo '<SELECT name="Sex" '.$BloquerAcces.' >';
	foreach ($Liste_Genre as $Genre){
		if ( $id > 0 ) {
			if ($row['Sex'] == $Genre){
				echo '<option value="'.$Genre.'" selected="selected">'.$Genre.'</option>';
			} else {
				echo '<option value="'.$Genre.'">'.$Genre.'</option>';
			}
		} else {
			echo '<option value="'.$Genre.'">'.$Genre.'</option>';
		}
	}
	echo '</SELECT>';

	// Conjoint
	
	if ( $id > 0 ) {
		echo ' ';
		if ( $BloquerAcces=="" ) {
			echo ' ';
			echo '<div style="display:inline"><input type="submit" name="Selectionner_Ascendant" value="Conjoint">';
			echo '<input type="hidden" name="RetourPage" value="SuiviParoissien">';
			echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
		} else {
			echo ' / ';
		}
		if ( $row['Conjoint_id'] > 0 ) { 
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerConjoint&Qui_id='.$id.' TITLE="Retirer Conjoint"><img src="images/moins.gif" border=0 alt="Retirer Conjoint"></a>  ';
			Display_Photo($row['Nom_Conjoint'], $row['Prenom_Conjoint'], $row['Conjoint_id'], "2");
			echo '<input type="hidden" name="Conjoint_id" value="'.$row['Conjoint_id'].'"></div>'; // indispensable pour l'enregistrement ensuite
		}
	}
	
	// Enfants
	if ( $id > 0 ) {
		$requeteEnfants = 'SELECT T0.id, T0.`Nom`, T0.`Prenom`, T0.`Nom`, T0.`Naissance` FROM `Individu` T0 WHERE T0.`Pere_id`='.$row['id'].' OR T0.`Mere_id`='.$row['id'].' ORDER BY Naissance';
		pCOM_DebugAdd($debug, "Paroissien:edit_Individu - requeteEnfants=".$requeteEnfants);
		$TitreLigne ='<BR><FONT SIZE="2">Enfant(s) : </FONT>';
		$resultListEnfants = mysqli_query($eCOM_db, $requeteEnfants);
		while( $ListEnfants = mysqli_fetch_assoc( $resultListEnfants ))
		{
			echo $TitreLigne;
			$TitreLigne = "";
			//$Prenom=$ListEnfants[Prenom];
			Display_Photo("", $ListEnfants['Prenom'], $ListEnfants['id'], "1");
			if (strftime("%d/%m/%y", fCOM_sqlDateToOut($ListEnfants['Naissance'])) != "01/01/70" ) {
				$birthDate = explode("-", $ListEnfants['Naissance']);
				$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
				//$Prenom= $Prenom." ($Age ans)";
				
				echo '<FONT SIZE="1">('.$Age.' ans) </FONT>';
			} else {
				echo '</A><FONT SIZE="1"> - </FONT>';
			}
		}
		echo '<BR>';
	}
	
	
	// Photo
	
	if ( $id > 0 ) {
		echo "</TD>";
		echo '<TD rowspan="4">';
		if (file_exists("Photos/Individu_" . $row['id'] . ".jpg")) { 
			echo '<IMG SRC="Photos/Individu_' . $row['id'] . '.jpg" HEIGHT=150><BR><BR>';
			if (fCOM_Get_Autorization( 0)>= 30) {
				echo "<div align=center><input type=submit name=upload_Photo value='Charger une autre photo (50k octets max)'>"; }
		} else {
			if (fCOM_Get_Autorization( 0)>= 30) {
				echo "<div align=center><input type=submit name=upload_Photo value='Charger une photo (50k octets max)'>"; }
		}
	}
	echo '</TD></TR>';
	
	// Téléphone

	echo '<TR><TD bgcolor="#eeeeee">';
	echo '<B><FONT SIZE="2">Téléphone:</FONT></B></TD>';
	?>
	<TD bgcolor="#eeeeee"><input type=tel name="Telephone" placeholder="Séparer tous le 2 chiffres avec un point" <?php if ( $id > 0 ) {echo ' value ="'.format_Telephone(Securite_html($row['Telephone']), " ").'"';} ?> size="50" maxlength="50" <?php echo $BloquerAcces;?>>
	<?php
	echo '</TD></TR>';
	
	// email
	echo '<TR><TD bgcolor="#eeeeee"><B><FONT SIZE="2">Email:</FONT></B></TD>';
	?>
	<TD bgcolor="#eeeeee"><input type=text name="e_mail" placeholder="Séparer mail avec ';'" <?php if ( $id > 0 ) {echo ' value ="'.format_email_list(Securite_html($row['e_mail']), ";").'"';} ?> size="60" maxlength="50" <?php echo $BloquerAcces;?>>
	<?php
	echo '</TD></TR>';
	
	// adresse
	echo '<TR><TD bgcolor="#eeeeee"><B><FONT SIZE="2">Adresse:</FONT></B></TD>';
	?>
	<TD bgcolor="#eeeeee"><input type=text name="Adresse" placeholder="<Num + Rue> <Code Postal> <Ville>" <?php if ( $id > 0 ) {echo ' value ="'.Securite_html($row['Adresse']).'"';} ?> size="60" maxlength="70" <?php echo $BloquerAcces;?>>
	<?php
	echo '</TD></TR>';


	// Date de Naissance
	echo '<TR><TD><B><FONT SIZE="2">Né  le:</FONT></B></TD>';
	?>
	<TD></b><input type=text name="Naissance" placeholder="JJ/MM/AAAA" style="width:75px" <?php if ( $id > 0 & $row['Naissance'] != "0000-00-00" ) {echo ' value ="'.date("d/m/Y", strtotime($row['Naissance'])).'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
	<?php
	// calcul de l'age
	if (fCOM_Afficher_Age($row['Naissance']) != -1) {
			echo  '<FONT FACE="Verdana" SIZE="1"> ('.fCOM_Afficher_Age($row['Naissance']).' ans)';
	}
		
	// Langue Maternelle
	echo '<FONT SIZE="2"> Langue Maternelle </FONT>';
	echo '<SELECT name="LangueMaternelle" '.$BloquerAcces.' >';
	foreach ($Liste_LangueMaternelle as $LangueMaternelle){
		if ( $id > 0 ) {
			if ($row['LangueMaternelle'] == $LangueMaternelle){
				echo '<option value="'.$LangueMaternelle.'" selected="selected">'.$LangueMaternelle.'</option>';
			} else {
				echo '<option value="'.$LangueMaternelle.'">'.$LangueMaternelle.'</option>';
			}
		} else {
			echo '<option value="'.$LangueMaternelle.'">'.$LangueMaternelle.'</option>';
		}
	}		
	echo '</SELECT> ';
	echo "</TD></TR>";

	// Pere et Mere
	
	if ( $id > 0 ) { 
		echo "<TR><TD>";
		if ( $BloquerAcces!="" ) {
			echo '<B><FONT SIZE="2">Parents:</FONT></b>';
		}
		echo "</TD><TD>";
		if ( $BloquerAcces=="" ) {
			echo '<div style="display:inline"><input type="submit" name="Selectionner_Ascendant" value="Mère">';
			echo '<input type="hidden" name="Genre" value="F"></div>';
			echo '<input type="hidden" name="RetourPage" value="SuiviParoissien">';
			echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
		}
		if ( $row['Mere_id'] > 0 ) { 
			
			//echo '<FONT SIZE="1">' .$row[Prenom_Mere]. ' ' .$row[Nom_Mere]. '</FONT> ';
			if ( $BloquerAcces=="" ) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendant&Qui=Mere&Individu_id='.$row['id'].' TITLE="Desélectionner Mère"><img src="images/moins.gif" border=0 alt="Retirer Mère"></a>  ';
			}	
			echo '<FONT SIZE="1"> <A HREF='.$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row['Mere_id'].'>' .Securite_html($row['Prenom_Mere']). ' ' .Securite_html($row['Nom_Mere']). '</a>';
			echo '<input type="hidden" name="Mere_id" value="'.$row['Mere_id'].'"></div>'; // indispensable pour l'enregistrement ensuite
		} else {
			echo '<input type="hidden" name="Mere_id" value="0">'; // indispensable pour la sauvegarde
		}
		if ( $BloquerAcces=="" ) {
			echo '</td></tr><td></td><td>';
			echo '<div style="display:inline"><input type="submit" name="Selectionner_Ascendant" value="Père">';
			echo '<input type="hidden" name="Genre" value="M"></div>';
			echo '<input type="hidden" name="RetourPage" value="SuiviParoissien">';
			echo '<input type="hidden" name="Fiche_id" value="'.$id.'"></div>';
		} else {
			echo ' / ';
		}
		if ( $row['Pere_id'] > 0 ) { 
				
			//echo '<FONT SIZE="1">' .$row[Prenom_Pere]. ' ' .$row[Nom_Pere]. '</FONT> ';
			if ( $BloquerAcces=="" ) {
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerAscendant&Qui=Pere&Individu_id='.$row['id'].' TITLE="Desélectionner Père"><img src="images/moins.gif" border=0 alt="Retirer Père"></a>  ';
			}	
			echo '<FONT SIZE="1"> <A HREF='.$_SERVER['PHP_SELF'].'?Session='.$_SESSION["Session"].'&action=edit_Individu&id='.$row['Pere_id'].'>' .Securite_html($row['Prenom_Pere']). ' ' .Securite_html($row['Nom_Pere']). '</a>';
			echo '<input type="hidden" name="Pere_id" value="'.$row['Pere_id'].'"></div>'; // indispensable pour l'enregistrement ensuite
		} else {
			echo '<input type="hidden" name="Pere_id" value="0">'; // indispensable pour la sauvegarde
		}
	} 
	echo '<input type="hidden" name="Conjoint_id" value="'.$row['Conjoint_id'].'"></div>'; // indispensable pour l'enregistrement ensuite
	echo '</TD>';

	// google map localisation
	
	if ( $id > 0 && Securite_html($row['Adresse']) != "") {
		echo '<TD rowspan="10">';
	//if ( $row[Adresse] != "" ) {
	//	retourGMap=Display_google_map($row[Prenom]." ".$row[Nom], $row[Adresse])
	//}
		echo '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.fr/maps?f=q&amp;source=s_q&amp;hl=fr&amp;geocode=&amp;q='.str_replace(' ', '+',Securite_html($row['Adresse'])).'&amp;aq=0&amp;ie=UTF8&amp;hq=&amp;hnear='.str_replace(' ', '+',Securite_html($row['Adresse'])).'&amp;t=m&amp;z=13&amp;iwloc=A&amp;output=embed"></iframe>';
		
		echo '</TD>';
	}
	echo '</TR>';
	
	// Confession
	
	echo '<TR><TD></TD><TD valign="top">';
	echo '<BR><SELECT name="Confession" '.$BloquerAcces.' >';
	foreach ($Liste_Confessions as $Confession){
		if ( $id > 0 ) {
			if ($row['Confession'] == $Confession){
				echo '<option value="'.$Confession.'" selected="selected">'.$Confession.'</option>';
			} else {
				echo '<option value="'.$Confession.'">'.$Confession.'</option>';
			}
		} else {
			echo '<option value="'.$Confession.'">'.$Confession.'</option>';
		}
	}		
	echo '</SELECT><BR>';
	
	//$Checked_Pretre="";
	//$Checked_Diacre="";
	//$Checked_Dead="";
	//$Checked_Actif="";
	pCOM_DebugAdd($debug,"Paroissien:Edit - row['Actif'] :".$row['Actif']);
	pCOM_DebugAdd($debug,"Paroissien:Edit - row['Dead'] :".$row['Dead']);
	if ( $id > 0 ) {
		if ($row['Diacre']==1) {$Checked_Diacre="checked";} else {$Checked_Diacre="";};
		if ($row['Pretre']==1) {$Checked_Pretre="checked";} else {$Checked_Pretre="";};
		if ($row['Dead']==1) {$Checked_Dead="checked";} else {$Checked_Dead="";};
		if ($row['Actif']==1) {$Checked_Actif="checked";} else {$Checked_Actif="";};
	} else {
		$Checked_Actif="checked";
	}
	pCOM_DebugAdd($debug,"Paroissien:Edit - Checked_Actif :".$Checked_Actif);
	pCOM_DebugAdd($debug,"Paroissien:Edit - Checked_Dead :".$Checked_Dead);
	echo '<input type="checkbox" name="Pretre" value="on" '.$Checked_Pretre.' /> <label for="Pretre"><FONT SIZE="2">Prêtre</b></label>&nbsp&nbsp';
	
	echo '<input type="checkbox" name="Diacre" value="on" '.$Checked_Diacre.' /> <label for="Diacre"><FONT SIZE="2">Diacre</b></label><BR>';
	
	echo '<input type="checkbox" name="Actif" value="on" '.$Checked_Actif.' /> <label for="Actif"><FONT SIZE="2">Paroissien(ne)&nbsp.&nbsp.&nbsp.&nbsp</b></label>';
	
	echo '<input type="checkbox" name="Dead" value="on" '.$Checked_Dead.' /> <label for="Dead"><FONT SIZE="2">Décédé(e)</b></label>';
	
	echo '</TD></TR>';
	echo '<TR></TR>';
	echo '<TR><TD></TD><TD>';
	
	?>
	<B><FONT SIZE="2"><br>Baptême</FONT>&nbsp &nbsp</b><input type=text name="Bapteme" placeholder="JJ/MM/AAAA" style="width:75px" <?php if ( $id > 0 & $row['Bapteme'] != "0000-00-00" ) {echo ' value ="'.date("d/m/Y", strtotime($row['Bapteme'])).'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
	<B><FONT SIZE="2">Communion</FONT>&nbsp &nbsp</b><input type=text name="Communion" placeholder="JJ/MM/AAAA" style="width:75px" <?php if ( $id > 0 & $row['Communion'] != "0000-00-00" ) {echo ' value ="'.date("d/m/Y", strtotime($row['Communion'])).'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
	<B><FONT SIZE="2"><br>Profession de Foi</FONT>&nbsp &nbsp</b><input type=text name="ProfessionFoi" placeholder="JJ/MM/AAAA" style="width:75px" <?php if ( $id > 0 & $row['ProfessionFoi'] != "0000-00-00" ) {echo ' value ="'.date("d/m/Y", strtotime($row['ProfessionFoi'])).'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
	<B><FONT SIZE="2">Confirmation</FONT>&nbsp &nbsp</b><input type=text name="Confirmation" placeholder="JJ/MM/AAAA" style="width:75px" <?php if ( $id > 0 & $row['Confirmation'] != "0000-00-00" ) {echo ' value ="'.date("d/m/Y", strtotime($row['Confirmation'])).'"';} ?> size="8" maxlength="10" <?php echo $BloquerAcces;?>>
	<?php
	echo '</TD></TR>';
	echo '<TR><TD>&nbsp</TD></TR>';
	
	// Les Ressourcements ==============================
	
	echo '<TR><TD valign="top"><b><FONT SIZE="2">Ressourcements:</FONT></TD>';
	echo '<TD>';
	if ( $id > 0 ) {
		$Compteur = 0;
		$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Nom` FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id` = T0.`Activite_id`
					WHERE T0.`Individu_id`='.$id.' AND T1.`Formation`=1 AND Session = '.$SessionActuelle.' AND T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=1 
					ORDER BY T1.`Nom` '; 
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2))
		{
			echo '<span align="center"><button name="delete_ressourcement" value="'.$row2['id'].'" type="submit" '.$BloquerAcces.' style="background-color:PaleTurquoise">'.$row2['Nom'].' <img src="images/delete.gif" width=10 height=10 alt="Supprimer ce ressourcement"></button>';
			echo '</span>';	
			$Compteur = $Compteur + 1;
		}
		if ($Compteur > 0) {
			echo '<BR>';
		}
	
		if (fCOM_Get_Autorization( 0)>= 30) {
			echo '<select name="Ressourcements" '.$BloquerAcces.' >';
			echo '<option value=" " hidden>Choisissez puis cliquer sur Ajouter -></option>';
			$requete = 'SELECT * FROM `Activites` WHERE Formation=1 ORDER BY `Nom` '; 
			$result = mysqli_query($eCOM_db, $requete);
			while( $row3 = mysqli_fetch_assoc( $result )) 
			{
				echo '<option value="'.$row3['id'].'">'.$row3['Nom'].'</option>';
			}
			echo '</select>';
			echo '<span align="center"><input type="submit" formnovalidate="formnovalidate" name="ajouter_ressourcement" value="Ajouter Ressourcement">';
			//echo '<input type=hidden name=Individu_id value='.$id.' >';
			echo '</br>';
		}
		
		$requete = 'SELECT DISTINCT CONCAT(T1.`Nom`," [",T0.`Session`,"]") AS list_Ressourcements
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Formation`=1 AND T0.`Individu_id`='.$id.' AND Session >('.$SessionActuelle.'-10) AND Session < '.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1
					ORDER BY T1.`Nom`, T0.`Session` DESC '; 
		debug_plus('Requete = '.$requete);
		$result = mysqli_query($eCOM_db, $requete);
		$count_Nb_Services=mysqli_num_rows($result);
		if ($count_Nb_Services > 0) {
			//echo '<FONT SIZE="1"> Historique des ressourcements : </FONT>';
			echo '<select name="HistoRessource" '.$BloquerAcces.' >';
			echo '<option value="0">Historique des ressourcements</option>';
			$counter=1;
			$result = mysqli_query($eCOM_db, $requete);
			while( $row3 = mysqli_fetch_assoc( $result )) 
			{
				echo '<option value="'.$counter.'">'.$row3['list_Ressourcements'].'</option>';
				$counter =$counter + 1;
			}
			echo '</select>';
		}


	}
	echo '</TD><TD></TD></TR>';
	echo '<TR><TD>&nbsp</TD></TR>';
	
	// Les SERVICES ==============================
	
	echo '<TR><TD valign="top"><b><FONT SIZE="2">Services:</FONT></TD><TD>';
	
	if ( $id > 0 ) {
		// Liste des activités du paroissien
		$AddWhere = "AND T1.`id` > 1 ";
		if (fCOM_Get_Autorization( 0)>= 90) {
			$AddWhere = "";
		}

		$Compteur = 0;
		$requete2 = 'SELECT DISTINCT T0.`id` as QuiQuoi_id, T0.`Lieu_id` as Lieu_id, T1.`id`, T1.`Nom`, T2.`Lieu` as Clocher
					FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id` = T0.`Activite_id`
					LEFT JOIN `Lieux` T2 ON T2.`id` = T0.`Lieu_id`
					WHERE T0.`Individu_id`='.$id.' AND Session = '.$SessionActuelle.' AND T0.`Engagement_id`=0 AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10)) '.$AddWhere.'
					ORDER BY T1.`Nom` '; 
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2))
		{
			if ($row2['Lieu_id'] > 0) {
				$Clocher = ' '.$row2['Clocher'];
			} else {
				$Clocher = '';
			}
			$Compteur = $Compteur + 1;
			echo '<span align="center">';
			echo "<A HREF=\"javascript:AjouterService('".$id."', '".$row2['QuiQuoi_id']."')\">";
			echo '<button type="button" '.$BloquerAcces.' style="background-color:SandyBrown">'.$row2['Nom'].$Clocher.' <img src="images/profile.gif" width=10 height=10 alt="Modifier ce service"></button>';
			echo "</A>";
			echo '</span>';
			if ($Compteur % 3 == 0) {
				echo '<BR>';
			}
		}
		if ($Compteur > 0) {
			echo '<BR>';
		}
	
		// Liste des autres activités disponibles
		if (fCOM_Get_Autorization( 0)>= 30) {

			echo '<span align="center">';
			echo "<A HREF=\"javascript:AjouterService('".$id."', '0')\">";
			echo '<button type="button" '.$BloquerAcces.' >Ajouter Service</button>';
			echo "</A>";
			echo '</span>';
			if (fCOM_Get_Autorization( 0)>= 90) {
				echo '<span align="center"><input type="submit" formnovalidate="formnovalidate" name="NewBouton_service" value="En test ne pas utiliser"></span>';
			}
			//echo '<input type=hidden name=Individu_id value='.$_GET['id'].' >';
			//echo '</br>';
		}
		
		// afficher historique des services 
		$requete = 'SELECT DISTINCT CONCAT(T1.`Nom`, " [", T0.`Session`, "]") AS list_Services FROM `QuiQuoi` T0 LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id` WHERE T1.`Service`=1 AND T0.`Individu_id`='.$id.' AND Session > ('.$SessionActuelle.'-10) AND Session < '.$SessionActuelle.' AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10)) ORDER BY T1.`Nom`, T0.`Session` DESC '; 
		//$result = mysqli_query($eCOM_db, $requete);
		echo '<SELECT name="HistoServices" '.$BloquerAcces.' >';
		echo '<option value="0">Historique des services</option>';
		$counter = 1;
		$result = mysqli_query($eCOM_db, $requete);
		while( $row3 = mysqli_fetch_assoc( $result )) {
			echo '<option value="'.$counter.'">'.$row3['list_Services'].'</option>';
			$counter = $counter + 1;
		}
		echo '</SELECT>';
	}
	
	// Autorisation à se connecter à la base de donnée
	if (fCOM_Get_Autorization( 0)>= 50) {
		echo '<BR><FONT SIZE="2"> Base de données :</FONT>';
		$requete_login='SELECT * FROM Admin_membres WHERE Individu_id='.$id.' AND droit_acces=1';
		$result_login=mysqli_query($eCOM_db, $requete_login);
		$count=mysqli_num_rows($result_login);
		if ( $count > 0) {
			// le paroissien a le droit de se connecter à la base
			echo '<span align="center"><button name="Database_Acces" value="0" type="submit" style="background-color:OrangeRed" title="Interdire accès à la base de données">Retirer Accès</button>'; //PaleGreen
			echo '</span>';
		} else {
			echo '<span align="center"><button name="Database_Acces" value="1" type="submit" style="background-color:Gainsboro" title="Autoriser accès à la base de données">Donner Accès</button>';
			echo '</span>';
		}
	}
	
	echo '</TD><TD></TD></TR>';
	echo '<TR><TD>&nbsp</TD></TR>';
	
	// Les Souhaits ==============================
	
	echo '<TR><TD valign="top"><b><FONT SIZE="2">Souhaits:</FONT></TD>';
	echo '<TD>';
	if ( $id > 0 ) {
		$Compteur = 0;
		$requete2 = 'SELECT DISTINCT T1.`id`, T1.`Nom` FROM `QuiQuoi` T0
					LEFT JOIN `Activites` T1 ON T1.`id` = T0.`Activite_id`
					WHERE T0.`Individu_id`='.$id.' AND T1.`Souhait`=1 AND Session = '.$SessionActuelle.' AND T0.`Engagement_id`=0 AND T0.`QuoiQuoi_id`=1 
					ORDER BY T1.`Nom` '; 
		$result2 = mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2))
		{
			echo '<span align="center"><button name="delete_souhait" value="'.$row2['id'].'" type="submit" '.$BloquerAcces.' style="background-color:PaleTurquoise">'.$row2['Nom'].' <img src="images/delete.gif" width=10 height=10 alt="Supprimer ce souhait"></button>';
			echo '</span>';	
			$Compteur = $Compteur + 1;
		}
		if ($Compteur > 0) {
			echo '<BR>';
		}
	
		if (fCOM_Get_Autorization( 0)>= 30) {
			echo '<select name="Souhaits" '.$BloquerAcces.' >';
			echo '<option value=" " hidden>Choisissez puis cliquer sur Ajouter -></option>';
			$requete = 'SELECT * FROM `Activites` WHERE Souhait=1 ORDER BY `Nom` '; 
			$result = mysqli_query($eCOM_db, $requete);
			while( $row3 = mysqli_fetch_assoc( $result )) 
			{
				echo '<option value="'.$row3['id'].'">'.$row3['Nom'].'</option>';
			}
			echo '</select>';
			echo '<span align="center"><input type="submit" formnovalidate="formnovalidate" name="ajouter_souhait" value="Ajouter Souhait">';
			echo '</br>';
		}
		
		$requete = 'SELECT DISTINCT CONCAT(T1.`Nom`," [",T0.`Session`,"]") AS list_Souhaits
					FROM `QuiQuoi` T0
					LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
					WHERE T1.`Souhait`=1 AND T0.`Individu_id`='.$id.' AND Session >('.$SessionActuelle.'-10) AND Session < '.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1
					ORDER BY T1.`Nom`, T0.`Session` DESC '; 
		debug_plus('Requete = '.$requete);
		$result = mysqli_query($eCOM_db, $requete);
		$count_Nb_Services=mysqli_num_rows($result);
		if ($count_Nb_Services > 0) {
			//echo '<FONT SIZE="1"> Historique des souhaits : </FONT>';
			echo '<select name="HistoSouhaits" '.$BloquerAcces.' >';
			echo '<option value="0">Historique des souhaits</option>';
			$counter=1;
			$result = mysqli_query($eCOM_db, $requete);
			while( $row3 = mysqli_fetch_assoc( $result )) 
			{
				echo '<option value="'.$counter.'">'.$row3['list_Souhaits'].'</option>';
				$counter =$counter + 1;
			}
			echo '</select>';
		}

	}
	echo '</TD><TD></TD></TR>';
	echo '<TR><TD>&nbsp</TD></TR>';
	
	
	// Denier du culte
	
	if (fCOM_Get_Autorization( 0)>= 40) {
		echo '<TR><TD valign="top"><B><FONT SIZE="2">Denier de l\'église:</FONT></TD>';
		echo '<TD>';
		if ( $id > 0 ) {
			$Compteur_Denier = 0;
			$requete = 'SELECT * FROM `Denier` WHERE Paroissien_id='.$id.' ORDER BY `Date` '; 
			$result = mysqli_query($eCOM_db, $requete);
			while( $row3 = mysqli_fetch_assoc( $result )) 
			{
				$Compteur_Denier = $Compteur_Denier + 1;
				echo '<FONT SIZE="2">['.$row3['Montant'].' €</FONT><FONT Size="1"> le '.date("d/m/Y", strtotime($row3['Date'])).'</FONT>';
				echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=RetirerDenier&id='.$row3['id'].' TITLE="Retirer ce denier"><img src="images/delete.gif" width=10 height=10border=0 alt="Retirer Denier"></a>  ';
				echo '<FONT SIZE="2">] </FONT>';
				if ( $Compteur_Denier % 3 == 0 ) {
					echo '<BR>';
				}
			}
			echo '</FONT>';
			if ($Compteur_Denier > 0) {
				echo '<BR>';
			}
	
			echo '<FONT SIZE="2">';
			echo 'Montant <input type=text name="Montant_denier" placeholder="en euros" size="12" maxlength="12"> ';
			echo 'Date <input type=text name="Date_denier" placeholder="JJ/MM/AAAA" size="10" maxlength="10">';
			echo '</font>';
			echo '<span align="center"><input type="submit" formnovalidate="formnovalidate" name="ajouter_Denier" value="Ajouter Denier">';
			echo '</BR>';
		}
		echo '</TD><TD></TR><TR></TR>';
	}
	
	// Commentaire
	echo '<TR><TD colspan="3" bgcolor="#eeeeee">';
	echo '<B><FONT SIZE="2">Commentaires:</FONT></B>';
	echo '<BR>';
	if ( $id > 0 ) {
		echo '<textarea cols=70 rows=5 name="Commentaire" maxlength="350" value ="'.Securite_html($row['Commentaire']).'">'.Securite_html($row['Commentaire']).'</textarea>';
	}

	echo '<input type=hidden name=RetourPage value=SuiviParoissien>';
	//echo '<input type=hidden name=id_Bapteme value=0>';
	echo '</TD></TR>';
	echo '<TR><TD></TD><TD>';
 
	if ( $id > 0 ) {
		echo '<input type=hidden name=Individu_id value='.$id.'>';
		echo '<br><div align="center"><input type="submit" formnovalidate="formnovalidate" name="edit_individu" value="Enregistrer">';
	} else {
		echo '<br><div align="center"><input type="submit" formnovalidate="formnovalidate" name="submit_individu" value="Enregistrer">';
	}
	echo '<input type="reset" name="Reset" value="Reset">';
	if (fCOM_Get_Autorization( 0)>= 50) {
		echo '<input type="submit" name="delete_fiche_Paroissien" value="Détruire la fiche">';
		echo '<input type="hidden" name="id" value="'.$id.'">';
	}
	echo '</TD></TR></TABLE>';
	echo '</FORM>';
	echo '</CENTER>';

	fCOM_address_bottom();
	exit; 
}


//---------------------------------------------------
// Gestion accès à la base de données de la paroisse
//---------------------------------------------------

if ( isset( $_POST['Database_Acces'] ) AND ($_POST['Database_Acces']=="0" OR $_POST['Database_Acces']=="1" )) {
	Global $eCOM_db;
	$debug = True;
	//pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Paroissien:Database_Acces=".$_POST['Database_Acces']."");
	
	if (fCOM_Get_Autorization( 0)>= 90) {
		pCOM_DebugAdd($debug, "Paroissien:Database_Acces - administrateur, sauvegarde des data");
		
		$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
		$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
		$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
		$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
		pCOM_DebugAdd($debug, "Paroissien:Database_Acces.Enregistrer - Actif=".$check_Actif);
		pCOM_DebugAdd($debug, "Paroissien:Database_Acces.Enregistrer - Dead=".$check_Dead);

		Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);
		
		$requete='SELECT * FROM Admin_membres WHERE Individu_id='.$_POST['Individu_id'].'';
		pCOM_DebugAdd($debug, "Paroissien:Database_Acces requete=".$requete."");
		$result=mysqli_query($eCOM_db, $requete);
		$count=mysqli_num_rows($result);
		pCOM_DebugAdd($debug, "Paroissien:Database_Acces count=".$count."");
		$mynaissance = substr(fCOM_getSqlDate($_POST['Naissance'],0,0,0), 0, 10);

		if ($count > 0) {
			pCOM_DebugAdd($debug, "Paroissien:Database_Acces.La fiche existe déjà");
			$result=mysqli_query($eCOM_db, 'UPDATE Admin_membres SET droit_acces='.(int)$_POST['Database_Acces'].', Naissance="'.$mynaissance.'", password="" WHERE Individu_id='.$_POST['Individu_id'].'') or die("Paroissien-Database_Acces: Erreur d'ajout d'accès à la base de données: ".mysqli_error($eCOM_db)."");		
		} else {
			pCOM_DebugAdd($debug, "Paroissien:Database_Acces. La fiche n'existe pas, il faut en créer une");
			// 13/04/2017, récupérer la deuxième adresse mail, s'il y en a deux
			$email = trim($_POST['e_mail']).";";
			$tab_email = explode(";",$email);
			$email="";
			$i = 0;
			while (strlen($tab_email[$i]) > 0) {
				$email = trim($tab_email[$i]);
				$i = $i+1;
			}
			$result=mysqli_query($eCOM_db, 'INSERT INTO Admin_membres (Individu_id, username, Naissance, droit_acces) VALUES ('.$_POST['Individu_id'].', "'.$email.'", "'.$mynaissance.'", '.(int)$_POST['Database_Acces'].')') or die("Paroissien-Database_Acces: Erreur de modification d'accès à la base de données: ".mysqli_error($eCOM_db)."");		}
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


//adds records to table
if (isset( $_POST['submit_individu'] ) AND $_POST['submit_individu']=="Enregistrer" ) {
//if ($submit_individu) {
	$debug=false;
	$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
	$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
	$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
	$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
	pCOM_DebugAdd($debug, "Paroissien:submit_individu.Enregistrer - Actif=".$check_Actif);
	pCOM_DebugAdd($debug, "Paroissien:submit_individu.Enregistrer - Dead=".$check_Dead);

	Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);
		
	echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche paroissien enregistrée avec succès</FONT></CENTER></B>';

	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit;
}

// Afficher l'adresse sur une carte GoogleMap
function Display_google_map($Nom, $Adresse) {


}


//updates table in DB
if ( isset( $_POST['edit_individu'] ) AND $_POST['edit_individu']=="Enregistrer") {
//if ($edit_individu) {
	
	$debug = True;
	pCOM_DebugInit($debug);

	//debug($Sex . "<BR>\n");
	pCOM_DebugAdd("Paroissien:edit_individu.Enregistrer - Mere id = " .$_POST['Mere_id']. "<BR>\n");
	//debug($Celebrant . "<BR>\n");
	//debug('Pretre ='.$_POST["Pretre"].'<BR>\n');
	
	if (fCOM_Get_Autorization( 0) >= 30) {
		
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Actif=".$_POST['checkbox_actif']);
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Actif=".$_POST['Actif']);
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Dead=".$_POST['checkbox_dead']);
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Dead=".$_POST['Dead']);

		$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
		$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
		$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
		$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Actif=".$check_Actif);
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Dead=".$check_Dead);

		Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);

		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche paroissien enregistrée avec succès</FONT></CENTER></B>';
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit;

}


function sauvegarder_avant() {
	$debug = True;
	pCOM_DebugAdd($debug, "Paroissien.php:sauvegarde_avant : Individu_id=".$_POST['Individu_id']." Nom=".$_POST['Nom']);
	
	$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
	$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
	$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
	$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
	pCOM_DebugAdd($debug, "Paroissien:sauvegarder_avant.Enregistrer - Actif=".$check_Actif);
	pCOM_DebugAdd($debug, "Paroissien:sauvegarder_avant.Enregistrer - Dead=".$check_Dead);

	Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);

}


function Paroissien_sauvegarder_Fiche ($Individu_id, $Nom, $Prenom, $Sex, $Diacre, $Pretre, $Dead, $Actif, $Naissance, $LangueMaternelle, $e_mail, $Telephone, $Adresse, $Pere_id, $Mere_id, $Conjoint_id, $Confession, $Bapteme, $Communion, $ProfessionFoi, $Confirmation, $Commentaire) {
	Global $eCOM_db;
	$debug = False;

	$requeteTest = 'SELECT id, Nom, Prenom FROM `Individu` WHERE Nom COLLATE latin1_swedish_ci LIKE "%'.$Nom.'%" AND Prenom COLLATE latin1_swedish_ci LIKE "%'.$Prenom.'%" AND Sex = "'.$Sex.'" AND Naissance = '.fCOM_getSqlDate($Naissance,0,0,0).'';
	
	//pCOM_DebugAdd($debug, "Paroissien:Paroissien_sauvegarder_Fiche - requeteTest=".$requeteTest." -> Individu_id=".$Individu_id);
	pCOM_DebugAdd($debug, "Paroissien.php:Paroissien_sauvegarder_Fiche:Actif=".$Actif);
	pCOM_DebugAdd($debug, "Paroissien.php:Paroissien_sauvegarder_Fiche:Diacre=".$Diacre);
	pCOM_DebugAdd($debug, "Paroissien.php:Paroissien_sauvegarder_Fiche:Pretre=".$Pretre);
	pCOM_DebugAdd($debug, "Paroissien.php:Paroissien_sauvegarder_Fiche:Dead=".$Dead);
	$resultTest = mysqli_query($eCOM_db, $requeteTest);
	$ContinuerSauvegarde = True;
	while ( $Listid = mysqli_fetch_assoc($resultTest)) {
		if ( $Listid['id'] != $Individu_id ){
			$ContinuerSauvegarde = False;
			$MemoId=$Listid['id'];
		}
	}
	
	pCOM_DebugAdd($debug, "Paroissien:Paroissien_sauvegarder_Fiche - ContinuerSauvegarde=".$ContinuerSauvegarde);

	if ($ContinuerSauvegarde == True) {
		$Naissance = substr(fCOM_getSqlDate($Naissance,0,0,0), 0, 10);
		$Bapteme = substr(fCOM_getSqlDate($Bapteme,0,0,0), 0, 10);
		$Communion = substr(fCOM_getSqlDate($Communion,0,0,0), 0, 10);
		$debug=true;
		$ProfessionFoi = substr(fCOM_getSqlDate($ProfessionFoi,0,0,0), 0, 10);
		$debug = false;
		$Confirmation = substr(fCOM_getSqlDate($Confirmation,0,0,0), 0, 10);
		
		$Nom = Securite_bdd($Nom);
		$Prenom = ucwords(strtolower($Prenom));
		if (strpos($Prenom, "-")===false) {
		} else {
			$pos=strpos($Prenom, "-");
			$Prenom = substr($Prenom, 0, $pos)."-".ucwords(substr($Prenom, $pos+1));
		}
		
		$Prenom = Securite_bdd($Prenom);
		$Sex = Securite_bdd($Sex);
		$Diacre = Securite_bdd($Diacre);
		$Pretre = Securite_bdd($Pretre);
		$Dead = Securite_bdd($Dead);
		$Actif = Securite_bdd($Actif);
		$LangueMaternelle = Securite_bdd($LangueMaternelle);
		$e_mail = format_email_list($e_mail, ";");
		$Telephone = Securite_bdd(format_Telephone($Telephone, " "), " ");
		$Adresse = Securite_bdd($Adresse);
		$Pere_id = Securite_bdd($Pere_id);
		$Mere_id = Securite_bdd($Mere_id);
		$Conjoint_id = Securite_bdd($Conjoint_id);
		$Confession = Securite_bdd($Confession);
		$Commentaire = Securite_bdd($Commentaire);
		
		if(isset($Diacre) AND $Diacre=="on"){	$SetDiacre = 1;} else {$SetDiacre = 0;}
		if(isset($Pretre) AND $Pretre=="on"){	$SetPretre = 1;} else {$SetPretre = 0;}
		if(isset($Dead) AND $Dead=="on"){	$SetDead = 1;} else {$SetDead = 0;}
		if(isset($Actif) AND $Actif=="on"){	$SetActif = 1;} else {$SetActif = 0;}
		
		pCOM_DebugAdd($debug, "UPDATE Individu SET Nom='".$Nom."' WHERE id=".$Individu_id." ");
		
		mysqli_query($eCOM_db, 'UPDATE Individu SET Nom="'.$Nom.'" WHERE id='.$Individu_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Prenom='".$Prenom."' WHERE id = ".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Sex='".$Sex."' WHERE id = ".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Individu SET Diacre='.$SetDiacre.' WHERE id ='.$Individu_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Individu SET Pretre='.$SetPretre.' WHERE id ='.$Individu_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Individu SET Dead='.$SetDead.' WHERE id ='.$Individu_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Individu SET Actif='.$SetActif.' WHERE id ='.$Individu_id.' ') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Naissance='".$Naissance."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET LangueMaternelle='".$LangueMaternelle."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET e_mail='".$e_mail."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Telephone='".$Telephone."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Adresse='".$Adresse."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Pere_id='".$Pere_id."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Mere_id='".$Mere_id."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Conjoint_id='".$Conjoint_id."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Confession='".$Confession."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		if ($Bapteme != NULL) {
			mysqli_query($eCOM_db, "UPDATE Individu SET Bapteme='".$Bapteme."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		}
		if ($Communion != NULL) {
			mysqli_query($eCOM_db, "UPDATE Individu SET Communion='".$Communion."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		}
		if ($ProfessionFoi != NULL) {
			mysqli_query($eCOM_db, "UPDATE Individu SET ProfessionFoi='".$ProfessionFoi."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		}
		if ($Confirmation != NULL) {
			mysqli_query($eCOM_db, "UPDATE Individu SET Confirmation='".$Confirmation."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		}
		mysqli_query($eCOM_db, "UPDATE Individu SET Commentaire='".$Commentaire."' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		
		mysqli_query($eCOM_db, 'UPDATE Individu SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$Individu_id.' ') or die (mysqli_error($eCOM_db));

	} else {
		mysqli_query($eCOM_db, "UPDATE Individu SET Nom=".$Nom.".' dupliqué' WHERE id=".$Individu_id." ") or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, "UPDATE Individu SET Prenom=".$Prenom.".' dupliqué' WHERE id = ".$Individu_id." ") or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=red>Impossible de créer cette nouvelle fiche, le(la) <A URL=/SuiviParoissien.php?action=edit_Individu&id='.$MemoId.'>paroissien(ne)</A> existe déjà dans la base</FONT></CENTER></B>';
		//echo '<META http-equiv="refresh" content="2; URL=https://'.$_SERVER['SERVER_NAME'].'/SuiviParoissien.php?action=edit_Individu&id='.$MemoId.'">';
		exit;
	}
	//echo '<script language=javascript>window.history.go(-2);</script>';
	//echo '<body onload="document.refresh();">';
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerConjoint") {
//if ($action == "RetirerConjoint") {
	Global $eCOM_db;
	$debug=False;
	$requete='UPDATE Individu SET Conjoint_id=0 WHERE id='.$_GET['Qui_id'].' ';
	mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerAscendant") {
//if ($action == "RetirerAscendant") {
	Global $eCOM_db;
	$debug=False;
	//Debug_plus("Individu_id=".$Individu_id." ");
	//mysqli_query($eCOM_db, 'UPDATE Individu SET '.$Qui.'_id="0" WHERE id='.$Paroissien_id.' ') or die (mysqli_error($eCOM_db));
	if (strcmp($_GET['Qui'], 'Mere')==0) {
		$requete='UPDATE Individu SET Mere_id=0 WHERE id='.$_GET['Individu_id'].' ';
	} elseif (strcmp($_GET['Qui'], 'Pere')==0) {
		$requete='UPDATE Individu SET Pere_id=0 WHERE id='.$_GET['Individu_id'].' ';
	} else {
		Debug_plus("Condition Else Qui=".$_GET['Qui']." ");
	}
	//Debug_plus("requete=".$requete." ");
	mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


if ( isset( $_POST['NewBouton_service'] ) AND 
   ($_POST['NewBouton_service']=="En test ne pas utiliser")) {

	$debug=false;
	$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
	$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
	$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
	$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
	pCOM_DebugAdd($debug, "Paroissien:NewBouton_service.Enregistrer - Actif=".$check_Actif);
	pCOM_DebugAdd($debug, "Paroissien:NewBouton_service.Enregistrer - Dead=".$check_Dead);

	Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);

	//echo "<A HREF=\"javascript:AjouterService('".$_POST['Individu_id']."', '0')\">";
	?><script language="JavaScript" type="text/javascript">
	//echo "javascript:AjouterService('".$_POST['Individu_id']."', '0');";
	var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=500,height=270";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=AjouterCeService&Individu_id=" + Individu_id + "&QuiQuoi_id=" + Quiquoi_id , "profile", windowprops); 
		
	</script><?php
	
	exit;
}
   
if ( isset( $_POST['ajouter_service'] ) AND 
   ($_POST['ajouter_service']=="Ajouter ce service" OR $_POST['ajouter_service']=="Enregistrer")) {
//if ($ajouter_service) {
	Global $eCOM_db;
	if (fCOM_Get_Autorization( 0)>= 30) {

		//Paroissien_sauvegarder_Fiche($Individu_id, $Nom, $Prenom, $Sex, $Diacre, $Pretre, $Dead, $Actif, $Naissance, $LangueMaternelle, $e_mail, $Telephone, $Adresse, $Pere_id, $Mere_id, $Conjoint_id, $Confession, $Bapteme, $Communion, $ProfessionFoi, $Confirmation, $Commentaire);

		$debug = False;
		pCOM_DebugInit($debug);
		
		if (date("n") <= 7 )
		{
			$SessionActuelle= date("Y");
		} else {
			$SessionActuelle= date("Y")+1;
		}
		
		$SetResponsable=0;
		$SetPoint_de_contact=0;
		$SetWEB_gestionnaire=0;
		$SetEssentiel_Fraternite=0;
		$SetEssentiel_Adoration=0;
		$SetEssentiel_Service=0;
		$SetEssentiel_Formation=0;
		$SetEssentiel_Mission=0;
		
		if (isset( $_POST['Responsable'] )) { 
			pCOM_DebugAdd($debug, "Responsable =" .$_POST['Responsable']);
			if ($_POST['Responsable'] == "on") {$SetResponsable=1;}
		}

		if (isset( $_POST['Point_de_contact'] )) { 
			pCOM_DebugAdd($debug, "Point_de_contact =" .$_POST['Point_de_contact']);
			if ($_POST['Point_de_contact'] == "on") {$SetPoint_de_contact=1;}
		}

		if (isset( $_POST['WEB_G'] )) { 
			pCOM_DebugAdd($debug, "WEB_gestionnaire =" .$_POST['WEB_G']);
			if ($_POST['WEB_G'] == "on") {$SetWEB_gestionnaire=1;}
		}

		if (isset( $_POST['Essentiel_Fraternite'] )) { 
			pCOM_DebugAdd($debug, "Essentiel_Fraternite =" .$_POST['Essentiel_Fraternite']);
			if ($_POST['Essentiel_Fraternite'] == "on") {$SetEssentiel_Fraternite=1;}
		}

		if (isset( $_POST['Essentiel_Adoration'] )) { 
			pCOM_DebugAdd($debug, "Essentiel_Adoration =" .$_POST['Essentiel_Adoration']);
			if ($_POST['Essentiel_Adoration'] == "on") {$SetEssentiel_Adoration=1;}
		}

		if (isset( $_POST['Essentiel_Service'] )) { 
			pCOM_DebugAdd($debug, "Essentiel_Service =" .$_POST['Essentiel_Service']);
			if ($_POST['Essentiel_Service'] == "on") {$SetEssentiel_Service=1;}
		}

		if (isset( $_POST['Essentiel_Formation'] )) { 
			pCOM_DebugAdd($debug, "Essentiel_Formation =" .$_POST['Essentiel_Formation']);
			if ($_POST['Essentiel_Formation'] == "on") {$SetEssentiel_Formation=1;}
		}

		if (isset( $_POST['Essentiel_Mission'] )) { 
			pCOM_DebugAdd($debug, "Essentiel_Mission =" .$_POST['Essentiel_Mission']);
			if ($_POST['Essentiel_Mission'] == "on") {$SetEssentiel_Mission=1;}
		}

		pCOM_DebugAdd($debug, "QuiQuoi_id =" .$_POST['QuiQuoi_id']);
		pCOM_DebugAdd($debug, "Ajouter Service id =" .$_POST['Activite_id']);
		pCOM_DebugAdd($debug, "Session =" .$SessionActuelle);
		pCOM_DebugAdd($debug, "Individu_id =" .$_POST['Individu_id']);
		pCOM_DebugAdd($debug, "Clocher =" .$_POST['Lieu_id']);
		pCOM_DebugAdd($debug, "Responsable =" .$SetResponsable);
		pCOM_DebugAdd($debug, "Point_de_contact =" .$SetPoint_de_contact);
		pCOM_DebugAdd($debug, "WEB_gestionnaire =" .$SetWEB_gestionnaire);
		pCOM_DebugAdd($debug, "Essentiel_Fraternite =" .$SetEssentiel_Fraternite);
		pCOM_DebugAdd($debug, "Essentiel_Adoration =" .$SetEssentiel_Adoration);
		pCOM_DebugAdd($debug, "Essentiel_Service =" .$SetEssentiel_Service);
		pCOM_DebugAdd($debug, "Essentiel_Formation =" .$SetEssentiel_Formation);
		pCOM_DebugAdd($debug, "Essentiel_Mission =" .$SetEssentiel_Mission);
		
		if ($_POST['QuiQuoi_id'] == 0) {
			$requete = 'SELECT * FROM `QuiQuoi` T0 WHERE T0.`Individu_id`='.$_POST['Individu_id'].' AND T0.`Session` = '.$SessionActuelle.' AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10)) AND T0.`Engagement_id`=0 AND T0.`Activite_id`='.$_POST['Activite_id'].' AND T0.`Lieu_id`='.$_POST['Lieu_id'].' '; 
			$result = mysqli_query($eCOM_db, $requete);
			$count_Nb_Services=mysqli_num_rows($result);
			pCOM_DebugAdd($debug, "Paroissien:ajouter_service - count_Nb_Services " .$count_Nb_Services);
			if ($count_Nb_Services == 0)
			{
				mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session, Essentiel_Fraternite, Essentiel_Adoration, Essentiel_Service, Essentiel_Formation, Essentiel_Mission, Responsable, Point_de_contact, WEB_G, Lieu_id) VALUES (0, '.$_POST['Individu_id'].', '.$_POST['Activite_id'].', 0, 2, "'.$SessionActuelle.'", '.$SetEssentiel_Fraternite.', '.$SetEssentiel_Adoration.', '.$SetEssentiel_Service.', '.$SetEssentiel_Formation.', '.$SetEssentiel_Mission.', '.$SetResponsable.', '.$SetPoint_de_contact.', '.$SetWEB_gestionnaire.', '.$_POST['Lieu_id'].') ') or die (mysqli_error($eCOM_db));
			}
		} else {
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Essentiel_Fraternite='".$SetEssentiel_Fraternite."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Essentiel_Adoration='".$SetEssentiel_Adoration."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Essentiel_Service='".$SetEssentiel_Service."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Essentiel_Formation='".$SetEssentiel_Formation."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Essentiel_Mission='".$SetEssentiel_Mission."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Responsable='".$SetResponsable."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Point_de_contact='".$SetPoint_de_contact."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET WEB_G='".$SetWEB_gestionnaire."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, "UPDATE QuiQuoi SET Lieu_id='".$_POST['Lieu_id']."' WHERE id=".$_POST['QuiQuoi_id']." ") or die (mysqli_error($eCOM_db));
		}
	}
	echo '<script language="JavaScript" type="text/javascript">';
	echo 'javascript:window.opener.location = opener.location; window.close();';
	echo '</script>';
	exit;
}

if ( isset( $_POST['delete_service'] ) AND $_POST['delete_service']=="Supprimer ce service" ) {
//if( $delete_service ) {
	Global $eCOM_db;
	$debug = False;
	if (fCOM_Get_Autorization( 0)>= 30) {

		pCOM_DebugAdd($debug, 'Paroissien:delete_service - QuiQuoi_id='.$_POST['QuiQuoi_id']);
		$requete = 'DELETE FROM QuiQuoi WHERE id='.$_POST['QuiQuoi_id'].' '; 
		mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	}
	echo '<script language="JavaScript" type="text/javascript">';
	echo 'javascript:window.opener.location = opener.location; window.close();';
	echo '</script>';
	exit;
}

if ( (isset( $_POST['ajouter_ressourcement'] ) AND $_POST['ajouter_ressourcement']=="Ajouter Ressourcement") OR 
	 (isset( $_POST['ajouter_souhait'] ) AND $_POST['ajouter_souhait']=="Ajouter Souhait")) {

	Global $eCOM_db;
	$debug = False;
	if (fCOM_Get_Autorization( 0)>= 30) {

		$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
		$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
		$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
		$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
		pCOM_DebugAdd($debug, "Paroissien:ajouter_souhait.Enregistrer - Actif=".$check_Actif);
		pCOM_DebugAdd($debug, "Paroissien:ajouter_souhait.Enregistrer - Dead=".$check_Dead);

		Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);

		if (date("n") <= 7 )
		{
			$SessionActuelle= date("Y");
		} else {
			$SessionActuelle= date("Y")+1;
		}
		
		if (isset( $_POST['ajouter_ressourcement'] ) AND $_POST['ajouter_ressourcement']=="Ajouter Ressourcement") {
			$Activite_id=$_POST['Ressourcements'];
		} elseif (isset( $_POST['ajouter_souhait'] ) AND $_POST['ajouter_souhait']=="Ajouter Souhait") {
			$Activite_id=$_POST['Souhaits'];
		}
		
		pCOM_DebugAdd($debug, "Paroissien:ajouter_ressourcement_souhait - Ajouter Ressource id " .$Activite_id);
		pCOM_DebugAdd($debug, "Paroissien:ajouter_ressourcement_souhait - Session " .$SessionActuelle);
		pCOM_DebugAdd($debug, "Paroissien:ajouter_ressourcement_souhait - Individu_id " .$_POST['Individu_id']);
		
		$requete = 'SELECT * FROM `QuiQuoi` T0 WHERE T0.`Individu_id`='.$_POST['Individu_id'].' AND T0.`Session` = '.$SessionActuelle.' AND T0.`QuoiQuoi_id`=1 AND T0.`Engagement_id`=0 AND T0.`Activite_id`='.$Activite_id.' '; 
		$result = mysqli_query($eCOM_db, $requete);
		$count_Nb_Services=mysqli_num_rows($result);
		pCOM_DebugAdd($debug, "Paroissien:ajouter_ressourcement_souhait - count_Nb_Services=" .$count_Nb_Services);
		if ($count_Nb_Services == 0)
		{
			mysqli_query($eCOM_db, 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session) VALUES (0, '.$_POST['Individu_id'].', '.$Activite_id.', 0, 1, "'.$SessionActuelle.'") ') or die (mysqli_error($eCOM_db));
		}
	}
	
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

if ( (isset( $_POST['delete_ressourcement'] ) AND $_POST['delete_ressourcement'] != "")  OR
	 (isset( $_POST['delete_souhait'] ) AND $_POST['delete_souhait'] != "" )) {
//if ($delete_ressourcement) {

	Global $eCOM_db;
	$debug = false;
	if (fCOM_Get_Autorization( 0)>= 30) {

		$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
		$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
		$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
		$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Actif=".$check_Actif);
		pCOM_DebugAdd($debug, "Paroissien:edit_individu.Enregistrer - Dead=".$check_Dead);

		Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);

		if (date("n") <= 7 )
		{
			$SessionActuelle= date("Y");
		} else {
			$SessionActuelle= date("Y")+1;
		}
		
		if ($_POST['delete_ressourcement']!="") {
			$Activite_id=$_POST['delete_ressourcement'];
		} elseif ($_POST['delete_souhait']!="") {
			$Activite_id=$_POST['delete_souhait'];
		}
		
		$requete = 'DELETE FROM QuiQuoi WHERE Individu_id='.$_POST['Individu_id'].' AND Activite_id='.$Activite_id.' AND Engagement_id=0 AND QuoiQuoi_id=1 AND Session='.$SessionActuelle.' '; 
		mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	}
	
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


if ( isset( $_POST['ajouter_Denier'] ) AND $_POST['ajouter_Denier']=="Ajouter Denier" ) {
//if ($ajouter_Denier) {
	Global $eCOM_db;
	$debug = True;
	if (fCOM_Get_Autorization( 0)>= 40) {

		if (fCOM_Get_Autorization( 0)>= 50) {
			$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
			$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
			$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
			$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
			pCOM_DebugAdd($debug, "Paroissien:ajouter_Denier.Enregistrer - Actif=".$check_Actif);
			pCOM_DebugAdd($debug, "Paroissien:ajouter_Denier.Enregistrer - Dead=".$check_Dead);

			Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);
		}
		$Montant_denier=number_format(floatval($_POST['Montant_denier']),2,'.','');
		$Date_denier = fCOM_getSqlDate($_POST['Date_denier'],0,0,0);

		pCOM_DebugAdd($debug, "Paroissien:ajouter_Denier - Date denier =".$Date_denier);
		pCOM_DebugAdd($debug, "Paroissien:ajouter_Denier - Montant denier =".$Montant_denier);
		if ($Date_denier != "0000-00-00 00:00:00" ) {
			$requete = "INSERT INTO Denier (id, Date, Paroissien_id, Montant) VALUES ( 0 , '".$Date_denier."' , ".$_POST['Individu_id']." , ".$Montant_denier.")"; 
			mysqli_query($eCOM_db, 'UPDATE Individu SET MAJ="'.date("Y-m-d H:i:s").'" WHERE id='.$_POST['Individu_id'].' ') or die (mysqli_error($eCOM_db));
			mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
			pCOM_DebugAdd($debug, "Paroissien:ajouter_Denier - requete=".$requete);
		}
	}
		
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
	
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="RetirerDenier") {
//if ($action == "RetirerDenier") {
	Global $eCOM_db;
	$debug=False;
	$requete = 'DELETE FROM Denier WHERE id='.$_GET['id'].' '; 
	mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}


//delete part 1
if ( isset( $_POST['delete_fiche_Paroissien'] ) AND $_POST['delete_fiche_Paroissien']=="Détruire la fiche" ) {
//if ($delete_fiche_Paroissien) {
	Global $eCOM_db;
	$debug = false;
	$DestructionPossible = True;
	$requete = 'SELECT * FROM Individu WHERE id='.$_POST['id'].' '; 
	pCOM_DebugAdd($debug, "Paroissien:delete_fiche_Paroissien - requete=".$requete);
	$result = mysqli_query($eCOM_db, $requete);

	while($row = mysqli_fetch_assoc($result))
	{
		address_top();
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Destruction d\'une fiche Paroissien</B><BR></TD></TR>';
		echo '<TR><TD BGCOLOR="#EEEEEE">';
		echo '<FONT FACE="Verdana" size="2" >Etes-vous certain de vouloir détruire cette fiche : ';
		echo $row['Prenom']. ' ' .$row['Nom']. ' ?</FONT></TD></TR>';
		
		// vérifier que le paroissien n'est pas un conjoint d'un autre paroissien
		$requete = 'SELECT T1.id, T1.Nom, T1.Prenom 
			FROM Individu T1 
			WHERE T1.Conjoint_id='.$_POST['id'].' ';
		$result = mysqli_query($eCOM_db, $requete);
		if (mysqli_num_rows( $result )>=1)
		{
			$DestructionPossible = False;
			echo '<TR><TD><B><FONT FACE="Verdana" size="2" >Le paroissien est aussi conjoint :</FONT></B></TD></TR>';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				echo '<TR><TD><FONT FACE="Verdana" size="1" >['.$row['id'].'] '.$row['Prenom'].'  '.$row['Nom'].'</FONT></TD></TR>';
			}
		}		
		
		// Liste des services associées à la fiche
		$requete = 'SELECT DISTINCT T1.Nom AS Nom_Activite, T0.Session AS Annee
			FROM QuiQuoi T0 
			LEFT JOIN Activites T1 ON T1.id=T0.Activite_id
			WHERE T0.Individu_id='.$_POST['id'].' AND T1.Service=1
			ORDER BY T0.Session, T1.Nom';
		$result = mysqli_query($eCOM_db, $requete);
		if (mysqli_num_rows( $result )>=1)
		{
			$DestructionPossible = False;
			echo '<TR><TD><B><FONT FACE="Verdana" size="2" >Liste des services associés au paroissien que vous souhaitez détruire :</FONT></B></TD></TR>';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				echo '<TR><TD><FONT FACE="Verdana" size="1" >['.$row['Annee'].'] - '.$row['Nom_Activite'].'</FONT></TD></TR>';
			}
		}
		
		// Liste des Ressourcements associées à la fiche
		$requete = 'SELECT DISTINCT T1.Nom AS Nom_Activite, T0.Session AS Annee
			FROM QuiQuoi T0 
			LEFT JOIN Activites T1 ON T1.id=T0.Activite_id
			WHERE T0.Individu_id='.$_POST['id'].' AND T1.Formation=1
			ORDER BY T0.Session, T1.Nom';
		$result = mysqli_query($eCOM_db, $requete);
		if (mysqli_num_rows( $result )>=1)
		{
			$DestructionPossible = False;
			echo '<TR><TD><B><FONT FACE="Verdana" size="2" >Liste des ressourcements associés au paroissien que vous souhaitez détruire :</FONT></B></TD></TR>';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				echo '<TR><TD><FONT FACE="Verdana" size="1" >['.$row['Annee'].'] - '.$row['Nom_Activite'].'</FONT></TD></TR>';
			}
		}
		
		// Liste des Souhaits associées à la fiche
		$requete = 'SELECT DISTINCT T1.Nom AS Nom_Activite, T0.Session AS Annee
			FROM QuiQuoi T0 
			LEFT JOIN Activites T1 ON T1.id=T0.Activite_id
			WHERE T0.Individu_id='.$_POST['id'].' AND T1.Souhait=1
			ORDER BY T0.Session, T1.Nom';
		$result = mysqli_query($eCOM_db, $requete);
		if (mysqli_num_rows( $result )>=1)
		{
			$DestructionPossible = False;
			echo '<TR><TD><B><FONT FACE="Verdana" size="2" >Liste des souhaits associés au paroissien que vous souhaitez détruire :</FONT></B></TD></TR>';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				echo '<TR><TD><FONT FACE="Verdana" size="1" >* '.$row['Nom_Activite'].'</FONT></TD></TR>';
			}
		}

		if ($DestructionPossible == True) {
			echo '<TR><TD><P><form method=post action="'.$PHP_SELF.'">';
			echo '<input type="submit" name="delete_fiche_Paroissien_confirme" value="Oui">';
			echo '<input type="submit" name="" value="Non">';
			echo '<input type="hidden" name="id" value="'.$_POST['id'].'">';
			echo '</form></TD></TR>';
		} else {
			echo '<TR><TD><BR><FONT face="verdana" size="2" color=red>Tant qu\'il y a des activités associés au paroissien, il est impossible de détruire sa fiche</FONT></TD></TR>';
		}
		
		fCOM_address_bottom();
		exit();	
	}
}

//delete part 2
if ( isset( $_POST['delete_fiche_Paroissien_confirme'] ) AND $_POST['delete_fiche_Paroissien_confirme']=="Oui" ) {
//if ($delete_fiche_Paroissien_confirme) {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, "Paroissien:delete_fiche_Paroissien_confirme - id=".$_POST['id']);
	$requete = 'SELECT * FROM Individu WHERE id=' . $_POST['id'] . ' '; 
	pCOM_DebugAdd($debug, "Paroissien:delete_fiche_Paroissien_confirme - requete=".$requete);
	$result = mysqli_query($eCOM_db, $requete);
    pCOM_DebugAdd($debug, "Paroissien:delete_fiche_Paroissien_confirme - Nb Enreg dans la table=".mysqli_num_rows( $result));

	//while($row = mysql_fetch_row($result))
	if (mysqli_num_rows( $result )==1)
	{ 
        $requete = 'Delete FROM Individu WHERE id=' . $_POST['id'] . ' '; 
		pCOM_DebugAdd($debug, "Paroissien:delete_fiche_Paroissien_confirme - requete=".$requete);
        $result = mysqli_query($eCOM_db, $requete); 
		if (!$result) {
			echo 'Impossible d\'exécuter la requête : ' . mysqli_error($eCOM_db);
			exit;
        }
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Fiche détruite avec succès</FONT></CENTER></B>';
	}
}



function Selectionner_individu ( $pQui, $Genre, $Individu_id, $RetourPage, $Fiche_id)
{
		Global $eCOM_db;
		$debug = True;
		//debug('Paroissien:Selectionner_individu - pQui ='.$pQui);
		//debug('Paroissien:Selectionner_individu - Genre ='.$Genre);
		//debug('Paroissien:Selectionner_individu - Individu_id ='.$Individu_id);
		//debug('Paroissien:Selectionner_individu - RetourPage ='.$RetourPage);
		//debug('Paroissien:Selectionner_individu - Fiche_id ='.$Fiche_id);
		address_top();
		
		$requete = 'SELECT Prenom, Nom FROM Individu WHERE id='.$Individu_id.' ';
		$result = mysqli_query($eCOM_db, $requete);
		$row = mysqli_fetch_assoc($result);
		
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7"><TD><FONT FACE="Verdana" SIZE="2"><B>Sélectionner '.$pQui.' de '.$row['Prenom'].' '.$row['Nom'].'</B><BR></TD><TD></TD></TR>';

		echo '<TR><TD BGCOLOR="#EEEEEE"><FONT FACE="Verdana" size="2" ><BR>';
		
		$Afficher_10_premier = 2;
		while ($Afficher_10_premier > 0) {
			echo "<TABLE>";
			$trcolor = "#EEEEEE";
			if ($Afficher_10_premier > 1) {
				if ($Genre =="F") {
					echo "<TR><TD colspan=2><FONT face=verdana size=2>Dernières paroissiennes modifiées</FONT></TD></TR>";
				} else {
					echo "<TR><TD colspan=2><FONT face=verdana size=2>Derniers paroissiens modifiés</FONT></TD></TR>";
				}
				$requete_2 = 'MAJ DESC, ';
				$requete_3 = ' LIMIT 0, 10';
			} else {
				echo "<TR><TD colspan=2><FONT face=verdana size=2>Tous les paroissiens</FONT></TD></TR>";
				$requete_2 = '';
				$requete_3 = '';
			}
			if ($Genre =="M" or $Genre =="F") {
				$requete_1 = 'Where Sex="'.$Genre.'"';
			} else {
				$requete_1= '';
			}
			$requete = 'SELECT id, Prenom, Nom FROM Individu '.$requete_1.' Order by '.$requete_2.'Nom, Prenom '.$requete_3.' '; 
			
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Selectionner</FONT></TH>";
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Nom</FONT></TH>";
			echo "<TH bgcolor=".$trcolor."><FONT face=verdana size=2>Prénom</FONT></TH>";

			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result)){
				if ($row['id'] != $Individu_id ) {
					if ($RetourPage == "SaisieBapteme") {
						echo '<TR><TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=AffecterAIndividu&Qui='.$pQui.'&Qui_id='.$row['id'].'&Enfant='.$Individu_id.' TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Delete Record"></A></TD> ';
					} else {
						echo '<TR><TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=AffecterAIndividu&Qui='.$pQui.'&Qui_id='.$row['id'].'&Enfant='.$Individu_id.' TITLE="Selectionner '.$pQui.'"><img src="images/plus.gif" border=0 alt="Delete Record"></A></TD>  ';
					}
					echo '<TD><FONT face=verdana size=2>'.$row['Nom'].'</FONT></TD><TD><FONT face=verdana size=2>'.$row['Prenom'].'</FONT></TD></TR>'; 
				}
			}
			echo '</TABLE><BR></FONT>';
			$Afficher_10_premier = $Afficher_10_premier - 1;
		}
		
		fCOM_address_bottom();
		exit;
}

if ( isset( $_POST['Selectionner_Ascendant'] )) {
//if ($Selectionner_Ascendant) {
	$debug=false;
	if (fCOM_Get_Autorization( 0)>= 30) {
		$check_Actif = isset($_POST['Actif']) ? $_POST['Actif'] : "off" ;
		$check_Dead = isset($_POST['Dead']) ? $_POST['Dead'] : "off" ;
		$check_Pretre = isset($_POST['Pretre']) ? $_POST['Pretre'] : "off" ;
		$check_Diacre = isset($_POST['Diacre']) ? $_POST['Diacre'] : "off" ;
		pCOM_DebugAdd($debug, "Paroissien:Selectionner_Ascendant.Enregistrer - Actif=".$check_Actif);
		pCOM_DebugAdd($debug, "Paroissien:Selectionner_Ascendant.Enregistrer - Dead=".$check_Dead);

		Paroissien_sauvegarder_Fiche ($_POST['Individu_id'], $_POST['Nom'], $_POST['Prenom'], $_POST['Sex'], $check_Diacre, $check_Pretre, $check_Dead, $check_Actif, $_POST['Naissance'], $_POST['LangueMaternelle'], $_POST['e_mail'], $_POST['Telephone'], $_POST['Adresse'], $_POST['Pere_id'], $_POST['Mere_id'], $_POST['Conjoint_id'], $_POST['Confession'], $_POST['Bapteme'], $_POST['Communion'], $_POST['ProfessionFoi'], $_POST['Confirmation'], $_POST['Commentaire']);
	}
	
	if ($_POST['Selectionner_Ascendant'] == "Père" )
	{
		$Genre = "M";
	} elseif ($_POST['Selectionner_Ascendant'] == "Mère" ) {
		$Genre = "F";
	} elseif ($_POST['Selectionner_Ascendant'] == "Conjoint" ) {
		if ( $_POST['Sex'] == "F") {
			$Genre = "M";
		} elseif ( $_POST['Sex'] == "M") {
			$Genre = "F";
		} else {
			$Genre = "?";
		}
	}
	
	Selectionner_individu($_POST['Selectionner_Ascendant'], $Genre, $_POST['Individu_id'], $_POST['RetourPage'], $_POST['Fiche_id']);
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="AffecterAIndividu") {
//if ($action == "AffecterAIndividu"){
	Global $eCOM_db;
	$debug = true;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Paroissien:Action=".$_GET['action']);
	
	if ($_GET['Qui_id'] > 0 & $_GET['Enfant'] > 0) {
		pCOM_DebugAdd($debug, "Paroissien:Enfant=UPDATE Individu SET ".fCOM_stripAccents($_GET['Qui'])."_id=".$_GET['Qui_id']." WHERE id=".$_GET['Enfant']);
		$Qui = fCOM_stripAccents($_GET['Qui']);
		mysqli_query($eCOM_db, "UPDATE Individu SET ".$Qui."_id=".$_GET['Qui_id']." WHERE id=".$_GET['Enfant']." ") or die (mysqli_error($eCOM_db));
		// mysqli_query($eCOM_db, "UPDATE Individu SET '".$Qui."'_id='".$Qui_id."' WHERE id=".$Enfant." ") or die (mysqli_error($eCOM_db));
	}
	if ($_GET['Qui'] == "Conjoint") {
		pCOM_DebugAdd($debug, "Paroissien:Conjoint");
		mysqli_query($eCOM_db, "UPDATE Individu SET ".$_GET['Qui']."_id=".$_GET['Enfant']." WHERE id=".$_GET['Qui_id']." ") or die (mysqli_error($eCOM_db));
		//mysqli_query($eCOM_db, "UPDATE Individu SET ".$Qui."_id=".$Qui_id." WHERE id=".$Enfant." ") or die (mysqli_error($eCOM_db));
	}
	pCOM_DebugAdd($debug, "Paroissien:Action=AffecterAIndividu_RetourPage=".$_SESSION["RetourPageCourante"]);
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPageCourante"].'">';
	exit;
}

if ( isset( $_POST['upload_Photo'] )) {
	Global $eCOM_db;
	echo '<form method="POST" action="upload.php" enctype="multipart/form-data">';
	echo '<FONT color=green><h4>La taille maximum du fichier ne doit pas dépasser 50Ko<BR>';
	echo 'Veuillez ne pas mettre d\'accents ni d\'espace dans le nom de l\'image</font><BR>';
	echo 'Fichier (id='.$_POST['id'].') :  <BR></h4>';
	//<!-- On limite le fichier à 100Ko -->
	echo '<input type="hidden" name="MAX_FILE_SIZE" value="50000">';
	echo '<input type="file" name="avatar">';
	echo '<input type=hidden name=id value='.$_POST['id'].'>';
	echo '<input type=hidden name=Activite value='.$_POST['Activite'].'>';
	echo '<input type=hidden name=fichier_target value="Individu_'.$_POST['id'].'.jpg">';
	echo '<input type="submit" name="envoyer" value="Télécharger le fichier"></form>';
	mysqli_close($eCOM_db);
	exit();
}

