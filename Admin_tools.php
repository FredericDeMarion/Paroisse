<?php
session_start();

function debug($ch) {
   global $debug;
   if ($debug)
      echo $ch;
}

require('Common.php');

$debug = false;



if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderSouhaitsDsServices" AND False)
{
	//global $eCOM_db;
	$debug=true;
	error_log( "Démarrage Admin_tools:SauvegarderSouhaitsDsServices");
	$requete = 'SELECT * FROM Souhaits';
	//$result=$eCOM_db->query('SELECT * FROM souhaits');
	$result=mysql_query($requete);
	//while($row = $result->fetch_assoc()) {
	$counter=1;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<TR><TD>';
		echo $counter.' '.$row['Libelle'].'<BR>';
		$requete3 = 'INSERT INTO Activites (id, Nom, Souhait) VALUES (0, "'.$row['Libelle'].'", 1) ';
		$counter=$counter+1;
		error_log( "Admin_tools:SauvegarderSouhaitsDsServices - requete=".$requete3);
		//$eCOM_db->query($requete3) or die (mysql_error());
		mysql_query($requete3) or die (mysql_error());
		echo $requete3."<BR><BR>";
	}
	error_log( "Fin Admin_tools:SauvegarderSouhaitsDsServices");
}

if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderSouhaitsDsQuiQuoi" ) {

	if (intval(date("n")) >= 8) {
		$Session=strval(intval(date("Y"))+1);
	} else {
		$Session=date("Y");
	}
	
	echo "<BR><BR>--------------";
	echo "<BR>Souhaits<BR>";
	echo "--------------<BR><BR>";
	
	// sauvegarde des souhaits :
	$requete = 'SELECT T0.`id`, T0.`Souhaits`, T0.`Prenom`, T0.`Nom`
				FROM `Individu` T0
				WHERE T0.`Souhaits`>0 ';

	$result = mysql_query($requete);
	while( $row = mysql_fetch_array( $result))
	{
		echo '<BR>------------------<BR>';
		$requete2 = 'SELECT DISTINCT T0.`id`, T0.`Nom`, T0.`code_souhait`
				FROM `Activites` T0
				WHERE T0.`code_souhait`>0 AND T0.`Souhait` = 1
				ORDER BY T0.`Nom` ';
		$result2=mysql_query($requete2);
		while( $row2 = mysql_fetch_array( $result2)) {
			if (((int)$row['Souhaits'] & (int)$row2['code_souhait']) > 0 and $row2['code_souhait'] > 1) {
				// vérifier que le transfert n'a pas déjà été réalisé
				$requete3 = 'SELECT DISTINCT T0.`id`
				FROM `QuiQuoi` T0
				WHERE T0.`Individu_id`='.$row['id'].' AND T0.`Engagement_id`=0 AND T0.`Activite_id` = '.$row2['id'].' AND T0.`Session`='.$Session.' ';
				$result3=mysql_query($requete3);
				$count_users=mysql_num_rows($result3);
				if ($count_users == 0){
					echo "<BR>";
					$requete4 = 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Lieu_id, Session, Detail) VALUES (0, '.$row['id'].', '.$row2['id'].', 0, 1, 1, "'.$Session.'", "Admin '.$Session.'") ';
					mysql_query($requete4) or die (mysql_error());
					echo 'Requete ='.$requete4.'<BR>';
					echo 'Souhait: '.$row['Prenom'].' '.$row['Nom'].' ('.$row['id'].') - '.$row2['id'].'-'.$row2['Nom'].' ajouté en '.$Session;
				} else {
					echo 'Rien à ajouter pour '.$row['Prenom'].' '.$row['Nom'].' ('.$row['id'].') - '.$row2['id'];
				}
			}
		}
	}
	echo "<BR><BR>Fin du traitement<BR><BR>";
	exit;
	
}


// ==================================================================
// Début --------------------------------------------------------------------------------------------------------------------------
// ==================================================================

echo '<HTML><HEAD>';
echo '<TITLE>Database Tools</TITLE>';
echo '</HEAD>';
echo '<BODY>';

$debug = False;

//$_SESSION["RetourPage"]=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
Echo '$_SERVER[\'SERVER_NAME\']='.$_SERVER['SERVER_NAME'].'<BR>';
Echo '$_SERVER[\'PHP_SELF\']='.$_SERVER['PHP_SELF'].'<BR>';
Echo '$_SERVER[\'QUERY_STRING\']='.$_SERVER['QUERY_STRING'].'<BR>';

$result = Afficher_compteur_de_login();

echo '</BODY>';
echo '</HTML>';
