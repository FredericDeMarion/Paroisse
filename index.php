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
// 08/04/2018 : Modification tables Admin_members:username varchar(50); Admin_user_online:id int(11); Bapteme:Activite_id int(11); fraternite:Jour tinyint(4) null=oui, default=0; QuiQuoi:Participation double(9,2) unsigned default=0,00; QuiQuoi:Default_02 varchar(6)
//==================================================================================================

require('Common.php');
//require('template.inc');
require('Menu.php');
require('Paroissien.php');

$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

if ( isset( $_POST['SessionSelection'] )) {
	$_SESSION["Session"] = $_POST['SessionSelection'];
}

fMENU_top();
fMENU_Title("Liste des prochains anniversaires ...");

?>

<?php
//<div class="container-fluid">
	//----------------------
	// section Anniversaire
	//----------------------
	Global $eCOM_db;

	$trcolor = "#EEEEEE";
	$joursem = array('dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam');
	
	echo '<table class="table table-bordered table-hover table-sm">';
	echo '<thead  class="thead-dark"><tr>';
	//echo '<th scope="col">Quand</th>';
	echo '<th scope="col">Anniversaire</th>';
	echo '<th scope="col">Nom</th>';
	echo '<th scope="col">Date</font></th>';
	echo '</tr></thead>';
	echo '<tbody>';
	setlocale(LC_TIME, "fr_FR");
	
	$requete = '(SELECT id, Concat(Prenom, " ", Nom) AS Paroissien,  "Naissance" as Type, Naissance as DateEv, (YEAR(CURRENT_DATE)-YEAR(Naissance)) AS Age, MOD((DATEDIFF(ADDDATE(Naissance, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Naissance)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours 
	FROM Individu 
	WHERE Naissance != "0000-00-00" AND Actif=1 AND Dead=0)
	UNION ALL 
	(SELECT T4.`id` AS id, CONCAT((SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
	FROM Individu T6
	LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
	WHERE T6.`Sex` = "F" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`), " et ",(SELECT concat( T6.`Prenom` , " ", T6.`Nom` )
	FROM Individu T6
	LEFT JOIN `QuiQuoi` AS T7 ON T7.`Individu_id` = T6.`id`
	WHERE T6.`Sex` = "M" AND T7.`QuoiQuoi_id` =1 AND T7.`Activite_id` =2 AND T7.`Engagement_id` = T4.`id`)) As Paroissien,"Mariage" AS Activité, T4.`Date_mariage` AS DateEve,
	(YEAR(CURRENT_DATE)-YEAR(T4.`Date_mariage`)) AS Age, MOD((DATEDIFF(ADDDATE(T4.`Date_mariage`, INTERVAL (YEAR(CURRENT_DATE)-YEAR(Date_mariage)) YEAR), CURRENT_DATE) + DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)),DATEDIFF(ADDDATE(CURRENT_DATE, INTERVAL 1 YEAR), CURRENT_DATE)) as NbJours
	FROM `Fiancés` AS T4
	WHERE T4.`Date_mariage` < now() and T4.`Date_mariage` != "0000-00-00 00:00:00" AND T4.`Status` != "Annulé/Reporté") 
	ORDER by NbJours, Paroissien '; //LIMIT 0,20
	
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result ))
	{
		if ($row['NbJours'] < 7) {
			$BoldText = '';
			if ($row['Paroissien'] != "" or ($row['Paroissien'] == "" and ($_SERVER['PHP_AUTH_USER'] == "administrateur" || fCOM_Get_Autorization( 0) >= 90))) {
				if ($row['Type'] == "Naissance") {
					$TypeAction=$_SERVER['PHP_SELF'].'?action=edit_Individu';
				} else {
					$TypeAction='Mariage.php?action=edit';
				}
				if ($row['NbJours'] == 0) {
					echo '<tr class="table-success" role="button" data-href="'.$TypeAction.'&id='.$row['id'].'">';
				} else {
					echo '<tr role="button" data-href="'.$TypeAction.'&id='.$row['id'].'">';
				}

				if ($row['Paroissien'] != "") {
					echo '<td width="100">'.$row['Type'].'</td>';
					echo '<td width="170">'.strftime("%Y/%m/%d",fCOM_sqlDateToOut($row['DateEv'])).' ('.$row['Age'].' ans)</td>';
					echo '<td>'.$BoldText;
					if ($row['Paroissien'] == "") {
						$Paroissien ="--";
					} else {
						$Paroissien = $row['Paroissien'];
					}
					if ($row['Type'] == "Naissance") {
						fCOM_Display_Photo($Paroissien,"", $row['id'], "edit_Individu", true);
					} else {
						fCOM_Display_Photo($Paroissien,"", $row['id'], "edit", true);
					}
					echo '</td>';

					echo '</tr>';
				}
			}
		}
	}
	echo '</tbody>';
	echo '</table>';
//</div>
?>

<?php

fMENU_bottom();





?>
