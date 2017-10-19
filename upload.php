<?php

  //$tmp_name=basename($_FILES['avatar']['tmp_name']); 
  //$name=basename($_FILES['avatar']['name']); 
  //$size=basename($_FILES['avatar']['size']); 
  //$type=basename($_FILES['avatar']['type']); 
  //$erreur=basename($_FILES['avatar']['error']); 
   
  //On affiche les différentes variables 
   
  //echo "Nom du fichier :".$name; 
  //echo "<br>Taille du fichier :".$size; 
  //echo "<br>Type de fichier :".$type; 
  //echo "<br>Nom temporaire :".$tmp_name; 
  //echo "<br>Erreur avant le move :".$erreur; 

$UpdVAR['DIR']	= "load/";
$dossier =$_SERVER['DOCUMENT_ROOT']."/Photos/"; //."/"; // ='Photos/';
$dossier ="Photos/"; //."/"; // ='Photos/';
$fichier = basename($_FILES['avatar']['name']);
$fichier_tmp = basename($_FILES["avatar"]["tmp_name"]);
//$fichier_target = $id.".jpg"; Déjà initialisé avant appel
$taille_maxi = 500000;
$taille = filesize($_FILES['avatar']['tmp_name']);
$extensions = array(0=>'.jpg', '.jpeg', '.pjpeg');
//$extension = basename($_FILES['avatar']['type']); //
//$extension = strrchr($_FILES['avatar']['name'], '.'); 
$extension = strtolower(strrchr($_FILES['avatar']['name'], "."));
//Début des vérifications de sécurité...


if(!in_array($extension, $extensions)) { //Si l'extension n'est pas dans le tableau
	$fichier = basename($_FILES['avatar']['name']);
	$erreur = 'Vous devez sélectionner un fichier de type ".jpg" ou ".jpeg" <BR>Or, l\'extension du fichier '.$fichier.' est : "'.$extension.'" ';
}
if($taille>$taille_maxi) {
     $erreur = 'Le fichier est trop gros, sa taille ('.$taille.') dépasse le max : '.$taille_maxi.'';
}

if(!isset($erreur)) //pas d'erreur, on upload
{
	//echo '<meta http-equiv="refresh" content="0;URL=http://frederic.de.marion.free.fr/Parcours_Alpha.php?Session='.$SessionEnCours.'&action=edit_Individu&id='.$id.'>';

     //On formate le nom du fichier ici...
     $fichier = strtr($fichier, 
          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜİàáâãäåçèéêëìíîïğòóôõöùúûüıÿ', 
          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
     //$fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
     
     //$fichier_target = $dossier.$fichier_nom;
     //chmod("/",0777);
     //chmod($dossier,0777);
     $UpdSEND=$UpdVAR['DIR'].basename($_FILES['avatar']['name']);
     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $UpdSEND)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné... was move_uploaded_file($fichier, $dossier.$fichier_nom)
     {
        rename($UpdSEND, $dossier.$_POST['fichier_target']);
		$erreur = "Photo récupérée avec succès !";
		
	 } else { //Sinon (la fonction renvoie FALSE).
		echo "Echec du chargement de la photo ".$fichier_tmp." vers ".$UpdSEND." ! erreur=". $_FILES['avatar']['error']." ";
		if ($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE) {
			$erreur = "<BR>Fichier manquant"; }
		elseif  ($_FILES['avatar']['error'] == UPLOAD_ERR_INI_SIZE) {
			$erreur = "<BR>Fichier dépassant la taille maximale autorisée"; }
		elseif  ($_FILES['avatar']['error'] == UPLOAD_ERR_FORM_SIZE) {
			$erreur = "<BR>Fichier dépassant la taille maximale autorisée"; }
		elseif  ($_FILES['avatar']['error'] == UPLOAD_ERR_PARTIAL) {
			$erreur = "<BR>Fichier transféré partiellement";	}
		else {
			$erreur = "<BR>Fichier non transféré"; }
     }
}
echo $erreur;

//echo '<META http-equiv="refresh" content="3; URL=https://'.$_SESSION["RetourPageCourante"].'">';
?><script language="JavaScript" type="text/javascript"><!--
setTimeout("window.history.go(-2)",3000);
//--></script><?php

//echo '<meta http-equiv="refresh" content="3; URL=javascript:history.go(-2)">';//echo '<meta 
mysql_close();
exit;

?>
