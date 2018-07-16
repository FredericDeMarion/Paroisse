<?php
session_start();

//==================================================================================================
//    Nom du module : Evenements.php développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================
// 21/05/2018 : premiere version
//==================================================================================================

require("Login/sqlconf.php");
$eCOM_db = mysqli_connect( $sqlserver , $login , $password, $sqlbase ) or die("Common: Cannot connect MySql : " . mysqli_error());
setlocale (LC_TIME, 'fr_FR','fra'); 


//=========================================================
//=========================================================
//=========================================================
// Liste des Annonces
//=========================================================
//=========================================================
//=========================================================
if ( ! isset($_GET['church'])) {exit;};
if ( ! isset($_GET['year'])) {exit;};
if ( ! isset($_GET['month'])) {exit;};
if ( ! isset($_GET['day'])) {exit;};

if ( isset( $_GET['action'] ) AND $_GET['action']=="getList") {

	if (isset($_GET['debug']) AND $_GET['debug']=="true") {
		header( 'content-type: text/html; charset=UTF-8' );
		echo '<!DOCTYPE HTML>';
		echo '<HTML><HEAD>';
		echo '<TITLE>Annonces de '.$_GET['church'].'</TITLE>';
		echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		setlocale (LC_TIME, 'fr_FR','fra');	
		mb_internal_encoding('UTF-8');
		echo '</HEAD>';
		echo '<BODY>';

	}

	$DateCourante=$_GET['year'].'/'.sprintf('%02d', $_GET['month']).'/'.sprintf('%02d', $_GET['day']);
	
	Global $eCOM_db;
	$debug = false;
	
	$requete = 'SELECT T0.`id`, T0.`DateDeb`, T0.`DateFin`, T0.`Lieu_id`, T0.`Annonce_texte`, T1.`Lieu`
				FROM Annonces T0
				LEFT JOIN Lieux T1 ON T1.`id`=T0.`Lieu_id`
				WHERE T0.`DateDeb`<="'.$DateCourante.'" AND T0.`DateFin`>="'.$DateCourante.'" AND (Lieu="'.$_GET['church'].'" OR Lieu_id=0)
				ORDER BY T0.`DateFin`';
	if (isset($_GET['debug']) AND $_GET['debug']=="true") {
		echo 'Date souhaitée='.$DateCourante.', clocher='.$_GET['church'].'<BR><BR>';
		echo $requete.'<BR><BR>';
	}
	$result = mysqli_query($eCOM_db, $requete);
	$Item = 1;
	$Get_liste_Annonces[$Item] = array("Texte de l\'annonce", "Adresse pour récupérer le flyer");
	while($row = mysqli_fetch_assoc($result)){

		$Annonce_texte=$row['Annonce_texte'];
		$Annonce_flyer="";
		if (file_exists($_SERVER['SERVER_NAME']."/images/Annonces/Annonce_".$row['id'].".jpg")) { 
			$Annonce_flyer=$_SERVER['SERVER_NAME']."/images/Annonces/Annonce_".$row['id'].".jpg";
		}
		$Item =$Item + 1;
		$Get_liste_Annonces[$Item] = array($Annonce_texte, $Annonce_flyer);
	if (isset($_GET['debug']) AND $_GET['debug']=="true") {
			echo $Annonce_texte.'<BR>';
			echo $Annonce_flyer.'<BR><BR>';
		}
		
	}
	return $Get_liste_Annonces;
	if (isset($_GET['debug']) AND $_GET['debug']=="true") {
		echo '</BODY>';
	}
	exit();
}


