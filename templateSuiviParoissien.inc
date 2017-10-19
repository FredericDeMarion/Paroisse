<?php

//==================================================================================================
//    Nom du module : templateSuiviParoissien.inc développé par Frédéric de Marion - frederic.de.marion@free.fr
//--------------------------------------------------------------------------------------------------
//  Version |    Date    | Commentaires
//--------------------------------------------------------------------------------------------------
//    V1.00 | 12/04/2017 | Version originale
//==================================================================================================


$Liste_Confessions = array("Confession ?", "Sans", "Catéchumène", "Catholique", "Orthodoxe", "Protestant", "Musulman", "Juif", "Bouddhiste", "Autre");
$Liste_Genre = array(" ", "F", "M");

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
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite=8 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Session=' . $_SESSION["Session"] . ' and Activite&8>0 ORDER by Accompagnateur';
	}
	$result = mysqli_query($eCOM_db, $requete);
	$Liste_Accompagnateurs = array("A affecter");
	$Item = 1;
	while($row = mysqli_fetch_aassoc($result)){
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

function get_liste_celebrants_testold() {
	Global $eCOM_db;
	$Liste_Celebrants = array("Autre ->"); // definit affichage par defaut - ici "Autre ->"
	$Item = 1;
	if ($_SESSION["Session"]=="All") {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE Activite&1>0 || Activite&4>0 ORDER by Accompagnateur';
	} else {
		$requete = 'SELECT DISTINCT Accompagnateur FROM QuiQuoi WHERE (Activite&1>0 || Activite&4>0) && Session=' . $_SESSION["Session"] . ' ORDER by Accompagnateur';
	}
	$result = mysqli_query($eCOM_db, $requete);
	$Liste_Celebrants[$Item]="En attente";
	$Item =$Item + 1;
	while($row = mysqli_fetch_assoc($result)){
		$Liste_Celebrants[$Item]=$row[Accompagnateur];
		$Item =$Item + 1;
	//	debug("celebrant acronyme=".$row[Acronyme] . "<BR>\n");
	//	sleep(5);
	}
	//debug_plus('templateMariage.php celebrants are = "'.$Liste_Celebrants[1].'"');
	$Liste_Celebrants[$Item]="Célébrant Extérieur";
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
	//Re-initialiser la valeur de $Session en revenant a l'accueil
	echo '<META http-equiv="refresh" content="1; URL=/index.php">';
	exit;
}

global $SessionEnCours;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> Database Paroissiens </TITLE>
<meta name="generator" content="WYSIWYG Web Builder - http://www.wysiwygwebbuilder.com">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" type="text/javascript">
<!--
function popupwnd(url, toolbar, menubar, locationbar, resize, scrollbars, statusbar, left, top, width, height)
{
   var popupwindow = this.open(url, '', 'toolbar=' + toolbar + ',menubar=' + menubar + ',location=' + locationbar + ',scrollbars=' + scrollbars + ',resizable=' + resize + ',status=' + statusbar + ',left=' + left + ',top=' + top + ',width=' + width + ',height=' + height);
}
//-->
</script>
<link rel="stylesheet" href="css/style_titre.css" type="text/css" media="screen" />
<link rel="icon" type="image/png" href="icone.png" />
</HEAD>
<BODY BGCOLOR="#FFFFFF" link="blue" vlink="blue" alink="blue" onload="document.refresh();">
<CENTER>
<!-- Provided by: Ross Poulton -->
<SCRIPT LANGUAGE="JavaScript">
	<!-- Begin
	function AjouterService(Individu_id, Quiquoi_id) {
		var windowprops = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=500,height=270";

		OpenWindow = window.open("<?php echo $_SERVER['PHP_SELF']; ?>?action=AjouterCeService&Individu_id=" + Individu_id + "&QuiQuoi_id=" + Quiquoi_id , "profile", windowprops); 
		
}
	//  End --> 
</script>

<?php
$debug = false;
Global $eCOM_db;
//debug("Session(template)=".$Session . "<BR>\n");
pCOM_DebugAdd($debug, "templateSuiviParoissien - SessionEnCours(template)=".$SessionEnCours);
$levelAutorisation = fCOM_Get_Autorization(0);

// compter le nombre de personne connecté
$sql3='SELECT * FROM Admin_user_online';
$result3=mysqli_query($eCOM_db, $sql3);
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
				<TD><FONT FACE="Verdana" SIZE="2"><B>Suivi des paroissiens - <?php echo $_SESSION['Session'] ?> </B>
				<?php
				$phpmyvisites_Title = "SuiviParoissien";
				include 'phpmyvisites.php';
				?>
				<BR><FONT FACE="Verdana" SIZE="1">
				<?php

				require("Login/sqlconf.php");
				$result = mysqli_query($eCOM_db, "SELECT T0.`id` FROM `Individu` T0");
				$num_total = mysqli_num_rows($result);
				//$result = mysql_query("SELECT T0.`id`, T0.`Services`, T0.`Ressourcements` FROM `Individu` T0 where T0.`Services`!=0 ");
				$result = mysqli_query($eCOM_db, "Select DISTINCT T0.Nom, T0.Prenom FROM Individu T0 LEFT JOIN QuiQuoi T1 on T1.Individu_id = T0.id LEFT JOIN Activites T2 on T2.id = T1.Activite_id WHERE T1.Engagement_id = 0 and T2.Service = 1 and T1.Session=".$_SESSION["Session"]." ORDER BY T0.Nom, T0.Prenom");
				$num_total_au_service = mysqli_num_rows($result);
				echo($num_total.' paroissiens, dont '.$num_total_au_service.' en service.</FONT></TD>'); 
				$Champs_to_all="ALL";
				echo '<TD ALIGN="right">';
				$result = Afficher_compteur_de_login();
				?>
				</TR></TABLE></TD>
			</TR>
			<TR BGCOLOR="#DDDDDD">
				<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
				<TR><TD>
				<ul id="menu">
					<li class="menu_right"><a href="#" class="drop">Paroissiens</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'">Rechercher</a></li>';
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'&any='.$Champs_to_all.'&all='.$Champs_to_all.'&none='.$Champs_to_all.'">Tous</a></li>';
									echo '<li><a href="SuiviParoissien.php?action=AfficherParoissiensParAge">Par Age</a></li>';
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'&action=edit_Individu&id=0">Ajouter un Paroissien</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
					<li class="menu_right"><a href="#" class="drop">Engagements</a><!-- classique -->
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<ul class="simple">
									<?php
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'&action=list_services">Au service</a></li>';
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'&action=list_ressourcements">En ressourcement</a></li>';
									echo '<li><a href="SuiviParoissien.php?Session='.$SessionEnCours.'&action=list_souhaits">Souhaits</a></li>';
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
									echo '<li><a href="SuiviParoissien.php?action=list_langue">Langue Maternelle</a></li>';
									?>
								</ul>
							</div>
						</div>
					</li>
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
	//echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : '.$_SERVER['PHP_AUTH_USER'].' &nbsp</TD></TR>';
	echo '<TR><TD HEIGHT="20" ALIGN="right" VALIGN="middle" ><FONT FACE="Verdana" SIZE="1">Connecté en tant que : </TD></TR>';
	echo '</DIV></TABLE>';
	echo '</TR>';
	
	echo '</TABLE>';
	
	echo '</TD></TR>';
	echo '</TABLE>';

	echo '</BODY>';
	echo '</HTML>';

}
