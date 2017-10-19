<?php


function debug($ch) {
   global $debug;
   if ($debug)
      echo $ch;
}

//row color
function usecolor()
{
	$tr1 = "#EEEEEE";
	$trcolor2 = "#E1E1E1";
	static $colorvalue;
	if($colorvalue == $trcolor1)
		$colorvalue = $trcolor2;
	else
		$colorvalue = $trcolor1;
	return($colorvalue);
}

session_start();
$debug = False;
//$IdSession = $_POST["IdSession"];
//session_readonly();

require('Common.php');

$debug = false;



if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderRessourcementDsServices" )
{
	global $eCOM_db;
	$requete = 'SELECT * FROM Ressourcements';
	$result=mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result, MYSQL_ASSOC))
	{
		echo '<TR>';
		echo '<TD>';
		echo ''.$row[Libelle].'; ' ;
		$requete3 = 'INSERT INTO Activites (id, Valeur_int, Nom, Formation) VALUES (0, '.$row[Code].', "'.$row[Libelle].'", 1) ';
		mysqli_query($eCOM_db, $requete3) or die (mysqli_error($eCOM_db));
	}
}

if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderSouhaitsDsServices" AND False)
{
	global $eCOM_db;
	$debug=true;
	
	error_log( "Démarrage Admin_tools:SauvegarderSouhaitsDsServices");
	$requete = 'SELECT * FROM Souhaits';
	//$result=$eCOM_db->query('SELECT * FROM souhaits');
	$result=mysqli_query($eCOM_db, $requete);
	//while($row = $result->fetch_assoc()) {
	$counter=1;
	while( $row = mysqli_fetch_assoc($result, MYSQL_ASSOC)) {
		echo '<TR><TD>';
		echo $counter.' '.$row['Libelle'].'<BR>';
		$requete3 = 'INSERT INTO Activites (id, code_souhait, Nom, Souhait) VALUES (0, '.$row['Code'].', "'.$row['Libelle'].'", 1) ';
		$counter=$counter+1;
		//$eCOM_db->query($requete3) or die (mysql_error());
		mysqli_query($eCOM_db, $requete3) or die (mysqli_error($eCOM_db));
		echo $requete3."<BR><BR>";
	}
}

if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderServicesYearly" )
{
	Global $eCOM_db;
	$debug = False;
	
	// A faire chaque année en Aout pour garder l'histoire de l'année passée
	//----------------------------------------------------------------------
	if (intval(date("n")) >= 8) {
		$AncienneSession=date("Y");
		$NouvelleSession=strval(intval(date("Y"))+1);
	} else {
		pCOM_DebugAdd($debug, "Service Exit 01");
		exit;
	}
	
	echo "<BR><BR>--------------";
	echo "<BR>Services<BR>";
	echo "--------------<BR><BR>";
	pCOM_DebugAdd($debug, "Service Start");
	
	// sauvegarde des paroissiens aux services d'une année à l'autre:
	
	$requete = 'SELECT DISTINCT T0.`Individu_id`, T0.`Engagement_id`, T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Session`, T0.Lieu_id, T1.`Nom`, T0.`Essentiel_Fraternite`, T0.`Essentiel_Adoration`, T0.`Essentiel_Service`, T0.`Essentiel_Formation`, T0.`Essentiel_Mission`, T0.`Responsable`, T0.`Point_de_contact`, T0.`WEB_G`
				FROM `QuiQuoi` T0
				LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
				WHERE T1.`Service`=1 AND T0.`Session` = '.$AncienneSession.' AND T0.`Engagement_id`=0 AND (T0.`QuoiQuoi_id`=2 OR (T0.`QuoiQuoi_id`>=5 AND T0.`QuoiQuoi_id`<=10))
				ORDER BY T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Individu_id` ';
	
	$result = mysqli_query($eCOM_db, $requete);
	pCOM_DebugAdd($debug, "Service Requete 01 = ".$requete);
	while( $row = mysqli_fetch_assoc( $result)) {
		// Vérifier que cela n'a pas déjà été déclaré dans l'année suivante
		$requete2 = 'SELECT T0.`Individu_id`, T0.`Engagement_id`, T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Session`, T0.Lieu_id, T1.`Nom`, T1.`Service`, T0.`Essentiel_Fraternite`, T0.`Essentiel_Adoration`, T0.`Essentiel_Service`, T0.`Essentiel_Formation`, T0.`Essentiel_Mission`, T0.`Responsable`, T0.`Point_de_contact`, T0.`WEB_G`
				FROM `QuiQuoi` T0
				LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
				WHERE T1.`Service`=1 AND T0.`Individu_id` = '.$row['Individu_id'].' AND T0.`Session` = '.$NouvelleSession.' AND T0.`Engagement_id`=0 AND T0.`Activite_id`='.$row['Activite_id'].' AND T0.`QuoiQuoi_id`='.$row['QuoiQuoi_id'].' AND T0.`Lieu_id`='.$row['Lieu_id'].' ';
		$result2=mysqli_query($eCOM_db, $requete2);
		//echo "<BR>".$requete2;
		$count_users=mysqli_num_rows($result2);
		pCOM_DebugAdd($debug, "Service Requete 02 = ".$requete2);
		pCOM_DebugAdd($debug, "Service Nombre = ".$count_users);
		if ($count_users == 0){
			echo "<BR>";
			$requete3 = 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Session, Lieu_id, Detail, Essentiel_Fraternite, Essentiel_Adoration, Essentiel_Service, Essentiel_Formation, Essentiel_Mission, Responsable, Point_de_contact, WEB_G) VALUES (0, '.$row['Individu_id'].', '.$row['Activite_id'].', 0, '.$row['QuoiQuoi_id'].', '.$NouvelleSession.', '.$row['Lieu_id'].', "Admin '.$NouvelleSession.'", '.$row['Essentiel_Fraternite'].', '.$row['Essentiel_Adoration'].', '.$row['Essentiel_Service'].', '.$row['Essentiel_Formation'].', '.$row['Essentiel_Mission'].', '.$row['Responsable'].', '.$row['Point_de_contact'].', '.$row['WEB_G'].') ';
			mysqli_query($eCOM_db, $requete3) or die (mysqli_error($eCOM_db));
			echo 'Service: '.$row['Nom'].' ('.$row['Activite_id'].') ajouté à '.$row['Individu_id'].' en '.$NouvelleSession.'';
		} else {
			//echo 'Rien à ajouter pour '.$row[Individu_id];
		}
	}
	
	
	echo "<BR><BR>--------------";
	echo "<BR>Ressourcements<BR>";
	echo "--------------<BR><BR>";
	
	// sauvegarde des ressources :
	$requete = 'SELECT DISTINCT T0.`Individu_id`, T0.`Engagement_id`, T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Session`, T0.`Lieu_id`, T1.`Nom`
				FROM `QuiQuoi` T0
				LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
				WHERE T1.`Formation`=1 AND T1.`YearReq`="Oui" AND T0.`Session` = '.$AncienneSession.' AND T0.`Engagement_id`=0
				ORDER BY T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Individu_id` ';
	
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result))
	{
		$requete2 = 'SELECT T0.`Individu_id`, T0.`Engagement_id`, T0.`Activite_id`, T0.`QuoiQuoi_id`, T0.`Session`, T0.`Lieu_id`, T1.`Nom`
				FROM `QuiQuoi` T0
				LEFT JOIN Activites T1 ON T1.`id`=T0.`Activite_id`
				WHERE T1.`Formation`=1 AND T1.`YearReq`="Oui" AND T0.`Individu_id` = '.$row['Individu_id'].' AND T0.`Session` = '.$NouvelleSession.' AND T0.`Engagement_id`=0 AND T0.`Activite_id`='.$row['Activite_id'].' AND T0.`QuoiQuoi_id`='.$row['QuoiQuoi_id'].' ';
		$result2=mysqli_query($eCOM_db, $requete2);
		//echo "<BR>".$requete2;
		$count_users=mysqli_num_rows($result2);
		if ($count_users == 0){
			echo "<BR>";
			$requete3 = 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Lieu_id, Session, Detail) VALUES (0, '.$row['Individu_id'].', '.$row['Activite_id'].', 0, '.$row['QuoiQuoi_id'].', '.$row['Lieu_id'].', '.$NouvelleSession.', "Admin '.$NouvelleSession.'") ';
			mysqli_query($eCOM_db, $requete3) or die (mysqli_error($eCOM_db));
			echo 'Ressourcement: '.$row['Nom'].' ('.$row['Activite_id'].') ajouté à '.$row['Individu_id'].' en '.$NouvelleSession;
		} else {
			//echo 'Rien à ajouter pour '.$row[Individu_id];
		}
	}
	echo "<BR><BR>Fin du traitement<BR><BR>";
	exit;
	
	
}



if (isset($_GET['action'] ) AND $_GET['action']=="SauvegarderSouhaitsDsQuiQuoi" )
{
	Global $eCOM_db;
	
	// A faire chaque année en Aout pour garder l'histoire de l'année passée
	//----------------------------------------------------------------------
	if (intval(date("n")) >= 8) {
		$AncienneSession=date("Y");
		$NouvelleSession=strval(intval(date("Y"))+1);
	} else {
		exit;
	}
	
	echo "<BR><BR>--------------";
	echo "<BR>Souhaits<BR>";
	echo "--------------<BR><BR>";
	
	// sauvegarde des souhaits :
	$requete = 'SELECT T0.`id`, T0.`Souhaits`, T0.`Prenom`, T0.`Nom`
				FROM `Individu` T0
				WHERE T0.`Souhaits`>0 AND T0.`id`=23';

	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result, MYSQL_ASSOC))
	{
		$requete2 = 'SELECT DISTINCT T0.`id`, T0.`Nom`, T0.`code_souhait`
				FROM `Activites` T0
				WHERE T0.`code_souhait`>0 AND T0.`Souhait` = 1
				ORDER BY T0.`Nom` ';
		$result2=mysqli_query($eCOM_db, $requete2);
		while( $row2 = mysqli_fetch_assoc( $result2, MYSQL_ASSOC))
		{
			if (((int)$row['Souhaits'] & (int)$row2['code_souhait']) > 0 and $row2['code_souhait'] > 1)
			{
				// vérifier que le transfert n'a pas déjà été réalisé
				$requete3 = 'SELECT DISTINCT T0.`id`
				FROM `QuiQuoi` T0
				WHERE T0.`Individu_id`='.$row['id'].' AND T0.`Engagement_id`=0 AND T0.`Activite_id` = '.$row2['id'].' AND T0.`Session`='.$NouvelleSession.' ';
				$result3=mysqli_query($eCOM_db, $requete3);
				$count_users=mysqli_num_rows($result3);
				if ($count_users == 0){
					echo "<BR>";
					$requete4 = 'INSERT INTO QuiQuoi (id, Individu_id, Activite_id, Engagement_id, QuoiQuoi_id, Lieu_id, Session, Detail) VALUES (0, '.$row['id'].', '.$row2['id'].', 0, 1, 1, '.$NouvelleSession.', "Admin '.$NouvelleSession.'") ';
					//mysql_query($requete4) or die (mysql_error());
					echo 'Requete ='.$requete4.'<BR>';
					echo 'Souhait: '.$row['Prenom'].' '.$row['Nom'].' - '.$row2['id'].'-'.$row2['Nom'].' ajouté à '.$row['id'].' en '.$NouvelleSession;
				} else {
					echo 'Rien à ajouter pour '.$row['Prenom'].' '.$row['Nom'].' - '.$row2['id'];
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
