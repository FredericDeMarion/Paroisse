<?php

//==================================================================================================
//    Nom du module : templateMariage.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


//$Liste_Accompagnateurs = array("A affecter", "BUIS", "COMTE", "COUPE", "DONAT", "DORTAN", "DUPREZ", "FOX", "GUIBERT", "MARION", "PELLEGRIN", "RIDEAU", "VRIGNAULT", "ROCA", "THIEFFRY", "OGDEN", "PLAULT", "BILLE", "Autre", "Prépa. Ext.", "Annulé/Reporté", "WE", "WE Annulé", "CANA WE", "CANA WE Annulé");
//$Liste_Accompagnateurs = array("A affecter", "BUIS", "CORNU", "COUPE", "DONAT", "DORTAN", "FOX", "GUIBERT", "MARION", "MOERMAN", "PELLEGRIN", "RIDEAU", "ROCA", "THIEFFRY", "GAUTHIER", "OGDEN", "PLAULT", "BILLE", "Autre", "Prépa. Ext.", "Annulé/Reporté", "WE", "WE Annulé", "CANA WE", "CANA WE Annulé");
$Liste_Confessions = array("Confession ?", "Sans", "Catéchumène", "Catholique", "Orthodoxe", "Protestant", "Musulman", "Juif", "Bouddhiste", "Autre");

function debug_plus($ch) {
	?><SCRIPT language=javascript>
		alert('<?php print $ch; ?>')
	</SCRIPT><?php

}

// Base QuiQuoi.Activite
// 1 - Pretre
// 2 - Accompagnateur Fiances preparation mariage
// 4 - Diacre
// 8 - Accompagnateur Bapteme

function get_liste_accompagnateurs() {
	Global $eCOM_db;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite=2 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Session=' . $_SESSION["Session"] . ' and Activite&2>0 ORDER by Accompagnateur';
	}
	$result = mysqli_query( $eCOM_db, $requete);
	$Liste_Accompagnateurs = array("A affecter");
	$Item = 1;
	while($row = mysqli_fetch_assoc($result)){
		$Liste_Accompagnateurs[$Item]=$row[Accompagnateur];
		$Item =$Item + 1;
	}
	$Liste_Accompagnateurs[$Item]="PLAULT";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="BILLE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Autre";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Prépa. Ext.";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="Annulé/Reporté";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="WE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="WE Annulé";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="CANA WE";
	$Item =$Item + 1;
	$Liste_Accompagnateurs[$Item]="CANA WE Annulé";
	$Item =$Item + 1;
	return $Liste_Accompagnateurs;
}

function get_liste_celebrants_Mariage() {
	Global $eCOM_db;
	$Liste_Celebrants = array("Autre ->"); // definit affichage par defaut - ici "Autre ->"
	$Item = 1;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT T1.`id`, T1.`Nom`, T1.`Prenom` 
					FROM `Individu` T1 
					WHERE T1.`Diacre`=1 OR T1.`Pretre`=1
					ORDER BY T1.`Prenom`, T1.`Nom`';
	} else {
		$requete = 'SELECT DISTINCT T1.`id`, T1.`Nom`, T1.`Prenom` 
					FROM `Individu` T1 
					WHERE T1.`Actif`=1 AND T1.`Dead`=0 AND (T1.`Diacre`=1 OR T1.`Pretre`=1) 
					ORDER BY T1.`Prenom`, T1.`Nom`';
	}
	$result = mysqli_query( $eCOM_db, $requete);
	$Liste_Celebrants["Id"][$Item]=0;
	$Liste_Celebrants["Prenom"][$Item]="";
	$Liste_Celebrants["Nom"][$Item]="En attente";
	$Item =$Item + 1;
	while($row = mysqli_fetch_assoc($result)){
		$Liste_Celebrants["Id"][$Item]=$row['id'];
		//$Liste_Celebrants[$Item]=$row[Prenom].' '.$row[Nom];
		$Liste_Celebrants["Prenom"][$Item]=$row['Prenom'];
		$Liste_Celebrants["Nom"][$Item]=$row['Nom'];
		$Item =$Item + 1;
	}
	//debug_plus('templateMariage.php celebrants are = "'.$Liste_Celebrants[1].'"');
	$Liste_Celebrants["Id"][$Item]=0;
	$Liste_Celebrants["Prenom"][$Item]="";
	$Liste_Celebrants["Nom"][$Item]="Célébrant Extérieur";
	$Item =$Item + 1;
	return $Liste_Celebrants;
}


//list all table 
function list_top ()
{
	?>	
		<table border=0 cellpadding=2 cellspacing=1 border=0 width=100%>
		<tr><td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Actions</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Accompagnateurs:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>LUI Prenom:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Nom:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>ELLE Prenom:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Nom:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Celebrant:</CENTER></B></font></td>
		<td bgcolor=#CCCCCC><font face=verdana size=2><B><CENTER>Date:</CENTER></B></font></td>
<?php
}



//header for all pages
function address_top () 
{

if(empty($_SESSION["Session"])) {
	// Re-initialiser la valeur de $Session en revenant a l'accueil
	echo '<META http-equiv="refresh" content="1; URL=/index.php">';
	exit;
}

global $SessionEnCours;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<TITLE> Database Mariage </TITLE>
<meta name="generator" content="WYSIWYG Web Builder - http://www.wysiwygwebbuilder.com">
<script language="JavaScript" type="text/javascript">
<!--
function popupwnd(url, toolbar, menubar, locationbar, resize, scrollbars, statusbar, left, top, width, height)
{
   var popupwindow = this.open(url, '', 'toolbar=' + toolbar + ',menubar=' + menubar + ',location=' + locationbar + ',scrollbars=' + scrollbars + ',resizable=' + resize + ',status=' + statusbar + ',left=' + left + ',top=' + top + ',width=' + width + ',height=' + height);
}
//-->
</script>
<link rel="stylesheet" href="css/style_titre.css" type="text/css" media="screen" />
<link rel="icon" type="image/png" href="logo.png" />
</HEAD>
<BODY BGCOLOR="#FFFFFF" link="blue" vlink="blue" alink="blue">
<CENTER>
<!-- Provided by: Ross Poulton -->
<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin
	function showProfile(nom_fiance, email) {
		var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=400,height=350";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=profile&nom_fiance=" + nom_fiance +"&email=" + email, "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php
Global $eCOM_db;
$debug = false;
//debug("Session(template)=".$Session . "<BR>\n");
//debug("SessionEnCours(template)=".$SessionEnCours . "<BR>\n");

$levelAutorisation = fCOM_Get_Autorization($_SESSION["Activite_id"]);

// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query( $eCOM_db, $sql3);
$count_user_online=mysqli_num_rows($result3);
?>
<style type="text/css">
#Titre { width: 100%; height: 90px; display:inline; background-color:white; color: white; position: fixed; z-index: 2; top: 0px; left: 0px; right: 0px; }
</style>
<style type="text/css">
#Corps { width: 100%; height: 190px; display:inline; color: white; position: relative; top: 90px; left: 0px; right: 0px; }
</style>

<div id="Titre">
<TABLE WIDTH="98%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000">
	<TR>
		<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#FFFFFF">
			<TR BGCOLOR="#F7F7F7">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR>
				<TD><FONT FACE="Verdana" SIZE="2"><B>Préparation Mariage <?php echo "$SessionEnCours" ?> </B>
				<?php
				$phpmyvisites_Title = "Mariage";
				include 'phpmyvisites.php';
				?>
				<BR>
				<FONT FACE="Verdana" SIZE="1">
				<?php
				
				require("Login/sqlconf.php");
				if ($_SESSION["Session"]=="All") {
					$result = mysqli_query( $eCOM_db, "SELECT * FROM ".$Table." ");
				} else {
					$result = mysqli_query( $eCOM_db, "SELECT * FROM ".$Table." WHERE Session = ".$_SESSION["Session"]." ");
				}
				
				$num_total = mysqli_num_rows($result);
				echo("Il y a " . $num_total . " fiches renseignées dans la base actuellement.</FONT></TD>\n"); 
				echo '<TD ALIGN="right">';
				$result = Afficher_compteur_de_login();
				?>
				</TR></TABLE></TD>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
				<?php
				if ($_SERVER['USER'] <= 3 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 20) {
				?>
					<li class="menu_right"><a href="#" class="drop">Fiancés &nbsp &nbsp &nbsp</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'">Liste des Fiancés</a></li>';
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=trombinoscope">Trombinoscope</a></li>';
									if ($_SERVER['PHP_AUTH_USER'] == "administrateur" || $_SERVER['PHP_AUTH_USER'] == "comptable" || $_SERVER['PHP_AUTH_USER'] == "gestionnaire"|| fCOM_Get_Autorization( $_SESSION["Activite_id"] ) >= 30) {
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?action=edit_Individu&id=0">Ajouter un Paroissien</a></li>';
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=edit&id=0">Ajouter des fiancés</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Accompagnateurs</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=list_accomp">Liste</a></li>';
									if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
										echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=Configuration_Accompagnateur&id=0">Ajouter et retirer</a></li>';
									}
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Rencontres</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=rencontres">Liste</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Divers</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									//echo '<li><a href="'.$_SERVER['PHP_SELF'].'.php?Session='.$SessionEnCours.'&action=printall">Imprimer fiches fiancés</a></li>';
									echo '<li><a href="http://nd.sagesse.free.fr/Celebration">Préparation célébration</a></li>';
									echo '<li><a href="/Chants_Mariage/index.htm">Chants célébration</a></li>';
									echo '<li><a href="Reference/Carnet de note V6.pdf">Questionnaire du livret</a></li>';
									//echo '<li><a href="/Formulaires">Formulaires d\'intention</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					if ($_SERVER['USER'] <= 2 || fCOM_Get_Autorization( $_SESSION["Activite_id"] )>= 30) {
					?>
					<li class="menu_right"><a href="#" class="drop">Compta</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="'.$_SERVER['PHP_SELF'].'?Session='.$SessionEnCours.'&action=vue_financiere">Etat participation financière</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<?php
					}
				}
					?>
					<li class="menu_right"><a href="#" class="drop">Accueil</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<li><a href="index.php">Retour menu principal</a></li>
								</ul>
							</div>
						</div>
					</li>
				</ul>
				</TD>
				<TD ALIGN="right" width="300px" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : <?php echo '<A HREF="/Login/logout.php">'.$_SERVER['PHP_AUTH_USER'].'</A><BR>Utilisateur(s) connecté(s) : '.$count_user_online.''; ?>
				</TR></TABLE></TD>
			</TR>
		</TABLE></TD>
	</TR>
</TABLE>
</div>
<P>
<div id="Corps">
<TABLE WIDTH="99%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#000000"><TR><TD>	

<?php
}

//footer all pages
function address_bottom () {

	echo '<TR BGCOLOR="#DDDDDD">';
	echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="1" CELLPADDING="0" BGCOLOR="#DDDDDD">';
	echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].' &nbsp</TD></TR>';
	echo '</DIV></TABLE>';
	echo '</TR>';
	
	echo '</TABLE>';
	
	echo '</TD></TR>';
	echo '</TABLE>';

	echo '</BODY>';
	echo '</HTML>';

}
