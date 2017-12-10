<?php
//==================================================================================================
//    Nom du module : Common.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 10/05/2017 : suppression des USER_LEVEL_REQUESTED
//==================================================================================================


$a = session_id();
if(empty($a)) session_start();

header( 'content-type: text/html; charset=iso-8859-1' );



// Initialisation des variables
if( ! isset( $action ) ) $action = ""; // l'initialiser si elle n'existe pas
if( ! isset( $id ) ) $id = ""; // l'initialiser si elle n'existe pas
if( ! isset( $rencontre_supprimer ) ) $rencontre_supprimer = ""; // l'initialiser si elle n'existe pas
if( ! isset( $rencontre_delete ) ) $rencontre_delete = ""; // l'initialiser si elle n'existe pas
if( ! isset( $rencontre_sauvegarder ) ) $rencontre_sauvegarder = ""; // l'initialiser si elle n'existe pas

if( ! isset( $_SESSION['Activite_id'] ) ) $_SESSION['Activite_id'] = ""; 
if( ! isset( $_SESSION['myusername'] ) ) $_SESSION['myusername'] = "";
if( ! isset( $_SERVER['PHP_AUTH_USER'] ) ) $_SERVER['PHP_AUTH_USER'] = "";

if( ! isset( $_SESSION['USER_ID'] ) ) {
	echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
	exit;
} elseif ( isset( $_SESSION['USER_ID'] ) AND $_SESSION['USER_ID'] == 0) {
	echo '<META http-equiv="refresh" content="0; URL=/Login/login.php">';
	exit;
}

function session_is_registered($x)
{
    return isset($_SESSION['$x']);
}


if ($_SERVER['PHP_AUTH_USER'] == "celebration"){
	echo '<META http-equiv="refresh" content="1; URL=/Celebration/index.php">';
}
//if(empty($_SESSION["Session"])) {
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	//echo '<META http-equiv="refresh" content="1; URL=https://frederic.de.marion.free.fr/index.php">';
	//exit;
//}

// Connection à la base MySql
require("Login/sqlconf.php");

require('Login/user_online.php');
if(!session_is_registered('myusername')){
	//require('Login/main_login.php');
	//exit;
}

$eCOM_db = mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("Common: Cannot connect MySql : " . mysqli_error());
mysqli_query($eCOM_db, "SET sql_mode = ''");
mysqli_query($eCOM_db, "SET NAMES 'ISO-8859-1'");
mysqli_query($eCOM_db, 'SET NAMES latin1');
setlocale (LC_TIME, 'fr_FR','fra'); 


// Filtrer les vieilles fiches
if (! empty($_SERVER['FILTER_OLD'])) {
	$_SERVER['FILTER_OLD'] = False;
}

// Données entrantes
function Securite_bdd($string)
{
	global $eCOM_db;
	// On regarde si le type de string est un nombre entier (int)
	if(ctype_digit($string)) {
		$string = intval($string);
	} else {
		$string = mysqli_real_escape_string($eCOM_db, $string);
		$string = addcslashes($string, '%_');
	}
	return $string;
 
}

// Données sortantes
function Securite_html($string)
{
	//return htmlentities($string);
	return stripslashes($string);
}


// Compteur de login
function Afficher_compteur_de_login()
{
	Global $eCOM_db;
	$sql='SELECT counter FROM Admin_counter';
	$result1=mysqli_query($eCOM_db, $sql);
	$row1 = mysqli_fetch_assoc($result1);
	$counter= $row1['counter'];

	$CounterToPrint = sprintf("%07d", $row1['counter']);
	echo "<img src='images/Digit_coin_G.gif' border='0' alt='Compteur'>";

	for ($i=0; $i<=6; $i++) {
		$digit = substr($CounterToPrint, $i, 1);
		echo "<img src='images/Digit_".$digit.".jpg' border='0' alt='Compteur'>";
	}
	echo "<img src='images/Digit_coin_D.gif' border='0' alt='Compteur'>";
	return (0);

}

//-------------------------------------------
// Texte
//-------------------------------------------


function fCOM_stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


function fCOM_Min($A, $B) {
	if ($A < $B) {
		return $A;
	} else {
		return $B;
	}
}

function fCOM_Max($A, $B) {
	if ($A > $B) {
		return $A;
	} else {
		return $B;
	}
}


function fCOM_GetWindowBack($pNB_Back) {
	echo '<script language="JavaScript" type="text/javascript">';
	echo '<script language=javascript>window.history.go(-'.$pNB_Back.');</script>';
	echo '</script>';}

//-------------------------------------------
// Date
//-------------------------------------------

function fCOM_sqlDateToOut($sqldate) {

	setlocale (LC_TIME, "fr");
	$year= substr($sqldate,0,4);
	$month= substr($sqldate,5,2);
	$day = substr($sqldate,8,2);
	$hour = substr($sqldate,11,2);
	$min = substr($sqldate,14,2);

	return mktime($hour,$min,0,$month,$day, $year);
}

function fCOM_PrintDate($sqldate) {

	setlocale (LC_TIME, "fr");
	$year= substr($sqldate,0,4);
	$month= substr($sqldate,5,2);
	$day = substr($sqldate,8,2);
	//$hour = substr($sqldate,11,2);
	//$min = substr($sqldate,14,2);

	return $day."/".$month."/".$year; //." ".$hour.":".$min."00";
}

function fCOM_getSqlDate($Date, $hour, $minute, $second) {
	$debug=False;
	pCOM_DebugAdd($debug, "Common:fCOM_getSqlDate - Date=" .$Date." Heure=".$hour.":".$minute.":".$second);
	if (strlen($Date) == 0) {
		$fCOM_getSqlDate = NULL; 
	} else {
		list($day, $month, $year) = preg_split('[/]', $Date);
		if (strlen($day)==1) $day="0".$day;
		if (strlen($month)==1) $month="0".$month;
		if (strlen($year)==2) $year="20".$year;
		$fCOM_getSqlDate=$year."-".$month."-".$day." ".sprintf("%02d", $hour).":".sprintf("%02d", $minute).":".sprintf("%02d", $second);
	
		pCOM_DebugAdd($debug, "Common:fCOM_getSqlDate - fCOM_getSqlDate is " .$fCOM_getSqlDate);
		$DateTimeATester= Date("Y-m-d H:i:s", mktime($hour, $minute, $second, $month, $day, $year));
		pCOM_DebugAdd($debug, "Common:fCOM_getSqlDate - DateTime a tester is " .$DateTimeATester);
		if ($fCOM_getSqlDate != $DateTimeATester) {
			//pCOM_DebugAlert($debug, "COMMON:fCOM_getSqlDate - Erreur dans la saisie de date: fCOM_getSqlDate=".$fCOM_getSqlDate." DateTimeATester=".$DateTimeATester);
			pCOM_DebugAlert($debug, "COMMON:fCOM_getSqlDate - Erreur dans la saisie de date: fCOM_getSqlDate=".$fCOM_getSqlDate);
			$fCOM_getSqlDate = NULL; //"0000-00-00 00:00:00";
		}
	}
	return $fCOM_getSqlDate;
}

function fCOM_Afficher_Age($pDate)
{
	$birthDate = explode("-", $pDate);
	
	if ( date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0])) != 943916400 )
	//if (strftime("%d/%m/%y", fCOM_sqlDateToOut($pDate)) != "01/01/70" ) 
	{
		$birthDate = explode("-", $pDate);
		$Age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-1):(date("Y")-$birthDate[0]));
		return($Age);
	} else {
		return(-1);
	}
}


//=========================================================
// Debuger
//=========================================================

function pCOM_DebugInit($pDebug){
	Global $eCOM_db;
	if ($pDebug) {
		mysqli_query( $eCOM_db, 'TRUNCATE Debug') or die (mysqli_error($eCOM_db));
	}
}

function pCOM_DebugAdd($pDebug, $pString){
	Global $eCOM_db;
	$Where = "Logbook"; // "MySQL" "Logbook"
	$pString = '(user='.$_SESSION['USER_ID'].') '.$pString;
	
	if ( $pDebug ) {
		if ($Where == "MySQL") {
			mysqli_query($eCOM_db, 'INSERT INTO Debug (Date, Comment) VALUES ("'.date("Y-m-d H:i:s").'", "'.$pString.'")') or die (mysqli_error($eCOM_db));
			
		} elseif ($Where == "Logbook") {
			error_log($pString);
		}
	}
}

function pCOM_DebugAlert($pDebug, $pString){
	if ($pDebug == True) {
	?><SCRIPT language=javascript>
		alert('<?php print $pString; ?>')
	</SCRIPT><?php
	}
}


function debug_add($pDebug, $pString){
	Global $eCOM_db;
	//global $pDebug;
	if ($pDebug) {
		mysqli_query($eCOM_db, 'INSERT INTO Debug (Date, Comment) VALUES ("'.date("Y-m-d H:i:s").'", "'.$pString.'")') or die (mysqli_error($eCOM_db));
	}
}

function fCOM_debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php echo $ch; ?>')
	</SCRIPT><?php

}


//=========================================================
// Couleur des lignes
//=========================================================
function usecolorPlus( $pDate )
{
	global $debug;
	$debug=false;
	pCOM_DebugInit($debug);
	pCOM_DebugAdd($debug, "Common:usecolorPlus - pDate=".$pDate);
	if (! empty($pDate)) {
		$dateNow=strtotime(now);
		$dateTest=strtotime($pDate);
		if ($dateNow > $dateTest) {
			$trcolor1 = "#CCEECC";
			$trcolor2 = "#CCE4CC";
		} else {
			$trcolor1 = "#EEEEEE";
			$trcolor2 = "#E1E1E1";
		}
	} else {
		$trcolor1 = "#EEEEEE";
		$trcolor2 = "#E1E1E1";
	}
	static $colorvalue;
	if($colorvalue == $trcolor1)
		$colorvalue = $trcolor2;
	else
		$colorvalue = $trcolor1;
	return($colorvalue);
}


function ustr_replace($search, $replace, $subject, $cur=0) {
return (strpos($subject, $search,$cur)) ? substr_replace($subject, $replace,(int)strpos($subject,$search,$cur), strlen($search)) : $subject;
}//ustr_replace

//=========================================================
// Récupérer le niveau d'autorisation
//=========================================================

if ($_SERVER['PHP_AUTH_USER'] == "administrateur" ) {
	$_SERVER['USER'] = 1;
} elseif ($_SERVER['PHP_AUTH_USER'] == "comptable" || 
	$_SERVER['PHP_AUTH_USER'] == "gestionnaire") {
	$_SERVER['USER'] = 2;
} elseif ($_SERVER['PHP_AUTH_USER'] == "accompagnateur") {
	$_SERVER['USER'] = 3;
} elseif ($_SERVER['PHP_AUTH_USER'] == "sacristie") {
	$_SERVER['USER'] = 4;
}
$_SERVER['USER'] = 5;


Function fCOM_Get_Autorization($pActivite_id, $pLevel= 100) {
	Global $eCOM_db;
	
	//  0 aucun droit
	// 10 Paroissien (consommateur de l'activité)
	// 20 consultation / accompagnateur
	// 30 Gestionnaire
	// 40 Comptable
	// 50 super-utilisateur, gestionnaire +
	// 90 Administrateur
	
	// '.fCOM_Get_Autorization(3, $_SESSION['USER_LEVEL_REQUESTED']).'
	Global $Debug;
	if (date("n") <= 7 ) {
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}
	//pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization pActivite_id=".$pActivite_id." USER_LEVEL_REQUESTED=".$_SESSION['USER_LEVEL_REQUESTED']);
	//pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization - pLevel=".$pLevel);

	if ($pActivite_id != 0) {
		$deltaRequete = 'AND Activite_id='.$pActivite_id.' ';
	} else {
		$deltaRequete = '';
	}
	
	//pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization - pLevel=".$pLevel);
	$fCOM_Get_Autorization = $pLevel;
	pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - USER_LEVEL_REQUESTED = 100");

	// tester s'il est administrateur
	$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' AND Activite_id=116 AND Engagement_id=0 AND WEB_G=1 AND Session="'.$SessionActuelle.'"';
	//error_log($Requete);
	$result=mysqli_query($eCOM_db, $Requete);
	$count_1=mysqli_num_rows($result);
	if ($count_1 > 0) {
		$fCOM_Get_Autorization = fCOM_Min(90, $pLevel);
		$_SERVER['PHP_AUTH_USER'] = "Administrateur";
		pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Administrateur ".$fCOM_Get_Autorization." requete = ".$Requete);
	} else {
		
		// tester s'il est super-utilisateur
		$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' AND Activite_id=1 AND Engagement_id=0 AND WEB_G=1 AND Session="'.$SessionActuelle.'"';
		//error_log($Requete);
		$result=mysqli_query($eCOM_db, $Requete);
		$count_1=mysqli_num_rows($result);
		if ($count_1 > 0) {
			$fCOM_Get_Autorization = fCOM_Min(50, $pLevel);
			$_SERVER['PHP_AUTH_USER'] = "Super-utilisateur";
			pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Super-utilisateur ".$fCOM_Get_Autorization." requete = ".$Requete);
		} else {
			
			// tester si niveau comptable "Conseil économique"=31
			$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' AND Activite_id=31 AND Engagement_id=0 AND WEB_G=1 AND Session="'.$SessionActuelle.'"';
			$result=mysqli_query($eCOM_db, $Requete);
			$count_1=mysqli_num_rows($result);
			if ($count_1 > 0) {
				$fCOM_Get_Autorization = fCOM_Min(40, $pLevel);
				$_SERVER['PHP_AUTH_USER'] = "Comptable";
				pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Comptable ".$fCOM_Get_Autorization." requete = ".$Requete);
			} else {
				
				// tester s'il y a des droits niveau gestionnaire
				$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' '.$deltaRequete.' AND Engagement_id=0 AND WEB_G = 1 AND Session="'.$SessionActuelle.'"';
				$result=mysqli_query($eCOM_db, $Requete);
				$count_1=mysqli_num_rows($result);
				if ($count_1 > 0) {
					$fCOM_Get_Autorization = fCOM_Min(30, $pLevel);
					$_SERVER['PHP_AUTH_USER'] = "Gestionnaire";
					pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Gestionnaire ".$fCOM_Get_Autorization." requete = ".$Requete);				
				} else {

					// tester s'il y a des droits niveau consultation, accompagnateur
					$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' '.$deltaRequete.' AND Engagement_id=0 AND QuoiQuoi_id=2 AND Session="'.$SessionActuelle.'"';
					$result=mysqli_query($eCOM_db, $Requete);
					$count_1=mysqli_num_rows($result);
					if ($count_1 > 0) {
						$fCOM_Get_Autorization = fCOM_Min(20, $pLevel);
						$_SERVER['PHP_AUTH_USER'] = "Accompagnateur";
						pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Accompagnateur ".$fCOM_Get_Autorization." requete = ".$Requete);
					} else {

						// tester s'il y a des droits niveau paroissien consommateur de l'activité
						$Requete = 'SELECT * from QuiQuoi WHERE Individu_id='.$_SESSION['USER_ID'].' AND '.$deltaRequete.' AND Engagement_id>0 AND QuoiQuoi_id=1 AND Session="'.$SessionActuelle.'"';
						$result=mysqli_query($eCOM_db, $Requete);
						$count_1=mysqli_num_rows($result);
						if ($count_1 > 0) {
							$fCOM_Get_Autorization = fCOM_Min(10, $pLevel);
							$_SERVER['PHP_AUTH_USER'] = "Paroissien";
						} else {
							$fCOM_Get_Autorization = 0;
							$_SERVER['PHP_AUTH_USER'] = "Pas défini";
						}
						pCOM_DebugAdd($Debug, "Common:fCOM_Get_Autorization (user=".$_SESSION['USER_ID'].") - Paroissien ".$fCOM_Get_Autorization." requete = ".$Requete);
					}
				}
			}
		}
	}
	return $fCOM_Get_Autorization;
}


//=========================================================
// Récupérer la liste des lieux
//=========================================================


function pCOM_Get_liste_lieu_celebration($pParoisse) {
	Global $eCOM_db;
	$Liste_Lieu_Celebration="";
	$Item = 1;
	$requete_Lieux = 'SELECT * FROM Lieux WHERE IsParoisse <= '.$pParoisse.' AND IsParoisse >= 0 ORDER BY IsParoisse, Lieu';
	$result_Lieux = mysqli_query($eCOM_db, $requete_Lieux);
	while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
		$Liste_Lieu_Celebration[$Item]= array($row_lieu['id'], $row_lieu['Lieu']);
		$Item =$Item + 1;
	}
	return $Liste_Lieu_Celebration;
}

function pCOM_Get_NomParoisse() {
	Global $eCOM_db;
	$pCOM_Get_NomParoisse="";
	$requete_Lieux = 'SELECT * FROM Lieux WHERE IsParoisse = -1';
	$result_Lieux = mysqli_query($eCOM_db, $requete_Lieux);
	while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
		$pCOM_Get_NomParoisse = $row_lieu['Lieu'];
	}
	return $pCOM_Get_NomParoisse;
}

function fCOM_get_lieu($pLieu_id) {
	Global $eCOM_db;
	$fCOM_get_lieu="";
	if ($pLieu_id == 0) {
		$fCOM_get_lieu = "All";
	} else {
		$requete_Lieux = 'SELECT * FROM Lieux WHERE id='.$pLieu_id;
		$result_Lieux = mysqli_query($eCOM_db, $requete_Lieux);
		while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
			$fCOM_get_lieu= $row_lieu['Lieu'];
		}
	}
	return $fCOM_get_lieu;
}

function fCOM_Get_JourSemaine($pJourId) {
	if (! isset($pJourId)) {$pJourId = 0;}
	$Liste_Jour = fCOM_Get_Liste_JoursSemaine();
	if ($pJourId >= 0 AND $pJourId <= 7) {
		$fCOM_Get_JourSemaine = $Liste_Jour[$pJourId];
	} else {
		$fCOM_Get_JourSemaine = $Liste_Jour[0];
	}
	return $fCOM_Get_JourSemaine;
}

function fCOM_Get_Ecole($pEcoleId) {
	Global $eCOM_db;
	$fCOM_Get_Ecole="";

	$requete_Lieux = 'SELECT * FROM Ecoles WHERE id='.$pEcoleId;
	$result_Lieux = mysqli_query($eCOM_db, $requete_Lieux);
	while($row_lieu = mysqli_fetch_assoc($result_Lieux)){
		$fCOM_Get_Ecole= $row_lieu['Nom'];
	}
	
	return $fCOM_Get_Ecole;
}

//=========================================================
// Récupérer la liste des serviteurs
//=========================================================


function fCOM_Get_liste_serviteurs($pService, $pAppendedList) {
	Global $eCOM_db;
	// Array = Individu_id, Lieu, Prénom, Nom
	$Item = 1;
	if ($pAppendedList == False) {
		//$Liste_Lieu_Celebration[$Item]= array($row_lieu['id'], $row_lieu['Lieu']);
		$fCOM_Get_liste_serviteurs[$Item]= array(0,"","","Non défini");
		$Item =$Item + 1;
	}
	
	if (date("n") <= 7 ) {
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}

	$requete = 'SELECT DISTINCT T1.`id`, T1.`Prenom`, T1.`Nom`, T1.`Adresse`, T1.`Telephone`, T1.`e_mail`, T2.Lieu AS Lieu
				FROM `QuiQuoi` T0
				LEFT JOIN `Individu` T1 ON T1.`id`=T0.`Individu_id`
				LEFT JOIN `Lieux` T2 ON T2.`id`=T0.`Lieu_id`
				WHERE T0.`Engagement_id`=0 AND T0.`Activite_id`='.$pService.' AND T0.`Session`='.$SessionActuelle.' AND T1.`Dead`=0 AND T1.`Actif`=1
				ORDER BY T2.`Lieu`, T1.`Nom`, T1.`Prenom`';

	$result_Serviteurs = mysqli_query($eCOM_db, $requete);
	while($row_Serviteur = mysqli_fetch_assoc($result_Serviteurs)){
		$fCOM_Get_liste_serviteurs[$Item]= array($row_Serviteur['id'],$row_Serviteur['Lieu'],$row_Serviteur['Prenom'],$row_Serviteur['Nom']);
		$Item =$Item + 1;
	}
	return $fCOM_Get_liste_serviteurs;
}


function fCOM_Get_liste_celebrants($pCelebrant = 0) {
	// $pCelebrant permet de garder un célébrant dans une liste alors qu'il ne fait plus partie de la paroisse (ex Jean-Hubert), ceci est primordiale si on consulte une ancienne fiche (ex. baptême) et qu'on enregistre la fiche, on perd alors le célébrant
	Global $eCOM_db;
	$Item = 1;
	if (date("n") <= 7 ) {
		$SessionActuelle= date("Y");
	} else {
		$SessionActuelle= date("Y")+1;
	}
	if ( $_SESSION["Session"] <> $SessionActuelle ) {
		$WhereRequete = 'WHERE (T1.`Diacre`=1 OR T1.`Pretre`=1)';
	} else {
		$WhereRequete = 'WHERE T1.`Actif`=1 AND T1.`Dead`=0 AND (T1.`Diacre`=1 OR T1.`Pretre`=1)';
	}
	$fCOM_Get_liste_celebrants[$Item] = array(0, "", "En attente");
	$Item =$Item + 1;
	$requete = 'SELECT DISTINCT T1.`id`, T1.`Nom`, T1.`Prenom` 
				FROM `Individu` T1 
				'.$WhereRequete.'
				ORDER BY T1.`Prenom`, T1.`Nom`';
	$result_Serviteurs = mysqli_query($eCOM_db, $requete);
	while($Celebrant = mysqli_fetch_assoc($result_Serviteurs)){
		$fCOM_Get_liste_celebrants[$Item] = array($Celebrant['id'], $Celebrant['Prenom'], $Celebrant['Nom']);
		If ($Celebrant['id'] == $pCelebrant){
			$pCelebrant = 0;
		}
		$Item =$Item + 1;
	}
	If ($pCelebrant != 0){
		$requete = 'SELECT DISTINCT T1.`id`, T1.`Nom`, T1.`Prenom` 
				FROM `Individu` T1 
				WHERE T1.`id`= '.$pCelebrant.'';
		$result_Serviteurs = mysql_query($requete);
		while($Celebrant = mysql_fetch_array($result_Serviteurs)){
			$fCOM_Get_liste_celebrants[$Item] = array($Celebrant['id'], $Celebrant['Prenom'], $Celebrant['Nom']);
			$Item =$Item + 1;
		}
	}
	
	return $fCOM_Get_liste_celebrants;
}



function fCOM_Get_liste_activites() {
	Global $eCOM_db;
	// La fonction n'est pas encore utilisée, mais disponible ici
	$fCOM_Get_liste_activites = array("NbItems" => array(0),
							"Code" => array(),
							"Libelle" => array()
							);
	$fCOM_Get_liste_activites["NbItems"][0] = 0;
	$requete = 'SELECT * FROM `Activites` WHERE Service=1 AND id > 1 ORDER BY `Nom` '; 
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result )) 
	{
		$fCOM_Get_liste_activites["id"][$fCOM_Get_liste_activites["NbItems"][0]]=$row['id'];
		$fCOM_Get_liste_activites["Libelle"][$fCOM_Get_liste_activites["NbItems"][0]]=$row['Nom'];
		$fCOM_Get_liste_activites["NbItems"][0] = $fCOM_Get_liste_activites["NbItems"][0] + 1;
	}
	return $fCOM_Get_liste_activites;
}



function fCOM_Get_liste_ressources() {
	Global $eCOM_db;
	// Initialiser liste des Ressourcements
	$fCOM_Get_liste_ressources = array("NbItems" => array(0),
									"Code" => array(),
									"Libelle" => array()
								);
	$fCOM_Get_liste_ressources["NbItems"][0] = 0;
	$requete = 'SELECT * FROM `Activites` WHERE Formation=1 ORDER BY `Nom` '; 
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result )) 
	{
		$fCOM_Get_liste_ressources["id"][$fCOM_Get_liste_ressources["NbItems"][0]]=$row['id'];
		$fCOM_Get_liste_ressources["Libelle"][$fCOM_Get_liste_ressources["NbItems"][0]]=$row['Nom'];
		$fCOM_Get_liste_ressources["NbItems"][0] = $fCOM_Get_liste_ressources["NbItems"][0] + 1;
	}

	return $fCOM_Get_liste_ressources;
}



function fCOM_Get_liste_souhaits() {
	Global $eCOM_db;
	// Initialiser liste des Souhaitss
	$fCOM_Get_liste_souhaits = array("NbItems" => array(0),
									"Code" => array(),
									"Libelle" => array()
								);
	$fCOM_Get_liste_souhaits["NbItems"][0] = 0;
	$requete = 'SELECT * FROM `Souhaits` ORDER BY `Libelle` '; 
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result )) 
	{
		$fCOM_Get_liste_souhaits["Code"][$fCOM_Get_liste_souhaits["NbItems"][0]]=$row['Code'];
		$fCOM_Get_liste_souhaits["Libelle"][$fCOM_Get_liste_souhaits["NbItems"][0]]=$row['Libelle'];
		$fCOM_Get_liste_souhaits["NbItems"][0] = $fCOM_Get_liste_souhaits["NbItems"][0] + 1;
	}
	return $fCOM_Get_liste_souhaits;
}


function fCOM_Get_liste_ecoles() {
	Global $eCOM_db;

	$Item = 1;
	$fCOM_Get_liste_ecoles[$Item] = array(0, "Ecole ne figurant pas dans la liste");

	$requete = 'SELECT * FROM `Ecoles` ORDER BY `Nom` '; 
	$result = mysqli_query($eCOM_db, $requete);
	while( $row = mysqli_fetch_assoc( $result )) 
	{
		$Item =$Item + 1;
		$fCOM_Get_liste_ecoles[$Item] = array($row['id'], $row['Nom']);
	}
	return $fCOM_Get_liste_ecoles;
}


function fCOM_Get_Liste_JoursSemaine() {
	$fCOM_Get_Liste_JoursSemaine = array("Jour non défini", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
	// 1 = Lundi
	return $fCOM_Get_Liste_JoursSemaine;
}

function fCOM_Get_Liste_Groupe_KT() {
	$fCOM_Get_Liste_Groupe_KT[1] = array("NO", "Non défini");
	$fCOM_Get_Liste_Groupe_KT[2] = array("BAPT1A", "Découverte et Préparation Baptême 1ère Année");
	$fCOM_Get_Liste_Groupe_KT[3] = array("BAPT2A", "Découverte et Préparation Baptême 2ème Année");
	$fCOM_Get_Liste_Groupe_KT[4] = array("CVE", "Préparation 1ère communion"); //Chemin vers Eucharistie 
	$fCOM_Get_Liste_Groupe_KT[5] = array("PP", "Porte parole");
	return $fCOM_Get_Liste_Groupe_KT;
}

function fCOM_Get_Liste_SituationFamiliale() {
	$fCOM_Get_Liste_SituationFamiliale = array("Non défini", "Marié", "Pacsé", "Séparé", "Veuf", "Seul");

	return $fCOM_Get_Liste_SituationFamiliale;
}


//=========================================================
// Formater les emails
// 
// créé le 29/03/2016
//=========================================================
function format_email_list( $pListeEmail, $pSeparation) {
	$pListeEmail = strtolower(trim(str_replace(';', ' ', $pListeEmail)));
	$RetourChaine="";
	$pos = 0;
	while (strlen($pListeEmail) > 0) {
		$pos = strpos($pListeEmail, " ");
		if ($pos > 0) {
			$ChaineTrouve = substr($pListeEmail, 0, $pos);
			if (strpos($pListeEmail, $ChaineTrouve, 1) === False) {
				$RetourChaine .= $ChaineTrouve." ";
			}
			$pListeEmail = trim(substr($pListeEmail,  $pos));
		} else {
			$RetourChaine .= $pListeEmail;
			$pListeEmail = "";
		}
	}
	$RetourChaine = str_replace(' ', $pSeparation.' ', $RetourChaine);
	return trim($RetourChaine);
}

//=========================================================
// Formater les NumTel
// 
// créé le 29/03/2016
//=========================================================
function format_Telephone( $pTelNum, $pSeparator) {
	$pTelNum = trim($pTelNum);
	
	// Gérer le premier '0' ou signe '+'
	if (substr($pTelNum, 0, 1) != "0" && substr($pTelNum, 0, 1) != "+") {
		$pTelNum = "0".$pTelNum;
	}
	
	// remplacer les '_' '-' '/' par des '.'
	$pTelNum = str_replace('-','', $pTelNum);
	$pTelNum = str_replace('_','', $pTelNum);
	$pTelNum = str_replace('/','', $pTelNum);
	$pTelNum = str_replace('.','', $pTelNum);
	
	$debug = false;
	pCOM_DebugAdd($debug, "Common:format_Telephone pTelNum=".$pTelNum);
	
	$ratio_espace = 0;
	$Test_Zero = substr_count($pTelNum, " ");
	if ($Test_Zero > 0) {
		$ratio_espace = strlen($pTelNum) / $Test_Zero;
	}
	
	if ($ratio_espace < 3 ) {
		$pTelNum = str_replace(' ','', $pTelNum);
		$RetourChaine = substr(chunk_split($pTelNum, 2, '.'), 0, -1);
	
	} else {
		$RetourChaine="";
		$pos = 0;
		while (strlen($pTelNum) > 0) {
			$pos = strpos($pTelNum, " ");
			if ($pos > 0) {
				// evite les répétitions de même numéro
				$ChaineTrouve = substr($pTelNum, 0, $pos);
				if (strpos($pTelNum, $ChaineTrouve, 1) === False) {
					$RetourChaine .= substr(chunk_split(substr($pTelNum, 0, $pos), 2, '.'), 0, -1).$pSeparator;
				}
				$pTelNum = trim(substr($pTelNum,  $pos));
			} else {
				$RetourChaine .= substr(chunk_split($pTelNum, 2, '.'), 0, -1);
				$pTelNum = "";
			}
		}
	}
	
	return trim($RetourChaine);
}

function fCOM_Display_Photo($pNom, $pPrenom, $pid, $pFont_Size="1", $pCliquable=False)
{
	
	if (file_exists("Photos/Individu_".$pid.".jpg")) { 
		if ($pCliquable == False) {
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].' class="tooltip"><FONT SIZE="'.$pFont_Size.'">'.$pNom.'</FONT>';
			echo '<em><span></span>';
			echo "<img src='Photos/Individu_".$pid.".jpg' height='100' border='1' alt='Paroissien_".$pid."'>";
			echo '<br><FONT face=verdana size=1>'.$pPrenom.' '.$pNom.'</FONT>';
			echo '</em></A>';
		} else {
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$pid.' class="tooltip"><FONT SIZE="'.$pFont_Size.'">'.$pPrenom.' '.$pNom.'</FONT>';
			echo '<em><span></span>';
			echo "<img src='Photos/Individu_".$pid.".jpg' height='100' border='1' alt='Paroissien_".$pid."'>";
			echo '<br><FONT face=verdana size=1>'.$pPrenom.' '.$pNom.'</FONT>';
			echo '</em></A>';
		}
	} else {
		if ($pCliquable == False) {
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].'>';
			echo '<FONT SIZE='.$pFont_Size.'>'.$pPrenom.' '.$pNom.'</FONT> ';
			echo '</A>';
		} else {
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=edit_Individu&id='.$pid.'>';
			echo '<FONT SIZE='.$pFont_Size.'>'.$pPrenom.' '.$pNom.'</FONT> ';
			echo '</A>';
		}
	}
}

//=========================================================
// Appel à Bootstrap
//=========================================================
function fCOM_Bootstrap_init() {
	echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">';
	echo '<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>';
	echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>';
	echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>';
}


//=========================================================
//  Bas de pages
//=========================================================
function fCOM_address_bottom () {
	Global $eCOM_db;
	echo '<TR BGCOLOR="#DDDDDD">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#DDDDDD">';
	echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].'&nbsp</TD></TR>';
	echo '</DIV></TABLE>';
	echo '</TR>';

	echo '</TABLE>';
	
	echo '</TD></TR>';
	echo '</TABLE>';

	echo '</BODY>';
	echo '</HTML>';
	mysqli_close($eCOM_db);
}

//=========================================================
// Configuration des accompagnateurs
//=========================================================

if ( isset( $_GET['action'] ) AND $_GET['action']=="Configuration_Accompagnateur") {
//if ($action == "Configuration_Accompagnateur") {
	Global $eCOM_db;
	//require("Login/sqlconf.php");
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

	address_top();
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	$debug = false;
	echo '<link rel="stylesheet" type="text/css" href="includes/Tooltip.css">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Configuration des accompagnateurs</B><BR></TD>';
	echo '</TR>';
	echo '<TR>';
	echo '<TD BGCOLOR="#EEEEEE">';
	echo "<TABLE>";
	$trcolor = "#EEEEEE";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2> </font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Prénom</font></TH>\n";
	echo "<TH bgcolor=$trcolor><font face=verdana size=2>Nom</font></TH>\n";
	
	// Afficher les accompagnateurs noir='session courante'  grise='autre session'
	$requete = 'SELECT * FROM Individu WHERE Actif=1 ORDER BY Nom, Prenom';
	$requete = 'SELECT T0.`id`, T0.`Nom`, T0.`Prenom`, T1.`Activite_id`, T1.`QuoiQuoi_id`, T1.`Engagement_id`, T1.`Session`, IF(T1.`Activite_id`='.$_SESSION["Activite_id"].' AND T1.`QuoiQuoi_id`=2 AND T1.`Engagement_id`=0 AND T1.`Session`="'.$_SESSION["Session"].'", 1, 0) AS Test
    FROM Individu T0
	LEFT JOIN QuiQuoi T1 ON T0.`id` = T1.`Individu_id`
	GROUP BY T0.`Nom`, T0.`Prenom`, Test
	ORDER BY Test DESC, T0.`Nom`, T0.`Prenom`';
	//
	
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
	
		// définition de la couleur de la ligne
		$Accompagnateur_couleur = "";  // gris
		if ($row['Test']==1 || $_SESSION["Session"] == "All") {
			$Accompagnateur_couleur = "<B>"; // noir
		}
		$trcolor = usecolor();
		echo '<TR>';
		
		if ($_SESSION["Session"] <> "All") {
			if ($Accompagnateur_couleur == "<B>") {
				echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=accompagnateur_retirer_session&Individu_id='.$row['id'].' TITLE="Retirer de la session '.$_SESSION["Session"].' ('.$_SESSION["Activite_id"].') "><img src="images/moins.gif" border=0 alt="Supprimer"></a></TD>  ';
			} else {
				echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?action=accompagnateur_ajouter_session&Individu_id='.$row['id'].' TITLE="Ajouter a la session '.$_SESSION["Session"].' ('.$_SESSION["Activite_id"].') "><img src="images/plus.gif" border=0 alt="Ajouter"></a></TD>  ';
			} 
		} else {
			echo '<TD bgcolor='.$trcolor.'><CENTER></TD>';
		}
		echo '<TD bgcolor='.$trcolor.'><CENTER><A HREF='.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=edit_Individu&id='.$row['id'].' TITLE="Editer accompagnateur"><img src="images/edit.gif": border=0 alt="Edit Record"></A></TD>';
		echo '<TD bgcolor='.$trcolor.'><CENTER></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$Accompagnateur_couleur.$row['Prenom'].'</FONT></TD>';
		echo '<TD bgcolor='.$trcolor.'><FONT face=verdana size=2>'.$Accompagnateur_couleur.$row['Nom'].'</FONT></TD>';
		echo '</TR>';
	}	
	echo '<TR></TR></TABLE>';
	fCOM_address_bottom();
	exit();
}


if ( isset( $_GET['action'] ) AND $_GET['action']=="accompagnateur_retirer_session") {
//if ($action == "accompagnateur_retirer_session"){
	Global $eCOM_db;
	$debug = false;
	$requete = 'SELECT * FROM QuiQuoi WHERE Individu_id="'.$_GET['Individu_id'].'" and Activite_id='.$_SESSION["Activite_id"].' and Engagement_id=0 and QuoiQuoi_id=2 and Session = "'.$_SESSION["Session"].'"'; 
	debug($requete . "<BR>\n");
	$result = mysqli_query($eCOM_db, $requete);
	if (mysqli_num_rows($result) >= 1 && $_SESSION["Session"] <> "All") {
		$row = mysqli_fetch_assoc($result);
		$requete = 'Delete FROM QuiQuoi WHERE id='.$row['id'].' '; 
		pCOM_DebugAdd($debug, "Common:accompagnateur_retirer_session - requete=".$requete);
		$result = mysqli_query($eCOM_db, $requete); 
		if (!$result) {
			echo '<B><CENTER><FONT face="verdana" size="2" color=red>Impossible d\'exécuter la requête : '.mysqli_error($eCOM_db).'</FONT></CENTER></B>';
		} else {
			echo '<B><CENTER><FONT face="verdana" size="2" color=green>Accompagnateur retiré de l\'équipe</FONT></CENTER></B>';
		}
	}
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit;
}

if ( isset( $_GET['action'] ) AND $_GET['action']=="accompagnateur_ajouter_session") {
//if ($action == "accompagnateur_ajouter_session"){
	Global $eCOM_db;
	$debug = false;
	$requete = 'INSERT INTO QuiQuoi (Individu_id, Activite_id, QuoiQuoi_id, Engagement_id, Session) VALUES ('.$_GET['Individu_id'].', '.$_SESSION["Activite_id"].', 2, 0, "'.$_SESSION["Session"].'")'; 
		pCOM_DebugAdd($debug, "Common:accompagnateur_ajouter_session - requete=".$requete);
	mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
	echo '<B><CENTER><FONT face="verdana" size="2" color=green>';
	echo "Accompagnateur ajoutée à l'équipe ".$_SESSION["Session"]." ";
	echo '</FONT></CENTER></B>';
	echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
	exit;
}



//=========================================================
// liste des prochaines rencontres
//=========================================================

if ( isset( $_GET['action'] ) AND $_GET['action']=="rencontres") {
//if ($action == "rencontres") {
	Global $eCOM_db;
	$debug = false;
	pCOM_DebugAdd($debug, "Action=".$_GET['action']." ... en cours");
	
	address_top();
	//$_SESSION["RetourPage"]=$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	$_SESSION["RetourPage"]=$_SERVER['PHP_SELF'].'?action=rencontres';

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Rencontres</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';

	echo '<TABLE>';
	$trcolor = "#EEEEEE";
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2> </FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Date</FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2> </FONT></TH>';
	echo '<TH bgcolor='.$trcolor.'><FONT face=verdana size=2>Intitulé</FONT></TH>';
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT * FROM Rencontres where Activite_id='.$_SESSION["Activite_id"].' and Date < "'.date("Y-m-d H:i:s").'" ORDER BY Date';
	} else {
		$requete = 'SELECT * FROM Rencontres where Activite_id='.$_SESSION["Activite_id"].' and Session = "'.$_SESSION["Session"].'" and Date < "'.date("Y-m-d H:i:s").'" ORDER BY Date';
		
	}
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		echo '<TR>';
		if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
			echo '<TD bgcolor='.$trcolor.'><CENTER>';
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=rencontres&id='.$row['id'].'><img src="images/edit.gif" border=0 alt="Mofifier Record"></A>  ';
			echo '</CENTER></TD>';
		} else {
			echo '<TD></TD>';
		}
		echo '<TD width=120 bgcolor='.$trcolor.'><font face=verdana color=#919191 size=2>';
		echo strftime("%d/%m/%y  %H:%M", sqlDateToOut($row['Date']));
		echo '</TD>';
		if ($row['Classement'] == "Préparation") {
			echo '<TD WIDTH=10></TD><TD width=600 bgcolor='.$trcolor.'><font face=verdana color=#919191 size=1><I>'.$row['Intitule'].'</I></TD>';
		} else {
			echo '<TD colspan=2 width=600 bgcolor='.$trcolor.'><font face=verdana color=#919191 size=2>'.$row['Intitule'].'</TD>';
		}
		echo '</TR>';
	}
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT * FROM Rencontres where Date >= "'.date("Y-m-d H:i:s").'" and Activite_id='.$_SESSION["Activite_id"].' ORDER BY Date';
	} else {
		$requete = 'SELECT * FROM Rencontres where Session = "'.$_SESSION["Session"].'" and Date >= "'.date("Y-m-d H:i:s").'" and Activite_id='.$_SESSION["Activite_id"].' ORDER BY Date';
	}
	$result = mysqli_query($eCOM_db, $requete);
	while($row = mysqli_fetch_assoc($result)){
		$trcolor = usecolor();
		echo '<TR>';
		if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
			echo '<TD bgcolor='.$trcolor.'><CENTER>';
			echo '<A HREF='.$_SERVER['PHP_SELF'].'?action=rencontres&id='.$row['id'].'><img src="images/edit.gif" border=0 alt="Mofifier Record"></A>  ';
			echo '</CENTER></TD>';

		} else {
			echo '<TD></TD>';
		}
		echo '<TD width=120 bgcolor='.$trcolor.'><font face=verdana color=#000000 size=2>';
		echo strftime("%d/%m/%y  %H:%M", fCOM_sqlDateToOut($row['Date']));
		echo '</TD>';
		if ($row['Classement'] == "Préparation") {
			echo '<TD WIDTH=10></td><td width=600 bgcolor='.$trcolor.'><font face=verdana color=#000000 size=1><I>'.$row['Intitule'].'</I></TD>';
		} else {
			echo '<TD colspan=2 width=600 bgcolor='.$trcolor.'><font face=verdana color=#000000 size=2>'.$row['Intitule'].'</TD>';
		}
		echo '</TR>';
	}	
	echo '<TR></TR></TABLE>';
	
	// formulaire de saisie
	//---------------------
	if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30) {
		
		if (isset($_GET['id'])) {$id = $_GET['id'];} else {$id = 0;}
		
		echo '<FORM method=post action='.$_SERVER['PHP_SELF'].'>';
		echo '<P><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF">';
		echo '<TR BGCOLOR="#F7F7F7">';
		if ( $id > 0 ) {
			echo '<TD COLSPAN="5"><FONT FACE="Verdana" SIZE="2"><BR><B>Modification d\'une rencontre</B></TD>';
		} else {
			echo '<TD COLSPAN="5"><FONT FACE="Verdana" SIZE="2"><BR><B>Saisie d\'une nouvelle rencontre</B></TD>';
		}
		echo '</TR>';
		echo '<TR>';
		echo '<TD width="100" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Session:</U></FONT></B></TD>';
		echo '<TD width="120" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Date:</U></FONT></B></TD>';
		echo '<TD width="120" bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Heure:</U></FONT></B></TD>';
		echo '<TD bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Classement:</U></FONT></B></TD>';
		echo '<TD bgcolor="#eeeeee"><FONT FACE="Verdana" SIZE="2"><U>Intitulé:</U></FONT></B></TD>';
		echo '</TR>';
		
		echo '<TR>';
		echo '<TD bgcolor="#eeeeee">';
		echo '<SELECT name="Session">';
		for ($i=2006; $i<=(intval(date("Y"))+5); $i++) {
			if (($row['Session'] != "" && $i == intval($row['Session'])) || ($row['Session'] == "" && $i == intval($_SESSION["Session"]))) {
				echo '<option value="'.$i.'" selected="selected">'.($i-1).' - '.$i.'</option>';
			} else {
				echo '<option value="'.$i.'">'.($i-1).' - '.$i.'</option>';
			}
		}
		echo '</SELECT></TD>';

		if ( $id > 0 )
		{
			$requete = 'SELECT * FROM Rencontres where id='.$_GET['id'].'';
			$result = mysqli_query($eCOM_db, $requete);
			while($row = mysqli_fetch_assoc($result))
			{
				$DateYear=substr($row['Date'],0,4);
				$DateMonth=substr($row['Date'],5,2);
				$DateDay=substr($row['Date'],8,2);
				$_DateRencontre = $DateDay."/".$DateMonth."/".$DateYear;
				$_HeureRencontre = substr($row['Date'],11,2);
				$_MinuteRencontre = substr($row['Date'],14,2);
				$_Classement = $row['Classement'];
				$_Intitule = $row['Intitule'];
			}
		} else {
			$_DateRencontre = "";
			$_HeureRencontre = "";
			$_MinuteRencontre = "";
			$_Classement = "";
			$_Intitule = "";
		}
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<input type=text id="DateRencontre" name="DateRencontre" size="8" maxlength="10" value="'.$_DateRencontre.'">';
		?>	
		<a href="javascript:popupwnd('calendrier.php?idcible=DateRencontre&langue=fr','no','no','no','yes','yes','no','50','50','470','400')" target="_self"><img src="images/calendrier.gif" id="Image1" alt="" border="0" style="width:20px;height:20px;"></a></span>
		<?php
		echo '</TD>';
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<B><FONT FACE="Verdana" SIZE="2"></FONT></B>';
		echo '<SELECT name="heure">';
		for ($i=0; $i<=23; $i++) {
			if (sprintf("%02d", $i) == sprintf("%02d", $_HeureRencontre)) {
				echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
			} else {
				echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
			}
		}
		echo '</SELECT><FONT FACE="Verdana" SIZE="2">h</FONT>';
		echo '<SELECT name="minute">';
		for ($i=0; $i<=45; $i=$i+15) {
			if (sprintf("%02d", $i) == sprintf("%02d", $_MinuteRencontre)) {
				echo '<option value="'.sprintf("%02d", $i).'" selected="selected">'.sprintf("%02d", $i).'</option>';
			} else {
				echo '<option value="'.sprintf("%02d", $i).'">'.sprintf("%02d", $i).'</option>';
			}
		}
		echo '</SELECT>';
		echo '</TD>';
		
		// Classement
		echo '<TD bgcolor="#eeeeee">';
		echo '<SELECT name="Classement">';
		//echo'<option value="?">?</option>';
		if ( $_Classement == "Parcours" ) {
			echo '<option value="Parcours" selected="selected">Parcours</option>';
		} else {
			echo '<option value="Parcours">Parcours</option>';
		}
		if ( $_Classement == "Préparation" ) {
			echo '<option value="Préparation" selected="selected">Préparation</option>';
		} else {
			echo '<option value="Préparation">Préparation</option>';
		}
		echo '</SELECT></TD>';
		
		echo '<TD bgcolor="#eeeeee">';
		echo '<INPUT type=text name="Intitule" size="50" maxlength="100" value="'.$_Intitule.'"></TD></TR>';
		echo '<TR><TD COLSPAN="2">';
		echo '<INPUT type="submit" name="rencontre_sauvegarder" value="Enregistrer">';
		echo '<INPUT type="reset" name="Reset" value="Reset">';
		if ( $id > 0 ) {
			echo '<INPUT type="submit" name="rencontre_supprimer" value="Supprimer">';
		}
		echo '<INPUT type="hidden" name="id" value='.$id.'>';
		echo '<BR></TD></TR>';
		echo '</TABLE></P></FORM>';

	} 
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();
}

//--------------------------------------------------------------------------------------
//delete one rencontre by id
//--------------------------------------------------------------------------------------

if ( isset( $_POST['rencontre_supprimer'] ) AND $_POST['rencontre_supprimer']=="Supprimer") {
//if ( $rencontre_supprimer ) {
	Global $eCOM_db;
	$debug = false;
	$requete = 'SELECT * FROM Rencontres WHERE id='.$_POST['id'].' '; 
	pCOM_DebugAdd($debug, "Common:rencontre_supprimer - requete =".$requete);
	$result = mysqli_query($eCOM_db, $requete);
    pCOM_DebugAdd($debug, "Common:rencontre_supprimer - Enreg dans la table ".mysqli_num_rows( $result ));

	address_top();

	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">';
	echo '<TR BGCOLOR="#F7F7F7">';
	echo '<TD><FONT FACE="Verdana" SIZE="2"><B>Database Rencontre: Suppression d\'une rencontre</B><BR>';
	echo '</TD></TR>';
	echo '<TR><TD BGCOLOR="#EEEEEE">';
	echo '<FONT FACE="verdana" color="#313131" size="2">Etes-vous certain de vouloir supprimer cette rencontre ? </FONT><BR><BR>';

	while($row = mysqli_fetch_assoc($result))
	{
		echo "<font face=verdana color=#313131 size=2>";
		echo strftime("%d/%m/%y  %H:%M    ", fCOM_sqlDateToOut($row['Date']));
		echo " - ";
		echo "<font face=verdana color=#313131 size=2>".$row['Intitule']."</font>";
	}
	
	echo '<P><FORM method=post action='.$_SERVER['PHP_SELF'].'>';
	echo '<INPUT type="submit" name="rencontre_delete" value="Oui">';
	echo '<INPUT type="submit" name="rencontre_delete" value="Non">';
	echo '<INPUT type="hidden" name="id" value='.$_POST['id'].'>';
	echo '</FORM>';
	
	fCOM_address_bottom();
	mysqli_close($eCOM_db);
	exit();	
}


if ( isset( $_POST['rencontre_delete'] ) AND $_POST['rencontre_delete']=="Oui") {
//if ( $rencontre_delete ) {
	Global $eCOM_db;
	$debug = False;
	$Delay = "0";
	if ( $_POST['rencontre_delete'] == "Oui" )
	{
		if (fCOM_Get_Autorization($_SESSION["Activite_id"])>= 30)
		{
			$requete = 'DELETE FROM Rencontres WHERE id='.$_POST['id'].' '; 
			pCOM_DebugAdd($debug, "Common:rencontre_delete - requete =".$requete);
			$result = mysqli_query($eCOM_db, $requete); 
			if (!$result) {
				echo '<B><CENTER><FONT face="verdana" size="2" color=red>';
				echo 'Impossible de retirer la rencontre : '.mysqli_error($eCOM_db);
				echo '</FONT></CENTER></B>';
			} else {
				echo '<B><CENTER><FONT face="verdana" size="2" color=green>Rencontre supprimée avec succès</FONT></CENTER></B>';
			}
		} else {
			echo '<B><CENTER><FONT face="verdana" size="2" color=red>';
			echo 'Impossible de retirer la rencontre : pas de droit accordé</FONT></CENTER></B>';
		}
		$Delay = "2";
	}
	echo '<META http-equiv="refresh" content="'.$Delay.'; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}


if ( isset( $_POST['rencontre_sauvegarder'] ) AND $_POST['rencontre_sauvegarder']=="Enregistrer") {
//if ($rencontre_sauvegarder) {
	Global $eCOM_db;
	if (fCOM_Get_Autorization($_SESSION["Activite_id"]) <= 20)
	{
		echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
		mysqli_close($eCOM_db);
		exit;
	}
	
	$debug = false;
	pCOM_DebugAdd($debug, "Common:rencontre_sauvegarder Date is " .$_POST['DateRencontre']);
	$DateRencontre = $_POST['DateRencontre'];
	pCOM_DebugAdd($debug, "Common:rencontre_sauvegarder Session = " .$_POST['Session']);
	pCOM_DebugAdd($debug, "Common:rencontre_sauvegarder Date is " .$_POST['DateRencontre']);
	
	$DateTimeValue = fCOM_getSqlDate($_POST['DateRencontre'],$_POST['heure'],$_POST['minute'],0);
	pCOM_DebugAdd($debug, "Common:rencontre_sauvegarder - DateTimeValue=".$DateTimeValue);
	if ($DateTimeValue == NULL) {
		pCOM_DebugAlert(True, "Erreur dans la saisie de date: Pas de sauvegarde, recommencez la saisie");
		echo '<META http-equiv="refresh" content="0; URL='.$_SESSION["RetourPage"].'">';
		mysqli_close($eCOM_db);
		exit;
	}
	$Activite_id=$_SESSION["Activite_id"];

	if (isset($_POST['id'])) {$id = $_POST['id'];} else { $id = 0;}
	if ( $id > 0 ) {
		mysqli_query($eCOM_db, 'UPDATE Rencontres SET Activite_id="'.$Activite_id.'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Rencontres SET Session="'.$_POST['Session'].'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Rencontres SET Date="'.$DateTimeValue.'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Rencontres SET Classement="'.$_POST['Classement'].'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		mysqli_query($eCOM_db, 'UPDATE Rencontres SET Intitule="'.$_POST['Intitule'].'" WHERE id='.$id.'') or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Rencontre modifiée avec succès</FONT></CENTER></B>';
	} else {
		$requete = 'INSERT INTO Rencontres (Activite_id, Session, Date, Classement, Intitule, Lieux_id) VALUES ("'.$Activite_id.'","'.$_POST['Session'].'", "'.$DateTimeValue.'", "'.$_POST['Classement'].'", "'.$_POST['Intitule'].'", 0)';
		pCOM_DebugAdd($debug, "Common:rencontre_sauvegarder - requete=".$requete);
		mysqli_query($eCOM_db, $requete) or die (mysqli_error($eCOM_db));
		echo '<B><CENTER><FONT face="verdana" size="2" color=green>Rencontre ajoutée avec succès</FONT></CENTER></B>';
	}

	echo '<META http-equiv="refresh" content="1; URL='.$_SESSION["RetourPage"].'">';
	mysqli_close($eCOM_db);
	exit;
}



